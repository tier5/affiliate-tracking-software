<?php
require 'bootstrap.php';
$user_id = 183;
$user = new \Vokuro\Services\UserManager();

$result = $user->sudoAsUserId($user_id);