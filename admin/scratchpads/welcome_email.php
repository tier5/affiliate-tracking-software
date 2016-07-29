<?php
require 'bootstrap.php';

$email = new \Vokuro\Services\Email();
$email->sendActivationEmailByUserId(197);