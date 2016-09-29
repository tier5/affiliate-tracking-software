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

class Reviews extends BaseService
{
    protected $const_class;
    protected $types = [];
    public function __construct($config = null, $di = null){
        parent::__construct($config, $di);
        $this->types[] = ServicesConsts::$GOOGLE_REVIEW_TYPE;
        $this->types[] = ServicesConsts::$FACEBOOK_REVIEW_TYPE;
        $this->types[] = ServicesConsts::$YELP_REVIEW_TYPE;
        $this->types[] = 0; //this is the internal review type
    }

    public function DeleteGoogleReviews($LocationID) {
        $dbReviews = \Vokuro\Models\Reviews::find("rating_type_id = 3 AND location_id = {$LocationID}");
        foreach($dbReviews as $objReview)
            $objReview->delete();
    }

    /**
     * @param int $rating_type_id the same as the type of review, 1,2,3 etc based off of source
     * @param int $location_id
     */
    public function updateReviewCountByTypeAndLocationId($rating_type_id, $location_id){
       $review = new Review();
        if(!in_array($rating_type_id,$this->types)) {
            throw new \Exception("Invalid review type specified, you provided" .
                $rating_type_id . ', we expected one of: ' . implode(',', $this->types));
        }
        $query = $review->query()->where('location_id = :location_id:')->andWhere('rating_type_id = :rating_type_id:')->
        bind(['rating_type_id'=>$rating_type_id,'location_id'=>$location_id]);
        $count = $query->execute()->count();
        //get the LocationRevievw
        $lr = new LocationReviewSite();
        $result = $lr->findFirst([
            'conditions'=>'location_id = :location_id: AND review_site_id = :review_site_id:',
            'bind'=>['location_id'=>$location_id,'review_site_id'=>$rating_type_id]
            ]
        );

        if($result) {
            $result->update(['review_count' => $count]);
            $cc = $result->update();
            return $cc;
        }
        if(!$result) throw new \Exception("LocationReviewSite not found with rating_type_id of: {$rating_type_id} for
        location with id of: {$location_id}, perhaps it hasn't been imported?");
    }


    /**
     * This function takes in a location id, and updates the respective counts for each type id that is set in the construct
     * @param int $location_id
     */
    public function updateReviewCountsForLocationById($location_id){
        foreach($this->types  as $type_id) $this->updateCount($type_id,$location_id);
    }


    /**
     * @param array $data
     * @throws \Exception
     */
    public function saveReviewFromData($data)
    {
        if (!is_array($data)) throw new \Exception("Invalid data specified, expected array");
        if (!isset($data['rating_type_id'])) throw new \Exception('Invalid rating_type_id');
        $review = new Review();
        $record = $review->findOneBy([
            'rating_type_id'=>$data['rating_type_id'],
            'location_id'=>$data['location_id'],
            'rating_type_review_id'=>$data['rating_type_review_id']
        ]);
        if(!$record){
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
        if($data['time_created'])
            $record->time_created = date("Y-m-d H:i:s", strtotime($data['time_created']));

        if($data['user_id'])
            $record->user_id = $data['user_id'];
        $record->save();
        $messages = $record->getMessages();
        if($messages) print_r($messages);
    }

    public function importFacebook($Obj, $location, &$foundagency) {
        $face = new \Vokuro\Models\FacebookScanning();
        $this->facebook_access_token = $face->getAccessToken();

        //first initialize our scanners
        $yelp = new \Vokuro\Models\YelpScanning();
        $yelp->construct();

        //$facebook_reviews = $face->getBusinessDetails($Obj->external_id, $this->facebook_access_token);
        //echo '<pre>$facebook_reviews:'.print_r($facebook_reviews,true).'</pre>';
        //Facebook has special permissions on public reviews, so lets try to scrape them
        $url = 'https://www.facebook.com/' . $Obj->external_id . '/reviews/';
        $results = $yelp->getHTML($url);
        //echo '<pre>$facebook_reviews:'.$results.'</pre>';
        //get the review info from the html
        //<meta content="#" itemprop="ratingValue" />
        $pos = strpos($results, '" itemprop="ratingValue"');
        $rating = substr($results, 0, $pos);
        $pos2 = strrpos($rating, '"');
        $rating = substr($rating, $pos2 + 1);
        //echo '<pre>$rating:'.$rating.'</pre>';
        //<meta content="6" itemprop="ratingCount" />
        $pos = strpos($results, '" itemprop="ratingCount');
        $rating_count = substr($results, 0, $pos);
        $pos2 = strrpos($rating_count, '"');
        $rating_count = substr($rating_count, $pos2 + 1);
        //echo '<pre>$rating_count:'.$rating_count.'</pre>';
        //import data from the feed into the database, first update the location
        $Obj->rating = $rating;
        $Obj->review_count = $rating_count;
        if (!isset($Obj->original_review_count) || (!($Obj->original_review_count > 0)) || $Obj->original_review_count > $Obj->review_count) {
            $Obj->original_rating = $Obj->rating;
            $Obj->original_review_count = $Obj->review_count;
        }
        $Obj->save();

        //if we have a facebook page token, try to import reviews
        if (isset($Obj->access_token) && $Obj->access_token != '') {
            //use the graph api to get facebook "ratings" aka reviews
            require_once __DIR__ . "/../library/Facebook/autoload.php";
            require_once __DIR__ . "/../library/Facebook/Facebook.php";
            require_once __DIR__ . "/../library/Facebook/FacebookApp.php";
            require_once __DIR__ . "/../library/Facebook/FacebookClient.php";
            require_once __DIR__ . "/../library/Facebook/FacebookRequest.php";
            require_once __DIR__ . "/../library/Facebook/FacebookResponse.php";
            require_once __DIR__ . "/../library/Facebook/Authentication/AccessToken.php";
            require_once __DIR__ . "/../library/Facebook/Authentication/OAuth2Client.php";
            require_once __DIR__ . "/../library/Facebook/Helpers/FacebookRedirectLoginHelper.php";
            require_once __DIR__ . "/../library/Facebook/PersistentData/PersistentDataInterface.php";
            require_once __DIR__ . "/../library/Facebook/PersistentData/FacebookSessionPersistentDataHandler.php";
            require_once __DIR__ . "/../library/Facebook/Url/UrlDetectionInterface.php";
            require_once __DIR__ . "/../library/Facebook/Url/FacebookUrlDetectionHandler.php";
            require_once __DIR__ . "/../library/Facebook/Url/FacebookUrlManipulator.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/PseudoRandomStringGeneratorTrait.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/PseudoRandomStringGeneratorInterface.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/OpenSslPseudoRandomStringGenerator.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookHttpClientInterface.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookCurl.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookCurlHttpClient.php";
            require_once __DIR__ . "/../library/Facebook/Http/RequestBodyInterface.php";
            require_once __DIR__ . "/../library/Facebook/Http/RequestBodyUrlEncoded.php";
            require_once __DIR__ . "/../library/Facebook/Http/GraphRawResponse.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookSDKException.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookAuthorizationException.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookAuthenticationException.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookResponseException.php";

            $this->fb = new \Services\Facebook\Facebook(array(
                'app_id' => '628574057293652',
                'app_secret' => '95e89ebac7173ba0980c36d8aa5777e4'
            ));
            /*
              $result = $this->fb->get('/'. $Obj->external_id.'/ratings?limit=10000', $Obj->access_token)->getDecodedBody();
              $reviews = $result['data'];
              while(!empty($result['data'])) {
              $result = $this->fb->get('/'.$Obj->external_id.'/ratings?limit=10000&after='.$result['paging']['cursors']['after'], $this->facebook_access_token)->getDecodedBody();
              $reviews = array_merge($reviews, $result['data']);
              }
              //echo '<pre>$reviews:'.print_r($reviews,true).'</pre>';

              //$token = str_replace("access_token=", "", $this->facebook_access_token);
              //echo '<pre>$this->facebook_access_token:'.print_r($this->facebook_access_token,true).'</pre>';
              //echo '<pre>$token:'.print_r($token,true).'</pre>';
              // Disable app secret proof
              FacebookSession::enableAppSecretProof(false);
              $session = new FacebookSession($Obj->access_token);
              $request = new FacebookRequest(
              $session,
              'GET',
              '/'.$Obj->external_id.'/ratings'
              );
              $response = $request->execute();
              $graphObject = $response->getGraphObject();
              echo '<pre>$graphObject:'.print_r($graphObject,true).'</pre>';
             */
            $url = '/me/accounts';
            $pages = $this->fb->get($url, $Obj->access_token)->getDecodedBody();
            //echo '<pre>$pages:'.print_r($pages,true).'</pre>';

            $page_access_token = '';
            if (!empty($pages['data'])) {
                foreach ($pages['data'] as $page) {
                    if ($page['id'] == $Obj->external_id) {
                        $page_access_token = $page['access_token'];
                        //echo '<p><strong>$page_access_token:'.$page_access_token.'</strong></p>';
                    }
                }
            }

            //if we found a page access token, try to find reviews
            if ($page_access_token != '') {
                $reviews = $face->getBusinessReviews($Obj->external_id, $page_access_token);
                //echo '<pre>$reviews:'.print_r($reviews,true).'</pre>';

                if (isset($reviews) && $reviews != '') {
                    $reviews = json_decode($reviews);
                }

                //now import the reviews (if not already in the database)
                //loop through reviews
                foreach ($reviews->data as $reviewDetails) {
                    //check if the review is already in the db
                    $conditions = "time_created = :time_created: AND rating_type_id = 2 AND location_id = " . $location->location_id;
                    $phpdate = strtotime($reviewDetails->created_time);
                    $parameters = array("time_created" => date("Y-m-d H:i:s", $phpdate));
                    $googlerev = Review::findFirst(array($conditions, "bind" => $parameters));
                    if (!$googlerev) {
                        //we didn't find the review, so assign the values
                        $r = new Review();
                        $r->assign(array(
                            'rating_type_id' => 2, //2 = Facebook
                            'rating' => $reviewDetails->rating,
                            'review_text' => $reviewDetails->review_text,
                            'time_created' => date("Y-m-d H:i:s", $phpdate),
                            'user_name' => $reviewDetails->reviewer->name,
                            'user_id' => $reviewDetails->reviewer->id,
                            //'external_id' => $reviewDetails->id,  facebook has no review id
                            'location_id' => $location->location_id,
                        ));
                        //save now
                        $r->save();

                        //add agency to our found array
                        if (isset($foundagency[$location->agency_id])) {
                            $foundagency[$location->agency_id] .= ', ';
                        } else {
                            $foundagency[$location->agency_id] = '';
                        }
                        $foundagency[$location->agency_id] .= $location->name;
                    }
                } // go to the next facebook review
            }
        } //end checking for an access token

        try {
            $s = $this->di->get('ReviewService');
            /**
             * @var $s \Vokuro\Services\Reviews
             */
            if($location && $location->location_id) $s->updateReviewCountByTypeAndLocationId(3, $location->location_id);

        } catch (\Exception $e) {
            print "there was an error \n";
            print_r($e->getTraceAsString());
            exit();
        }

        return $Obj;
    }
}