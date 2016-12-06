<?php 
$event = json_decode(file_get_contents("php://input")); // get event from celery
if (empty($event) || !isset($event->type) || $event->type != 'order.created') exit; // track when the order is created

include("../API/config.php");

$idev_ordernum = $event->data->number; // get order number
$ip_address = $event->data->client_details->ip; // get ip address

/* get products */
$products = $event->data->line_items;
$skus = array();
foreach ($products as $product) {
$skus[] = $product->sku; }
$products_purchased = implode('|', $skus);
/* get products  */

/* get coupon code */
$coupon_code = '';
if (isset($event->data->discounts[0]->code)) {
$coupon_code = $event->data->discounts[0]->code; }
/* get coupon code */

$idev_saleamt = $event->data->subtotal / 100; // get subtotal

/* generate tracking url */
$tracking_url = $base_url.'/sale.php';
$tracking_fields = 'profile=154&ip_address='.$ip_address.'&idev_saleamt='.$idev_saleamt.'&idev_ordernum='.$idev_ordernum.'&products_purchased='.$products_purchased.'&coupon_code='.$coupon_code.'&idev_secret='.$secret;
/* generate tracking url */
				
/* submit url */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tracking_url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $tracking_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($ch);
curl_close($ch);
/* submit url */