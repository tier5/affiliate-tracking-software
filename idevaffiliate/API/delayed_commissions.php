<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// DO NOT EDIT BELOW UNLESS YOU WANT TO ALTER THE ACTIONS TAKEN DURING THE RECURRING COMMISSIONS PROCESS
// -----------------------------------------------------------------------------------------------------

// CONNECT TO THE DATABASE & MAKE SITE CONFIG SETTINGS AVAILABLE
// ----------------------------------------------------------------
require_once("../API/config.php");

// QUERY THE DATABASE FOR SECRET KEY
// ----------------------------------------------------------------
$s_key = $db->query("select secret from idevaff_config");
$s_key = $s_key->fetch();
$s_key = $s_key['secret'];

// CHECK VALID SECRET KEY IS PRESENT AND VALID
// - The variable is already sanitized.
// - The variable is already validated through _GET, or _POST.
// ------------------------------------------------------------------------------

$secret = check_type_api('secret');
if ($secret == $s_key) {

// QUERY THE DATABASE FOR DATA
// ----------------------------------------------------------------
$total_delayed = $db->query("select id from idevaff_sales where delay > '1' and payment > '0' and approved = '0'");
$total_delayed = number_format($total_delayed->rowCount());
$processing = $db->query("select id from idevaff_sales where delay = '1' and payment > '0'");
$processing = number_format($processing->rowCount());

$date=(date ("Y-m-d"));
$time=(date ("H:i:s"));

// PROCESS COMMISSION IF DAYS ARE COUNTED DOWN TO ZERO
// ----------------------------------------------------------------
$a = $db->query("select * from idevaff_sales where delay > '0' and payment > '0'");
if ($a->rowCount()) {
while ($qry = $a->fetch()) {

$uid = $qry['record'];
$id = $qry['id'];
$idev_id_override = $qry['id']; // for overrides
$payment = $qry['payment'];
$tracking_code = $qry['tracking'];
$sales_code = $qry['code'];
$recstatus = $qry['recurring'];
$ov1 = $qry['op1'];
$ov2 = $qry['op2'];
$ov3 = $qry['op3'];
if (!$ov1) { $ov1 = null; }
if (!$ov2) { $ov2 = null; }
if (!$ov3) { $ov3 = null; }
$profile = $qry['profile'];
$type = $qry['type'];
$ip = $qry['ip'];
$amount = $qry['amount'];
$delay = $qry['delay'];
$sub_id = $qry['sub_id'];
$tid1 = $qry['tid1'];
$tid2 = $qry['tid2'];
$tid3 = $qry['tid3'];
$tid4 = $qry['tid4'];
$target_url = $qry['target_url'];
$referring_url = $qry['referring_url'];
$currency_to_write = $qry['currency'];
$converted_amount = $qry['converted_amount'];

if ($delay_action == 1) {

$db->query("update idevaff_sales set approved = '1' where delay = '1'");
if ($rewards == 1) { if (($rew_app == 1) || ($rew_app == 3)) { $update_account_process = $id; include("$path/includes/process_rewards.php"); } }

if ($delay == 1) {

if ($sale_notify_affiliate == 1) {
$email = 'top'; $payoute = $payment;
include($path . "/templates/email/affiliate.new_commission.php");
}

// GET GEO LOCATION
include_once ($path . "/includes/geo.php");

//$idev_tier_1 = $db->query("select parent from idevaff_tiers where child = '$id' order by id");
//$idev_tier_1 = $idev_tier_1->fetch(); 
//$texist = $idev_tier_1['parent'];
$idev_tier_1_st = $db->prepare("select parent from idevaff_tiers where child = ? order by id");
$idev_tier_1_st->execute(array($id));
$idev_tier_1 = $idev_tier_1_st->fetch();
$texist = $idev_tier_1['parent'];

if ($texist > 0) {
$tiernumber = $texist;
$idev_ordernum = $tracking_code;
$avar = $amount;
$r_url = $referring_url;
$idev = $id;
$ip_addr = $ip;
$commission_time = $sales_code;
 } else {
$tiernumber = 0; }
$payout = $payment;
if ($tier_numbers > 0) { include ("../includes/tiers.php"); }
// include overrides processing
$commission_time = $sales_code;
include ("../includes/overrides.php");

// --------------------------------------------
// START POST BACK TRIGGER
// --------------------------------------------
/*
$check_for_postback = $db->prepare("select id from idevaff_postback where affiliate_id = ?");
$check_for_postback->execute(array($id));
if ($check_for_postback->rowCount()) {
$postback_affiliate_id = $id;
$postback_order_number = $tracking_code;
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

}

} else {

$db->query("update idevaff_sales set approved = '0' where delay = '1'"); }

} }

// REMOVE 1 DAY FROM CURRENT DELAYED COMMISSIONS
// ----------------------------------------------------------------
$db->query("update idevaff_sales set delay = delay -1 where delay > 0 and payment > 0");
// ----------------------------------------------------------------


$email_success = true;

} else {

$email_success = null;

}

// EMAIL DAILY ADMIN REPORT
// ----------------------------------------------------------------
if ($admin_notify_api_delayed == 1) { include ($path . "/templates/email/admin.api_report_delayed.php"); }

?>
