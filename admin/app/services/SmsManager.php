<?php
namespace Vokuro\Services;
use Vokuro\Models\Location;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
class SmsManager extends BaseService {

    public static $reviewPercentage = 10;

    public function __construct($config) {
        parent::__construct($config);
    }

    public function getTwilioKeys($AgencyID) {
        if(!$AgencyID)
            return [];
        $TwilioSID = '';
        $TwilioToken = '';
        $TwilioFrom = '';
        $TwilioAPI = '';

        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$AgencyID}");
        // Are we a business?
        if($objAgency->parent_id > 0) {
            // Return parent's keys.
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = " . $objAgency->parent_id);
            $TwilioSID = $objParentAgency->twilio_auth_messaging_sid;
            $TwilioToken = $objParentAgency->twilio_auth_token;
            // We use the businesses' from number if it exists, otherwise use the agency's.
            $TwilioFrom = $objAgency->twilio_from_phone ?: $objParentAgency->twilio_from_phone;
            $TwilioAPI = $objParentAgency->twilio_api_key;
        } elseif($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            // Business under RV.  Return default from config.
            $TwilioSID = $this->config->twilio->twilio_auth_messaging_sid;
            $TwilioToken = $this->config->twilio->twilio_auth_token;
            $TwilioFrom = $this->config->twilio->twilio_from_phone;
            $TwilioAPI = $this->config->twilio->twilio_api_key;
        }

        return [
            'twilio_auth_messaging_sid' => $TwilioSID,
            'twilio_auth_token'         => $TwilioToken,
            'twilio_from_phone'         => $TwilioFrom,
            'twilio_api_key'            => $TwilioAPI,
        ];
    }

    /**
     * @param int $reviewPercentage
     */
    public static function setReviewPercentage($reviewPercentage)
    {
        self::$reviewPercentage = $reviewPercentage;
    }

    public function getBusinessSmsQuotaParams($locationId) {

        $smsQuotaParams['hasUpgrade'] = false;

        /* Sms sent last month */
        $lastMonthStartTime = date("Y-m-d", strtotime("first day of previous month"));
        $lastMonthEndTime = date("Y-m-d 23:59:59", strtotime("last day of previous month"));

        $smsQuotaParams['smsSentLastMonth'] = ReviewInvite::query()
            ->columns("review_invite_id")
            ->where('date_sent >= :startTime:')
            ->andWhere('date_sent >= :endTime:')
            ->andWhere('location_id >= :locationId:')
            ->andWhere('sms_broadcast_id IS NULL')
            ->bind([
                "startTime" => $lastMonthStartTime,
                "endTime" => $lastMonthEndTime,
                "locationId" => $locationId
                ])
            ->execute()
            ->count();

        /* Sms sent this month */
        $thisMonthStartTime = date("Y-m-d", strtotime("first day of this month"));
        $thisMonthEndTime = date("Y-m-d 23:59:59", strtotime("last day of this month"));

        $smsQuotaParams['smsSentThisMonth'] = ReviewInvite::query()
            ->columns("review_invite_id")
            ->where('date_sent >= :startTime:')
            ->andWhere('date_sent >= :endTime:')
            ->andWhere('location_id >= :locationId:')
            ->andWhere('sms_broadcast_id IS NULL')
            ->bind([
                "startTime" => $thisMonthStartTime,
                "endTime" => $thisMonthEndTime,
                "locationId" => $locationId
                ])
            ->execute()
            ->count();

        /* Reviews sent last month */
        $smsQuotaParams['numReviewsLastMonth'] = ReviewsMonthly::sum(
            [
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = " . date("m", strtotime("first day of previous month")) . " AND year = '" . date("Y", strtotime("first day of previous month")) . "' AND location_id = " . $locationId,
            ]
        );

        /* Reviews sent 2 months ago */
        $smsQuotaParams['numReviewsTwoMonthsAgo'] = ReviewsMonthly::sum(
            [
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = " . date("m", strtotime("-2 months", time())) . " AND year = '" . date("Y", strtotime("-2 months", time())) . "' AND location_id = " . $locationId,
            ]
        );

        /* Reviews total last month */
        $smsQuotaParams['totalReviewsLastMonth'] = $smsQuotaParams['numReviewsLastMonth'] - $smsQuotaParams['numReviewsTwoMonthsAgo'];

        /* Reviews this month */
        $smsQuotaParams['numReviewsThisMonth'] = ReviewsMonthly::sum(
            [
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = " . date("m", strtotime("first day of this month")) . " AND year = '" . date("Y", strtotime("first day of this month")) . "' AND location_id = " . $locationId,
            ]
        );

        /* Reviews total this month */
        $smsQuotaParams['totalReviewsThisMonth'] = $smsQuotaParams['numReviewsThisMonth'] - $smsQuotaParams['totalReviewsLastMonth'];

        /* Set the agency SMS limit */
        $location = Location::findFirst(
            [
                "location_id = :location_id:",
                "bind" => [ "location_id" => $locationId ]
            ]
        );
        if ($location) {

            $smsQuotaParams['reviewGoal'] = $location->review_goal;
            $smsQuotaParams['percentNeeded'] = SmsManager::$reviewPercentage;
            $smsQuotaParams['totalSmsNeeded'] = round(
                    $smsQuotaParams['reviewGoal'] / ($smsQuotaParams['percentNeeded'] * 0.1)
            );

        } else {

            $smsQuotaParams['reviewGoal'] = 0;
            $smsQuotaParams['percentNeeded'] = 0;
            $smsQuotaParams['totalSmsNeeded'] = 0;

        }

        if (!$smsQuotaParams['hasUpgrade']) {
            $smsQuotaParams['percent'] =
                ($smsQuotaParams['totalSmsNeeded'] > 0 ?
                floatval(number_format((float)($smsQuotaParams['smsSentThisMonth'] / $smsQuotaParams['totalSmsNeeded']) * 100, 0, '.', '')) :
                100);
            $smsQuotaParams['percent']  > 100 ? 100 : $smsQuotaParams['percent'] ;
        } else {
            $smsQuotaParams['percent'] = 100;
        }

        return $smsQuotaParams;
    }

    public function getAgencySmsQuotaParams() {

        // $smsQuotaParams['showUpgradeMessage'] = false;
        // if (!(isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency')) {
        //     //we have a business, so check if free
        //     //echo '<p>$agency->subscription_id:'.$agency->subscription_id.'</p>';
        //     //echo '<p>$agency->agency_id:'.$agency->agency_id.'</p>';
        //     if (isset($agency->subscription_id) && $agency->subscription_id > 0) {
        //         //we have a subscription, check if free
        //         $conditions = "subscription_id = :subscription_id:";
        //         $parameters = array("subscription_id" => $agency->subscription_id);
        //         $subscriptionobj = Subscription::findFirst(array($conditions, "bind" => $parameters));
        //         if ($subscriptionobj->amount > 0) {
        //             $this->view->is_upgrade = false;
        //         } else {
        //             $this->view->is_upgrade = true;
        //         }
        //     } else {
        //         $this->view->is_upgrade = true;
        //     }
        // }

    }

}
