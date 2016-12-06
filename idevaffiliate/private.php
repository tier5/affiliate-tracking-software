<?PHP
$control_panel_session = true;
unset($_SESSION[$install_directory_name.'_idev_LoggedID']);
include_once("includes/control_panel.php");

if (isset($_POST['signup_code'])) {

// Get token from form
$token_affiliate_private = '';
if (isset($_POST['token_affiliate_private'])) {
if ($_POST['token_affiliate_private'] != '') {
$token_affiliate_private = htmlentities($_POST['token_affiliate_private'], ENT_QUOTES, 'UTF-8'); } }

// Get token from database
$token_db = '';
$token_db = $db->query("select affiliate_private from idevaff_tokens");
$token_db_results = $token_db->fetch();
$token_db = $token_db_results['affiliate_private'];

$check_code = $db->prepare("select * from idevaff_private where code = ?");
$check_code->execute(array($_POST['signup_code']));
if ($check_code->rowCount()) {

$time_now = time();
$qry = $check_code->fetch();
$signup_id = $qry['id'];
$signup_type = $qry['type'];
$signup_expires = $qry['expires'];

if ($signup_type == '1') {
if ($signup_expires > $time_now) {
$_SESSION['idev_private'] = 'true';
header("Location: signup.php");
} elseif ($signup_expires < $time_now) {
$smarty->assign('display_signup_errors', '1');
$smarty->assign('error_title', $private_error_title);
$smarty->assign('error_list', $private_error_expired);
} } elseif ($signup_expires == '0') {
$_SESSION['idev_private'] = 'true';
header("Location: signup.php"); }

} else {

$smarty->assign('display_signup_errors', '1');
$smarty->assign('error_title', $private_error_title);
$smarty->assign('error_list', $private_error_invalid);
}

}

$smarty->assign('private_heading', $private_heading);
$smarty->assign('private_info', $private_info);
$smarty->assign('private_required_heading', $private_required_heading);
$smarty->assign('private_code_title', $private_code_title);
$smarty->assign('private_button', $private_button);

include_once("includes/tokens.php");

$smarty->display('private.tpl');

?>