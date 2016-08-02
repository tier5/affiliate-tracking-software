<?php
require 'bootstrap.php';

$email = new \Vokuro\Services\Email();
$u = new \Vokuro\Models\Users();
$user = $u->getById(69);
$controller = new \Vokuro\Controllers\UsersController();

$data = [
    'type'=>1,
    'agency_id'=>54,
    'name'=>'Scott Conrad'.rand(1,100000),
    'email' => 'scott+'.rand(1,10000000).'@honopu.com',
    'phone' => '9418947325'
];

$controller->createFunction(0,0,$data);