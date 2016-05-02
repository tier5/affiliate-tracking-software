<link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
<link href="<?php echo plugins_url( 'css/admin.css', __FILE__ ); ?>" rel="stylesheet">
<span style="margin: 15px 0;width: 650px" class="compatCheck compatwarning">
	Deleting ClickFunnels Pages &amp; API Settings...  
	<strong><i class="fa fa-spinner fa-spin"></i> Please wait just a moment.</strong>
</span>
<div id="progress" style="width:670px;margin-bottom: 10px;height: 10px;border-bottom:4px solid #D04738;background: #E54F3F;"></div>
<?php echo 'Deleting <strong>'.wp_count_posts( 'clickfunnels' )->publish.'</strong> pages...'; ?>
<div id="information" style="width"></div>
<?php 
	$args = array(
		'numberposts' => 50,
		'post_type' =>'clickfunnels'
	);
	$posts = get_posts( $args );
	$total = wp_count_posts( 'clickfunnels' )->publish;
	$current = 0;
	if (is_array($posts)) {
	   foreach ($posts as $post) {
	      wp_delete_post( $post->ID, true);
	      $i += 1;
	      $percent = intval($i/$total * 100)."%";
		    echo '<script language="javascript">
		    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#1C69A9;border-bottom: 4px solid #135184;height: 10px\">&nbsp;</div>";
		    document.getElementById("information").innerHTML="<strong>'.$i.'</strong> pages(s) deleted.";
		    </script>';
		    echo str_repeat(' ',1024*64);
		    flush();
		    sleep(1);
				echo '<script language="javascript">document.getElementById("information").innerHTML="<strong>Success</strong> all ClickFunnels pages removed..."</script>';
				flush();
				sleep(1);
				echo 'Removed Plugin Settings...';
				update_option( 'clickfunnels_api_email', '' );
				update_option( 'clickfunnels_api_auth', '' );
				update_option( 'clickfunnels_siteURL', '' );
				update_option( 'clickfunnels_404Redirect', '' );
				update_option( 'clickfunnels_agency_group_tag', '' );
				update_option( 'clickfunnels_agency_api_details', '' );
				update_option( 'clickfunnels_agency_reset_data', '' );
				update_option( 'clickfunnels_agency_hide_settings', '' );
				echo '<br />Redirecting to Plugins Page...';
				echo '<script language="javascript">setTimeout(function(){window.location = "edit.php?post_type=clickfunnels&page=cf_api";}, 1800);</script>';
	   }
	}
?>