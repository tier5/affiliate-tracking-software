<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// -----------------------------------------------------------------------------------------------------
// DO NOT EDIT BELOW UNLESS YOU WANT TO ALTER THE ACTIONS TAKEN
// DURING THE RECURRING COMMISSIONS PROCESS
// -----------------------------------------------------------------------------------------------------

// CONNECT TO THE DATABASE @ MAKE SITE CONFIG SETTINGS AVAILABLE
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
$updated = $db->query("select id from idevaff_recurring");
$updated = number_format($updated->rowCount());
$removed = $db->query("select id from idevaff_recurring where pending = '1' and rec_now = '1'");
$removed = number_format($removed->rowCount());
$reset = $db->query("select id from idevaff_recurring where rec_now = '1'");
$reset = number_format($reset->rowCount());

// REMOVE 1 DAY FROM CURRENT RECURRING COMMISSIONS
// ----------------------------------------------------------------
$db->query("update idevaff_recurring set rec_now = rec_now -1");
// ----------------------------------------------------------------

// INSERT NEW COMMISSION IF DAYS ARE COUNTED DOWN TO ZERO
// ----------------------------------------------------------------
$a = $db->query("select * from idevaff_recurring where rec_now = '0'");
if ($a->rowCount()) {
while ($b = $a->fetch()) {
$f = $b['id'];
$af = $b['aff_id'];
$ap = $b['amount'];
$tr = $b['tracking'];
$op1 = $b['op1'];
$op2 = $b['op2'];
$op3 = $b['op3'];
$profile = $b['profile'];
$oamount = $b['oamount'];
$type = $b['type'];
$sub_id = $b['sub_id'];
$tid1 = $b['tid1'];
$tid2 = $b['tid2'];
$tid3 = $b['tid3'];
$tid4 = $b['tid4'];
$target_url = $b['target_url'];
$referring_url = $b['referring_url'];
$ip = null;

if (!$profile) { $profile = 9000; }

//$db->query("insert into idevaff_sales (id, payment, code, tracking, recurring, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url) values ('$af', '$ap', '$commission_time', '$tr', '$f', '$op1', '$op2', '$op3', '$oamount', '$type', '$profile', '$sub_id', '$tid1', '$tid2', '$tid3', '$tid4', '$target_url', '$referring_url')");
$st = $db->prepare("insert into idevaff_sales (id, payment, code, tracking, recurring, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($af,$ap,$commission_time,$tr,$f,$op1,$op2,$op3,$oamount,$type,$profile,$sub_id,$tid1,$tid2,$tid3,$tid4,$target_url,$referring_url));
if ($sale_notify == 1) { include ($path . "/templates/email/admin.recurring_commission.php"); }

// --------------------------------------------
// START POST BACK TRIGGER
// --------------------------------------------
/*
$check_for_postback = $db->prepare("select id from idevaff_postback where affiliate_id = ?");
$check_for_postback->execute(array($af));
if ($check_for_postback->rowCount()) {
$postback_affiliate_id = $af;
$postback_order_number = $tr;
$postback_commission = number_format($ap,$decimal_symbols);
$postback_sale_amount = number_format($oamount,$decimal_symbols);
$postback_sub_id = $sub_id;
$postback_tid1 = $tid1;
$postback_tid2 = $tid2;
$postback_tid3 = $tid3;
$postback_tid4 = $tid4;
$postback_currency = $currency;
include ($path . "/includes/postback.php");
}
*/
// --------------------------------------------
// END POST BACK TRIGGER
// --------------------------------------------

// REMOVE COMMISSIONS THAT ARE PENDING REMOVAL
// ----------------------------------------------------------------
$db->query("delete from idevaff_recurring where pending = '1' and rec_now = '0'");
// ----------------------------------------------------------------

// RESET COMMISSIONS BACK TO ORIGINAL COUNT DOWN DAYS FOR REPEAT PROCESSING
// ----------------------------------------------------------------
//$db->query("update idevaff_recurring set rec_now = rec_stamp where id = '$f'");
$st = $db->prepare("update idevaff_recurring set rec_now = rec_stamp where id = ?");
$st->execute(array($f));
// ----------------------------------------------------------------

} }

$email_success = true;

} else {

$email_success = null;

}

// EMAIL DAILY ADMIN REPORT
// ----------------------------------------------------------------
if ($admin_notify_api_recurring == 1) { include ($path . "/templates/email/admin.api_report_recurring.php"); }

?>