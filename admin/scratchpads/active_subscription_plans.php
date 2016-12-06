<?php
require 'bootstrap.php';
$sm = new \Vokuro\Services\SubscriptionManager();

$results = $sm->getActiveSubscriptionPlans();

$active = $sm->getActiveSubscriptionPlan();
