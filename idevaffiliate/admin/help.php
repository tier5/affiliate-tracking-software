<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");
include("templates/header.php");

if (DEBUG_LEVEL > 0) { $debug_mode = 1; } else { $debug_mode = 0; }

if (isset($_POST['checkfordebug'])) {

$my_file = "../API/debug.php";
if (file_exists($my_file)) { unlink($my_file); }
$handle = @fopen($my_file, 'a');
$data_to_write = "<?PHP

// Basic function for outputting errors to screen.  Designed to
// be very basic in case you want to add your own functionality.
// --------------------------------------------------------------

 define('DEBUG_LEVEL', '". $_POST['debug_mode'] . "');

 switch(DEBUG_LEVEL) {
 case 0: error_reporting (0); break;
 case 1: error_reporting(E_ERROR | E_WARNING | E_PARSE); break;
 case 2: error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); break;
 case 3: error_reporting(E_ALL ^ (E_NOTICE | E_WARNING)); break;
 case 4: error_reporting(E_ALL ^ E_NOTICE); break;
 case 5: error_reporting(E_ALL); break;
 
 default:
 error_reporting(E_ALL);
   
}

?>";
@fwrite($handle, $data_to_write);

	// POSTED, OVERRIDE DEFINITIONS ABOVE
	// ----------------------------------------------------
	if ($_POST['debug_mode'] > 0) { $debug_mode = 1; } else { $debug_mode = 0; }
	
}




