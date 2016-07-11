<?php

namespace Vokuro\Services;

use Vokuro\Models\Users;
use Vokuro\Models\Agency;
use Vokuro\Services\ServicesConsts;
use Vokuro\Models\AuthorizeDotNet as AuthorizeDotNetModel;
use Vokuro\Payments\AuthorizeDotNet as AuthorizeDotNetPayment;

class PaymentService extends BaseService {
        
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getRegisteredCardType($userId, $provider) {
        $class = $this->getProviderClass($provider);
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $creditCard = AuthorizeDotNetModel::query()
                    ->where("user_id = :userId:")
                    ->bind(["userId" => $userId])
                    ->execute()
                    ->getFirst();
            break;
            default:
                break;
        }
        return $creditCard ? $creditCard->credit_card_type : false;
    }
    
    public function hasPaymentProfile($paymentParams) {
        $class = $this->getProviderClass($paymentParams['provider']);
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $creditCard = AuthorizeDotNetModel::query()
                    ->where("user_id = :userId:")
                    ->bind(["userId" => $paymentParams['userId']])
                    ->execute()
                    ->getFirst();
                $status = $creditCard ? true : false;
                break;
            default:
                $status = false;
                break;
        }
        return $status;    
    }
    
    public function createPaymentProfile($ccParameters) {
        $class = $this->getProviderClass($ccParameters['provider']);
        
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $status = $this->createAuthorizeDotNetPaymentProfile($ccParameters);
                break;
            default:
                $status = false;
                break;
        }
        
        return $status;
    }
    
    public function updatePaymentProfile($ccParameters) {
        $class = $this->getProviderClass($ccParameters['provider']);
        
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $status = $this->updateAuthorizeDotNetPaymentProfile($ccParameters);
                break;
            default:
                $status = false;
                break;
        }
        
        return $status;
    }
    
    public function changeSubscription($subscriptionParameters) {
        $class = $this->getProviderClass($subscriptionParameters['provider']);
        
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $status = $this->changeAuthorizeDotNetSubscription($subscriptionParameters);
                break;
            default:
                $status = false;
                break;
        }
        
        return $status;
    }
    
    private function getProviderClass($provider) {
        $providerClass = null;
        
        switch($provider) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $providerClass = ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET; 
                break;
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
            default:
                break;
        }
        
        return $providerClass;
    }
    
    private function createAuthorizeDotNetPaymentProfile($ccParameters) {
        
        /* Check parameters */
        $required = ['userEmail', 'cardNumber', 'expirationDate', 'csv'];
        $supplied = array_keys($ccParameters); 
        $intersect = array_intersect($supplied, $required);
        if ( count($intersect) !== count($required)) {
            return false;
        }
        
        /* Assemble customer profile parameters */
        $customerProfileParameters['customerType']                 = 'individual';
        $customerProfileParameters['customerProfileDescription']   = 'Empty';
        $customerProfileParameters['email']                        = $ccParameters['userEmail'];
        $customerProfileParameters['cardNumber']                   = $ccParameters['cardNumber'];
        $customerProfileParameters['cardExpiryDate']               = $ccParameters['expirationDate'];
        $customerProfileParameters['cardCode']                     = $ccParameters['csv'];
        $customerProfileParameters['firstName']                    = isset($ccParameters['userName'])              ? $ccParameters['userName']             : 'Required';
        $customerProfileParameters['lastName']                     = isset($ccParameters['lastName'])              ? $ccParameters['lastName']             : "Required";
        $customerProfileParameters['companyName']                  = isset($ccParameters['agencyName'])            ? $ccParameters['agencyName']           : '';
        $customerProfileParameters['companyAddress']               = isset($ccParameters['agencyAddress'])         ? $ccParameters['agencyAddress']        : '';
        $customerProfileParameters['city']                         = isset($ccParameters['agencyCity'])            ? $ccParameters['agencyCity']           : '';
        $customerProfileParameters['state']                        = isset($ccParameters['agencyStateProvince'])   ? $ccParameters['agencyStateProvince']  : '';
        $customerProfileParameters['zip']                          = isset($ccParameters['agencyPostalCode'])      ? $ccParameters['agencyPostalCode']     : '';
        $customerProfileParameters['country']                      = isset($ccParameters['agencyCountry'])         ? $ccParameters['agencyCountry']        : '';
        
        $authorizeDotNet = new AuthorizeDotNetPayment($this->config);
        
        $profile = $authorizeDotNet->createCustomerProfile($customerProfileParameters);
        if (!$profile) {
            return false;
        }
        
        $authorizeDotNetModel = new AuthorizeDotNetModel();
        $authorizeDotNetModel->user_id = $ccParameters['userId'];
        $authorizeDotNetModel->customer_profile_id = $profile['customerProfileId'];
        if(!$authorizeDotNetModel->create()) {
            return false;
        }
        
        return $profile;
    }
    
    private function updateAuthorizeDotNetPaymentProfile($ccParameters) {
        $authorizeDotNet = new AuthorizeDotNetPayment($this->config);
        
        $creditCard = AuthorizeDotNetModel::query()
            ->where("user_id = :userId:")
            ->bind(["userId" => $ccParameters['userId']])
            ->execute()
            ->getFirst();
        if(!$creditCard){
            return false;
        }
        
        /* Get the customer payment profile */    
        $customerProfile = $authorizeDotNet->getCustomerProfile([ 'customerProfileId' => $creditCard->customer_profile_id ]);  
        if (!$customerProfile) {
            return false;
        }
        $customerPaymentProfile = $customerProfile['paymentProfiles'][0];
        
        $parameters['customerProfileId'] = $creditCard->customer_profile_id;
        $parameters['customerPaymentProfileId'] = $customerPaymentProfile->getCustomerPaymentProfileId();
        $parameters['customerType'] = 'individual';
        $parameters['customerProfileDescription'] = 'Empty';
        $parameters['email'] = $ccParameters['userEmail']; 
        $parameters['cardNumber'] = $ccParameters['cardNumber'];
        $parameters['cardExpiryDate'] = $ccParameters['expirationDate'];
        $parameters['cardCode'] = $ccParameters['csv'];
        $parameters['firstName'] = $ccParameters['userName'];
        $parameters['lastName'] = "Required";
        $parameters['companyName'] = $ccParameters['agencyName'];
        $parameters['companyAddress'] = $ccParameters['agencyAddress'];
        $parameters['city'] = $ccParameters['agencyCity'];
        $parameters['state'] = $ccParameters['agencyStateProvince'];
        $parameters['zip'] = $ccParameters['agencyPostalCode'];
        $parameters['country'] = $ccParameters['agencyCountry'];
        
        return $authorizeDotNet->updatePaymentProfileForCustomer($parameters);
    }
    
    private function changeAuthorizeDotNetSubscription($subscriptionParameters) {

        /* Get the customer profile */
        $authorizeDotNetModel = AuthorizeDotNetModel::query()
            ->where("user_id = :userId:")
            ->bind(["userId" => $subscriptionParameters['userId']])
            ->execute()
            ->getFirst();
        if (!$authorizeDotNetModel) {
            return false;
        }
        
        /* Get the customer payment profile */
        $authorizeDotNetPayment = new AuthorizeDotNetPayment($this->config);
        if (!$authorizeDotNetPayment) {
            return false;
        }
        
        $parameters = [];
        $subscriptionId = $authorizeDotNetModel->subscription_id;

        if($subscriptionId === 'N') {
            
            $parameters['customerProfileId'] = $authorizeDotNetModel->customer_profile_id;
            
            /* Get the customer payment profile */    
            $customerProfile = $authorizeDotNetPayment->getCustomerProfile($parameters);
            if (!$customerProfile) {
                return false;
            }
            
            // Get customer billing info
            $customerPaymentProfile = $customerProfile['paymentProfiles'][0];
            $shippingAddresses = $customerProfile['shippingAddresses'][0];
            
            $parameters['billTo'] = $customerPaymentProfile->getBillTo();
            $parameters['customerPaymentProfileId'] = $customerPaymentProfile->getCustomerPaymentProfileId();
            $parameters['customerAddressId'] = $shippingAddresses->getCustomerAddressId();
            $parameters['subscriptionName'] = "Review Velocity Subscription";
            $parameters['intervalLength'] = $this->config->authorizeDotNet->intervalLength;
            $parameters['unit'] = $this->config->authorizeDotNet->unit;
            $parameters['startDate'] = date("Y-m-d");
            $parameters['totalOccurences'] = $this->config->authorizeDotNet->totalOccurences;
            $parameters['amount'] = round($subscriptionParameters['price'], 2);
            
            $subscriptionId = $authorizeDotNetPayment->createSubscriptionForCustomer($parameters);
            if (!$subscriptionId) {
                return false;
            }
            
            $authorizeDotNetModel->subscription_id = $subscriptionId;
            if(!$authorizeDotNetModel->update()) {
                return false;
            }
            
        } else {
            
            $parameters['subscriptionId'] = $subscriptionId;
            $parameters['amount'] = $subscriptionParameters['price'];
            
            $status = $authorizeDotNetPayment->updateSubscriptionForCustomer($parameters);
            if(!$status) {
                return false;
            }
            
        }
        
        return true;
    }
}
