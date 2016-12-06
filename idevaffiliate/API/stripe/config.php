<?php
//add required files here
require_once 'init.php';
require_once 'oauth2/lib/OAuth2Client.php';
require_once 'StripeOAuth/StripeOAuth.class.php';
require_once 'StripeOAuth/StripeOAuth2Client.class.php';

$query = $db->prepare("select AES_DECRYPT(stripe_client_id, '" . SITE_KEY . "') AS decrypted_stripe_id, AES_DECRYPT(stripe_api_secret, '" . SITE_KEY . "') AS decrypted_stripe_key from idevaff_config limit 1");
$query->execute();
$row = $query->fetch();
if(is_array($row) && !empty($row)) {
    define( "STRIPE_PLATFORM_SECRET", isset($row['decrypted_stripe_id']) ? $row['decrypted_stripe_id'] : '' );
    define( "STRIPE_API_SECRET", isset($row['decrypted_stripe_key']) ? $row['decrypted_stripe_key'] : '' );
}
