<?PHP

if (!defined('admin_includes')) { die(); }
include("session.check.php");

// ---------------------------
function getPlatform() {
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$platform = "windows";
	} else {
		$platform = "unix"; 
	}
	return($platform); 
}

function isWritable ($file) {
	$rtnVal = false;
	$platform = getPlatform();
	if($platform == 'windows') {
		$dir_separator = "\\"; 
	}
	if($platform == 'unix') {
		$dir_separator = '/'; 
	}
	if (@is_dir($file)) {
		if($platform == 'windows') {
			$file = preg_replace('/\\\\$/', '', $file). $dir_separator . md5(uniqid('', true));
		} else {
			$file = preg_replace('/\/\/$/', '', $file) . $dir_separator . md5(uniqid('',true)); 
		}
		$unlink = true;
	} else {
		$unlink = !file_exists($file); 
	}
	if ($fp = @fopen($file, 'ab')) {
		@fclose($fp);
		if ($unlink) {
			@unlink($file); 
		}
		$rtnVal = true;
	} else {
		$rtnVal = false; 
	}
	return($rtnVal);
}
// ---------------------------

if (isset($_GET['remove'])) {
$db->query("update idevaff_config set main_logo = '', logo_uploaded = '0'");
$success_message = "<strong>Success!</strong> Control panel logo has been removed.";
}

if (isset($_POST['set_logo'])) {

// DIRECT LINKED IMAGE
if (strlen(trim($_FILES['file_upload_logo']['name'])) < 1) {
$corvette_racecar = $_POST['corvette_racecar'];
$st = $db->prepare("update idevaff_config set main_logo = ?, logo_uploaded = '0'");
$st->execute(array($corvette_racecar));
$success_message = "<strong>Success!</strong> Logo updated.";

} else {

// UPLOADED LOGO

$logo_error = null;
$fail_message = null;

$logo_file_type = $_FILES['file_upload_logo']['type'];
$logo_file_size = $_FILES['file_upload_logo']['size'];
$logo_file_name = $_FILES['file_upload_logo']['name'];
$logo_file_temp = $_FILES['file_upload_logo']['tmp_name'];
$logo_file_extension = strtolower( substr($logo_file_name, -3));

list($width, $height, $type, $attr) = getimagesize($logo_file_temp);

//1 IMAGETYPE_GIF 
//2 IMAGETYPE_JPEG 
//3 IMAGETYPE_PNG 

if ( ($type != 1) && ($type != 2) && ($type != 3) ) {
$logo_error = true;
$fail_message .= "<strong>Error!</strong> You can only upload image files with a <strong>.gif</strong>, <strong>.jpg</strong>, or <strong>.png</strong> extension."; }


if (!isset($logo_error)) {
	$newfilename = null;
	$newfilename = md5($newfilename.microtime());
	$newfilename = $newfilename . "." . $logo_file_extension;


	$res = copy($logo_file_temp, $path . "/templates/logo/" . $newfilename);
	if (!$res) {
		$logo_error = true;
		$fail_message .= "<strong>Error!</strong> Due to an unexpected response from the server, the logo did not upload properly. Check your <font color=\"#CC0000\">templates/logo</font> folder permissions for <strong>write</strong> permissions.";
	}
	else {
		
		// imagick
		/*
		if(extension_loaded('imagick')) {
			$thumb = new Imagick($path . "/templates/logo/" . $newfilename);

			$thumb->resizeImage(150, 25, Imagick::FILTER_LANCZOS, 1);
			$thumb->writeImage($path . "/templates/logo/" . $newfilename);

			$thumb->destroy();
		}
		*/
	}

	/*
	// Create image from file
	switch(strtolower($_FILES['file_upload_logo']['type']))
	{
		case 'image/jpeg':
			$image = imagecreatefromjpeg($_FILES['file_upload_logo']['tmp_name']);
			break;
		case 'image/png':
			$image = imagecreatefrompng($_FILES['file_upload_logo']['tmp_name']);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($_FILES['file_upload_logo']['tmp_name']);
			break;
		default:
			$logo_error = true;
			$fail_message .= "<strong>Unsupported type:{$_FILES['file_upload_logo']['type']}</strong> You can only upload image files with a <strong>.gif</strong>, <strong>.jpg</strong>, or <strong>.png</strong> extension.";

	}

	if (!isset($logo_error)) {
		// Delete original file
		@unlink($_FILES['file_upload_logo']['tmp_name']);


		// Target dimensions
		$max_width = 305;
		$max_height = 51;


		// Calculate new dimensions
		$old_width      = imagesx($image);
		$old_height     = imagesy($image);
		$scale          = min($max_width/$old_width, $max_height/$old_height);
		$new_width      = ceil($scale*$old_width);
		$new_height     = ceil($scale*$old_height);


		// Create new empty image
		$new = imagecreatetruecolor($new_width, $new_height);


		// Resample old into new
		imagecopyresampled($new, $image,
				0, 0, 0, 0,
				$new_width, $new_height, $old_width, $old_height);

		//save image to file
		//1 IMAGETYPE_GIF
		//2 IMAGETYPE_JPEG
		//3 IMAGETYPE_PNG
		if ( $type == 1 ) {
			$res = imagegif($new, $path . "/templates/logo/" . $newfilename);
		} elseif ( $type == 2 ) {
			$res = imagejpeg($new, $path . "/templates/logo/" . $newfilename, 100);
		} elseif ($type == 3) {
			$res = imagepng($new, $path . "/templates/logo/" . $newfilename, 0);
		}


		// Destroy resources
		imagedestroy($image);
		imagedestroy($new);

		//$res = copy($logo_file_temp, $path . "/templates/logo/" . $newfilename);
		if (!$res) {
			$logo_error = true;
			$fail_message .= "<strong>Error!</strong> Due to an unexpected response from the server, the logo did not upload properly. Check your <font color=\"#CC0000\">templates/logo</font> folder permissions for <strong>write</strong> permissions.";
		}

	}
	*/

if (!isset($logo_error)) {

$fname = "templates/logo/" . $newfilename;

$st = $db->prepare("update idevaff_config set main_logo = ?, logo_uploaded = '1'");
$st->execute(array($fname));
$success_message = "<strong>Success!</strong> Your logo has been uploaded.";

} } } }
$query	= $db->query("select main_logo, logo_uploaded from idevaff_config");
$config=$query->fetch();
$corvette_racecar=$config['main_logo'];
$logo_uploaded=$config['logo_uploaded'];


