<?PHP
if (!defined('admin_includes')) { die(); }
include("session.check.php");

$st = $db->prepare("update idevaff_sales set payment = ?, approved = '1' where record = ?");
$st->execute(array($_POST['commission_primary'],$_POST['id']));

$affiliate_lookup = $_POST['acct'];
$payout = $_POST['paytop'];
$sub_id = $_POST['sub_id'];
$tvar = $_POST['tracking'];

if ($aff_lock == 1) { $unlock = " order by id desc"; } else { $unlock = ""; }
$checkip	= $db->prepare("select src1, src2 from idevaff_iptracking where ip = ? {$unlock}");
$checkip->execute(array($_POST['ip']));
$ipdata=@$checkip->fetch();
$src1=$ipdata['src1'];
$src2=$ipdata['src2'];
if (($src1) && ($src2)) {
if ($src1 == 1) { $table = "banners"; $col = "number"; }
if ($src1 == 2) { $table = "ads"; $col = "id"; }
if ($src1 == 3) { $table = "links"; $col = "id"; }
if ($src1 == 4) { $table = "htmlads"; $col = "id"; }
if ($src1 == 5) { $table = "email_templates"; $col = "id"; }
if ($src1 == 6) { $table = "peels"; $col = "number"; }
if ($src1 == 7) { $table = "lightboxes"; $col = "number"; }
$st = $db->prepare("update idevaff_$table set conv = conv+1 where $col = ?"); 
$st->execute(array($src2));
}
if ($_POST['type'] == 3) { $st = $db->prepare("update idevaff_affiliates set conv = conv+1 where id = ?"); 
$st->execute(array($_POST['acct']));
}

if ($rewards == 1) {
$afftype = $_POST['afftype'];
if (($rew_app == 1) && ($afftype == 1)) { $process = 1; }
if (($rew_app == 1) && ($afftype == 2)) { $process = 1; }
if (($rew_app == 2) && ($afftype == 3)) { $process = 1; }
if ($rew_app == 3) { $process = 1; }
if ($process == 1) {
$update_account_process = $_POST['acct'];
include("$path/includes/process_rewards.php");
} }

