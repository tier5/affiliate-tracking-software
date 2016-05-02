<?php
namespace Vokuro\Models;
use Phalcon\Mvc\Model;

class FacebookScanning extends Model {

    public $objCommonFunc;
    protected $_access_token;
    protected $_query;
    protected $_limit;
    protected $_client_id;
    protected $_client_secret;

    public function construct()
    {
        #set facebook App Id & Secret Id
        $this->setClientId('1650142038588223')->setClientSecret('b1c2cb9c1cbb774ea35eb68de725ee45');
    }

     /**
     * This is Search result using facebook Search API.
     * 
     * @return              Array
     */
    public function setClientId($value) {
        $this->_client_id = $value;
        return $this;
    }

    public function setClientSecret($value) {
        $this->_client_secret = $value;
        return $this;
    }

    public function setQuery($value) {
        $this->_query = $value;
        return $this;
    }

    public function setAccessToken($value) {
        $this->_access_token = $value;
        return $this;
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
    
    
    /**
     * Get Access Token
     * @param   void
     * @return  String
     */
    public function getAccessToken() {
      $this->construct();
      $strAccessUrl = "https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id={$this->_client_id}&client_secret={$this->_client_secret}&redirect_uri=http://".$_SERVER['HTTP_HOST'];
      return $this->file_get_contents_curl($strAccessUrl);
    }
    
    
    

    /**
     * Fetch Phone no using Page Id
     * @param   string $strPageId
     * @return  String
     */
    public function getBusinessReviews($facebook_page_id, $facebook_access_token) {

        //$strDetailsUrl = "https://graph.facebook.com/v2.5/" . $facebook_page_id . "/ratings?x=x&{$facebook_access_token}";
        $strDetailsUrl = "https://graph.facebook.com/v2.5/" . $facebook_page_id . "/ratings?limit=10000&access_token=".$facebook_access_token;
        echo '<p>$strDetailsUrl:'.$strDetailsUrl.'</p>';
        return $this->file_get_contents_curl($strDetailsUrl);
    }
    

    /**
     * Fetch Businesses
     * @param   void
     * @return  Array
     */
    protected function getBusinessPageId() {
        //Validate
        //if (empty($this->_query))
        //    throw new Exception("Query Not Set");

        $strSearchUrl = "https://graph.facebook.com/search?q=" . urlencode($this->_query) . "&type=page&limit=100&{$this->_access_token}";
        //echo '<p>$strSearchUrl:'.$strSearchUrl.'</p>';
        return $result = $this->file_get_contents_curl($strSearchUrl);
    }

    /**
     * Fetch Phone no using Page Id
     * @param   string $strPageId
     * @return  String
     */
    public function getBusinessDetails($facebook_page_id, $facebook_access_token) {

        $strDetailsUrl = "https://graph.facebook.com/v2.4/" . $facebook_page_id . "?fields=name,phone,location,general_info&{$facebook_access_token}";
        echo '<p>$strDetailsUrl:'.$strDetailsUrl.'</p>';
        return $this->file_get_contents_curl($strDetailsUrl);
    }


    /**
     * Fetch Business List
     * @param   void
     * @return  Array
     */
    public function facebookApiResult($paramBusinessDetails, &$returnreviews) {
        $responseArr = array();
        //set access token
        $strAccessToken = $this->getAccessToken();
        $this->setAccessToken($strAccessToken);
        $this->setQuery($paramBusinessDetails['businessName']);

        $arrBusinessId = $this->getBusinessPageId();
        $arrBusiness = json_decode($arrBusinessId);

        if (!empty($arrBusiness->data)) {

            $arrBusinessList = array();

            //iteratively get phone no
            foreach ($arrBusiness->data as $business) {

                if (strtolower($business->name) == $paramBusinessDetails['businessName']) {
                    $arrBusinessList['name'] = $business->name;
                    $arrBusinessList['pageId'] = $business->id;
                    break;
                } else {
                    $arrBusinessList['name'] = "";
                    $arrBusinessList['pageId'] = "";
                }
            }

            if (!empty($arrBusinessList['pageId'])) {
                $arrBusinessDetails = $this->getBusinessDetails($arrBusinessList);
                $arrBusinessDetail = json_decode($arrBusinessDetails);

                $responseArr['city'] = @$arrBusinessDetail->location->city;
                $responseArr['state'] = @$arrBusinessDetail->location->state;
                $responseArr['country'] = @$arrBusinessDetail->location->country;
                $responseArr['zip'] = @$arrBusinessDetail->location->zip;
                $responseArr['businessName']['value'] = @$arrBusinessDetail->name;
                $responseArr['businessName']['status'] = true;
                $strAddress = @$arrBusinessDetail->location->street.', '.$responseArr['city'].', '.$responseArr['state'].' '.$responseArr['zip'].', '.$responseArr['country'];
                $responseArr['address']['value'] = $strAddress;
                $responseArr['address']['status'] = @$this->objCommonFunc->stringMatching($paramBusinessDetails['completeAddress'], $arrBusinessDetail->location->street);
                $responseArr['phoneNumber']['value'] = @$arrBusinessDetail->phone;
                $responseArr['phoneNumber']['status'] = @$this->objCommonFunc->phoneNumberMatching($paramBusinessDetails['phoneNumber'], $arrBusinessDetail->phone);
                $responseArr['averageRating'] = 0;
                $responseArr['totalReviews'] = 0;
                $responseArr['customerReviewDetails']['happy_customer'] = array();
                $responseArr['customerReviewDetails']['unhappy_customer'] = array();
                $responseArr['allReviewDetails']    = array();
                if(isset($arrBusinessDetail->id))
                    $responseArr['businessUrl']         = "http://facebook.com/".$arrBusinessDetail->id;
                else
                    $responseArr['businessUrl']         = "";

                #store in Directory table
                $BusinessTable                       = $this->getController()->getServiceLocator()->get("Application\Model\BusinessTable");

                $objBusiness                         = new Business();
                $objBusiness->intBusinessId          = $paramBusinessDetails['intBusinessId'];
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
                $objBusiness->enmDirectoryType       = 'facebook';
                $objBusiness->strAverageRating       = 0;
                $objBusiness->intTotalReview         = 0;

                $intDirectoryId                      = $BusinessTable->fncDIRAddDirectory($objBusiness);
            } else {

               $responseArr = $this->objCommonFunc->blankArrayOutput();
            }
        } else {
           $responseArr = $this->objCommonFunc->blankArrayOutput();
        }
        return $responseArr;
    }

}
?>
