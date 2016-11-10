<?php

namespace Vokuro\Services;

use Vokuro\Models\StripeSubscriptions;
use Vokuro\Models\Users;
use Vokuro\Models\Agency;
use Vokuro\Services\ServicesConsts;
use Vokuro\Models\AuthorizeDotNet as AuthorizeDotNetModel;
use Vokuro\Payments\AuthorizeDotNet as AuthorizeDotNetPayment;

class PaymentService extends BaseService {
        
    function __construct($config, $di) {
        parent::__construct($config, $di);
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
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
                // We don't store credit card type for Stripe
                return false;

            default:
                break;
        }
        return $creditCard ? $creditCard->credit_card_type : false;
    }

    public function getPaymentProfile($paymentParams) {
        $class = $this->getProviderClass($paymentParams['provider']);
        switch($class) {
            case ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET:
                $profile = AuthorizeDotNetModel::query()
                    ->where("user_id = :userId:")
                    ->bind(["userId" => $paymentParams['userId']])
                    ->execute()
                    ->getFirst();
                $paymentProfile = [
                    'id'                => $profile->id,
                    'user_id'           => $profile->user_id,
                    'customer_id'       => $profile->customer_profile_id,
                    'subscription_id'   => $profile->subscription_id,
                    'provider'          => $class,
                ];
                return $paymentProfile;
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
                $profile = StripeSubscriptions::query()
                    ->where("user_id = :userId:")
                    ->bind(["userId" => $paymentParams['userId']])
                    ->execute()
                    ->getFirst();
                $paymentProfile = [
                    'id'                => $profile->id,
                    'user_id'           => $profile->user_id,
                    'customer_id'       => $profile->stripe_customer_id,
                    'subscription_id'   => $profile->stripe_subscription_id,
                    'provider'          => $class,
                ];
                return $paymentProfile;
        }
        return false;
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
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
                $creditCard = StripeSubscriptions::query()
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
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
                $status = $this->createStripePaymentProfile($ccParameters);
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
                $status = $this->updateStripePaymentProfile($ccParameters);
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
            case ServicesConsts::$PAYMENT_PROVIDER_STRIPE:
                $status = $this->changeStripeSubscription($subscriptionParameters);
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
                $providerClass = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
                break;
            default:
                break;
        }
        
        return $providerClass;
    }

    private function createStripePaymentProfile($ccParameters) {
        /* Check parameters */
        $required = ['tokenID', 'userEmail'];
        $supplied = array_keys($ccParameters);
        $intersect = array_intersect($supplied, $required);

        if ( count($intersect) !== count($required)) {
            return false;
        }


        $ccParameters['type'] = $ccParameters['type'] ?: 'Business';

        $responseParameters = ['status' => false];
        $userId = $ccParameters['userId'];

        $user = Users::query()
            ->where("id = :id:")
            ->bind(["id" => $userId])
            ->execute()
            ->getFirst();
        if($ccParameters['type'] == 'Business') {
            $objBusiness = Agency::query()
                ->where("agency_id = :agency_id:")
                ->bind(["agency_id" => $user->agency_id])
                ->execute()
                ->getFirst();

            $objAgency = Agency::findFirst("agency_id = {$objBusiness->parent_id}");

            $StripeSecretKey = $objBusiness->parent_id == -1 ? $this->config->stripe->secret_key : $objAgency->stripe_account_secret;
        } else {
             $StripeSecretKey = $this->config->stripe->secret_key;
        }

        if(!$StripeSecretKey) {
            $responseParameters['errors'] = "Invalid stripe key";
            return $responseParameters;
        }

        try {
            \Stripe\Stripe::setApiKey($StripeSecretKey);
            $Customer = \Stripe\Customer::create([
                'email'     => $ccParameters['userEmail'],
                'source'    => $ccParameters['tokenID'],
            ]);

            if($Customer->id) {
                $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$userId}");
                if (!$objStripeSubscription)
                    $objStripeSubscription = new \Vokuro\Models\StripeSubscriptions();

                $objStripeSubscription->stripe_customer_id = $Customer->id;
                $objStripeSubscription->user_id = $userId;
                $objStripeSubscription->stripe_subscription_id = 'N';

                if ($objStripeSubscription->save())
                    $responseParameters['status'] = true;
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return $responseParameters;
    }

    private function changeStripeSubscription($ccParameters) {
        /* Check parameters */
        $required = ['userId'];
        $supplied = array_keys($ccParameters);
        $intersect = array_intersect($supplied, $required);

        if ( count($intersect) !== count($required))
            return false;

        if($ccParameters['type'] == 'Business' && !$ccParameters['planType'])
            return false;

        if($ccParameters['type'] == 'Agency' && !$ccParameters['amount'])
            return false;

        $responseParameters = ['status' => false];
        $userId = $ccParameters['userId'];

        $ccParameters['type'] = $ccParameters['type'] ?: 'Business';

        $user = Users::query()
            ->where("id = :id:")
            ->bind(["id" => $userId])
            ->execute()
            ->getFirst();
        $agency = Agency::query()
            ->where("agency_id = :agency_id:")
            ->bind(["agency_id" => $user->agency_id])
            ->execute()
            ->getFirst();

        // Is a business
        if($agency->parent_id > 0) {
            $objParentAgency = Agency::findFirst("agency_id = {$agency->parent_id}");
        }

        $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$userId}");
        if(!$objStripeSubscription->stripe_customer_id)
            return false;

        //GARY_TODO: This needs to change, saving for last.  Not sure on behavior if stripe key not available.
        $StripeSecretKey = $agency->parent_id > 0 ? $objParentAgency->stripe_account_secret : $this->config->stripe->secret_key;

        try {
            \Stripe\Stripe::setApiKey($StripeSecretKey);

            $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$userId}");
            if (!$objStripeSubscription)
                return false;

            $PlanID = strtolower($ccParameters['type']) . '_plan_' . $user->agency_id;
            $Name = $ccParameters['type'] . " Plan {$user->agency_id}";
            $subscriptionManager = $this->di->get('subscriptionManager');

            if($objStripeSubscription->stripe_subscription_id != 'N' && $objStripeSubscription->stripe_subscription_id) {
                // Delete plan first
                $StripePlan = \Stripe\Plan::retrieve($PlanID);
                $StripePlan->delete();
            }

            $subscriptionManager = $this->di->get('subscriptionManager');
            $Interval = $ccParameters['planType'] == 'Annually' ? 'year' : 'month';

            $Amount = $ccParameters['type'] == 'Agency' ? $ccParameters['amount'] : $subscriptionManager->getSubscriptionPrice($userId, $ccParameters['planType']) * 100;

            \Stripe\Plan::create([
                'amount'    => $Amount,
                'interval'  => $Interval,
                'name'      => $Name,
                'currency'  => 'usd',
                'id'        => $PlanID
            ]);

            if($objStripeSubscription->stripe_subscription_id != 'N' && $objStripeSubscription->stripe_subscription_id) {
                $StripeSubscription = \Stripe\Subscription::retrieve($objStripeSubscription->stripe_subscription_id);
            } else {
                $StripeSubscription = \Stripe\Subscription::create([
                    'customer' => $objStripeSubscription->stripe_customer_id,
                    'plan' => $PlanID,
                ]);
            }

            // Update stripe subscription to newly created plan
            $StripeSubscription->plan = $PlanID;
            $StripeSubscription->save();

            // Create an initial charge if there is one
            if(isset($ccParameters['initial_amount']) && $ccParameters['initial_amount']) {
                $objCharge = \Stripe\Charge::create([
                    'amount' => $ccParameters['initial_amount'],
                    'currency' => 'usd',
                    'customer' => $objStripeSubscription->stripe_customer_id,
                    'description' => "Initial Service Fee"
                ]);
                $objStripeSubscription->initial_charge_id = $objCharge->id;
            }

            $objStripeSubscription->stripe_subscription_id = $StripeSubscription->id;

            if ($objStripeSubscription->update())
                return true;

        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return false;
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
            throw new \Exception('Could not create AuthorizeDotNetPayment Customer Profile');
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

    private function updateStripePaymentProfile($ccParameters) {
        $required = ['userId', 'tokenID'];
        $supplied = array_keys($ccParameters);
        $intersect = array_intersect($supplied, $required);

        if (count($intersect) !== count($required)) {
            return false;
        }

        $user = Users::query()
            ->where("id = :id:")
            ->bind(["id" => $userId])
            ->execute()
            ->getFirst();
        $agency = Agency::query()
            ->where("agency_id = :agency_id:")
            ->bind(["agency_id" => $user->agency_id])
            ->execute()
            ->getFirst();

        $responseParameters = ['status' => false];
        $Errors = [];
        $userId = $ccParameters['userId'];

        //TODO: This needs to change, saving for last.  Not sure on behavior if stripe key not available.
        $StripeSecretKey = $agency->stripe_account_secret ?: $this->config->stripe->secret_key;
        try {
            \Stripe\Stripe::setApiKey($StripeSecretKey);
            $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$userId}");
            if (!$objStripeSubscription->stripe_customer_id) {
                // Need to create a profile.  Ideally, this shouldn't really be called.
                if ($this->createStripePaymentProfile($ccParameters))
                    $responseParameters['status'] = true;
            } else {
                $StripeCustomer = \Stripe\Customer::retrieve($objStripeSubscription->stripe_customer_id);
                $StripeCustomer->source = $ccParameters['tokenID'];
                $StripeCustomer->save();

            }
        } catch (Exception $e) {
            $Errors[] = $e->getMessage();
        }

        if(count($Errors)) {
            $responseParameters['Errors'] = $Errors;
            $responseParameters['status'] = false;
        }

        return $responseParameters;
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
            $parameters['intervalLength'] = $subscriptionParameters['intervalLength'] ?: $this->config->authorizeDotNet->intervalLength;
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
            $parameters['intervalLength'] = $subscriptionParameters['intervalLength'] ?: $this->config->authorizeDotNet->intervalLength;

            $status = $authorizeDotNetPayment->updateSubscriptionForCustomer($parameters);
            if(!$status) {
                return false;
            }
            
        }
        
        return true;
    }
}
