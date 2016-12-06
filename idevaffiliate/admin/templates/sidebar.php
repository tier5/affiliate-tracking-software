<?PHP

// TOTAL NUMBER AFFILIATES PENDING
$query	= $db->query("select count(*) as num_affs_pending from idevaff_affiliates where approved = '0'");
$aff_num_pending=$query->fetch();
$affcount_pending = $aff_num_pending['num_affs_pending'];

// TOTAL NUMBER AFFILIATES APPROVED
$query	= $db->query("select count(*) as num_affs_approved from idevaff_affiliates where approved = '1'");
$aff_num_approved=$query->fetch();
$affcount_approved = $aff_num_approved['num_affs_approved'];

// TOTAL NUMBER AFFILIATES DECLINED
$query	= $db->query("select count(*) as num_affs_declined from idevaff_deleted_accounts");
$aff_num_declined=$query->fetch();
$affcount_declined = $aff_num_declined['num_affs_declined'];

// TOTAL NUMBER COMMISSIONS APPROVED
$query	= $db->query("select count(*) as num_comms_approved from idevaff_sales where bonus != '1' and approved = '1'");
$comm_num_approved=$query->fetch();
$commcount_approved = $comm_num_approved['num_comms_approved'];

// TOTAL NUMBER COMMISSIONS PENDING
$query	= $db->query("select count(*) as num_comms_pending from idevaff_sales where bonus != '1' and approved = '0' and delay = '0'");
$comm_num_pending=$query->fetch();
$commcount_pending = $comm_num_pending['num_comms_pending'];

// TOTAL NUMBER COMMISSIONS DELAYED
$query	= $db->query("select count(*) as num_comms_delayed from idevaff_sales where approved = '0' and delay > '0'");
$comm_num_delayed=$query->fetch();
$commcount_delayed = $comm_num_delayed['num_comms_delayed'];

// TOTAL NUMBER COMMISSIONS DECLINED
$query	= $db->query("select count(*) as num_comms_declined from idevaff_deleted_sales");
$comm_count_declined=$query->fetch();
$commcount_declined = $comm_count_declined['num_comms_declined'];

// TOTAL NUMBER DEBITS PENDING
$query	= $db->query("select count(*) as num_debs_pending from idevaff_debit");
$debs_num_pending=$query->fetch();
$debcount_pending = $debs_num_pending['num_debs_pending'];

// TOTAL NUMBER DEBITS SETTLED
$query	= $db->query("select count(*) as num_debs_settled from idevaff_debit_archive");
$debs_num_settled=$query->fetch();
$debcount_settled = $debs_num_settled['num_debs_settled'];

$query	= $db->query("select count(*) as num_affs from idevaff_affiliates");
$aff_num=$query->fetch();
$affcount = $aff_num['num_affs'];

$query	= $db->query("select count(*) as num_comms from idevaff_sales where bonus != '1'");
$comm_num=$query->fetch();
$commcount = $comm_num['num_comms'];

?>

<div id="sidebar" class="sidebar-fixed">
<div id="sidebar-content">

<?PHP if (defined('CLOUD')) { ?>
<div style="margin-top:20px; text-align:center;"><a href="setup.php?action=85"><button class="btn btn-sm btn-blue-login">Manage My Cloud Account</button></a></div>
<?PHP } ?>

<?PHP if ($qsg_box == '1') { ?>
<div class="sidebar-widget align-center" style="margin-right:18px; padding-bottom:5px; color:#FFFFFF; background:#144a9c; background-image:url('images/qsg_back.png'); border:#000000 solid 1px; min-height:102px; height:auto !important; height:102px;">
<div style="padding-top:7px;">
<span class="title"><p style="margin-bottom:0px; font-weight:600; font-size:16px;">Quick Setup Guide</p><span style="font-size:14px"><?PHP echo $side_bar_progress_number; ?>% Complete</span></span>
</div>
<div class="progress progress-mini progress-striped active align-center" style="padding:0px; width:80%;">
<span class="progress-bar progress-bar-<?PHP echo $side_bar_progress; ?>" style="padding:0px; width: <?PHP echo $side_bar_progress_number; ?>%;"></span>
</div>
<div style="padding:10px;">
<a href="help.php"><button class="btn btn-xs btn-default">Quick Setup Guide</button></a> 
</div>
</div>
<?PHP } elseif ($video_sidebar == '1') { ?>
<div class="sidebar-widget align-center" style="margin-right:18px; padding-bottom:10px; color:#FFFFFF; background:#144a9c; background-image:url('images/vid_back.png'); border:#000000 solid 1px; min-height:102px; height:auto !important; height:102px;">
<div style="padding-top:7px;">
<span class="title"><p style="margin-bottom:0px; font-weight:600; font-size:16px;">Affiliate Training Videos</p><p style="font-size:12px">Status: 
<?PHP if (($stamped_date == '50000') || ($stamped_date == '60000') || ($stamped_date == '100000') || ($days_available > 0)) { ?>
Currently Active
<?PHP } else { ?>
Not Active
<?PHP } ?>
</p></span>
</div>
<div style="margin-top:5px">
<a href="setup.php?action=5"><button class="btn btn-xs btn-default">
<?PHP if (($stamped_date == '50000') || ($stamped_date == '60000') || ($stamped_date == '100000') || ($days_available > 0)) { ?>
Manage Videos
<?PHP } else { ?>
Learn More
<?PHP } ?>
</button></a> 
</div>
</div>
<?PHP } ?>

