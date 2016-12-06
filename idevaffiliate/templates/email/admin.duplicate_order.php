<?PHP

// FILE INCLUDE VALIDATION
if (!defined('IDEV_EMAIL')) { die('Unauthorized Access'); }
// -------------------------------------------------------------------------------------------------

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
$sendBody = "iDevAffiliate tried to write a new commission to the system and couldn't.  This is because a commission already exists in the database with this order number.<br /><br />--------<br />Duplicate Order Number: " . $idev_ordernum . "<br />--------<br /><br />To change these settings and allow duplicate order numbers into the system, login to your admin center and navigate to the following area.<br /><br />General Settings > Fraud Control - Pick a setting other than \"Order Number\".<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$sendBody = "iDevAffiliate tried to write a new commission to the system and couldn't.  This is because a commission already exists in the database with this order number.\n\n--------\nDuplicate Order Number: " . $idev_ordernum . "\n--------\n\nTo change these settings and allow duplicate order numbers into the system, login to your admin center and navigate to the following area.\n\nGeneral Settings > Fraud Control - Pick a setting other than \"Order Number\".\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

$mail->Subject = "iDevAffiliate Duplicate Commission Error";
$mail->From = "$address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$address","iDevAffiliate System");

// Added for multiple CC's, semi-colon separated.
if($cc_email == true) {
	$cc_emails = explode(";", $cc_email_address);
	foreach($cc_emails as $added_cc_emails)
	{
		$added_cc_emails_stripped = str_replace(' ', '', $added_cc_emails);
		$mail->AddCC($added_cc_emails_stripped,"iDevAffiliate Admin");
	}
}

$mail->Body = $sendBody;

$mail->Send();
$mail->ClearAddresses();

?>