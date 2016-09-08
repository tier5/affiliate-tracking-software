<?php
    require 'bootstrap.php';
    use Vokuro\Services\Reviews;

    // GARY_TODO:  In a rush.  I know extending the controller is terrible, but dead lines...
    class LocationImporter extends \Vokuro\Controllers\LocationController {

        function initialize() {
            // Purposefully not calling parent
            $path_to_admin = realpath(__DIR__ . '/../');
            include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
            define('APPLICATION_NAME', 'User Query - Google My Business API');
            define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');
        }

        function ImportGoogleMyBusinessReviews() {
            $dbLocations = \Vokuro\Models\LocationReviewSite::find("review_site_id = 3 AND json_access_token IS NOT NULL");
            foreach ($dbLocations as $objLocation) {
                $LocationID = $objLocation->location_id;
                $reviewService = new Reviews();

                $client = $this->getGoogleClient();

                try {
                    //if we don't have a token, it will complain.. in that case we catch the error, and in our case
                    //redirect back over to where they can click the link to get the new token
                    $client->setAccessToken($this->getAccessToken($LocationID));
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    exit();
                }

                $myBusiness = new \Google_Service_Mybusiness($client);
                $accounts = $myBusiness->accounts->listAccounts()->getAccounts();
                if ($accounts) foreach ($accounts as $account) {
                    /**
                     * @var $account \Google_Service_Mybusiness_Account
                     */
                    //print '<h1>'.$account->accountName.'</h1>';
                    $locations = $myBusiness->accounts_locations->listAccountsLocations($account->name)->getLocations();
                    if ($locations) {
                        foreach ($locations as $location) {
                            /**
                             * @var $location \Google_Service_Mybusiness_Location
                             */
                            $lr = $myBusiness->accounts_locations_reviews->listAccountsLocationsReviews($location->name);
                            $reviews = $lr->getReviews();
                            $reviewCount = $lr->getTotalReviewCount();
                            $avg = $lr->getAverageRating();
                            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = 3");
                            // GARY_TODO:  This is simply a guess of what this field means
                            //$objLocationReviewSite->original_review_count = $objLocationReviewSite->review_count;
                            $objLocationReviewSite->review_count = $reviewCount;

                            // Seems to be a bug with google not including the average (the field is blank as of 08/31/2016)
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
                                    $reviewer = $review->getReviewer();
                                    $rating = $review->getStarRating();
                                    $ratings = ['ZERO' => 0, 'ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5];
                                    $rating = $ratings[$rating];
                                    $review_id = str_replace('/reviews', '', $review->getReviewId());
                                    $TotalRating += $rating;

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
                                        ];
                                        $reviewService->saveReviewFromData($arr);

                                    } catch (Exception $e) {
                                        continue;
                                    }
                                }
                            }
                            $objLocationReviewSite->rating = $TotalReviews > 0 ? $TotalRating / $TotalReviews : 0;
                            $objLocationReviewSite->save();
                        }
                    }
                }
            }
        }

        public function ImportFacebookReviews() {
            $LocationID = 64;
            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = 1");
            $objLocation = \Vokuro\Models\Location::findFirst("location_id = {$LocationID}");
            $FoundAgency = [];

            $objReviewService = new \Vokuro\Services\Reviews();
            $objReviewService->importFacebook($objLocationReviewSite, $objLocation, $FoundAgency);
        }
    }

    $objImporter = new LocationImporter();
    $objImporter->initialize();
    //$objImporter->ImportGoogleMyBusinessReviews();
    $objImporter->ImportFacebookReviews();