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
	
// QUERY & SANITIZE ALL INCOMING DATA
// ----------------------------------------------------------------
$order_number = check_type_api('order_number');

// CHECK IF ORDER NUMBER EXISTS
// ----------------------------------------------------------------
if ($order_number) {
$check_order_number = $db->prepare("select id from idevaff_recurring where tracking = ?");
$check_order_number->execute(array($order_number));

if ($check_order_number->rowCount()) {

// REMOVE THE COMMISSIONS
// ----------------------------------------------------------------
$st = $db->prepare("delete from idevaff_recurring where tracking = ?");
$st->execute(array($order_number));

if ($email_html_delivery == true) {
$content = "The API file (terminate_recurring.php) successfully removed a recurring commission.<br/><br />Order Number: " . $order_number . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_recurring.php) successfully removed a recurring commission.\n\nOrder Number: " . $order_number . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} else {

// COMMISSION NOT FOUND
// ----------------------------------------------------------------
if ($email_html_delivery == true) {
$content = "The API file (terminate_recurring.php) tried to remove a commission and couldn't.<br/><br />Reason:<br />- No recurring commission was found with the provided order number.<br /><br />Order Number Received: " . $order_number . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_recurring.php) tried to remove a commission and couldn't.\n\nReason:\n- No recurring commission was found with the provided order number.\n\nOrder Number Received: " . $order_number . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} } else {

// ORDER NUMBER NOT RECEIVED
// ----------------------------------------------------------------
if ($email_html_delivery == true) {
$content = "The API file (terminate_recurring.php) tried to remove a commission and couldn't.<br/><br />Reason:<br />- No order number was received.<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_recurring.php) tried to remove a commission and couldn't.\n\nReason:\n- No order number was received.\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

}

// EMAIL NOTIFICATION TO ADMIN
// ----------------------------------------------------------------
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

$mail->Subject = "iDevAffiliate API - Recurring Commission Removal Notification";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

} else {

// EMAIL FAILED SECRET NOTIFICATION
// ----------------------------------------------------------------
if (!$secret) { $secret = "None"; }
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
$content = "Invalid or missing secret key.  No recurring commission was removed.<br /><br />Key Used: ". $secret;
} else {
$mail->isHTML(false);
$content = "Invalid or missing secret key.  No recurring commission was removed.\n\nKey Used: ". $secret;
}

$mail->Subject = "iDevAffiliate API - Recurring Commission Removal Failure";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

}

?>
