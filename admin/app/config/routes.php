<?php
/*
 * Define custom routes. File gets included in the router service definition.65
 */
$router = new Phalcon\Mvc\Router();

$router->add('/confirm/{code}/{email}', array(
    'controller' => 'user_control',
    'action' => 'confirmEmail'
));

$router->add('/reset-password/{code}/{email}', array(
    'controller' => 'user_control',
    'action' => 'resetPassword'
));

$router->add('/dashboard/css', array(
    'controller' => 'admindashboard',
    'action' => 'css'
));

$router->add('/signup',['controller'=>'session','action'=>'signup']);
return $router;
