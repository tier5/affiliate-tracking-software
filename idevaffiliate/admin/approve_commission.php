<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");

if (($staff_app_dec_commissions == 'off') && (!isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { header("Location: staff_notice.php"); exit(); }

$process = 0;

if (isset($_POST['commission_notes'])) {
$st=$db->prepare("update idevaff_sales set notes = ? where record = ?");
$st->execute(array($_POST['commission_notes'],$_POST['sale_id']));
$success_message = "<strong>Success!</strong> Commission notes updated.";
}

// CHANGE AFFILIATE ASSIGNMENT
if (isset($_POST['aff_change'])) {
if (is_numeric($_POST['affiliate_id_set'])) { $new_aff = $_POST['affiliate_id_set']; } else { $new_aff = '0'; }
if (is_numeric($_POST['rid'])) { $something = $_POST['rid']; } else { $something = '0'; }
if (($new_aff > '0') && ($something > '0')) {
$st=$db->prepare("update idevaff_sales set id = ? where record = ?");
$st->execute(array($new_aff,$something));
$success_message = "<strong>Success!</strong> Affiliate assignment change complete.";
} }

if (isset($_REQUEST['sale_id'])) { $calc_commissions = true; }

$leftSubActiveMenu = 'commissions';
require("templates/header.php");

$acct = $db->prepare("select * from idevaff_sales where record = ?"); 
$acct->execute(array($_REQUEST['sale_id']));
$qry = $acct->fetch();
$uid = $qry['record'];
$id = $qry['id'];
$date = date('m-d-Y', $qry['code']);
$time = date('h:i a', $qry['code']);
$commission = $qry['payment'];
$ip = $qry['ip'];
$tracking_code = $qry['tracking'];
$sales_code = $qry['code'];
$recstatus = $qry['recurring'];
$op1 = $qry['op1'];
$op2 = $qry['op2'];
$op3 = $qry['op3'];
$profile = $qry['profile'];
$profadd = $qry['profile'];
$tamount = $qry['amount'];
$type = $qry['type'];
$listref = $qry['referring_url'];
$converted_amount_raw = $qry['converted_amount'];
$conversion_currency = $qry['currency'];
$flag_country = strtolower($qry['geo']);
$flag = '';
if (file_exists($path.'/admin/images/geo_flags/'.$flag_country.'.png')) {
$flag = "<img src=\"images/geo_flags/".$flag_country.".png\" height=\"24\" width=\"24\" border=\"none;\" />"; }

$tracking_method = $qry['tracking_method'];

$conversion_time = $qry['conversion_time'];
$commission_notes = $qry['notes'];
if (empty($commission_notes) || trim($commission_notes)==='') {	$tabnotes_count = '0'; $tabnotes_color = "label-default"; } else { $tabnotes_count = '1'; $tabnotes_color = "label-primary"; }

$listsub_id = $qry['sub_id'];
$listtid1 = $qry['tid1'];
$listtid2 = $qry['tid2'];
$listtid3 = $qry['tid3'];
$listtid4 = $qry['tid4'];
$listtarget_url = $qry['target_url'];
$listreferring_url = $qry['referring_url'];

$alt_amt = $qry['alt_amt'];

$sub_id = $qry['sub_id'];
if (!$sub_id) { $sub_id = "N/A"; }

$tid_count = 0;
if ($qry['tid1'] != '') { $tid1 = $qry['tid1']; $tid_count = $tid_count + 1; } else { $tid1 = "N/A"; }
if ($qry['tid2'] != '') { $tid2 = $qry['tid2']; $tid_count = $tid_count + 1; } else { $tid2 = "N/A"; }
if ($qry['tid3'] != '') { $tid3 = $qry['tid3']; $tid_count = $tid_count + 1; } else { $tid3 = "N/A"; }
if ($qry['tid4'] != '') { $tid4 = $qry['tid4']; $tid_count = $tid_count + 1; } else { $tid4 = "N/A"; }
if ($tid_count > 0) { $tabtid_color = "label-danger"; } else { $tabtid_color = "label-default"; }

if ($qry['target_url']) { $target_url = "<a href=\"" . $qry['target_url'] . "\" target=\"_blank\">" . $qry['target_url'] . "</a>"; } else { $target_url = "Not available."; }

$optional_count = 0;
if ($op1 == '') { $op1 = "N/A"; } else { $optional_count = $optional_count + 1; }
if ($op2 == '') { $op2 = "N/A"; } else { $optional_count = $optional_count + 1; }
if ($op3 == '') { $op3 = "N/A"; } else { $optional_count = $optional_count + 1; }
if ($optional_count > 0) { $tabopt_color = "label-primary"; } else { $tabopt_color = "label-default"; }

$override_exists = false;

include("includes/form_lib_override.php");
include("includes/form_lib_tiers.php");

if ($tracking_code) { $track_code = $tracking_code; } else { $track_code = "N/A"; }

//$getaff=$db->query("select username, type, level from idevaff_affiliates where id = '$id'");
$getaff=$db->prepare("select username, type, level from idevaff_affiliates where id = ?");
$getaff->execute(array($id));
$aff1=$getaff->fetch();
$aff=$aff1['username'];
$afftype=$aff1['type'];
$afflev=$aff1['level'];

$getamts=$db->prepare("select amt, amt_alt from idevaff_paylevels where type = ? and level = ?");
$getamts->execute(array($afftype,$afflev));
$amtinfo=$getamts->fetch();
$amtinfo_normal=$amtinfo['amt'];
$amtinfo_alt=$amtinfo['amt_alt'];

if ($afftype == 1) {
$amttocalc2 = $amtinfo_normal * 100; $displayamount2 = $amttocalc2 . "% of sale amount.";
} elseif ($afftype == 2) {
$displayamount2 = number_format($amtinfo_normal,$decimal_symbols);
if($cur_sym_location == 1) { $displayamount2 = $cur_sym . $displayamount2; }
if($cur_sym_location == 2) { $displayamount2 = $displayamount2 . " " . $cur_sym; }
$displayamount2 = $displayamount2 . " " . $currency . " flat rate.";
} elseif  ($afftype == 3) {
$displayamount2 = number_format($amtinfo_normal,$decimal_symbols);
if($cur_sym_location == 1) { $displayamount2 = $cur_sym . $displayamount2; }
if($cur_sym_location == 2) { $displayamount2 = $displayamount2 . " " . $cur_sym; }
$displayamount2 = $displayamount2 . " " . $currency . " flat rate (PPC).";
}

if ((strpos($tracking_method,'Coupon Code: ') === false) && ($alt_amt == 1)) {
if ($afftype == 1) {
$amttocalc2 = $amtinfo_alt * 100; $displayamount_alt = $amttocalc2 . "% of sale amount.";
} elseif ($afftype == 2) {
$displayamount_alt = number_format($amtinfo_alt,$decimal_symbols);
if($cur_sym_location == 1) { $displayamount_alt = $cur_sym . $displayamount_alt; }
if($cur_sym_location == 2) { $displayamount_alt = $displayamount_alt . " " . $cur_sym; }
$displayamount_alt = $displayamount_alt . " " . $currency . " flat rate.";
} }

if (!$profile) { $profile = 9000; }
if ($profile == 72198) { $profile = 0; }

$profile_to_check = $profile;
if ($profile == 0) { $profile_to_check = 72198; }
$integration=$db->prepare("select * from idevaff_integration where type = ?");
$integration->execute(array($profile_to_check));
$iconfig=$integration->fetch();
$opvar1_cart = $iconfig['cart_var1'];
$use_op1 = $iconfig['use_var1'];
$opvar1_tag = $iconfig['tag_var1'];
$opvar2_cart = $iconfig['cart_var2'];
$use_op2 = $iconfig['use_var2'];
$opvar2_tag = $iconfig['tag_var2'];
$opvar3_cart = $iconfig['cart_var3'];
$use_op3 = $iconfig['use_var3'];
$opvar3_tag = $iconfig['tag_var3'];

$custom_count = $db->query("SELECT COUNT(*) FROM idevaff_form_fields_custom where display_record = '1'");
$custom_count = $custom_count->fetchColumn();
if ($custom_count > 0) { $tabcustom_color = "label-success"; } else { $tabcustom_color = "label-default"; }

$prependfield = null;
if ($cur_sym_location == 1) { $prependfield = $cur_sym; $appendfield = $currency; } elseif ($cur_sym_location == 2) { $appendfield = $cur_sym . " " . $currency; }

$profile_name = $db->prepare("select name from idevaff_carts where id = ?");
$profile_name->execute(array($profile));
$qry = $profile_name->fetch();
$tdisp=$qry['name'];

if (!isset($tdisp)) {
if ($profile == '0') { $tdisp = "Generic Tracking Pixel"; }
if ($profile == '44') { $tdisp = "Pay-Per-Lead Tracking Pixel"; }
}

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
	
	if ($dtF->diff($dtT)->format('%a') > 1) { $day_addon = "s"; } else { $day_addon = null; }
	if ($dtF->diff($dtT)->format('%h') > 1) { $hour_addon = "s"; } else { $hour_addon = null; }
	if ($dtF->diff($dtT)->format('%i') > 1) { $minu_addon = "s"; } else { $minu_addon = null; }
	if ($dtF->diff($dtT)->format('%s') > 1) { $seco_addon = "s"; } else { $seco_addon = null; }

	if ($dtF->diff($dtT)->format('%a') > 0) {
	return $dtF->diff($dtT)->format('%a day'.$day_addon).", ".$dtF->diff($dtT)->format('%h hour'.$hour_addon).", ".$dtF->diff($dtT)->format('%i minute'.$minu_addon)." and ".$dtF->diff($dtT)->format('%s second'.$seco_addon.'.');
	} elseif ($dtF->diff($dtT)->format('%h') > 0) {
	return $dtF->diff($dtT)->format('%h hour'.$hour_addon).", ".$dtF->diff($dtT)->format('%i minute'.$minu_addon)." and ".$dtF->diff($dtT)->format('%s second'.$seco_addon.'.');
	} elseif ($dtF->diff($dtT)->format('%i') > 0) {
	return $dtF->diff($dtT)->format('%i minute'.$minu_addon)." and ".$dtF->diff($dtT)->format('%s second'.$seco_addon.'.');
	} elseif ($dtF->diff($dtT)->format('%s') > 0) {
	return $dtF->diff($dtT)->format('%s second'.$seco_addon.'.');
	}
	
}

?>
<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Commissions</li>
<li class="current"> <a href="approve_commission.php?sale_id=<?PHP echo html_output($_REQUEST['sale_id']); ?>">Commission Record</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Commission Record</h3><span>Commission record ready for you to approve or decline.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">General Details</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Page URLs</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Optional Data <span class="label<?PHP if (isset($tabopt_color)) { echo " " . $tabopt_color; } ?>"><?PHP echo html_output($optional_count); ?></a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Tracking IDs <span class="label<?PHP if (isset($tabtid_color)) { echo " " . $tabtid_color; } ?>"><?PHP echo html_output($tid_count); ?></a></li>
<li <?php makeActiveTab(5);?>><a href="#tab_1_5" data-toggle="tab">Custom Fields <span class="label<?PHP if (isset($tabcustom_color)) { echo " " . $tabcustom_color; } ?>"><?PHP echo html_output($custom_count); ?></a></li>
<li <?php makeActiveTab(6);?>><a href="#tab_1_6" data-toggle="tab">Notes <span class="label<?PHP if (isset($tabnotes_color)) { echo " " . $tabnotes_color; } ?>"><?PHP echo html_output($tabnotes_count); ?></a></li>

</ul>

<div class="tab-content">

<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">

<?PHP
$totaltoapprove = (number_format($commission,$decimal_symbols));
if ($tamount == 0) { $disamount = "None"; } else {

$disamount = number_format($tamount,$decimal_symbols);
if($cur_sym_location == 1) { $disamount = $cur_sym . $disamount; }
if($cur_sym_location == 2) { $disamount = $disamount . " " . $cur_sym; }

 
if ($converted_amount_raw > 0) {
$temp_cur_sym=$db->prepare("select currency_symbol from idevaff_currency where currency_code = ?");
$temp_cur_sym->execute(array($conversion_currency));
$temp_cur_sym=$temp_cur_sym->fetch();
$temp_cur_sym=$temp_cur_sym['currency_symbol'];

$converted_amount=(number_format($converted_amount_raw, $decimal_symbols));
if($cur_sym_location == 1) { $converted_amount = $cur_sym . $converted_amount; }
if($cur_sym_location == 2) { $converted_amount = $converted_amount . " " . $cur_sym; }
$converted_amount = $converted_amount;

$disamount = $temp_cur_sym . $tamount . " " . $conversion_currency . " was converted to " . $converted_amount . " " . $currency;
} else {
$disamount = $disamount . " " . $currency; }
}
?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-money"></i> Order Details</h4></div>
<div class="widget-content">

<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td width="25%"><strong>Order Number</strong></td>
<td width="25%"><?PHP if (!$tracking_code) { echo "N/A"; } else { echo html_output($tracking_code); } ?></td>
<td width="25%"><strong>Sale Amount</strong></td>
<td width="25%"><?PHP if (!$disamount) { echo "N/A"; } else { echo html_output($disamount); } ?></td>
</tr>
<tr>
<td width="25%"><strong>Order Date</strong></td>
<td width="25%"><?PHP echo html_output($date); ?></td>
<td width="25%"><strong>Cart/Billing System</strong></td>
<td width="25%"><?PHP if (!isset($tdisp)) { echo "N/A"; } else { echo html_output($tdisp); } ?></td>
</tr>
<tr>
<!--<td width="25%"><strong>Sub-ID</strong></td>-->
<!--<td width="25%"><?PHP //echo html_output($sub_id); ?></td>-->
<td width="25%"><strong>Order Time</strong></td>
<td width="25%"><?PHP echo html_output($time); ?></td>
<td width="25%"><strong>Tracking Method</strong><span class="pull-right"><a data-toggle="modal" href="#tracking_method_info" class="btn btn-xs btn-info">info</a></span></td>
<td width="25%"><?PHP if ($tracking_method == '') { echo "N/A"; } else { echo html_output($tracking_method); } ?></td>
</tr>
<tr>
<td width="25%"><strong>Customer IP</strong></td>
<td width="25%"><?PHP if ($ip == '') { echo "N/A"; } else { echo html_output($ip); } ?><span class="pull-right" style="margin:0px; padding:0px;"><?PHP echo $flag; ?></span></td>
<td width="25%"><strong>Conversion Time</strong><span class="pull-right"><a data-toggle="modal" href="#conversion_time_info" class="btn btn-xs btn-info">info</a></span></td>
<td width="25%"><?PHP if ($conversion_time > 0) { echo secondsToTime($conversion_time); } else { echo "Data Not Available"; } ?></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

<div class="modal fade" id="tracking_method_info">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Tracking Method</h4>
</div>
<div class="modal-body">There are several methods and factors involved when creating a commission. The method used depends on the different overrides, API calls, coupon codes and priority settings you have selected. This information is for display purposes only.
<br /><br />
<table class="table valign table-striped">
<tbody>
<tr><td>N/A</td><td>Not available.</td></tr>
<tr><td>N/A - Manually Created</td><td>Not available. Commission was manually created.</td></tr>
<tr><td>IP Address</td><td>Standard IP tracking log was used for this commission.</td></tr>
<tr><td>Cookie</td><td>Standard cookie was used for this commission.</td></tr>
<tr><td>IP Address from Paypal</td><td>The Paypal IPN sent in the IP address for lookup.</td></tr>
<tr><td>IP Address - API Style</td><td>An IP address was sent in via API style call.</td></tr>
<tr><td>Affiliate ID Override</td><td>An affiliate ID was sent in, overriding all tracking logs.</td></tr>
<tr><td>Coupon Code</td><td>A coupon code was used - includes actual code used.</td></tr>
</tbody>
</table>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="conversion_time_info">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Conversion Time</h4>
</div>
<div class="modal-body">This is the amount of time that has passed since the customer clicked the affiliate link, to the time they made the purchase.
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-money"></i> Commission Details</h4></div>
<div class="widget-content">

<table class="table valign table-striped table-bordered table-highlight-head">
<thead>
<tr>
<td width="100%" colspan="3" style="background:#428bca; color:#f3f3f3;">Primary Commission</td>
</tr>
<tr>
<th style="width:35%;">Affiliate<span class="pull-right"><a href="account_details.php?id=<?PHP echo html_output($id); ?>"><button class="btn btn-info btn-xs">View Account</button></a></span></th>
<th style="width:30%;">Payout Level</th>
<th style="width:35%;">Commission Amount</th>
</tr>
</thead>
<tbody>
<tr>
<form class="form-horizontal row-border" method="post" action="approve_commission.php">
<td style="width:35%;"><select name="affiliate_id_set" class="form-control" onchange='this.form.submit()'>
<?PHP
$getnames = $db->query("select id, username from idevaff_affiliates order by id"); 
if ($getnames->rowCount()) {
while ($qry = $getnames->fetch()) {
$chid=$qry['id'];
$chuser=$qry['username'];
print "<option value='$chid'";
if ((isset($id)) && ($chid == $id)) { print ' selected'; }
print ">$chid - $chuser</option>\n"; } }
?></select>
</td>
<input type="hidden" name="aff_change" value="1">
<input type="hidden" name="sale_id" value="<?PHP echo $uid; ?>">
<input type="hidden" name="rid" value="<?PHP echo $uid; ?>">
</form>

<form class="form-horizontal row-border" method="post" action="commissions_pending.php">
<!--<td style="width:30%;">Level <?PHP //echo html_output($afflev); ?>: <?PHP //echo html_output($displayamount2); ?></td>-->

<?PHP
if (strpos($tracking_method,'Coupon Code: ') !== false) {
$code_extract = substr($tracking_method, 13);
$getccinfo = $db->prepare("select coupon_amount, coupon_type from idevaff_coupons where coupon_code = ?"); 
$getccinfo->execute(array($code_extract));
$qry = $getccinfo->fetch();
$coupon_amount = $qry['coupon_amount'];
$coupon_type = $qry['coupon_type'];

if ($coupon_type == '3') {
?>
<td style="width:30%;">Level <?PHP echo html_output($afflev); ?>: <?PHP echo html_output($displayamount2); ?></td>
<?PHP } else {

if ($coupon_type == '1') { $commission_display = $coupon_amount . "% of sale amount."; }
if ($coupon_type == '2') {
$display_payment = number_format($coupon_amount,$decimal_symbols);
if($cur_sym_location == 1) { $pdis = $cur_sym . $display_payment; }
if($cur_sym_location == 2) { $pdis = $display_payment . " " . $cur_sym; }
$commission_display = $pdis . " " . $currency;
$commission_display = $commission_display . " flat rate."; }
?>
<td style="width:30%;"><span style="text-decoration: line-through;">Level <?PHP echo html_output($afflev); ?>: <?PHP echo html_output($displayamount2); ?></span>
<br />
Coupon Code Amount: <?PHP echo $commission_display; ?>
</td>
<?PHP } } else { if ($alt_amt == 1) { ?>
<td style="width:30%;">Level <?PHP echo html_output($afflev); ?>: <span style="text-decoration: line-through;"><?PHP echo html_output($displayamount2); ?></span>
<br />
<span style="color:#CC0000;">Repeat/Recurring Amount:</span> <?PHP echo html_output($displayamount_alt); ?>
</td>
<?PHP } else {?>
<td style="width:30%;">Level <?PHP echo html_output($afflev); ?>: <?PHP echo html_output($displayamount2); ?></td>
<?PHP } } ?>



<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_primary" id="commission_primary" onblur="re_calc(this)" value="<?PHP echo number_format($commission,2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div>
</td>
</tr>
</tbody>
</table>

<?PHP
if (($print_overrides[1]) || ($print_overrides[2]) || ($print_overrides[3]) || ($print_overrides[4]) || ($print_overrides[5]) || ($print_overrides[6]) || ($print_overrides[7]) || ($print_overrides[8]) || ($print_overrides[9]) || ($print_overrides[10])) {
if (($opays[1] > .01) || ($opays[2] > .01) || ($opays[3] > .01) || ($opays[4] > .01) || ($opays[5] > .01) || ($opays[6] > .01) || ($opays[7] > .01) || ($opays[8] > .01) || ($opays[9] > .01) || ($opays[10] > .01)) {
?>
<input type="hidden" name="lastres" value="<?PHP echo $uid; ?>">

<table class="table valign table-striped table-bordered table-highlight-head">
<thead>
<tr>
<td width="100%" colspan="5" style="background:#5bc0de; color:#f3f3f3;">Override Commissions</td>
</tr>
<tr>
<th style="width:15%;">ID</th>
<th style="width:20%;">Affiliate</th>
<th style="width:30%;">Default Override Amount</th>
<th style="width:35%;">Override Commission Amount</th>
</tr>
</thead>
<tbody>
<?PHP
if(($print_overrides[1]) && ($opays[1] > .01)) {

//$get_o_name_1=$db->query("select username from idevaff_affiliates where id = $override_1");
$get_o_name_1=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_1->execute(array($override_1));
$get_o_name_1=$get_o_name_1->fetch();
$get_o_name_1=$get_o_name_1['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_1' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_1,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate.
        $dtype1 = $commission_amount;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_1); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_1); ?>"><b><?PHP echo html_output($get_o_name_1); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_1" id="commission_override_1" onblur="re_calc(this)" value="<?PHP echo number_format($opays[1],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override1" value="<?PHP echo $override_1; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_1" id="commission_override_1" onblur="re_calc(this)" value="<?PHP echo number_format($opays[1],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[2]) && ($opays[2] > .01)) {

//$get_o_name_2=$db->query("select username from idevaff_affiliates where id = $override_2");
$get_o_name_2=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_2->execute(array($override_2));
$get_o_name_2=$get_o_name_2->fetch();
$get_o_name_2=$get_o_name_2['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_2' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_2,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
	
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_2); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_2); ?>"><b><?PHP echo html_output($get_o_name_2); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_2" id="commission_override_2" onblur="re_calc(this)" value="<?PHP echo number_format($opays[2],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override2" value="<?PHP echo $override_2; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_2" id="commission_override_2" onblur="re_calc(this)" value="<?PHP echo number_format($opays[2],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[3]) && ($opays[3] > .01)) {

//$get_o_name_3=$db->query("select username from idevaff_affiliates where id = $override_3");
$get_o_name_3=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_3->execute(array($override_3));
$get_o_name_3=$get_o_name_3->fetch();
$get_o_name_3=$get_o_name_3['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_3' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_3,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_3); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_3); ?>"><b><?PHP echo html_output($get_o_name_3); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_3" id="commission_override_3" onblur="re_calc(this)" value="<?PHP echo number_format($opays[3],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override3" value="<?PHP echo $override_3; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_3" id="commission_override_3" onblur="re_calc(this)" value="<?PHP echo number_format($opays[3],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[4]) && ($opays[4] > .01)) {

//$get_o_name_4=$db->query("select username from idevaff_affiliates where id = $override_4");
$get_o_name_4=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_4->execute(array($override_4));
$get_o_name_4=$get_o_name_4->fetch();
$get_o_name_4=$get_o_name_4['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_4' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_4,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

	if ($commission_type == 1) { // Calc from main commission.
		$dtype1 = $commission_amount . "%";
		$dtype2 = "of primary commission.";
	} elseif ($commission_type == 2) { // Flat rate..
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
		if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_4); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_4); ?>"><b><?PHP echo html_output($get_o_name_4); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_4" id="commission_override_4" onblur="re_calc(this)" value="<?PHP echo number_format($opays[4],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override4" value="<?PHP echo $override_4; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_4" id="commission_override_4" onblur="re_calc(this)" value="<?PHP echo number_format($opays[4],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[5]) && ($opays[5] > .01)) {

//$get_o_name_5=$db->query("select username from idevaff_affiliates where id = $override_5");
$get_o_name_5=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_5->execute(array($override_5));
$get_o_name_5=$get_o_name_5->fetch();
$get_o_name_5=$get_o_name_5['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_5' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_5,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_5); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_5); ?>"><b><?PHP echo html_output($get_o_name_5); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_5" id="commission_override_5" onblur="re_calc(this)" value="<?PHP echo number_format($opays[5],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override5" value="<?PHP echo $override_5; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_5" id="commission_override_5" onblur="re_calc(this)" value="<?PHP echo number_format($opays[5],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[6]) && ($opays[6] > .01)) {

//$get_o_name_6=$db->query("select username from idevaff_affiliates where id = $override_6");
$get_o_name_6=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_6->execute(array($override_6));
$get_o_name_6=$get_o_name_6->fetch();
$get_o_name_6=$get_o_name_6['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_6' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_6,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_6); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_6); ?>"><b><?PHP echo html_output($get_o_name_6); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_6" id="commission_override_6" onblur="re_calc(this)" value="<?PHP echo number_format($opays[6],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override6" value="<?PHP echo $override_6; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_6" id="commission_override_6" onblur="re_calc(this)" value="<?PHP echo number_format($opays[6],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[7]) && ($opays[7] > .01)) {

