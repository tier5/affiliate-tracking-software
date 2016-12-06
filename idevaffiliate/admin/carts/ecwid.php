<?PHP
$cart_profile = '86';
$cart_profile_version = '2.0';
$cart_name = "Ecwid";
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

$integration = $db->prepare("select * from idevaff_integration where type = ?");
$integration->execute(array($cart_profile));
$iconfig=$integration->fetch();
$opvar1_name = $iconfig['idev_var1'];
$opvar1_cart = $iconfig['cart_var1'];
$use_op1 = $iconfig['use_var1'];
$opvar1_tag = $iconfig['tag_var1'];
$opvar2_name = $iconfig['idev_var2'];
$opvar2_cart = $iconfig['cart_var2'];
$use_op2 = $iconfig['use_var2'];
$opvar2_tag = $iconfig['tag_var2'];
$opvar3_name = $iconfig['idev_var3'];
$opvar3_cart = $iconfig['cart_var3'];
$use_op3 = $iconfig['use_var3'];
$opvar3_tag = $iconfig['tag_var3'];
if (($use_op1 == 1) && ($opvar1_tag) && ($opvar1_cart)) { $addvar1 = "&idev_option_1=" . $opvar1_cart; }
if (($use_op2 == 1) && ($opvar2_tag) && ($opvar2_cart)) { $addvar2 = "&idev_option_2=" . $opvar2_cart; }
if (($use_op3 == 1) && ($opvar3_tag) && ($opvar3_cart)) { $addvar3 = "&idev_option_3=" . $opvar3_cart; }

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
<td width="95%">We have an App in the Ecwid App store that is needed for integration. Please install the App directly from the Ecwid App store.</td>
</tr>
<tr>
<td width="5%" align="center"></td>
<td width="95%"><a href="https://www.ecwid.com/apps/marketing/idev-affiliate" class="btn btn-danger btn-sm" target="_blank">Go To The iDevAffiliate App Now</a></td>
</tr>
<tr>
<td width="5%" align="center">2.</td>
<td width="95%">Once installed, go to your new <strong>iDevAffiliate Integration</strong> settings in <strong><?PHP echo $cart_name; ?></strong>.</td>
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
<tr>
<td width="5%" align="center"></td>
<td width="95%"><img src="http://www.idevlibrary.com/files/ecwid_settings.png" style="width:800px; height:300px; border:none;" /></td>
</tr>
</table>
</div>
</div>

<?PHP include("carts/notes.php"); ?>

<?PHP } ?>