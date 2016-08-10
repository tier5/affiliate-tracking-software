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

        public function findWhere($params)
        {
            $queries = [];
            $bind = [];
            foreach ($params as $key => $value) {
                $queries[] = $key . ' = :' . $key . ':';
                $bind[$key] = $value;
            }
            $query = implode(' AND ', $queries);
            return $this->find([$query, 'bind' => $bind]);
        }

        public function findBy($params)
        {
            return $this->findWhere($params);
        }

        public function findOneBy($params){
            $records = $this->findwhere($params);
            if($records) $first = $records[0];
            return $first;
        }

    }