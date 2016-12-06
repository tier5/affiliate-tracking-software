<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

include ("../API/config.php");

if(!function_exists('chargebee_validation')) {
function chargebee_validation($user = '', $pass = '') {

global $db;
$query_cart_data = $db->query("SELECT AES_DECRYPT(chargebee_user, '" . SITE_KEY . "') AS decrypted_user, AES_DECRYPT(chargebee_pass, '" . SITE_KEY . "') AS decrypted_pass from idevaff_carts_data");
$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
$cart_data=$query_cart_data->fetch();
$username=$cart_data['decrypted_user'];
$password=$cart_data['decrypted_pass'];
		
        //$username = 'username';
        //$password = 'password';
		
        if($user != '' && $pass != '' && $username === $user && $pass === $password)
            return true;
        else 
            return false;
    }
}

if (!chargebee_validation($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="My Website"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You need to enter a valid username and password.";
    exit;
} else {

    //now do chargify task here
    require '../API/chargebee/lib/ChargeBee.php';
    $webhook_request = file_get_contents('php://input');
    //$event = ChargeBee_Event::deserialize($webhook_request);
    //$eventType = $event->eventType;  // to get the event type
    //$content = $event->content();

    try {    
        $event = ChargeBee_Event::deserialize($webhook_request);
        $eventType = $event->eventType;  // to get the event type
        
        if($eventType === 'payment_succeeded') {
            $content = $event->content();
            
			$url = $base_url . '/sale.php';
            $profile = '120';
            
            //get amount and order id
            $idev_saleamt = 0;
            if(isset($content->transaction()->amount))
                $idev_saleamt = $content->transaction()->amount / 100;
            
            $idev_ordernum = '';
            if(isset($content->transaction()->id))
                $idev_ordernum = $content->transaction()->id;
            
            //get ip address
            $ip = '';
            if(isset($content->customer()->createdFromIp))
                $ip = $content->customer()->createdFromIp;
            
            //get coupon
            $coupon_code = "";
            if(is_array($content->subscription()->coupons) && count($content->subscription()->coupons) > 1) {
                foreach ($content->subscription()->coupons as $coupon_data):
                    $coupon_code .= $coupon_data->couponId . ' ';
                endforeach;
            } else {
                if(isset($content->subscription()->coupon))
                    $coupon_code = $content->subscription()->coupon;
            }
            
            $data = array(
                "profile" => $profile,
                "idev_saleamt" => $idev_saleamt,
                "idev_ordernum" => $idev_ordernum,
				"coupon_code" => $coupon_code,
                'ip_address' => $ip,
				'idev_secret'   => $secret
            );

            $ch = curl_init();   
            curl_setopt($ch, CURLOPT_URL, $url);                                    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
            $json = curl_exec($ch); 
            curl_close($ch);
            
            //mail('mail@mail.com','Chargify Request '. $_POST['event'],  print_r($data,true));
            //we need to send http status code 200 to confirm that webhook call is succeed
            //https://apidocs.chargebee.com/docs/api/events
            header('X-PHP-Response-Code: 200', true, 200);
            die();
        } else {
			//mail('mail@mail.com','Chargify Request','200');
            //header('X-PHP-Response-Code: 400', true, 400);
            //die('Invalid Webhook ' . $eventType);
            //we are good here, because we are only handling payment_succeeded webhook
            //if we don't send 200 ok, chargebee will send data to this link again
            header('X-PHP-Response-Code: 200', true, 200);
            die();
        }
		} catch (Exception $e) {
			//mail('mail@mail.com','Chargify Request','400');
			header('X-PHP-Response-Code: 400', true, 400);
			echo $e->getMessage();
			die;
    }
    exit;
}