{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_html_links}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($one_click_delivery)}

{section name=nr loop=$html_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$html_link_results[nr].html_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$html_name}:</label> {$html_link_results[nr].html_link_name}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$html_link_results[nr].html_target_url}" target="_blank">{$html_link_results[nr].html_target_url}</a></li>
<li class="list-group-item"><label style="width:100%;"><a href="{$base_url}/adview.php?id={$html_link_results[nr].html_link_id}" class="btn btn-mini btn-primary fancy-page">{$html_view_link}</a><br/><br/>{$marketing_source_code}</label><br/><textarea rows="8" class="form-control">{$html_link_results[nr].html_link_url}</textarea></li>
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
<input type="hidden" name="page" value="23">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="html_picked" class="form-control">
{section name=nr loop=$htmlad_results}
<option value="{$htmlad_results[nr].htmlad_group_id}">{$htmlad_results[nr].htmlad_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_html_links}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($html_group_chosen)}

{section name=nr loop=$html_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$html_chosen_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$html_name}:</label> {$html_link_results[nr].html_link_name}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$html_link_results[nr].html_target_url}" target="_blank">{$html_link_results[nr].html_target_url}</a></li>
<li class="list-group-item"><label style="width:100%;"><a href="{$base_url}/adview.php?id={$html_link_results[nr].html_link_id}" class="btn btn-mini btn-primary fancy-page">{$html_view_link}</a><br/><br/>{$marketing_source_code}</label><br/><textarea rows="8" class="form-control">{$html_link_results[nr].html_link_url}</textarea></li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}

{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$marketing_no_group}</legend> *}
{* <p><b>{$marketing_choose}</b><BR /><BR />{$marketing_notice}<BR /><BR /><BR /></p> *}
{/if}
{/if}

</div>
</div>
</div>
</div>