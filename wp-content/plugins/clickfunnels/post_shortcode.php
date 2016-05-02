<style>.hndle {display: none !important}</style>
<link href="<?php echo plugins_url( 'css/admin.css', __FILE__ ); ?>" rel="stylesheet">
<link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
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
  $cf_thefunnel = get_post_meta( $_GET['post'], "cf_thefunnel", true );
  $cf_thepage = get_post_meta( $_GET['post'], "cf_thepage", true );
  $cf_iframe_check = get_post_meta( $_GET['post'], "cf_iframe_check", true );
  $cf_iframe_url = get_post_meta( $_GET['post'], "cf_iframe_url", true );
  $cf_seo_title = get_post_meta( $_GET['post'], "cf_seo_title", true );
  $cf_seo_desc = get_post_meta( $_GET['post'], "cf_seo_desc", true );
  $cf_seo_image = get_post_meta( $_GET['post'], "cf_seo_image", true );
  $cf_page_url = get_post_meta( $_GET['post'], "cf_page_url", true );
  $cf_favicon = get_post_meta( $_GET['post'], "cf_favicon", true );
  $cf_author = get_post_meta( $_GET['post'], "cf_author", true );
  $cf_keywords = get_post_meta( $_GET['post'], "cf_keywords", true );
  $cf_head_tracking = get_post_meta( $_GET['post'], "cf_head_tracking", true );
  $cf_footer_tracking = get_post_meta( $_GET['post'], "cf_footer_tracking", true );
  $cf_wptracking_code = get_post_meta( $_GET['post'], "cf_wptracking_code", true );
  if ( !isset( $cf_page['page_id'] ) || $cf_page['page_id'] < 10 ) {
      $thepage = explode( "{#}", $cf_page );
      $savedData = $cf_page;
  }
  else {
      $thepage = explode( "{#}", $cf_page['page_id'] );
      $savedData = $cf_page['page_id'];
  }
  $cf_options = get_option( "cf_options" );
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.draft').hide();
        console.log("%cClickFunnels WordPress Plugin", "background: #0166AE; color: white; font-size: 23px");
        console.log("%cEditing anything inside the console is for developers only. Do not paste in any code given to you by anyone. Use with caution. Visit for support: https://support.clickfunnels.com/", "color: #888; font-size: 16px");
        var allfunnels = 'https://api.clickfunnels.com/funnels.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';

         jQuery.getJSON(allfunnels, function(data) {
            jQuery.each(data, function() {
            	// Check for Agency
            	if ("<?php echo get_option( 'clickfunnels_agency_group_tag'); ?>" != 'off') {
            		if (this.group_tag) {
            			group_tag = this.group_tag.replace(/(['"])/g, "{replace}");
            			if ("<?php echo get_option( 'clickfunnels_agency_group_tag'); ?>" == group_tag) {
										jQuery('#cf_thefunnel').append('<option value="' + this.id + '">' + this.name + '</option>');
			            	jQuery('#cf_thefunnel_clickpop').append('<option value="' + this.id + '">' + this.name + '</option>');
			            	jQuery('#cf_thefunnel_clickoptin').append('<option value="' + this.id + '">' + this.name + '</option>');
	            		}
            		} else {
            			if ("<?php echo get_option( 'clickfunnels_agency_group_tag'); ?>" == 'ungrouped') {
	            			jQuery('#cf_thefunnel').append('<option value="' + this.id + '">' + this.name + '</option>');
			            	jQuery('#cf_thefunnel_clickpop').append('<option value="' + this.id + '">' + this.name + '</option>');
			            	jQuery('#cf_thefunnel_clickoptin').append('<option value="' + this.id + '">' + this.name + '</option>');
	            		}
            		}
            	}
            	// Show All Funnels
            	else {
	            	jQuery('#cf_thefunnel').append('<option value="' + this.id + '">' + this.name + '</option>');
	            	jQuery('#cf_thefunnel_clickpop').append('<option value="' + this.id + '">' + this.name + '</option>');
	            	jQuery('#cf_thefunnel_clickoptin').append('<option value="' + this.id + '">' + this.name + '</option>');
            	}
            });
          }).fail(function() {
          	jQuery('.badAPI').show();
          });
        // ****************************************************************************************************************************
        // Blog Post
        jQuery( '#cf_thefunnel' ).change(function() {
           jQuery('.choosePageBox').fadeIn();
            var thefunnel = jQuery(this).val();
            var totalPages = 0;
            var specificFunnel = 'https://api.clickfunnels.com/funnels/'+thefunnel+'.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
            jQuery('#cf_thepage').find('option').remove().end();
            jQuery.getJSON(specificFunnel, function(data) {
                // Populate ClickFunnels Page
                jQuery.each(data.funnel_steps, function() {
                  if( this.wp_friendly == true && this.pages != ''  ) {
                      jQuery('#cf_thepage').append('<option value="' + this.pages[0].published_url+'">'+ this.name +'</option>');
                  }
                  jQuery('#cf_shortcode').val('[clickfunnels_embed height="650" url="'+this.pages[0].published_url+'"]');
                  jQuery('#cf_shortcode').select();
                  totalPages += 1;
                });
            }).done(function() {
                jQuery('#loading').fadeOut();
            	jQuery('#cf_thepage').trigger('change');
                if (totalPages == 0) {
                    jQuery('#cf_thepage').hide();
                    jQuery('#noPageWarning').fadeIn();
                }
                else {
                    jQuery('#noPageWarning').hide();
                    jQuery('#cf_thepage').fadeIn();
                }
              })
              .fail(function() {
                jQuery('#loading').fadeOut();
              })
              .always(function() {
                jQuery('#loading').fadeOut();
              });
        });
        jQuery( '#cf_thepage' ).change(function() {
            jQuery('#loading').fadeOut();
             height = jQuery('#cf_height').val();
            theURL = jQuery('#cf_thepage').val();
            scrollCheck = jQuery('#cf_scrolling').val();
            jQuery('#cf_shortcode').val('[clickfunnels_embed height="'+height+'" url="'+theURL+'" scroll="'+scrollCheck+'"]');
            jQuery('#cf_shortcode').select();
        });
        jQuery( '.cf_embedchange' ).change(function() {
            jQuery('#loading').fadeOut();
            height = jQuery('#cf_height').val();
            theURL = jQuery('#cf_thepage').val();
            scrollCheck = jQuery('#cf_scrolling').val();
            jQuery('#cf_shortcode').val('[clickfunnels_embed height="'+height+'" url="'+theURL+'" scroll="'+scrollCheck+'"]');
             jQuery('#cf_shortcode').select();
        });
        // ****************************************************************************************************************************
        // ClickOptin
        jQuery( '#cf_thefunnel_clickoptin' ).change(function() {
           jQuery('.choosePageBox_clickoptin').fadeIn();
            var thefunnel = jQuery(this).val();
            var totalPages = 0;
            var specificFunnel = 'https://api.clickfunnels.com/funnels/'+thefunnel+'.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
            jQuery('#cf_thepage_clickoptin').find('option').remove().end();
                jQuery.getJSON(specificFunnel, function(data) {
                jQuery.each(data.funnel_steps, function() {
                  if( this.wp_friendly == true && this.pages != ''  ) {
                  			var parts = this.pages[0].published_url.split('.');
                        if (this.pages[0].published_url.indexOf('clickfunnels.com') > -1) {
                          subdomain = parts.shift().replace("https://", "");
                        }
                        else {
                          subdomain = parts[0] + '.' + parts[1];
                          subdomain = subdomain.replace("https://", "")
                        }
                        jQuery('#cf_thepage_clickoptin').append('<option value="' + this.pages[0].key+'{#}'+subdomain+'">'+ this.name +'</option>');
                    }
                    placeholder = jQuery('#cf_placeholder').val();
                    button_text = jQuery('#cf_button_text').val();
                    button_color = jQuery('#cf_button_color').val();
                    redirect = jQuery('#cf_redirect').val();
                    input_icon = jQuery('#cf_input_icon').val();
                    jQuery('#cf_shortcode_clickoptin').val('[clickfunnels_clickoptin id="'+this.pages[0].key+'" subdomain="'+subdomain+'" placeholder="'+placeholder+'" button_text="'+button_text+'" button_color="'+button_color+'" redirect="'+redirect+'" input_icon="'+input_icon+'"]');
                        jQuery('#cf_shortcode_clickoptin').select();
                         totalPages += 1;
                });
            }).done(function() {
                jQuery('#loading').fadeOut();
            	jQuery('#cf_thepage_clickoptin').trigger('change');
                if (totalPages == 0) {
                    jQuery('#cf_thepage_clickoptin').hide();
                    jQuery('#noPageWarning_clickoptin').fadeIn();
                }
                else {
                    jQuery('#noPageWarning_clickoptin').hide();
                    jQuery('#cf_thepage_clickoptin').fadeIn();
                }
              })
              .fail(function() {
                jQuery('#loading').fadeOut();
              })
              .always(function() {
                jQuery('#loading').fadeOut();
              });
        });
        jQuery( '#cf_thepage_clickoptin' ).change(function() {
            jQuery('#loading').fadeOut();
            data = jQuery(this).val().split('{#}');
            var parts = data[1].split('.');
						var subdomain = data[1].split('/');
						placeholder = jQuery('#cf_placeholder').val();
            button_text = jQuery('#cf_button_text').val();
            button_color = jQuery('#cf_button_color').val();
            redirect = jQuery('#cf_redirect').val();
            input_icon = jQuery('#cf_input_icon').val();
            jQuery('#cf_shortcode_clickoptin').val('[clickfunnels_clickoptin id="'+data[0]+'" subdomain="'+subdomain[0]+'" placeholder="'+placeholder+'" button_text="'+button_text+'" button_color="'+button_color+'" redirect="'+redirect+'" input_icon="'+input_icon+'"]');
            jQuery('#cf_shortcode_clickoptin').select();
        });
        jQuery( '.cf_optinchange' ).change(function() {
            jQuery('#loading').fadeOut();
            data = jQuery('#cf_thepage_clickoptin').val().split('{#}');
            var parts = data[1].split('.');
						var subdomain = data[1].split('/');
						placeholder = jQuery('#cf_placeholder').val();
            button_text = jQuery('#cf_button_text').val();
            button_color = jQuery('#cf_button_color').val();
            redirect = jQuery('#cf_redirect').val();
            input_icon = jQuery('#cf_input_icon').val();
            jQuery('#cf_shortcode_clickoptin').val('[clickfunnels_clickoptin id="'+data[0]+'" subdomain="'+subdomain[0]+'" placeholder="'+placeholder+'" button_text="'+button_text+'" button_color="'+button_color+'" redirect="'+redirect+'" input_icon="'+input_icon+'"]');
        });
        // ****************************************************************************************************************************
        // ClickPops
        jQuery( '#cf_thefunnel_clickpop' ).change(function() {
        	jQuery('#loading').fadeIn();
           jQuery('.choosePageBox_clickpop').fadeIn();
            var thefunnel = jQuery(this).val();
            var totalPages = 0;
            var specificFunnel = 'https://api.clickfunnels.com/funnels/'+thefunnel+'.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
            jQuery('#cf_thepage_clickpop').find('option').remove().end();
                jQuery.getJSON(specificFunnel, function(data) {
                jQuery.each(data.funnel_steps, function() {
                  if( this.wp_friendly == true && this.pages != ''  ) {
                  			var parts = this.pages[0].published_url.split('.');
												// var subdomain = parts.shift().replace("https://", "");
                        if (this.pages[0].published_url.indexOf('clickfunnels.com') > -1) {
                          subdomain = parts.shift().replace("https://", "");
                        }
                        else {
                          subdomain = parts[0] + '.' + parts[1];
                          subdomain = subdomain.replace("https://", "")
                        }
                        jQuery('#cf_thepage_clickpop').append('<option value="' + this.pages[0].key+'{#}'+subdomain+'">'+ this.name +'</option>');
                    		jQuery('#cf_shortcode_clickpop').val('[clickfunnels_clickpop id="'+this.pages[0].key+'" subdomain="'+subdomain+'"]Your Content[/clickfunnels_clickpop]');
                        jQuery('#cf_shortcode_clickpop').select();
                        totalPages += 1;
                    }
                });
            }).done(function() {
              jQuery('#loading').fadeOut();
            	jQuery('#cf_thepage_clickpop').trigger('change');
                if (totalPages == 0) {
                    jQuery('#cf_thepage_clickpop').hide();
                    jQuery('#noPageWarning_clickpop').fadeIn();
                }
                else {
                    jQuery('#noPageWarning_clickpop').hide();
                    jQuery('#cf_thepage_clickpop').fadeIn();
                }
              })
              .fail(function() {
                jQuery('#loading').fadeOut();
              })
              .always(function() {
                jQuery('#loading').fadeOut();
              });
        });
        jQuery( '#cf_thepage_clickpop' ).change(function() {
            jQuery('#loading').fadeOut();
            showOnExit = '';
            if(jQuery('#cf_exit').val() == 'true') {
            	showOnExit = 'exit="true" ';
            }
            showDelay = '';
            if(jQuery('#cf_delay').val() != '') {
            	showDelay = 'delay="'+jQuery('#cf_delay').val()+'" ';
            }
            data = jQuery(this).val().split('{#}');
						var subdomain = data[1].split('/');
            jQuery('#cf_shortcode_clickpop').val('[clickfunnels_clickpop '+showOnExit+showDelay+'id="'+data[0]+'" subdomain="'+subdomain[0]+'"]Your Content[/clickfunnels_clickpop]');
            jQuery('#cf_shortcode_clickpop').select();
        });
        jQuery( '#cf_exit' ).change(function() {
            jQuery('#loading').fadeOut();
            showOnExit = '';
            if(jQuery(this).val() == 'true') {
            	showOnExit = 'exit="true" ';
            }
            showDelay = '';
            if(jQuery('#cf_delay').val() != '') {
            	showDelay = 'delay="'+jQuery('#cf_delay').val()+'" ';
            }
            data = jQuery('#cf_thepage_clickpop').val().split('{#}');
            var subdomain = data[1].split('/');
            jQuery('#cf_shortcode_clickpop').val('[clickfunnels_clickpop '+showOnExit+showDelay+'id="'+data[0]+'" subdomain="'+subdomain[0]+'"]Your Content[/clickfunnels_clickpop]');
            jQuery('#cf_shortcode_clickpop').select();
        });
        jQuery( '#cf_delay' ).change(function() {
            jQuery('#loading').fadeOut();
            showOnExit = '';
            if(jQuery('#cf_exit').val() == 'true') {
            	showOnExit = 'exit="true" ';
            }
            showDelay = '';
            if(jQuery(this).val() != '') {
            	showDelay = 'delay="'+jQuery('#cf_delay').val()+'" ';
            }
            data = jQuery('#cf_thepage_clickpop').val().split('{#}');
            var subdomain = data[1].split('/');
            jQuery('#cf_shortcode_clickpop').val('[clickfunnels_clickpop '+showOnExit+showDelay+'id="'+data[0]+'" subdomain="'+subdomain[0]+'"]Your Content[/clickfunnels_clickpop]');
            jQuery('#cf_shortcode_clickpop').select();
        });
        // ****************************************************************************************************************************
				// Tabs
				jQuery('.cftablink').click(function() {
				    if (jQuery(this).hasClass('disabledLink')=== true) {
				    }
				    else {
				        jQuery('.cftabs').hide();
				        jQuery('.cftablink').removeClass('active');
				        jQuery(this).addClass('active');
				        var tab = jQuery(this).attr('data-tab');
				        jQuery('#'+tab).show();
				    }
				});
	});
</script>
<?php
    if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
    }
    else {
        if ( $cf_page != "" && is_readable('https://api.clickfunnels.com/funnels.json?email='.get_option( 'clickfunnels_api_email' ).'&auth_token='.get_option( 'clickfunnels_api_auth'))) {
            $response = wp_remote_get( 'https://api.clickfunnels.com/funnels/'.$cf_thefunnel.'.json?email='.get_option( 'clickfunnels_api_email' ).'&auth_token='.get_option( 'clickfunnels_api_auth' ));
            if( is_array($response) ) {
              $json = $response['body']; // use the content
            }
            $cf_funnels_pages = json_decode( $json );
        }
        else if ($cf_funnels == '' && $cf_thefunnel != '') {
        $response = get_file_content( 'https://api.clickfunnels.com/funnels/'.$cf_thefunnel.'.json?email='.get_option( 'clickfunnels_api_email' ).'&auth_token='.get_option( 'clickfunnels_api_auth' ));
          if( is_array($response) ) {
            $json = $response['body']; // use the content
          }
          $cf_funnels_pages = json_decode( $json );
      }
    }