function readThemeInfo($themeRootDir, $themeUrl) {

    $themeRootDir = rtrim($themeRootDir, '/') . '/';
    $themeUrl = rtrim($themeUrl, '/') . '/';
    
    $themeInfoList = array();
    
    if (is_dir($themeRootDir)) {
        $themeEntryList = scandir($themeRootDir);
        
        if (is_array($themeEntryList) && count($themeEntryList) > 0) {
            foreach($themeEntryList as $themeDirName) {
                if ($themeDirName != '.' && $themeDirName != '..' && is_dir($themeRootDir . $themeDirName)) {
                    $infoFile = $themeRootDir . $themeDirName . '/theme_info/info.php';
                    if (file_exists($infoFile)) {
                        
                        $theme_name = '';
                        $author = '';
                        $date_created = '';
						$exrta_info = '';
                        $thumb = '';
						$preview = '';
						$theme_id = '';
                        
                        ob_start();
                        include($infoFile);
                        ob_end_clean();
                        
                        if ($theme_name != '') {
                            $tmp['theme_name'] = $theme_name;
                            $tmp['author'] = $author;
                            $tmp['date_created'] = $date_created;
							$tmp['extra_info'] = $extra_info;
							$tmp['theme_id'] = $theme_id;
							$tmp['theme_dir_name'] = $themeDirName;
                            
                            $thumbFile = $themeRootDir . $themeDirName . '/theme_info/' . $thumb;
                            if (file_exists($thumbFile)) {
                                $tmp['thumb'] = $themeUrl . $themeDirName . '/theme_info/' .$thumb;
                            }
                            else {
                                $tmp['thumb'] = '';
                            }
							
                            $previewFile = $themeRootDir . $themeDirName . '/theme_info/' . $preview;
                            if (file_exists($previewFile)) {
                                $tmp['preview'] = $themeUrl . $themeDirName . '/theme_info/' .$preview;
                            }
                            else {
                                $tmp['preview'] = '';
                            }
                            
                            
                            
                            $themeInfoList[] = $tmp;
                        }
                        
                    }
                }
            }
        }
        
    }
    
    return $themeInfoList;

}


//here code for page editor

