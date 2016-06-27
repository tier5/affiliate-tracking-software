<?php

namespace Vokuro\Payments;

use \DateTime;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/*
 * 
 * authorize.net v1.8.8 
 */

class AuthorizeDotNet {

    const SANDBOX = 'https://sandbox.authorize.net';
    const PRODUCTION = 'http://www.authorize.net/';

    private $environment;
    private $apiLoginId;
    private $transactionKey;

    function __construct($config) {
        $this->apiLoginId = $config->authorizeDotNet->apiLoginId;
        $this->transactionKey = $config->authorizeDotNet->transactionKey;
        $this->environment = $config->application->environment;
    }

    /*
     * 
     * Customer Profiles
     *      
     */

    public function createCustomerProfile($parameters) {

        /* TODO: Add parameter validation */
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }

        // Create the merchant authentication type
        $merchantAuthentication = $this->createMerchantAuthenticationType();

        // Create the payment data for a credit card
        $hasPaymentProfileParameters = $this->hasPaymentProfileParameters($parameters);
        if ($hasPaymentProfileParameters) {
            $paymentCreditCard = $this->createCreditCardPayment($parameters);
            $customerAddress = $this->createCustomerAddress($parameters);
        }

        // Create a Customer Profile Request
        //  1. create a Payment Profile
        //  2. create a Customer Profile   
        //  3. Submit a CreateCustomerProfile Request
        //  4. Validate Profiiel ID returned
        if ($hasPaymentProfileParameters) {
            $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
            $paymentProfile->setCustomerType($parameters['customerType']);
            $paymentProfile->setBillTo($customerAddress['billTo']);
            $paymentProfile->setPayment($paymentCreditCard);
            $paymentProfiles[] = $paymentProfile;
        }
        $refId = 'ref' . time();
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription($parameters['customerProfileDescription']);
        $customerProfile->setMerchantCustomerId('M_' . $refId);
        $customerProfile->setEmail($parameters['email']);
        $customerProfile->addToShipToList($customerAddress['customerShippingAddress']);   
        if ($hasPaymentProfileParameters) {
            $customerProfile->setPaymentProfiles($paymentProfiles);
        }

        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setProfile($customerProfile);
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && $response->getMessages()->getResultCode() == "Ok") {
            $customerProfileId = $response->getCustomerProfileId();
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();
            $shippingAddresses = $response->getCustomerShippingAddressIdList();
            return [
                'customerProfileId' => $customerProfileId,
                'customerPaymentProfileId' => $paymentProfiles[0],
                'shippingAddressId' => $shippingAddresses[0]
            ];
        } else {

            $errorMessages = $response->getMessages()->getMessage();

        }
        
        return false;
    }

    public function getCustomerProfile($parameters) {
        /* TODO: Add parameter validation */
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }

        // Create the merchant authentication type
        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\GetCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($parameters['customerProfileId']);
        $controller = new AnetController\GetCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            $customerProfile = [];
            $customerProfile['customerProfile'] = $response->getProfile();
            $customerProfile['paymentProfiles'] = $response->getProfile()->getPaymentProfiles();
            $customerProfile['shippingAddresses'] = $response->getProfile()->getShipToList();
            if (method_exists($response, 'getSubscriptionIds')) {
                $customerProfile['subscriptionIds'] = $response->getSubscriptionIds();
            }
            return $customerProfile;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            echo "<PRE>";
            print_r($errorMessages);
            die;
        }

        return false;
    }

    public function deleteCustomerProfile($parameters) {
        /* TODO: Add parameter validation */

        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            return true;
        }

        $refId = 'ref' . time();

        // Create the merchant authentication type
        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\DeleteCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($parameters['customerProfileId']);

        $controller = new AnetController\DeleteCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && $response->getMessages()->getResultCode() == "Ok") {
            return true;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    /*
     * 
     * Payment Profiles
     * 
     */

    public function createPaymentProfileForCustomer($parameters) {
        /* TODO: Add parameter validation */
        $validationMode = "liveMode";

        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $hasPaymentProfileParameters = $this->hasPaymentProfileParameters($parameters);
        if (!$hasPaymentProfileParameters) {
            return false;
        }

        $paymentCreditCard = $this->createCreditCardPayment($parameters);
        $customerAddress = $this->createCustomerAddress($parameters);

        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType($parameters['customerType']);
        $paymentProfile->setBillTo($customerAddress['billto']);
        $paymentProfile->setPayment($paymentCreditCard);

        $paymentProfileRequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentProfileRequest->setMerchantAuthentication($merchantAuthentication);
        $paymentProfileRequest->setCustomerProfileId($parameters['customerProfileId']);
        $paymentProfileRequest->setPaymentProfile($paymentProfile);
        $paymentProfileRequest->setValidationMode($validationMode);

        $controller = new AnetController\CreateCustomerPaymentProfileController($paymentProfileRequest);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getCustomerPaymentProfileId();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }
        return false;
    }

    public function updatePaymentProfileForCustomer($parameters) {
        $validationMode = "none";

        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        // Set profile ids of profile to be updated
        $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($parameters['customerProfileId']);
        $controller = new AnetController\GetCustomerProfileController($request);

        // Create the credit card update
        $creditCardPayment = $this->createCreditCardPayment($parameters);

        // Create the address
        $customerAddress = $this->createCustomerAddress($parameters);

        // Create the Customer Payment Profile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileExType();
        $paymentProfile->setCustomerPaymentProfileId($parameters['customerPaymentProfileId']);
        $paymentProfile->setBillTo($customerAddress['billTo']);
        $paymentProfile->setPayment($creditCardPayment);

        // Submit a UpdatePaymentProfileRequest
        $request->setPaymentProfile($paymentProfile);

        $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    public function getPaymentProfileForCustomer($parameters) {
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($parameters['customerProfileId']);
        $request->setCustomerPaymentProfileId($parameters['customerPaymentProfileId']);

        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getPaymentProfile();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    public function validatePaymentProfileForCustomer($parameters) {
        $validationMode = "liveMode";
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\ValidateCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($parameters['customerProfileId']);
        $request->setCustomerPaymentProfileId($parameters['customerPaymentProfileId']);
        $request->setValidationMode($validationMode);

        $controller = new AnetController\ValidateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getMessages()->getMessage();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    public function deletePaymentProfileForCustomer($parameters) {
        $environment = self::SANDBOX;  // \net\authorize\api\constants\ANetEnvironment::
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($parameters['customerProfileId']);
        $request->setCustomerPaymentProfileId($parameters['customerPaymentProfileId']);
        $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    /*
     * Recurring Payments
     */

    public function createSubscriptionForCustomer($parameters) {
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();


        // Subscription
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($parameters['subscriptionName']);
        
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($parameters['intervalLength']);
        $interval->setUnit($parameters['unit']);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime($parameters['startDate']));
        $paymentSchedule->setTotalOccurrences($parameters['totalOccurences']);
        if (array_key_exists('trialOccurences', $parameters)) {
            $paymentSchedule->setTrialOccurrences($parameters['trialOccurences']);
        }

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($parameters['amount']);
        if (array_key_exists('trialOccurences', $parameters)) {
            $subscription->setTrialAmount($parameters['trialAmount']);
        }
        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($parameters['customerProfileId']);
        $profile->setCustomerPaymentProfileId($parameters['customerPaymentProfileId']);
        $profile->setCustomerAddressId($parameters['customerAddressId']);

        echo "<PRE>";
        print_r($parameters);
        die();

        $subscription->setProfile($profile);

        /* Send the request */
        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getSubscriptionId();
            print_r($response);
            die;
        } else {

            $errorMessages = $response->getMessages()->getMessage();
            print_r($errorMessages);
            die;
        }

        return false;
    }

    public function getSubscriptionForCustomer($parameters) {
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        // Creating the API Request with required parameters
        $request = new AnetAPI\ARBGetSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($parameters['subscriptionId']);
        $controller = new AnetController\ARBGetSubscriptionController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getSubscription();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
        }

        return false;
    }

    public function updateSubscriptionForCustomer($parameters) {
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $this->updateIntervalFields($parameters, $interval);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        // $paymentSchedule->setInterval($interval);
        $this->updatePaymentScheduleFields($parameters, $paymentSchedule);

        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setPaymentSchedule($paymentSchedule);
        $this->updateSubscriptionFields($parameters, $subscription);
        $request = new AnetAPI\ARBUpdateSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($parameters['subscriptionId']);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBUpdateSubscriptionController($request);
        $response = $controller->executeWithApiResponse($environment);
        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            print_r($errorMessages);
            die;
        }

        return false;
    }

    public function cancelSubscriptionForCustomer($parameters) {
        $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        if ($this->environment === 'production') {
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        $refId = 'ref' . time();

        $merchantAuthentication = $this->createMerchantAuthenticationType();

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($parameters['subscriptionId']);
        $controller = new AnetController\ARBCancelSubscriptionController($request);
        $response = $controller->executeWithApiResponse($environment);

        if ($response && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            print_r($errorMessages);
        }

        return false;
    }

    /*
     * Transactions 
     */

    private function createMerchantAuthenticationType() {
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->apiLoginId);
        $merchantAuthentication->setTransactionKey($this->transactionKey);
        return $merchantAuthentication;
    }

    private function createCreditCardPayment($parameters) {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($parameters['cardNumber']);
        $creditCard->setExpirationDate($parameters['cardExpiryDate']);
        $creditCard->setCardCode($parameters['cardCode']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);
        return $paymentCreditCard;
    }

    private function createCustomerAddress($parameters) {
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($parameters['firstName']);
        $billto->setLastName($parameters['lastName']);
        $billto->setCompany($parameters['companyName']);
        $billto->setAddress($parameters['companyAddress']);
        $billto->setCity($parameters['city']);
        $billto->setState($parameters['state']);
        $billto->setZip($parameters['zip']);
        $billto->setCountry($parameters['country']);

        // Create the customer shipping address
        $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName($parameters['firstName']);
        $customerShippingAddress->setLastName($parameters['lastName']);
        $customerShippingAddress->setCompany($parameters['companyName']);
        $customerShippingAddress->setAddress($parameters['companyAddress']);
        $customerShippingAddress->setCity($parameters['city']);
        $customerShippingAddress->setState($parameters['state']);
        $customerShippingAddress->setZip($parameters['zip']);
        $customerShippingAddress->setCountry($parameters['country']);
        $customerShippingAddress->setPhoneNumber("XXX-XXX-XXXX");
        $customerShippingAddress->setFaxNumber("XXX-XXX-XXXX");

        return [
            'billTo' => $billto,
            'customerShippingAddress' => $customerShippingAddress
        ];
    }

    private function hasCreditCardParameters($parameters) {
        $parameterKeys = array_keys($parameters);
        $paymentParameters = [
            'cardNumber',
            'cardExpiryDate',
            'cardCode'
        ];
        $result = array_intersect_key($parameterKeys, $paymentParameters);
        return count($result) == count($paymentParameters) ? true : false;
    }

    private function hasPaymentProfileParameters($parameters) {
        $parameterKeys = array_keys($parameters);
        $paymentParameters = [
            'cardNumber',
            'cardExpiryDate',
            'cardCode',
            'customerType',
            'firstName',
            'lastName',
            'companyName',
            'companyAddress',
            'city',
            'state',
            'zip',
            'country',
            'customerType'
        ];
        $result = array_intersect_key($parameterKeys, $paymentParameters);
        return count($result) == count($paymentParameters) ? true : false;
    }

    private function updatePaymentProfileFields($parameters, &$paymentProfile) {

        $billTo = $paymentProfile->getBillTo();

        foreach ($parameters as $parameter => $value) {

            switch ($parameter) {
                case 'cardNumber':
                case 'cardExpiryDate':
                case 'cardCode':
                    break;
                case 'firstName':
                    $billTo->setFirstName($parameters['firstName']);
                    break;
                case 'lastName':
                    $billTo->setLastName($parameters['lastName']);
                    break;
                case 'companyName':
                    $billTo->setCompany($parameters['companyName']);
                    break;
                case 'companyAddress':
                    $billTo->setAddress($parameters['companyAddress']);
                    break;
                case 'city':
                    $billTo->setCity($parameters['city']);
                    break;
                case 'state':
                    $billTo->setState($parameters['state']);
                    break;
                case 'zip':
                    $billTo->setZip($parameters['zip']);
                    break;
                case 'country':
                    $billTo->setCountry($parameters['country']);
                    break;
                case 'customerType':
                    $paymentProfile->setCustomerType($parameters['customerType']);
                    break;
                default:
                    break;
            }
        }
    }

    private function updateSubscriptionFields($parameters, &$subscription) {

        foreach ($parameters as $parameter => $value) {

            switch ($parameter) {
                case 'subscriptionName':
                    // $subscription->setName($parameters['subscriptionName']);
                    break;
                case 'amount':
                    $subscription->setAmount($parameters['amount']);
                    break;
                case 'trialAmount':
                    // $subscription->setTrialAmount($parameters['trialAmount']);
                    break;
                default:
                    break;
            }
        }
    }

    private function updateIntervalFields($parameters, &$interval) {
        foreach ($parameters as $parameter => $value) {
            switch ($parameter) {
                case 'intervalLength':
                    $interval->setLength($parameters['intervalLength']);
                    break;
                case 'unit':
                    $interval->setUnit($parameters['unit']);
                    break;
                default:
                    break;
            }
        }
        return $interval;
    }

    private function updatePaymentScheduleFields($parameters, &$paymentSchedule) {
        foreach ($parameters as $parameter => $value) {
            switch ($parameter) {
                case 'startDate':
                    $paymentSchedule->setStartDate(new \DateTime($parameters['startDate']));
                    break;
                case 'totalOccurences':
                    $paymentSchedule->setTotalOccurrences($parameters['totalOccurences']);
                    break;
                case 'trialOccurences':
                    $paymentSchedule->setTrialOccurrences($parameters['trialOccurences']);
                    break;
                default:
                    break;
            }
        }
        return $paymentSchedule;
    }

}
