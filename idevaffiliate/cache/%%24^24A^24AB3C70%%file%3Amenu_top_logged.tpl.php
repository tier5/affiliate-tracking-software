<?php /* Smarty version 2.6.28, created on 2016-12-05 17:09:02
         compiled from file:menu_top_logged.tpl */ ?>
<?php if (isset ( $this->_tpl_vars['affiliateUsername'] )): ?>
<div class="b-right hidden-xs"<?php if (! isset ( $this->_tpl_vars['cp_menu_location'] )): ?> style="margin-top: -1px; border-bottom: 1px solid <?php echo $this->_tpl_vars['background_color']; ?>
;"<?php endif; ?>>
<div style="display:none;" class="menu-button"><span>Touch for more options</span><img alt="" src="images/collapse-top.png" /></div>
<ul class="prime">

<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=1"><?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
</a></li>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=2"><?php echo $this->_tpl_vars['menu_drop_tier_stats']; ?>
</a></li>
</ul>
</li>
<?php else: ?>
<li><a href="account.php?page=1" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_drop_general_stats']; ?>
</a></li>
<?php endif; ?>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_heading_marketing']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<?php if (isset ( $this->_tpl_vars['coupon_codes_available'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=44"><?php echo $this->_tpl_vars['menu_coupon']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['videomarketing_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=47"><?php echo $this->_tpl_vars['menu_marketing_videos']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['announcement_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=45"><?php echo $this->_tpl_vars['menu_announcements']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['banner_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=7"><?php echo $this->_tpl_vars['menu_banners']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['qr_codes_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=42"><?php echo $this->_tpl_vars['qr_code_title']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['page_peel_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=37"><?php echo $this->_tpl_vars['menu_page_peels']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['lightbox_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=38"><?php echo $this->_tpl_vars['menu_lightboxes']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['textad_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=8"><?php echo $this->_tpl_vars['menu_text_ads']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['htmlcount'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=23"><?php echo $this->_tpl_vars['menu_html_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['textlink_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=9"><?php echo $this->_tpl_vars['menu_text_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['email_links_available'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=10"><?php echo $this->_tpl_vars['menu_email_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['etemplates_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=28"><?php echo $this->_tpl_vars['menu_etemplates']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['offline_marketing'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=11"><?php echo $this->_tpl_vars['menu_offline']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['second_tier'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=12"><?php echo $this->_tpl_vars['menu_tier_linking_code']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pdf_marketing_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=24"><?php echo $this->_tpl_vars['menu_pdf_marketing']; ?>
</a></li><?php endif; ?>
</ul>
</li>

<?php if (isset ( $this->_tpl_vars['custom_tracking_enabled'] )): ?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_heading_custom_links']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<?php if (isset ( $this->_tpl_vars['custom_links_enabled'] ) || isset ( $this->_tpl_vars['sub_affiliates_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=36"><?php echo $this->_tpl_vars['menu_custom_reports']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['custom_links_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=14"><?php echo $this->_tpl_vars['menu_keyword_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['sub_affiliates_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=26"><?php echo $this->_tpl_vars['menu_subid_links']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['alternate_keywords_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=35"><?php echo $this->_tpl_vars['menu_alteranate_links']; ?>
</a></li><?php endif; ?>
</ul>
</li>	
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['commission_alert'] )): ?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_heading_additional']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=15"><?php echo $this->_tpl_vars['menu_comalert']; ?>
</a></li>
</ul>
</li>	
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['menu_heading_training_materials'] )): ?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_heading_training_materials']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<?php if (isset ( $this->_tpl_vars['training_videos'] ) || isset ( $this->_tpl_vars['uploaded_training_videos'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=39"><?php echo $this->_tpl_vars['menu_videos']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pdf_training_count'] )): ?><li><hr style="margin:0px;"></li><li><a href="account.php?page=25"><?php echo $this->_tpl_vars['menu_pdf_training']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['custom_tracking_enabled'] )): ?><li><hr style="margin:0px;"></li><li><a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank"><?php echo $this->_tpl_vars['menu_custom_manual']; ?>
</a></li><?php endif; ?>
</ul>
</li>	
<?php endif; ?>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_drop_heading_commissions']; ?>
 <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=1"><?php echo $this->_tpl_vars['menu_drop_current']; ?>
</a></li>
<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=2"><?php echo $this->_tpl_vars['menu_drop_tier']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pending_enabled'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=3"><?php echo $this->_tpl_vars['menu_drop_pending']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['delayed_enabled'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=6"><?php echo $this->_tpl_vars['menu_drop_delayed']; ?>
</a></li>
<?php endif; ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=4"><?php echo $this->_tpl_vars['menu_drop_paid']; ?>
</a></li>
<?php if (isset ( $this->_tpl_vars['tier_enabled'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=5"><?php echo $this->_tpl_vars['menu_drop_paid_rec']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['recurring_enabled'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=5"><?php echo $this->_tpl_vars['menu_drop_recurring']; ?>
</a></li>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['show_debits'] )): ?>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=46"><?php echo $this->_tpl_vars['menu_drop_pending_debits']; ?>
</a></li>
<?php endif; ?>
</ul>
</li>



<li><a href="account.php?page=3" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_drop_heading_history']; ?>
</a></li>
<li><a href="account.php?page=6" style="background-color: <?php echo $this->_tpl_vars['cp_main_menu_color']; ?>
; color:<?php echo $this->_tpl_vars['cp_main_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_drop_heading_traffic']; ?>
</a></li>

</ul>
</div>
<?php endif; ?>