function scanFile($dir,$ext) {
	$ret = array();
	if($dir != "" && $ext != "" && ($ext == "tpl" || $ext == "css")) {
		//scan dir
		if(is_dir($dir)):
			$files = scandir($dir);
			$n_files=count($files);
			foreach($files as $k=>$file){
				if($file==".") continue;
				elseif($file=="..") continue;
				elseif(is_dir($dir . $file)) continue;
				else{
					$file_ext = substr(strrchr($file,'.'),1);
					if($file_ext == $ext)
						$ret[] = $file;
				}
			}
			
		endif;
	}
	return $ret;
}

// Page Editor Stuff
// -----------------------------------

		$theme = (isset($_GET['theme'])) ? urldecode(trim(strip_tags($_GET['theme']))) : (isset($_POST['theme']) ? urldecode(trim(strip_tags($_POST['theme']))) : $active_theme); 
		$template_file = isset($_GET['template_file']) ? urldecode(trim(strip_tags($_GET['template_file']))) : (isset($_POST['template_file']) ? urldecode(trim(strip_tags($_POST['template_file']))) : '');
		$file_type = isset($_GET['file_type']) ? (int) $_GET['file_type'] : (isset($_POST['file_type']) ? (int) $_POST['file_type'] : 0);
		$file_path = "";
		$folder_path = "";
		if($file_type > 0) {
			if($file_type == 1) {
				$file_path = "../templates/themes/$theme/$template_file";
				$folder_path = "../templates/themes/$theme";
			}
			elseif ($file_type == 2) {
				$file_path = "../templates/themes/$theme/custom/$template_file";
				$folder_path = "../templates/themes/$theme/custom";
			}
			elseif ($file_type == 3) {
				$file_path = "../templates/themes/$theme/css/$template_file";
				$folder_path = "../templates/themes/$theme/css";
			}
			elseif ($file_type == 4) {
				$file_path = "../templates/source/common/bootstrap/css/$template_file";
				$folder_path = "../templates/source/common/bootstrap/css";
			}
			elseif ($file_type == 5) {
				$file_path = "../templates/source/common/css/$template_file";
				$folder_path = "../templates/source/common/css";
			}
			elseif ($file_type == 6) {
				$file_path = "../templates/source/common/font-awesome/$template_file";
				$folder_path = "../templates/source/common/font-awesome";
			}
		} 
		
		
		if(isset($_POST['codeEditor'])) {
			// ADDED BY JIM WEBSTER
			// If we unlink before writing, fopen will automatically (re)create the file.
			// All that is needed is write perms on the folder instead of the file which
			// is much more common and easy to do.
			
			//$folder_permission = substr(sprintf('%o', fileperms($folder_path)), -4);
						
			if((fileperms($folder_path) & 0777) >= 0755) {
				//good to go
				if (file_exists($file_path)) { @unlink($file_path); }
				$data = html_entity_decode($_POST['codeEditor']);
				if ($fp = @fopen ($file_path, "wb")) {
					@fwrite($fp, $data);
					@fclose($fp);
					$success_message = "<strong>Success!</strong> File updated.";
				}
				else {
				$display_folder = substr($file_path, 2);
				$display_folder = pathinfo($display_folder);
				$display_folder = $display_folder['dirname'];
					$fail_message = "<strong>Error!</strong> The selected folder (<strong>" . $display_folder . "</strong>) is not writeable. Using FTP, please give the folder write (777) permissions. If needed, please consult your web hosting provider and/or server admin for help with this task.";
				}
			}
			else {
				$display_folder = substr($file_path, 2);
				$display_folder = pathinfo($display_folder);
				$display_folder = $display_folder['dirname'];
					$fail_message = "<strong>Error!</strong> The selected folder (<strong>" . $display_folder . "</strong>) is not writeable. Using FTP, please give the folder write (777) permissions. If needed, please consult your web hosting provider and/or server admin for help with this task.";
				}
		}

?>


<div class="crumbs">
<ul id="breadcrumbs" class="breadcrumb">
<li><i class="icon-home"></i> <a href="dashboard.php">Dashboard</a></li>
<li> Templates</li>
<li class="current"> <a href="setup.php?action=9" title="">Control Panel Theme</a></li>
</ul>
<?PHP include("templates/crumb_right.php"); ?>
</div>

