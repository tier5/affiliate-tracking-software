<?PHP
if (!defined('admin_includes')) { die(); }
include("session.check.php");

if (isset($_POST['update_default'])) {
$pid = $_POST['lang_def'];
$db->query("update idevaff_language_packs set def = '0'");
$st = $db->prepare("update idevaff_language_packs set def = '1' where id = ?");
$st->execute(array($pid));

unset($_SESSION[$install_directory_name.'_admin_edit_language']);
unset($_SESSION[$install_directory_name.'_admin_edit_table']);
$success_message = "<strong>Success!</strong> Default language updated.";
}

if (isset($_REQUEST['rtl'])) {
$st = $db->prepare("update idevaff_language_packs set direction = ? where id = ?");
$st->execute(array($_REQUEST['rtl'],$_REQUEST['pack_id']));
$success_message = "<strong>Success!</strong> Settings updated.";
}

if (isset($_REQUEST['remove'])) {
$remove = $_REQUEST['remove'];
$get_table_name = $db->prepare("select table_name from idevaff_language_packs where id = ?");
$get_table_name->execute(array($remove));
$get_table_name = $get_table_name->fetch();
$table_to_remove = $get_table_name['table_name'];
$db->query("drop table if exists idevaff_language_" . $table_to_remove);
$st1 = $db->prepare("delete from idevaff_language_packs where id = ?");
$st1->execute(array($remove));
$db->query("ALTER TABLE idevaff_language_custom_values DROP pack_" . $remove);
unset($_SESSION[$install_directory_name.'_admin_edit_language']);
unset($_SESSION[$install_directory_name.'_admin_edit_table']);
$success_message = "<strong>Success!</strong> Language pack removed.";
}

if (isset($_POST['renamepackid'])) {
$pid = $_POST['renamepackid'];
$oldpackname = strtolower($_POST['oldpackname']);
function valid_input($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if((strlen($credential) >= 1) && (strlen($credential) <= 30)) {
if( !(preg_match("/[^[:space:]a-zA-Z]/i", $credential)) ) {
$rtn_value=true; } }
return $rtn_value; }
$newpackname = strtolower($_POST['newpackname']);
if(valid_input($newpackname)) {
$st = $db->prepare("update idevaff_language_packs set name = ? where id = ?");
$st->execute(array($newpackname,$pid));
unset($_SESSION[$install_directory_name.'_admin_edit_language']);
unset($_SESSION[$install_directory_name.'_admin_edit_table']);
$success_message = "<strong>Success!</strong> Language pack renamed.";
} else {
$fail_message = "<strong>Error!</strong> Language pack can only contain letters and must be 1 - 30 characters in length.";
} }

if (isset($_GET['option'])) {
$newstat = $_GET['status'];
$adjpack = $_GET['pack'];
$st  = $db->prepare("update idevaff_language_packs set status = ? where name = ?");
$st->execute(array($newstat,$adjpack));
$success_message = "<strong>Success!</strong> Language pack status changed."; }

// edit
if (isset($_POST['new_pack_add'])) {
$new_pack = strtolower($_POST['new_pack']);

$check_existing = $db->prepare("select id from idevaff_language_packs where table_name = ?");
$check_existing->execute(array($new_pack));
if ($check_existing->rowCount()) {
$fail_message = "<strong>Error!</strong> The pack name you're trying to use already exists.";
}

function valid_input($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) {
$credential = stripslashes($credential); }
if((strlen($credential) >= 1) && (strlen($credential) <= 30)) {
if( !(preg_match("/[^a-zA-Z]/i", $credential)) ) {
$rtn_value=true; } }
return $rtn_value; }

if(!valid_input($new_pack)) {
$fail_message = "<strong>Error!</strong> Language pack can only contain letters and must be 1 - 30 characters in length. If you need more than one word in the name, create it with one word then edit it.";
}

if ($_POST['new_pack'] == '') {
$fail_message = "<strong>Error!</strong> Please enter a language pack name.";
}