<ul id="nav">

<?PHP if (($staff_print_reports == 'on') || (isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { ?>
<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'reports')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Reports</a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'reports')) echo ' style="display:block;"';?>>
<li><a href="reports.php?report=6"><i class="icon-angle-right"></i> Daily Report</a></li>
<li><a href="reports.php?report=7"><i class="icon-angle-right"></i> Trends Report</a></li>
<li><a href="reports.php?report=4"><i class="icon-angle-right"></i> Top Affiliates</a></li>
<li><a href="reports.php?report=5"><i class="icon-angle-right"></i> Top Referring URLs</a></li>
<li><a href="reports.php?report=1"><i class="icon-angle-right"></i> T&amp;C Report</a></li>
<li><a href="reports.php?report=3"><i class="icon-angle-right"></i> Commissions</a></li>
<li><a href="reports.php?report=8"><i class="icon-angle-right"></i> Marketing Statistics</a></li>
</ul>
</li>
<?PHP } ?>

<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'affiliates')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Affiliates<span class="label label-primary pull-right"><?PHP echo number_format($affcount); ?></span></a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'affiliates')) echo ' style="display:block;"';?>>
<li><a href="accounts_approved.php"><i class="icon-angle-right"></i> Approved Accounts<span class="label label-success pull-right"><?PHP echo number_format($affcount_approved); ?></span></a></li>
<li><a href="accounts_pending.php"><i class="icon-angle-right"></i> Pending Accounts<span class="label label-warning pull-right"><?PHP echo number_format($affcount_pending); ?></span></a></li>
<li><a href="accounts_declined.php"><i class="icon-angle-right"></i> Declined Accounts<span class="label label-danger pull-right"><?PHP echo number_format($affcount_declined); ?></span></a></li>
<li><a href="manage_tiers.php"><i class="icon-angle-right"></i> Tiers</a></li>
<li><a href="notes.php"><i class="icon-angle-right"></i> Notes</a></li>
<li><a href="email_affiliates.php"><i class="icon-angle-right"></i> Email</a></li>
<li><a href="user_access.php"><i class="icon-angle-right"></i> User Access</a></li>
<li><a href="logos.php"><i class="icon-angle-right"></i> Logos</a></li>
<li><a href="testimonials.php"><i class="icon-angle-right"></i> Testimonials</a></li>
</ul>
</li>

<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'commissions')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Commissions<span class="label label-primary pull-right"><?PHP echo number_format($commcount); ?></span></a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'commissions')) echo ' style="display:block;"';?>>
<li><a href="commissions_pending.php"><i class="icon-angle-right"></i> Pending Approval<span class="label label-danger pull-right"><?PHP echo number_format($commcount_pending); ?></span></a></li>
<li><a href="commissions_approved.php"><i class="icon-angle-right"></i> Currently Approved<span class="label label-success pull-right"><?PHP echo number_format($commcount_approved); ?></span></a></li>
<li><a href="commissions_delayed.php"><i class="icon-angle-right"></i> Delayed<span class="label label-warning pull-right"><?PHP echo number_format($commcount_delayed); ?></span></a></li>
<li><a href="commissions_declined.php"><i class="icon-angle-right"></i> Declined<span class="label label-default pull-right"><?PHP echo number_format($commcount_declined); ?></span></a></li>
<li><a href="recurring.php"><i class="icon-angle-right"></i> Recurring</a></li>
<li><a href="create_commission.php"><i class="icon-angle-right"></i> Add A Commission</a></li>
</ul>
</li>

<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'debits')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Debits</a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'debits')) echo ' style="display:block;"';?>>
<li><a href="debits.php"><i class="icon-angle-right"></i> Pending Debits<span class="label label-danger pull-right"><?PHP echo number_format($debcount_pending); ?></span></a></li>
<li><a href="debits_settled.php"><i class="icon-angle-right"></i> Settled Debits<span class="label label-success pull-right"><?PHP echo number_format($debcount_settled); ?></span></a></li>
<li><a href="add_debit.php"><i class="icon-angle-right"></i> Add A Debit</a></li>
</ul>
</li>

<li><a href="traffic_logs.php"><i class="icon-chevron-sign-right"></i>Traffic Log</a></li>

