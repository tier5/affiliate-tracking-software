<?php
namespace Vokuro\Models;
use Phalcon\Mvc\Model;
use Yelp;

/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */
class YelpScanning extends Model {

    public $objCommonFunc;
    protected $CONSUMER_KEY = 'aUIG1lOJOLaaphLEcomKag';
    protected $CONSUMER_SECRET = 'VaN9d1EEnHEng_ZyHSyIW4K4Kvg';
    protected $TOKEN = 'bucNlZyFwF-eUGlIzAmoPfLamUW7MrDw';
    protected $TOKEN_SECRET = '4sr60F0mUxlndjhbuVH281TMckY';
    protected $API_HOST = 'api.yelp.com';
    protected $DEFAULT_TERM = '';
    protected $DEFAULT_LOCATION = '';
    protected $SEARCH_LIMIT = 3;
    protected $SEARCH_PATH = "/v2/search/";
    protected $BUSINESS_PATH = "/v2/business/";

    public function construct() {
        #create CommonFunction object            
        //$this->objCommonFunc = new CommonFunction();
        // Enter the path that the oauth library is in relation to the php file
        require_once('Yelp/OAuth.php');
    }

    /**
     * Makes a request to the Yelp API and returns the response
     * 
     * @param    $host    The domain host of the API 
     * @param    $path    The path of the APi after the domain
     * @return   The JSON response from the request      
     */
    public function request($host, $path) {
        $unsigned_url = "http://" . $host . $path;

        // Token object built using the OAuth library
        $token = new Yelp\OAuthToken($this->TOKEN, $this->TOKEN_SECRET);

        // Consumer object built using the OAuth library
        $consumer = new Yelp\OAuthConsumer($this->CONSUMER_KEY, $this->CONSUMER_SECRET);

        // Yelp uses HMAC SHA1 encoding
        $signature_method = new Yelp\OAuthSignatureMethod_HMAC_SHA1();

        $oauthrequest = Yelp\OAuthRequest::from_consumer_and_token(
                        $consumer, $token, 'GET', $unsigned_url
        );

        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);

        // Get the signed URL
        $signed_url = $oauthrequest->to_url();

        //echo '<p>$signed_url:'.$signed_url.'</p>';
        // Send Yelp API Call
        $ch = curl_init($signed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Query the Search API by a search term and location 
     * 
     * @param    $term        The search term passed to the API 
     * @param    $location    The search location passed to the API 
     * @return   The JSON response from the request 
     */
    public function search($term, $location) {
        $url_params = array();

        $url_params['term'] = $term;
        $url_params['location'] = $location;
        $url_params['limit'] = $this->SEARCH_LIMIT;
        $search_path = $this->SEARCH_PATH . "?" . http_build_query($url_params);
        return $this->request($this->API_HOST, $search_path);
    }

    /**
     * Query the Business API by business_id
     * 
     * @param    $business_id    The ID of the business to query
     * @return   The JSON response from the request 
     */
    public function get_business($business_id) {
        $business_path = $this->BUSINESS_PATH . $business_id;

        return $this->request($this->API_HOST, $business_path);
    }




    public function getHTML($url) {
      $curl=curl_init();
      $cookie = tempnam ("/tmp", "CURLCOOKIE");
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
      curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($curl, CURLOPT_ENCODING, "");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_AUTOREFERER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
      curl_setopt($curl, CURLOPT_TIMEOUT, 2);
      curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
      $strhtml = curl_exec($curl);
//echo '<pre>$html: '.print_r($strhtml,true).'</pre>';
      //$html = str_get_html($strhtml);
      curl_close($curl);
//echo '<pre>$html: '.print_r($html,true).'</pre>';
      return $strhtml;
    }


    public function file_get_contents_curl($url) {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
      $data = curl_exec($ch);
      echo curl_error($ch);
      curl_close($ch);

      return $data;
    }
    

    public function customerReviewDetails($reviews) {
        $arrReview = array();
        $intCountReviews = count($reviews);

        if ($intCountReviews > 0) {
            foreach ($reviews as $reviewDetails) {
              if ($reviewDetails->rating > 0) {
                if ($reviewDetails->rating >= 4) {
                    $arrReview['authorName'] = @$reviewDetails->user->name;
                    $arrReview['rating'] = @$reviewDetails->rating;
                    $arrReview['authorUrl'] = array();
                    $arrReview['reviewText'] = @$reviewDetails->excerpt;
                    $arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails->time_created);
                    $arrReview['from'] = "Yelp";
                    $returnReviewDetails['happy_customer'][] = $arrReview;
                } elseif ($reviewDetails->rating <= 3 && $reviewDetails->rating > 0) {
                    $arrReview['authorName'] = @$reviewDetails->user->name;
                    $arrReview['rating'] = @$reviewDetails->rating;
                    $arrReview['authorUrl'] = array();
                    $arrReview['reviewText'] = @$reviewDetails->excerpt;
                    $arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails->time_created);
                    $arrReview['from'] = "Yelp";
                    $returnReviewDetails['unhappy_customer'][] = $arrReview;
                }
              }
            }
        } else {
            $returnReviewDetails[] = array();
        }

