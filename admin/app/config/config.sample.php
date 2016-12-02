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
        'servicesDir' => APP_DIR . '/services/',
        'pluginsDir' => APP_DIR . '/plugins/',
        'cacheDir' => APP_DIR . '/cache/',
        'baseUri' => '/',
        'publicUrl' => 'www.reviewvelocity.co',
        'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'environment' => 'dev',
        'domain' => 'getmobilereviews.com',
    ),
    'mail' => array(
        'fromName' => '',
        'fromEmail' => 'no-reply@reviewvelocity.co',
        //use the SendGrid Relay server to send mail
        'smtp' => array(
            'server' => 'mailtrap.io',
            'port' => 465,
            'security' => 'tls',
            'username' => 'de0d513c12498d',
            'password' => '700de902cdaa9f'
        )
    ),
    'authorizeDotNet' => array(
        'apiLoginId' => '2aD9Vxe3X',
        'transactionKey' => '3C8c3hh8r8w6L7Dk',
        'prefixKey' => 'afrYkgkBunsbMsv5__',
        'intervalLength' => "1",
        'unit' => "months",
        'totalOccurences' => '9999'
    ),
    'amazon' => array(
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    ),
    'maxSignup' => array(
        'perday' => '0', //zero equals infinite
    ),
    'stripe' => [
        'secret_key'        => '',
        'publishable_key'   => '',
    ],
    'facebook' => [
        'app_id'        => '',
        'app_secret'    => ''
    ],
    'twilio' => [
        'twilio_auth_token'         => '',
        'twilio_auth_messaging_sid' => '',
        'twilio_from_phone'         => '',
    ],
));
