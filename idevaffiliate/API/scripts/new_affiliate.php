<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// ------------------------------------------------------------------------------
// We've designed this API file as simple as possible.  We didn't use any 
// complex queries and everything should be fairly self explanatory.
// ------------------------------------------------------------------------------

// CONNECT TO THE DATABASE & MAKE SITE CONFIG SETTINGS AVAILABLE
// ------------------------------------------------------------------------------
include_once("../../API/config.php");

if (!function_exists('password_verify')) {
	require_once '../../API/pass_lib.php';
}
if(!defined('SITE_KEY')) {
    require_once '../../API/keys.php';
}

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
// - These variables are already sanitized.
// - These variables are already validated through global $$, _GET, or _POST.
// ------------------------------------------------------------------------------

$username = check_type_api('username');
$password = check_type_api('password');
$email = check_type_api('email');
$approved = check_type_api('approved');
if ($approved != '1') { $approved = '0'; }
$payout_type = check_type_api('payout_type');
$payout_level = check_type_api('payout_level');
$use_paypal = check_type_api('use_paypal');
$paypal_account = check_type_api('paypal_account');
$first_name = check_type_api('first_name');
$last_name = check_type_api('last_name');
$company = check_type_api('company');
$payable = check_type_api('payable');
$tax_id = check_type_api('tax_id');
$website = check_type_api('website');
$address_1 = check_type_api('address_1');
$address_2 = check_type_api('address_2');
$city = check_type_api('city');
$state = check_type_api('state');
$zip = check_type_api('zip');
$country = check_type_api('country');
$phone = check_type_api('phone');
$fax = check_type_api('fax');
$signup_date = time();

// FORCED TIER ACCOUNT LOGGING
$tier = check_type_api('tier');

// STANDARD TIER ACCOUNT LOGGING (overrides forced entry)
$ip_address = check_type_api('ip_address');
if ($ip_address) {
//$cta = $db->query("select ta from idevaff_tlog where ti = '$ip_address' order by id desc");
$cta = $db->prepare("select ta from idevaff_tlog where ti = ? order by id desc"); 
$cta->execute(array($ip_address));
$ctb = $cta->fetch();
$tier = $ctb['ta'];
}

// OVERRIDE APPROVED VARIABLE WITH SETTINGS FROM ADMIN CENTER
// Uncomment to disable this override.
// ----------------------------------------------------------------
// if (!$account_approval) { $approved = 1; } else { $approved = 0; }

// SET PAYOUT TYPE TO FIRST AVAILABLE IF NONE WAS PRESENT
// ----------------------------------------------------------------
if ($ap_1) { $payout_type = 1;
} elseif ($ap_2) { $payout_type = 2;
} elseif ($ap_3) { $payout_type = 3; }

// SET PAYOUT LEVEL TO 1 IF NONE WAS PRESENT
// ----------------------------------------------------------------
if (!$payout_level) { $payout_level = 1; }

// CHECK FOR REQUIRED INFORMATION
// ----------------------------------------------------------------
include_once("new_affiliate_validation.php");

if (!$error) {

// CREATE THE ACCOUNT
// ----------------------------------------------------------------
$st = $db->prepare("insert into idevaff_affiliates (
		username, 
		password, 
		approved, 
		payable, 
		tax_id_ssn, 
		company, 
		f_name, 
		l_name, 
		email, 
		address_1, 
		address_2, 
		city, 
		state, 
		zip, 
		country, 
		phone, 
		fax, 
		url, 
		pp, 
		paypal, 
		type, 
		level,
		signup_date

) VALUES (
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		?, 
		'0', 
		?, 
		?, 
		?,
		?

)");

$st->execute(array($username,$password,$approved,$payable,$tax_id,$company,$first_name,$last_name,$email,$address_1,$address_2,$city,$state,$zip,$country,$phone,$fax,$website,$paypal_account,$payout_type,$payout_level,$signup_date));

if (isset($tier)) {
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
$insert_tier = $tier;
$st = $db->prepare("insert into idevaff_tiers (parent, child) VALUES (?, ?)");
$st->execute(array($tier,$insertid));
if ($email_tier_referral == 1) { include($path.'/templates/email/affiliate.new_tier.php'); }
}

// NEW ACCOUNT API TRIGGER
// ----------------------------------------------------------------
if ($signup_api == 1) {
$f_name = $first_name; $l_name = $last_name;
$address_one = $address_1; $address_two = $address_2;
$NewAccountAPITrigger = true;
include_once("new_account_API_trigger.php");
}

// ENCRYPT PASSWORD & SSN/TAX ID IN DATABASE
// ----------------------------------------------------------------
include_once("../../includes/enc_insert.php");

// REMOVE TIER ENTRY LOGS
// ----------------------------------------------------------------
if ($tier) {
$st = $db->prepare("delete from idevaff_tlog where ta = ? and ti = ?");    
$st->execute(array($tier,$ip_address));
}

// EMAIL ADMIN - NEW ACCOUNT: IF ENABLED
// ----------------------------------------------------------------
if ($mailadmin == 1) { include($path.'/templates/email/admin.new_account.php'); }

// EMAIL AFFILIATE - WELCOME NOTICE: IF ENABLED
// ----------------------------------------------------------------
if ($we == 1) { include($path.'/templates/email/affiliate.welcome.php'); }

// WRITE SIGNUP BONUS IF ENABLED
// ----------------------------------------------------------------
if ($initialbalance > 0) {
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
$st = $db->prepare("insert into idevaff_sales (id, payment, bonus, approved, ip, code, currency) values (?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($insertid,$initialbalance,'1','1',$ip_addr,$commission_time,$currency));
}


} else {

// EMAIL FAILED VALIDATION TO ADMIN
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
$content = "Account creation failed.<br/><br />Reason:<br />" . nl2br($error) . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$content = "Account creation failed.\n\nReason:\n" . $error . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

$mail->Subject = "iDevAffiliate API - New Account Failure";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

}

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
$content = "Invalid or missing secret key.  No account was created.<br/><br />Key Used: ". $secret . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$content = "Invalid or missing secret key.  No account was created.\n\nKey Used: ". $secret . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

$mail->Subject = "iDevAffiliate API - New Account Failure";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();
}



?>