if ((!isset($fail_message)) && (valid_input($new_pack))) {
$st = $db->prepare("insert into idevaff_language_packs (name, status, def, table_name, user_created) VALUES (?, '1', '0', ?, '1')");
$st->execute(array($new_pack,$new_pack));
$db->query("CREATE TABLE IF NOT EXISTS idevaff_language_" . $new_pack . " LIKE idevaff_language_english");
$db->query("INSERT INTO idevaff_language_" . $new_pack . " SELECT * FROM idevaff_language_english");
$get_new_table_id = $db->prepare("select id from idevaff_language_packs where table_name = ?");
$get_new_table_id->execute(array($new_pack));
$get_new_table_id = $get_new_table_id->fetch();
$new_id = $get_new_table_id['id'];
$db->query("ALTER TABLE idevaff_language_custom_values ADD `pack_" . $new_id . "` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
unset($_SESSION[$install_directory_name.'_admin_edit_language']);
unset($_SESSION[$install_directory_name.'_admin_edit_table']);
$success_message = "<strong>Success!</strong> Language pack has been created.";
} }

if (isset($_POST['newtoken'])) {
$newtoken = $_POST['newtoken'];
$newtoken = strtolower($newtoken);
$newtoken = 'custom_'.$newtoken;
if ($newtoken != 'custom_') {
$checkname = $db->prepare("select name from idevaff_language_custom where name = ?");
$checkname->execute(array($newtoken));
if ($checkname->rowCount()) {
$fail_message = "<strong>Error!</strong> Token name already in use.";
} else {
function valid_token($credential) {
$rtn_value = false;
if (get_magic_quotes_gpc()) { $credential = stripslashes($credential); }
if(strlen($credential) > 0) {
if(!(preg_match("/[^a-z_]/i", $credential)) ) {
$rtn_value=true; } }
return $rtn_value; }
if(valid_token($newtoken)) {
$st = $db->prepare("insert into idevaff_language_custom (name) VALUES (?)");
$st->execute(array($newtoken));
$getlast = $db->query("select max(id) as highest from idevaff_language_custom");
$getlast = $getlast->fetch();
$lastlang = $getlast['highest'];
$db->query("insert into idevaff_language_custom_values (id) VALUES ($lastlang)");
$success_message = "<strong>Success!</strong> Token created. Be sure to edit the language content of your token below.";
} else {
$fail_message = "<strong>Error!</strong> Token name can only include letters and underscores with no spaces.";
} } } }

if (isset($_POST['deletetoken'])) {
$deletetoken = $_POST['deletetoken'];
$st = $db->prepare("delete from idevaff_language_custom where id = ?");
$st->execute(array($deletetoken));
$st = $db->prepare("delete from idevaff_language_custom_values where id = ?");
$st->execute(array($deletetoken));
$success_message = "<strong>Success!</strong> Token removed.";
}

if ((isset($_POST['edittoken'])) && (isset($_POST['newtext']))) {
$field = $_POST['field'];
$line = $_POST['edittoken'];
$new = $_POST['newtext'];
$st  = $db->prepare("update idevaff_language_custom_values set $field = ? where id = ?");
$st->execute(array($new,$line));
$success_message = "<strong>Success!</strong> Token language updated.";
$editcomplete = 1; }

if (!isset($_REQUEST['content'])) {
unset($_SESSION[$install_directory_name.'_admin_edit_language']);
unset($_SESSION[$install_directory_name.'_admin_edit_table']);
}


if (isset($_POST['idev_language'])) {

// Set table name
$_SESSION[$install_directory_name.'_admin_edit_table'] = $_POST['idev_language'];

// Set language name
$st = $db->prepare("select name from idevaff_language_packs where table_name = ?");
$st->execute(array($_POST['idev_language']));
$st->setFetchMode(PDO::FETCH_ASSOC);
$st_acura=$st->fetch();
$language_name_selected=$st_acura['name'];
$_SESSION[$install_directory_name.'_admin_edit_language'] = $language_name_selected;
}
/*
	echo "language: ";
	if (isset($_SESSION[$install_directory_name.'_admin_edit_language'])) { echo $_SESSION[$install_directory_name.'_admin_edit_language']; }
	echo "<br />";
	echo "table: ";
	if (isset($_SESSION[$install_directory_name.'_admin_edit_table'])) { echo $_SESSION[$install_directory_name.'_admin_edit_table']; }
	*/

