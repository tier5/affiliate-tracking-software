<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// exit if order number is empty
if (empty($_REQUEST['order_number'])) { exit; }

include("../API/config.php");

// include nusoap library
require_once('../includes/nusoap/nusoap.php');

$query_cart_data = $db->query("SELECT AES_DECRYPT(zoey_url, '" . SITE_KEY . "') AS decrypted_url, AES_DECRYPT(zoey_user, '" . SITE_KEY . "') AS decrypted_user, AES_DECRYPT(zoey_pass, '" . SITE_KEY . "') AS decrypted_pass from idevaff_carts_data");
$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
$cart_data=$query_cart_data->fetch();
$zoey_commerce_url=rtrim($cart_data['decrypted_url'], '/');
$api_username=$cart_data['decrypted_user'];
$api_password=$cart_data['decrypted_pass'];

// get order number
$order_number = intval($_REQUEST['order_number']);

/* connect to zoeycommerce */
$client = new nusoap_client($zoey_commerce_url.'/api/soap/?wsdl', true);
$session = $client->call('login', array('username' => $api_username, 'apiKey' => $api_password));
$result = $client->call('call', array('sessionId' => $session, 'resourcePath' => 'sales_order.info', 'args' => $order_number));
/* connect to zoeycommerce */

// connection error
if ($client->getError()) {
	//echo $client->getError();
} else {
	//var_dump($result); exit;
	// get order id
	$order_id = $result['order_id'];
		
	// get ip address
	$ip_address = $result['remote_ip'];
		
	/* get product data */
	$skus = array();
	foreach ($result['items'] as $item) {
		if (!in_array($item['sku'], $skus)) {
			$skus[] = $item['sku'];
		}
	}
	$products_purchased = implode('|', $skus);
	/* get product data */
		
	// get coupon code
	$coupon_code = $result['coupon_code'];
	
	// get coupon amount
	$coupon_amount = $result['discount_amount'];
	
	// get subtotal
	$subtotal = $result['subtotal'] + $coupon_amount;
				
	/* generate tracking url */
	$tracking_url = $base_url.'/sale.php';
	$tracking_fields = 'profile=133&ip_address='.$ip_address.'&idev_saleamt='.$subtotal.'&idev_ordernum='.$order_id.'&products_purchased='.$products_purchased.'&coupon_code='.$coupon_code.'&idev_secret='.$secret;
	//echo $tracking_fields; exit;
	
	echo $tracking_url;
				
	//mail('mail@mail.com', 'Tracking Pixel Called', $tracking_url.'?'.$tracking_fields);
	/* generate tracking url */
				
	/* submit url */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $tracking_url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $tracking_fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$return = curl_exec($ch);
	curl_close($ch);
	/* submit url */
	
	// end session
	$client->call('endSession', array('sessionId' => $session));
}