        return $returnReviewDetails;
    }

    public function allReviewDetails($reviews, $intDirectoryId, &$returnreviews) {
        $arrReview = array();
        $intCountReviews = count($reviews);

        if ($intCountReviews > 0) {
            foreach ($reviews as $reviewDetails) {

               $arrReview['authorName'] = @$reviewDetails->user->name;
               $arrReview['rating'] = @$reviewDetails->rating;
               $arrReview['authorUrl'] = NULL;
               $arrReview['reviewText'] = @$reviewDetails->excerpt;
               $arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails->time_created);
               $arrReview['from'] = "Yelp";
               $returnReviewDetails[] = $arrReview;
               //echo '<p>Test:'.@$reviewDetails->user->name.'</p>';
               //$returnreviews[] = $arrReview;
               array_push($returnreviews, $arrReview);
                /*
               $BusinessTable                       = $this->getController()->getServiceLocator()->get("Application\Model\BusinessTable");

               $objBusiness                         = new Business();
               //store review in database
               $objBusiness->intDirectoryId    = $intDirectoryId;
               $objBusiness->strAuthorName     = $arrReview['authorName'];
               $objBusiness->strRating         = $arrReview['rating'];
               $objBusiness->strDateTime       = $arrReview['dateTime'];
               $objBusiness->strFrom           = 'yelp';
               $objBusiness->strAuthorUrl      = $arrReview['authorUrl'];
               $objBusiness->strReviewText     = addSlashes($arrReview['reviewText']);
               if($arrReview['rating'] > 3)
               $objBusiness->enmReviewType     = 1;
               else if($arrReview['rating'] == 3)
               $objBusiness->enmReviewType     = 2;
               else if($arrReview['rating'] < 3)
               $objBusiness->enmReviewType     = 3;
               //$BusinessTable->fncDIRAddDirectoryReview($objBusiness);
               */
            }
        } else {
            $returnReviewDetails[] = array();
        }

        return $returnReviewDetails;
    }

    /**
     * Queries the API by the input values from the user 
     * 
     * @param    $term        The search term to query
     * @param    $location    The location of the business to query
     */
    public function yelpApiResult($businessDetails, &$returnreviews, $countrycode) {
        
        $response = json_decode($this->search($businessDetails['businessName'], $businessDetails['completeAddress']));
        $responseArr = $this->objCommonFunc->blankArrayOutput();
        
        if (isset($response->businesses)) {
          //loop through results, stop when we find a match
          foreach ($response->businesses as $business) {        
            $business_id = $business->id;
            //echo '<p>$business_id:'.$business_id.'</p>';
        
            if($business_id){
                //$response = $this->get_business(urlencode(utf8_decode($business_id)));
                $responsebusiness = $this->get_business(urlencode($business_id));
                $yelpResponse = json_decode($responsebusiness);
    //echo '<pre>$yelpResponse:'.print_r($yelpResponse,true).'</pre>';
            
                if($this->objCommonFunc->matchBusinessName($yelpResponse->name, $businessDetails['businessName'])){                
            
                    $responseArr = array();
                    $responseArr['city'] = @$yelpResponse->location->city;
                    $responseArr['state'] = @$yelpResponse->location->state_code;
                    $responseArr['country'] = @$yelpResponse->location->country_code;
                    $responseArr['zip'] = @$yelpResponse->location->postal_code;
                    $responseArr['businessName']['value'] = @$yelpResponse->name;
                    $responseArr['businessName']['status'] = $this->objCommonFunc->matchBusinessName($yelpResponse->name, $businessDetails['businessName']);
                    $responseArr['address']['value'] = implode(", ", @$yelpResponse->location->display_address);
                    $responseArr['address']['status'] = @$this->objCommonFunc->stringMatching($businessDetails['address'], implode(", ", @$yelpResponse->location->display_address));
                    $responseArr['phoneNumber']['value'] = @$yelpResponse->display_phone;
                    $responseArr['phoneNumber']['status'] = @$this->objCommonFunc->phoneNumberMatching($businessDetails['phoneNumber'], $yelpResponse->phone);
                    $responseArr['averageRating'] = @$yelpResponse->rating;
                    //if we are searching the UK, then lets search different Web sites
                    if ($countrycode == 'GB') {
                      $responseArr['businessUrl'] = str_replace("yelp.com", "yelp.co.uk", $yelpResponse->url);
                    } else {
                      $responseArr['businessUrl'] = @$yelpResponse->url;
                    }
                    $responseArr['totalReviews'] = @$yelpResponse->review_count;
                
                    #store in Directory table
                    $BusinessTable                       = $this->getController()->getServiceLocator()->get("Application\Model\BusinessTable");

                    $objBusiness                         = new Business();
                    $objBusiness->intBusinessId          = $businessDetails['intBusinessId'];
                    $objBusiness->strBusinessName        = $responseArr['businessName']['value'];
                    $objBusiness->enmIsBusinessNameMatch = $responseArr['businessName']['status'];
                    $objBusiness->strAddress             = $responseArr['address']['value'];
                    $objBusiness->strCityShort           = $responseArr['city'];
                    $objBusiness->strCityLong            = $responseArr['city'];
                    $objBusiness->strStateShort          = $responseArr['state'];
                    $objBusiness->strStateLong           = $responseArr['state'];
                    $objBusiness->strCountryShort        = $responseArr['country'];
                    $objBusiness->strCountryLong         = $responseArr['country'];
                    $objBusiness->enmIsAddressMatch      = $responseArr['address']['status'];
                    $objBusiness->strZipCode             = $responseArr['zip'];
                    $objBusiness->strPhoneNumber         = $this->objCommonFunc->stringFormat($responseArr['phoneNumber']['value'], "phone");
                    $objBusiness->strDisplayPhoneNumber  = $responseArr['phoneNumber']['value'];
                    $objBusiness->enmIsPhoneNumberMatch  = $responseArr['phoneNumber']['status'];
                    $objBusiness->strWebsiteUrl          = $responseArr['businessUrl'];
                    $objBusiness->enmDirectoryType       = 'yelp';
                    $objBusiness->strAverageRating       = $responseArr['averageRating'];
                    $objBusiness->intTotalReview         = $responseArr['totalReviews'];

                    $intDirectoryId                      = $BusinessTable->fncDIRAddDirectory($objBusiness);
                
                    $responseArr['customerReviewDetails'] = $this->customerReviewDetails(@$yelpResponse->reviews);
                    $responseArr['allReviewDetails'] = $this->allReviewDetails(@$yelpResponse->reviews, $intDirectoryId, $returnreviews); 
                
                    return $responseArr;
                }  else {                
                    $responseArr = $this->objCommonFunc->blankArrayOutput();
                }
            }  else {
               $responseArr = $this->objCommonFunc->blankArrayOutput();
            }
          }
        }
        return $responseArr;
    }

}

?>
