<?PHP

if (!defined('IDEV_FILE_AUTH')) { die('validate.php - Unauthorized Access'); }

// Facebook Connect
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;

if (!function_exists('password_verify')) {
	require_once 'API/pass_lib.php';
}
if(!defined('SITE_KEY')) {
    require_once 'API/keys.php';
}

// ------------------------------------------------------------------
// Data is checked individually in case you want to make alterations.
// Don't make changes unless you know what you're doing.
// ------------------------------------------------------------------

// ------------------------------------------------------------------
// Check For Ban
// ------------------------------------------------------------------

if (isset($_POST['email'])) { $email = $_POST['email']; } else { $email = null; }

include("check_ban.php");
if ($passed_ban_check == true) {

// ------------------------------------------------------------------
// Define Username Variable
// ------------------------------------------------------------------

$username = $_POST['username'];
$username = strtolower($username);

// ------------------------------------------------------------------
// Check Username Exists
// ------------------------------------------------------------------

$check_username = $db->prepare("select id from idevaff_affiliates where username = ?");$check_username->execute(array($username));
if ($check_username->rowCount()) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $username_taken . "<BR />\n"; }

// ------------------------------------------------------------------
// Check Username Is Short
// ------------------------------------------------------------------
function username_short($credential) {
global $db;
$user_min = $db->query("select user_min from idevaff_config");
$user_min = $user_min->fetch();
$user_min = $user_min['user_min'];
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if ((strlen($credential) < $user_min)) {
$rtn_value=true; } return $rtn_value; }
$username_short = preg_replace("/user_min_chars/", $user_min, $username_short);
if (username_short($username)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $username_short . "<br />\n"; }

// ------------------------------------------------------------------
// Check Username Is Long
// ------------------------------------------------------------------
function username_long($credential) {
global $db;
$user_max = $db->query("select user_max from idevaff_config");
$user_max = $user_max->fetch();
$user_max = $user_max['user_max'];
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if((strlen($credential) > $user_max)) {
$rtn_value=true; } return $rtn_value; }
$username_long = preg_replace("/user_max_chars/", $user_max, $username_long);
if (username_long($username)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $username_long . "<br />\n"; }