<div class="page-header">
<div class="page-title"><h3>Control Panel Theme</h3><span>Customize the look and feel of your affiliate control panel.</span></div>
<?PHP include("templates/stats.php"); ?>
</div>

<?PHP include("notifications.php"); ?>

<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
<li <?php makeActiveTab(1);?>><a href="#tab_1_1" data-toggle="tab">Theme Selection</a></li>
<li <?php makeActiveTab(2);?>><a href="#tab_1_2" data-toggle="tab">Logo</a></li>
<li <?php makeActiveTab(21);?>><a href="#tab_1_21" data-toggle="tab">Layout Options</a></li>
<li <?php makeActiveTab(3);?>><a href="#tab_1_3" data-toggle="tab">Color Scheme</a></li>
<li <?php makeActiveTab(35);?>><a href="#tab_1_35" data-toggle="tab">Page Editor</a></li>
<li <?php makeActiveTab(45);?>><a href="#tab_1_45" data-toggle="tab">Accountability Seal</a></li>
<li <?php makeActiveTab(4);?>><a href="#tab_1_4" data-toggle="tab">Create A Theme</a></li>
<li><a data-toggle="modal" href="#video_tutorial"><i class="icon-play"></i> Video Tutorial</a></li>
</ul>

<div class="modal fade" id="video_tutorial">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Video Tutorial: Affiliate Panel</h4>
</div>
<div class="modal-body">
<div class="video-container">
<iframe src="//player.vimeo.com/video/153026482" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
<div class="widget-header"><h4><i class="icon-picture"></i> Theme Selection</h4></div>
<div class="widget-content">

<?PHP
$themeRootDir = '../templates/themes/';
$themeUrl = $base_url . '/templates/themes/';
$themeInfoList = readThemeInfo($themeRootDir, $themeUrl);
?>


<div class="theme-list-container">
    <?PHP 
        $nodeCount = 0;
        foreach ($themeInfoList as $data) {
	?>
						<form class="form-horizontal" method="post" action="setup.php">
						<input type="hidden" name="action" value="9">
						<input type="hidden" name="cfg" value="133">
						<input type="hidden" name="tab" value="2">
						<input type="hidden" name="theme" value="<?PHP echo $data['theme_id']; ?>">
        <div class="theme-node">
            <div class="inner">
		
			    <div align="center" class="thumbnail-preview-cont">
                    <a href="<?PHP echo $data['preview'];?>" target="_blank" class="fancy-image"><img src="<?PHP echo $data['thumb'];?>" width="300" height="160" style="border:1px solid #c4c4c4;" /><span class="desc-overlay">Click To Preview</span></a>
                    
                </div>
			    <div style="margin-top:10px;"><font style="font-size:19px; color:#000000;"><?PHP echo html_output($data['theme_name']);?></font></div>
				<?PHP if ($data['extra_info'] != "") { ?><div><font color="#CC0000"><?PHP echo html_output($data['extra_info']);?></font></div><?PHP } ?>
                <div style="margin-top: 5px;">Author: <?PHP echo html_output($data['author']);?></div>
                <!--<div>Date Created: <?PHP // echo $data['date_created'];?></div>-->
				
				<?PHP if ($data['extra_info'] != "") { $pix_height = 5; } else { $pix_height = 22; } ?>
			    <div style="margin-top: <?PHP echo html_output($pix_height); ?>px;">
			        <?PHP if (($active_theme) == ($data['theme_id'])) { ?>
			        <p><button class="btn btn-danger" type="button" disabled>Currently Enabled</button></p>
			        <?PHP } else { ?>
			        <p><button class="btn btn-primary">Enable This Theme</button></p>
			        <?PHP } ?>
			    </div>
            </div>
            
        </div>
		</form>
		
		
		
    <?PHP } ?>
	<div class="clearfix"></div>
	</div>
</div>
</div>
</div>
</div>



<div class="tab-pane<?php makeActiveTab(2, 'no');?>" id="tab_1_2">

<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-picture"></i> Control Panel Logo</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" enctype="multipart/form-data" action="setup.php">
<div class="well">Upload a logo OR enter a direct URL to your logo. Logo image types allowed are <font color="#CC0000">GIF</font>, <font color="#CC0000">JPG</font> and <font color="#CC0000">PNG</font>.
<!--<br /><br /><a href="#logo_info" class="btn btn-success" data-toggle="modal">Logo Sizing Information</a>-->
</div>

