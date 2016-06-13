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
        $class = $this->getProviderClass($paymentParams['provider']);
        
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
        $class = $this->getProviderClass($paymentParams['provider']);
        
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
        $authorizeDotNet = new AuthorizeDotNetPayment($this->config);
        
        $user = Users::query()
            ->where("id = :id:")
            ->bind(["id" => $ccParameters['userId']])
            ->execute()
            ->getFirst();
        $agency = Agency::query()
            ->where("id = :id:")
            ->bind(["id" => $user->agency_id])
            ->execute()
            ->getFirst();
        
        $parameters['customerType'] = 'individual';
	$parameters['customerProfileDescription'] = 'Empty';
        $parameters['email'] = $user['email'];
	$parameters['cardNumber'] = $ccParameters['cardNumber'];
	$parameters['cardExpiryDate'] = $ccParameters['expirationDate'];
	$parameters['cardCode'] = $ccParameters['csv'];
        $parameters['firstName'] = $user['name'];
	$parameters['lastName'] = "";
	$parameters['companyName'] = $agency["name"];
	$parameters['companyAddress'] = $agency["address"];
	$parameters['city'] = "Los Angeles";
	$parameters['state'] = $agency["state_province"];
	$parameters['zip'] = $agency["postal_code"];
	$parameters['country'] = $agency["country"];
        
        return $authorizeDotNet->createCustomerProfile($parameters); 
    }
    
    private function updateAuthorizeDotNetPaymentProfile($ccParameters) {
        $authorizeDotNet = new AuthorizeDotNetPayment($this->config);
        
        /* REFACTOR: For the time being, we have to pull the full set of user 
         * data in again on update calls as the API functionality for "field"
         * specific updates doesn't work.  We'll keep an eye this.  MT, 2016 
         */
        $user = Users::query()
            ->where("id = :id:")
            ->bind(["id" => $ccParameters['userId']])
            ->execute()
            ->getFirst();
        $agency = Agency::query()
            ->where("id = :id:")
            ->bind(["id" => $user->agency_id])
            ->execute()
            ->getFirst();
        
        $parameters['customerType'] = 'individual';
	$parameters['customerProfileDescription'] = 'Empty';
        $parameters['email'] = $user['email'];
	$parameters['cardNumber'] = $ccParameters['cardNumber'];
	$parameters['cardExpiryDate'] = $ccParameters['expirationDate'];
	$parameters['cardCode'] = $ccParameters['csv'];
        $parameters['firstName'] = $user['name'];
	$parameters['lastName'] = "";
	$parameters['companyName'] = $agency["name"];
	$parameters['companyAddress'] = $agency["address"];
	$parameters['city'] = "Los Angeles";
	$parameters['state'] = $agency["state_province"];
	$parameters['zip'] = $agency["postal_code"];
	$parameters['country'] = $agency["country"];
        
        return $authorizeDotNet->updatePaymentProfileForCustomer($parameters);
    }
    
}
