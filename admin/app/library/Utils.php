<?php

namespace Vokuro; 

use DateTime;
use HTMLPurifier_Config;
use HTMLPurifier;

class Utils {

    public static function formatCCDate($date) {
        $stripped = str_replace(' ', '', $date); // Remove whitespace
        $date = DateTime::createFromFormat('m/y', $stripped);
        return $date->format('Y-m');
    }
    
    public static function objectToArray($obj) {
        if(is_object($obj)) { 
            $obj = (array) $obj;
        }
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = self::objectToArray($val);
            }
        }
        else {
            $new = $obj;
        }
        return $new;
    }
    
    public static function purifyHtml($dirtyHtml) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($dirtyHtml);
    }
    
    
                
    
}