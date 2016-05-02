<style>.hndle {display: none !important}</style>
<?php
	if(!get_option( 'clickfunnels_api_email')) {
			update_option( 'clickfunnels_api_email', '');
	}
	if(!get_option( 'clickfunnels_api_auth')) {
			update_option( 'clickfunnels_api_auth', '');
	}
	if(!get_option( 'clickfunnels_siteURL')) {
			update_option( 'clickfunnels_siteURL', '');
	}
	if(!get_option( 'clickfunnels_404Redirect')) {
			update_option( 'clickfunnels_404Redirect', '');
	}
	if(!get_option( 'clickfunnels_agency_group_tag')) {
			update_option( 'clickfunnels_agency_group_tag', '');
	}
	if(!get_option( 'clickfunnels_agency_api_details')) {
			update_option( 'clickfunnels_agency_api_details', '');
	}
	if(!get_option( 'clickfunnels_agency_reset_data')) {
			update_option( 'clickfunnels_agency_reset_data', '');
	}
	if(!get_option( 'clickfunnels_agency_hide_settings')) {
			update_option( 'clickfunnels_agency_hide_settings', '');
	}
	$the_group_tag = get_option('clickfunnels_agency_group_tag');
	if ( 'yes' == $_REQUEST['save_api'] ) {
		if ($_POST['clickfunnels_api_email'] == '') {
			echo "<div id='message' class='error notice is-dismissible' style='width: 733px;padding: 10px 12px;font-weight: bold'><i class='fa fa-times' style='margin-right: 5px;'></i> Please add an email address. <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button></div>";
		}
		else if ($_POST['clickfunnels_api_auth'] == '') {
			echo "<div id='message' class='updated notice is-dismissible' style='width: 733px;padding: 10px 12px;font-weight: bold'><i class='fa fa-times' style='margin-right: 5px;'></i> Please add Authorization Key. <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button></div>";
		}
		else {
			echo "<div id='message' class='updated notice is-dismissible' style='width: 733px;padding: 10px 12px;font-weight: bold'><i class='fa fa-check' style='margin-right: 5px;'></i> Successfully updated ClickFunnels plugin settings. <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button></div>";
			update_option( 'clickfunnels_api_email', $_POST['clickfunnels_api_email'] );
			update_option( 'clickfunnels_api_auth', $_POST['clickfunnels_api_auth'] );
			update_option( 'clickfunnels_siteURL', $_POST['clickfunnels_siteURL'] );
			update_option( 'clickfunnels_404Redirect', $_POST['clickfunnels_404Redirect'] );
			update_option( 'clickfunnels_agency_group_tag', $_POST['clickfunnels_agency_group_tag'] );
			update_option( 'clickfunnels_agency_api_details', $_POST['clickfunnels_agency_api_details'] );
			update_option( 'clickfunnels_agency_reset_data', $_POST['clickfunnels_agency_reset_data'] );
			update_option( 'clickfunnels_agency_hide_settings', $_POST['clickfunnels_agency_hide_settings'] );
			$the_group_tag = $_POST['clickfunnels_agency_group_tag'];
		}
	}
