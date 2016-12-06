<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

include_once("../API/config.php");

if (empty($_SERVER['HTTP_X_CG_SIGNATURE'])) {
	//invalid request
} else {
	
	$query_cart_data = $db->query("SELECT AES_DECRYPT(cheddar_key, '" . SITE_KEY . "') AS decrypted_key from idevaff_carts_data");
	$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
	$cart_data=$query_cart_data->fetch();
	$productKey=$cart_data['decrypted_key'];
	
	//$productKey = "xyz";

	$idv_url = $base_url . '/sale.php';
	$profile = '122';
	$admin_email = $address;

	$rawBody     = file_get_contents( 'php://input' );
	$token       = md5( $rawBody );

	// check signature
	if ( $_SERVER['HTTP_X_CG_SIGNATURE'] == hash_hmac( 'sha256', $token, $productKey ) ) {
		//valid data

		$response_data = json_decode($rawBody, true);
		//mail( $admin_email, 'CheddarGetter Response Success', print_r( $response_data, true ) );
		if( $response_data['activityType'] == 'transaction') {
			$transaction_id = $response_data['subscription']['invoice']['transaction']['id'];
			$amount = $response_data['subscription']['invoice']['transaction']['amount'];

			//now get user ip address from metadata
			$ip_address = "";
			$error = "";
			$metadata = $response_data['customer']['metaData'];
			if( is_array($metadata) && !empty($metadata) ) {
				foreach ($metadata as $key => $meta) {
					if($meta['name'] == 'ip') {
						$ip_address = $meta['value'];
						break;
					}
				}
			}

			if($ip_address != "") {
				$data = array (
					"profile"       => $profile,
					"idev_saleamt"  => $amount,
					"idev_ordernum" => $transaction_id,
					'ip_address'    => $ip_address,
					'idev_secret'   => $secret
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $idv_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$json = curl_exec($ch);
				curl_close($ch);

				//mail($admin_email, 'CheddarGetter Pursed Data', print_r($data,true));
				header('X-PHP-Response-Code: 200', true, 200);
				die();
			}
		} else {
			//this are other hooks, we are not processing this, so sending status success
			header( 'X-PHP-Response-Code: 200', true, 200 );
			exit();
		}


	} else {
		//invalid data, validation failed

	}
}