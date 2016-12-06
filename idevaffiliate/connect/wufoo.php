<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

include_once("../API/config.php");

	$query_cart_data = $db->query("SELECT AES_DECRYPT(wufoo_key, '" . SITE_KEY . "') AS decrypted_key from idevaff_carts_data");
	$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
	$cart_data=$query_cart_data->fetch();
	$productKey=$cart_data['decrypted_key'];
	
$idv_url = $base_url . '/sale.php';
$profile = '131';
$admin_email = $address;
$HandshakeKey = $productKey;

if( isset($_POST['HandshakeKey'], $_POST['PurchaseTotal']) && $_POST['HandshakeKey'] == $HandshakeKey  ) {

    $amount = isset($_POST['PurchaseTotal']) ? $_POST['PurchaseTotal'] : '0.00';

    if( isset( $_POST['FormStructure'] ) ) {
        $_POST['FormStructure'] = json_decode($_POST['FormStructure'], true);
        $id = $_POST['FormStructure']['Url'] . '_' . $_POST['EntryId'];
    } else {
        $id = $_POST['EntryId'];
    }

    $ip = $_POST['IP'];

    $data = array (
        "profile"       => $profile,
        "idev_saleamt"  => $amount,
        "idev_ordernum" => $id,
        'ip_address'    => $ip,
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
}



