<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);

try {

    /**
     * Define some useful constants
     */
    if (!defined('BASE_DIR')) { define('BASE_DIR', dirname(__DIR__)); }
    if (!defined('APP_DIR')) { define('APP_DIR', BASE_DIR . '/app'); }

    /**
      * Read the configuration
      */
    $config = include APP_DIR . '/config/config.php';

    /**
     * Read auto-loader
     */
    include APP_DIR . '/config/loader.php';

    /**
     * Read services
     */
    include APP_DIR . '/config/services.php';

    include APP_DIR . '/config/functions.php';

    if (!defined('ENV_PRODUCTION')) { define('ENV_PRODUCTION', 'production'); }
    if (!defined('APPLICATION_ENV')) { define('APPLICATION_ENV', getenv('APP_ENV') ?: ENV_PRODUCTION); }

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    /**
     * sanitize post
     */

    if(!function_exists('sanitizeArray')){
        function sanitizeArray($array){
            $arr = ['a', 'b', 'iframe', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6','span','ul','li','ol','table','tr','td',
            'tbody','thead','strong'];
            $tags = '<' . implode('><', $arr) . '>';
            foreach ($array as &$value) {
                if(is_array($value)){
                $value = sanitizeArray($value);
                }else {
                    $value = strip_tags($value, $tags);
                }
            }
            return $array;
        }
    }
    $_POST = sanitizeArray($_POST);



    if (APPLICATION_ENV === 'testing') {

        return $application;

    } else {

        echo $application->handle()->getContent();

    }

} catch (Exception $e) {
	echo $e->getMessage(), '<br>';
	echo nl2br(htmlentities($e->getTraceAsString()));
}
