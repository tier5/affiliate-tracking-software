<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

include_once ("../API/config.php");
include_once ("../API/stripe/init.php");

$query_cart_data = $db->query("SELECT AES_DECRYPT(stripe_api_key, '" . SITE_KEY . "') AS decrypted_key from idevaff_carts_data");
$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
$cart_data=$query_cart_data->fetch();
$stripe_api_key=$cart_data['decrypted_key'];

\Stripe\Stripe::setApiKey($stripe_api_key);

$idv_url = $base_url . '/sale.php';
$profile = '109';
$admin_email = $address;


// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

//this is for security reason, getting same event from stripe
$event_id = $event_json->id;

try {

	$event = \Stripe\Event::retrieve($event_id);

} catch (Exception $e) {

	$fail_message = $e->getMessage();
	//mail($admin_email, 'Stripe Webhook - Invalid Event', $fail_message);
	return;
}


switch ( $event->type ) {
	case "charge.succeeded":
		//mail($admin_email, 'Stripe Integration Event', print_r($event, true));
		//echo "<pre>"; print_r($event);
		$id = $event->data->object->id;
		$object = $event->data->object->object;

		$amount = $event->data->object->amount;
		if( $amount > 0 ) {
			$amount = $amount / 100;
		}

		$currency = $event->data->object->currency;

		//get ip
		$ip = "";
		$metadata = $event_json->data->object->metadata;
		if ( is_array( $metadata ) && array_key_exists( 'ip', $metadata ) ) {
			$ip= $metadata['ip'];
		} elseif ( is_object( $metadata ) && $metadata->ip ) {
			$ip = $metadata->ip;
		}

		if($ip == "") {
			//check on source object
			$metadata = $event->data->object->source->metadata;
			if ( is_array( $metadata ) && array_key_exists( 'ip', $metadata ) ) {
				$ip= $metadata['ip'];
			} elseif ( is_object( $metadata ) && $metadata->ip ) {
				$ip = $metadata->ip;
			}

		}

		if( $ip == "" ) {
			//get metadata from customer object
			$customer = $event->data->object->customer;
			if ($customer == null) {
				$customer = $event_json->data->object->source->customer;
			}

			if ( $customer != null ) {
				try {
					//get customer object
					$customer_obj   = \Stripe\Customer::retrieve( $customer );
					$cus_obj_pursed = $customer_obj->__toArray( true );
					$metadata       = $cus_obj_pursed['metadata'];
					//check on metadata first
					if ( is_array( $metadata ) && array_key_exists( 'ip', $metadata ) ) {
						$ip = $metadata['ip'];
					} elseif ( is_object( $metadata ) && $metadata->ip ) {
						$ip = $metadata->ip;
					}

					if ( $ip == "" ) {
						//last step
						//check on subscription object for ip address
						$subscriptions = $cus_obj_pursed['subscriptions'];
						if ( is_array( $subscriptions['data'] ) && ! empty( $subscriptions['data'] ) ) {
							foreach ( $subscriptions['data'] as $data ) {
								if ( is_array( $data ) ) {
									$metadata = $data['metadata'];
								} elseif ( is_object( $data ) ) {
									$metadata = $data->metadata;
								}

								if ( is_array( $metadata ) && array_key_exists( 'ip', $metadata ) ) {
									$ip = $metadata['ip'];
								} elseif ( is_object( $metadata ) && $metadata->ip ) {
									$ip = $metadata->ip;
								}

								if ( $ip != '' ) {
									break;
								}
							}
						}
					}
				} catch (Exception $e) {
					$fail_message = "";
					$fail_message = '<p>' .$e->getMessage() . '</p>';
					//mail($admin_email, 'Stripe ERROR', $fail_message);
				}
			}

		}

		//check for coupons by invoice
		$coupon = "";
		$coupon_amount = "";
		$coupon_type = "";
		if ( $event->data->object->invoice ) {
			$invoice_id = $event->data->object->invoice;

			$invoice_obj = \Stripe\Invoice::retrieve($invoice_id);

			$invoice = $invoice_obj->__toArray(true);

			if ( isset( $invoice['discount']['coupon'] ) ) {
				$coupon_data    = $invoice['discount']['coupon'];
				$coupon         = isset( $coupon_data['id'] )           ? $coupon_data['id'] : '';
				$amount_off     = isset( $coupon_data['amount_off'] )   ? $coupon_data['amount_off'] : 0;
				$percent_off    = isset( $coupon_data['percent_off'] )  ? $coupon_data['percent_off'] : 0;
				if ( $amount_off > 0 ) {
					$coupon_amount = $amount_off;
					$coupon_type = "amount";
				} elseif ( $percent_off > 0 ) {
					$coupon_amount = $percent_off;
					$coupon_type = "percent";
				}
			}
		}


		$data = array (
			"profile"       => $profile,
			"idev_saleamt"  => $amount,
			"idev_ordernum" => $id,
			"coupon_code"   => $coupon,
			'ip_address'    => $ip,
			'coupon_amount' => $coupon_amount,
			'coupon_type'   => $coupon_type,
			'currency'      => $currency,
			'object'        => $object,
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


		//mail($admin_email, 'Stripe Pursed Data', print_r($data,true));

		break;

}

header('X-PHP-Response-Code: 200', true, 200);
die();


?>