?>

<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Templates</li>
<li class="current"> <a href="setup.php?action=73" title="">Language Templates</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Language Templates</h3><span>Change how things read in your affiliate control panel.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP

// -------------------------
// REMOVE LANGUAGES
// -------------------------
/*
	$check_for_languages = $db->query("select id, table_name, name, def from idevaff_language_packs");
	while ($qry = $check_for_languages->fetch()) {
	$file_to_check = $qry['table_name'] . ".php";
	if ($file_to_check != "english.php") {
	if (!file_exists('../includes/languages/' . $file_to_check)) {
	if ($qry['def'] == '1') {
	$db->query("update idevaff_language_packs set def = '1' where table_name = 'english'");
	}
	$st2 = $db->prepare("delete from idevaff_language_packs where id = ?");
	$st2->execute(array($qry['id']));
	$db->query("drop table if exists `idevaff_language_".$qry['table_name']."`");
	echo "<div class=\"alert alert-danger\"><span style=\"font-size:120%;\">Language Pack Notice</span><br />A language pack has been removed from the integration list: <b>" . ucfirst($qry['name']) . "</b></div>";
	}
	}
	}
*/
// -------------------------
// ADD NEW LANGUAGES
// -------------------------

if (!isset($_SESSION[$install_directory_name.'_admin_edit_table'])) {
	if ($handle = opendir("../includes/languages/")) {
    while (false !== ($entry = readdir($handle))) {
		
		$info = pathinfo($entry);
		
		if ($entry != "." && $entry != ".." && $entry != "english.php" && $info['extension'] == "php") {
		include('../includes/languages/' . $entry);
	} }
    closedir($handle);
	}
}

?>