//$get_o_name_7=$db->query("select username from idevaff_affiliates where id = $override_7");
$get_o_name_7=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_7->execute(array($override_7));
$get_o_name_7=$get_o_name_7->fetch();
$get_o_name_7=$get_o_name_7['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_7' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_7,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_7); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_7); ?>"><b><?PHP echo html_output($get_o_name_7); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_7" id="commission_override_7" onblur="re_calc(this)" value="<?PHP echo number_format($opays[7],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override7" value="<?PHP echo $override_7; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_7" id="commission_override_7" onblur="re_calc(this)" value="<?PHP echo number_format($opays[7],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[8]) && ($opays[8] > .01)) {

//$get_o_name_8=$db->query("select username from idevaff_affiliates where id = $override_8");
$get_o_name_8=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_8->execute(array($override_8));
$get_o_name_8=$get_o_name_8->fetch();
$get_o_name_8=$get_o_name_8['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_8' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_8,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_8); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_8); ?>"><b><?PHP echo html_output($get_o_name_8); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_8" id="commission_override_8" onblur="re_calc(this)" value="<?PHP echo number_format($opays[8],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override8" value="<?PHP echo $override_8; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_8" id="commission_override_8" onblur="re_calc(this)" value="<?PHP echo number_format($opays[8],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[9]) && ($opays[9] > .01)) {