?>
<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li class="current"> <a href="help.php">iDevAffiliate Help</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>iDevAffiliate Help</h3><span>All the information needed to operate your affiliates program!</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("includes/notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Quick Setup Guide</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">System Info</a></li>
<li><a href="http://help.idevaffiliate.com/" target="_blank">Knowledge Base</a></li>
<li><a href="http://help.idevaffiliate.com/faq/" target="_blank">FAQ</a></li>
<li><a href="http://help.idevaffiliate.com/videos/" target="_blank">Support Videos</a></li>
</ul>

<div class="tab-content">

<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">


<div class="col-md-8">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-cog"></i> Quick Setup Guide</h4>
<span class="pull-right">
<?PHP if ($qsg_box == '0') { ?>
<a href="help.php?qsg_status=on&cfg_special=true"><button class="btn btn-inverse btn-sm">Show Quick Setup Guide Widget</button></a>
<?PHP } elseif ($qsg_box == '1') { ?>
<a href="help.php?qsg_status=off&cfg_special=true"><button class="btn btn-inverse btn-sm">Hide Quick Setup Guide Widget</button></a>
<?PHP } ?>
</span>
</div>
<div class="widget-content">

<?PHP

if ($qsg_step_1 == '1') { $check_qsg_status_button_1 = "success"; $qsg_action_1 = 0; } elseif ($qsg_step_1 == '0') { $check_qsg_status_button_1 = "danger"; $qsg_action_1 = 1; }
if ($qsg_step_2 == '1') { $check_qsg_status_button_2 = "success"; $qsg_action_2 = 0; } elseif ($qsg_step_2 == '0') { $check_qsg_status_button_2 = "danger"; $qsg_action_2 = 1; }
if ($qsg_step_3 == '1') { $check_qsg_status_button_3 = "success"; $qsg_action_3 = 0; } elseif ($qsg_step_3 == '0') { $check_qsg_status_button_3 = "danger"; $qsg_action_3 = 1; }
if ($qsg_step_4 == '1') { $check_qsg_status_button_4 = "success"; $qsg_action_4 = 0; } elseif ($qsg_step_4 == '0') { $check_qsg_status_button_4 = "danger"; $qsg_action_4 = 1; }
if ($qsg_step_5 == '1') { $check_qsg_status_button_5 = "success"; $qsg_action_5 = 0; } elseif ($qsg_step_5 == '0') { $check_qsg_status_button_5 = "danger"; $qsg_action_5 = 1; }
if ($qsg_step_6 == '1') { $check_qsg_status_button_6 = "success"; $qsg_action_6 = 0; } elseif ($qsg_step_6 == '0') { $check_qsg_status_button_6 = "danger"; $qsg_action_6 = 1; }
if ($qsg_step_7 == '1') { $check_qsg_status_button_7 = "success"; $qsg_action_7 = 0; } elseif ($qsg_step_7 == '0') { $check_qsg_status_button_7 = "danger"; $qsg_action_7 = 1; }
if ($qsg_step_8 == '1') { $check_qsg_status_button_8 = "success"; $qsg_action_8 = 0; } elseif ($qsg_step_8 == '0') { $check_qsg_status_button_8 = "danger"; $qsg_action_8 = 1; }
if ($qsg_step_9 == '1') { $check_qsg_status_button_9 = "success"; $qsg_action_9 = 0; } elseif ($qsg_step_9 == '0') { $check_qsg_status_button_9 = "danger"; $qsg_action_9 = 1; }
if ($qsg_step_10 == '1') { $check_qsg_status_button_10 = "success"; $qsg_action_10 = 0; } elseif ($qsg_step_10 == '0') { $check_qsg_status_button_10 = "danger"; $qsg_action_10 = 1; }

if ($qsg_step_1 == '1') { $check_qsg_status_1 = "Completed!"; } elseif ($qsg_step_1 == '0') { $check_qsg_status_1 = "I've Done This"; }
if ($qsg_step_2 == '1') { $check_qsg_status_2 = "Completed!"; } elseif ($qsg_step_2 == '0') { $check_qsg_status_2 = "I've Done This"; }
if ($qsg_step_3 == '1') { $check_qsg_status_3 = "Completed!"; } elseif ($qsg_step_3 == '0') { $check_qsg_status_3 = "I've Done This"; }
if ($qsg_step_4 == '1') { $check_qsg_status_4 = "Completed!"; } elseif ($qsg_step_4 == '0') { $check_qsg_status_4 = "I've Done This"; }
if ($qsg_step_5 == '1') { $check_qsg_status_5 = "Completed!"; } elseif ($qsg_step_5 == '0') { $check_qsg_status_5 = "I've Done This"; }
if ($qsg_step_6 == '1') { $check_qsg_status_6 = "Completed!"; } elseif ($qsg_step_6 == '0') { $check_qsg_status_6 = "I've Done This"; }
if ($qsg_step_7 == '1') { $check_qsg_status_7 = "Completed!"; } elseif ($qsg_step_7 == '0') { $check_qsg_status_7 = "I've Done This"; }
if ($qsg_step_8 == '1') { $check_qsg_status_8 = "Completed!"; } elseif ($qsg_step_8 == '0') { $check_qsg_status_8 = "I've Done This"; }
if ($qsg_step_9 == '1') { $check_qsg_status_9 = "Completed!"; } elseif ($qsg_step_9 == '0') { $check_qsg_status_9 = "I've Done This"; }
if ($qsg_step_10 == '1') { $check_qsg_status_10 = "Completed!"; } elseif ($qsg_step_10 == '0') { $check_qsg_status_10 = "I've Done This"; }

?>
<div class="progress-stats" style="padding:0px 10px 0 10px;">
<span class="title"><p style="margin-bottom:0px; font-weight:600;"><?PHP echo html_output($side_bar_progress_number); ?>% Complete</p></span>
<div class="progress progress-striped active">
<div class="progress-bar progress-bar-<?PHP echo html_output($side_bar_progress); ?>" style="width: <?PHP echo html_output($side_bar_progress_number); ?>%;"></div>
</div>
</div>

<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
    <tr>
      <td width="5%" align="center">1.</td>
      <td width="95%">General Settings<span class="pull-right"><a href="setup.php?action=1"><button class="btn btn-xs btn-primary" style="width:125px;">START HERE</button></a> <a href="help.php?update_step=1&cfg_special=1&status=<?PHP echo html_output($qsg_action_1); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_1); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_1); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">2.</td>
      <td width="95%">Localization Settings<span class="pull-right"><a href="setup.php?action=54"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=2&cfg_special=1&status=<?PHP echo html_output($qsg_action_2); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_2); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_2); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">3.</td>
      <td width="95%">Email Settings<span class="pull-right"><a href="setup.php?action=31"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=3&cfg_special=1&status=<?PHP echo html_output($qsg_action_3); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_3); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_3); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">4.</td>
      <td width="95%">Payment Settings<span class="pull-right"><a href="setup.php?action=35"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=4&cfg_special=1&status=<?PHP echo html_output($qsg_action_4); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_4); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_4); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">5.</td>
      <td width="95%">Create Commission Structure<span class="pull-right"><a href="setup.php?action=4"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=5&cfg_special=1&status=<?PHP echo html_output($qsg_action_5); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_5); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_5); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">6.</td>
      <td width="95%">Create Tier Commission Structure<span class="pull-right"><a href="setup.php?action=36"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=6&cfg_special=1&status=<?PHP echo html_output($qsg_action_6); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_6); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_6); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">7.</td>
      <td width="95%">Edit Email Templates<span class="pull-right"><a href="setup.php?action=6"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=7&cfg_special=1&status=<?PHP echo html_output($qsg_action_7); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_7); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_7); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">8.</td>
      <td width="95%">Create Terms &amp; Conditions<span class="pull-right"><a href="setup.php?action=15"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=8&cfg_special=1&status=<?PHP echo html_output($qsg_action_8); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_8); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_8); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">9.</td>
      <td width="95%">Create FAQs<span class="pull-right"><a href="setup.php?action=21"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=9&cfg_special=1&status=<?PHP echo html_output($qsg_action_9); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_9); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_9); ?></button></a></span></td>
    </tr>
    <tr>
      <td width="5%" align="center">10.</td>
      <td width="95%">Shopping Cart/Billing System Integration<span class="pull-right"><a href="setup.php?action=10"><button class="btn btn-xs btn-default" style="width:125px;">Take Me There</button></a> <a href="help.php?update_step=10&cfg_special=1&status=<?PHP echo html_output($qsg_action_10); ?>"><button class="btn btn-xs btn-<?PHP echo html_output($check_qsg_status_button_10); ?>" style="width:125px;"><?PHP echo html_output($check_qsg_status_10); ?></button></a></span></td>
    </tr>
	</tbody>
  </table>
    </div>
	</div>
	</div>
	
