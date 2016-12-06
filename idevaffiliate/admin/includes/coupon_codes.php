<?PHP
if (!defined('admin_includes')) { die(); }
include("session.check.php");

if (isset($_GET['remove'])) {
$remove = $_GET['remove'];
$st = $db->prepare("delete from idevaff_coupons where id = ?");
$st->execute(array($remove));
$success_message = "<strong>Success!</strong> Coupon code removed."; }

$da1 = (date ("Y"));
$da2 = (date ("m"));
$da3 = (date ("d"));
?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Commission Settings</li>
<li class="current"> <a href="setup.php?action=64" title="">Coupon Code Commissioning</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Coupon Code Commissioning</h3><span>Allow your affiliates to market your products/services using coupon codes in addition to traditional marketing methods.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Add A Coupon Code</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Manage Coupon Codes</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Pending Approval <?PHP if ($c_pending_count > '0') { ?><span class="label label-danger"><?PHP echo html_output($c_pending_count); ?></span><?PHP } ?></a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Coupon Code Settings</a></li>
<li><a href="http://www.idevdirect.com/cart_integrations.php?tab=8" target="_blank"><i class="icon-shopping-cart"></i> Compatible Carts</a></li>
<li <?php makeActiveTab(6);?>><a href="#tab_1_6" data-toggle="tab"><i class="icon-file-alt"></i> Tutorial</a></li>
</ul>

<div class="tab-content">

<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Add A Coupon Code</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<div class="alert alert-warning">You can also <a href="setup.php?action=72" style="color:blue;">bulk import coupon codes</a> from a CSV file.</div>
<div class="form-group">
<label class="col-md-3 control-label">Coupon Code</label>
<div class="col-md-6"><input type="text" name="coupon_code" value="<?PHP if (isset($_REQUEST['code'])) { echo html_output($_REQUEST['code']); } ?>" class="form-control" /></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Assign To</label>
<div class="col-md-6"><select class="form-control" name="coupon_affiliate">
<?PHP
$getnames= $db->query("select id, username from idevaff_affiliates order by id");
if ($getnames->rowCount()) {
while ($qry = $getnames->fetch()) {
$chid=$qry['id'];
$chuser=$qry['username'];
print "<option value='$chid'";
if ((isset($_REQUEST['coupon_affiliate'])) && ($chid == $_REQUEST['coupon_affiliate'])) { print ' selected'; }
print ">Affiliate ID: $chid - Username: $chuser</option>\n"; } }
?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Commission Amount</label>
<div class="col-md-9"><input type="text" name="coupon_amount" value="20" class="form-control input-width-small" style="display:inline-block;" /> <select class="form-control input-width-xlarge" style="display:inline-block;" name="coupon_type">
<option value="1">Percent of Sale Amount</option>
<option value="2">Flat Rate</option>
<option value="3">Use Default Payout Level</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Discount To Customer</label>
<div class="col-md-6"><input type="text" name="discount_amount" value="" placeholder="10% off entire order." class="form-control" /><div class="help-block">This desription tells your affiliate the discount offered to the customer.</div>
</div></div>

<div class="form-group">
<label class="col-md-3 control-label">Send Email Notification To Affiliate</label>
<div class="col-md-9"><select class="form-control input-width-small" name="email_notify" style="display:inline-block;"">
<option value="1">Yes</option>
<option value="0">No</option>
</select> <span style="display:inline-block;"><a href=""><button class="btn btn-default btn-sm">Edit The Email Template</button></a></span></div>
</div>

<div class="form-actions">
<input type="submit" value="Add Coupon Code" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="64">
<input type="hidden" name="cfg" value="119">
<?PHP if (isset($_REQUEST['code_id'])) { ?><input type="hidden" name="code_id" value="<?PHP echo html_output($_REQUEST['code_id']); ?>"><?PHP } ?>
</form>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">

