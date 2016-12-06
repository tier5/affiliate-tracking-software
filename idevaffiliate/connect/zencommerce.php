<?php

include_once("../API/config.php");

	$query_cart_data = $db->query("SELECT AES_DECRYPT(zencommerce, '" . SITE_KEY . "') AS decrypted_zencommerce from idevaff_carts_data");
	$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
	$cart_data=$query_cart_data->fetch();
	$webhookSecret=$cart_data['decrypted_zencommerce'];

    //sent server response

    $idv_url = $base_url . '/sale.php';
    $profile = '160';
    $admin_email = $address;

    $rawBody = file_get_contents( 'php://input' );
    $response_data = json_decode($rawBody, true);

    //check for required hook
    if ( $_SERVER['HTTP_X_WEBHOOK_NAME'] == 'order.paid' ) {
        //validate request
        if ( $_SERVER['HTTP_X_WEBHOOK_SHA1'] == sha1($_SERVER['HTTP_X_WEBHOOK_ID'] . ':' . $webhookSecret . ':' . $rawBody) ) {
            // now get required data
            $order_id = $response_data['order_id'];
            $ip_address = $response_data['ip_address'];
            $amount = $response_data['sum'] - $response_data['shipping_cost'];

            $data = array (
                "profile"       => $profile,
                "idev_saleamt"  => $amount,
                "idev_ordernum" => $order_id,
                'ip_address'    => $ip_address,
                'idev_secret'   => $secret
            );

            //check for coupon
            if ( isset($response_data['promo_code']) ) {
                $data['coupon_code'] = $response_data['promo_code'];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $idv_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $json = curl_exec($ch);
            curl_close($ch);

            //mail($admin_email, 'Zencommerce Pursed Data', print_r($data,true));
            header('X-PHP-Response-Code: 200', true, 200);
            die();
        }
    }

?>