<?php


    namespace Vokuro\Models;
	use Vokuro\Models\BaseModel;
    use Phalcon\Mvc\Model\Validator\Uniqueness;
   
    use \Phalcon\Mvc\Model\Validator\Regex;


    class Notification extends BaseModel {

    	public function initialize()
        {
            $this->setSource('notification');


            // Not sure if this is correct, so commenting out until needed.
            //$this->hasMany("id", "AgencySubscriptionPlan", "pricing_plan_id");
        }
        public function createOrUpdateBusiness($tData) {
            $this->assign($tData);
            return $this->save();
        }
    }