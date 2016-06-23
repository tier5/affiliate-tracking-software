<?php

namespace Vokuro; 

use DateTime;

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
}