<?php

if ((!isset($_REQUEST['alert_name'])) || ($_REQUEST['alert_name'] != 'payment_succeeded')) { exit; }

include("../API/config.php");

$ip_address = $_POST['ip'];
$idev_currency = $_POST['currency'];
$idev_ordernum = $_POST['order_id'];
$idev_saleamt = $_POST['sale_gross'];

$tracking_url = $base_url.'/sale.php';
$tracking_fields = 'profile=159&ip_address='.$ip_address.'&idev_saleamt='.$idev_saleamt.'&idev_ordernum='.$idev_ordernum.'&idev_secret='.$secret.'&idev_currency='.$idev_currency;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tracking_url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $tracking_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($ch);
curl_close($ch);
