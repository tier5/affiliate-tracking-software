<?php
require 'bootstrap.php';

$email = new \Vokuro\Services\Email();
$email->sendResetPasswordEmailByUserId(197);