<?php
namespace Vokuro\Models;
use Phalcon\Mvc\Model;

class GoogleScanning extends Model {

    #array for input parameters for other directories
    public $inputParamsForAPIs = array();
    public $googleApiKey = 'AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20';
    
    
     /**
     * This finds business details
     * ModifiedBy
     * @return Json
     */
    public function get_business($google_place_id) {      
      $strFindPlaceDetail = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $google_place_id . "&key=" . $this->googleApiKey;
//echo '<pre>$strFindPlaceDetail:'.print_r($strFindPlaceDetail,true).'</pre>';
      $resultFindPlaceDetail = file_get_contents($strFindPlaceDetail);
      $arrResultFindPlaceDetail = json_decode($resultFindPlaceDetail, true);

//echo '<pre>$arrResultFindPlaceDetail[result]:'.print_r($arrResultFindPlaceDetail,true).'</pre>';
      return $arrResultFindPlaceDetail['result'];
    }

     /**
     * This is Search result using Google place API.
     * ModifiedBy
     * @return Json
     */
    public function googleSearchApiResult($businessName, $zip, &$returnreviews) {
            $BusinessTable  = $this->getController()->getServiceLocator()->get("Application\Model\BusinessTable");
            try {
                #call map api
                $keyword = urlencode($businessName . ', ' . $zip);

                if (!empty($businessName)) {

                    #get place of an business with business name and zip using Google API
                    $strFindPlaceUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . $keyword . "&key=" . $this->googleApiKey;
                    //echo '<p>$strFindPlaceUrl:'.$strFindPlaceUrl.'</p>';
                    $resultFindPlace = file_get_contents($strFindPlaceUrl);
                    $arrResultFindPlace = json_decode($resultFindPlace, true);

                    #get place details from place ID using Google API
                    if (!empty($arrResultFindPlace['results']) && $arrResultFindPlace['status'] == "OK") {

                        #initialize variables
                        $intCounter = 0;
                        $responseArr = array();
                        $returnReviewDetails = array();

                        foreach ($arrResultFindPlace['results'] as $singleResultFindPlace) {
                            #break after 10 result
                            if ($intCounter == 1)
                                break;

                            $strPlaceId = $singleResultFindPlace['place_id'];

                            $strFindPlaceDetail = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $strPlaceId . "&key=" . $this->googleApiKey;
                            $resultFindPlaceDetail = file_get_contents($strFindPlaceDetail);
                            $arrResultFindPlaceDetail = json_decode($resultFindPlaceDetail, true);

//echo '<pre>$arrResultFindPlaceDetail[result]:'.print_r($arrResultFindPlaceDetail['result'],true).'</pre>';
                            $returnAddress       = @$arrResultFindPlaceDetail['result']['formatted_address'];
                            $returnPhoneNumber   = @$arrResultFindPlaceDetail['result']['formatted_phone_number'];
                            $returnBusinessName  = @$arrResultFindPlaceDetail['result']['name'];
                            $returnAverageRating = @$arrResultFindPlaceDetail['result']['rating'];
                            $returnTypes         = @$arrResultFindPlaceDetail['result']['types'];
                            $returnTypes         = array_diff($returnTypes, array('point_of_interest','establishment'));
                            $returnBusinessCat   = ucwords(str_replace("_", " ", implode(", ",$returnTypes)));
                            $returnbusinessTypes = @$arrResultFindPlaceDetail['result']['types'];
                            $returnTotalReviews  = @$arrResultFindPlaceDetail['result']['user_ratings_total'];
                            //$returnWebsite       = @$this->objCommonFunc->stringFormat($arrResultFindPlaceDetail['result']['website']);
                            $returnSimplifiedAddress= @$arrResultFindPlaceDetail['result']['vicinity'];
                            $returnLatitude      = @$arrResultFindPlaceDetail['result']['geometry']['location']['lat'];
                            $returnLongitude     = @$arrResultFindPlaceDetail['result']['geometry']['location']['lng'];
                            $returnBusinessUrl   = @$arrResultFindPlaceDetail['result']['url'];

                            //initialize variables
                            $returnCityLong = '';
                            $returnCityShort = '';
                            $returnStateLong = '';
                            $returnStateShort = '';
                            $returnCountryLong = '';
                            $returnCountryShort = '';
                            $returnZipLong = '';
                            $returnZipShort = '';

                            #getting all address components
                            if (!empty($arrResultFindPlaceDetail['result']['address_components'])) {
                                $intCountAddComponent = count($arrResultFindPlaceDetail['result']['address_components']);

                                for ($i = 0; $i <= $intCountAddComponent-1; $i++) {
                                    $strType = $arrResultFindPlaceDetail['result']['address_components'][$i]['types'][0];

                                    switch ($strType) {
                                        case "locality":
                                            $returnCityLong = @$arrResultFindPlaceDetail['result']['address_components'][$i]['long_name'];
                                            $returnCityShort = @$arrResultFindPlaceDetail['result']['address_components'][$i]['short_name'];
                                            $responseArr['cityLong'] = $returnCityLong;
                                            $responseArr['cityShort'] = $returnCityShort;
                                            break;
                                        case "postal_town":
                                            $returnCityLong = @$arrResultFindPlaceDetail['result']['address_components'][$i]['long_name'];
                                            $returnCityShort = @$arrResultFindPlaceDetail['result']['address_components'][$i]['short_name'];
                                            $responseArr['cityLong'] = $returnCityLong;
                                            $responseArr['cityShort'] = $returnCityShort;
                                            break;

                                        case "administrative_area_level_1";
                                            $returnStateLong = @$arrResultFindPlaceDetail['result']['address_components'][$i]['long_name'];
                                            $returnStateShort = @$arrResultFindPlaceDetail['result']['address_components'][$i]['short_name'];
                                            $responseArr['stateLong'] = $returnStateLong;
                                            $responseArr['stateShort'] = $returnStateShort;
                                            break;

                                        case "country":
                                            $returnCountryLong = @$arrResultFindPlaceDetail['result']['address_components'][$i]['long_name'];
                                            $returnCountryShort = @$arrResultFindPlaceDetail['result']['address_components'][$i]['short_name'];
                                            $responseArr['countryLong'] = $returnCountryLong;
                                            $responseArr['countryShort'] = $returnCountryShort;
                                            break;

                                        case "postal_code":
                                            $returnZipLong = @$arrResultFindPlaceDetail['result']['address_components'][$i]['long_name'];
                                            $returnZipShort = @$arrResultFindPlaceDetail['result']['address_components'][$i]['short_name'];
                                            $responseArr['zipLong'] = $returnZipLong;
                                            $responseArr['zipShort'] = $returnZipShort;
                                            break;
                                    }
                                }
                           }
                           #store in Database
                           $objBusiness                         = new Business();
                           $objBusiness->intAgencyId            = 1;//rand(1, 1000);
                           $objBusiness->strBusinessName        = $returnBusinessName;
                           //$objBusiness->strPhoneNumber         = $this->objCommonFunc->stringFormat($returnPhoneNumber, "phone");
                           $objBusiness->strDisplayPhoneNumber  = $returnPhoneNumber;
                           $objBusiness->strBusinessCategory    = $returnBusinessCat;

                           $intBusinessId                       = $BusinessTable->fncBSNAddBusiness($objBusiness);

                           #store in Directory table
                           $objBusiness->intBusinessId          = $intBusinessId;
                           $objBusiness->enmIsBusinessNameMatch = 1;
                           if (isset($returnAddress)) {
                              $objBusiness->strAddress = $returnAddress;
                           } else {
                              $objBusiness->strAddress = '';
                           }
                           if (isset($returnCityShort)) {
                              $objBusiness->strCityShort = $returnCityShort;
                           } else {
                              $objBusiness->strCityShort = '';
                           }
                           if (isset($returnCityLong)) {
                              $objBusiness->strCityLong = $returnCityLong;
                           } else {
                              $objBusiness->strCityLong = '';
                           }
                           if (isset($returnStateShort)) {
                              $objBusiness->strStateShort = $returnStateShort;
                           } else {
                              $objBusiness->strStateShort = '';
                           }
                           if (isset($returnStateLong)) {
                              $objBusiness->strStateLong = $returnStateLong;
                           } else {
                              $objBusiness->strStateLong = '';
                           }
                           if (isset($returnCountryShort)) {
                              $objBusiness->strCountryShort = $returnCountryShort;
                           } else {
                              $objBusiness->strCountryShort = '';
                           }
                           if (isset($returnCountryLong)) {
                              $objBusiness->strCountryLong = $returnCountryLong;
                           } else {
                              $objBusiness->strCountryLong = '';
                           }
                           $objBusiness->enmIsAddressMatch = 1;
                           if (isset($returnZipLong)) {
                              $objBusiness->strZipCode = $returnZipLong;
                           } else {
                              $objBusiness->strZipCode = '';
                           }
                           if (isset($returnPhoneNumber)) {
                              //$objBusiness->strPhoneNumber = $this->objCommonFunc->stringFormat($returnPhoneNumber, "phone");
                              $objBusiness->strDisplayPhoneNumber  = $returnPhoneNumber;
                           } else {
                              $objBusiness->strPhoneNumber = '';
                              $objBusiness->strDisplayPhoneNumber = '';
                           }
                           $objBusiness->enmIsPhoneNumberMatch  = 1;
                           if (isset($returnBusinessUrl)) {
                              $objBusiness->strWebsiteUrl = $returnBusinessUrl;
                           } else {
                              $objBusiness->strWebsiteUrl = '';
                           }
                           $objBusiness->enmDirectoryType       = 'google';
                           if (isset($returnAverageRating)) {
                              $objBusiness->strAverageRating = $returnAverageRating;
                           } else {
                              $objBusiness->strAverageRating = '';
                           }
                           if (isset($returnTotalReviews)) {
                              $objBusiness->intTotalReview = $returnTotalReviews;
                           } else {
                              $objBusiness->intTotalReview = '';
                           }

                           $intDirectoryId                      = $BusinessTable->fncDIRAddDirectory($objBusiness);

                           if(!empty($arrResultFindPlaceDetail['result']['reviews'])){
                           #getting all review details for happy and unhappy customer
                           $returnCustomerReviewDetails = $this->googleReviewsForHappyUnhappyCustomer(@$arrResultFindPlaceDetail['result']['reviews']);

                           #getting all reviews
                           $returnReviewDetails = $this->googleReviewsDetails(@$arrResultFindPlaceDetail['result']['reviews'], $intDirectoryId, $returnreviews);
                           }else{
                              $returnCustomerReviewDetails = array();
                              $returnReviewDetails = array();
                           }
                            # return response in json array
                            $responseArr['businessName']['value']   = $returnBusinessName;
                            $responseArr['businessName']['status']  = true;
                            $responseArr['address']['value']        = $returnAddress;
                            $responseArr['address']['status']       = true;
                            $responseArr['phoneNumber']['value']    = $returnPhoneNumber;
                            $responseArr['phoneNumber']['status']   = true;
                            $responseArr['averageRating']           = $returnAverageRating;
                            $responseArr['totalReviews']            = $returnTotalReviews;
                            $responseArr['website']                 = $returnWebsite;
                            $responseArr['businessCategory']        = $returnBusinessCat;
                            $responseArr['customerReviewDetails']   = $returnCustomerReviewDetails;
                            $responseArr['allReviewDetails']        = $returnReviewDetails;
                            $responseArr['simplifiedAdd']           = $returnSimplifiedAddress;
                            $responseArr['businessUrl']             = $returnBusinessUrl;
                            $responseArr['intBusinessId']           = $intBusinessId;

                            # create array as input paramerters for other directory and access that array globally

                            $inputParams = array();
                            $inputParams['businessName'] = strtolower($returnBusinessName);
                            $inputParams['address']      = strtolower($returnSimplifiedAddress);
                            $inputParams['completeAddress']= $returnAddress;
                            $inputParams['cityLong']     = $returnCityLong;
                            $inputParams['cityShort']    = $returnCityShort;
                            $inputParams['stateLong']    = $returnStateLong;
                            $inputParams['stateShort']   = $returnStateShort;
                            $inputParams['countryLong']  = $returnCountryLong;
                            $inputParams['countryShort'] = $returnCountryShort;
                            
                            if (isset($returnZipLong)) {
                              $inputParams['zipLong'] = $returnZipLong;
                            } else {
                              $inputParams['zipLong'] = '';
                            }
                            if (isset($returnZipShort)) {
                              $inputParams['zipShort'] = $returnZipShort;
                            } else {
                              $inputParams['zipShort'] = '';
                            }

                            //$inputParams['phoneNumber']  = $this->objCommonFunc->stringFormat($returnPhoneNumber, "phone");
                            $inputParams['latitude']     = $returnLatitude;
                            $inputParams['longitude']    = $returnLongitude;
                            $inputParams['businessType'] = $returnbusinessTypes;
                            $inputParams['intBusinessId']= $intBusinessId;

                            #assign array to global array
                            $this->inputParamsForAPIs = $inputParams;

                            #increment counter
                            $intCounter++;
                        }

                    }else {
                        #set response
                        if (isset($arrResultFindPlace['error_message'])) {
                           $responseArr = array('errorMsg' => $arrResultFindPlace['error_message']);
                        } else {
                           $responseArr = array('errorMsg' => 'Error status: '.$arrResultFindPlace['status']);
                        }
                    }
                } else {
                    #set response
                    $responseArr = array('errorMsg' => 'Result not found');
                }
            } catch (Exception $e) {

                #set response
                $responseArr = array('errorMsg' => 'There was an error processing your request.');
            }

        #return array
        return $responseArr;
    }

