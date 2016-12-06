<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

if (isset($_POST['idev_ordernum'])) {

include("../API/config.php");

$profile_id = 144;
$ip_address = $_POST['ip'];
$idev_leadamt = $_POST['idev_leadamt'];
$idev_ordernum = $_POST['idev_ordernum'];

/* generate tracking url */
$tracking_url = $base_url.'/sale.php';
$tracking_fields = 'profile='.$profile_id.'&ip_address='.$ip_address.'&idev_leadamt='.$idev_leadamt.'&idev_ordernum='.$idev_ordernum.'&idev_secret='.$secret;
				
//mail('mail@mail.com', 'Tracking Pixel Called', $tracking_url.'?'.$tracking_fields);
				
/* submit url */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tracking_url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $tracking_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($ch);
curl_close($ch);

}

?>