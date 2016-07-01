<?php

namespace Vokuro\Services;

class BaseService {
    
    protected $config;
    protected $di;
    
    function __construct($config, $di = null) {
        $this->config = $config;
        $this->di = $di;
    }
    
}
