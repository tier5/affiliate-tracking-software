<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

include_once("../API/config.php");
include_once("../API/recurly/lib/recurly.php");

$query_cart_data = $db->query("SELECT AES_DECRYPT(recurly_sub, '" . SITE_KEY . "') AS decrypted_sub, AES_DECRYPT(recurly_key, '" . SITE_KEY . "') AS decrypted_key, AES_DECRYPT(recurly_user, '" . SITE_KEY . "') AS decrypted_user, AES_DECRYPT(recurly_pass, '" . SITE_KEY . "') AS decrypted_pass from idevaff_carts_data");
$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
$cart_data=$query_cart_data->fetch();
$recurly_sub=$cart_data['decrypted_sub'];
$recurly_key=$cart_data['decrypted_key'];
$username=$cart_data['decrypted_user'];
$password=$cart_data['decrypted_pass'];

Recurly_Client::$subdomain = $recurly_sub;
Recurly_Client::$apiKey = $recurly_key;

$idv_url = $base_url . '/sale.php';
$profile = '118';

$admin_email = $address;

/*
//testing purpose only
$post_xml = file_get_contents ("php://input");
$notification = new Recurly_PushNotification($post_xml);
mail($admin_email, 'Recurly Initial', print_r($notification,true) );
*/

if( $_SERVER['PHP_AUTH_USER'] == $username  && $_SERVER['PHP_AUTH_PW'] == $password ) {
	$error = "";
	$notification = "";
	try {
		//Get the XML Payload
		$post_xml = file_get_contents ("php://input");
		$notification = new Recurly_PushNotification($post_xml);
		//each webhook is defined by a type
		switch ($notification->type) {
			case "successful_payment_notification":
				$transaction_id = $notification->transaction->id;

				$transaction = Recurly_Transaction::get($transaction_id);
				$account = $transaction->account->get();

				$amount = ( isset($transaction->amount_in_cents) && $transaction->amount_in_cents > 0 ) ? $transaction->amount_in_cents / 100 : 0;
				$tax = ( isset($transaction->tax_in_cents) && $transaction->tax_in_cents > 0 ) ? $transaction->tax_in_cents / 100 : 0;
				$currency = isset( $transaction->currency ) ? $transaction->currency : 'USD' ;
				$status = isset( $transaction->status) ? $transaction->status : '';


				$ip_address = "";
				if($transaction->ip_address) {
					$ip_address = $transaction->ip_address;
				} else {
					//get billing information
					$billing = $account->billing_info->get();
					if ( $billing->ip_address) {
						$ip_address = $billing->ip_address;
					}
				}

				$coupons = "";
				$coupon_amount = 0;

				if ( $account->redemptions ) {
					$redemptions = $account->redemptions->get();
					//print_r($redemptions);
					foreach ( $redemptions as $obj) {
						//print_r($obj);
						$coupon_amount = $coupon_amount + $obj->total_discounted_in_cents;
						$coupon_details = $obj->coupon->get();
						$coupons[] = $coupon_details->coupon_code;
						//print_r($coupon_details);
					}
				}

				if(!empty($coupons)) {
					$coupons = implode(',', $coupons);
				}
				if($coupon_amount > 0) {
					$coupon_amount /= 100;
				}

				$data = array (
					"profile"       => $profile,
					"idev_saleamt"  => $amount,
					"idev_ordernum" => $transaction_id,
					"coupon_code"   => $coupons,
					'ip_address'    => $ip_address,
					'coupon_amount' => $coupon_amount,
					'currency'      => $currency,
					'tax'           => $tax,
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

				//mail($admin_email, 'Recurly Pursed Data', print_r($data,true));
				header('X-PHP-Response-Code: 200', true, 200);
				die();

				break;
				default:
				header('X-PHP-Response-Code: 200', true, 200);
				die();

		}
	}
	catch (Recurly_NotFoundError $e) {
		$error .= 'Record could not be found';
	}
	catch (Recurly_ValidationError $e) {
		// If there are multiple errors, they are comma delimited:
		$messages = explode(',', $e->getMessage());
		$error .= 'Validation problems: ' . implode("\n", $messages);
	}
	catch (Recurly_ServerError $e) {
		$error .= 'Problem communicating with Recurly';
	}
	catch (Exception $e) {
		// You could use send these messages to a log for later analysis.
		$error .= get_class($e) . ': ' . $e->getMessage();
	}

	if($error != '') {
		//mail($admin_email,'Recurly Webhook Error', $error );

	}


} else {
	//mail($admin_email, 'Recurly Webhook - Invalid', 'Invalid user and password');
	header('X-PHP-Response-Code: 203', true, 203);
	die();
}

?>