<div class="col-md-4">

<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-play"></i> Popular How-To Items</h4></div>
<div class="widget-content">
<div class="row">
<div class="col-md-6">
<a href="#templates" class="btn btn-icon btn-warning input-block-level" data-toggle="modal">
<i class="icon-font icon-2x"></i> <i class="icon-edit icon-2x"></i> <i class="icon-picture icon-2x"></i><div style="white-space:normal;">Customize Language<br />and Templates</div></a>
</div>
<div class="col-md-6">
<a href="#duplicates" class="btn btn-icon btn-primary input-block-level" data-toggle="modal">
<i class="icon-refresh icon-spin icon-2x"></i><div style="white-space:normal;">Preventing Duplicate Commissions</div></a>
</div>
</div>
</div>
</div>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-play"></i> Let's Get Started!</h4></div>
<div class="widget-content">
<div class="video-container">
<iframe src="//player.vimeo.com/video/85588431" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>
</div>

</div>

<div class="modal fade" id="templates">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Customizing the Look and Feel of Your Affiliate Panel</h4>
</div>
<div class="modal-body">
<div>Modifications to your affiliate control panel are done in the <i>Templates</i> folder above.</div>
<br />
<div class="row">
<div class="col-md-4">
<a href="setup.php?action=33" class="btn btn-warning btn-sm btn-block">Edit Language Templates</a>
</div>
<div class="col-md-4">
<a href="setup.php?action=6" class="btn btn-warning btn-sm btn-block">Edit Email Templates</a>
</div>
<div class="col-md-4">
<a href="setup.php?action=34" class="btn btn-warning btn-sm btn-block">Edit Signup Form Fields</a>
</div>
</div>
<br />
<div class="row">
<div class="col-md-4">
<a href="setup.php?action=9" class="btn btn-warning btn-sm btn-block">Theme/Logo/Colors</a>
</div>
<div class="col-md-4">
<a href="setup.php?action=21" class="btn btn-warning btn-sm btn-block">FAQ</a>
</div>
<div class="col-md-4">
<a href="setup.php?action=15" class="btn btn-warning btn-sm btn-block">Terms and Conditions</a>
</div>
</div>
<br />
<div class="video-container">
<iframe src="//player.vimeo.com/video/85580507" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="duplicates">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Preventing Duplicate Commissions</h4>
</div>
<div class="modal-body">
<div>We recommend leaving this feature disabled while testing. Enable this feature just before you officially launch your affiliate program to the public.</div>
<br />
<div class="video-container">
<iframe src="//player.vimeo.com/video/114788456" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>
<div class="modal-footer" style="text-align:center;">
<a href="setup.php?action=53"><button type="button" class="btn btn-primary">Take Me To This Feature</button></a>
<a href="http://help.idevaffiliate.com/preventing-duplicate-commissions/" target="_blank"><button type="button" class="btn btn-danger">View Knowledge Base Article</button></a>
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

