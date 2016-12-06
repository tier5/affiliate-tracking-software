<?PHP
$cart_profile = '16';
$cart_profile_version = '1.0';
$cart_name = "X-Cart";
$cart_cat = '5';
$protection_eligible = '0';
$coupon_code_eligible = '1';
$per_product_eligible = '1';
$profile_protection_eligible = '1';
$recurring_supported = '0';
$alternate_commission_supported = '0';
$ssl_required = '0';

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
<tr>
<td width="5%" align="center">1.</td>
<td width="95%"><a href="http://www.idevlibrary.com/files/iDevAffiliate_X-Cart_Module.tar" class="btn btn-danger btn-sm">Download the <?PHP echo $cart_name; ?> Module</a></td>
</tr>
<tr>
<td width="5%" align="center">2.</td>
<td width="95%">Login to your <?PHP echo $cart_name; ?> admin center and click on <strong>Modules</strong>. Using the module downloaded above, upload the add-on.</td>
</tr>
<tr>
<td width="5%" align="center"></td>
<td width="95%"><img src="http://www.idevlibrary.com/files/x-cart_module.png" style="width:800px; height:131px; border:none;" /></td>
</tr>
<tr>
<td width="5%" align="center">3.</td>
<td width="95%">Now click on <strong>Settings</stong>.</td>
</tr>
<tr>
<td width="5%" align="center"></td>
<td width="95%">There you can enter the following URL location for your <strong>iDevAffiliate Installation URL</strong>.</td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><input type="text" style="background:#e1f0ff;" class="form-control" value="<?PHP echo $base_url; ?>/"></td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><font color="#CC0000">Make sure the above path is correctly pointing to your installation folder/directory.</font></td>
</tr>
</table>


</div>
</div>
<?PHP include("carts/notes.php"); ?>

<?PHP } ?>