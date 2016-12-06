<?php /* Smarty version 2.6.28, created on 2016-12-05 15:49:13
         compiled from file:menu_left_column.tpl */ ?>

<nav class="navbar-side sidebar-light<?php if (! isset ( $this->_tpl_vars['cp_menu_location'] ) || ! isset ( $this->_tpl_vars['inner_page'] )): ?> collapsed<?php endif; ?>" role="navigation" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<?php if (isset ( $this->_tpl_vars['cp_fixed_left_menu'] )): ?> <div class="navbar-outer"><?php endif; ?>
<div class="navbar-collapse sidebar-collapse collapse">
<ul id="side" class="nav navbar-nav side-nav">

<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#general_stats" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i></a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'general_stats' )): ?> in<?php endif; ?>" id="general_stats">
<li><a href="account.php?page=1"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'general_stats' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
</a></li>
<li><a href="account.php?page=2"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'tier_stats' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_tier_stats']; ?>
</a></li>
</ul>
</li>
<?php else: ?>
<li><a href="account.php?page=1"><i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
</a></li>
<?php endif; ?>

<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#comms" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_heading_commissions']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i>
</a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'comms' )): ?> in<?php endif; ?>" id="comms">
<li><a href="account.php?page=4&report=1"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_current' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_current']; ?>
</a></li>
<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li><a href="account.php?page=4&report=2"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_tier' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_tier']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pending_enabled'] )): ?>
<li><a href="account.php?page=4&report=3"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_pending' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_pending']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['delayed_enabled'] )): ?>
<li><a href="account.php?page=4&report=6"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_delayed' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_delayed']; ?>
</a></li>
<?php endif; ?>
<li><a href="account.php?page=4&report=4"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_paid' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_paid']; ?>
</a></li>
<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li><a href="account.php?page=4&report=5"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_paid_tier' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_paid_rec']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['recurring_enabled'] )): ?>
<li><a href="account.php?page=5"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'comms_rec' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_drop_recurring']; ?>
</a></li>
<?php endif; ?>
</ul>
</li>

<?php if (isset ( $this->_tpl_vars['show_debits'] )): ?>
<li><a href="account.php?page=46" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_pending_debits']; ?>
</a></li>
<?php endif; ?>

<li><a href="account.php?page=3" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_heading_history']; ?>
</a></li>
<li><a href="account.php?page=6" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_drop_heading_traffic']; ?>
</a></li>

<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#marketing" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_heading_marketing']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i></a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'marketing' )): ?> in<?php endif; ?>" id="marketing">
<?php if (isset ( $this->_tpl_vars['coupon_codes_available'] )): ?><li><a href="account.php?page=44"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'coupons' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_coupon']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['videomarketing_count'] )): ?><li><a href="account.php?page=47"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'video_marketing' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_marketing_videos']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['announcement_count'] )): ?><li><a href="account.php?page=45"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'social_media' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_announcements']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['banner_count'] )): ?><li><a href="account.php?page=7"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'banners' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_banners']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['qr_codes_enabled'] )): ?><li><a href="account.php?page=42"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'qr_codes' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['qr_code_title']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['page_peel_count'] )): ?><li><a href="account.php?page=37"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'peels' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_page_peels']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['lightbox_count'] )): ?><li><a href="account.php?page=38"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'lightboxes' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_lightboxes']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['textad_count'] )): ?><li><a href="account.php?page=8"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'textads' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_text_ads']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['htmlcount'] )): ?><li><a href="account.php?page=23"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'htmlads' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_html_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['textlink_count'] )): ?><li><a href="account.php?page=9"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'text_links' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_text_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['email_links_available'] )): ?><li><a href="account.php?page=10"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'email_links' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_email_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['etemplates_count'] )): ?><li><a href="account.php?page=28"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'email_templates' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_etemplates']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['offline_marketing'] )): ?><li><a href="account.php?page=11"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'offline' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_offline']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['second_tier'] )): ?><li><a href="account.php?page=12"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'tiers' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_tier_linking_code']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pdf_marketing_count'] )): ?><li><a href="account.php?page=24"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'pdf_marketing' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_pdf_marketing']; ?>
</a></li><?php endif; ?>
</ul>
</li>

<?php if (isset ( $this->_tpl_vars['custom_tracking_enabled'] )): ?>
<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#custom" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_heading_custom_links']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i></a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'custom' )): ?> in<?php endif; ?>" id="custom">
<?php if (isset ( $this->_tpl_vars['custom_links_enabled'] ) || isset ( $this->_tpl_vars['sub_affiliates_enabled'] )): ?><li><a href="account.php?page=36"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'reports' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_custom_reports']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['custom_links_enabled'] )): ?><li><a href="account.php?page=14"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'keywords' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_keyword_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['sub_affiliates_enabled'] )): ?><li><a href="account.php?page=26"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'subs' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_subid_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['alternate_keywords_enabled'] )): ?><li><a href="account.php?page=35"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'alternate' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_alteranate_links']; ?>
</a></li><?php endif; ?>
</ul>
</li>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['training_materials'] )): ?>
<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#tm" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_heading_training_materials']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i></a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'tm' )): ?> in<?php endif; ?>" id="tm">
<?php if (isset ( $this->_tpl_vars['training_videos'] ) || isset ( $this->_tpl_vars['uploaded_training_videos'] )): ?><li><a href="account.php?page=39"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'videos' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_videos']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pdf_training_count'] )): ?><li><a href="account.php?page=25"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'pdf_training' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_pdf_training']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['custom_tracking_enabled'] )): ?><li><a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank"><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_custom_manual']; ?>
</a></li><?php endif; ?>
</ul>
</li>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['commission_alert'] )): ?>
<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#additional" style="background-color:<?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;">
<i class="fa fa-caret-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i> <?php echo $this->_tpl_vars['menu_heading_additional']; ?>
 <i class="fa fa-angle-down pull-right" style="color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"></i>
</a>
<ul class="collapse nav<?php if (isset ( $this->_tpl_vars['main_menu_group'] ) && ( $this->_tpl_vars['main_menu_group'] == 'additional' )): ?> in<?php endif; ?>" id="additional">
<li><a href="account.php?page=15"<?php if (isset ( $this->_tpl_vars['sub_menu_group'] ) && ( $this->_tpl_vars['sub_menu_group'] == 'commissionalert' )): ?> class="active"<?php endif; ?>><i class="fa fa-angle-double-right"></i> <?php echo $this->_tpl_vars['menu_comalert']; ?>
</a></li>
</ul>
</li>
<?php endif; ?>
</ul>
</div>

<?php if (isset ( $this->_tpl_vars['affiliate_library_access'] )): ?>
<div style="padding-top:15px; padding-bottom: 15px; text-align:center;" class="hidden-xs">
<form method="post" target="_blank" action="http://www.affiliatelibrary.com/welcome/index.php">
<input type="hidden" name="aff_fname" value="<?php echo $this->_tpl_vars['aff_fname']; ?>
" />
<input type="hidden" name="aff_lname" value="<?php echo $this->_tpl_vars['aff_lname']; ?>
" />
<input type="hidden" name="aff_email" value="<?php echo $this->_tpl_vars['aff_email']; ?>
" />
<button class="btn btn-primary"><?php echo $this->_tpl_vars['aff_lib_button']; ?>
</button>
</form>
</div>
<?php if (isset ( $this->_tpl_vars['cp_fixed_left_menu'] )): ?> </div><?php endif; ?>
<?php endif; ?>

</nav>