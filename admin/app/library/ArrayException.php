<?php

namespace Vokuro;

class ArrayException extends \Exception {

    private $options;

    public function __construct($message, $code = 0, Exception $previous = null, $options = ['params']) 
    {
        parent::__construct($message, $code, $previous);

        $this->options = $options; 
    }

    public function getOptions() { 
        return $this->options; 
    }
}

