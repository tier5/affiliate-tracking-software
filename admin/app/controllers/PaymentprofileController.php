<?php

namespace Vokuro\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Vokuro\Models\Agency;
use Vokuro\Models\FacebookScanning;
use Vokuro\Models\GoogleScanning;
use Vokuro\Models\Location;
use Vokuro\Models\LocationNotifications;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Users;
use Vokuro\Models\UsersSubscription;
use Vokuro\Models\YelpScanning;
use Vokuro\Services\Permissions;
use Vokuro\Services\ServicesConsts;
use Services_Twilio;
use Services_Twilio_RestException;

use Exception;
use Vokuro\Models\BusinessSubscriptionPlan;
use Vokuro\Services\StripeService as Stripe;

/**
 * Vokuro\Controllers\BusinessSubscriptionController
 * CRUD to manage users
 */
class PaymentprofileController extends Controller
{
    public function initialize()
    {

    }


    /**
     * Update credit card
     */
    public function indexAction()
    {
        $this->view->disable();

        $responseParameters['status'] = false;

        try {
            if (!$this->request->isPost()) {
                throw new \Exception();
            }

            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');

            $email = $this->request->getPost('email');

            //die($email);

            $user = Users::query()
                ->where("email = :email:")
                ->bind(["email" => $email])
                ->execute()
                ->getFirst();
            $agency = Agency::query()
                ->where("agency_id = :agency_id:")
                ->bind(["agency_id" => $user->agency_id])
                ->execute()
                ->getFirst();

            $objSuperUser = \Vokuro\Models\Users::findFirst(
                "agency_id = {$agency->agency_id} AND role='Super Admin'"
            );

            //die($objSuperUser->email);

            $Provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;

            // Card Number, Name and CSV aren't required for Stripe.  Just grab the token

            $tokenID = $this->request->getPost('tokenID', 'striptags');

            /* Create the payment profile */
            $paymentParams = [ 'userId' => $objSuperUser->id, 'provider' => $Provider];
            $ccParameters = [
                'userId'                => $objSuperUser->id,
                //'cardNumber'            => str_replace(' ', '', $cardNumber),
                'provider'              => $Provider,
                'userEmail'             => $user->email,
                'userName'              => $user->name,
                'agencyName'            => $agency->name,
                'agencyAddress'         => $agency->address,
                'agencyCity'            => '', //$agency->city,  This field doesn't exist yet.  Will add later  GARY_TODO:  Fix this!
                'agencyStateProvince'   => $agency->state_province,
                'agencyPostalCode'      => $agency->postal_code,
                'agencyCountry'         => $agency->country,
                'tokenID'               => $tokenID,
            ];

            if ($paymentService->hasPaymentProfile($paymentParams)) {
                $profile = $paymentService->updatePaymentProfile($ccParameters);
                // die('has payment profile');
                if (!$profile) {
                    throw new \Exception('Payment Profile Could not be updated');
                }
            } else {
                $profile = $paymentService->createPaymentProfile($ccParameters);
                // die('attempted to create payment profile');
                if (!$profile) {
                    throw new \Exception('Payment Profile Could not be created');
                }
            }

            /*
             * Success!!!
             */
            $responseParameters['status'] = true;

            $agency->subscription_valid = 'Y';
            $agency->save();

        }  catch(Exception $e) {
            die($e->getMessage());
        }

        /*
         * Construct the response
         */
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));

        return $this->response;
    }
}