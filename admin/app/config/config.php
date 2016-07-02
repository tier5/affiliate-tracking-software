<?php
return new \Phalcon\Config(array(
    'database' => array(
      'adapter' => 'Mysql',
      'host' => 'localhost',
      'username' => 'root',
      'password' => 'root',
      'dbname' => 'reviewvelocity',
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
        'environment' => 'dev'
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
    )
));
