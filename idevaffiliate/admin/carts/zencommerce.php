<?PHP
$cart_profile = '160';
$cart_profile_version = '1.0';
$cart_name = "Zencommerce";
$cart_cat = '5';
$protection_eligible = '1';
$coupon_code_eligible = '1';
$per_product_eligible = '0';
$profile_protection_eligible = '1';
$recurring_supported = '0';
$alternate_commission_supported = '0';
$ssl_required = '0';

	// CREATE TABLE DATA
	$checkdat = $db->query("SHOW COLUMNS from idevaff_carts_data LIKE 'zencommerce'");
	if (!$checkdat->rowCount()) {
	$add_column = $db->prepare("ALTER TABLE idevaff_carts_data ADD zencommerce blob NOT NULL");
	$add_column->execute(); }

if (!isset($readingonly)) {
include("module_update.php");

	// UPDATE DATA
	if (isset($_POST['zencommerce_key'])) {
	$st = $db->prepare("update idevaff_carts_data set zencommerce = (AES_ENCRYPT(?, '" . SITE_KEY . "'))");
	$st->execute(array($_POST['zencommerce_key']));
	$success_message = "<strong>Success!</strong> Settings saved.";
	}

	// GET CART DATA
	$query_cart_data = $db->query("SELECT AES_DECRYPT(zencommerce, '" . SITE_KEY . "') AS decrypted_zencommerce from idevaff_carts_data");
	$query_cart_data->setFetchMode(PDO::FETCH_ASSOC);
	$cart_data=$query_cart_data->fetch();
	$zencommerce_key=$cart_data['decrypted_zencommerce'];
	
?>

<?PHP include("includes/notifications.php"); ?>

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
<td width="95%">Login to your Zencommerce admin center and go to <strong>Configuration</strong> > <strong>Administrator, System</strong> > <strong>Webhooks</strong>.</td>
</tr>
<tr>
<td width="5%">2.</td>
<td width="95%">Click <strong>Add Webhook</strong>.</td>
</tr>
<tr>
<td width="5%">3.</td>
<td width="95%">Enter this for your URL Address:</td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><textarea rows="2" class="form-control"><?PHP echo $base_url; ?>/connect/zencommerce.php</textarea></td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><font color="#CC0000">Make sure the above path is correctly pointing to your installation folder/directory.</font></td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><strong>Key</strong>. This is a generic password of your choosing (to be used below). Save your changes.</td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><strong>Events</strong>. Choose <strong>order.paid</strong>.</td>
</tr>
<tr>
<td width="5%"></td>
<td width="95%"><strong>Format</strong>. Choose <strong>JSON</strong>.</td>
</tr>
<tr>
<td width="5%" align="center"></td>
<td width="95%"><img src="http://www.idevlibrary.com/files/zencommerce_webhook.png" style="width:800px; height:330px; border:none;" /></td>
</tr>
<tr>
<td width="5%">4.</td>
<td width="95%">Activate the button and hit the save button.</td>
</tr>
</tbody>
</table>
</div>
</div>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-shopping-cart"></i> <?PHP echo $cart_name; ?> Integration Settings</h4>
</div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<div class="form-group">
<label class="col-md-3 control-label">Zencommerce Key</label>
<div class="col-md-9"><input type="text" name="zencommerce_key" class="form-control input-width-xlarge" value="<?PHP echo html_output($zencommerce_key); ?>"><div class="help-block">Enter your <strong>key</strong> (password), created in step 3 above.</div></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="2">
<input type="hidden" name="code" value="1">
<input type="hidden" name="module" value="160">
</form>
</div>
</div>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-shopping-cart"></i> <?PHP echo $cart_name; ?> Notes</h4>
</div>
<div class="widget-content">
A commission will be created only after the order status is marked <strong>paid</strong>.
<br /><br />
<img src="http://www.idevlibrary.com/files/zencommerce_status.png" style="width:800px; height:260px; border:none;" />
</div>
</div>

<?PHP include("carts/notes.php"); ?>

<?PHP } ?>