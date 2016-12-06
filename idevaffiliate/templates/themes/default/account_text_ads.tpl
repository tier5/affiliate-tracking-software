{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_text_ads}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($one_click_delivery)}

{section name=nr loop=$textad_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$textad_link_results[nr].textad_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$textad_link_results[nr].textad_target_url}" target="_blank">{$textad_link_results[nr].textad_target_url}</a></li>
<li class="list-group-item"><script type="text/javascript"><!--
iDevAffiliate_BoxWidth = "{$BoxWidth}";
iDevAffiliate_OutlineColor = "#{$OutlineColor}";
iDevAffiliate_TitleTextColor = "#{$TitleTextColor}";
iDevAffiliate_TitleTextBackgroundColor = "#{$TitleTextBackgroundColor}";
iDevAffiliate_LinkColor = "#{$LinkColor}";
iDevAffiliate_TextColor = "#{$TextColor}";
iDevAffiliate_TextBackgroundColor = "#{$TextBackgroundColor}";
//-->
</script>
<script language="JavaScript" type="text/javascript" src="{$textad_link_results[nr].textad_full_url}"></script></li>
<li class="list-group-item"><label style="width:100%;">{$marketing_source_code}</label><br /><textarea rows="14" class="form-control"><script type="text/javascript"><!--
iDevAffiliate_BoxWidth = "{$BoxWidth}";
iDevAffiliate_OutlineColor = "#{$OutlineColor}";
iDevAffiliate_TitleTextColor = "#{$TitleTextColor}";
iDevAffiliate_TitleTextBackgroundColor = "#{$TitleTextBackgroundColor}";
iDevAffiliate_LinkColor = "#{$LinkColor}";
iDevAffiliate_TextColor = "#{$TextColor}";
iDevAffiliate_TextBackgroundColor = "#{$TextBackgroundColor}";
//-->
</script>
<script language="JavaScript" type="text/javascript" src="{$textad_link_results[nr].textad_full_url}"></script></textarea><br />{$ad_info}</li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_3};">
<div class="portlet-heading" style="background:{$portlet_3};"><div class="portlet-title" style="color:{$portlet_3_text};"><h4>{$choose_marketing_group}</h4></div></div>
<div class="portlet-body">

<form class="form-horizontal" role="form" method="post" action="account.php">
<input type="hidden" name="page" value="8">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="textads_picked" class="form-control">
{section name=nr loop=$textad_results}
<option value="{$textad_results[nr].textad_group_id}">{$textad_results[nr].textad_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_text_ads}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($textad_group_chosen)}




{section name=nr loop=$textad_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$textad_chosen_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$textad_link_results[nr].textad_target_url}" target="_blank">{$textad_link_results[nr].textad_target_url}</a></li>
<li class="list-group-item"><script type="text/javascript"><!--
iDevAffiliate_BoxWidth = "{$BoxWidth}";
iDevAffiliate_OutlineColor = "#{$OutlineColor}";
iDevAffiliate_TitleTextColor = "#{$TitleTextColor}";
iDevAffiliate_TitleTextBackgroundColor = "#{$TitleTextBackgroundColor}";
iDevAffiliate_LinkColor = "#{$LinkColor}";
iDevAffiliate_TextColor = "#{$TextColor}";
iDevAffiliate_TextBackgroundColor = "#{$TextBackgroundColor}";
//-->
</script>
<script language="JavaScript" type="text/javascript" src="{$textad_link_results[nr].textad_full_url}"></script></li>
<li class="list-group-item"><label style="width:100%;">{$marketing_source_code}</label><br /><textarea rows="14" class="form-control"><script type="text/javascript"><!--
iDevAffiliate_BoxWidth = "{$BoxWidth}";
iDevAffiliate_OutlineColor = "#{$OutlineColor}";
iDevAffiliate_TitleTextColor = "#{$TitleTextColor}";
iDevAffiliate_TitleTextBackgroundColor = "#{$TitleTextBackgroundColor}";
iDevAffiliate_LinkColor = "#{$LinkColor}";
iDevAffiliate_TextColor = "#{$TextColor}";
iDevAffiliate_TextBackgroundColor = "#{$TextBackgroundColor}";
//-->
</script>
<script language="JavaScript" type="text/javascript" src="{$textad_link_results[nr].textad_full_url}"></script></textarea><br />{$ad_info}</li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}
{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$marketing_no_group}</legend> *}
{* <p><b>{$marketing_choose}</b><BR /><BR />{$marketing_notice}</p> *}
{/if}
{/if}

</div>
</div>
</div>
</div>