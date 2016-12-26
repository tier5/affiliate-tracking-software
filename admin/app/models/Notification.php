<?php


    namespace Vokuro\Models;
	use Vokuro\Models\BaseModel;
    use Phalcon\Mvc\Model\Validator\Uniqueness;
   
    use \Phalcon\Mvc\Model\Validator\Regex;


    class Notification extends BaseModel {

    	public function initialize()
        {
            $this->setSource('notification');

        }
        public function createOrUpdateBusiness($tData) {
            $this->assign($tData);
            return $this->save();
        }
    }