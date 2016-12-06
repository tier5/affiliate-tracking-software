<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// Let's check if the entry is from unbounce ip addresses
$allowed_ip_addresses = array('54.241.34.25', '50.19.99.184');
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ip_addresses)) {
	//mail('mail@mail.com', 'Tracking Pixel Called', 'Not submitted from Unbounce: '.$_SERVER['REMOTE_ADDR']);
	exit;
}

include("../API/config.php");

// This is a sample PHP script that demonstrates accepting a POST from the        
// Unbounce form submission webhook, and then sending an email notification.      
function stripslashes_deep($value) {
  $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);

  return $value;
}

// First, grab the form data.  Some things to note:                               
// 1.  PHP replaces the '.' in 'data.json' with an underscore.                    
// 2.  Your fields names will appear in the JSON data in all lower-case,          
//     with underscores for spaces.                                               
// 3.  We need to handle the case where PHP's 'magic_quotes_gpc' option           
//     is enabled and automatically escapes quotation marks.                      
if (get_magic_quotes_gpc()) {
  $unescaped_post_data = stripslashes_deep($_POST);
} else {
  $unescaped_post_data = $_POST;
}
$form_data = json_decode($unescaped_post_data['data_json']);

$profile_id = 143;
$ip_address = $form_data->ip_address[0];
//$idev_saleamt = $form_data->idev_saleamt[0];
$idev_leadamt = $form_data->idev_leadamt[0];
$idev_ordernum = $form_data->idev_ordernum[0];

/* generate tracking url */
$tracking_url = $base_url.'/sale.php';
$tracking_fields = 'profile='.$profile_id.'&ip_address='.$ip_address.'&idev_leadamt='.$idev_leadamt.'&idev_ordernum='.$idev_ordernum.'&idev_secret='.$secret;
			
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