<?PHP if (($staff_pay_affiliates == 'on') || (isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { ?>
<li><a href="payout_method.php"><i class="icon-chevron-sign-right"></i>Pay Affiliates</a></li>
<?PHP } ?>

<?PHP if (($staff_marketing_materials == 'on') || (isset($_SESSION[$install_directory_name.'_idev_AdminAccount']))) { ?>
<li><a href="groups.php"><i class="icon-chevron-sign-right "></i>Marketing Groups</a></li>

<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'marketing')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Marketing Materials</a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'marketing')) echo ' style="display:block;"';?>>
<li><a href="social_media.php"><i class="icon-angle-right"></i> Social Media Campaigns</a></li>
<li><a href="banners.php"><i class="icon-angle-right"></i> Banners</a></li>
<li><a href="videos.php"><i class="icon-angle-right"></i> Videos</a></li>
<li><a href="peels.php"><i class="icon-angle-right"></i> Page Peels</a></li>
<li><a href="lightboxes.php"><i class="icon-angle-right"></i> Lightboxes</a></li>
<li><a href="textads.php"><i class="icon-angle-right"></i> Text Ads</a></li>
<li><a href="text_links.php"><i class="icon-angle-right"></i> Text Links</a></li>
<li><a href="html_templates.php"><i class="icon-angle-right"></i> HTML Templates</a></li>
<li><a href="email_templates.php"><i class="icon-angle-right"></i> Email Templates</a></li>
<li><a href="pdf_marketing.php"><i class="icon-angle-right"></i> PDF Documents</a></li>
<li><a href="qr_codes.php"><i class="icon-angle-right"></i> QR Codes</a></li>
</ul>
</li>

<?PHP } ?>

<li<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'training')) echo ' class="open"';?>>
<a href="javascript:void(0);">
<i class="icon-plus-sign-alt"></i> Training Materials</a>
<ul class="sub-menu"<?php if ((isset($leftSubActiveMenu)) && ($leftSubActiveMenu == 'training')) echo ' style="display:block;"';?>>
<li><a href="pdf_training.php"><i class="icon-angle-right"></i> PDF Documents</a></li>
<li><a href="video_tutorials.php"><i class="icon-angle-right"></i> YouTube &amp; Vimeo Videos</a></li>
</ul>
</li>





</ul>

<?PHP if (($video_sidebar == '1') && ($qsg_box == '1')) { ?>
<div class="sidebar-widget align-center" style="margin-right:18px; padding-bottom:10px; color:#FFFFFF; background:#144a9c; background-image:url('images/vid_back.png'); border:#000000 solid 1px; min-height:102px; height:auto !important; height:102px;">
<div style="padding-top:7px;">
<span class="title"><p style="margin-bottom:0px; font-weight:600; font-size:16px;">Affiliate Training Videos</p><p style="font-size:12px">Status: 
<?PHP if (($stamped_date == '50000') || ($stamped_date == '60000') || ($stamped_date == '100000') || ($days_available > 0)) { ?>
Currently Active
<?PHP } else { ?>
Not Active
<?PHP } ?>
</p></span>
</div>
<div style="margin-top:5px">
<a href="setup.php?action=5"><button class="btn btn-xs btn-default">
<?PHP if (($stamped_date == '50000') || ($stamped_date == '60000') || ($stamped_date == '100000') || ($days_available > 0)) { ?>
Manage Videos
<?PHP } else { ?>
Learn More
<?PHP } ?>
</button></a> 
</div>
</div>
<?PHP } ?>

<?PHP
$query	= $db->query("SHOW COLUMNS from idevaff_config LIKE 'admin_theme'");
//$check4theme = $db->query("SHOW COLUMNS from idevaff_config LIKE 'admin_theme'");
if ($query->rowCount()) {
?>  
<div class="sidebar-widget align-center">
<div class="btn-group">
<a href="<?php echo html_output($_SERVER['PHP_SELF']); ?>?admin_theme=light" class="btn btn-sm btn-default"><i class="icon-sun"></i> Light</a>
<a href="<?php echo html_output($_SERVER['PHP_SELF']); ?>?admin_theme=dark" class="btn btn-sm btn-default"><i class="icon-moon"></i> Dark</a>
</div>
</div>
<?PHP } ?>
				
<div class="sidebar-widget align-center <?php echo $theme_added; ?>">
<div style="padding-top:8px;"><span class="title"><p style="margin-bottom:0px; font-size:14px"><a href="http://www.idevdirect.com/" target="_blank">Affiliate Software</a></p></span></div>
<div style="padding-top:0px;">Version <?PHP echo $version; ?></div>
<div style="padding-top:7px;">Copyright &copy; 1999-<?PHP echo date("Y"); ?><br />iDevDirect LLC</div>
</div>

</div>
<div id="divider"></div>
</div>


<div id="content">
<div class="container crumbFix">