<?php

namespace Vokuro\Services;

/**
 * Class StripeValidationService - This class will handle the implementation of all the methods that are possible in the Stripe Validation module for Super Admins.
 * @package Vokuro\Services
 */
class StripeValidationService extends BaseService {

    public function GenerateProcessString($ActionID) {
        $tActions = [];
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::ACTION_ENABLE)
            $tActions[] = 'Enable Agency';
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::ACTION_DISABLE)
            $tActions[] = 'Disable Agency';
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::CREATE_SUPER_USER)
            $tActions[] = 'Create Super User';
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::CREATE_INTERNAL_SUBSCRIPTION)
            $tActions[] = 'Create Internal Subscription';
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::UPDATE_CUSTOMER_ID)
            $tActions[] = 'Update Internal Customer ID';
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::UPDATE_SUBSCRIPTION_ID)
            $tActions[] = 'Update Internal Subscription ID';

        return count($tActions) ? implode(', ', $tActions) : 'Do nothing';
    }

    public function ProcessServices($ValidationID) {
        $objStripeValidation = \Vokuro\Models\AgencyStripeValidation::findFirst("id = {$ValidationID}");
        if(!$objStripeValidation)
            return false;

        $ActionID = $objStripeValidation->action;
        if($ActionID == 0)
            echo "Nothing to process for agency {$objStripeValidation->agency_id}<BR />";
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::ACTION_ENABLE)
            $this->EnableAgency($objStripeValidation->agency_id);
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::ACTION_DISABLE)
            $this->DisableAgency($objStripeValidation->agency_id);
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::CREATE_SUPER_USER)
            $this->CreateSuperUser($objStripeValidation->agency_id);
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::CREATE_INTERNAL_SUBSCRIPTION)
            $this->CreateInternalSubscription($objStripeValidation->agency_id, $objStripeValidation->stripe_customer_id, $objStripeValidation->stripe_subscription_id);
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::UPDATE_CUSTOMER_ID)
            $this->UpdateInternalCustomerID($objStripeValidation->agency_id, $objStripeValidation->stripe_customer_id);
        if($ActionID & \Vokuro\Models\AgencyStripeValidation::UPDATE_SUBSCRIPTION_ID)
            $this->UpdateInternalSubscriptionID($objStripeValidation->agency_id, $objStripeValidation->stripe_subscription_id);

        $objStripeValidation->process_status = \Vokuro\Models\AgencyStripeValidation::STATUS_PROCESSED;
        $objStripeValidation->save();
    }

    public function EnableAgency($AgencyID) {
        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$AgencyID}");
        $objAgency->status = 1;
        $objAgency->save();
        echo "Enabled Agency {$AgencyID}<BR />";
    }
    public function DisableAgency($AgencyID) {
        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$AgencyID}");
        $objAgency->status = 0;
        $objAgency->save();

        echo "Disabled Agency {$AgencyID}<BR />";
    }
    public function CreateSuperUser($AgencyID) {
        echo "Creating Super User for Agency {$AgencyID} (NOT IMPLEMENTED TILL FURTHER DISCUSSION)<BR />";
    }
    public function CreateInternalSubscription($AgencyID, $StripeCustomerID, $StripeSubscriptionID) {
        echo "Created Internal Subscription for Agency {$AgencyID} with CustomerID - {$StripeCustomerID} and SubscriptionID - {$StripeSubscriptionID} (NOT IMPLEMENTED TILL FURTHER DISCUSSION)<BR />";
    }
    public function UpdateInternalCustomerID($AgencyID, $StripeCustomerID) {
        echo "Created Internal Subscription for Agency {$AgencyID} with CustomerID - {$StripeCustomerID} (NOT IMPLEMENTED TILL FURTHER DISCUSSION)<BR />";
    }
    public function UpdateInternalSubscriptionID($AgencyID, $StripeSubscriptionID) {
        echo "Created Internal Subscription for Agency {$AgencyID} with SubscriptionID - {$StripeSubscriptionID} (NOT IMPLEMENTED TILL FURTHER DISCUSSION)<BR />";
    }

}