<?PHP include("notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Edit Language Packs</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Create New Language Pack</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Manage Language Packs</a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Custom Language Tokens</a></li>
<li><a data-toggle="modal" href="#video_tutorial"><i class="icon-play"></i> Video Tutorial</a></li>
</ul>

<div class="modal fade" id="video_tutorial">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Video Tutorial: Language Templates</h4>
</div>
<div class="modal-body">
<div class="video-container">
<iframe src="//player.vimeo.com/video/85582483" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
<?PHP if ((!isset($_POST['idev_language'])) && (!isset($_REQUEST['content']))) { ?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Edit Language Templates</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">

<div class="well">There are no language packs for the Affiliate Training Videos. Affiliate Training Videos are only available in English. The Excel export report is also only available in English.</div>

<div class="form-group">
<label class="col-md-3 control-label">Choose A Language</label>
<div class="col-md-3"><select class="form-control" name="idev_language"><?PHP
$get_lang_packs = $db->query("select name, table_name from idevaff_language_packs ORDER BY name");
if ($get_lang_packs->rowCount()) {
while ($pack = $get_lang_packs->fetch()) {
$pack_value = $pack['table_name'];
$pack_name = ucwords($pack['name']);
echo "<option value='$pack_value'>$pack_name</option>\n"; } }
?>
</select></div>
</div>
<div class="form-actions">
<input type="submit" value="Edit Language Templates" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="lang_set_init" value="1">
</form>
</div>
</div>
</div>

<?PHP } ?>

<?PHP
if ((isset($_SESSION[$install_directory_name.'_admin_edit_table'])) || (isset($_REQUEST['content']))) {
if (isset($_REQUEST['content'])) { include("language_headings.php"); }
?>

<?PHP include("language_menu.php"); ?>

<?PHP if (isset($_REQUEST['content'])) { ?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><span class="label label-danger"><?PHP echo ucwords($_SESSION[$install_directory_name.'_admin_edit_language']); ?></span> Language Template: <?PHP echo html_output($template_name); ?></h4><span class="pull-right"><a href="setup.php?action=33" class="btn btn-warning btn-sm">Edit A Different Language</a></span></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<?PHP include("language_includes.php"); ?>
<div class="form-actions">
<input type="submit" value="Update Language" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="cfg_lang" value="<?PHP echo html_output($_REQUEST['content']); ?>">
<input type="hidden" name="content" value="<?PHP echo html_output($_REQUEST['content']); ?>">
<input type="hidden" name="idev_language" value="<?PHP echo html_output($_SESSION[$install_directory_name.'_admin_edit_table']); ?>">
</form>
</div>
</div>
</div>

<?PHP } else { ?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><span class="label label-danger"><?PHP echo ucwords($_SESSION[$install_directory_name.'_admin_edit_language']); ?></span> Language Template: Header Content</h4><span class="pull-right"><a href="setup.php?action=33" class="btn btn-warning btn-sm">Edit A Different Language</a></span></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<?PHP include("templates/language/general.header.php"); ?>
<div class="form-actions">
<input type="submit" value="Update Language" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="cfg_lang" value="1">
<input type="hidden" name="content" value="1">
<input type="hidden" name="idev_language" value="<?PHP echo html_output($_SESSION[$install_directory_name.'_admin_edit_table']); ?>">
</form>
</div>
</div>
</div>
<?PHP } } ?>

<?PHP
$get_packs = $db->query("select COUNT(*) from idevaff_language_packs where user_created = '0' and name != 'english'");
if ((!$get_packs->fetchColumn()) && (!isset($_POST['idev_language']))) {
?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Add More Language Packs + Create Your Own</h4><span class="pull-right"></span></div>
<div class="widget-content">
<div class="alert alert-warning">
The language packs plugin below is optional but is also required if you want to create your own language pack.</div>

<div class="row">
<div class="col-md-3">
<img src="images/module-language-200px.png" height="274" width="200" style="border:none; display:block; margin-left:auto; margin-right:auto;">
</div>

<div class="col-md-9">
<div class="well">Instantly add all of the language packs below to your affiliate control panel. These language packs have been professionally translated by human translators. This plugin also gives you the ability to create your own language pack in the tab above.</div>

<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td width="5%"><img src="images/geo_flags/fr.png" alt="French" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">French</td>
<td width="5%"><img src="images/geo_flags/nl.png" alt="Dutch" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="27%">Dutch</td>
<td width="5%"><img src="images/geo_flags/de.png" alt="German" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">German</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/es.png" alt="Spanish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Spanish</td>
<td width="5%"><img src="images/geo_flags/it.png" alt="Italian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Italian</td>
<td width="5%"><img src="images/geo_flags/pt.png" alt="Portuguese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Portuguese</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/jp.png" alt="Japanese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Japanese</td>
<td width="5%"><img src="images/geo_flags/cn.png" alt="Simplified Chinese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Simplified Chinese</td>
<td width="5%"><img src="images/geo_flags/cn.png" alt="Traditional Chinese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Traditional Chinese</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/ru.png" alt="Russian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Russian</td>
<td width="5%"><img src="images/geo_flags/fi.png" alt="Finnish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Finnish</td>
<td width="5%"><img src="images/geo_flags/il.png" alt="Hebrew" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Hebrew</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/hu.png" alt="Hungarian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Hungarian</td>
<td width="5%"><img src="images/geo_flags/kr.png" alt="Korean" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Korean</td>
<td width="5%"><img src="images/geo_flags/pl.png" alt="Polish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Polish</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/tr.png" alt="Turkish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Turkish</td>
<td width="5%"><img src="images/geo_flags/ua.png" alt="Ukranian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Ukranian</td>
<td width="5%"><img src="images/geo_flags/vn.png" alt="Vietnamese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Vietnamese</td>
</tr>
</tbody>
</table>

<a href="http://www.idevdirect.com/module_language.php" target="_blank" class="btn btn-primary" style="margin-top:10px;">Order This Plugin</a>
</div>
</div>

</div>
</div>
</div>

<?PHP } ?>

</div>

<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Create A Language Pack</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">

<div class="well">Your new language pack will have default English language in it. Once created, you can then translate it using the Edit Language Templates tab above.</div>

<div class="form-group">
<label class="col-md-3 control-label">Language Pack Name</label>
<div class="col-md-3">
<?PHP
$get_packs = $db->query("select COUNT(*) from idevaff_language_packs where user_created = '0' and name != 'english'");
if (!$get_packs->fetchColumn()) {
?>
<input type="text" name="" class="form-control" disabled />
<?PHP } else { ?>
<input type="text" name="new_pack" class="form-control" />
<?PHP } ?>
</div>
</div>

<?PHP
$get_packs = $db->query("select COUNT(*) from idevaff_language_packs where user_created = '0' and name != 'english'");
if (!$get_packs->fetchColumn()) {
?>
<div class="form-actions">
<input type="submit" value="Feature Not Available, Language Packs Plugin Required - See Below" class="btn btn-primary" disabled>
</div>

<?PHP } else { ?>
<div class="form-actions">
<input type="submit" value="Create Language Pack" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="tab" value="3">
<input type="hidden" name="new_pack_add" value="1">
<?PHP } ?>

</form>
</div>
</div>
</div>

<?PHP
$get_packs = $db->query("select COUNT(*) from idevaff_language_packs where user_created = '0' and name != 'english'");
if (!$get_packs->fetchColumn()) {
?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Add More Language Packs + Create Your Own</h4><span class="pull-right"></span></div>
<div class="widget-content">
<div class="alert alert-warning">
The language packs plugin below is optional but is also required if you want to create your own language pack.</div>

<div class="row">
<div class="col-md-3">
<img src="images/module-language-200px.png" height="274" width="200" style="border:none; display:block; margin-left:auto; margin-right:auto;">
</div>

<div class="col-md-9">
<div class="well">Instantly add all of the language packs below to your affiliate control panel. These language packs have been professionally translated by human translators. This plugin also gives you the ability to create your own language pack in the tab above.</div>

<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<tr>
<td width="5%"><img src="images/geo_flags/fr.png" alt="French" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">French</td>
<td width="5%"><img src="images/geo_flags/nl.png" alt="Dutch" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="27%">Dutch</td>
<td width="5%"><img src="images/geo_flags/de.png" alt="German" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">German</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/es.png" alt="Spanish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Spanish</td>
<td width="5%"><img src="images/geo_flags/it.png" alt="Italian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Italian</td>
<td width="5%"><img src="images/geo_flags/pt.png" alt="Portuguese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Portuguese</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/jp.png" alt="Japanese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Japanese</td>
<td width="5%"><img src="images/geo_flags/cn.png" alt="Simplified Chinese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Simplified Chinese</td>
<td width="5%"><img src="images/geo_flags/cn.png" alt="Traditional Chinese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"><td width="29%">Traditional Chinese</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/ru.png" alt="Russian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Russian</td>
<td width="5%"><img src="images/geo_flags/fi.png" alt="Finnish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Finnish</td>
<td width="5%"><img src="images/geo_flags/il.png" alt="Hebrew" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Hebrew</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/hu.png" alt="Hungarian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Hungarian</td>
<td width="5%"><img src="images/geo_flags/kr.png" alt="Korean" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Korean</td>
<td width="5%"><img src="images/geo_flags/pl.png" alt="Polish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Polish</td>
</tr>
<tr>
<td width="5%"><img src="images/geo_flags/tr.png" alt="Turkish" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Turkish</td>
<td width="5%"><img src="images/geo_flags/ua.png" alt="Ukranian" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="27%">Ukranian</td>
<td width="5%"><img src="images/geo_flags/vn.png" alt="Vietnamese" style="display:block; margin-left:auto; margin-right:auto; padding-top:4px;"></td><td width="29%">Vietnamese</td>
</tr>
</tbody>
</table>

<a href="http://www.idevdirect.com/module_language.php" target="_blank" class="btn btn-primary" style="margin-top:10px;">Order This Plugin</a>
</div>
</div>

</div>
</div>
</div>

<?PHP } ?>
</div>

<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Default Language Pack</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<div class="form-group">
<label class="col-md-3 control-label">Choose A Language</label>
<div class="col-md-3"><select class="form-control" name="lang_def">
<?PHP
$get_lang_packs = $db->query("select id, name, def from idevaff_language_packs where status = 1 ORDER BY name");
if ($get_lang_packs->rowCount()) {
while ($pack = $get_lang_packs->fetch()) {
$pack_def = $pack['def'];
$pack_id = $pack['id'];
$pack_name = $pack['name'];
$pack_name = ucwords($pack['name']);
echo "<option value='$pack_id'";
if ($pack_def == 1) { print "selected "; }
print ">$pack_name</option>\n"; } }
?>
</select></div>
</div>
<div class="form-actions">
<input type="submit" value="Set Default Language" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="update_default" value="1">
<input type="hidden" name="tab" value="3">
</form>
</div>
</div>
</div>

<?PHP
$get_packs = $db->query("select * from idevaff_language_packs ORDER BY name");
if ($get_packs->rowCount()) {
?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Manage Language Packs</h4></div>
<div class="widget-content">

<table class="table valign table-striped table-bordered table-highlight-head">
<thead>
<tr>
<th colspan="2">Language Pack Name</th>
<th>Status</th>
<th>Action</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<?PHP
while ($pack_qry = $get_packs->fetch()) {
$pack_name = ucwords($pack_qry['name']);
$pack_rem = $pack_qry['name'];
$pack_id = $pack_qry['id'];
$pack_status = $pack_qry['status'];
$def_status = $pack_qry['def'];
$table_name = $pack_qry['table_name'];
$user_created = $pack_qry['user_created'];
$direction = $pack_qry['direction'];
?>
<tr>

<form class="form-horizontal row-border" method="post" action="setup.php">
<td>
<input type="text" name="newpackname" class="form-control" value="<?PHP echo html_output($pack_name); ?>" />
<input type="hidden" name="action" value="33">
<input type="hidden" name="renamepackid" value="<?PHP echo html_output($pack_id); ?>">
<input type="hidden" name="oldpackname" value="<?PHP echo html_output($pack_name); ?>">
<input type="hidden" name="tab" value="3">
</td>
<td>
<input type="submit" class="btn btn-primary" value="Rename" />
</td>
</form>



<td>
<?PHP if ($pack_status == 1) { ?>
<span class="label label-success">Enabled</span>
<?PHP } else { ?>
<span class="label label-default">Disabled</span>
<?PHP } ?>
</td>
<?PHP if ($def_status == 1) { ?>
<td><button class="btn btn-warning" disabled>Default Language Pack</button></td>
<?PHP
} else {
if ($pack_status == 1) {
?>
<td><a href="setup.php?tab=3&action=33&option=1&status=0&pack=<?PHP echo $pack_name; ?>" class="btn btn-default">Disable</a></td>
<?PHP
} else {
?>
<td><a href="setup.php?tab=3&action=33&option=1&status=1&pack=<?PHP echo $pack_name; ?>" class="btn btn-primary">Enable</a></td>
<?PHP } } ?>

<td>
<?PHP if (($user_created == 1) && ($def_status == 0)) { ?>
<form method="post" value="setup.php">
<input type="hidden" value="<?PHP echo html_output($pack_id); ?>" name="remove"><input type="submit" class="btn btn-danger" value="Remove" />
<input type="hidden" value="33" name="action">
<input type="hidden" name="tab" value="3">
</form>
<?PHP } else { ?>
<?PHP if ($def_status == 1) { ?>
<button class="btn btn-warning" disabled>Default Pack Not Removable</button>
<?PHP } else { ?>
<button class="btn btn-default" disabled>Stock Pack Not Removable</button>
<?PHP } ?>
<?PHP } ?></td>
</tr>
<?PHP } ?>
</tbody>
</table>
</div>
</div>
</div>
<?PHP } ?>

</div>

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">

<?PHP
if (isset($_REQUEST['edittoken'])) {
$passedid = $_POST['edittoken'];
$getname = $db->prepare("select id, name from idevaff_language_custom where id = ?");
$getname->execute(array($passedid));
$getname = $getname->fetch();
$token_id = $getname['id'];
$token_name = $getname['name'];
?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Edit Token Language: <?PHP echo html_output($token_name); ?></h4></div>
<div class="widget-content">
<table class="table valign table-striped table-bordered table-highlight-head">
<tbody>
<?PHP
$getpacknames = $db->query("select id, name, table_name from idevaff_language_packs ORDER by name");
if ($getpacknames->rowCount()) {
$table_name = '';
while ($qry = $getpacknames->fetch()) {
$pack_name = ucwords($qry['name']);
$pack_id = $qry['id'];
$table_name = $qry['table_name'];

$getpackvals = $db->prepare("select pack_".$pack_id." as res from idevaff_language_custom_values where id = ?");
$getpackvals->execute(array($token_id));
$getpackvals = $getpackvals->fetch();
$pvalue = $getpackvals['res'];
?>
<tr>
<td><?PHP echo html_output($pack_name); ?></td>
<td>
<form class="form-horizontal row-border" method="post" action="setup.php">
<textarea rows="2" name="newtext" class="form-control"><?PHP echo $pvalue; ?></textarea>
<input type="submit" class="btn btn-warning btn-sm" value="Edit <?PHP echo html_output($pack_name); ?> Language" />
<input type="hidden" name="action" value="33">
<input type="hidden" name="edittoken" value="<?PHP echo html_output($token_id); ?>">
<input type="hidden" name="field" value="<?PHP echo "pack_".$pack_id; ?>">
<input type="hidden" name="tab" value="4">
</form>
</td>
</tr>
<?PHP } ?>
</tbody>
</table>
</div>
</div>
</div>
<?PHP } } ?>

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Custom Language Tokens</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">
<div class="form-group">
<label class="col-md-3 control-label">Token Name</label>
<div class="col-md-3"><input type="text" name="newtoken" class="form-control" /></div>
</div>
<div class="form-actions">
<input type="submit" value="Create Token" class="btn btn-primary">
</div>
<input type="hidden" name="action" value="33">
<input type="hidden" name="tab" value="4">
</form>
</div>
</div>
</div>

<?PHP
$gettokens = $db->query("select * from idevaff_language_custom ORDER BY name");
if ($gettokens->rowCount()) {
?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Manage Current Tokens</h4></div>
<div class="widget-content">
<table class="table valign table-striped table-bordered table-highlight-head">
<thead>
<tr>
<th>Token Name</th>
<th>Edit Language</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<?PHP
while ($qry = $gettokens->fetch()) {
$token_id = $qry['id'];
$token_name = $qry['name'];
?>
<tr>
<td>
<?PHP echo html_output($token_name); ?>
</td>
<td>
<form class="form-horizontal row-border" method="post" action="setup.php">
<input type="hidden" name="edittoken" value="<?PHP echo html_output($token_id); ?>"><button class="btn btn-primary btn-sm">Edit Language Content</button>
<input type="hidden" name="action" value="33">
<input type="hidden" name="tab" value="4">
</form>
</td>
<td>
<form class="form-horizontal row-border" method="post" action="setup.php">
<button class="btn btn-danger btn-sm">Remove</button>
<input type="hidden" name="action" value="33">
<input type="hidden" name="tab" value="4">
<input type="hidden" name="deletetoken" value="<?PHP echo html_output($token_id); ?>">
</form>
</td>
</tr>
<?PHP } ?>
</tbody>
</table>
</div>
</div>
</div>

<?PHP } else { ?>
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-file-text-alt"></i> Custom Language Tokens</h4></div>
<div class="widget-content">
No custom tokens yet.
</div>
</div>
</div>

<?PHP } ?>

</div>

</div>
</div>