//$get_o_name_9=$db->query("select username from idevaff_affiliates where id = $override_9");
$get_o_name_9=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_9->execute(array($override_9));
$get_o_name_9=$get_o_name_9->fetch();
$get_o_name_9=$get_o_name_9['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_9' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_9,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_9); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_9); ?>"><b><?PHP echo html_output($get_o_name_9); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_9" id="commission_override_9" onblur="re_calc(this)" value="<?PHP echo number_format($opays[9],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override9" value="<?PHP echo $override_9; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_9" id="commission_override_9" onblur="re_calc(this)" value="<?PHP echo number_format($opays[9],2,'.',''); ?>" />
<?PHP } ?>

<?PHP
if(($print_overrides[10]) && ($opays[10] > .01)) {

//$get_o_name_10=$db->query("select username from idevaff_affiliates where id = $override_10");
$get_o_name_10=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_o_name_10->execute(array($override_10));
$get_o_name_10=$get_o_name_10->fetch();
$get_o_name_10=$get_o_name_10['username'];

//$get_payout_details = $db->query("select commission_amount, commission_type from idevaff_commission_override where id = '$override_10' and slave = '$id'");
$get_payout_details = $db->prepare("select commission_amount, commission_type from idevaff_commission_override where id = ? and slave = ?");
$get_payout_details->execute(array($override_10,$id));
$get_payout_details = $get_payout_details->fetch();
$commission_amount = $get_payout_details['commission_amount'];
$commission_type = $get_payout_details['commission_type'];

if ($commission_type == 1) { // Calc from main commission.
        $dtype1 = $commission_amount . "%";
        $dtype2 = "of primary commission.";
} elseif ($commission_type == 2) { // Flat rate..
        if($cur_sym_location == 1) { $dtype1 = $cur_sym . $commission_amount; }
        if($cur_sym_location == 2) { $dtype1 = $commission_amount . " " . $cur_sym; }
        $dtype1 = $dtype1 . " " . $currency;
        $dtype2 = "flat rate.";
}
?>

<tr>
<td style="width:15%;"><?PHP echo html_output($override_10); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($override_10); ?>"><b><?PHP echo html_output($get_o_name_10); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_override_10" id="commission_override_10" onblur="re_calc(this)" value="<?PHP echo number_format($opays[10],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="override10" value="<?PHP echo $override_10; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_override_10" id="commission_override_10" onblur="re_calc(this)" value="<?PHP echo number_format($opays[10],2,'.',''); ?>" />
<?PHP } ?>