    /**
     * This is a Review details for happy and unhappy customers using Google place API.
     * @return Json
     */
    public function googleReviewsForHappyUnhappyCustomer($arrResultFindPlaceDetailReviews) {

        $arrReview = array();
        $intCountReviews = count($arrResultFindPlaceDetailReviews);

        if ($intCountReviews > 0) {
          foreach ($arrResultFindPlaceDetailReviews as $reviewDetails) {
            if ($reviewDetails['rating'] > 0) {
              if($reviewDetails['rating'] >= HAPPY_CUSTOMER_REVIEW){
                  $arrReview['authorName'] = @$reviewDetails['author_name'];
                  $arrReview['rating'] = @$reviewDetails['rating'];
                  $arrReview['authorUrl'] = @$reviewDetails['author_url'];
                  $arrReview['reviewText'] = @$reviewDetails['text'];
                  //$arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails['time']);
                  $arrReview['from'] = "Google+";
                  $returnReviewDetails['happy_customer'][] = $arrReview;

              }elseif ($reviewDetails['rating'] <= UNHAPPY_CUSTOMER_REVIEW) {
                  $arrReview['authorName'] = @$reviewDetails['author_name'];
                  $arrReview['rating'] = @$reviewDetails['rating'];
                  $arrReview['authorUrl'] = @$reviewDetails['author_url'];
                  $arrReview['reviewText'] = @$reviewDetails['text'];
                  //$arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails['time']);
                  $arrReview['from'] = "Google+";
                  $returnReviewDetails['unhappy_customer'][] = $arrReview;
              }
            }

          }
        }else {
          $returnReviewDetails[] = array();
        }

        return $returnReviewDetails;
    }