<div class="modal fade" id="logo_info">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Logo Information</h4>
</div>
<div class="modal-body">
<p>We suggest using a logo no larger than about <strong>330x75 pixels</strong> in size. The logo is designed to fit in the section shown in the image below. Anything larger and the logo will scale to fit the location. Remember, this is just the stock HTML template that comes with iDevAffiliate. If you want to edit this template to your liking, you're welcome to do so. Visit the <a href="help.php" style="color:blue;">help center</a> then click on <strong>Customize Language and Templates</strong> to learn how.</p>
<p><img src="images/logo_screen.png" style="width:665px height:245px; border:0px;"></p>
</div>
<div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Close</button></div>
</div>
</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Logo Upload</label>
<div class="col-md-9"><input type="file" name="file_upload_logo" data-style="fileinput" /></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Direct Logo Location</label>
<div class="col-md-9"><input type="text" name="corvette_racecar" class="form-control" value="<?PHP echo $corvette_racecar; ?>" /></div>
</div>

<div class="form-actions">
<input type="submit" value="Update Logo" class="btn btn-primary">
</div>
<input type="hidden" name="set_logo" value="1">
<input type="hidden" name="action" value="9">
<input type="hidden" name="tab" value="2">
</form>

</div>
</div>


<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-picture"></i> Current Control Panel Logo</h4> <?PHP if ($corvette_racecar != '') { ?><a href="setup.php?action=9&tab=2&remove=true" class="btn btn-danger btn-sm" style="margin:0px 0 3px 10px;">Remove Current Logo</a><?PHP } ?></div>
<div class="widget-content">

<?PHP
if ($corvette_racecar != '') {

if ($logo_uploaded == 1) {
$url_help = "../";
} else {
if ((strpos($corvette_racecar, "http://") !== false) || (strpos($corvette_racecar, "https://") !== false)) {
$url_help = null;
} else {
$url_help = "../";
}
}

?>

<img src="<?PHP echo $url_help . $corvette_racecar; ?>" style="border:none;" />

<?PHP
} else {
echo "No control panel logo is set."; }
?>

</div>
</div>


</div>
</div>








<div class="tab-pane<?php makeActiveTab(21, 'no');?>" id="tab_1_21">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-picture"></i> Layout Options</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">

