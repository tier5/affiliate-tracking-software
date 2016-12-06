<?PHP
$cart_profile = '96';
$cart_profile_version = '2.0';
$cart_name = "MemberMouse";
$cart_cat = '3';
$protection_eligible = '1';
$coupon_code_eligible = '1';
$per_product_eligible = '1';
$profile_protection_eligible = '1';
$recurring_supported = '1';
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
<div class="widget-header"><h4><i class="icon-shopping-cart"></i> <?PHP echo $cart_name; ?></h4></div>
<div class="widget-content" align="center">

<?PHP include ("carts/notes_integration.php"); ?>

<h4><?PHP echo $cart_name; ?> is now enabled.</h4>
Please see the following documentation to learn how to enable/configure iDevAffiliate in <?PHP echo $cart_name; ?>.
<br /><br />
<a href="http://support.membermouse.com/support/solutions/articles/9000020279-idevaffiliate-configuring" target="_blank" style="display:inline-block;"><button class="btn btn-warning"><i class="icon-gear"></i> Configuring iDevAffiliate</button></a>
<a href="setup.php?action=44&tab=2" style="display:inline-block;"><button class="btn btn-danger"><i class="icon-unlock-alt"></i> Get Your API Secret Key</button></a>
</div>
</div>


<?PHP include("carts/notes.php"); ?>

<?PHP } ?>