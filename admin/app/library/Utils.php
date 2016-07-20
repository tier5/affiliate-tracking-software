<?php

namespace Vokuro;

use DateTime;
use HTMLPurifier_Config;
use HTMLPurifier;

class Utils {

    public static function formatCCDate($date,$format = 'm/y') {
        $stripped = str_replace(' ', '', $date); // Remove whitespace
        $date = DateTime::createFromFormat($format, $stripped);
        if($date) return $date->format('Y-m');
        if(!$date) throw new \Exception('Invalid date specified, expected date in the format of m/y');
    }

    public static function objectToArray($obj) {
        if(is_object($obj)) $obj = (array) $obj;

        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = self::objectToArray($val);
            }
        } else {
            $new = $obj;
        }
        return $new;
    }

    public static function purifyHtml($dirtyHtml) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($dirtyHtml);
    }


    /**
     * @TODO : determine why this existed
     * @param $page
     * @param $validSubdomains
     * @param $subscription_id
     */
    public static function noSubDomains($page, $validSubdomains, $subscription_id) {
        $http_host = $_SERVER['HTTP_HOST'];
        $sub = array_shift(explode(".", $http_host));

        if ($sub && in_array($sub, $validSubdomains)) {
            //there is a subdomain.  That is not allowed, so redirect them out of here
            $found = false;
            $querystring = '';
            if (isset($_GET['code'])) {
                $code = $_GET['code'];
                $querystring = '?code=' . $code;
                $found = true;
            }
            if ($subscription_id > 0) {
                $querystring = $subscription_id . '/' . $querystring;
                $found = true;
            }
            //this looks lifted from a controller...
            //return $this->response->redirect('/session/signup' . ($page > 1 ? $page : '') . '/' . $querystring);
        }
    }

}