{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{include file='file:header.tpl'}

{if isset($re_accept)}
{include file='file:tandc_re-accept.tpl'}

{else}

{if isset($re_accept)}
<div class="row">
{include file='file:tandc_re-accept.tpl'}
</div>

{else}

<div class="row">
<div class="col-lg-9">

{if !isset($cp_menu_location) || !isset($inner_page)}
<div class="breadcrumbs" style="background-color: {$cp_main_menu_color};{if !isset($cp_menu_location)} border-bottom: 1px solid {$background_color};{/if}">
{include file='file:menu_top_logged.tpl'}
</div>
{/if}

</div>
</div>

{if isset($page_not_authorized)}
{include file='file:account_pending_approval.tpl'}

{elseif isset($affiliate_suspended)}
{include file='file:account_suspended.tpl'}

{else}

{if isset($payment_update_notice)}{$payment_update_notice}{/if}

{if $internal_page == 1}
{include file='file:account_general_stats.tpl'}
{elseif $internal_page == 2}
{include file='file:account_tier_stats.tpl'}
{elseif $internal_page == 3}
{include file='file:account_payment_history.tpl'}
{elseif $internal_page == 4}
{if isset($sub_affiliates_enabled)}
{include file='file:account_commission_list_subs.tpl'}
{else}
{include file='file:account_commission_list.tpl'}
{/if}
{elseif $internal_page == 5}
{if isset($sub_affiliates_enabled)}
{include file='file:account_recurring_commissions_subs.tpl'}
{else}
{include file='file:account_recurring_commissions.tpl'}
{/if}
{elseif $internal_page == 6}
{include file='file:account_traffic_log.tpl'}
{elseif $internal_page == 7}
{include file='file:account_banners.tpl'}
{elseif $internal_page == 8}
{include file='file:account_text_ads.tpl'}
{elseif $internal_page == 9}
{include file='file:account_text_links.tpl'}
{elseif $internal_page == 10}
{include file='file:account_email_links.tpl'}
{elseif $internal_page == 11}
{include file='file:account_offline_marketing.tpl'}
{elseif $internal_page == 12}
{include file='file:account_tier_code.tpl'}
{elseif $internal_page == 13}
{include file='file:account_email_friends.tpl'}
{elseif $internal_page == 14}
{include file='file:account_keyword_links.tpl'}
{elseif $internal_page == 15}
{include file='file:account_commission_alert.tpl'}
{elseif $internal_page == 16}
{include file='file:account_commission_stats.tpl'}
{elseif $internal_page == 17}
{include file='file:account_edit.tpl'}
{elseif $internal_page == 18}
{include file='file:account_change_password.tpl'}
{elseif $internal_page == 19}
{include file='file:account_change_commission.tpl'}
{elseif $internal_page == 21}
{include file='file:account_faq.tpl'}
{elseif $internal_page == 22}
{include file='file:account_commission_details.tpl'}
{elseif $internal_page == 23}
{include file='file:account_html_ads.tpl'}
{elseif $internal_page == 24}
{include file='file:account_pdf_marketing.tpl'}
{elseif $internal_page == 25}
{include file='file:account_pdf_training.tpl'}
{elseif $internal_page == 26}
{include file='file:account_sub_affiliates.tpl'}
{elseif $internal_page == 27}
{include file='file:account_upload_logo.tpl'}
{elseif $internal_page == 28}
{include file='file:account_email_templates.tpl'}
{elseif $internal_page == 29}
{include file='file:account_sub_affiliates_test.tpl'}
{elseif $internal_page == 30}
{include file='file:custom/30.tpl'}
{elseif $internal_page == 31}
{include file='file:custom/31.tpl'}
{elseif $internal_page == 32}
{include file='file:custom/32.tpl'}
{elseif $internal_page == 33}
{include file='file:custom/33.tpl'}
{elseif $internal_page == 34}
{include file='file:custom/34.tpl'}
{elseif $internal_page == 35}
{include file='file:account_alternate_page_links.tpl'}
{elseif $internal_page == 36}
{include file='file:account_custom_reports.tpl'}
{elseif $internal_page == 37}
{include file='file:account_page_peels.tpl'}
{elseif $internal_page == 38}
{include file='file:account_lightboxes.tpl'}
{elseif $internal_page == 39}
{include file='file:training_videos.tpl'}
{elseif $internal_page == 40}
{include file='file:account_direct_links.tpl'}
{elseif $internal_page == 41}
{include file='file:account_testimonials.tpl'}
{elseif $internal_page == 42}
{include file='file:account_qr_codes.tpl'}
{elseif $internal_page == 43}
{include file='file:account_upload_picture.tpl'}
{elseif $internal_page == 44}
{include file='file:account_coupon_codes.tpl'}
{elseif $internal_page == 45}
{include file='file:account_announcements.tpl'}
{elseif $internal_page == 46}
{include file='file:account_debits.tpl'}
{elseif $internal_page == 47}
{include file='file:account_marketing_videos.tpl'}
{elseif $internal_page == 48}
{include file='file:account_edit_payment_method.tpl'}
{/if}
{/if}                
{/if}
{/if}

{if isset($re_accept)}
{include file='file:footer.tpl'}
{else}
{include file='file:footer.tpl'}
{/if}