<?php

namespace Vokuro\Services;

class BaseService {
    
    protected $config;
    
    function __construct($config) {
        $this->config = $config;
    }
    
}