?>
<link href="<?php echo plugins_url( 'css/admin.css', __FILE__ ); ?>" rel="stylesheet">
<link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
<script>
	jQuery(document).ready(function() {
		// Console Warning
		jQuery('.draft').hide();
		console.log("%cClickFunnels WordPress Plugin", "background: #0166AE; color: white; font-size: 23px");
		console.log("%cEditing anything inside the console is for developers only. Do not paste in any code given to you by anyone. Use with caution. Visit for support: https://support.clickfunnels.com/", "color: #888; font-size: 16px");
		// Tabs
		jQuery('.cftablink').click(function() {
      jQuery('.cftabs').hide();
      jQuery('.cftablink').removeClass('active');
      jQuery(this).addClass('active');
      var tab = jQuery(this).attr('data-tab');
      jQuery('#'+tab).show();
		})
		// Get Funnels API and show Agency Options
		var specificFunnel = 'https://api.clickfunnels.com/funnels.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
		jQuery.getJSON(specificFunnel, function(data) {
		  jQuery('.checkSuccess').html('<i class="fa fa-check successGreen"></i>');
		  jQuery('.checkSuccessDev').html('<i class="fa fa-check"> Connected</i>');
		  jQuery('#api_check').addClass('compatenabled');
	  	var is_selected = "";
			jQuery.each(data, function() {
				group_tag = '';
				if (this.group_tag) {
					group_tag = this.group_tag.replace(/(['"])/g, "{replace}");
					if ("<?php echo $the_group_tag; ?>" == group_tag) {
						is_selected = 'selected';
					} else {
						is_selected = '';
					}
					if (jQuery("#clickfunnels_agency_group_tag option[value='"+group_tag+"']").length == 0) {
						jQuery('#clickfunnels_agency_group_tag').append('<option value="'+group_tag+'">'+ this.group_tag +'</option>');
					}
				}
			});
	  }).done(function() {
	  	group_tags = "<?php echo $the_group_tag; ?>";
			jQuery("#clickfunnels_agency_group_tag").val(group_tags);
	  }).fail(function(jqXHR) {
	  	jQuery('#api_check').removeClass('compatenabled');
	  	jQuery('#api_check').addClass('compatdisabled');
     	jQuery('.checkSuccess').html('<i class="fa fa-times errorRed"></i>');
     	jQuery('.checkSuccessDev').html('<i class="fa fa-times"> Not Connected</i>');
     	jQuery('.badAPI').show();
	  });
	  // If agency is true
	  <?php if ($_GET['agency'] && $_GET['agency'] == get_option('clickfunnels_api_auth')) { ?>
	  	jQuery('#agency_open').trigger('click');
		<?php } ?>
	});
</script>
<div id="message" class="badAPI error notice" style="display: none; width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i> Failed API Connection with ClickFunnels. Check <a href="edit.php?post_type=clickfunnels&page=cf_api&error=compatibility">Settings > Compatibility Check</a> for details.</div>
<div class="api postbox" style="width: 780px;margin-top: 20px;">
	<!-- Header -->
	<?php include('header.php'); ?>
	<div class="apiSubHeader" style="padding: 18px 16px;">
		<h2 style="font-size: 1.5em"><i class="fa fa-cog" style="margin-right: 5px"></i> Plugin Settings</h2>
	</div>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
		<div class="bootstrap-wp">
			<div id="app_sidebar">
				<a href="#" data-tab="tab1" class="cftablink <?php if(!$_GET['error']) { echo 'active';} ?>">API Connection</a>
				<a href="#" data-tab="tab2" class="cftablink <?php if($_GET['error']) { echo 'active';} ?>">Compatibility Check</a>
				<a href="#" data-tab="tab5" class="cftablink <?php if($_GET['error']) { echo 'active';} ?>" style="<?php if(get_option('clickfunnels_agency_reset_data') == 'hide') { echo 'display: none';} ?>">Reset Plugin Data</a>
				<a href="#" data-tab="tab4" id="agency_open" class="cftablink" style="display: none;<?php if($_GET['agency'] && $_GET['agency'] == get_option('clickfunnels_api_auth')) { echo 'display: block';} ?>">Agency Feature <small>(hidden)</small></a>
			</div>
			<div id="app_main">
				<div id="tab5" class="cftabs" style="display: none;">
					<!-- Reset Plugin Data -->
					<h2>Reset Plugin Data</h2>
					<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Delete all your ClickFunnels pages inside your WordPress blog and remove your API details to clean up the database if you are starting fresh.</p>
					<a href="edit.php?post_type=clickfunnels&page=reset_data" class="button" style="margin-left: 51px" onclick="return confirm('Are you sure?')">Delete All Pages and API Settings</a>
					<p class="infoHelp" style="font-style: italic;font-weight: bold;margin-right: 3px;"><i class="fa fa-exclamation-triangle" style="font-weight: bold;margin-right: 3px;color: #E54F3F;"></i> Use with caution.</p>
				</div>
				<div id="tab2" class="cftabs" style="display: none;<?php if($_GET['error']) { echo 'display: block';} ?>">
					<!-- Compatibility Check -->
					<h2>Compatibility Check</h2>
					<span class="compatCheck" id="api_check">API Authorization:  <strong class='checkSuccessDev'><i class="fa fa-spinner"></i> Connecting...</strong></span>
					<?php
						if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
						echo '<span class="compatCheck compatwarning">CloudFlare:  <strong><a target="_blank" href="https://support.clickfunnels.com/support/solutions/5000164139">If you have blank pages, turn off minify for JavaScript.</a></strong></span>';
						}
					?>
					<?php if ( get_option( 'permalink_structure' ) == '' ) {
							echo '<span class="compatCheck compatdisabled">Permalinks:  <strong>ClickFunnels needs <a href="options-permalink.php">custom permalinks</a> enabled!</strong></span>';
					}
					else {
						echo '<span class="compatCheck compatenabled">Permalinks:  <strong><i class="fa fa-check"> Enabled</i></strong></span>';
					} ?>
					<?php echo function_exists('curl_version') ? '<span class="compatCheck compatenabled">CURL:  <strong><i class="fa fa-check"> Enabled</i></strong></span>' : '<span class="compatCheck"><i class="fa fa-times">Disabled</i></strong></span>'  ?>
					<?php echo file_get_contents(__FILE__) ? '<span class="compatCheck compatenabled">File Get Contents:  <strong><i class="fa fa-check"> Enabled</i></strong></span>' : '<span class="compatCheck">File Get Contents:  <strong><i class="fa fa-times">Disabled</i></strong></span>' ; ?>
					<?php echo ini_get('allow_url_fopen') ? '<span class="compatCheck compatenabled">Allow URL fopen:  <strong><i class="fa fa-check"> Enabled</i></strong></span>' : '<span class="compatCheck">Allow URL fopen:  <strong><i class="fa fa-times">Disabled</i></strong></span>' ; ?>
					<?php
						if (version_compare(phpversion(), "5.3.0", ">=")) {
							echo '<span class="compatCheck compatenabled">PHP Version:  <strong>'.PHP_VERSION.'</strong></span>';
						} else {
							// you're not PHP enough
							echo '<span class="compatCheck compatdisabled">PHP Version:  <strong><a href="https://support.clickfunnels.com/support/home" target="_blank">This plugin requires PHP 5.3.0 or above.</a></strong></span>';
						}
					?>
				</div>
				<div id="tab4" class="cftabs" style="display: none;">
					<!-- Agency Feature -->
					<h2>Agency Feature <small style="float: right;opacity: .7;margin-top: 7px;font-size: 12px;margin-right: 53px;">(for advanced users only)</small></h2>
					<div class="control-group clearfix" >
						<label class="control-label" for="clickfunnels_agency_group_tag"> Only Show Group Tag:</span> </label>
						<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
							<select name="clickfunnels_agency_group_tag" id="clickfunnels_agency_group_tag" class="input-xlarge" style="height: 30px;">
								<option value="off">Show All Funnels (default)</option>
								<option value="ungrouped" <?php if (get_option('clickfunnels_agency_group_tag') == 'ungrouped') { echo "selected";} ?>>Only Show: UnGrouped Funnels</option>
								<!-- Get Group Tags Funnel JSON API -->
							</select>
						</div>
					</div>
					<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Select a <strong>Group Tag</strong> to <em>ONLY SHOW</em> for this blog. This is for limiting what Funnels are accessible via the plugin. This is for anyone who allows their pages to be managed by multiple people. </p>
					<hr style="margin-left: 51px;" />
					<div class="control-group clearfix" >
						<label class="control-label" for="clickfunnels_agency_api_details"> Show or Hide Auth Code:</span> </label>
						<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
							<select name="clickfunnels_agency_api_details" id="clickfunnels_agency_api_details" class="input-xlarge" style="height: 30px;">
								<option value="show">Show API Connection Details (default)</option>
								<option value="hide" <?php if (get_option('clickfunnels_agency_api_details') == 'hide') { echo "selected";} ?>>Hide API Connection Details</option>
							</select>
						</div>
					</div>
					<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> For advanced users only - if you hide your details you can ONLY change them by enabling 'show' again in the select box above. Only use when you want to hide your API details.</p>
					<hr style="margin-left: 51px;" />
					<div class="control-group clearfix" >
						<label class="control-label" for="clickfunnels_agency_reset_data"> Show or Hide Reset Data:</span> </label>
						<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
							<select name="clickfunnels_agency_reset_data" id="clickfunnels_agency_reset_data" class="input-xlarge" style="height: 30px;">
								<option value="show">Show Reset Data (default)</option>
								<option value="hide" <?php if (get_option('clickfunnels_agency_reset_data') == 'hide') { echo "selected";} ?>>Hide Reset Data Settings</option>
							</select>
						</div>
					</div>
					<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Choose "Hide Reset Data" to prevent any users from deleting all of your pages and API settings. Use if you have other people managing your blog.</p>
					<hr style="margin-left: 51px;" />
					<div class="control-group clearfix" >
						<label class="control-label" for="clickfunnels_agency_hide_settings"> Show or Hide Settings:</span> </label>
						<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
							<select name="clickfunnels_agency_hide_settings" id="clickfunnels_agency_hide_settings" class="input-xlarge" style="height: 30px;">
								<option value="show">Show Settings (default)</option>
								<option value="hide" <?php if (get_option('clickfunnels_agency_hide_settings') == 'hide') { echo "selected";} ?>>Hide Settings</option>
							</select>
						</div>
					</div>
					<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Choose "Hide Settings" to prevent any users from being able to view the settings panel. You will only be able to access it via the agency feature to turn it back on.</p>
					<button class="action-button shadow animate green " id="publish" style="float: right;margin-top: 10px;"><i class="fa fa-check-circle"></i> Save Settings</button>
				</div>
				<div id="tab1" class="cftabs" style="<?php if($_GET['error'] || $_GET['agency'] && $_GET['agency'] == get_option('clickfunnels_api_auth')) { echo 'display: none';} ?>">
					<!-- Main Settings -->
					<h2>API Connection</h2>
					<input type="hidden" class="form-control" name="save_api" value="yes" />
					<div style='<?php if (get_option( "clickfunnels_agency_api_details" ) == 'hide') { echo 'display: none'; } ?>'>
						<div class="control-group clearfix">
							<label class="control-label" for="clickfunnels_api_email"> Account Email: <span class="checkSuccess"></span> </label>
							<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
								<input type="text" class="input-xlarge" style="height: 30px;" value="<?php echo get_option( 'clickfunnels_api_email' ); ?>" name="clickfunnels_api_email" />
							</div>
						</div>
						<div class="control-group clearfix">
							<label class="control-label" for="clickfunnels_api_auth"> Authentication Token:  <span class="checkSuccess"></span> </label>
							<div class="controls" style="padding-left: 24px;margin-bottom: 16px;">
								<input type="text" class="input-xlarge" style="height: 30px;" value="<?php echo get_option( 'clickfunnels_api_auth' ); ?>" name="clickfunnels_api_auth" />
							</div>
						</div>
						<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> To access your Authentication Token go to your ClickFunnels Members area and choose <a href="https://app.clickfunnels.com/users/edit" target="_blank">My Account > Settings</a> and you will find your API information.</p>
					</div>
					<div style='display: none;<?php if (get_option( "clickfunnels_agency_api_details" ) == 'hide') { echo 'display: block'; } ?>'>
						<span class="compatCheck compatwarning">API Details Locked:  <strong>Set by administrator.</strong></span>
						<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> If you need access to change the API details, please contact the person who set up the ClickFunnels plugin for you.</p>
					</div>
					<button class="action-button shadow animate green " id="publish" style="float: right;margin-top: 10px;"><i class="fa fa-check-circle"></i> Save Settings</button>
				</div>

				<br clear="both" />
			</div>
		</div>
	</form>
	<?php include('footer.php'); ?>
</div>