<?php


    namespace Vokuro\Models;

    use Vokuro\Models\BaseModel;
    use Phalcon\Mvc\Model\Validator\Uniqueness;
    use Vokuro\Models\Subscription;
    use \Phalcon\Mvc\Model\Validator\Regex;
    use \Phalcon\Mvc\Model\Validator\Email;

    class Agency extends BaseModel {

        //public $id;
        public $agency_id;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->agency_id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->agency_id = $id;
        }

        const AGENCY = 0;
        const BUSINESS_UNDER_RV = -1;

        /**
         * Validate that custom_domain is unique across agencies
         */
        public function validation() {
            /*$this->validate(new Regex([
                'field'   => 'name',
                'pattern' => '/^[a-zA-Z\.0-9 ]+$/',
                'message' => 'Name is in the wrong format (letters, period, and apostrophe)'
            ]));*/

            $this->validate(new Email([
                'field'   => 'email',
                'message' => 'Email is in the wrong format (xxx@xxx.xxx)'
            ]));
            $this->validate(new Uniqueness(array(
                "field"   => "email",
                "message" => "Value of field 'email' is already present in another record"
              )));
            if (isset($this->custom_domain) && $this->custom_domain != '') {
                $this->validate(new Uniqueness(array(
                    "field" => "custom_domain",
                    "message" => "The Custom Domain is already used"
                )));
            }

            return $this->validationHasFailed() != true;
        }

        public function initialize() {
           // $this->skipAttributes(['address2']);

            if(isset($this->parent_id) && $this->parent_id != static::AGENCY) {
                $this->skipAttributes(['website']);
                $this->skipAttributes(['email_from_name']);
                $this->skipAttributes(['email_from_address']);
            }

            $this->setSource('agency');

            $this->belongsTo('subscription_id', __NAMESPACE__ . '\Subscription', 'subscription_id', array(
                'alias' => 'subscription',
                'reusable' => true
            ));
            //if (!$this->_skipped) $this->skipAttributes(['address2']); //address2 should NOT be required
            parent::initialize();
        }

        /**
         * Creates (or updates if exists) business.
         * @param $tData array Form fields for business
         */
        public function createOrUpdateBusiness($tData) {
            $this->assign($tData);
            return $this->save();
        }

    }
