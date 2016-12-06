<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");
$process = 0;

if (isset($_POST['terminate'])) {

if (($staff_delete_accounts == 'off') && (!isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) {
header("Location: staff_notice.php"); exit(); }

$account = $_POST['terminate'];

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
$success_message = "<strong>Success!</strong> This account was created using Facebook and has been permanently terminated.";
}

	define('TERMINATE_ROUTINE', TRUE);
	include ("../includes/terminate_routines.php");
	
}

if (isset($_REQUEST['remove'])) {

if ($auto_add_ban == 1) {
$term_email = $db->prepare("select email from idevaff_deleted_accounts where id = ?"); 
$term_email->execute(array($_REQUEST['remove']));
$qry = $term_email->fetch();
$term_email=$qry['email'];
$get_blocked_email = $db->prepare("select email_address from idevaff_banned_email where email_address = ?");
$get_blocked_email->execute(array($term_email));
if (!$get_blocked_email->rowCount()) {
$st=$db->prepare("insert into idevaff_banned_email (email_address) VALUES (?)");
$st->execute(array($term_email));
}
$info_message = "<strong>Note!</strong> Account email address was also added to the account signup ban list. [<a href=\"setup.php?action=53&tab=3\">Change Settings</a>]"; }

$st=$db->prepare("delete from idevaff_deleted_accounts where id = ?");
$st->execute(array($_REQUEST['remove']));
$success_message = "<strong>Success!</strong> Account has been permanently removed."; }

if (isset($_REQUEST['activate'])) {
try{
	$st=$db->prepare("INSERT INTO idevaff_affiliates SELECT * FROM idevaff_deleted_accounts WHERE id = ?");
	$st->execute(array($_REQUEST['activate']));
}catch(exception $e){}
$st=$db->prepare("delete from idevaff_deleted_accounts where id = ?");
$st->execute(array($_REQUEST['activate']));
$success_message = "<strong>Success!</strong> Account has been re-activated and is now in your <strong>Pending Approval</strong> list."; }


$leftSubActiveMenu = 'affiliates';
require("templates/header.php");

$accounts_declined = $db->query("SELECT COUNT(*) FROM idevaff_deleted_accounts");
?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Affiliates</li>
<li class="current"> <a href="accounts_declined.php">Declined Affiliate Accounts</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Declined Affiliate Accounts</h3><span>Affiliate accounts that have been declined and removed from the active system.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<?PHP if ($accounts_declined->fetchColumn()) { ?>

<div class="row">
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-file-alt"></i> Notes</h4></div>
<div class="widget-content">
All account information is preserved except traffic and commission data. Re-Activating an account will put the account back into the <strong>Pending Approval</strong> list for you to re-approve when you're ready.
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-user"></i> Account List</h4></div>
<div class="widget-content">
<table class="table table-striped table-bordered table-highlight-head valign" id="dyntable_declined">
<thead>
<tr>
<th class="head0">Affiliate ID</th>
<th class="head1">Username</th>
<th class="head0">Email</th>
<th class="head1">Action</th>
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
<div class="widget-header"><h4><i class="icon-user"></i> Declined Accounts</h4></div>
<div class="widget-content">
No declined accounts.
</div>
</div>
</div>
</div>

<?PHP } ?>

<?PHP include("templates/footer.php"); ?>