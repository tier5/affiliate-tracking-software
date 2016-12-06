<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

$order_number = null;

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
if ($order_number != '') {

// GATHER COMMISSION DATA
// ----------------------------------------------------------------
$check_order_number = $db->prepare("select * from idevaff_sales where tracking = ? and approved = '0'");
$check_order_number->execute(array($order_number));
if ($check_order_number->rowCount()) {

$commission_data = $check_order_number->fetch();
$record = $commission_data['record'];
$aff_id = $commission_data['id'];
$cust_ip = $commission_data['ip'];
$payment = $commission_data['payment'];
$amount = $commission_data['amount'];
$tid1 = $commission_data['tid1'];
$tid2 = $commission_data['tid2'];
$tid3 = $commission_data['tid3'];
$tid4 = $commission_data['tid4'];
$sub_id = $commission_data['sub_id'];

$getpaylevel = $db->prepare("select level, type from idevaff_affiliates where id = ?");
$getpaylevel->execute(array($aff_id));
$paylevel=$getpaylevel->fetch();
$level=$paylevel['level'];
$type=$paylevel['type'];

// APPROVE THE COMMISSION
// ----------------------------------------------------------------
$approve_commission = $db->prepare("update idevaff_sales set approved = '1' where tracking = ?");
$approve_commission -> execute(array($order_number));

// GET GEO LOCATION
include_once ($path . "/includes/geo.php");

// --------------------------------------------
// START POST BACK TRIGGER
// COMING SOON
// --------------------------------------------
/*
$check_for_postback = $db->prepare("select id from idevaff_postback where affiliate_id = ?");
$check_for_postback -> execute(array($aff_id));
if ($check_for_postback->rowCount()) {
$postback_affiliate_id = $aff_id;
$postback_order_number = $order_number;
$postback_commission = number_format($payment,$decimal_symbols);
$postback_sale_amount = number_format($amount,$decimal_symbols);
$postback_sub_id = $sub_id;
$postback_tid1 = $tid1;
$postback_tid2 = $tid2;
$postback_tid3 = $tid3;
$postback_tid4 = $tid4;
$postback_currency = $currency_to_write;
include ($path . "/includes/postback.php");
}
*/
// --------------------------------------------
// END POST BACK TRIGGER
// --------------------------------------------

// UPDATE MARKETING STATS
// ----------------------------------------------------------------
if ($aff_lock == 1) { $unlock = " order by id desc"; } else { $unlock = null; }
//$checkip = $db->query("select src1, src2 from idevaff_iptracking where ip = '$cust_ip'{$unlock}");
$checkip = $db->prepare("select src1, src2 from idevaff_iptracking where ip = ? {$unlock}");
$checkip->execute(array($cust_ip));
$ipdata = $checkip->fetch();
$src1 = $ipdata['src1'];
$src2 = $ipdata['src2'];

if (($src1) && ($src2)) {
if ($src1 == 1) { $table = "banners"; $col = "number"; }
if ($src1 == 2) { $table = "ads"; $col = "id"; }
if ($src1 == 3) { $table = "links"; $col = "id"; }
if ($src1 == 4) { $table = "htmlads"; $col = "id"; }
if ($src1 == 5) { $table = "email_templates"; $col = "id"; }
if ($src1 == 6) { $table = "peels"; $col = "number"; }
$st = $db->prepare("update idevaff_$table set conv = conv+1 where $col = ?");
$st->execute(array($src2));
}
if ($type == 3) { 
    $st = $db->prepare("update idevaff_affiliates set conv = conv+1 where id = ?");
    $st->execute(array($aff_id));
}

// EMAIL AFFILIATE - NEW COMMISSION: IF ENABLED
// ----------------------------------------------------------------
if ($sale_notify_affiliate == 1) {
$id = $aff_id;
$email = 'top';
$payoute = $payment;
include($path . "/templates/email/affiliate.new_commission.php"); }

// INSERT TIER COMMISSION IF REQUIRED
// ----------------------------------------------------------------
$idev_tier_1_st = $db->prepare("select parent from idevaff_tiers where child = ? order by id");
$idev_tier_1_st->execute(array($id));
$idev_tier_1 = $idev_tier_1_st->fetch();
$texist = $idev_tier_1['parent'];
if ($texist > 0) {

$acct_st = $db->prepare("select * from idevaff_sales where record = ?");
$acct_st->execute(array($record));
$qry = $acct_st->fetch();
$uid = $qry['record'];
$id = $qry['id'];
$idev_id_override = $qry['id']; // for overrides
$payment = $qry['payment'];
$tracking_code = $qry['tracking'];
$sales_code = $qry['code'];
$recstatus = $qry['recurring'];
$op1 = $qry['op1'];
$op2 = $qry['op2'];
$op3 = $qry['op3'];
$profile = $qry['profile'];
$type = $qry['type'];
$ip = $qry['ip'];
$amount = $qry['amount'];
$sub_id = $qry['sub_id'];
$tid1 = $qry['tid1'];
$tid2 = $qry['tid2'];
$tid3 = $qry['tid3'];
$tid4 = $qry['tid4'];
$target_url = $qry['target_url'];
$referring_url = $qry['referring_url'];
$currency_to_write = $qry['currency'];
$converted_amount = $qry['converted_amount'];
$tracking_method_used = $qry['tracking_method'];

$tiernumber = $texist;
$idev_ordernum = $tracking_code;
$avar = $amount;
$r_url = $referring_url;
$idev = $id;
$ip_addr = $ip;
$ov1 = $op1;
$ov2 = $op2;
$ov3 = $op3;
$commission_time = $sales_code;
 } else {
 $tiernumber = 0; }
$payout = $payment;
if ($tier_numbers > 0) { include ($path . "/includes/tiers.php"); }

// PROCESS OVERRIDE COMMISSIONS
$idev_id_override = $aff_id;
include ($path . "/includes/overrides.php");
// -------------------------------------

// PROCESS PERFORMANCE REWARDS: IF ENABLED
// ----------------------------------------------------------------
if ($rewards == 1) {
$afftype = $type;															
if (($rew_app == 1) && ($afftype == 1)) { $process = 1; }
if (($rew_app == 1) && ($afftype == 2)) { $process = 1; }
if (($rew_app == 2) && ($afftype == 3)) { $process = 1; }
if ($rew_app == 3) { $process = 1; }
if ($process == 1) {
$update_account_process = $aff_id;
include($path . "/includes/process_rewards.php");
} }

// COMMISSON APPROVED EMAIL
// ----------------------------------------------------------------
$subject = "iDevAffiliate API - Commission Approved";
if ($email_html_delivery == true) {
$content = "The iDevAffiliate API for commission approval successfully approved a commission.<br/><br />Order Number: ". $order_number . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The iDevAffiliate API for commission approval successfully approved a commission.\n\nOrder Number: ". $order_number . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} else {

// COMMISSION NOT FOUND
// ----------------------------------------------------------------
$subject = "iDevAffiliate API - Commission Not Found";
if ($email_html_delivery == true) {
$content = "The iDevAffiliate API for commission approval tried to approve a commission and couldn't.<br/><br />Reason:<br />- No commission was found with the provided order number.<br /><br />Order Number Received: ". $order_number . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The iDevAffiliate API for commission approval tried to approve a commission and couldn't.\n\nReason:\n- No commission was found with the provided order number.\n\nOrder Number Received: ". $order_number . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

} } else {

// ORDER NUMBER NOT RECEIVED
// ----------------------------------------------------------------
$subject = "iDevAffiliate API - Order Number Error";
if ($email_html_delivery == true) {
$content = "The iDevAffiliate API for commission approval tried to approve a commission and couldn't.<br/><br />Reason:<br />- No order number was received.<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$content = "The iDevAffiliate API for commission approval tried to approve a commission and couldn't.\n\nReason:\n- No order number was received.\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
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

$mail->Subject = "$subject";
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
$content = "Invalid or missing secret key.  No commission was removed.<br/><br />Key Used: ". $secret . "<br /><br />--------<br />Message Auto-Sent By iDevAffiliate " . $version;
} else {
$mail->isHTML(false);
$content = "Invalid or missing secret key.  No commission was removed.\n\nKey Used: ". $secret . "\n\n--------\nMessage Auto-Sent By iDevAffiliate " . $version;
}

$mail->Subject = "iDevAffiliate API - Error";
$mail->From = "$api_email_address";
$mail->FromName = "iDevAffiliate System";
$mail->AddAddress("$api_email_address","iDevAffiliate System");
if($cc_email == true) { $mail->AddCC("$cc_email_address","iDevAffiliate System"); }
$mail->Body = $content;

$mail->Send();
$mail->ClearAddresses();

}

?>