?>
<div id="message" class="badAPI error notice" style="display: none; width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i> Failed API Connection with ClickFunnels. Check <a href="edit.php?post_type=clickfunnels&page=cf_api&error=compatibility">Settings > Compatibility Check</a> for details.</div>
<div class="api postbox" style="width: 780px;margin-top: 20px;">
	<!-- Header -->
	<?php include('header.php'); ?>
	<div class="apiSubHeader" style="padding: 18px 16px;">
		<h2 style="font-size: 1.5em"><i class="fa fa-code" style="margin-right: 5px"></i> Shortcode Generator</h2>
	</div>
	<!-- Sidebar Navigation -->
	<div class="bootstrap-wp">
		<div id="app_sidebar">
			<a href="#" data-tab="tab1" class="cftablink <?php if(!$_GET['error']) { echo 'active';} ?>">Embed Code</a>
			<a href="#" data-tab="tab2" class="cftablink">ClickPop</a>
			<a href="#" data-tab="tab3" class="cftablink">ClickForms</a>
		</div>
		<div id="app_main">
			<div id="tab4" class="cftabs"  style="display: none">
				<h2>Shortcode Settings</h2>
			</div>
			<!-- ClickOptin -->
			<div id="tab3" class="cftabs"  style="display: none">
				<h2>Collect Leads with ClickForm</h2>
				<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Generate a email optin form with a simple Email Input and Submit Button on any of your blog posts or pages. Select a funnel and customize settings to your needs. The thank you page will be the next page in your funnel after the one you choose below.</p>
				<div class="control-group cf_uses_api clearfix" style="">
					<label class="control-label" for="cf_thefunnel_clickoptin"> Choose Funnel  </label>
					<div class="controls">
						<select class="input-xlarge" id="cf_thefunnel_clickoptin" style="width: 450px !important;margin-left: 26px !important;" name="cf_thefunnel_backup">
							<option value="0">Select a Funnel</option>
						</select>
					</div>
				</div>
				<div class="control-group choosePageBox_clickoptin clearfix" style="display: none">
					<label class="control-label" for="cf_thepage_clickoptin"> Choose Page  <i class="fa fa-spinner fa-spin" id="loading"></i></label>
					<div class="controls">
						<select class="input-xlarge " id="cf_thepage_clickoptin"  style="width: 450px !important;margin-left: 26px !important;" name="cf_thepage">
							<option value="0">No Pages Found</option>
						</select>
					</div>
					<div id="noPageWarning_clickoptin" style="font-size: 11px; margin-left: 26px !important; margin-top: -13px;float: left;padding-top: 10px;display: none;width: 100%; clear: both">
						<strong style="font-size: 13px;display: block;">No compatible pages found. </strong>
						<em style="display: block">Membership pages and order pages are not available through plugin.</em>
					</div>
				</div>
				<div class="control-group choosePageBox_clickoptin cf_uses_api clearfix" style="display: none">
					<label class="control-label" for="" style="width: 450px !important;"> Placeholder Text</label>
					<div class="controls">
						<input type="text" class="input-xlarge cf_optinchange" id="cf_placeholder" style="width: 450px !important;margin-left: 26px" value="" placeholder="Text to display for placeholder on email input..." >
					</div>
				</div>
				<div class="control-group choosePageBox_clickoptin cf_uses_api clearfix" style="display: none">
					<label class="control-label" for="" style="width: 450px !important;"> Button Text</label>
					<div class="controls">
						<input type="text" class="input-xlarge cf_optinchange" id="cf_button_text" style="width: 450px !important;margin-left: 26px" value="" placeholder="Text to display on subscribe button..." >
					</div>
				</div>
				<div class="control-group choosePageBox_clickoptin cf_uses_api clearfix" style="display: none">
					<label class="control-label" for=""> Button Color</label>
					<select class="input-xlarge cf_optinchange" id="cf_button_color"  style="width: 450px !important;margin-left: 26px" name="">
						<option value="blue">Blue</option>
						<option value="red">Red</option>
						<option value="grey">Grey</option>
						<option value="green">Green</option>
						<option value="black">Black</option>
					</select>
				</div>
				<div class="control-group choosePageBox_clickoptin cf_uses_api clearfix" style="display: none">
					<label class="control-label" for=""> Redirect on Submit</label>
					<select class="input-xlarge cf_optinchange" id="cf_redirect"  style="width: 450px !important;margin-left: 26px" name="">
						<option value="">Submit Form in Same Page</option>
						<option value="newtab">Submit Form in New Tab</option>
					</select>
				</div>
				<div class="control-group choosePageBox_clickoptin cf_uses_api clearfix" style="display: none">
					<label class="control-label" for=""> Input Icon</label>
					<select class="input-xlarge cf_optinchange" id="cf_input_icon"  style="width: 450px !important;margin-left: 26px" name="">
						<option value="show">Show Envelope Icon</option>
						<option value="emailiimage">Hide Envelope Icon</option>
					</select>
				</div>
				<div class="control-group choosePageBox_clickoptin" style="display: none">
					<label class="control-label" for="cf_iframe_check"> ClickOptin Shortcode </label>
					<div class="controls">
						<textarea  class="input-xlarge " id="cf_shortcode_clickoptin" style="width: 450px !important;height: 80px;margin-left: 26px"  placeholder="Shortcode embed code here..."></textarea>
					</div>
				</div>
			</div>
			<!-- ClickPop Shortcode -->
			<div id="tab2" class="cftabs" style="display: none">
				<h2>Show ClickPop Link or Automated Popup</h2>
				<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Copy and paste the shortcode onto any blog post you want the ClickPop link to appear on. If you have show on exit you can leave the content blank. You can also surround the shortcode with any content in your blog such as images, everything inside the [] code will be a ClickPop link.</p>
				<div class="control-group cf_uses_api clearfix" style="">
					<label class="control-label" for="cf_thefunnel_clickpop"> Choose Funnel  </label>
					<div class="controls">
						<select class="input-xlarge" id="cf_thefunnel_clickpop" style="width: 450px !important;margin-left: 26px !important;" name="cf_thefunnel_backup">
							<option value="0">Select a Funnel</option>
						</select>
					</div>
				</div>
				<div class="control-group choosePageBox_clickpop clearfix" style="display: none">
					<label class="control-label" for="cf_thepage_clickpop"> Choose Page  <i class="fa fa-spinner fa-spin" id="loading"></i></label>
					<div class="controls">
						<select class="input-xlarge" id="cf_thepage_clickpop"  style="width: 450px !important;margin-left: 26px !important;" name="cf_thepage">
							<option value="0">No Pages Found</option>
						</select>
					</div>
					<div id="noPageWarning_clickpop" style="font-size: 11px; margin-left: 26px !important; margin-top: -13px;float: left;padding-top: 10px;display: none;width: 100%; clear: both">
						<strong style="font-size: 13px;display: block;">No compatible pages found. </strong>
						<em style="display: block">Membership pages and order pages are not available through plugin.</em>
					</div>
				</div>
				<div class="control-group choosePageBox_clickpop cf_uses_api clearfix" style="display: none">
					<label class="control-label" for="cf_thefunnel"> Show on Mouse Exit  <small style="margin-right: 32px">(optional)</small></label>
					<select class="input-xlarge" id="cf_exit"  style="width: 450px !important;margin-left: 26px" name="">
						<option value="false">Disabled</option>
						<option value="true">Enable Popup on Mouse Leave</option>
					</select>
				</div>
				<div class="control-group choosePageBox_clickpop cf_uses_api clearfix" style="display: none">
					<label class="control-label" for="cf_thefunnel" style="width: 450px !important;"> Timed Popup Delay <small style="margin-right: 0;">(optional)</small> </label>
					<div class="controls">
						<input type="text" class="input-xlarge " id="cf_delay" style="width: 450px !important;margin-left: 26px" value="" placeholder="Number of seconds for automatic popup." >
					</div>
				</div>
				<div class="control-group choosePageBox_clickpop" style="display: none">
					<label class="control-label" for="cf_iframe_check_clickpop"> ClickPop Shortcode </label>
					<div class="controls">
						<textarea  class="input-xlarge " id="cf_shortcode_clickpop" style="height: 80px;width: 450px !important;margin-left: 26px"  placeholder="Shortcode embed code here..."></textarea>
					</div>
				</div>
			</div>
			<!-- Blog Post Shortcode -->
			<div id="tab1" class="cftabs">
				<h2>Embed ClickFunnels Page on a Blog Post</h2>
				<p class="infoHelp"><i class="fa fa-question-circle" style="margin-right: 3px"></i> Copy and paste the shortcode onto any blog post you want the ClickFunnels page to appear on. <strong>Adjust the height number to make sure you page is fully displayed.</strong> Pages such as Order Pages and Membership pages are excluded.</p>
				<div class="control-group cf_uses_api clearfix" style="">
					<label class="control-label" for="cf_thefunnel"> Choose Funnel  </label>
					<div class="controls">
						<select class="input-xlarge" id="cf_thefunnel" style="width: 450px !important;margin-left: 26px" name="cf_thefunnel_backup">
							<option value="0">Select a Funnel</option>
						</select>
					</div>
				</div>
				<div class="control-group choosePageBox clearfix" style="<?php if ( empty( $_GET['action'] ) ) {  echo "display: none"; } ?>">
					<label class="control-label" for="cf_thepage"> Choose Page  <i class="fa fa-spinner fa-spin" id="loading"></i></label>
					<div class="controls">
						<select class="input-xlarge" id="cf_thepage"  style="width: 450px !important;margin-left: 26px" name="cf_thepage">
							<option value="0">No Pages Found</option>
						</select>
					</div>
					<div id="noPageWarning" style="font-size: 11px; margin-left: 26px !important; margin-top: -13px;float: left;padding-top: 10px;display: none;width: 100%; clear: both">
						<strong style="font-size: 13px;display: block;">No compatible pages found. </strong>
						<em style="display: block">Membership pages and order pages are not available through plugin.</em>
					</div>
				</div>
                <div class="control-group choosePageBox  clearfix" style="display: none">
                    <label class="control-label" for="cf_height" style="width: 450px !important;"> Height of Iframe</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge cf_embedchange" id="cf_height" style="width: 450px !important;margin-left: 26px" placeholder="Number for height in px." value="650" >
                    </div>
                </div>
                <div class="control-group choosePageBox clearfix" style="display: none">
                    <label class="control-label" for="cf_scrolling"> Allow Page Scrolling  <small style="margin-right: 32px">(optional)</small></label>
                    <select class="input-xlarge cf_embedchange" id="cf_scrolling"  style="width: 450px !important;margin-left: 26px" name="">
                        <option value="yes">Enable Scrolling</option>
                        <option value="no">Disable Scrolling</option>
                    </select>
                </div>
				<div class="control-group choosePageBox" style="display: none">
					<label class="control-label" for="cf_iframe_check" > Blog Embed Shortcode </label>
					<div class="controls">
						<textarea  class="input-xlarge " id="cf_shortcode" style="width: 450px !important;height: 80px;margin-left: 26px"  placeholder="Shortcode embed code here..."></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</div>
