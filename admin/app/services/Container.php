<?php namespace Vokuro\Services;
class Container extends BaseService{

    /**
     * @return \Phalcon\Db\Adapter
     */
    public function getDB(){
        $db =  $this->di->get('db');
        /**
         * @var $db \Phalcon\Db\Adapter
         */
        return $db;
    }

}