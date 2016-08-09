<?php

namespace Vokuro\Services;

class BaseService {

    protected $config = null;
    protected $di = null;

    function __construct($config = null, $di = null) {
        $this->di = $di;
        if(!$this->di){
            $di = \Phalcon\Di::getDefault();
            $this->di = $di;
        }
        if($config) $this->config = $config;
        if(!$this->config){
            $config = $di->get('config');
            $this->config  = $config;
        }
    }

}
