<?php
    require 'bootstrap.php';
    $di = \Phalcon\Di::getDefault();
    $config = $di->get('config');

    if(!$config->stripe->secret_key)
        die("Stripe's secret key is not setup properly.");

    \Stripe\Stripe::setApiKey($config->stripe->secret_key);

    $dbAgency = \Vokuro\Models\Agency::find();
    foreach($dbAgency as $objAgency) {
        if(IsFreePlan($objAgency->id))
            continue;

        echo "Processing Agency {$objAgency->id}\n";

        $AgencyID = $objAgency->id;
        $objStripeValidation = FindOrCreateValidationRow($objAgency->id);

        try {
            $objStripeSubscription = \Stripe\Subscription::all(['plan' => "agency_plan_{$AgencyID}"])['data'][0];
            $objStripeValidation->action = 0;


            $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$AgencyID} AND role = 'Super Admin'");
            if($objSuperUser) {
                $objInternalStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$objSuperUser->id}");
                if($objInternalStripeSubscription) {
                    if($objStripeSubscription->id && $objStripeSubscription->status && $objStripeSubscription->customer) {
                        if (trim($objInternalStripeSubscription->stripe_customer_id) != trim($objStripeSubscription->customer)) {
                            $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::UPDATE_CUSTOMER_ID;
                        }
                        if (trim($objInternalStripeSubscription->stripe_subscription_id) != trim($objStripeSubscription->id)) {
                            $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::UPDATE_SUBSCRIPTION_ID;
                        }
                    }
                } else {
                    if($objStripeSubscription->id && $objStripeSubscription->status && $objStripeSubscription->customer)
                        $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::CREATE_INTERNAL_SUBSCRIPTION;
                }
            } else {
                $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::CREATE_SUPER_USER;
            }

            $objStripeValidation->stripe_status = $objStripeSubscription->status ?: 'N/A';
            $objStripeValidation->stripe_subscription_id = $objStripeSubscription->id ?: 'N/A';
            $objStripeValidation->stripe_customer_id = $objStripeSubscription->customer ?: 'N/A';
            if($objStripeSubscription->status == 'active') {
                if(!$objAgency->status) {
                    $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::ACTION_ENABLE;
                }
            } else {
                if($objAgency->status) {
                    $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::ACTION_DISABLE;
                }
            }
        } catch (Exception $e) {
            $objStripeValidation->stripe_status = $e->getMessage();
            if($objAgency->status) {
                $objStripeValidation->action |= \Vokuro\Models\AgencyStripeValidation::ACTION_DISABLE;
            }
        }

        $objStripeValidation->save();
    }

    function FindOrCreateValidationRow($AgencyID) {
        $objStripeValidation = \Vokuro\Models\AgencyStripeValidation::findFirst("agency_id = {$AgencyID}");
        if(!$objStripeValidation) {
            $objStripeValidation = new \Vokuro\Models\AgencyStripeValidation();
            $objStripeValidation->agency_id = $AgencyID;
            $objStripeValidation->save();
        }
        return $objStripeValidation;
    }

    function IsFreePlan($AgencyID) {
        return false;
    }