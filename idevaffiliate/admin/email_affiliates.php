<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");

if (($staff_contact_affiliates == 'off') && (!isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { header("Location: staff_notice.php"); exit(); }

if (isset($_REQUEST['id'])) {
	$email_action = 1;
} else {
	$email_action = 2;
}

if (isset($_POST['send_email'])) {
	if (isset($_REQUEST['id'])) {
		$email_aff_id = $_REQUEST['id'];
		$affdet = $db->prepare("select username from idevaff_affiliates where id = ?");
		$affdet->execute(array($email_aff_id));
		$affdet = $affdet->fetch();
		$affdet = $affdet['username'];
		if ($affdet) {
			if ($_POST['subject'] != '') {
				include($path . "/templates/email/mail.send_affiliate.php");
				$success_message = "<strong>Success!</strong> Email sent.";
			} else {
				$fail_message = "<strong>Error!</strong> Please enter a subject for your email.";
			}
		}
	} else {
		if ($_POST['subject'] != '') {
			include($path . "/templates/email/mail.send_bulk.php");
			$success_message = "<strong>Success!</strong> Email sent.";
		} else {
			$fail_message = "<strong>Error!</strong> Please enter a subject for your email.";
		}
	}
}

$leftSubActiveMenu = 'affiliates';
include ("templates/header.php");

$full_width_page = true;
?>
<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Affiliates</li>
<li class="current"> <a href="email_affiliates.php">Email Affiliates</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Email Affiliates</h3><span>Contact your affiliates, individually or in bulk.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>


<div class="widget box">
<div class="widget-header"><h4><i class="icon-envelope-alt"></i> Email Affiliates</h4></div>
<div class="widget-content">
<a href="templates/email-loading.php" class="hide" id="email_progress">&nbsp;</a>

<form class="form-horizontal row-border" method="post" action="email_affiliates.php" id="EmailForm">
<div class="form-group">
<label class="col-md-3 control-label">Send To</label>
<div class="col-md-4">

<?PHP if ($email_action == 1) { ?>
<select class="form-control" name="id">
<?PHP
$getnames = $db->query("select id, username from idevaff_affiliates order by id"); 
if ($getnames->rowCount()) {
while ($qry = $getnames->fetch()) {
$chid=$qry['id'];
$chuser=$qry['username'];
print "<option value='$chid'";
if ((isset($_REQUEST['id'])) && ($chid == $_REQUEST['id'])) { print ' selected'; }
print ">ID: " . $chid . " - Username: " . $chuser . "</option>\n"; } }
?>
</select>
<?PHP } else { ?>
<select class="form-control" name="choice">
<option value="all">All Affiliates</option>
<option value="approved">Only Approved Affiliates</option>
<option value="notapproved">Only Non-Approved Affiliates</option>
</select>
<?PHP } ?>
</div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Subject</label>
<div class="col-md-9"><input type="text" name="subject" class="form-control" /></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Message Body</label>
<div class="col-md-9"><textarea rows="6" name="message" class="form-control">Dear {first_name},

Write your message here.</textarea><span class="help-block">Your default email signature is auto-inserted for you.</span></div>
</div>


<div class="form-actions">
<input type="submit" value="Send Email Message" class="btn btn-primary <?php echo ($email_action == 2) ? "ajaxEmail" : "";?>"><span class="pull-right"><a href="setup.php?action=31" class="btn btn-mini">Edit Default Email Signature</a></span>
</div>
<input type="hidden" name="send_email" value="1">
</form>
</div>
</div>


<?PHP include("includes/tokens_email_templates.php"); ?>
<?PHP include("templates/footer.php"); ?>