</tbody>
</table>
<?PHP } else { ?>
<input type="hidden" name="commission_override_1" id="commission_override_1" onblur="re_calc(this)" value="<?PHP echo number_format($opays[1],2,'.',''); ?>" />
<input type="hidden" name="commission_override_2" id="commission_override_2" onblur="re_calc(this)" value="<?PHP echo number_format($opays[2],2,'.',''); ?>" />
<input type="hidden" name="commission_override_3" id="commission_override_3" onblur="re_calc(this)" value="<?PHP echo number_format($opays[3],2,'.',''); ?>" />
<input type="hidden" name="commission_override_4" id="commission_override_4" onblur="re_calc(this)" value="<?PHP echo number_format($opays[4],2,'.',''); ?>" />
<input type="hidden" name="commission_override_5" id="commission_override_5" onblur="re_calc(this)" value="<?PHP echo number_format($opays[5],2,'.',''); ?>" />
<input type="hidden" name="commission_override_6" id="commission_override_6" onblur="re_calc(this)" value="<?PHP echo number_format($opays[6],2,'.',''); ?>" />
<input type="hidden" name="commission_override_7" id="commission_override_7" onblur="re_calc(this)" value="<?PHP echo number_format($opays[7],2,'.',''); ?>" />
<input type="hidden" name="commission_override_8" id="commission_override_8" onblur="re_calc(this)" value="<?PHP echo number_format($opays[8],2,'.',''); ?>" />
<input type="hidden" name="commission_override_9" id="commission_override_9" onblur="re_calc(this)" value="<?PHP echo number_format($opays[9],2,'.',''); ?>" />
<input type="hidden" name="commission_override_10" id="commission_override_10" onblur="re_calc(this)" value="<?PHP echo number_format($opays[10],2,'.',''); ?>" />
<?PHP } } ?>

