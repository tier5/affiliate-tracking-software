<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");
require_once '../API/stripe/config.php';

if (!function_exists('password_verify')) {
	require_once '../API/pass_lib.php';
}
if(!defined('SITE_KEY')) {
    require_once '../API/keys.php';
}

if (isset($_REQUEST['terminate'])) {
if (($staff_delete_accounts == 'off') && (!isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) {
	header("Location: staff_notice.php");
exit(); }
}

$clean_id = '0';
if (isset($_REQUEST['id'])) {
if (is_numeric($_REQUEST['id'])) {
$clean_id = $_REQUEST['id'];
} }


$leftSubActiveMenu = 'affiliates';
require("templates/header.php");

if (isset($_REQUEST['remove_pic'])) {
$getpicture = $db->prepare("select picture from idevaff_affiliates where id = ?");
$getpicture->execute(array($clean_id));
$getpicture = $getpicture->fetch();
$picturename = $getpicture['picture'];
if ($picturename != '') { unlink("../assets/pictures/" . $picturename); }
$st=$db->prepare("update idevaff_affiliates set picture = '' where id = ?");
$st->execute(array($clean_id));
$success_message = "<strong>Success!</strong> Picture removed."; }

if (isset($_POST['id_update'])) {
$custom_value = $_POST['custom_value'];
$id_update = $_POST['id_update'];
$custom_id = $_POST['custom_id'];

$st = $db->prepare("select * from idevaff_form_custom_data where custom_id = ?");
$st->execute(array($custom_id));
if (!$st->rowCount()) {
$st=$db->prepare("insert into idevaff_form_custom_data (affid, custom_id, custom_value) VALUES (?, ?, ?)");
$st->execute(array($clean_id,$custom_id,$custom_value));
} else {
$st=$db->prepare("update idevaff_form_custom_data set custom_value = ? where id = ?");
$st->execute(array($custom_value,$id_update));
}
$success_message = "<strong>Success!</strong> Custom field has been updated.";
}

if (isset($_POST['submit_data_6'])) {

if ($_POST['exp2'] == 1) { $estamp = $_POST['exp1'] * 60; }
if ($_POST['exp2'] == 2) { $estamp = $_POST['exp1'] * 60 * 60; }
if ($_POST['exp2'] == 3) { $estamp = $_POST['exp1'] * 60 * 60 * 24; }
if ($_POST['exp2'] == 4) { $estamp = $_POST['exp1'] * 60 * 60 * 24 * 365; }
$st=$db->prepare ("update idevaff_affiliates set vat_override = ?, expire = ?, expire_type = ?, expire_stamp = ? where id = ?");
if (isset($_POST['vat_override'])) { $vat_override = $_POST['vat_override']; } else { $vat_override = 0; }
if ((isset($_POST['cust_dur_reset']) && $_POST['cust_dur_reset'] == '1')) { $estamp = 0; }
$st->execute(array($vat_override,$_POST['exp1'],$_POST['exp2'],$estamp,$_POST['who']));
$success_message = "<strong>Success!</strong> Account information updated.";
}

if (isset($_POST['submit_data_1'])) {
$st=$db->prepare ("update idevaff_affiliates set username = ?, payable = ?, f_name = ?, company = ?, l_name = ?, email = ?, address_1 = ?, address_2 = ?, city = ?, state = ?, zip = ?, country = ?, phone = ?, fax = ?, url = ?, vat_override = ? where id = ?");
if (isset($_POST['vat_override'])) { $vat_override = $_POST['vat_override']; } else { $vat_override = 0; }
$st->execute(array($_POST['username'],$_POST['payable'],$_POST['f_name'],$_POST['company'],$_POST['l_name'],$_POST['email'],$_POST['address_one'],$_POST['address_two'],$_POST['city'],$_POST['state'],$_POST['zip'],$_POST['country'],$_POST['phone'],$_POST['fax'],$_POST['url'],$vat_override,$_POST['who']));

if ($_POST['password'] != '') {
    $new_pass = $_POST['password'];
    $user_key = substr(strtr(base64_encode(sha1(microtime(true), true)), '+', '.'), 0, 22); //store this to database as user_key 
    $new_pass = password_hash(SITE_KEY . $new_pass . $user_key, PASSWORD_BCRYPT) ;

    $sql = "UPDATE `idevaff_affiliates` set `user_key`=?, `password`=? WHERE id=?";
    $q = $db->prepare($sql);
    $q->execute(array($user_key,$new_pass,$_POST['who']));
}
$tax = $_POST['tax_id_ssn'];
if ($tax != '') {
$st=$db->prepare("update idevaff_affiliates set tax_id_ssn = (AES_ENCRYPT(?, '" . AUTH_KEY . "')) where id = ?");
$st->execute(array($tax,$_POST['who']));
}
$success_message = "<strong>Success!</strong> Account information updated.";
}

if (isset($_POST['submit_data_2'])) {
if (is_numeric($_POST['submit_data_2'])) {
if ($_POST['old_type'] != $_POST['new_type']) {
$setme = 1;
$warning_message = "<strong>Warning!</strong> Payout level has been reset to level 1.  If you're ok with this, you're done. If not, please adjust the payout level now.";
} else {
$setme = $_POST['level'];
}
if (is_numeric($_POST['new_type'])) {
if ($_POST['new_type'] == '1') { $allowtype = "a1"; }
if ($_POST['new_type'] == '2') { $allowtype = "a2"; }
if ($_POST['new_type'] == '3') { $allowtype = "a3"; }
$st=$db->prepare ("update idevaff_affiliates set type = ?, level = ?, " . $allowtype . " = '1' where id = ?");
$st->execute(array($_POST['new_type'],$setme,$_POST['who']));
$success_message = "<strong>Success!</strong> Commission payout settings updated.";
} } }

if (isset($_POST['submit_data_3'])) {
$st=$db->prepare ("update idevaff_affiliates set paypal = ? where id = ?");
$st->execute(array($_POST['pp_account'],$_POST['who']));
$success_message = "<strong>Success!</strong> Paypal payments updated.";
}

if (isset($_POST['submit_datas_4'])) {
    $delete_stripe = isset($_POST['delete_stripe_account']) ? $_POST['delete_stripe_account'] : null;
    if($delete_stripe === 'delete_stripe') {
        try {
            $tokens = array();
            $tokens = base64_encode(serialize($tokens));
            $st=$db->prepare ("update idevaff_affiliates set stripe_user_data  = ? where id = ?");
            $st->execute(array($tokens,$_POST['who']));
            $success_message = "<strong>Success!</strong> Stripe Account Deleted.";
        } catch (Exception $ex) {
            $fail_message = $ex->getMessage();
        }
    }
}

if (isset($_POST['submit_data_5'])) {
$st=$db->prepare ("update idevaff_affiliates set pay_method = ? where id = ?");
$st->execute(array($_POST['current_method'],$_POST['who']));
$success_message = "<strong>Success!</strong> Payment method updated.";
if (($_POST['current_method'] == '1') || ($_POST['current_method'] == '2')) {
$insert = null;
if ($_POST['current_method'] == '1') { $insert = "PayPal"; } elseif ($_POST['current_method'] == '2') { $insert = "Stripe"; }
$warning_message = "<strong>Warning!</strong> Please take a moment to enter the " . $insert . " account below.";
}
}

$alldata=$db->prepare("select * from idevaff_affiliates where id = ?");
$alldata->execute(array($clean_id));
$indv_data=$alldata->fetch();
$uname=$indv_data['username'];
$upass=$indv_data['password'];
$picture=$indv_data['picture'];
$payto=$indv_data['payable'];
$company=$indv_data['company'];
$ufname=$indv_data['f_name'];
$ulname=$indv_data['l_name'];
$uemail=$indv_data['email'];
$ad1=$indv_data['address_1'];
$ad2=$indv_data['address_2'];
$c=$indv_data['city'];
$s=$indv_data['state'];
$z=$indv_data['zip'];
$coun=$indv_data['country'];
$phone=$indv_data['phone'];
$fax=$indv_data['fax'];
$url=$indv_data['url'];
$pp=$indv_data['pp'];
$approved=$indv_data['approved'];
$suspended=$indv_data['suspended'];
$hits=$indv_data['hits_in'];
$vat_override=$indv_data['vat_override'];
$hits = number_format($hits);
$signup_date = $indv_data['signup_date'];
if ($signup_date > 0) {
$signup_date = date('m-d-Y', $signup_date);
} else {
$signup_date = "N/A"; }

$pay_method=$indv_data['pay_method'];
$get_pay_name = $db->prepare("SELECT name from idevaff_payment_methods where id = ?");
$get_pay_name->execute(array($pay_method));
$get_pay_name = $get_pay_name->fetch();
$pay_name = $get_pay_name['name'];

$ip = $indv_data['ip'];
if ($ip < 1) {
$ip = "N/A"; }

//------------

$get_tax = $db->prepare("SELECT AES_DECRYPT(tax_id_ssn, '" . AUTH_KEY . "') AS decrypted FROM idevaff_affiliates where id = ?");
$get_tax->execute(array($clean_id));
$get_tax = $get_tax->fetch();
$utax = $get_tax['decrypted'];

$sales1 = $db->prepare("select record from idevaff_sales where id = ? and approved = '1' and top_tier_tag = '0' and bonus = '0'"); 
$sales1->execute(array($clean_id));
$sales1 = $sales1->rowCount();
$sales2 = $db->prepare("select record from idevaff_archive where id = ? and top_tier_tag = '0' and bonus = '0'"); 
$sales2->execute(array($clean_id));
$sales2 = $sales2->rowCount();
$sales = $sales1 + $sales2;
$sales = number_format($sales);

$tsales1 = $db->prepare("select record from idevaff_sales where id = ? and approved = '1' and top_tier_tag = '1' and bonus = '0'"); 
$tsales1->execute(array($clean_id));
$tsales1 = $tsales1->rowCount();
$tsales2 = $db->prepare("select record from idevaff_archive where id = ? and top_tier_tag = '1' and bonus = '0'"); 
$tsales2->execute(array($clean_id));
$tsales2 = $tsales2->rowCount();
$tsales = $tsales1 + $tsales2;
$tsales = number_format($tsales);

$osales1 = $db->prepare("select record from idevaff_sales where id = ? and approved = '1' and override = '1' and bonus = '0'"); 
$osales1->execute(array($clean_id));
$osales1 = $osales1->rowCount();
$osales2 = $db->prepare("select record from idevaff_archive where id = ? and override = '1' and bonus = '0'"); 
$osales2->execute(array($clean_id));
$osales2 = $osales2->rowCount();
$osales = $osales1 + $osales2;
$osales = number_format($osales);

$earnings1 = $db->prepare("select SUM(amount) AS total from idevaff_payments where id = ?"); 
$earnings1->execute(array($clean_id));
$row1 =  $earnings1->fetch();
$sexact = $row1['total'];
$sexactd = number_format($sexact, $decimal_symbols);
$earnings2 = $db->prepare("select SUM(payment) AS total from idevaff_sales where id = ? and approved = '1'"); 
$earnings2->execute(array($clean_id));
$row2 =  $earnings2->fetch();
$pexact = $row2['total'];
$pexactd = number_format($pexact, $decimal_symbols);
$totalsales = $sexact + $pexact;
$totalsales = number_format($totalsales, $decimal_symbols);

$appsales = $db->prepare("select id from idevaff_sales where id = ? and approved = '0'"); 
$appsales->execute(array($clean_id));
$salestotal = $appsales->rowCount();
$salestotal = number_format($salestotal, $decimal_symbols);

$ipstat = $db->prepare("select COUNT(DISTINCT ip) from idevaff_iptracking where acct_id = ?");
$ipstat->execute(array($clean_id));
$unique = number_format($ipstat->fetchColumn());

$bonu = $db->prepare("select bonus from idevaff_sales where id = ? and bonus = '1' and approved = '1'"); 
$bonu->execute(array($clean_id));
$bon = $bonu->rowCount();
$level=$indv_data['level'];
$levperc = $db->prepare("select amt from idevaff_paylevels where level = ?");
$levperc->execute(array($level));
$getlevperc = $levperc->fetch();
$percpay = $getlevperc['amt'];
$percpay = $percpay * 100;
if (($paytype == 1) || ($paytype == 3)) { $plt = "per sale"; } elseif ($paytype == 2) { $plt = "per click"; }
if ($approved == 1) { $stat = "Approved"; } else { $stat = "<font color=\"#CC0000\">Pending Approval</font>"; }

$ipstat_total = $db->prepare("select COUNT(ip) from idevaff_iptracking where acct_id = ?");
$ipstat_total->execute(array($clean_id));
$hits = number_format($ipstat_total->fetchColumn());

?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Affiliates</li>
<li class="current"> <a href="account_details.php?id=<?PHP echo $clean_id; ?>" title="">Account Details</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Account Details<span>Affiliate ID #<?PHP echo html_output($clean_id); ?>.</span></h3>
</div>

<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<?PHP if (isset($_REQUEST['suspend'])) { ?>
<div class="alert alert-warning">
<h4><strong>Suspend This Account</strong></h4>
This action will suspend the account. Once suspended, the affiliate will no longer have access to the affiliate control panel.<br /><br />
<form class="form-horizontal row-border" method="post" action="user_access.php">
<input type="hidden" name="suspend" value="<?PHP echo html_output($clean_id); ?>">
<input type="hidden" name="activity" value="1">
<button class="btn btn-warning">Suspend This Account</button>
</form>
</div>
<?PHP } if (isset($_REQUEST['terminate'])) { ?>
<div class="alert alert-danger">
<h4><strong>Terminate This Account</strong></h4>
If this is <u>not</u> a Facebook created account, this action will send the account to the <strong>Declined Accounts</strong> list. If needed, you can permanently remove this account from that page. If this is a Facebook created account, this termination function will be permanent right now as there is no way to preserve these types of accounts.<br /><br />
<form class="form-horizontal row-border" method="post" action="accounts_declined.php">
<input type="hidden" name="terminate" value="<?PHP echo html_output($clean_id); ?>">
<button class="btn btn-danger">Terminate This Account</button>
</form>
</div>
<?PHP } ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Account Snapshot</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Edit</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Commission &amp; Payment</a></li>
<li <?php makeActiveTab(6);?>><a href="#tab_1_6" data-toggle="tab">Account Overrides</a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Notes</a></li>
<li <?php makeActiveTab(5);?>><a href="#tab_1_5" data-toggle="tab">Custom Fields</a></li>

<li>
<?PHP if ($suspended > '0') { ?>
<div class="btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-warning dropdown-toggle">Currently Suspended <span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="user_access.php?tab=2&unid=<?PHP echo $clean_id;?>">Un-Suspend Account</a></li>
<li class="divider"></li>
<li><a href="account_details.php?id=<?PHP echo $clean_id; ?>&terminate=1">Terminate This Account</a></li>
</ul>
</div>
<?PHP } else { if ($approved == '0') { ?>
<div class="btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-danger dropdown-toggle">Pending Approval <span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="accounts_pending.php?decision=1&id=<?PHP echo $clean_id; ?>">Approve Account</a></li>
<li class="divider"></li>
<li><a href="accounts_pending.php?decision=2&id=<?PHP echo $clean_id; ?>">Decline Account</a></li>
<li><a href="accounts_pending.php?decision=3&id=<?PHP echo $clean_id; ?>">Decline Account w/ Email Notification</a></li>
</ul></div>
<?PHP } else { ?>
<div class="btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-primary dropdown-toggle">Currently Approved <span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="account_details.php?cfg=35&id=<?PHP echo $clean_id; ?>">Un-Approve Account</a></li>
<li><a href="account_details.php?id=<?PHP echo $clean_id; ?>&suspend=1">Suspend Account</a></li>
<li class="divider"></li>
<li><a href="account_details.php?id=<?PHP echo $clean_id; ?>&terminate=1">Terminate This Account</a></li>
</ul></div>
<?PHP } } ?>
</li>
</ul>

<div class="tab-content">


<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">

<div class="col-md-12">
<div class="row">
<div class="col-md-2">

<div class="widget box" style="margin-top:20px;" align="center">
<div class="widget-content">
<?PHP if ($picture == '') { ?>
<img src="images/default_image.jpg">
<?PHP } else { ?>
<img src="../assets/pictures/<?PHP echo $picture; ?>">
<?PHP } ?>
</div>
</div>
<?PHP if ($picture != '') { ?>
<p style="text-align:center;">
<a href="account_details.php?remove_pic=true&id=<?PHP echo $clean_id; ?>"><button class="btn btn-sm btn-default">Remove Picture</button></a>
</p>
<?PHP } ?>
</div>

<div class="col-md-5">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-user"></i> Account Details</h4><span class="pull-right"><a href="../login.php?override_login=1&override_id=<?PHP echo $clean_id; ?>&secret_key=<?PHP echo $secret; ?>" target="_blank"><button class="btn btn-sm btn-info">Login As Affiliate</button></a></span></div>
<div class="widget-content">
<table class="table table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td>Name</td>
<td><?PHP echo html_output($ufname); ?> <?PHP echo html_output($ulname); ?></td>
</tr>
<tr>
<td>Username</td>
<td><font color="#CC0000"><?PHP echo html_output($uname); ?></font></td>
</tr>
<tr>
<td>Email Address</td>
<td style="padding:3px 0 0 5px;"><?PHP if ($uemail) { ?><a href="mailto:<?PHP echo html_output($uemail); ?>" class="btn btn-sm btn-success">Direct Email</a> <a href="email_affiliates.php?id=<?PHP echo $clean_id; ?>" class="btn btn-sm btn-success">Internal Email</a><?PHP } else { echo "N/A"; } ?></td>
</tr>
<tr>
<td>Website</td>
<td style="padding:3px 0 0 5px;"><?PHP if (($url) && ($url != '') && ($url != 'http://')) { ?><a href="<?PHP echo $url; ?>" target="_blank" class="btn btn-default btn-sm">Visit Website</a><?PHP } else { ?>N/A<?PHP } ?></td>
</tr>
<tr>
<td>Phone Number</td>
<td><?PHP if ($phone) { echo html_output($phone); } else { echo "N/A"; } ?></td>
</tr>
<tr>
<td>IP Address At Signup</td>
<td><?PHP echo html_output($ip); ?></td>
</tr>
<tr>
<td>Signup Date</td>
<td><?PHP echo html_output($signup_date); ?></td>
</tr>
<tr>
<td>Payment Method</td>
<td><?PHP echo $pay_name; ?></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

<div class="col-md-5">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-signal"></i> Account Activity</h4><span class="pull-right"><a href="report.php?view=1&id=<?PHP echo $clean_id; ?>" target="_blank"><button class="btn btn-sm btn-default"><i class="icon-print"></i> Printable Version</button></a></span></div>
<div class="widget-content">

<table class="table table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td style="padding:3px 8px; line-height: 28px;">Total Hits In<a href="traffic_logs.php?id=<?PHP echo html_output($clean_id); ?>" class="btn btn-danger btn-sm pull-right">Traffic Log</a></td>
<td align="right"><?PHP echo html_output($hits); ?></td>
</tr>
<tr>
<td>Unique Hits In</td>
<td align="right"><?PHP echo html_output($unique); ?></td>
</tr>
<tr>
<td>Commissions</td>
<td align="right"><?PHP echo html_output($sales); ?></td>
</tr>
<tr>
<td>Tier Commissions</td>
<td align="right"><?PHP echo html_output($tsales); ?></td>
</tr>
<tr>
<td>Override Commissions</td>
<td align="right"><?PHP echo html_output($osales); ?></td>
</tr>
<tr>
<td>Current Commissions</td>
<td align="right"><?PHP if($cur_sym_location == 1) { echo html_output($cur_sym); } echo html_output($pexactd); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
</tr>
<tr>
<td>Paid Commissions</td>
<td align="right"><?PHP if($cur_sym_location == 1) { echo html_output($cur_sym); } echo html_output($sexactd); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
</tr>
<tr>
<td><strong>Total Commissions</strong></td>
<td align="right"><strong><?PHP if($cur_sym_location == 1) { echo html_output($cur_sym); } echo html_output($totalsales); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></strong></td>
</tr>
</tbody>
</table>							
							
</div>
</div>
</div>
</div>
</div>

<div class="col-md-12">
<div class="row">
<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-bar-chart"></i> Last 30 Days Activity</h4></div>
<div class="widget-content">
							<div class="widget-content">
								<div id="chart_filled_red" class="chart"></div>
							</div>
							<div class="divider"></div>
							<div class="widget-content">
								<ul class="stats">
									<li>
										<strong id="month_aff_traffic"></strong>
										<small>Traffic Last 30 Days</small>
									</li>
									<li>
										<strong id="month_aff_commissions"></strong>
										<small>Commissions Last 30 Days</small>
									</li>
								</ul>
							</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-bar-chart"></i> Lifetime Activity</h4></div>
<div class="widget-content">


							<div class="widget-content">
								<div id="chart_filled_green" class="chart"></div>
							</div>
							<div class="divider"></div>
							<div class="widget-content">
								<ul class="stats"> <!-- .no-dividers -->
									<li class="light">
										<strong id="life_aff_traffic"></strong>
										<small>Total Traffic</small>
									</li>
									<li class="light">
										<strong id="life_aff_commissions"></strong>
										<small>Total Commissions</small>
									</li>

								</ul>
							</div>
</div>
</div>
</div>
</div>
</div>
</div>


<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">
<?PHP
$alldata=$db->prepare("select * from idevaff_affiliates where id = ?");
$alldata->execute(array($clean_id));
$indv_data=$alldata->fetch();
$username=$indv_data['username'];
$password=$indv_data['password'];
$payable=$indv_data['payable'];
$company=$indv_data['company'];
$f_name=$indv_data['f_name'];
$l_name=$indv_data['l_name'];
$email=$indv_data['email'];
$address_one=$indv_data['address_1'];
$address_two=$indv_data['address_2'];
$city=$indv_data['city'];
$state=$indv_data['state'];
$zip=$indv_data['zip'];
$country=$indv_data['country'];
$phone=$indv_data['phone'];
$fax=$indv_data['fax'];
$url=$indv_data['url'];
$pp=$indv_data['pp'];
$paypal=$indv_data['paypal'];
$stripe_user_data=$indv_data['stripe_user_data'];
$atype=$indv_data['type'];
$alevel=$indv_data['level'];
$get_tax = $db->prepare("SELECT AES_DECRYPT(tax_id_ssn, '" . AUTH_KEY . "') AS decrypted FROM idevaff_affiliates where id = ?");
$get_tax->execute(array($clean_id));
$get_tax = $get_tax->fetch();
$tax_id_ssn = $get_tax['decrypted'];

?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Edit Account</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="account_details.php">
<div class="form-group">
<label class="col-md-3 control-label">Username</label>
<div class="col-md-4"><input type="text" name="username" class="form-control" value="<?PHP echo $username; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">New Password</label>
<div class="col-md-4"><input type="text" name="password" class="form-control" value=""><span class="help-block">Leave blank to keep existing password.</span></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">First Name</label>
<div class="col-md-4"><input type="text" name="f_name" class="form-control" value="<?PHP echo $f_name; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Last Name</label>
<div class="col-md-4"><input type="text" name="l_name" class="form-control" value="<?PHP echo $l_name; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Email Address</label>
<div class="col-md-6"><input type="text" name="email" class="form-control" value="<?PHP echo $email; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Website Address</label>
<div class="col-md-6"><input type="text" name="url" class="form-control" value="<?PHP if ($url) { echo $url; } else { echo "http://"; } ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Street Address</label>
<div class="col-md-9"><input type="text" name="address_one" class="form-control" value="<?PHP echo $address_one; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Street Address 2</label>
<div class="col-md-9"><input type="text" name="address_two" class="form-control" value="<?PHP echo $address_two; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">City</label>
<div class="col-md-4"><input type="text" name="city" class="form-control" value="<?PHP echo $city; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">State</label>
<div class="col-md-2"><input type="text" name="state" class="form-control" value="<?PHP echo $state; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Zip Code</label>
<div class="col-md-2"><input type="text" name="zip" class="form-control" value="<?PHP echo $zip; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Country</label>

<div class="col-md-9"><select class="form-control input-width-xxlarge" name="country">
<?PHP
$get_countries = $db->query("select * from idevaff_countries order by country_name"); 
if ($get_countries->rowCount()) {
while ($qry = $get_countries->fetch()) {
echo "<option value=\"" . $qry['country_code'] . "\"";
if ($country == $qry['country_code']) { echo " selected"; }
echo ">" . $qry['country_name'] . "</option>\n"; } }
?>
</select>
</div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Phone</label>
<div class="col-md-2"><input type="text" name="phone" class="form-control" value="<?PHP echo $phone; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Fax</label>
<div class="col-md-2"><input type="text" name="fax" class="form-control" value="<?PHP echo $fax; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Company</label>
<div class="col-md-4"><input type="text" name="company" class="form-control" value="<?PHP echo $company; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Checks Payable To</label>
<div class="col-md-4"><input type="text" name="payable" class="form-control" value="<?PHP echo $payable; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Tax ID/SSN/VAT</label>
<div class="col-md-4"><input type="text" name="tax_id_ssn" class="form-control" value="<?PHP echo $tax_id_ssn; ?>"></div>
</div>

<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="submit_data_1" value="1">
<input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id;?>">
<input type="hidden" name="old_type" value="<?PHP echo $atype; ?>">
<input type="hidden" name="tab" value="2">
</form>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">

<div class="col-md-12">
<div class="row">

<?PHP
$alldata_pay=$db->prepare("select pay_method from idevaff_affiliates where id = ?");
$alldata_pay->execute(array($clean_id));
$indv_data_pay=$alldata_pay->fetch();
$getpayid=$indv_data_pay['pay_method'];
$getpayname=$db->prepare("select name from idevaff_payment_methods where id = ?");
$getpayname->execute(array($getpayid));
$getpayname_result=$getpayname->fetch();
$getpayname_result=$getpayname_result['name'];
?>

<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-money"></i> Payment Method</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="account_details.php">
<div class="form-group">
<label class="col-md-6 control-label">Change Payment Method</label>
<div class="col-md-6"><select name="current_method" class="form-control">
<?PHP
$available_payment_methods = $db->query("select * from idevaff_payment_methods where enabled = '1' order by name");
if ($available_payment_methods->rowCount()) {
while ($qry_methods = $available_payment_methods->fetch()) {
?>
<option value="<?PHP echo $qry_methods['id']; ?>"<?PHP if ($qry_methods['id'] == $getpayid) { ?> selected="selected"<?PHP } ?>><?PHP echo html_output($qry_methods['name']); ?></option>
<?PHP } } ?>
</select><br /><span class="help-block">Currently Selected: <?PHP
if ($getpayid == '0') {
echo "<font color='#cc0000'>ERROR - Nothing selected.</font><br />Please select something now.";
} else {
echo html_output($getpayname_result);
}
?></span></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="submit_data_5" value="1">
<input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id;?>">
<input type="hidden" name="tab" value="3">
</form>
</div>
</div>
</div>

<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-money"></i> Commission Payout Settings</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="account_details.php">
<div class="form-group">
<label class="col-md-6 control-label">Commission Style</label>
<div class="col-md-6"><select name="new_type" class="form-control">
<?PHP if ($ap_1 == 1) { ?><option value="1"<?PHP if ($atype == 1) { echo " selected=\"selected\""; } ?>>Percentage Payout</option><?PHP } ?>
<?PHP if ($ap_2 == 1) { ?><option value="2"<?PHP if ($atype == 2) { echo " selected=\"selected\""; } ?>>Flat Rate Payout</option><?PHP } ?>
<?PHP if ($ap_3 == 1) { ?><option value="3"<?PHP if ($atype == 3) { echo " selected=\"selected\""; } ?>>Pay-Per-Click</option><?PHP } ?>
</select></div>
</div>
<div class="form-group">
<label class="col-md-6 control-label"<?PHP if ((isset($_POST['new_type'])) && ($_POST['old_type'] != $_POST['new_type'])) { ?> style="color:#CC0000;"<?PHP } ?>>Payout Level</label>
<div class="col-md-6"><select name="level" class="form-control">
<?PHP if ($alevel == 0) { print "<option value='0'>Select A Payout Level</option>"; }
$getlevels = $db->prepare("select * from idevaff_paylevels where type = ? order by level");
$getlevels->execute(array($atype));
if ($getlevels->rowCount()) {
while ($qry = $getlevels->fetch()) {
$lev_lev=$qry['level'];
$lev_pay=$qry['amt'];
if ($atype == 1) {
$ext = "%";
$lev_pay = $lev_pay * 100;
$pre = "";
print "<option value='$lev_lev'"; if ($lev_lev == $alevel) { print " selected"; } echo ">Level: " . $lev_lev . " - " . $pre . $lev_pay . $ext . "</option>";
} else {
$lev_pay = number_format($lev_pay,$decimal_symbols);
if($cur_sym_location == 1) { $lev_pay = $cur_sym . $lev_pay; }
if($cur_sym_location == 2) { $lev_pay = $lev_pay . " " . $cur_sym; }
$lev_pay = $lev_pay . " $currency";
if ($lev_lev == $alevel) { print " selected"; }
print "<option value='$lev_lev'"; if ($lev_lev == $alevel) { print " selected"; } echo ">Level: " . $lev_lev . " - " . $lev_pay . "</option>"; } } }
?></select></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="submit_data_2" value="1">
<input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id;?>">
<input type="hidden" name="old_type" value="<?PHP echo $atype; ?>">
<input type="hidden" name="tab" value="3">
</form>
</div>
</div>
</div>

</div>
</div>

<?PHP
if ($getpayid != '0') {
if ($getpayid == '1') {
?>
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-money"></i> Paypal Payment Information</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="account_details.php">
<div class="form-group">
<label class="col-md-3 control-label">Paypal Account</label>
<div class="col-md-9"><input type="text" name="pp_account" class="form-control" value="<?PHP echo $paypal; ?>"><?PHP if ($paypal == '') { ?><span class="help-block" style="color:#CC0000;">A Paypal account is required.</span><?PHP } ?></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="submit_data_3" value="1">
<input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id;?>">
<input type="hidden" name="tab" value="3">
</form>
</div>
</div>
</div>
<?PHP } elseif ($getpayid == '2') { ?>
<?php 
//check stripe account here
$stripe_user_data = unserialize(base64_decode($indv_data['stripe_user_data']));
?>    
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header"><h4><i class="icon-money"></i> Stripe Payment Information</h4></div>
            <div class="widget-content">
			        <?php if(is_array($stripe_user_data) && !empty($stripe_user_data) && $stripe_user_data['access_token'] != '') { ?>
                <form class="form-horizontal row-border" id="stripe_account_edit_form" method="post" action="account_details.php">
	                
					<div class="form-group">
                        <label class="col-md-3 control-label">Stripe Account</label>
                        <div class="col-md-9">Stripe account is connected, you can make stripe payment for this account.</div>
                    </div>
					<div class="form-group">
					<label class="col-md-3 control-label">Delete This Account ?</label>
                        <div class="col-md-9">
                            <input type="checkbox" name="delete_stripe_account" value="delete_stripe">
                        </div>
                    </div>
                    <br style="clear:both;">
                    <div class="form-actions">
                        <input type="submit" value="Save Settings" class="btn btn-primary">
                    </div>
                    <input type="hidden" name="submit_datas_4" value="1">
                    <input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
                    <input type="hidden" name="id" value="<?PHP echo $clean_id; ?>">
                    <input type="hidden" name="tab" value="3">
                </form>
                            <?php } else { ?>
							<div class="col-md-12">
                                <font color="#CC0000"><strong>No Payment Can Be Made</strong><br />Stripe account is not connected. Please have affiliate login and edit their account to connect a stripe account.</font>
                            </div>
							<?php } ?>
            </div>
                             </div>
                    </div>
        <script type="text/javascript">
            $('body').on('click', '#delete_stripe_account', function(){
                if($(this).is(':checked')) {
                    if(confirm('Are you sure you want to delete this account ?')) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                }
            });
        </script>
   
<?PHP } else { ?>
<div class="col-md-12">
<div class="widget box">
<div class="widget-header"><h4><i class="icon-money"></i> <?PHP echo html_output($getpayname_result); ?> Payment Information</h4></div>
<div class="widget-content">
<?PHP
if ($getpayid == '3') {
echo "No settings are required for this payment option. You will just complete the account crediting manually then mark the commission payment as \"archived\" like normal.";
} elseif ($getpayid == '4') {
echo "No settings are required for this payment option. You will send the check/money order then mark the commission payment as \"archived\" like normal.";
} elseif ($getpayid == '5') {
echo "No settings are required for this payment option. You will send the wire transfer then mark the commission payment as \"archived\" like normal.<br /><br /><font color='#CC0000'>Special Note: </font>Due to security, PCI regulations and liability reasons, banking information is not stored in your database. To make payment via wire transfer, you will need to contact your affiliate directly to obtain this information.";
} else {
echo "No settings are available for this payment option.";
}
?>
</div>
</div>
</div>
<?PHP } } ?>

</div>

<div class="tab-pane<?php makeActiveTab(6, 'no');?>" id="tab_1_6">
<?PHP
$alldata=$db->prepare("select expire, expire_type, expire_stamp from idevaff_affiliates where id = ?");
$alldata->execute(array($clean_id));
$indv_data=$alldata->fetch();
$ex1_indi=$indv_data['expire'];
$ex2_indi=$indv_data['expire_type'];
$ex_stamp=$indv_data['expire_stamp'];

if ($ex_stamp == '0') { $ex1_indi = $ex1; }
if ($ex_stamp == '0') { $ex2_indi = $ex2; }

?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Individual Account Overrides</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="account_details.php">

<div class="well">These are individual account overrides and will override your <strong>global</strong> settings.</div>

<div class="form-group">
<label class="col-md-3 control-label">Disable VAT</label>
<div class="col-md-4"><label class="checkbox-inline"><input type="checkbox" name="vat_override" value="1"<?PHP if($vat_override=='1'){?> checked="checked"<?PHP } ?> /></label></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Customer Tracking Duration</label>
<div class="col-md-9"><input style="display:inline-block" type="text" name="exp1" value="<?PHP echo html_output($ex1_indi); ?>" class="form-control input-width-small"> 
<select style="display: inline-block" class="form-control input-width-small" name="exp2">
<option value="1" <?PHP if ($ex2_indi=='1'){?> selected="selected"<?PHP } ?>>Minutes</option>
<option value="2" <?PHP if ($ex2_indi=='2'){?> selected="selected"<?PHP } ?>>Hours</option>
<option value="3" <?PHP if ($ex2_indi=='3'){?> selected="selected"<?PHP } ?>>Days</option>
<option value="4" <?PHP if ($ex2_indi=='4'){?> selected="selected"<?PHP } ?>>Years</option></select> <span class="help-block">Set to 50 years for lifetime.</span>
<input type="checkbox" name="cust_dur_reset" value="1" /> Tick for default setting.
</div>
</div>

<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="submit_data_6" value="1">
<input type="hidden" name="who" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id;?>">
<input type="hidden" name="old_type" value="<?PHP echo $atype; ?>">
<input type="hidden" name="tab" value="6">
</form>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-comment-alt"></i> Affiliate Notes</h4><span class="pull-right"><a href="notes.php?note_to=<?PHP echo $clean_id; ?>" class="btn btn-primary btn-sm">Create A New Note</a></span></div>
<div class="widget-content">

<?PHP
$getnotes = $db->prepare("select * from idevaff_notes where note_to = ? and note_attach = '1' order by id DESC"); 
$getnotes->execute(array($clean_id));
if ($getnotes->rowCount()) {
while ($qry = $getnotes->fetch()) {
$edit_sub = stripslashes($qry['note_sub']);
$edit_con = stripslashes($qry['note_con']);
$edit_date = $qry['note_date'];
$note_image = $qry['note_image'];
$note_image_location = $qry['note_image_location'];

if ($note_image != '') {
list($width, $height, $type, $attr) = getimagesize("../assets/note_images/" . $note_image);
$draw_image = "<img src=\"../assets/note_images/" . $note_image . "\" width=\"" . $width . "\" height=\"" . $height . "\" border=\"0px;\" />"; }

?>

<table class="table table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td>Date Created: <?PHP echo html_output($edit_date); ?>
</td>
</tr>
<tr>
<td><h4><?PHP echo html_output($edit_sub); ?></h4>
<?PHP
if ($note_image == '') {
echo html_output($edit_con);
} else {
if ($note_image_location == '0') {
echo $draw_image . "<p style='margin-top:10px;'>" . html_output($edit_con) . "</p>";
} elseif ($note_image_location == '1') {
echo "<p style='margin-top:10px;'>" . html_output($edit_con) . "</p>" . $draw_image; } }
?>
</td>
</tr>
</tbody>
</table>

<?PHP } } else { ?>
No notes have been created for this affiliate.
<?PHP } ?>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(5, 'no');?>" id="tab_1_5">

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-text-file-alt"></i> Custom Fields</h4></div>
<div class="widget-content">

<?PHP
$getcustomrows = $db->query("select id, title from idevaff_form_fields_custom");
if ($getcustomrows->rowCount()) {
?>
<table class="table table-striped table-bordered table-highlight-head">
<tbody>
<?PHP
while ($qry = $getcustomrows->fetch()) {
$group_id = $qry['id'];
$custom_title = $qry['title'];
$getvars = $db->prepare("select * from idevaff_form_custom_data where custom_id = ? and affid = ?");
$getvars->execute(array($group_id,$clean_id));
$getvars = $getvars->fetch();
$entry_id = $getvars['id'];
$custom_id = $getvars['custom_id'];
$custom_value = $getvars['custom_value'];
?>
<form class="form-horizontal row-border" method="post" action="account_details.php">
<tr>
<td>
<?PHP echo html_output($custom_title); ?>
</td>
<td>
<input type="hidden" name="custom_id" value="<?PHP echo $group_id; ?>">
<input type="hidden" name="id" value="<?PHP echo $clean_id; ?>">
<input type="hidden" name="id_update" value="<?PHP echo $entry_id; ?>">
<input type="hidden" name="tab" value="5">
<input type="text" name="custom_value" size="20" value="<?PHP echo $custom_value; ?>" class="form-control input-width-xxlarge" style="display:inline-block;" /> <input  style="display:inline-block;" type="submit" class="btn btn-primary" value="Edit"></td>
</tr>
</form>
<?PHP } ?>
</tbody>
</table>
<?PHP } else { ?>
No custom fields created.
<?PHP } ?>
</div>
</div>
</div>

</div>
</div>


<?PHP include("templates/footer.php"); ?>