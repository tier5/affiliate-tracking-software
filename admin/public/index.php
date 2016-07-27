<?php

error_reporting(E_ALL ^ E_NOTICE);

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

    if (APPLICATION_ENV === 'testing') {

        return $application;

    } else {

        echo $application->handle()->getContent();

    }

} catch (Exception $e) {
	echo $e->getMessage(), '<br>';
	echo nl2br(htmlentities($e->getTraceAsString()));
}
