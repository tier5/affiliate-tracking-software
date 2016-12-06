<?PHP
if (!defined('admin_includes')) { die(); }
include("session.check.php");
?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Cart Integration</li>
<li class="current"> <a href="setup.php?action=83" title="">Advanced Fraud Protection</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Advanced Fraud Protection</h3><span>Help prevent fraudulent commission triggering.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Advanced Fraud Protection</a></li>
<li><a data-toggle="modal" href="#video_tutorial"><i class="icon-play"></i> Video Tutorial</a></li>
</ul>

<div class="modal fade" id="video_tutorial">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Video Tutorial: Advanced Fraud Protection</h4>
</div>
<div class="modal-body">
<div class="video-container">
<iframe src="//player.vimeo.com/video/152043958" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="tab-content">


<div class="tab-pane<?php makeActiveTab(1, 'no');?>" id="tab_1_1">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-warning-sign"></i> Advanced Fraud Protection</h4></div>
<div class="widget-content">

<form class="form-horizontal row-border" method="post" action="setup.php">
<div class="alert alert-danger"><h4>Warning</h4>These settings should only be used if directed to by our support staff and/or you are confident you know how to use them. They are designed to secure the commission processing file from being triggered without authorization. If used wrong, these settings can prevent your system from creating valid commissions.</div>
<div class="form-group">
<label class="col-md-3 control-label">Require Profile ID <img src="images/yellow_flag.png" style="height:16px; width:16px; border:0px;"></label>
<div class="col-md-9"><select class="form-control input-width-large" name="protection_profile">
<option value="0" <?PHP if ($protection_profile == 0) { ?> selected="selected" <?PHP } ?>>No</option>
<option value="1" <?PHP if ($protection_profile == 1) { ?> selected="selected" <?PHP } ?>>Yes</option>
</select> <div class="help-block">Enabling this feature will require a valid (and active) cart profile ID to be passed in the tracking pixel code.<br /><br /><a href="setup.php?action=2">Check My Cart System For Compatibility</a></div></div> 
</div>
<div class="form-group">
<label class="col-md-3 control-label">Require Secret Key <img src="images/red_flag.png" style="height:16px; width:16px; border:0px;"></label>
<div class="col-md-9"><select class="form-control input-width-large" name="protection_secret_key">
<option value="0" <?PHP if ($protection_secret_key == 0) { ?> selected="selected" <?PHP } ?>>No</option>
<option value="1" <?PHP if ($protection_secret_key == 1) { ?> selected="selected" <?PHP } ?>>Yes</option>
</select> <div class="help-block">Enabling this feature will require your iDevAffiliate <a href="setup.php?action=44&tab=2">secret key</a> to be passed in the tracking pixel code.<br /><br />This feature should only be enabled if you are using a custom API style commission processing trigger (with cURL for example) - or one of the carts listed below. With a custom API style call, you'll need to take your <a href="setup.php?action=44&tab=2">secret key</a> and pass it in to the sale.php file using a POST or GET.<br /><br />Example: &idev_secret=[key here]<br /><br />Important: Do not use this with the standard tracking pixel that is output to the browser on a 'thank you' page. It will be viewable via page source. Only use this with an API style call.<br /><br />If you are using one of the following cart integrations, this feature is already built-in and will automatically pass your secret key for you with no modifications.
<br /><br />
<?PHP

$getdirect = $db->query("select name from idevaff_carts where protection_eligible = '1'"); 
while($row = $getdirect->fetch()){
echo "<span class=\"label label-primary\" style=\"margin-right:5px\">" . $row['name'] . "</span>";

}

?>
</div></div> 
</div>
<div class="form-actions">
<input type="submit" value="Save Setting" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="83">
<input type="hidden" name="cfg" value="152">
</form>
</div>
</div>
</div>
</div>

</div>
</div>