// ------------------------------------------------------------------
// Check Username Is Valid
// ------------------------------------------------------------------
function username_valid($credential) {
global $db;
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if (!(preg_match("/[^a-zA-Z0-9-_]/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!username_valid($username)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $username_invalid . "<br />\n"; }

// ------------------------------------------------------------------
// Define Password Variables
// ------------------------------------------------------------------

$password = $_POST['password'];
$password_c = $_POST['password_c'];

// ------------------------------------------------------------------
// Check Passwords Match
// ------------------------------------------------------------------
if (($password) != ($password_c)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $password_mismatch . "<BR />\n"; }

// ------------------------------------------------------------------
// Check Password Is Short
// ------------------------------------------------------------------
function password_short($credential) {
global $db;
$pass_min = $db->query("select pass_min from idevaff_config");
$pass_min = $pass_min->fetch();
$pass_min = $pass_min['pass_min'];
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if ((strlen($credential) < $pass_min)) {
$rtn_value=true; } return $rtn_value; }
$password_short = preg_replace("/pass_min_chars/", $pass_min, $password_short);
if (password_short($password)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $password_short . "<br />\n"; }

// ------------------------------------------------------------------
// Check Password Is Long
// ------------------------------------------------------------------
function password_long($credential) {
global $db;
$pass_max = $db->query("select pass_max from idevaff_config");
$pass_max = $pass_max->fetch();
$pass_max = $pass_max['pass_max'];
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if((strlen($credential) > $pass_max)) {
$rtn_value=true; } return $rtn_value; }
$password_long = preg_replace("/pass_max_chars/", $pass_max, $password_long);
if (password_long($password)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $password_long . "<br />\n"; }

// ------------------------------------------------------------------
// Check Password Is Valid - Deprecated - Allow all chars.
// ------------------------------------------------------------------
//function password_valid($credential) {
//global $db;
//$rtn_value = false;
//if (get_magic_quotes_gpc()) {
//$credential = stripslashes($credential); }
//if (!(preg_match("/[^a-z0-9_]/i", $credential))) {
//$rtn_value=true; } return $rtn_value; }
//if (!password_valid($password)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $password_invalid . "<br />\n"; }

if ($f_er) {
// ------------------------------------------------------------------
// If Required - Check Email Present
// ------------------------------------------------------------------
if (!$email) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_email . "<br />\n"; }
}
// ------------------------------------------------------------------
// Check Email Is Valid
// ------------------------------------------------------------------
if ($email) {
function email_valid($credential) {
global $db;
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if ((preg_match("/^([a-zA-Z0-9_]|\\-|\\.)+@(([a-zA-Z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!email_valid($email)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $invalid_email . "<br />\n"; }
}

// ------------------------------------------------------------------
// Check Email Is Not Already Used
// ------------------------------------------------------------------
if ($emails_allowed > 0) {
$email_count = $db->prepare("select id from idevaff_affiliates where email = ?");
$email_count->execute(array($email));
if ($email_count->rowCount() >= $emails_allowed) {
if ($emails_allowed > 1) { $extension = "s"; } else { $extension = null; }
$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $email_already_used_1 . " " . $emails_allowed . " " . $email_already_used_2 . $extension . " " . $email_already_used_3 . "<br />\n"; }
}

if ((isset($_POST['company'])) && ($_POST['company'] != '')) { $company = $_POST['company']; } else { $company = null; } 
if ($f_cor == 1) { if (!$company) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_company . "<br />\n"; } }

if ((isset($_POST['payable'])) && ($_POST['payable'] != '')) { $payable = $_POST['payable']; } else { $payable = null; } 
if ($f_chr == 1) {
if (!$payable) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_checks . "<br />\n"; } }

if ((isset($_POST['url'])) && ($_POST['url'] != '')) { $website = $_POST['url']; } else { $website = null; }   
if ($f_wr == 1) { if ((!$website) || ($website == 'http://')) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_website . "<br />\n"; } }

if ((isset($_POST['tax_id_ssn'])) && ($_POST['tax_id_ssn'] != '')) { $tax = $_POST['tax_id_ssn']; } else { $tax = null; }  
if ($f_tr == 1) { if (!$tax) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_tax . "<br />\n"; } }

// ------------------------------------------------------------------
// Define Personal Variables
// ------------------------------------------------------------------

if ((isset($_POST['f_name'])) && ($_POST['f_name'] != '')) { $f_name = $_POST['f_name']; } else { $f_name = null; }  
if (($f_fnamer == 1) && (!$f_name)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_fname . "<br />\n"; }

if ((isset($_POST['l_name'])) && ($_POST['l_name'] != '')) { $l_name = $_POST['l_name']; } else { $l_name = null; }  
if (($f_lnamer == 1) && (!$l_name)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_lname . "<br />\n"; }

if ((isset($_POST['address_one'])) && ($_POST['address_one'] != '')) { $address_one = $_POST['address_one']; } else { $address_one = null; }  
if (($f_add1r == 1) && (!$address_one)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_address . "<br />\n"; }

if ((isset($_POST['address_two'])) && ($_POST['address_two'] != '')) { $address_two = $_POST['address_two']; } else { $address_two = null; }  
if (($f_add2r == 1) && (!$address_two)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_address . "<br />\n"; }

if ((isset($_POST['city'])) && ($_POST['city'] != '')) { $city = $_POST['city']; } else { $city = null; }  
if (($f_cityr == 1) && (!$city)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_city . "<br />\n"; }

if ((isset($_POST['state'])) && ($_POST['state'] != '')) { $state = $_POST['state']; } else { $state = null; }  
if (($f_stater == 1) && (!$state)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_state . "<br />\n"; }

if ((isset($_POST['zip'])) && ($_POST['zip'] != '')) { $zip = $_POST['zip']; } else { $zip = null; }  
if (($f_zipr == 1) && (!$zip)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_zip . "<br />\n"; }

if ((isset($_POST['phone'])) && ($_POST['phone'] != '')) { $phone = $_POST['phone']; } else { $phone = null; }  
if (($f_phoner == 1) && (!$phone)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_phone . "<br />\n"; }

if ((isset($_POST['fax'])) && ($_POST['fax'] != '')) { $fax = $_POST['fax']; } else { $fax = null; }  
if (($f_faxr == 1) && (!$fax)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_fax . "<br />\n"; }

// Country auto-required if used.
if (isset($_POST['country'])) { $country = $_POST['country']; } else { $country = null; }

// ------------------------------------------------------------------
// PayPal Variables
// ------------------------------------------------------------------
//commenting this temporary
/*

if (isset($_POST['pp'])) { $pp = 1; } else { $pp = 0; }
if (isset($_POST['pp_account'])) { $pp_account = $_POST['pp_account']; } else { $pp_account = null; } 
if (($force_paypal) && (!$pp_account)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $paypal_required . "<BR />\n";
} elseif (($pp) && (!$pp_account)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_paypal . "<BR />\n"; }

*/

// ------------------------------------------------------------------
//	Check for payment method
// ------------------------------------------------------------------

if(isset($_POST['payment_method'])) { $payment_method = (int) $_POST['payment_method']; } else { $payment_method = 0; }
$pp_account = null;
$stripe_data = null; 
$pp = 0;

if($payment_method) {
	if($payment_method == 1) {
		//paypal
		if (isset($_POST['pp_account'])) { $pp_account = $_POST['pp_account']; } else { $pp_account = null; }
		if(!$pp_account) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $paypal_required . "<BR />\n"; }
		elseif(!preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i",$pp_account)) {
			$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; Invalid Paypal Email <BR />\n";
		}
	}
} 

else {
	//no payment method selected
	$payment_method_error = "Please select a payment method.";
	$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $payment_method_error . "<BR />\n";
}





// ------------------------------------------------------------------
// T & C Variables
// ------------------------------------------------------------------
if (isset($_POST['accepted'])) { $terms_accepted = 1; } else { $terms_accepted = 0; }
if (($terms_f) && (!$terms_accepted)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_terms . "<BR />\n"; }

// ------------------------------------------------------------------
// CAN-SPAM Variables
// ------------------------------------------------------------------
if (isset($_POST['canspam_accepted'])) { $canspam_accepted = 1; } else { $canspam_accepted = 0; }
if (($canspam_f) && (!$canspam_accepted)) { $input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $canspam_error . "<BR />\n"; }

// ------------------------------------------------------------------
// Custom Field Variables
// ------------------------------------------------------------------

$getcustom = $db->query("select name, title from idevaff_form_fields_custom where req = '1'");
if ($getcustom->rowCount()) {
while ($qry = $getcustom->fetch()) {
$custom_name = $qry['name'];
$custom_title = $qry['title'];
if (!$_POST[$custom_name]) {
$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_custom . " " . "<b>" . $custom_title . "</b><BR />\n"; } } }

// ------------------------------------------------------------------
// Security Image
// ------------------------------------------------------------------
if ($use_security == 1) {
if (!isset($_POST['security_code'])) {
$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_security_code . "<BR />\n";
} else {
if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
unset($_SESSION['security_code']);
} else {
$input_error .= "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp; " . $missing_security_code . "<BR />\n";
} } }


// ------------------------------------------------------------------
// Logging from Facebook?
// ------------------------------------------------------------------
if (!empty($_POST['fb_user_id']) && empty($_POST['email'])) {
	$request = new FacebookRequest($fb_session, 'DELETE', '/me/permissions');
	$response = $request->execute();
	$input_error = "<img border='0' src='images/mark.gif' height='16' width='16'>&nbsp;" . $fb_permissions . "<BR />\n";
	unset($_SESSION['fb_token']);
}
else if (!empty($_POST['fb_user_id']) && !empty($_POST['email'])) {
	$input_error = "";
}





// ------------------------------------------------------------------
// Write Data If All Is Good
// ------------------------------------------------------------------

if (isset($_POST['payme'])) { $payme = $_POST['payme']; } else { $payme = $def_pay; }

//$cta = $db->query("select ta from idevaff_tlog where ti = '$ip_addr' order by id desc");
$cta = $db->prepare("select ta from idevaff_tlog where ti = ? order by id desc");
$cta->execute(array($ip_addr));
$ctb = $cta->fetch();
$ctc = $ctb['ta'];
if ($ctc) { $insert_tier = $ctc; } else { $insert_tier = 0; }
if (!$account_approval) { $setme = 1; } else { $setme = 0; }

if (!$input_error) {

$signup_date = time();

//password encription

$user_key = substr(strtr(base64_encode(sha1(microtime(true), true)), '+', '.'), 0, 22); //store this to database as user_key 
$password_enc = password_hash(SITE_KEY . $password . $user_key, PASSWORD_BCRYPT) ;

if (isset($_POST['email_language'])) { $write_email_language = $_POST['email_language']; } else { $write_email_language = null; }
if (!isset($ip_addr)) { $ip_addr = null; }
if(!isset($_POST['fb_user_id'])) {
    $_POST['fb_user_id'] = '';
}

$countaffiliates = $db->query("select id from idevaff_affiliates");
if (!$countaffiliates->rowCount()) { $insert_id = $startnumber; }

$level = 1;
$st=$db->prepare ("insert into idevaff_affiliates (id, fb_user_id, username, password, user_key, approved, payable, company, f_name, l_name, email, address_1, address_2, city, state, zip, country, phone, fax, url, pp, pay_method, paypal, type, level, signup_date, email_override, tc_status, ip, tax_id_ssn) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, AES_ENCRYPT(?, '" . AUTH_KEY . "'))");

$st->execute(array(!isset($insert_id) ? null : $insert_id, $_POST['fb_user_id'], $username, $password_enc,$user_key,$setme, $payable, $company, $f_name, $l_name, $email, $address_one, $address_two, $city, $state, $zip, $country, $phone, $fax, $website, $pp, $payment_method, $pp_account, $payme, $level, $signup_date, $write_email_language, $terms_accepted, $ip_addr, $tax));

if(is_array($mailchimp_data) && $mailchimp_data['enabled'] === '1') {
    require_once("$path/includes/integrations/mailchimp/mailchimp_signup.php");
}

if(is_array($getaweber) && $getaweber['enabled'] === '1') {
    include_once("$path/includes/integrations/aweber/aweber_signup.php");
}

if(is_array($constant_contact_data) && $constant_contact_data['enabled'] === '1') {
    include_once("$path/includes/integrations/constant_contact/cc_signup.php");
}

if(is_array($i_contact_data) && $i_contact_data['enabled'] === '1') {
    require_once("$path/includes/integrations/i_contact/ic_signup.php");
}

if(is_array($get_response_data) && $get_response_data['enabled'] === '1') {
    require_once("$path/includes/integrations/get_response/gr_signup.php");
}

if ($signup_api == 1) {
$NewAccountAPITrigger = true;
include_once("API/scripts/new_account_API_trigger.php");
}

if ($insert_tier > 0) {
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
//$db->query("insert into idevaff_tiers (parent, child) VALUES ('$insert_tier', '$insertid')");
$st = $db->prepare("insert into idevaff_tiers (parent, child) VALUES (?, ?)");
$st->execute(array($insert_tier,$insertid));
if ($email_tier_referral == 1) { include($path.'/templates/email/affiliate.new_tier.php'); }
}

// Start the new session.
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$idforsessionstart = $getid['id'];
$_SESSION[$install_directory_name.'_idev_LoggedID'] = $idforsessionstart;


if ($initialbalance > 0) {
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
//$db->query("insert into idevaff_sales (id, payment, bonus, approved, ip, code, currency) values ('$insertid', '$initialbalance', '1', '1', '$ip_addr', '$commission_time', '$currency')");
$st = $db->prepare("insert into idevaff_sales (id, payment, bonus, approved, ip, code, currency) values (?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($insertid,$initialbalance,'1','1',$ip_addr,$commission_time,$currency));
}


$getcustom = $db->query("select id, name from idevaff_form_fields_custom");
if ($getcustom->rowCount()) {
$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
while ($qry = $getcustom->fetch()) {
$cus_id = $qry['id'];
$cus_name = $qry['name'];
if (isset($_POST[$cus_name])) {
//$db->query("insert into idevaff_form_custom_data (affid, custom_id, custom_value) VALUES ('$insertid', '$cus_id', '" . $_POST[$cus_name] . "')"); 
$st = $db->prepare("insert into idevaff_form_custom_data (affid, custom_id, custom_value) VALUES (?, ?, ?)"); 
$st->execute(array($insertid,$cus_id,$_POST[$cus_name]));

} } }


//$db->query("delete from idevaff_tlog where ta = '$insert_tier' and ti = '$ip_addr'");
$st = $db->prepare("delete from idevaff_tlog where ta = ? and ti = ?");
$st->execute(array($insert_tier,$ip_addr));

if ($mailadmin == 1) { include($path.'/templates/email/admin.new_account.php'); }
if ($we == 1) { include($path.'/templates/email/affiliate.welcome.php'); }
$complete = 1;

}
}

?>