<?php namespace Vokuro\Services;
use Phalcon\Crypt;

class Encryption extends BaseService{
    protected $key = '3NONP7Mg11C8a1t14O0mBdLICWiMJJiC'; //this is soooo bad
    protected $crypt;
    public function __construct($config = null, $di = null)
    {
        parent::__construct($config, $di);
        $this->crypt = new Crypt();
    }


    public function encrypt($value){
        $value = (string)$value;
        $str = $this->crypt->encryptBase64($value,$this->key);
        $str = str_replace('/','*',$str);
        return $str;
    }

    public function decrypt($value){
        $value = str_replace('*','/',$value);
        return (string)trim($this->crypt->decryptBase64($value,$this->key));
    }

}