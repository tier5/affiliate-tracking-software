<?php

namespace Vokuro; 

use DateTime;

class Utils {

    public static function formatCCDate($date) {
        $stripped = str_replace(' ', '', $date); // Remove whitespace
        $date = DateTime::createFromFormat('m/y', $stripped);
        return $date->format('Y-m');
    }
    
}