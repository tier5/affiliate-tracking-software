<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");

if (isset($_REQUEST['archive'])) {
$leftSubActiveMenu = 'reports';
} else {
$leftSubActiveMenu = 'commissions';
}
require("templates/header.php");


if (!isset($_REQUEST['cfg'])) {
	
$details = null;
$data = null;
$table = null;

if (isset($_REQUEST['sales'])) {
if (isset($_POST['commission_notes'])) {
$st=$db->prepare("update idevaff_sales set notes = ? where record = ?");
$st->execute(array($_POST['commission_notes'],$_POST['record_id']));
$success_message = "<strong>Success!</strong> Commission notes updated.";
}
if (is_numeric($_REQUEST['sales'])) { $data = $_REQUEST['sales']; } else { $data = '0'; }
$table = "sales";
$details = $db->prepare("select * from idevaff_sales where record = ?"); 
$details->execute(array($data));
}

if (isset($_REQUEST['archive'])) {
if (isset($_POST['commission_notes'])) {
$st=$db->prepare("update idevaff_archive set notes = ? where record = ?");
$st->execute(array($_POST['commission_notes'],$_POST['record_id']));
$success_message = "<strong>Success!</strong> Commission notes updated.";
}
if (is_numeric($_REQUEST['archive'])) { $data = $_REQUEST['archive']; } else { $data = '0'; }
$table = "archive";
$details = $db->prepare("select * from idevaff_archive where record = ?"); 
$details->execute(array($data));
}

if ((isset($details)) && (isset($data))) {

$qry = $details->fetch();
$record=$qry['record'];
$id=$qry['id'];

$approved = null;
if (isset($table)) {
if ($table == "sales") {
$approved = $qry['approved'];
} }

$type=$qry['type'];
$date=date('m-d-Y', $qry['code']);
$time=date('h:i a', $qry['code']);
$payment=number_format($qry['payment'], $decimal_symbols);
$amount=$qry['amount'];
$tracking=$qry['tracking'];
$op1d=$qry['op1'];
$op2d=$qry['op2'];
$op3d=$qry['op3'];
$profile=$qry['profile'];
$top_tier_tag=$qry['top_tier_tag'];
$override=$qry['override'];

$tracking_method = $qry['tracking_method'];

$commission_notes = $qry['notes'];
if (empty($commission_notes) || trim($commission_notes)==='') {	$tabnotes_count = '0'; $tabnotes_color = "label-default"; } else { $tabnotes_count = '1'; $tabnotes_color = "label-primary"; }

$rec_id = null;
if (isset($table)) {
if ($table == "sales") {
$rec_id = $qry['rec_id'];
} }

$listref=$qry['referring_url'];
$converted_amount_raw = $qry['converted_amount'];
$conversion_currency = $qry['currency'];
$sub_id = $qry['sub_id'];
if (!$sub_id) { $sub_id = "None"; }

$ip=$qry['ip'];

$flag_country = strtolower($qry['geo']);
$flag = '';
if (file_exists($path.'/admin/images/geo_flags/'.$flag_country.'.png')) {
$flag = "<img src=\"images/geo_flags/".$flag_country.".png\" height=\"24\" width=\"24\" border=\"none;\" />"; }

$tid_count = 0;
if ($qry['tid1'] != '') { $tid1 = $qry['tid1']; $tid_count = $tid_count + 1; } else { $tid1 = "None"; }
if ($qry['tid2'] != '') { $tid2 = $qry['tid2']; $tid_count = $tid_count + 1; } else { $tid2 = "None"; }
if ($qry['tid3'] != '') { $tid3 = $qry['tid3']; $tid_count = $tid_count + 1; } else { $tid3 = "None"; }
if ($qry['tid4'] != '') { $tid4 = $qry['tid4']; $tid_count = $tid_count + 1; } else { $tid4 = "None"; }
if ($tid_count > 0) { $tabtid_color = "label-danger"; } else { $tabtid_color = "label-default"; }

if ($qry['target_url']) { $target_url = "<a href=\"" . $qry['target_url'] . "\" target=\"_blank\">" . $qry['target_url'] . "</a>"; } else { $target_url = "Not available."; }

$details2 = $db->prepare("select username from idevaff_affiliates where id = ?");
$details2->execute(array($id));
$qry2 = $details2->fetch();
$user=$qry2['username'];

$optional_count = 0;
if (($op1d == '') || ($op1d == "N/A")) { $op1d = "N/A"; } else { $optional_count = $optional_count + 1; }
if (($op2d == '') || ($op2d == "N/A")) { $op2d = "N/A"; } else { $optional_count = $optional_count + 1; }
if (($op3d == '') || ($op3d == "N/A")) { $op3d = "N/A"; } else { $optional_count = $optional_count + 1; }
if ($optional_count > 0) { $tabopt_color = "label-danger"; } else { $tabopt_color = "label-default"; }

if ($table == "archive") {
$status = "Paid";
} elseif ($approved == 1) {
$status = "Currently Approved";
} elseif ($approved == 0) {
$status = "Pending Approval";
}


if ($top_tier_tag) { $type = "Tier Commission"; }
elseif ($override == 1) { $type = "Override Commission"; }
elseif ($top_tier_tag == 0) { $type = "Standard Commission"; }
if (isset($recurring)) { $rec = "<a href=Yes</a>"; } else { $rec = "No"; }
if (!$tracking) { $tracking = "N/A"; }
if (!$amount) { $amount = "N/A"; } else {

if ($converted_amount_raw > 0) {
$temp_cur_sym=$db->prepare("select currency_symbol from idevaff_currency where currency_code = ?");
$temp_cur_sym->execute(array($conversion_currency));
$temp_cur_sym=$temp_cur_sym->fetch();
$temp_cur_sym=$temp_cur_sym['currency_symbol'];

$converted_amount=(number_format($converted_amount_raw, $decimal_symbols));
if($cur_sym_location == 1) { $converted_amount = $cur_sym . $converted_amount; }
if($cur_sym_location == 2) { $converted_amount = $converted_amount . " " . $cur_sym; }
$converted_amount = $converted_amount;

$disamount = $temp_cur_sym . $amount . " " . $conversion_currency . " was converted to " . $converted_amount . " " . $currency;
} else {
$amount=(number_format($amount, $decimal_symbols));
if($cur_sym_location == 1) { $amount = $cur_sym . $amount; }
if($cur_sym_location == 2) { $amount = $amount . " " . $cur_sym; }
$disamount = $amount . " " . $currency; } }

if (!$profile) { $profile = 9000; }
if ($profile == 72198) { $profile = 0; }
$integration=$db->prepare("select * from idevaff_integration where type = ?");
$integration->execute(array($profile));
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


$custom_count = $db->query("SELECT COUNT(*) FROM idevaff_form_fields_custom where display_record = '1' order by sort");
$custom_count = $custom_count->fetchColumn();
if ($custom_count > 0) { $tabcustom_color = "label-danger"; } else { $tabcustom_color = "label-default"; }

$profile_name = $db->prepare("select name from idevaff_carts where id = ?");
$profile_name->execute(array($profile));
$qry = $profile_name->fetch();
$tdisp=$qry['name'];

if (!isset($tdisp)) {
if ($profile == '0') { $tdisp = "Generic Tracking Pixel"; }
if ($profile == '44') { $tdisp = "Pay-Per-Lead Tracking Pixel"; }
}
?>
<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Commissions</li>
<li class="current"> <a href="commissions_approved.php">Commission Details</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Commission Details</h3><span>This commission has already been approved and/or paid.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">General Details</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Optional Data <span class="label<?PHP if (isset($tabopt_color)) { echo " " . $tabopt_color; } ?>"><?PHP echo html_output($optional_count); ?></span></a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Tracking IDs <span class="label<?PHP if (isset($tabtid_color)) { echo " " . $tabtid_color; } ?>"><?PHP echo html_output($tid_count); ?></span></a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Custom Fields <span class="label<?PHP if (isset($tabcustom_color)) { echo " " . $tabcustom_color; } ?>"><?PHP echo html_output($custom_count); ?></span></a></li>
<li <?php makeActiveTab(6);?>><a href="#tab_1_6" data-toggle="tab">Notes <span class="label<?PHP if (isset($tabnotes_color)) { echo " " . $tabnotes_color; } ?>"><?PHP echo html_output($tabnotes_count); ?></a></li>
</ul>

<div class="tab-content">

<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-money"></i> General Commission Details</h4>
<?PHP if ($table == "archive") { ?><span class="pull-right"><span class="pull-right"><a href="reports.php?report=3"><button class="btn btn-sm btn-success" style="display:inline-block;">Choose Another Report</button></a> 
<a href="reports.php?report=3&sub_report=4"><button class="btn btn-sm btn-danger" style="display:inline-block;">Choose Another Affiliate</button></a></span><?PHP } ?>

<?PHP if (($table != "archive") && ($approved == '1')) { ?><span class="pull-right"><span class="pull-right"><a href="commissions_approved.php?unapprove=<?PHP echo html_output($record); ?>"><button class="btn btn-danger btn-sm">Un-Approve This Commission</button></a></span><?PHP } ?>
</div>
<div class="widget-content">



<table class="table valign table-striped table-bordered table-highlight-head">
<tr>
<td>Account ID</td>
<td><?PHP echo html_output($id); ?></td>
<td>Commission Status</td>
<td><?PHP echo html_output($status); ?></td>
</tr>
<tr>
<td>Affiliate</td>
<td><a href="account_details.php?id=<?PHP echo html_output($id); ?>"><?PHP echo html_output($user); ?></a></td>
<td>Type of Commission</td>
<td><?PHP echo html_output($type); ?></td>
</tr>
<tr>
<td>Date</td>
<td><?PHP echo html_output($date); ?></td>
<td>Commission Amount</td>
<td><?PHP if($cur_sym_location == 1) { echo html_output($cur_sym); } echo html_output($payment); if($cur_sym_location == 2) { echo " " . html_output($cur_sym) . " "; }echo " $currency"; ?></td>
</tr>
<tr>
<td>Time</td>
<td><?PHP echo html_output($time); ?></td>
<td>Sale Amount</td>
<td><?PHP echo html_output($disamount); ?></td>
</tr>
<tr>
<td>Order Number</td>
<td><?PHP echo html_output($tracking); ?></td>
<td>Customer IP</td>
<td><?PHP echo html_output($ip); ?><span class="pull-right"><?PHP echo $flag; ?></span></td>
</tr>
<tr>
<td>Cart/Billing System</td>
<td><?PHP if (!isset($tdisp)) { echo "N/A"; } else { echo html_output($tdisp); } ?></td>
<td>Tracking Method<span class="pull-right"><a data-toggle="modal" href="#tracking_method_info" class="btn btn-xs btn-info">info</a></span></td>
<td><?PHP if ($tracking_method == '') { echo "N/A"; } else { echo html_output($tracking_method); } ?></td>
</tr>
<tr>
<td width="100%" colspan="4" style="background:#428bca; color:#f3f3f3;">Associated Page URLs</td>
</tr>
<tr>
<td colspan="2">Target URL</td>
<td colspan="2"><?PHP echo $target_url; ?></td>
</tr>
<tr>
<td colspan="2">Referring URL</td>
<td colspan="2"><?PHP if ($listref) { echo "<a href=\"" . $listref . "\" target=\"_blank\">" . $listref . "</a>"; } else { echo "Not available. Possible bookmark or email link."; } ?></td>
</tr>
</table>
</div>
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
<div class="modal-body">There are several methods and factors involved when creating a commission. The method used depends on the different overrides, API calls, coupon codes and priority settings you have selected. This information is purely informational.
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

<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Optional Data</h4></div>
<div class="widget-content">
<?PHP if ($optional_count > 0) { ?>
<table class="table valign table-striped table-bordered table-highlight-head">
<?PHP if (($use_op1 == 1) && ($opvar1_tag) && ($opvar1_cart)) { ?>
<tr><td width='30%'><?PHP echo html_output($opvar1_tag); ?></td><td width='70%'><?PHP echo html_output($op1d); ?></td></tr>
<?PHP } if (($use_op2 == 1) && ($opvar2_tag) && ($opvar2_cart)) { ?>
<tr><td width='30%'><?PHP echo html_output($opvar2_tag); ?></td><td width='70%'><?PHP echo html_output($op2d); ?></td></tr>
<?PHP } if (($use_op3 == 1) && ($opvar3_tag) && ($opvar3_cart)) { ?> 
<tr><td width='30%'><?PHP echo html_output($opvar3_tag); ?></td><td width='70%'><?PHP echo html_output($op3d); ?></td></tr>
<?PHP } ?>
</table>
<?PHP } else { ?>
No optional data has been added to this commission.
<?PHP } ?>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">
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

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">
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
<form class="form-horizontal row-border" method="post" action="commission_details.php">
<div class="alert alert-warning">Commission notes are only viewable to administrative staff.</div>
<div class="form-group">
<div class="col-md-12"><textarea rows="12" name="commission_notes" class="form-control"><?PHP echo $commission_notes; ?></textarea></div>
</div>
<div class="form-actions">
<input type="submit" value="Update Notes" class="btn btn-primary">
</div>
<input type="hidden" name="record_id" value="<?PHP echo $record; ?>">
<?PHP if (isset($_REQUEST['sales'])) { ?><input type="hidden" name="sales" value="<?PHP echo $record; ?>"><? } ?>
<?PHP if (isset($_REQUEST['archive'])) { ?><input type="hidden" name="archive" value="<?PHP echo $record; ?>"><? } ?>
<input type="hidden" name="tab" value="6">
</form>
</div>
</div>
</div>
</div>

</div>
</div>

<?PHP } } include("templates/footer.php"); ?>