    /**
     * This is a All Review details using Google place API.
     * @author		    Amit Bindal <amit.bindal@classicinformatics.com>
     * @createddate         03th Sept, 2015
     * ModifiedBy
     * @return              Array
     */
    public function googleReviewsDetails($arrResultFindPlaceDetailReviews, $intDirectoryId, &$returnreviews) {
            $BusinessTable  = $this->getController()->getServiceLocator()->get("Application\Model\BusinessTable");

            $arrReview = array();
            $intCountReviews = count($arrResultFindPlaceDetailReviews);

            if ($intCountReviews > 0) {
                $objBusiness    = new Business();
                foreach ($arrResultFindPlaceDetailReviews as $reviewDetails) {

                        $arrReview['authorName'] = @$reviewDetails['author_name'];
                        $arrReview['rating'] = @$reviewDetails['rating'];
                        $arrReview['authorUrl'] = @$reviewDetails['author_url'];
                        $arrReview['reviewText'] = @$reviewDetails['text'];
                        //$arrReview['dateTime'] = $this->objCommonFunc->convertUnixTimestamp($reviewDetails['time']);
                        //$arrReview['dateTime'] = $reviewDetails['time'];
                        $arrReview['from'] = "Google+";
                        $returnReviewDetails[] = $arrReview;

                        //store review
                        $objBusiness->intDirectoryId    = $intDirectoryId;
                        $objBusiness->strAuthorName     = $arrReview['authorName'];
                        $objBusiness->strRating         = $arrReview['rating'];
                        $objBusiness->strDateTime       = $arrReview['dateTime'];
                        $objBusiness->strFrom           = 'google';
                        $objBusiness->strAuthorUrl      = $arrReview['authorUrl'];
                        $objBusiness->strReviewText     = addSlashes($arrReview['reviewText']);
                        $objBusiness->enmDirectoryType = 'google';
                        if($arrReview['rating'] > 3)
                            $objBusiness->enmReviewType     = 1;
                        else if($arrReview['rating'] == 3)
                            $objBusiness->enmReviewType     = 2;
                        else if($arrReview['rating'] < 3)
                            $objBusiness->enmReviewType     = 3;
                        $BusinessTable->fncDIRAddDirectoryReview($objBusiness, $returnreviews);
                }
            }else {
            $returnReviewDetails[] = array();
        }

        return $returnReviewDetails;
    }

}
?>
