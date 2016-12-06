{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_page_peels}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($one_click_delivery)}

{section name=nr loop=$peel_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$peel_link_results[nr].peel_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$peels_title}:</label> {$peel_link_results[nr].peel_link_name}</li>
<li class="list-group-item"><label>{$peels_description}:</label> {$peel_link_results[nr].peel_description}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$peel_link_results[nr].peel_target_url}" target="_blank">{$peel_link_results[nr].peel_target_url}</a></li>
<li class="list-group-item"><label style="width:100%;"><a href="{$peel_link_results[nr].peel_sample_url}" title="{$peels_title}: {$peel_link_results[nr].peel_link_name}" class="btn btn-mini btn-primary fancy-page">{$peels_view}</a><br/><br/>{$marketing_source_code}</label><br/>
<textarea rows="4" class="form-control"><script src="{$peel_link_results[nr].peel_source_location}/jquery-2.0.3.min.js"></script>
<script src="{$peel_link_results[nr].peel_source_location}/jquery.peelback.js"></script>
<script src="{$peel_link_results[nr].peel_link_url}"></script></textarea></li>
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
<input type="hidden" name="page" value="37">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="peels_picked" class="form-control">
{section name=nr loop=$peels_results}
<option value="{$peels_results[nr].peels_group_id}">{$peels_results[nr].peels_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_page_peels}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($peels_group_chosen)}

{section name=nr loop=$peels_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$peels_link_results[nr].peels_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$peels_title}:</label> {$peels_link_results[nr].peels_link_name}</li>
<li class="list-group-item"><label>{$peels_description}:</label> {$peels_link_results[nr].peels_description}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$peels_link_results[nr].peels_target_url}" target="_blank">{$peels_link_results[nr].peels_target_url}</a></li>
<li class="list-group-item"><label style="width:100%;"><a href="{$peels_link_results[nr].peels_sample_url}" title="{$peels_title}: {$peels_link_results[nr].peels_link_name}" class="btn btn-mini btn-primary fancy-page">{$peels_view}</a><br/><br/>{$marketing_source_code}</label><br/>
<textarea rows="4" class="form-control"><script src="{$peels_link_results[nr].peels_source_location}/jquery-2.0.3.min.js"></script>
<script src="{$peels_link_results[nr].peels_source_location}/jquery.peelback.js"></script>
<script src="{$peels_link_results[nr].peels_link_url}"></script></textarea></li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}

{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$marketing_no_group}</legend> *}
{* <p><b>{$marketing_choose}</b><BR /><BR /><font color="#CC0000">{$marketing_notice}</font></p> *}
{/if}
{/if}

</div>
</div>
</div>
</div>