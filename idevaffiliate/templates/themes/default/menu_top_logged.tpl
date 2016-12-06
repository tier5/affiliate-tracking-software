{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}
{if isset($affiliateUsername)}
<div class="b-right hidden-xs"{if !isset($cp_menu_location)} style="margin-top: -1px; border-bottom: 1px solid {$background_color};"{/if}>
<div style="display:none;" class="menu-button"><span>Touch for more options</span><img alt="" src="images/collapse-top.png" /></div>
<ul class="prime">

{if isset($tier_enabled)}
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_drop_general_stats} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=1">{$menu_drop_general_stats}</a></li>
<li><hr style="margin:0px;"></li><li><a href="account.php?page=2">{$menu_drop_tier_stats}</a></li>
</ul>
</li>
{else}
<li><a href="account.php?page=1" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_drop_general_stats}</a></li>
{/if}

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_heading_marketing} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
{if isset($coupon_codes_available)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=44">{$menu_coupon}</a></li>{/if}
{if isset($videomarketing_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=47">{$menu_marketing_videos}</a></li>{/if}
{if isset($announcement_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=45">{$menu_announcements}</a></li>{/if}
{if isset($banner_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=7">{$menu_banners}</a></li>{/if}
{if isset($qr_codes_enabled)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=42">{$qr_code_title}</a></li>{/if}
{if isset($page_peel_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=37">{$menu_page_peels}</a></li>{/if}
{if isset($lightbox_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=38">{$menu_lightboxes}</a></li>{/if}
{if isset($textad_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=8">{$menu_text_ads}</a></li>{/if}
{if isset($htmlcount)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=23">{$menu_html_links}</a></li>{/if}
{if isset($textlink_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=9">{$menu_text_links}</a></li>{/if}
{if isset($email_links_available)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=10">{$menu_email_links}</a></li>{/if}
{if isset($etemplates_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=28">{$menu_etemplates}</a></li>{/if}
{if isset($offline_marketing)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=11">{$menu_offline}</a></li>{/if}
{if isset($second_tier)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=12">{$menu_tier_linking_code}</a></li>{/if}
{if isset($pdf_marketing_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=24">{$menu_pdf_marketing}</a></li>{/if}
</ul>
</li>

{if isset($custom_tracking_enabled)}
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_heading_custom_links} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
{if isset($custom_links_enabled) || isset($sub_affiliates_enabled)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=36">{$menu_custom_reports}</a></li>{/if}
{if isset($custom_links_enabled)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=14">{$menu_keyword_links}</a></li>{/if}
{if isset($sub_affiliates_enabled)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=26">{$menu_subid_links}</a></li>{/if}
{if isset($alternate_keywords_enabled)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=35">{$menu_alteranate_links}</a></li>{/if}
</ul>
</li>	
{/if}

{if isset($commission_alert)}
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_heading_additional} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=15">{$menu_comalert}</a></li>
</ul>
</li>	
{/if}

{if isset($menu_heading_training_materials)}
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_heading_training_materials} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
{if isset($training_videos) || isset($uploaded_training_videos)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=39">{$menu_videos}</a></li>{/if}
{if isset($pdf_training_count)}<li><hr style="margin:0px;"></li><li><a href="account.php?page=25">{$menu_pdf_training}</a></li>{/if}
{if isset($custom_tracking_enabled)}<li><hr style="margin:0px;"></li><li><a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank">{$menu_custom_manual}</a></li>{/if}
</ul>
</li>	
{/if}

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_drop_heading_commissions} <i class="caret"></i></a>
<ul class="dropdown-menu dropdown-primary pull-left">
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=1">{$menu_drop_current}</a></li>
{if isset($tier_enabled)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=2">{$menu_drop_tier}</a></li>
{/if}
{if isset($pending_enabled)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=3">{$menu_drop_pending}</a></li>
{/if}
{if isset($delayed_enabled)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=6">{$menu_drop_delayed}</a></li>
{/if}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=4">{$menu_drop_paid}</a></li>
{if isset($tier_enabled)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=4&report=5">{$menu_drop_paid_rec}</a></li>
{/if}
{if isset($recurring_enabled)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=5">{$menu_drop_recurring}</a></li>
{/if}
{if isset($show_debits)}
<li><hr style="margin:0px;"></li><li><a href="account.php?page=46">{$menu_drop_pending_debits}</a></li>
{/if}
</ul>
</li>



<li><a href="account.php?page=3" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_drop_heading_history}</a></li>
<li><a href="account.php?page=6" style="background-color: {$cp_main_menu_color}; color:{$cp_main_menu_text};">{$menu_drop_heading_traffic}</a></li>

</ul>
</div>
{/if}