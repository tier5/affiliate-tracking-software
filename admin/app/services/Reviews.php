<?php
    /**
     * Created by PhpStorm.
     * User: scottconrad
     * Date: 7/22/16
     * Time: 7:28 AM
     */

    namespace Vokuro\Services;

    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\Users;
    use Vokuro\Models\Location;
    use Vokuro\Models\Agency;
    use Vokuro\Models\LocationNotifications;
    use Phalcon\Logger\Adapter\File as FileLogger;

    use Services_Twilio;
    use Services_Twilio_RestException;

    class Reviews extends BaseService {
        protected $const_class;
        protected $types = [];

        public function __construct($config = null, $di = null) {
            parent::__construct($config, $di);
            $this->di = $this->getDI();
            $this->config = $this->di->get('config');
            $this->types[] = ServicesConsts::$GOOGLE_REVIEW_TYPE;
            $this->types[] = ServicesConsts::$FACEBOOK_REVIEW_TYPE;
            $this->types[] = ServicesConsts::$YELP_REVIEW_TYPE;
            $this->types[] = 0; //this is the internal review type
        }

         public function getDI() {
            if($this->di) return $this->di;
            $di = new \Phalcon\Di();
            $this->di = $di->getDefault();
            return $this->di;
        }

        public function DeleteGoogleReviews($LocationID) {
            $dbReviews = \Vokuro\Models\Review::find("rating_type_id = " . \Vokuro\Models\Location::TYPE_GOOGLE . " AND location_id = {$LocationID}");
            foreach ($dbReviews as $objReview)
                $objReview->delete();
        }

        public function DeleteFacebookReviews($LocationID) {
            $dbReviews = \Vokuro\Models\Review::find("rating_type_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK . "  AND location_id = {$LocationID}");
            foreach ($dbReviews as $objReview)
                $objReview->delete();
        }

        public function DeleteYelpReviews($LocationID) {
            $dbReviews = \Vokuro\Models\Review::find("rating_type_id = " . \Vokuro\Models\Location::TYPE_YELP . " AND location_id = {$LocationID}");
            foreach ($dbReviews as $objReview)
                $objReview->delete();
        }

        public function getGoogleClient($LocationID, $RedirectToSession = 0) {
            $Domain = $this->config->application->domain;
            $redirect_uri = "http://{$Domain}/location/googlemybusiness";

            $client = new \Google_Client();
            $client->setApplicationName(APPLICATION_NAME);
            $client->setAuthConfigFile(CLIENT_SECRET_PATH);
            $client->addScope("https://www.googleapis.com/auth/plus.business.manage");
            $client->setRedirectUri($redirect_uri);
            $client->setState($LocationID . '|' . $RedirectToSession);

            // For retrieving the refresh token
            $client->setAccessType('offline');
            $client->setApprovalPrompt("force");
            return $client;
        }

        public function setGoogleRefreshToken($refresh_token, $LocationID) {
            return;
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            if (!$objLocation) {
                $objLocation = new \Vokuro\Models\LocationReviewSite();
                $objLocation->location_id = $LocationID;
                $objLocation->review_site_id = \Vokuro\Models\Location::TYPE_GOOGLE;
            }

            $objLocation->json_access_token = json_encode($refresh_token);
            $objLocation->save();
        }

        public function getGoogleRefreshToken($LocationID) {
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            return $objLocation->json_access_token;
        }

        public function getGoogleAccessToken($LocationID) {
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            return $objLocation->json_access_token;
        }

        public function setGoogleAccessToken($access_token, $LocationID) {
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            if (!$objLocation) {
                $objLocation = new \Vokuro\Models\LocationReviewSite();
                $objLocation->location_id = $LocationID;
                $objLocation->review_site_id = \Vokuro\Models\Location::TYPE_GOOGLE;
            }

            $objLocation->json_access_token = json_encode($access_token);
            $objLocation->save();
        }

        public function getGoogleMyBusinessData($LocationID, $BusinessID) {
            $client = $this->getGoogleClient($LocationID);

            try {
                $client->setAccessToken($this->getGoogleAccessToken($LocationID));
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit();
            }

            $myBusiness = new \Google_Service_Mybusiness($client);
            $accounts = $myBusiness->accounts->listAccounts()->getAccounts();

            if ($accounts) {
                foreach ($accounts as $account) {
                    $locations = $myBusiness->accounts_locations
                                            ->listAccountsLocations($account->name)
                                            ->getLocations();
                    if ($locations) {
                        foreach($locations as $location) {
                            if($location->locationKey->placeId == $BusinessID) {
                                $objBusiness = new \stdClass();
                                $objBusiness->name = $location->locationName;
                                $objBusiness->id = $objBusiness->external_location_id = $BusinessID;
                                $objBusiness->url = $location->metadata->mapsUrl;
                                $objBusiness->address = implode ("\r\n", (array)$location->address->addressLines);
                                $objBusiness->postal_code = $location->address->postalCode;
                                $objBusiness->locality = $location->address->locality;
                                $objBusiness->country = $location->address->country;
                                $objBusiness->state_province = $location->address->administrativeArea;
                                $objBusiness->phone = $location->primaryPhone;

                                break 2;
                            }
                        }
                    }
                }
            }

            return $objBusiness;
        }

           public function SendSMS($phone, $smsBody, $AccountSid, $AuthToken, $twilio_from_phone) {
        if(!$AccountSid || !$AuthToken || !$twilio_from_phone) {
            $this->flash->error("Missing twilio configuration.");
            return false;
        }
        
        $client = new Services_Twilio($AccountSid, $AuthToken);

        try {

                $message = $client->account->messages->create(array(
                    "From" => $this->formatTwilioPhone($twilio_from_phone),
                    "To" => $phone,
                    "Body" => $smsBody,
                ));

        } catch (Services_Twilio_RestException $e) {
            $this->flash->error('There was an error sending the SMS message to ' . $phone . '.  Please check your Twilio configuration and try again. ');
            return false;
        }
        return true;
    }

         public function formatTwilioPhone($phone) {
        $phone = preg_replace('/\D+/', '', $phone);
        if (strlen($phone) == 10)
            $phone = '1' . $phone;
        return '+' . $phone;
    }

        public function getYelpBusinessData($LocationID, $BusinessID) {
            $Yelp = new \Vokuro\Models\YelpScanning();
            $Yelp->construct();

            $objYelpBusiness = json_decode($Yelp->get_business($BusinessID));

            $objBusiness = new \stdClass();
            $objBusiness->name = $objYelpBusiness->name;
            $objBusiness->type = 'Yelp';
            $objBusiness->id = $objBusiness->external_location_id = $objYelpBusiness->id;
            $objBusiness->mapsUrl = $objYelpBusiness->url;
            $objBusiness->url = $objYelpBusiness->url;
            $objBusiness->address = implode("\r\n", $objYelpBusiness->location->display_address);
            $objBusiness->postal_code = $objYelpBusiness->location->postal_code;
            $objBusiness->locality = $objYelpBusiness->location->city;
            $objBusiness->country = $objYelpBusiness->location->country_code;
            $objBusiness->state_province = $objYelpBusiness->location->state_code;
            $objBusiness->phone = $objYelpBusiness->display_phone;
            $objBusiness->external_id = $objYelpBusiness->id;

            return $objBusiness;
        }

        public function getYelpBusinessLocations($LocationID, $BusinessName, $PostalCode) {
            $yelp = new \Vokuro\Models\YelpScanning();
            $yelp->construct();
            $results = $yelp->search($BusinessName, $PostalCode);

            $YelpResults = json_decode($results);
            $tobjBusinesses = [];

            foreach($YelpResults->businesses as $objYelpBusiness) {
                $objBusiness = new \stdClass();
                $objBusiness->name = $objYelpBusiness->name;
                $objBusiness->type = 'Yelp';
                $objBusiness->id = $objBusiness->external_location_id = $objYelpBusiness->id;
                $objBusiness->mapsUrl = $objYelpBusiness->url;
                $objBusiness->url = $objYelpBusiness->mobile_url;
                $objBusiness->address = implode("\r\n", $objYelpBusiness->location->display_address);
                $objBusiness->postal_code = $objYelpBusiness->location->postal_code;
                $objBusiness->locality = $objYelpBusiness->location->city;
                $objBusiness->country = $objYelpBusiness->location->country_code;
                $objBusiness->state_province = $objYelpBusiness->location->state_code;
                $objBusiness->phone = $objYelpBusiness->display_phone;
                $tobjBusinesses[] = $objBusiness;
            }

            return $tobjBusinesses;
        }

        public function getGoogleMyBusinessLocations($LocationID) {
            $client = $this->getGoogleClient($LocationID);

            try {
                $client->setAccessToken($this->getGoogleAccessToken($LocationID));
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit();
            }

            $myBusiness = new \Google_Service_Mybusiness($client);
            $accounts = $myBusiness->accounts->listAccounts()->getAccounts();

            $tobjBusinesses = [];
            if ($accounts) {
                foreach ($accounts as $account) {
                    $locations = $myBusiness->accounts_locations->listAccountsLocations($account->name)->getLocations();
                    if ($locations) {
                        foreach ($locations as $location) {
                            $objBusiness = new \stdClass();
                            $objBusiness->name = $location->locationName;
                            $objBusiness->type = 'Google';
                            $objBusiness->id = $objBusiness->external_location_id = $location->locationKey->placeId;
                            $objBusiness->mapsUrl = $location->metadata->mapsUrl;
                            $objBusiness->address = implode ("\r\n", (array)$location->address->addressLines);
                            $objBusiness->postal_code = $location->address->postalCode;
                            $objBusiness->locality = $location->address->locality;
                            $objBusiness->country = $location->address->country;
                            $objBusiness->state_province = $location->address->administrativeArea;
                            $objBusiness->phone = $location->primaryPhone;
                            $tobjBusinesses[] = $objBusiness;
                        }
                    }
                }
            }
            //print_r($tobjBusinesses);
            
            return $tobjBusinesses;
        }

        public function importYelpReviews($LocationID) {
            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_YELP);
            if(!$objLocationReviewSite)
                return false;

            $Yelp = new \Vokuro\Models\YelpScanning();
            $Yelp->construct();

            $YelpReviews = json_decode($Yelp->get_business($objLocationReviewSite->external_location_id));

            $objLocationReviewSite->rating = $YelpReviews->rating;
            $objLocationReviewSite->review_count = $YelpReviews->review_count;
            $objLocationReviewSite->save();

            if ($YelpReviews->reviews) {
                foreach ($YelpReviews->reviews as $objYelpReview) {
                    $objReview = \Vokuro\Models\Review::findFirst(
                        "external_id = '{$objYelpReview->id}' AND rating_type_id = " . \Vokuro\Models\Location::TYPE_YELP . " AND location_id = {$LocationID}"
                        );
                    if (!$objReview) {
                        $objReview = new \Vokuro\Models\Review();
                        $objReview->assign(array(
                            'rating_type_id' => \Vokuro\Models\Location::TYPE_YELP,
                            'rating' => $objYelpReview->rating,
                            'review_text' => $objYelpReview->excerpt,
                            'time_created' => date("Y-m-d H:i:s", $objYelpReview->time_created),
                            'user_name' => $objYelpReview->user->name,
                            'user_id' => $objYelpReview->user->id,
                            'user_image' => $objYelpReview->user->image_url,
                            'external_id' => $objYelpReview->id,
                            'location_id' => $LocationID,
                        ));
                        $objReview->save();
                    }
                    unset($objReview);
                }
            }
            return true;
        }

        public function importGoogleMyBusinessReviews($LocationID) {
            $reviewService = new Reviews();
           // echo '-------->'.$LocationID;
            $client = $this->getGoogleClient($LocationID);

            try {
                $client->setAccessToken($this->getGoogleAccessToken($LocationID));
            } catch (\Exception $e) {
                return $this->response->redirect("/location/edit/{$LocationID}");
                exit();
            }



            $myBusiness = new \Google_Service_Mybusiness($client);
           
            $accounts = $myBusiness->accounts->listAccounts()->getAccounts();

        if ($accounts) {
                foreach ($accounts as $account) {
                    /**
                     * @var $account \Google_Service_Mybusiness_Account
                     */

                    $locations = $myBusiness->accounts_locations->listAccountsLocations($account->name)->getLocations();
                    if ($locations) {
                        $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
                         if(!$objLocationReviewSite->external_location_id )
                             return false;

                        foreach ($locations as $location) {
                            // if($location->locationKey->placeId != $objLocationReviewSite->external_location_id)
                            //     continue;

                            /**
                             * @var $location \Google_Service_Mybusiness_Location
                             */
                            $lr = $myBusiness->accounts_locations_reviews->listAccountsLocationsReviews($location->name);
                            $reviews = $lr->getReviews();
                            $reviewCount = $lr->getTotalReviewCount();
                            $avg = $lr->getAverageRating();

                            //Seems to be a bug with google not including the average (the field is blank as of 08/31/2016)
                            $TotalRating = 0;
                            $TotalReviews = 0;

                            if ($reviews) {
                                /**
                                 * @var $review \Google_Service_Mybusiness_Review Object
                                 */
                                foreach ($reviews as $review) {
                                    $TotalReviews++;
                                    /**
                                     * @var $reviewer \Google_Service_Mybusiness_Reviewer
                                     */
                                    /*echo '***********************';
                                    echo '$LocationID = '.$LocationID .' \n'. $review->comment;*/

                                    
                                    $reviewer = $review->getReviewer();
                                    $rating = $review->getStarRating();
                                    $ratings = ['ZERO' => 0, 'ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5];
                                    $rating = $ratings[$rating];
                                    echo $LocationID . ' - ';
                                    //echo $review_id."-";
                                    $TotalRating += $rating;
                                    $review_id = str_replace('/reviews', '', $review->getReviewId());

                                    try {
                                        $arr = [
                                            'rating_type_id' => 3,
                                            'review_text' => $review->comment,
                                            'rating_type_review_id' => $review_id,
                                            'external_id' => $review_id,
                                            'rating' => $rating,
                                            'location_id' => $LocationID,
                                            'time_created' => $review['createTime'],
                                            'user_id' => $reviewer->displayName,
                                            'user_name' => $reviewer->displayName,
                                        ];
                                        $reviewService->saveReviewFromData($arr);

                                    } catch (Exception $e) {
                                        continue;
                                    }
                                }
                            }
                            $dbReviews = \Vokuro\Models\Review::find("location_id = {$LocationID} and rating_type_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
                            $objLocationReviewSite->review_count = count($dbReviews);
                            $objLocationReviewSite->rating = $TotalReviews > 0 ? $TotalRating / $TotalReviews : 0;
                            echo $objLocationReviewSite->rating;
                            $objLocationReviewSite->save();

                        }
                    }
                }
            }
        }

        /**
         * @param int $rating_type_id the same as the type of review, 1,2,3 etc based off of source
         * @param int $location_id
         */
        public function updateReviewCountByTypeAndLocationId($rating_type_id, $location_id) {
            $review = new Review();
            if (!in_array($rating_type_id, $this->types)) {
                throw new \Exception("Invalid review type specified, you provided" .
                    $rating_type_id . ', we expected one of: ' . implode(',', $this->types));
            }
            $query = $review->query()->where('location_id = :location_id:')->andWhere('rating_type_id = :rating_type_id:')->
            bind(['rating_type_id' => $rating_type_id, 'location_id' => $location_id]);
            $count = $query->execute()->count();
            //get the LocationRevievw
            $lr = new LocationReviewSite();
            $result = $lr->findFirst([
                    'conditions' => 'location_id = :location_id: AND review_site_id = :review_site_id:',
                    'bind' => ['location_id' => $location_id, 'review_site_id' => $rating_type_id]
                ]
            );

            if ($result) {
                $result->update(['review_count' => $count]);
                $cc = $result->update();
                return $cc;
            }
            if (!$result) throw new \Exception("LocationReviewSite not found with rating_type_id of: {$rating_type_id} for
        location with id of: {$location_id}, perhaps it hasn't been imported?");
        }


        /**
         * This function takes in a location id, and updates the respective counts for each type id that is set in the construct
         * @param int $location_id
         */
        public function updateReviewCountsForLocationById($location_id) {
            foreach ($this->types as $type_id) $this->updateCount($type_id, $location_id);
        }


        /**
         * @param array $data
         * @throws \Exception
         */
        public function saveReviewFromData($data) {
            
            if (!is_array($data)) throw new \Exception("Invalid data specified, expected array");
            if (!isset($data['rating_type_id'])) throw new \Exception('Invalid rating_type_id');
            $review = new Review();
            $record = $review->findOneBy([
                'rating_type_id' => $data['rating_type_id'],
                'location_id' => $data['location_id'],
                'rating_type_review_id' => $data['rating_type_review_id']
            ]);
            if (!$record) {
                $record = new Review();
            }
            /**
             * @var $record \Vokuro\Models\Review
             */
            $record->external_id = $data['review_type_id'];
            $record->review_text = $data['review_text'];
            $record->rating = $data['rating'];
            $record->rating_type_id = $data['rating_type_id'];
            $record->location_id = $data['location_id'];
            $record->rating_type_review_id = $data['rating_type_review_id'];
            if ($data['time_created'])
                $record->time_created = date("Y-m-d H:i:s", strtotime($data['time_created']));

            if ($data['user_id'])
                $record->user_id = $data['user_id'];

            if($data['user_name'])
                $record->user_name = $data['user_name'];

            $save=$record->save();
            
            /**** send review for new entry ****/
            if($data['review_type_id']==1)
            {
                $site_review='Facebook';
            }
            elseif($data['review_type_id']==2)
            {
                 $site_review='Yelp';
            }
            else
            {
                $site_review='Google';
            }
            $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $data['location_id'] );
            $review_invite = new ReviewInvite();
            $invites = $review_invite::find(array($conditions, "bind" => $parameters));
            foreach($invites as $invite)
            {
                echo " (".$save.') - saving data here ->';
                echo "( ".$invite->location_id." )location";

                 if ($invite->location_id > 0 && $save) {

                     $Mail = $this->getDI()->getMail();
                                $Mail->setFrom('zacha','zacha@reputationloop.com');
                                $Mail->send('work@tier5.us','test mail', '', '', 'reached here !!');

                 $user_sent=$invite->sent_by_user_id;
                 $userobj = new Users();
                 $user_info = $userobj::findFirst($user_sent);
                 $emp= $user_info->is_employee;//exit;
                 
                    $role= $user_info->role;
                     $locationobj = new Location();
                     $location = $locationobj::findFirst($invite->location_id);
                     

                   
                     $agencyobj = new Agency();
                     $agency = $agencyobj::findFirst($location->agency_id);
                     $parent_agency=$agencyobj::findFirst($agency->parent_id);
                     


                     $TwilioToken = $parent_agency->twilio_auth_token;
                     
                     $TwilioFrom = $parent_agency->twilio_from_phone;
                     $TwilioAPI = $parent_agency->twilio_api_key;
                     if ($emp==1 && $role =="Super Admin") {
                    //echo 'kk';exit;                             
                         $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user_info->agency_id}");
                         $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");
                         $AgencyName = $objParentAgency->name;
                         $AgencyUser = $objAgencyUser->name." ".$objAgencyUser->last_name;
                
                         $conditions = "location_id = :location_id:";
                         $parameters = array("location_id" => $invite->location_id);
                         $agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));
                         $is_email_alert_on=0;
                         $is_sms_alert_on=0;
                
                         foreach($agencynotifications as $agencynotification) {
                             if ($agencynotification->user_id == $user_info->id) {
                                 $is_email_alert_on = ($agencynotification->email_alert==1?1:0);
                
                                  $is_sms_alert_on = ($agencynotification->sms_alert==1?1:0);
                
                
                             } }
                
                         if($is_email_alert_on==1)
                         {
                           echo '### Email 1 ####';
                           echo $user_info->email;

                           $EmailFrom = 'zacha@reviewvelocity.co';
                           $EmailFromName = "Zach Anderson";
                           $to=$user_info->email;
                           $subject="New Online Review";
                           $mail_body="";
                           $mail_body=$mail_body."<p>One of your customers just left an online review about your business.</p>";
                           $mail_body=$mail_body."<p>Star Rating : ".$data['rating']."</p>";
                           $mail_body=$mail_body."<p>Review Site : ".$site_review."</p>";
                           $mail_body=$mail_body."<p>Review : ".$data['review_text']."</p>";
                          
                           $mail_body=$mail_body."<p>Thank you,</p>";
                           $mail_body=$mail_body.$AgencyUser;
                           $mail_body=$mail_body.'<br>'.$AgencyName;
                
                          $Mail = $this->getDI()->getMail();
                          $Mail->setFrom($EmailFrom, $EmailFromName);
                           $Mail->send($to, $subject, '', '', $mail_body);
                             $phone='8127224722';
                            
                          
                        }
                
                        if($is_sms_alert_on==1)
                            {
                                if($user_info->phone!='')
                                {
                
                                $message=$invite->name." ".$invite->phone." has submitted ".$rating." for employee ".$user_info->name;

                                $message="You just received a new review".$data['rating']." from". $data['user_name']." on ".$site_review." and the review is: ".$data['review_text'];
                         // if ($this->SendSMS($user_info->phone, $message, $TwilioAPI, $TwilioToken,  $TwilioFrom)) {
                         //  }
                                }
                               
                
                                }
                    
                    }
                        
                        /**** else part ***/
                    else
                     {
                        //echo 'yy';exit;
                         $conditions = "location_id = :location_id:";
                         $parameters = array("location_id" => $invite->location_id);
                         $agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));
     
                           $is_email_alert_on=0;
                           $is_sms_alert_on=0;
     
                         foreach($agencynotifications as $agencynotification) {
                             if ($agencynotification->user_id == $user_info->id) {
                                 $is_email_alert_on = ($agencynotification->email_alert==1?1:0);

                                  $is_sms_alert_on = ($agencynotification->sms_alert==1?1:0);
                             } }
     
                              $business_info =  \Vokuro\Models\Users::findFirst('agency_id = ' . $user_info->agency_id . ' AND role="Super Admin"');
         
                             $business_agency= \Vokuro\Models\Agency::findFirst('agency_id = ' . $user_info->agency_id);

                            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$business_agency->parent_id}");
                          $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");
                          $AgencyName = $objParentAgency->name;
                          $AgencyUser = $objAgencyUser->name." ".$objAgencyUser->last_name;

                        
                        if($is_email_alert_on==1)
                         {
                           
                            echo '### Email 2 ####';
                            echo $user_info->email;
                             /*** mail to user ***/
                               $EmailFrom = 'zacha@reviewvelocity.co';
                               $EmailFromName = "Zach Anderson";
                               $to=$user_info->email;
                              $subject="New Online Review";
                               $mail_body="";
                               $mail_body=$mail_body."<p>One of your customers just left an online review about your business.</p>";
                               $mail_body=$mail_body."<p>Star Rating : ".$data['rating']."</p>";
                               $mail_body=$mail_body."<p>Review Site : ".$site_review."</p>";
                               $mail_body=$mail_body."<p>Review : ".$data['review_text']."</p>";
                               $mail_body=$mail_body."<p>Thank you,</p>";
                               $mail_body=$mail_body.$AgencyUser;
                               $mail_body=$mail_body.'<br>'.$AgencyName;

                                $Mail = $this->getDI()->getMail();
                                $Mail->setFrom($EmailFrom, $EmailFromName);
                                $Mail->send($to, $subject, '', '', $mail_body);
        
                            /*** mail to user end ****/
        
                            /**** mail to busines ****/
        
                            
                            echo '### Email 3 ####';
                            echo $to=$business_info->email;
                              $EmailFrom = 'zacha@reviewvelocity.co';
                              $EmailFromName = "Zach Anderson";
                              $to=$business_info->email;
                            $subject="New Online Review";
                               $mail_body="";
                               $mail_body=$mail_body."<p>One of your customers just left an online review about your business.</p>";
                               $mail_body=$mail_body."<p>Star Rating : ".$data['rating']."</p>";
                               $mail_body=$mail_body."<p>Review Site : ".$site_review."</p>";
                               $mail_body=$mail_body."<p>Review : ".$data['review_text']."</p>";
                             $mail_body=$mail_body."<p>Thank you,</p>";
                             $mail_body=$mail_body.$AgencyUser;
                             $mail_body=$mail_body.'<br>'.$AgencyName;

                             $Mail = $this->getDI()->getMail();
                             $Mail->setFrom($EmailFrom, $EmailFromName);
                             $Mail->send($to, $subject, '', '', $mail_body);
                                $phone='8127224722';
                             /**** mail to busines ****/

                            
     
     
                         }


                          if($is_sms_alert_on==1)
                             {
         
                             /*** sms to user ***/

                             if($user_info->phone!='')
                             {

                                  $message="You just received a new review".$data['rating']." from". $data['user_name']." on ".$site_review." and the review is: ".$data['review_text'];
                              // if ($this->SendSMS($user_info->phone, $message, $TwilioAPI, $TwilioToken,$TwilioFrom)) {
                              // }
                                }

                            
                             /*** sms to user ***/
         
                             /*** sms to business ***/
                                if($business_agency->phone!='')
                             {
                                 $message=$invite->name." ".$invite->phone." has submitted ".$rating." for employee ".$user_info->name;
                             // if ($this->SendSMS($business_agency->phone, $message, $TwilioAPI, $TwilioToken,  $TwilioFrom)) {
                             //  }

                            }
                             /*** sms to business ***/

                            }
                           
                         }
                        /**** else part ***/

                     }
                }
            /**** send review for new entry ****/
            $messages = $record->getMessages();
            if ($messages) print_r($messages);
        }

        public function importFacebook($LocationID) {
            $reviewService = new Reviews();
            $FB = new \Vokuro\Models\FacebookScanning();
            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);
            if(!$objLocationReviewSite->access_token)
                return false;

            $FB->setAccessToken($objLocationReviewSite->access_token);

            $logger = new FileLogger(__dir__."/../logs/ReviewImport.log");

            try {
                $tobjReviews = $FB->getReviews();

                $logger->log(var_export($tobjReviews, true));
            } catch(Exception $e) {
                $logger->error(var_export($e, true));
            } catch(Exception $e) {
                // catch filelogger error
            }
            
            $TotalRating = 0;
            $TotalReviews = 0;

            if ($tobjReviews) {
                foreach ($tobjReviews as $objReview) {

                    $TotalReviews++;
                    $TotalRating += $objReview->rating;

                    try {
                        $arr = [
                            'rating_type_id' => \Vokuro\Models\Location::TYPE_FACEBOOK,
                            'review_text' => $objReview->review_text,
                            'rating' => $objReview->rating,
                            'location_id' => $LocationID,
                            'time_created' => date("Y-m-d H:i:s", strtotime($objReview->created_time)),
                            'user_id' => $objReview->reviewer->id,
                            'user_name' => $objReview->reviewer->name,
                            // Assuming one user per location can only leave one review.  Currently, they do not provide any other identifier, and I believe this is true.
                            'rating_type_review_id' => $objReview->reviewer->id,
                        ];
                        $reviewService->saveReviewFromData($arr);

                    } catch (Exception $e) {
                        $logger->error(var_export($e, true));

                        continue;
                    } catch(Exception $e) {
                        continue;
                    }
                }
            }

            $dbReviews = \Vokuro\Models\Review::find("location_id = {$LocationID} and rating_type_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);
            $objLocationReviewSite->review_count = count($tobjReviews);
            $objLocationReviewSite->rating = $TotalReviews > 0 ? $TotalRating / $TotalReviews : 0;
            return $objLocationReviewSite->save();
            
        }
    }