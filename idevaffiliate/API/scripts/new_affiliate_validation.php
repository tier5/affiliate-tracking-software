<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

$error = null;

// ------------------------------------------------------------------
// Check Username Exists
// ------------------------------------------------------------------
$check_username = $db->prepare("select id from idevaff_affiliates where username = ?");
$check_username->execute(array($username));
if ($check_username->rowCount()) { $error .= "- Username is taken.\r\n"; }

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
if (username_short($username)) { $error .= "- Username is too short or missing. " . $user_min . " charaters min.\r\n"; }

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
if (username_long($username)) { $error .= "- Username is too long. " . $user_max . " characters max.\r\n"; }

// ------------------------------------------------------------------
// Check Username Is Valid
// ------------------------------------------------------------------
function username_valid($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if (!(preg_match("/[^a-zA-Z0-9-_]/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!username_valid($username)) { $error .= "- Username is invalid.  Can only be letters, numbers and underscores."; }

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
if (password_short($password)) { $error .= "- Password is too short or missing. " . $pass_min . " charaters min.\r\n"; }

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
if (password_long($password)) { $error .= "- Password is too long. " . $pass_max . " characters max.\r\n"; }

// ------------------------------------------------------------------
// Check Password Is Valid - deprecated.
// ------------------------------------------------------------------
//function password_valid($credential) {
//$rtn_value = false;
//if (get_magic_quotes_gpc()) {
//$credential = stripslashes($credential); }
//if (!(preg_match("/[^a-z0-9_]/i", $credential))) {
//$rtn_value=true; } return $rtn_value; }
//if (!password_valid($password)) { $error .= "- Password is invalid.  Can only be letters, numbers and underscores.\r\n"; }

// ------------------------------------------------------------------
// Check Email Address Is Present
// ------------------------------------------------------------------
// if (!$email) { $error .= "- Missing email address.\r\n"; }

// ------------------------------------------------------------------
// Check Email Is Valid
// ------------------------------------------------------------------
function email_valid($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if ((preg_match("/^([a-zA-Z0-9_]|\\-|\\.)+@(([a-zA-Z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i", $credential))) {
$rtn_value=true; } return $rtn_value; }
if (!email_valid($email)) { $error .= "- Email address is missing or invalid.\r\n"; }

// ------------------------------------------------------------------
// ALL OTHER VALUES ARE SANITIZED BUT NOT CHECKED AGAINST RULES.
// You can do that below if you want.
// ------------------------------------------------------------------

?>