<?PHP
if (($print_tiers[1]) || ($print_tiers[2]) || ($print_tiers[3]) || ($print_tiers[4]) || ($print_tiers[5]) || ($print_tiers[6]) || ($print_tiers[7]) || ($print_tiers[8]) || ($print_tiers[9]) || ($print_tiers[10])) {
if (($tpays[1] > .01) || ($tpays[2] > .01) || ($tpays[3] > .01) || ($tpays[4] > .01) || ($tpays[5] > .01) || ($tpays[6] > .01) || ($tpays[7] > .01) || ($tpays[8] > .01) || ($tpays[9] > .01) || ($tpays[10] > .01)) {
?>
<input type="hidden" name="adate" value="<?PHP echo $date; ?>">
<input type="hidden" name="atime" value="<?PHP echo $time; ?>">
<input type="hidden" name="amount" value="<?PHP echo $tamount; ?>">
<input type="hidden" name="lastres" value="<?PHP echo $uid; ?>">

<table class="table valign table-striped table-bordered table-highlight-head">
<thead>
<tr>
<td width="100%" colspan="5" style="background:#5bc0de; color:#f3f3f3;">Tier Commissions</td>
</tr>
<tr>
<th style="width:5%;">Tier</th>
<th style="width:10%;">ID</th>
<th style="width:20%;">Affiliate</th>
<th style="width:30%;">Default Tier Amount</th>
<th style="width:35%;">Tier Commission Amount</th>
</tr>
</thead>
<tbody>



<?PHP
if(($print_tiers[1]) && ($tpays[1] > .01)) {
//$get_tier_name_1=$db->query("select username from idevaff_affiliates where id = $idev_tier_1");
$get_tier_name_1=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_1->execute(array($idev_tier_1));
$get_tier_name_1=$get_tier_name_1->fetch();
$tier_name_1=$get_tier_name_1['username'];

	$get_payout_type_d = $db->query("select level_1_amount, level_1_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_1_amount_d = $get_payout_type_d['level_1_amount'];
	$tier_1_type_d = $get_payout_type_d['level_1_type'];

	if ($tier_1_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_1_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_1_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_1_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_1_type_d == 4) { // Flat rate.
		$dtype1 = $tier_1_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_1_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_1_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	

?>

<tr>
<td style="width:5%;">1</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_1); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_1); ?>"><b><?PHP echo html_output($tier_name_1); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_1" id="commission_tier_1" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[1],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid1" value="<?PHP echo $idev_tier_1; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_1" id="commission_tier_1" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[1],2,'.',''); ?>" />
<?PHP }


if(($print_tiers[2]) && ($tpays[2] > .01)) {
//$get_tier_name_2=$db->query("select username from idevaff_affiliates where id = $idev_tier_2");
$get_tier_name_2=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_2->execute(array($idev_tier_2));
$get_tier_name_2=$get_tier_name_2->fetch();
$tier_name_2=$get_tier_name_2['username'];

	$get_payout_type_d = $db->query("select level_2_amount, level_2_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_2_amount_d = $get_payout_type_d['level_2_amount'];
	$tier_2_type_d = $get_payout_type_d['level_2_type'];

	if ($tier_2_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_2_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_2_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_2_amount_d . "%";
		$dtype2 = "of Tier 1 commission.";
	} elseif ($tier_2_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_2_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_2_type_d == 4) { // Flat rate.
		$dtype1 = $tier_2_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_2_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_2_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">2</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_2); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_2); ?>"><b><?PHP echo html_output($tier_name_2); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_2" id="commission_tier_2" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[2],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid2" value="<?PHP echo $idev_tier_2; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_2" id="commission_tier_2" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[2],2,'.',''); ?>" />
<?PHP } if(($print_tiers[3]) && ($tpays[3] > .01)) {
//$get_tier_name_3=$db->query("select username from idevaff_affiliates where id = $idev_tier_3");
$get_tier_name_3=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_3->execute(array($idev_tier_3));
$get_tier_name_3=$get_tier_name_3->fetch();
$tier_name_3=$get_tier_name_3['username'];

	$get_payout_type_d = $db->query("select level_3_amount, level_3_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_3_amount_d = $get_payout_type_d['level_3_amount'];
	$tier_3_type_d = $get_payout_type_d['level_3_type'];

	if ($tier_3_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_3_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_3_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_3_amount_d . "%";
		$dtype2 = "of Tier 2 commission.";
	} elseif ($tier_3_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_3_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_3_type_d == 4) { // Flat rate.
		$dtype1 = $tier_3_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_3_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_3_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">3</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_3); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_3); ?>"><b><?PHP echo html_output($tier_name_3); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_3" id="commission_tier_3" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[3],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid3" value="<?PHP echo $idev_tier_3; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_3" id="commission_tier_3" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[3],2,'.',''); ?>" />
<?PHP } if(($print_tiers[4]) && ($tpays[4] > .01)) {
//$get_tier_name_4=$db->query("select username from idevaff_affiliates where id = $idev_tier_4");
$get_tier_name_4=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_4->execute(array($idev_tier_4));
$get_tier_name_4=$get_tier_name_4->fetch();
$tier_name_4=$get_tier_name_4['username'];

	$get_payout_type_d = $db->query("select level_4_amount, level_4_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_4_amount_d = $get_payout_type_d['level_4_amount'];
	$tier_4_type_d = $get_payout_type_d['level_4_type'];

	if ($tier_4_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_4_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_4_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_4_amount_d . "%";
		$dtype2 = "of Tier 3 commission.";
	} elseif ($tier_4_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_4_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_4_type_d == 4) { // Flat rate.
		$dtype1 = $tier_4_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_4_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_4_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">4</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_4); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_4); ?>"><b><?PHP echo html_output($tier_name_4); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_4" id="commission_tier_4" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[4],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid4" value="<?PHP echo $idev_tier_4; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_4" id="commission_tier_4" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[4],2,'.',''); ?>" />
<?PHP } if(($print_tiers[5]) && ($tpays[5] > .01)) {
//$get_tier_name_5=$db->query("select username from idevaff_affiliates where id = $idev_tier_5");
$get_tier_name_5=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_5->execute(array($idev_tier_5));
$get_tier_name_5=$get_tier_name_5->fetch();
$tier_name_5=$get_tier_name_5['username'];

	$get_payout_type_d = $db->query("select level_5_amount, level_5_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_5_amount_d = $get_payout_type_d['level_5_amount'];
	$tier_5_type_d = $get_payout_type_d['level_5_type'];

	if ($tier_5_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_5_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_5_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_5_amount_d . "%";
		$dtype2 = "of Tier 4 commission.";
	} elseif ($tier_5_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_5_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_5_type_d == 4) { // Flat rate.
		$dtype1 = $tier_5_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_5_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_5_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
?>

<tr>
<td style="width:5%;">5</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_5); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_5); ?>"><b><?PHP echo html_output($tier_name_5); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_5" id="commission_tier_5" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[5],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid5" value="<?PHP echo $idev_tier_5; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_5" id="commission_tier_5" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[5],2,'.',''); ?>" />
<?PHP } if(($print_tiers[6]) && ($tpays[6] > .01)) {
//$get_tier_name_6=$db->query("select username from idevaff_affiliates where id = $idev_tier_6");
$get_tier_name_6=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_6->execute(array($idev_tier_6));
$get_tier_name_6=$get_tier_name_6->fetch();
$tier_name_6=$get_tier_name_6['username'];

	$get_payout_type_d = $db->query("select level_6_amount, level_6_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_6_amount_d = $get_payout_type_d['level_6_amount'];
	$tier_6_type_d = $get_payout_type_d['level_6_type'];

	if ($tier_6_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_6_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_6_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_6_amount_d . "%";
		$dtype2 = "of Tier 5 commission.";
	} elseif ($tier_6_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_6_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_6_type_d == 4) { // Flat rate.
		$dtype1 = $tier_6_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_6_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_6_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">6</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_6); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_6); ?>"><b><?PHP echo html_output($tier_name_6); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_6" id="commission_tier_6" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[6],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid6" value="<?PHP echo $idev_tier_6; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_6" id="commission_tier_6" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[7],2,'.',''); ?>" />
<?PHP } if(($print_tiers[7]) && ($tpays[7] > .01)) {
//$get_tier_name_7=$db->query("select username from idevaff_affiliates where id = $idev_tier_7");
$get_tier_name_7=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_7->execute(array($idev_tier_7));
$get_tier_name_7=$get_tier_name_7->fetch();
$tier_name_7=$get_tier_name_7['username'];

	$get_payout_type_d = $db->query("select level_7_amount, level_7_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_7_amount_d = $get_payout_type_d['level_7_amount'];
	$tier_7_type_d = $get_payout_type_d['level_7_type'];

	if ($tier_7_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_7_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_7_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_7_amount_d . "%";
		$dtype2 = "of Tier 6 commission.";
	} elseif ($tier_7_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_7_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_7_type_d == 4) { // Flat rate.
		$dtype1 = $tier_7_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_7_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_7_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">7</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_7); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_7); ?>"><b><?PHP echo html_output($tier_name_7); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_7" id="commission_tier_7" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[7],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid7" value="<?PHP echo $idev_tier_7; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_7" id="commission_tier_7" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[8],2,'.',''); ?>" />
<?PHP } if(($print_tiers[8]) && ($tpays[8] > .01)) {
//$get_tier_name_8=$db->query("select username from idevaff_affiliates where id = $idev_tier_8");
$get_tier_name_8=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_8->execute(array($idev_tier_8));
$get_tier_name_8=$get_tier_name_8->fetch();
$tier_name_8=$get_tier_name_8['username'];

	$get_payout_type_d = $db->query("select level_8_amount, level_8_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_8_amount_d = $get_payout_type_d['level_8_amount'];
	$tier_8_type_d = $get_payout_type_d['level_8_type'];

	if ($tier_8_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_8_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_8_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_8_amount_d . "%";
		$dtype2 = "of Tier 7 commission.";
	} elseif ($tier_8_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_8_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_8_type_d == 4) { // Flat rate.
		$dtype1 = $tier_8_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_8_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_8_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">8</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_8); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_8); ?>"><b><?PHP echo html_output($tier_name_8); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_8" id="commission_tier_8" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[8],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid8" value="<?PHP echo $idev_tier_8; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_8" id="commission_tier_8" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[8],2,'.',''); ?>" />
<?PHP } if(($print_tiers[9]) && ($tpays[9] > .01)) {
//$get_tier_name_9=$db->query("select username from idevaff_affiliates where id = $idev_tier_9");
$get_tier_name_9=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_9->execute(array($idev_tier_9));
$get_tier_name_9=$get_tier_name_9->fetch();
$tier_name_9=$get_tier_name_9['username'];

	$get_payout_type_d = $db->query("select level_9_amount, level_9_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_9_amount_d = $get_payout_type_d['level_9_amount'];
	$tier_9_type_d = $get_payout_type_d['level_9_type'];

	if ($tier_9_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_9_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_9_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_9_amount_d . "%";
		$dtype2 = "of Tier 8 commission.";
	} elseif ($tier_9_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_9_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_9_type_d == 4) { // Flat rate.
		$dtype1 = $tier_9_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_9_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_9_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">9</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_9); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_9); ?>"><b><?PHP echo html_output($tier_name_9); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_9" id="commission_tier_9" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[9],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid9" value="<?PHP echo $idev_tier_9; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_9" id="commission_tier_9" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[9],2,'.',''); ?>" />
<?PHP } if(($print_tiers[10]) && ($tpays[10] > .01)) {
//$get_tier_name_10=$db->query("select username from idevaff_affiliates where id = $idev_tier_10");
$get_tier_name_10=$db->prepare("select username from idevaff_affiliates where id = ?");
$get_tier_name_10->execute(array($idev_tier_10));
$get_tier_name_10=$get_tier_name_10->fetch();
$tier_name_10=$get_tier_name_10['username'];

	$get_payout_type_d = $db->query("select level_10_amount, level_10_type from idevaff_tier_settings");
	$get_payout_type_d = $get_payout_type_d->fetch();
	$tier_10_amount_d = $get_payout_type_d['level_10_amount'];
	$tier_10_type_d = $get_payout_type_d['level_10_type'];

	if ($tier_10_type_d == 1) { // Calc from main commission.
		$dtype1 = $tier_10_amount_d . "%";
		$dtype2 = "of primary commission.";
	} elseif ($tier_10_type_d == 2) { // Calc from upper commission amount.
		$dtype1 = $tier_10_amount_d . "%";
		$dtype2 = "of Tier 9 commission.";
	} elseif ($tier_10_type_d == 3) { // Calc from sale amount.
		$dtype1 = $tier_10_amount_d . "%";
		$dtype2 = "of sale amount.";
	} elseif ($tier_10_type_d == 4) { // Flat rate.
		$dtype1 = $tier_10_amount_d;
		if($cur_sym_location == 1) { $dtype1 = $cur_sym . $tier_10_amount_d; }
		if($cur_sym_location == 2) { $dtype1 = $tier_10_amount_d . " " . $cur_sym; }
		$dtype1 = $dtype1 . " " . $currency;
		$dtype2 = "flat rate.";
	}
	
?>

<tr>
<td style="width:5%;">10</td>
<td style="width:10%;"><?PHP echo html_output($idev_tier_10); ?></td>
<td style="width:20%;"><a href="account_details.php?id=<?PHP echo html_output($idev_tier_10); ?>"><b><?PHP echo html_output($tier_name_10); ?></b></a></td>
<td style="width:30%;"><?PHP echo html_output($dtype1) . " " . html_output($dtype2); ?></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_tier_10" id="commission_tier_10" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[10],2,'.',''); ?>" class="form-control" placeholder=".input-group">
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<input type="hidden" name="aid10" value="<?PHP echo $idev_tier_10; ?>">
<?PHP } else { ?>
<input type="hidden" name="commission_tier_10" id="commission_tier_10" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[10],2,'.',''); ?>" />
<?PHP } ?>
</tbody>
</table>

<?PHP } else { ?>
<input type="hidden" name="commission_tier_1" id="commission_tier_1" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[1],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_2" id="commission_tier_2" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[2],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_3" id="commission_tier_3" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[3],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_4" id="commission_tier_4" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[4],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_5" id="commission_tier_5" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[5],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_6" id="commission_tier_6" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[6],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_7" id="commission_tier_7" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[7],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_8" id="commission_tier_8" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[8],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_9" id="commission_tier_9" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[9],2,'.',''); ?>" />
<input type="hidden" name="commission_tier_10" id="commission_tier_10" onblur="re_calc(this)" value="<?PHP echo number_format($tpays[10],2,'.',''); ?>" />
<?PHP } } ?>

<input type="hidden" name="approve" value="1">
<input type="hidden" name="id" value="<?PHP echo $uid; ?>">
<input type="hidden" name="approve" value="1">
<input type="hidden" name="aff" value="<?PHP echo $aff; ?>">
<input type="hidden" name="afftype" value="<?PHP echo $afftype; ?>">
<input type="hidden" name="tracking" value="<?PHP echo $tracking_code; ?>">
<input type="hidden" name="odate" value="<?PHP echo $date; ?>">
<input type="hidden" name="otime" value="<?PHP echo $time; ?>">
<input type="hidden" name="paytop" value="<?PHP echo $commission; ?>">
<input type="hidden" name="id" value="<?PHP echo $uid; ?>">
<input type="hidden" name="acct" value="<?PHP echo $id; ?>">
<input type="hidden" name="sub_id" value="<?PHP echo $sub_id; ?>">
<input type="hidden" name="op1" value="<?PHP echo $op1; ?>">
<input type="hidden" name="op2" value="<?PHP echo $op2; ?>">
<input type="hidden" name="op3" value="<?PHP echo $op3; ?>">
<input type="hidden" name="profile" value="<?PHP echo $profadd; ?>">
<input type="hidden" name="oamount" value="<?PHP echo $tamount; ?>">
<input type="hidden" name="type" value="<?PHP echo $type; ?>">
<input type="hidden" name="ip" value="<?PHP echo $ip; ?>">
<input type="hidden" name="sendcode" value="<?PHP echo $sales_code; ?>">
<input type="hidden" name="tid1" value="<?PHP echo $listtid1; ?>">
<input type="hidden" name="tid2" value="<?PHP echo $listtid2; ?>">
<input type="hidden" name="tid3" value="<?PHP echo $listtid3; ?>">
<input type="hidden" name="tid4" value="<?PHP echo $listtid4; ?>">
<input type="hidden" name="sub_id" value="<?PHP echo $listsub_id; ?>">
<input type="hidden" name="target_url" value="<?PHP echo $listtarget_url; ?>">
<input type="hidden" name="referring_url" value="<?PHP echo $listreferring_url; ?>">
<input type="hidden" name="converted_amount" value="<?PHP echo $converted_amount_raw; ?>">
<input type="hidden" name="conversion_currency" value="<?PHP echo $conversion_currency; ?>">
<input type="hidden" name="tracking_method_used" value="<?PHP echo $tracking_method; ?>">


<?PHP if ($use_rec == 1) { ?>

<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td width="100%" colspan="2" style="background:#646464; color:#f3f3f3;">Recurring Commission Option</td>
</tr>

<?PHP if ($recstatus > 0) { ?>
<tr><td><a href="recurring.php" class="btn btn-sm-btn-default">This Is A Recurring Commission - Manage Recurring Commissions</a></td></tr>
<?PHP } else { ?>
<tr>
<td style="width:20%;">Recur This Commission</td>
<td style="width:80%;"><select name="rec_option" class="form-control input-width-medium">
<option value="1">Yes</option>
<option value="0" selected>No</option>
</select></td>
</tr>
<tr>
<td style="width:20%;">Amount To Recur</td>
<td style="width:80%;"><select class="form-control input-width-medium" name="rec_perc">
<option value=.01>1%</option>
<option value=.02>2%</option>
<option value=.03>3%</option>
<option value=.04>4%</option>
<option value=.05>5%</option>
<option value=.06>6%</option>
<option value=.07>7%</option>
<option value=.08>8%</option>
<option value=.09>9%</option>
<option value=.10>10%</option>
<option value=.11>11%</option>
<option value=.12>12%</option>
<option value=.13>13%</option>
<option value=.14>14%</option>
<option value=.15>15%</option>
<option value=.16>16%</option>
<option value=.17>17%</option>
<option value=.18>18%</option>
<option value=.19>19%</option>
<option value=.20>20%</option>
<option value=.21>21%</option>
<option value=.22>22%</option>
<option value=.23>23%</option>
<option value=.24>24%</option>
<option value=.25>25%</option>
<option value=.26>26%</option>
<option value=.27>27%</option>
<option value=.28>28%</option>
<option value=.29>29%</option>
<option value=.30>30%</option>
<option value=.31>31%</option>
<option value=.32>32%</option>
<option value=.33>33%</option>
<option value=.34>34%</option>
<option value=.35>35%</option>
<option value=.36>36%</option>
<option value=.37>37%</option>
<option value=.38>38%</option>
<option value=.39>39%</option>
<option value=.40>40%</option>
<option value=.41>41%</option>
<option value=.42>42%</option>
<option value=.43>43%</option>
<option value=.44>44%</option>
<option value=.45>45%</option>
<option value=.46>46%</option>
<option value=.47>47%</option>
<option value=.48>48%</option>
<option value=.49>49%</option>
<option value=.50>50%</option>
<option value=.51>51%</option>
<option value=.52>52%</option>
<option value=.53>53%</option>
<option value=.54>54%</option>
<option value=.55>55%</option>
<option value=.56>56%</option>
<option value=.57>57%</option>
<option value=.58>58%</option>
<option value=.59>59%</option>
<option value=.60>60%</option>
<option value=.61>61%</option>
<option value=.62>62%</option>
<option value=.63>63%</option>
<option value=.64>64%</option>
<option value=.65>65%</option>
<option value=.66>66%</option>
<option value=.67>67%</option>
<option value=.68>68%</option>
<option value=.69>69%</option>
<option value=.70>70%</option>
<option value=.71>71%</option>
<option value=.72>72%</option>
<option value=.73>73%</option>
<option value=.74>74%</option>
<option value=.75>75%</option>
<option value=.76>76%</option>
<option value=.77>77%</option>
<option value=.78>78%</option>
<option value=.79>79%</option>
<option value=.80>80%</option>
<option value=.81>81%</option>
<option value=.82>82%</option>
<option value=.83>83%</option>
<option value=.84>84%</option>
<option value=.85>85%</option>
<option value=.86>86%</option>
<option value=.87>87%</option>
<option value=.88>88%</option>
<option value=.89>89%</option>
<option value=.90>90%</option>
<option value=.91>91%</option>
<option value=.92>92%</option>
<option value=.93>93%</option>
<option value=.94>94%</option>
<option value=.95>95%</option>
<option value=.96>96%</option>
<option value=.97>97%</option>
<option value=.98>98%</option>
<option value=.99>99%</option>
<option value="1" selected>100%</option>
</select></td>
</tr>
<tr>
<td style="width:20%;">Recur Every</td>
<td style="width:80%;"><select name="rec_days" class="form-control input-width-medium">
<option value="30">1 Month</option>
<option value="60">2 Months</option>
<option value="90">3 Months</option>
<option value="120">4 Months</option>
<option value="150">5 Months</option>
<option value="180">6 Months</option>
<option value="210">7 Months</option>
<option value="240">8 Months</option>
<option value="270">9 Months</option>
<option value="300">10 Months</option>
<option value="330">11 Months</option>
<option value="365">12 Months</option>
</select></td>
</tr>

<?PHP } ?>
</tbody>
</table>
<?PHP } ?>

</div>
</div>
</div>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-smile"></i> Process This Commission</h4></div>
<div class="widget-content">
<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<?PHP if ($commission != 0) { ?>
<tr>
<td style="width:65%;">
<a href="commissions_pending.php?delete=<?PHP echo html_output($uid); ?>" class="btn btn-danger">Decline Commission</a><span class="pull-right"><input type="submit" class="btn btn-primary" value="Approve Commission" /></span></td>
<td style="width:35%;"><div class="input-group">
<?PHP if (isset($prependfield)) { ?><span class="input-group-addon"><?PHP echo $prependfield; ?></span><?PHP } ?>
<input type="text" name="commission_total" id="commission_total" class="form-control" placeholder=".input-group" readonly>
<span class="input-group-addon"><?PHP echo $appendfield; ?></span>
</div></td>
</tr>
<?PHP } else { ?>
<tr>
<td style="width:65%;"><font color="#CC0000">Processing Error</font> - The commission amount was less than 1 penny or the sale amount was not received.</td>
<td style="width:35%;"><a href="commissions_pending.php?delete=<?PHP echo html_output($uid); ?>" class="btn btn-danger btn-sm">Decline Commission</a></td>
</tr>

<?PHP } ?>

</tbody>
</table>
</form>
</div>
</div>
</div>



</div>

<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-link"></i> Associated Page URLs</h4></div>
<div class="widget-content">
<table class="table valign table-striped table-bordered table-highlight-head">
<tr>
<td width='20%'>Target URL</td>
<td width='80%'><?PHP echo $target_url; ?></td>
</tr>
<tr>
<td width='20%'>Referring URL</td>
<td width='80%'><?PHP if ($listref) { echo "<a href=\"" . $listref . "\" target=\"_blank\">" . $listref . "</a>"; } else { echo "Not available. Possible bookmark or email link."; } ?></td>
</tr>
</table>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Optional Data</h4></div>
<div class="widget-content">
<?PHP if ($optional_count > 0) { ?>
<table class="table valign table-striped table-bordered table-highlight-head">
<?PHP if (($use_op1 == 1) && ($opvar1_tag) && ($opvar1_cart)) { ?>
<tr><td width='30%'><?PHP echo html_output($opvar1_tag); ?></td><td width='70%'><?PHP echo html_output($op1); ?></td></tr>
<?PHP } if (($use_op2 == 1) && ($opvar2_tag) && ($opvar2_cart)) { ?>
<tr><td width='30%'><?PHP echo html_output($opvar2_tag); ?></td><td width='70%'><?PHP echo html_output($op2); ?></td></tr>
<?PHP } if (($use_op3 == 1) && ($opvar3_tag) && ($opvar3_cart)) { ?> 
<tr><td width='30%'><?PHP echo html_output($opvar3_tag); ?></td><td width='70%'><?PHP echo html_output($op3); ?></td></tr>
<?PHP } ?>
</table>
<?PHP } else { ?>
No optional data has been added to this commission.
<?PHP } ?>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Tracking IDs</h4></div>
<div class="widget-content">
<?PHP if (($tid1 != "None") || ($tid2 != "None") || ($tid3 != "None") || ($tid4 != "None")) { ?>
<table class="table valign table-striped table-bordered table-highlight-head">
<tr>
<td width='20%'>TID1</td>
<td width='80%'><?PHP echo html_output($tid1); ?></td>
</tr>
<tr>
<td width='20%'>TID2</td>
<td width='80%'><?PHP echo html_output($tid2); ?></td>
</tr>
<tr>
<td width='20%'>TID3</td>
<td width='80%'><?PHP echo html_output($tid3); ?></td>
</tr>
<tr>
<td width='20%'>TID4</td>
<td width='80%'><?PHP echo html_output($tid4); ?></td>
</tr>
</table>
<?PHP } else { ?>
No TIDs were used for this commission.
<?PHP } ?>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(5, 'no');?>" id="tab_1_5">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Custom Fields</h4></div>
<div class="widget-content">
<?PHP
if ($custom_count > 0) {
$getcustomrows = $db->query("select id, title from idevaff_form_fields_custom where display_record = '1' order by sort");
?>
<table class="table valign table-striped table-bordered table-highlight-head">
<?PHP
while ($qry = $getcustomrows->fetch()) {
$group_id = $qry['id'];
$custom_title = $qry['title'];
//$getvars = $db->query("select custom_value from idevaff_form_custom_data where custom_id = '$group_id' and affid = '$id'");
$getvars = $db->prepare("select custom_value from idevaff_form_custom_data where custom_id = ? and affid = ?");
$getvars->execute(array($group_id,$id));
$getvars = $getvars->fetch();
$custom_value = $getvars['custom_value'];
if ($custom_value == null) { $custom_value = "N/A"; }
echo "<tr>";
echo "<td width='30%'>" . $custom_title . "\n</td>";
echo "<td width='70%'>" . $custom_value . "\n</td>";
echo "</tr>";
}
?>
</table>
<?PHP } else { ?>
No custom field answers being displayed on commission records.
<?PHP } ?>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(6, 'no');?>" id="tab_1_6">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-alt"></i> Commission Notes</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="approve_commission.php">
<div class="alert alert-warning">Commission notes are only viewable to administrative staff.</div>
<div class="form-group">
<div class="col-md-12"><textarea rows="12" name="commission_notes" class="form-control"><?PHP echo $commission_notes; ?></textarea></div>
</div>
<div class="form-actions">
<input type="submit" value="Update Notes" class="btn btn-primary">
</div>
<input type="hidden" name="sale_id" value="<?PHP echo $uid; ?>">
<input type="hidden" name="tab" value="6">
</form>
</div>
</div>
</div>
</div>

</div>
</div>

<?PHP include("templates/footer.php"); ?>