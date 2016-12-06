<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");

if (($staff_app_dec_accounts == 'off') && (!isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { header("Location: staff_notice.php"); exit(); }

if (isset($_REQUEST['decision'])) {
$addon = null;
if ($_REQUEST['decision'] == 1) {
$st=$db->prepare("update idevaff_affiliates set approved = '1' where id = ?");
$st->execute(array($_REQUEST['id']));
if (($account_notify_affiliate_approved == 1) && ($_REQUEST['decision'] == 1)) {
include_once("$path/templates/email/affiliate.account_approved.php"); }
$success_message = "<strong>Success!</strong> Account approved.";

} elseif ($_REQUEST['decision'] > 1) {
$account = $_REQUEST['id'];
if ($_REQUEST['decision'] == 3) { $addon = " The <strong>Account Declined</strong> email has been sent to the affiliate.";
include_once($path . "/templates/email/affiliate.account_declined.php"); }

$check_for_fb = $db->prepare("select id from idevaff_affiliates where fb_user_id = '' and id = ?");
$check_for_fb->execute(array($account));
if ($check_for_fb->rowCount()) {
	
// move to deleted list
$check_for_existing = $db->prepare("SELECT * FROM idevaff_deleted_accounts WHERE id = ?");
$check_for_existing->execute(array($account));
if (!$check_for_existing->rowCount()) {
$st=$db->prepare("INSERT INTO idevaff_deleted_accounts SELECT * FROM idevaff_affiliates WHERE id = ?");
$st->execute(array($account)); }
$st=$db->prepare("update idevaff_affiliates set approved = '0', suspended = '0', hits_in = '0', conv = '0', level = '1', tc_status = '0' where id = ?");
$st->execute(array($account));
$st=$db->prepare("DELETE FROM idevaff_affiliates where id = ?");
$st->execute(array($account));

} else {
// this is a facebook account - permanently delete
if ($auto_add_ban == 1) {
$term_email = $db->prepare("select email from idevaff_affiliates where id = ?"); 
$term_email->execute(array($account));
$qry = $term_email->fetch();
$term_email=$qry['email'];
$get_blocked_email = $db->prepare("select email_address from idevaff_banned_email where email_address = ?");
$get_blocked_email->execute(array($term_email));
if (!$get_blocked_email->rowCount()) {
$st=$db->prepare("insert into idevaff_banned_email (email_address) VALUES (?)");
$st->execute(array($term_email));
}
$info_message = "<strong>Note!</strong> Account email address was also added to the account signup ban list. [<a href=\"setup.php?action=53&tab=3\">Change Settings</a>]"; }
$st=$db->prepare("DELETE FROM idevaff_affiliates where id = ?");
$st->execute(array($account));
}

	define('TERMINATE_ROUTINE', TRUE);
	include ("../includes/terminate_routines.php");
	
$success_message = "<strong>Success!</strong> Account declined." . $addon; }
}

$leftSubActiveMenu = 'affiliates';
require("templates/header.php");

$accounts_pending = $db->query("SELECT COUNT(*) FROM idevaff_affiliates where approved = '0'");

?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Affiliates</li>
<li class="current"> <a href="accounts_pending.php">Pending Affiliate Accounts</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Pending Affiliate Accounts</h3><span>Your affiliates, pending account approval.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<?PHP if ($accounts_pending->fetchColumn()) { ?>

<div class="row">
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-user"></i> Account List</h4><span class="pull-right"><a href="create.php"><button class="btn btn-info btn-sm"><i class="icon-user"></i> Create An Account</button></a> <a href="export_affiliates.php"><button class="btn btn-inverse btn-sm"><i class="icon-table"></i> Export Affiliates</button></a> <a href="bulk.php"><button class="btn btn-default btn-sm"><i class="icon-gear"></i> Bulk Manage Accounts</button></a></span></div>
<div class="widget-content">

<table class="table table-striped table-bordered table-highlight-head valign" id="dyntable_pending">
<thead>
<tr>
<th>Affiliate ID</th>
<th>Username</th>
<th>Date Joined</th>
<th>Account Actions</th>
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

<div class="row">
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-user"></i> Pending Affiliate Accounts</h4><span class="pull-right"><a href="create.php"><button class="btn btn-info btn-sm"><i class="icon-user"></i> Create An Account</button></a></span></div>
<div class="widget-content">
No accounts to show.
</div>
</div>
</div>
</div>

<?PHP } ?>


<?PHP include("templates/footer.php"); ?>