<?PHP
$check_count= $db->query("SELECT COUNT(*) FROM idevaff_coupons");
if ($check_count->rowCount()) {
?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Manage Coupon Codes</h4></div>
<div class="widget-content">

<div class="widgetcontent bordered nomargin">
<table class="table table-striped table-bordered table-highlight-head valign" id="dyntable_coupons">
<thead>
<tr>
<th>Coupon Code</th>
<th>Affiliate ID</th>
<th>Username</th>
<th>Discount To Customer</th>
<th>Commission To Affiliate</th>
<th>Action</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
</div>
</div>
</div>
</div>

<?PHP } else { ?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Manage Coupon Codes</h4></div>
<div class="widget-content">
No coupon codes assigned yet.
</div>
</div>
</div>
<?PHP } ?>

</div>

<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">

<?PHP
$check_count= $db->query("SELECT COUNT(*) FROM idevaff_coupons_pending");
if ($check_count->rowCount()) {
?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Coupon Codes Pending Creation</h4></div>
<div class="widget-content">

<div class="widgetcontent bordered nomargin">
<table class="table table-striped table-bordered table-highlight-head valign" id="dyntable_coupons_pending">
<thead>
<tr>
<th>Date Requested</th>
<th>Affiliate ID</th>
<th>Username</th>
<th>Coupon Code Requested</th>
<th>Action</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
</div>
</div>
</div>
</div>

<?PHP } else { ?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Coupon Codes Pending Creation</h4></div>
<div class="widget-content">
No coupon codes pending creation.
</div>
</div>
</div>
<?PHP } ?>

</div>

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-tags"></i> Coupon Code Settings</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">

<div class="form-group">
<label class="col-md-3 control-label">Commissioning Priority</label>
<div class="col-md-3"><select class="form-control" name="coupon_priority">
<option value="1"<?PHP if ($coupon_priority == '1') { echo " selected"; } ?>>Tracking Logs</option>
<option value="2"<?PHP if ($coupon_priority == '2') { echo " selected"; } ?>>Coupon Code</option>
</select></div>
</div>

<?PHP
if (isset($mod_van)) {
include ("vanity_codes_key.php");
if ($vanity_key == '81573') { $vanity_check = true; }
}
?>

<div class="form-group">
<label class="col-md-3 control-label">Allow Vanity Code Request</label>
<div class="col-md-9"><select class="form-control input-width-medium" name="vanity_codes"<?PHP if ((!isset($mod_van)) || (!isset($vanity_check))) { echo " disabled"; } ?>>
<?PHP if ((isset($mod_van)) && (isset($vanity_check))) { ?><option value="1"<?PHP if ($vanity_codes == '1') { echo " selected"; } ?>>Yes</option><?PHP } ?>
<option value="0"<?PHP if ($vanity_codes == '0') { echo " selected"; } ?>>No</option>
</select><div class="help-block">This feature allows your affiliates to request a unique coupon code that you can then approve or decline.<?PHP if ((!isset($mod_van)) || (!isset($vanity_check))) { ?><br /><br /><a href="http://www.idevdirect.com/module_vanity_codes.php" target="_blank" class="btn btn-info btn-sm">Requires Vanity Coupon Codes Plugin</a><?PHP } ?></div></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Email On New Vanity Code Request</label>
<div class="col-md-3"><select class="form-control input-width-medium" name="vanity_notify">
<option value="1"<?PHP if ($vanity_notify == '1') { echo " selected"; } ?>>Yes</option>
<option value="0"<?PHP if ($vanity_notify == '0') { echo " selected"; } ?>>No</option>
</select></div>
</div>



<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="64">
<input type="hidden" name="cfg" value="126">
<input type="hidden" name="tab" value="4">
</form>
</div>
</div>
</div>

</div>

<div class="tab-pane<?php makeActiveTab(6, 'no');?>" id="tab_1_6">

<div class="col-md-12">
<div class="bs-callout bs-callout-tutorial">
<h4><img src="images/help-tutorial.png" height="48" width="48" border="none;" /> Coupon Code Commissioning Tutorial</h4>
<form method="LINK" action="http://www.idevlibrary.com/docs/Coupon_Code_Commissioning.pdf" target="_blank">
<button class="btn btn-success btn-lg">View Tutorial</button>
</form>
</div>
</div>

</div>
</div>

</div>





