<?php
return new \Phalcon\Config(array(
    'database' => array(
      'adapter' => 'Mysql',
      'host' => 'localhost',
      'username' => 'dev',
      'password' => '_G88{XEyj:Nr',
      'dbname' => 'dev',
      'charset' => 'utf8',
    ),
    'application' => array(
        'controllersDir' => APP_DIR . '/controllers/',
        'modelsDir' => APP_DIR . '/models/',
        'formsDir' => APP_DIR . '/forms/',
        'viewsDir' => APP_DIR . '/views/',
        'libraryDir' => APP_DIR . '/library/',
        'pluginsDir' => APP_DIR . '/plugins/',
        'cacheDir' => APP_DIR . '/cache/',
        'baseUri' => '/',
        'publicUrl' => 'www.reviewvelocity.co',
        'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
    ),
    'mail' => array(
        'fromName' => '',
        'fromEmail' => 'no-reply@reviewvelocity.co',
        //use the SendGrid Relay server to send mail
        'smtp' => array(
            'server' => 'smtp.sendgrid.net',
            'port' => 587,
            'security' => 'tls',
            'username' => 'reviewvelocity',
            'password' => 'r9dzDOEe6a3M'
        )
    ),
    'amazon' => array(
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    ),
    'maxSignup' => array(
        'perday' => '0', //zero equals infinite
    )
));
