<?PHP

// FILE INCLUDE VALIDATION
if (!defined('IDEV_EMAIL')) { die('Unauthorized Access'); }
// -------------------------------------------------------------------------------------------------

if ($api_email_address == '') { $api_email_address = $address; }

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

if (isset($email_success)) {
if ($email_html_delivery == true) {
$mail->isHTML(true);
$content = "iDevAffiliate Daily Recurring Commissions Report<br />----------------------------------------------------------------<br />Current Recurring Commissions: " . $updated . "<br />New Commissions Created Today: " . $reset . "<br />Rec Commissions Removed Today: " . $removed . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$content = "iDevAffiliate Daily Recurring Commissions Report\n----------------------------------------------------------------\nCurrent Recurring Commissions: " . $updated . "\nNew Commissions Created Today: " . $reset . "\nRec Commissions Removed Today: " . $removed . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} else {

if (!$secret) { $secret = "None"; }
if ($email_html_delivery == true) {
$mail->isHTML(true);
$content = "Invalid or missing secret key. No recurring commissions were processed.<br/><br />Key Used: ". $secret . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$content = "Invalid or missing secret key. No recurring commissions were processed.\n\nKey Used: ". $secret . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}
}

$mail->Subject = "iDevAffiliate Recurring Commissions Report";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");

// Added for multiple CC's, semi-colon separated.
if($cc_email == true) {
	$cc_emails = explode(";", $cc_email_address);
	foreach($cc_emails as $added_cc_emails)
	{
		$added_cc_emails_stripped = str_replace(' ', '', $added_cc_emails);
		$mail->AddCC($added_cc_emails_stripped,"iDevAffiliate Admin");
	}
}

$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

?>