</div>
				
<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">

<?PHP if ($install_date == '0') { $install_date = "N/A"; } else { $install_date = date("m-d-Y", $install_date); } ?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-question-sign"></i> System Information</h4></div>
<div class="widget-content">
<table class="table table-striped">
<tbody>
    <tr>
      <td>iDevAffiliate License</td>
      <td><?PHP echo html_output($license); ?></td>
    </tr>
    <tr>
      <td>Install Date</td>
      <td><?PHP echo html_output($install_date); ?></td>
    </tr>
    <tr>
      <td>License Type</td>
      <td>Owned - Valid For 1 Domain</td>
    </tr>
    <tr>
      <td>License Expires</td>
      <td>Never</td>
    </tr>
    <tr>
      <td>iDevAffiliate Version</td>
      <td>Version: <?PHP echo html_output($version); ?></td>
    </tr>
</tbody>
</table>
</div>
</div>
</div>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-gear"></i> General Utilities</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="help.php">
<?PHP if (is_writable("../API/")) { ?>
<div class="form-group">
<label class="col-md-3 control-label">Debug Mode</label>
<div class="col-md-9"><select class="form-control input-width-medium" name="debug_mode">
<option value="0" <?PHP if ($debug_mode == 0) { ?> selected <?PHP } ?>>Disabled</option>
<option value="5" <?PHP if ($debug_mode == 1) { ?> selected <?PHP } ?>>Enabled</option>
</select><span class="help-block">For troubleshooting only. This will enable error output.</span></div>
</div>
<input type="hidden" name="checkfordebug" value="1">
<?PHP } ?>
<div class="form-group">
<label class="col-md-3 control-label">Maintenance Mode</label>
<div class="col-md-9"><select class="form-control input-width-medium" name="maint_mode">
<option value="0" <?PHP if ($maint_mode == 0) { ?> selected <?PHP } ?>>Disabled</option>
<option value="1" <?PHP if ($maint_mode == 1) { ?> selected <?PHP } ?>>Enabled</option>
</select><span class="help-block">Enabling this option will disable signups in your affiliate program.</span></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Settings" class="btn btn-primary">
</div>
<input type="hidden" name="cfg" value="105">
<input type="hidden" name="topic" value="4">
<input type="hidden" name="tab" value="3">

</form>
</div>
</div>
</div>

</div>


</div>
</div>

				
				
				
				
				
				
<?PHP include("templates/footer.php"); ?>