if ((isset($_POST['rec_option'])) && ($_POST['rec_option'] == 1)) {
$rec_perc = $_POST['commission_primary'] * $_POST['rec_perc'];
$st = $db->prepare("insert into idevaff_recurring (aff_id, aff_name, amount, tracking, rec_stamp, rec_now, code, op1, op2, op3, oamount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?, ?,?, ?, ?, ?, ?, ?,?, ?,?)");

$st->execute(array($_POST['acct'],$_POST['aff'],$rec_perc,$_POST['tracking'],$_POST['rec_days'],$_POST['rec_days'],$_POST['sendcode'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url']));
}

if ($sale_notify_affiliate == 1) {
$id = $_POST['acct'];
$email = 'top';
$getpaylevel = $db->prepare("select level from idevaff_affiliates where id = ?");
$getpaylevel->execute(array($id));
$paylevel=$getpaylevel->fetch();
$level=$paylevel['level'];
$payoute = $_POST['commission_primary'];
include("$path/templates/email/affiliate.new_commission.php"); }

// --------------------------------------------
// START POST BACK TRIGGER
// --------------------------------------------
/*
$check_for_postback = $db->prepare("select id from idevaff_postback where affiliate_id = ?");
$check_for_postback->execute(array($_POST['acct']));
if ($check_for_postback->rowCount()) {
$postback_affiliate_id = $_POST['acct'];
$postback_order_number = $_POST['tracking'];
$postback_commission = number_format($_POST['paytop'], $decimal_symbols);
$postback_sale_amount = number_format($_POST['oamount'], $decimal_symbols);
$postback_sub_id = $_POST['sub_id'];
$postback_tid1 = $_POST['tid1'];
$postback_tid2 = $_POST['tid2'];
$postback_tid3 = $_POST['tid3'];
$postback_tid4 = $_POST['tid4'];
$postback_currency = $currency;
include ($path . "/includes/postback.php");
$info_message = "<strong>Additional Information!</strong> This new commission has triggered the 3rd Party Pixel Postback / Callback for this affiliate.";
}
*/
// --------------------------------------------
// END POST BACK TRIGGER
// --------------------------------------------


// -- TIER COMMISSIONS

if ((isset($_POST['commission_tier_1'])) && ($_POST['commission_tier_1'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid1'],$_POST['commission_tier_1'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['aid1'];
$email = 'tier';
$payoute = $_POST['commission_tier_1'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_2'])) && ($_POST['commission_tier_2'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid2'],$_POST['commission_tier_2'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['aid2'];
$email = 'tier';
$payoute = $_POST['commission_tier_2'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_3'])) && ($_POST['commission_tier_3'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid3'],$_POST['commission_tier_3'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['aid3'];
$email = 'tier';
$payoute = $_POST['commission_tier_3'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_4'])) && ($_POST['commission_tier_4'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");
$st->execute(array($_POST['aid4'],$_POST['commission_tier_4'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['aid4'];
$email = 'tier';
$payoute = $_POST['commission_tier_4'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_5'])) && ($_POST['commission_tier_5'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid5'],$_POST['commission_tier_5'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['aid5'];
$email = 'tier';
$payoute = $_POST['commission_tier_5'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_6'])) && ($_POST['commission_tier_6'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid6'],$_POST['commission_tier_6'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['aid6'];
$email = 'tier';
$payoute = $_POST['commission_tier_6'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_7'])) && ($_POST['commission_tier_7'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid7'],$_POST['commission_tier_7'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['aid7'];
$email = 'tier';
$payoute = $_POST['commission_tier_7'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_8'])) && ($_POST['commission_tier_8'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid8'],$_POST['commission_tier_8'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['aid8'];
$email = 'tier';
$payoute = $_POST['commission_tier_8'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_9'])) && ($_POST['commission_tier_9'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?,?, ?,?, ?,?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$st->execute(array($_POST['aid9'],$_POST['commission_tier_9'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['aid9'];
$email = 'tier';
$payoute = $_POST['commission_tier_9'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['commission_tier_10'])) && ($_POST['commission_tier_10'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, top_tier_tag, approved, ip, code, tracking, tier_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, tracking_method) values (?, ?, '1', '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?)");
$st->execute(array($_POST['aid10'],$_POST['commission_tier_10'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['aid10'];
$email = 'tier';
$payoute = $_POST['commission_tier_10'];
include("$path/templates/email/affiliate.new_commission.php"); } }

// -- OVERRIDE COMMISSIONS

if ((isset($_POST['override1'])) && ($_POST['override1'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override1'],$_POST['commission_override_1'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override1'];
$email = 'override';
$payoute = $_POST['commission_override_1'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override2'])) && ($_POST['override2'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override2'],$_POST['commission_override_2'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));
if ($sale_notify_affiliate == 1) {
$id = $_POST['override2'];
$email = 'override';
$payoute = $_POST['commission_override_2'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override3'])) && ($_POST['override3'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override3'],$_POST['commission_override_3'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override3'];
$email = 'override';
$payoute = $_POST['commission_override_3'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override4'])) && ($_POST['override4'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override4'],$_POST['commission_override_4'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override4'];
$email = 'override';
$payoute = $_POST['commission_override_4'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override5'])) && ($_POST['override5'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override5'],$_POST['commission_override_5'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override5'];
$email = 'override';
$payoute = $_POST['commission_override_5'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override6'])) && ($_POST['override6'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override6'],$_POST['commission_override_6'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override6'];
$email = 'override';
$payoute = $_POST['commission_override_6'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override7'])) && ($_POST['override7'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override7'],$_POST['commission_override_7'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override7'];
$email = 'override';
$payoute = $_POST['commission_override_7'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override8'])) && ($_POST['override8'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override8'],$_POST['commission_override_8'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override8'];
$email = 'override';
$payoute = $_POST['commission_override_8'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override9'])) && ($_POST['override9'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override9'],$_POST['commission_override_9'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override9'];
$email = 'override';
$payoute = $_POST['commission_override_9'];
include("$path/templates/email/affiliate.new_commission.php"); } }

if ((isset($_POST['override10'])) && ($_POST['override10'] > 0)) {
$st = $db->prepare("insert into idevaff_sales (id, payment, approved, ip, code, tracking, override_id, rec_id, op1, op2, op3, amount, type, profile, sub_id, tid1, tid2, tid3, tid4, target_url, referring_url, currency, converted_amount, override, tracking_method) values (?, ?, '1', ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)");
$st->execute(array($_POST['override10'],$_POST['commission_override_10'],$_POST['ip'],$_POST['sendcode'],$_POST['tracking'],$_POST['acct'],$_POST['lastres'],$_POST['op1'],$_POST['op2'],$_POST['op3'],$_POST['oamount'],$_POST['type'],$_POST['profile'],$_POST['sub_id'],$_POST['tid1'],$_POST['tid2'],$_POST['tid3'],$_POST['tid4'],$_POST['target_url'],$_POST['referring_url'],$_POST['conversion_currency'],$_POST['converted_amount'],$_POST['tracking_method_used']));

if ($sale_notify_affiliate == 1) {
$id = $_POST['override10'];
$email = 'override';
$payoute = $_POST['commission_override_10'];
include("$path/templates/email/affiliate.new_commission.php"); } }
?>