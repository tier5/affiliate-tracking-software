<?PHP

include_once("../API/config.php");
include_once("includes/session.check.php");

$fail_message = null;


// ------------------------------------------------------------------
// Username is missing.
// ------------------------------------------------------------------
if(!$username) { $fail_message = "<strong>Error!</strong> Missing affiliate username."; }

if (!$fail_message) {
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
if (username_short($username)) { $fail_message = "<strong>Error!</strong> " . $username_short; }
}

if (!$fail_message) {
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
if (username_long($username)) { $fail_message = "<strong>Error!</strong> " . $username_long; }
}

if (!$fail_message) {
// ------------------------------------------------------------------
// Check Username Is Valid
// ------------------------------------------------------------------
function username_valid($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if (!(preg_match("/[^a-zA-Z0-9-_]/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!username_valid($username)) { $fail_message = "<strong>Error!</strong> " . $username_invalid; }
}

if (!$fail_message) {
// ------------------------------------------------------------------
// Check Username Exists
// ------------------------------------------------------------------
$check_username = $db->prepare("select id from idevaff_affiliates where username = ?");
$check_username->execute(array($username));
if ($check_username->rowCount()) { $fail_message = "<strong>Error!</strong> Username is taken."; }
}

if (!$fail_message) {
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
if (password_short($password)) { $fail_message = "<strong>Error!</strong> " . $password_short; }
}

if (!$fail_message) {
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
if (password_long($password)) { $fail_message = "<strong>Error!</strong> " . $password_long; }
}

//if (!$fail_message) {
// ------------------------------------------------------------------
// Check Password Is Valid - deprecated.
// ------------------------------------------------------------------
//function password_valid($credential) {
//$rtn_value = false;
//if (get_magic_quotes_gpc()) {
//$credential = stripslashes($credential); }
//if (!(preg_match("/[^a-z0-9_]/i", $credential))) {
//$rtn_value=true; } return $rtn_value; }
//if (!password_valid($password)) { $fail_message = "<strong>Error!</strong> " . $password_invalid; }
//}




if (!$fail_message) {
// ------------------------------------------------------------------
// First Name Is Valid
// ------------------------------------------------------------------
if(!$f_name) { $fail_message = "<strong>Error!</strong> Missing affiliate first name."; }
}

if (!$fail_message) {
// ------------------------------------------------------------------
// Last Name Is Valid
// ------------------------------------------------------------------
if(!$l_name) { $fail_message = "<strong>Error!</strong> Missing affiliate last name."; }
}



if (!$fail_message) {
// ------------------------------------------------------------------
// If Required - Check Email Present
// ------------------------------------------------------------------
if (!$email) { $fail_message = "<strong>Error!</strong> " . $missing_email; }
}

if (!$fail_message) {
// ------------------------------------------------------------------
// Check Email Is Valid
// ------------------------------------------------------------------
if ($email) {
function email_valid($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if ((preg_match("/^([a-zA-Z0-9_]|\\-|\\.)+@(([a-zA-Z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!email_valid($email)) { $fail_message = "<strong>Error!</strong> " . $invalid_email; }
}
}

if (!$fail_message) {
// ------------------------------------------------------------------
// Check Email Is Not Already Used
// ------------------------------------------------------------------
if ($emails_allowed > 0) {
$email_count = $db->prepare("select id from idevaff_affiliates where email = ?");
$email_count->execute(array($email));
if ($email_count->rowCount() >= $emails_allowed) {
if ($emails_allowed > 1) { $extension = "s"; } else { $extension = null; }
$fail_message = "<strong>Error!</strong> Account cannot be created.  Only " . $emails_allowed . " account" . $extension . " can be created per email address."; }
}

}


// ------------------------------------------------------------------
// Write Data If All Is Good
// ------------------------------------------------------------------




if (!$fail_message) {

$signup_date = time();

//password hash
$user_key = substr(strtr(base64_encode(sha1(microtime(true), true)), '+', '.'), 0, 22); //store this to database as user_key 
$password_enc = password_hash(SITE_KEY . $password . $user_key, PASSWORD_BCRYPT) ;

$countaffiliates = $db->query("select id from idevaff_affiliates");
if (!$countaffiliates->rowCount()) { $insert_id = $startnumber; }

$st=$db->prepare ("insert into idevaff_affiliates (id, fb_user_id, username, password, user_key, approved, f_name, l_name, email, type, level, signup_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)");
$st->execute(array(!isset($insert_id) ? null : $insert_id,'',$username,$password_enc,$user_key,$acct_status,$f_name,$l_name,$email,$def_pay,$signup_date));
$success_message = "<strong>Success!</strong> New account was created.";


if ($mailadmin == 1) { include($path.'/templates/email/admin.new_account.php'); }
if ($send_welcome == 1) { include($path.'/templates/email/affiliate.welcome.php'); }

if(is_array($mailchimp_data) && $mailchimp_data['enabled'] === '1') {
    require_once("$path/includes/integrations/mailchimp/mailchimp_signup.php");
}

if(is_array($getaweber) && $getaweber['enabled'] === '1') {
    require_once("$path/includes/integrations/aweber/aweber_signup.php");
}

if(is_array($constant_contact_data) && $constant_contact_data['enabled'] === '1') {
    require_once("$path/includes/integrations/constant_contact/cc_signup.php");
}

if(is_array($i_contact_data) && $i_contact_data['enabled'] === '1') {
    require_once("$path/includes/integrations/i_contact/ic_signup.php");
}

if(is_array($get_response_data) && $get_response_data['enabled'] === '1') {
    require_once("$path/includes/integrations/get_response/gr_signup.php");
}

$newid = $db->prepare("select id from idevaff_affiliates where username = ?");
$newid->execute(array($username));
$getid = $newid->fetch();
$insertid = $getid['id'];
if ($initialbalance > 0) {
$st=$db->prepare("insert into idevaff_sales (id, payment, bonus, approved, ip, code, currency) values (?, ?, '1', '1', ?, ?, ?)");
$st->execute(array($insertid,$initialbalance,$ip_addr,$commission_time,$currency));
$warning_message = "<strong>Notice!</strong> A new signup bonus was also created for this account.";
}



}


?>