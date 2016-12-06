<?PHP
$cart_profile = '154';
$cart_profile_version = '1.0';
$cart_name = "Celery";
$cart_cat = '5';
$protection_eligible = '1';
$coupon_code_eligible = '1';
$per_product_eligible = '1';
$profile_protection_eligible = '1';
$recurring_supported = '1';
$alternate_commission_supported = '0';
$ssl_required = '1';

if (!isset($readingonly)) {
include("module_update.php");

?>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-shopping-cart"></i> <?PHP echo $cart_name; ?> Integration Instructions</h4>
<span class="pull-right"><a href="setup.php?action=2"><button class="btn btn-default btn-sm">Back To Integration Profiles</button></a>
</span>
</div>
<div class="widget-content">

<?PHP include ("carts/notes_integration.php"); ?>

<table class="table table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td width="5%">1.</td>
<td width="95%">Login to your <?PHP echo $cart_name; ?> admin center and go to <strong>Settings</strong> > <strong>Integration</strong>.</td>
</tr>
<tr>
<td width="5%">2.</td>
<td width="95%">Add a new Webhook and use this for your <strong>Webhook URL</strong>:</td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><textarea rows="2" class="form-control"><?PHP echo $base_url; ?>/connect/celery.php</textarea></td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><img src="http://www.idevlibrary.com/files/celery_setting.png" style="width:800px; height:471px; border:none;" /></td>
</tr>
<tr>
<td width="5%">3.</td>
<td width="95%">Hit the <strong>Save</strong> button and you're all set.</td>
</tr>
</tbody>
</table>
</div>
</div>

<?PHP include("carts/notes.php"); ?>

<?PHP } ?>