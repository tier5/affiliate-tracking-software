<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// ----------------------------------------------------------------
// We've designed this API file as simple as possible.  We didn't use any 
// complex queries and everything should be fairly self explanatory.
// Have fun customizing this API file to meet your needs.
// ----------------------------------------------------------------

// CONNECT TO THE DATABASE & MAKE SITE CONFIG SETTINGS AVAILABLE
// ----------------------------------------------------------------
require_once("../../API/config.php");

if ($api_email_address == '') { $api_email_address = $address; }

// CHECK VALID SECRET KEY IS PRESENT AND VALID
// - The variable is already sanitized.
// - The variable is already validated through _GET, or _POST.
// ------------------------------------------------------------------------------

$secret = check_type_api('secret');
$get_rows = $db->prepare("select secret from idevaff_config where secret = ? limit 1");
$get_rows->execute(array($secret));
if (is_numeric($secret) && $get_rows->rowCount()) {

// UPDATE ALL ACCOUNTS - RESET TO PAYOUT LEVEL 1
// ----------------------------------------------------------------
$db->query("update idevaff_affiliates set level = '1'");

// EMAIL ADMIN REPORT
// ----------------------------------------------------------------
if ($email_html_delivery == true) {
$content = "Payout levels for all affiliates have been reset to level 1.<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "Payout levels for all affiliates have been reset to level 1.\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} else {

// EMAIL FAILED SECRET NOTIFICATION
// ----------------------------------------------------------------
if (!$secret) { $secret = "None"; }
if ($email_html_delivery == true) {
$content = "Invalid or missing secret key. Payout levels have not been reset.<br /><br />Key Used: ". $secret . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "Invalid or missing secret key. Payout levels have not been reset.\n\nKey Used: ". $secret . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

}

include_once($path . "/templates/email/class.phpmailer.php");
include_once($path . "/templates/email/class.smtp.php");
$mail = new PHPMailer();

if ($email_smtp_delivery == true) {
$mail->IsSMTP();
$mail->SMTPAuth = $smtp_auth;
$mail->SMTPSecure = "$smtp_security";
$mail->Host = "$smtp_host";
$mail->Port = $smtp_port;
$mail->Username = "$smtp_user";
$mail->Password = "$smtp_pass"; }
$mail->CharSet = "$smtp_char_set";

if ($email_html_delivery == true) {
$mail->isHTML(true);
} else {
$mail->isHTML(false);
}

$mail->Subject = "iDevAffiliate API - Payout Level Reset";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();
?>
