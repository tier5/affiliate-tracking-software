<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;

    class BaseModel extends Model {

        public function initialize() {
            $this->useDynamicUpdate(true);
        }

        // messages from custom validators
        /*public function getMessages($filter = null) {
            $this->_errorMessages = [];
            $this->validation();

            return $this->_errorMessages;
        }*/



        // formatted version of getMessages
        public function get_val_errors() {
            $model_errors = $this->getMessages();

            $errors = '<strong>Something went wrong:</strong><br />';

            foreach ($model_errors as $error) {
                $errors .= $error . '<br />';
            }

            return $errors;
        }

        static public function format_val_errors($model_errors) {
            $errors = '<strong>Something went wrong:</strong><br />';

            foreach ($model_errors as $error) {
                $errors .= $error . '<br />';
            }

            return $errors;
        }

    }