<div class="form-group">
<label class="col-md-3 control-label">Page Style</label>
<div class="col-md-2"><select name="cp_page_width" class="form-control">
<option value="1" <?PHP if ($cp_page_width == 1) { ?> selected <?PHP }?>>Full Width</option>
<option value="0" <?PHP if ($cp_page_width == 0) { ?> selected <?PHP }?>>Fixed Width</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Menu Location</label>
<div class="col-md-2"><select name="cp_menu_location" class="form-control">
<option value="1" <?PHP if ($cp_menu_location == 1) { ?> selected <?PHP }?>>Left</option>
<option value="0" <?PHP if ($cp_menu_location == 0) { ?> selected <?PHP }?>>Top</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Fixed Navbar at Top</label>
<div class="col-md-2"><select name="cp_fixed_navbar" class="form-control">
<option value="1" <?PHP if ($cp_fixed_navbar == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($cp_fixed_navbar == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Fixed Left Menu (if selected)</label>
<div class="col-md-2"><select name="cp_fixed_left_menu" class="form-control">
<option value="1" <?PHP if ($cp_fixed_left_menu == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($cp_fixed_left_menu == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Use <strong>Contact Us</strong> Form</label>
<div class="col-md-2"><select name="contact_form" class="form-control">
<option value="1" <?PHP if ($contact_form == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($contact_form == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Include <strong>Contact Us</strong> Link In Footer</label>
<div class="col-md-2"><select name="contact_link" class="form-control">
<option value="1" <?PHP if ($contact_link == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($contact_link == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Show Logo In Footer</label>
<div class="col-md-2"><select name="logo_footer" class="form-control">
<option value="1" <?PHP if ($logo_footer == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($logo_footer == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>

<div class="form-actions">
<input type="submit" value="Save Layout Settings" class="btn btn-primary">
</div>
<input type="hidden" name="cfg" value="142">
<input type="hidden" name="action" value="9">
<input type="hidden" name="tab" value="21">
</form>
</div>
</div>
</div>
</div>





<div class="tab-pane<?php makeActiveTab(3, 'no');?>" id="tab_1_3">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-picture"></i> Control Panel Color Scheme</h4></div>
<div class="widget-content">
<form class="form-horizontal row-border" method="post" action="setup.php">

<div class="form-group">
<label class="col-md-3 control-label">Page Background Color</label>
<div class="col-md-3"><input type="text" name="cp_background" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_background; ?>"></div>
<label class="col-md-3 control-label">Header Background Color</label>
<div class="col-md-3"><input type="text" name="cp_header_background" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_header_background; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Page Heading Background Color</label>
<div class="col-md-3"><input type="text" name="cp_heading_back" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_heading_back; ?>"></div>
<label class="col-md-3 control-label">Top Menu Background Color</label>
<div class="col-md-3"><input type="text" name="cp_top_menu_background" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_top_menu_background; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Page Heading Text Color</label>
<div class="col-md-3"><input type="text" name="cp_heading_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_heading_text; ?>"></div>
<label class="col-md-3 control-label">Top Menu Text Color</label>
<div class="col-md-3"><input type="text" name="cp_top_menu_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_top_menu_text; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Main Menu Background Color</label>
<div class="col-md-3"><input type="text" name="cp_main_menu_color" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_main_menu_color; ?>"></div>
<label class="col-md-3 control-label">Main Menu Text Color</label>
<div class="col-md-3"><input type="text" name="cp_main_menu_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_main_menu_text; ?>"></div>
</div>

<div class="form-group" style="background:#CCE0FF; height:40px;">
<div class="col-md-12"><strong>Portlet Boxes</strong> - Boxes containing specific data on each page.</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 1 (default: light)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_1" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_1; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 1</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_1" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_1; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 2 (default: dark)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_2" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_2; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 2</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_2" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_2; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 3 (default: blue)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_3" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_3; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 3</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_3" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_3; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 4 (default: dark blue)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_4" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_4; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 4</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_4" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_4; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 5 (default: red)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_5" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_5; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 5</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_5" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_5; ?>"></div>
</div>
<div class="form-group">
<label class="col-md-3 control-label">Portlet Box Color 6 (default: green)</label>
<div class="col-md-3"><input type="text" name="cp_portlet_6" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_6; ?>"></div>
<label class="col-md-3 control-label">Portlet Box Text Color 6</label>
<div class="col-md-3"><input type="text" name="cp_portlet_text_6" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_portlet_text_6; ?>"></div>
</div>

<div class="form-group" style="background:#CCE0FF; height:40px;">
<div class="col-md-12"><strong>Statistics Boxes</strong> - Shown on main page once the affiliate is logged in.</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Background: Total Transactions</label>
<div class="col-md-3"><input type="text" name="cp_box_tt_back" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_tt_back; ?>"></div>
<label class="col-md-3 control-label">Text: Total Transactions</label>
<div class="col-md-3"><input type="text" name="cp_box_tt_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_tt_text; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Background: Current Earnings</label>
<div class="col-md-3"><input type="text" name="cp_box_ce_back" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_ce_back; ?>"></div>
<label class="col-md-3 control-label">Text: Current Earnings</label>
<div class="col-md-3"><input type="text" name="cp_box_ce_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_ce_text; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Background: Total Earned To Date</label>
<div class="col-md-3"><input type="text" name="cp_box_te_back" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_te_back; ?>"></div>
<label class="col-md-3 control-label">Text: Total Earned To Date</label>
<div class="col-md-3"><input type="text" name="cp_box_te_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_te_text; ?>"></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Background: Unique Visitors</label>
<div class="col-md-3"><input type="text" name="cp_box_uv_back" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_uv_back; ?>"></div>
<label class="col-md-3 control-label">Text: Unique Visitors</label>
<div class="col-md-3"><input type="text" name="cp_box_uv_text" id="hue-idevcolorpicker" class="form-control input-width-large demo idevcolorpicker" data-control="hue" value="<?PHP echo $cp_box_uv_text; ?>"></div>
</div>


<div class="form-actions">
<input type="submit" value="Save Color Settings" class="btn btn-primary"><a href="setup.php?action=9&tab=3&cfg=143" class="btn btn-warning pull-right">Reset To Default Color Settings</a>
</div>
<input type="hidden" name="cfg" value="4">
<input type="hidden" name="action" value="9">
<input type="hidden" name="tab" value="3">
</form>
</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(35, 'no');?>" id="tab_1_35">
<div class="row">
<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
				<div class="widget-header"><h4><i class="icon-picture"></i> Select Theme</h4></div>
				<div class="widget-content">
					<form method="get" action="setup.php">

						<div class="row">
	                        <div class="col-md-8">
	                            <select class="form-control" name="theme" id="template_theme">
	                            <?php
	                                if(is_array($themeInfoList) && !empty($themeInfoList)) {
	                                    foreach ($themeInfoList as $theme_key => $theme_data) :
	                                        if($theme == $theme_data['theme_dir_name'])
	                                            $selected = 'selected="selected"';
	                                        else
	                                            $selected = '';
	                                        echo "<option value='{$theme_data['theme_dir_name']}' $selected>{$theme_data['theme_name']}</option>";
	                                    endforeach;
	                                }
	                            ?>
	                            </select>
	                        </div>
	                        <div class="col-md-4">
	                            <input type="submit" value="Change" class="btn btn-primary">
	                        </div>
						</div>

	                    <input type="hidden" name="action" value="9">
	                    <input type="hidden" name="tab" value="35">
					</form>
				</div>
			</div>
		</div>

<div class="col-md-6">
<div class="widget box" style="margin-top:20px;">
				<div class="widget-header"><h4><i class="icon-list"></i> Template Files</h4></div>
				<div class="widget-content">
					<form method="get" action="setup.php" id="submit_template_file">

						<div class="row">

							<div class="col-md-8">
								<select class="form-control" name="template_file" id="template_file">
									<optgroup label="Theme Files">
									<?php
									$template1 = scanFile("../templates/themes/$theme/", 'tpl');
									if(!empty($template1)):
										foreach($template1 as $file):
											if($template_file == '' && $file_path == '') {
												$file_type = 1;
												$template_file = $file;
												$file_path = "../templates/themes/$theme/$template_file";
											}
											?>
											<option <?php if($file_type=='1' && $template_file == $file) { echo 'selected="selected"'; } ?> data-file_type="1" value="<?php echo $file; ?>" ><?php echo $file; ?></option>
											<?php
										endforeach;
									endif;
									?>
									</optgroup>

									<optgroup label="Custom Pages">
									<?php
									$template1 = scanFile("../templates/themes/$theme/custom/", 'tpl');
									if(!empty($template1)):
										foreach($template1 as $file):
											?>
											<option <?php if($file_type=='2' && $template_file == $file) { echo 'selected="selected"'; } ?> data-file_type="2" value="<?php echo $file; ?>" ><?php echo $file; ?></option>
											<?php
										endforeach;
									endif;
									?>
									</optgroup>

									<optgroup label="CSS Files">
									<?php
									$template1 = scanFile("../templates/themes/$theme/css/", 'css');
									if(!empty($template1)):
										foreach($template1 as $file):
											if($file != 'style.css' && $file != 'style_login.css')
												continue;
											?>
											<option <?php if($file_type=='3' && $template_file == $file) { echo 'selected="selected"'; } ?> data-file_type="3" value="<?php echo $file; ?>" ><?php echo $file; ?></option>
											<?php
										endforeach;
									endif;
									?>
									</optgroup>
								</select>
							</div>

							<div class="col-md-4">
								<input type="submit" value="Select" class="btn btn-primary">
							</div>

						</div>
						<input type="hidden" id="file_type" name="file_type" value="" />
						<input type="hidden" name="theme" value="<?php echo $theme; ?>" />
						<input type="hidden" name="action" value="9" />
						<input type="hidden" name="tab" value="35" />
					</form>

					<script type="text/javascript">
						jQuery(function($){
							$('#submit_template_file').submit(function() {
								var selected = $('#template_file').find('option:selected');
								var file_type = selected.data('file_type');
								$('#file_type').val(file_type);
							});
						});
					</script>
				</div>
			</div>
		</div>
</div>
				

		<div class="col-md-12">

			<div class="widget box">
				<div class="widget-header"><h4><i class="icon-edit"></i> <?php echo html_output($theme); ?><?PHP if ($template_file != '') { ?> - <?PHP } ?><span style="color:#CC0000;"><?php echo html_output($template_file); ?></span></h4></div>
				<div class="widget-content">
					<?php
					if($file_path != ""):
						if($fp = @fopen($file_path, "rb")) {
					?>
							<form class="form-horizontal row-border" action="setup.php" method="post">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="codeEditor" id="codeEditor" class="form-control" rows="30"><?php
											if (filesize($file_path) > 0 ) {
												print ( htmlentities( fread( $fp, filesize( $file_path ) ) ) );
												@fclose ($fp);
											}
										?></textarea>
									</div>
								</div>

								<div class="form-actions">
									<input type="submit" value="Update Template" class="btn btn-primary">
								</div>
								<input type="hidden" name="action" value="9">
								<input type="hidden" name="tab" value="35">
								<input type="hidden" name="file_type" value="<?php echo $file_type; ?>">
								<input type="hidden" name="template_file" value="<?php echo $template_file; ?>">
								<input type="hidden" name="theme" value="<?php echo $theme; ?>">
							</form>
					<?php
						} else {
							$display_folder = substr($file_path, 2);
							$display_folder = pathinfo($display_folder);
							$display_folder = $display_folder['dirname'];
							echo '<div class="alert alert-danger" role="alert"><strong>Error!</strong> The selected folder (<strong>' . $display_folder . '</strong>) is not writeable. Using FTP, please give the folder write (777) permissions. If needed, please consult your web hosting provider and/or server admin for help with this task."</div>';
						}
					else: ?>
						<div class="alert alert-info" role="alert">Select a file to edit.</div>
					<?php endif; ?>
				</div>
			</div>
		</div>



</div>

<div class="tab-pane<?php makeActiveTab(45, 'no');?>" id="tab_1_45">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-thumbs-up"></i> Accountability Seal</h4></div>
<div class="widget-content">

<div class="row">
<div class="col-md-12"><img class="img-responsive" src="../images/seals/certified_english.png" style="float:left; margin-left:10px; margin-right:25px; margin-bottom:15px; width:142px; height:142px; border:none;" />
By displaying the accountability seal, you're letting affiliates know you take a pro-active approach to accountability and communication. The seal will be displayed on your affiliate home page. It indicates you are open for communication where commission status is concerned and will be available to offer details on specific commissions. For instance, an affiliate may have a commission declined. In this case, he/she knows they can come to you with questions and can expect an open line of communication. This helps to build trust between you and your affiliates.
<br /><br /><a href="setup.php?action=33" class="btn btn-sm btn-warning">Update Content Shown To Affiliate</a></div>
</div>

<div class="row">
<div class="col-md-12">
<form class="form-horizontal row-border" method="post" enctype="multipart/form-data" action="setup.php">
<div class="form-group">
<label class="col-md-3 control-label">Show The Accountability Seal?</label>
<div class="col-md-9"><select name="seal_status" class="form-control input-width-small" style="display:inline;">
<option value="1" <?PHP if ($seal == 1) { ?> selected <?PHP }?>>Yes</option>
<option value="0" <?PHP if ($seal == 0) { ?> selected <?PHP }?>>No</option>
</select></div>
</div>
<div class="form-actions">
<input type="submit" value="Save Setting" class="btn btn-primary">
</div>
<input type="hidden" name="cfg" value="151">
<input type="hidden" name="action" value="9">
<input type="hidden" name="tab" value="45">
</form>
</div>
</div>


</div>
</div>
</div>
</div>

<div class="tab-pane<?php makeActiveTab(4, 'no');?>" id="tab_1_4">
<div class="col-md-12">
<div class="widget box" style="margin-top:20px;">
<div class="widget-header"><h4><i class="icon-picture"></i> Create A Theme</h4></div>
<div class="widget-content">
<div class="alert alert-info">This is an advanced feature designed specifically for developers. Creating your own theme requires basic development knowledge, primarily HTML and CSS. A background in Smarty Templates, Javascript and PHP will be handy as well.</div>
<a data-toggle="modal" href="#create_tutorial" class="btn btn-success">Watch The Instructional Video</a> <a href="http://www.idevlibrary.com/files/iDevAffiliate_Stock_Template_Pack_Version_<?PHP echo $version; ?>.zip" class="btn btn-primary">Download Stock Template Pack</a>

<div class="modal fade" id="create_tutorial">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Video Tutorial: Create A Theme</h4>
</div>
<div class="modal-body">
<div class="video-container">
<iframe src="//player.vimeo.com/video/153026490" frameborder="0" width="560" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>




