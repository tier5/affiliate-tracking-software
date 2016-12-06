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
$affiliate_id = check_type_api('affiliate_id');
$force_delete = check_type_api('force_delete');
if ($force_delete != "true") { $force_delete = null; }
$added_insert = null;
	
// CHECK IF AFFILIATE ID EXISTS
// ----------------------------------------------------------------
if ($affiliate_id) {

$check_affiliate_id=$db->prepare("select id from idevaff_affiliates where id = ?");
$check_affiliate_id->execute(array($affiliate_id));


if ($check_affiliate_id->rowCount()) {

// TERMINATE THIS ACCOUNT
// ----------------------------------------------------------------
	$check_for_fb = $db->prepare("select id from idevaff_affiliates where fb_user_id = '' and id = ?");
	$check_for_fb->execute(array($affiliate_id));
	
	if ((isset($force_delete)) || (!$check_for_fb->rowCount())) {
	
	if (!$check_for_fb->rowCount()) { $added_insert = "a facebook account and was"; }

	// this is a facebook account or delete has been forced - permanently delete
	$st=$db->prepare("DELETE FROM idevaff_affiliates where id = ?");
	$st->execute(array($affiliate_id));
	$added_message = "This account was " . $added_insert . " permanently deleted.";

	} else {
	
	// move to deleted list
	$st=$db->prepare("update idevaff_affiliates set approved = '0', suspended = '0', hits_in = '0', conv = '0', level = '1', tc_status = '0' where id = ?");
	$st->execute(array($affiliate_id));
	$st=$db->prepare("INSERT INTO idevaff_deleted_accounts SELECT * FROM idevaff_affiliates WHERE id = ?");
	$st->execute(array($affiliate_id));
	$added_message = "This account as moved to the Declined Accounts list.";
	$st=$db->prepare("DELETE FROM idevaff_affiliates where id = ?");
	$st->execute(array($affiliate_id));
	}
	
	define('TERMINATE_ROUTINE', TRUE);
	$account = $affiliate_id;
	include ("../../includes/terminate_routines.php");

if ($email_html_delivery == true) {
$content = "The API file (terminate_affiliate.php) successfully terminated an affiliate account.<br/><br />Affiliate ID: " . $affiliate_id . "<br />Account Status: " . $added_message . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_affiliate.php) successfully terminated an affiliate account.\n\nAffiliate ID: " . $affiliate_id . "\nAccount Status: " . $added_message . "\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} else {

// AFFILIATE NOT FOUND
// ----------------------------------------------------------------
if ($email_html_delivery == true) {
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.<br/><br />Reason:<br />- The affiliate ID was found.<br /><br />Affiliate ID: " . $affiliate_id . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.\n\nReason:\n- The affiliate ID was found.\n\nAffiliate ID: " . $affiliate_id . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} } else {

// AFFILIATE ID NOT RECEIVED
// ----------------------------------------------------------------
if ($email_html_delivery == true) {
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.<br/><br />Reason:<br />- No affiliate ID was received.<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.\n\nReason:\n- No affiliate ID was received.\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
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

$mail->Subject = "iDevAffiliate API - Affiliate Termination Notification";
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
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.<br /><br />Reason: Invalid or missing secret key.<br /><br />No affiliate account was terminated.<br /><br />Key Used: ". $secret;
} else {
$mail->isHTML(false);
$content = "The API file (terminate_affiliate.php) tried to terminate an affiliate account and couldn't.\n\nReason: Invalid or missing secret key.\n\nNo affiliate account was terminated.\n\nKey Used: ". $secret;
}

$mail->Subject = "iDevAffiliate API - Affiliate Termination Failure";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

}

?>
