{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_lightboxes}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($one_click_delivery)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_6};">
<div class="portlet-heading" style="background:{$portlet_6};"><div class="portlet-title" style="color:{$portlet_6_text};"><h4>{$lb_head_title}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label style="width:100%;">{$lb_head_description}</label><br /><br />{$lb_head_source_code}<br /><br /><textarea rows="6" class="form-control"><link rel="stylesheet" href="{$install_url}/templates/source/lightbox/css/jquery.fancybox.css" type="text/css" />
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/jquery.fancybox.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/video.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/fancy-custom.js"></script></textarea><br />{$lb_head_code_notes}</li>
<li class="list-group-item"><label><a href="http://www.idevlibrary.com/docs/Lightboxes.pdf" target="_blank" class="btn btn-mini btn-success">{$lb_head_tutorial}</a></label></li>
</ul>

</div>
</div>
</div>
</div>

{section name=nr loop=$lightbox_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$lightbox_link_results[nr].lightbox_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$lb_body_title}:</label> {$lightbox_link_results[nr].lightbox_link_name}</li>
<li class="list-group-item"><label>{$lb_body_description}:</label> {$lightbox_link_results[nr].lightbox_description}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$lightbox_link_results[nr].lightbox_target_url}" target="_blank">{$lightbox_link_results[nr].lightbox_target_url}</a></li>
<li class="list-group-item"><a  href="media/lightboxes/{$lightbox_link_results[nr].lightbox_image}" title="<a href='{$lightbox_link_results[nr].lightbox_link}' target='_blank'>{$lightbox_link_text}</a>" class="fancy-image">
<img src="media/lightboxes/{$lightbox_link_results[nr].lightbox_thumbnail}" width="{$lightbox_link_results[nr].lightbox_thumb_width}" height="{$lightbox_link_results[nr].lightbox_thumb_height}" style="border:none;" /></a>
<br /><BR />{$lb_body_click}</li>
<li class="list-group-item"><label style="width:100%;">{$lb_body_source_code}</label><BR />
<textarea rows="4" class="form-control">{$lightbox_link_results[nr].lightbox_code}</textarea></li>
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
<input type="hidden" name="page" value="38">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="lb_picked" class="form-control">
{section name=nr loop=$lb_results}
<option value="{$lb_results[nr].lb_group_id}">{$lb_results[nr].lb_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_lightboxes}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($lb_group_chosen)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_6};">
<div class="portlet-heading" style="background:{$portlet_6};"><div class="portlet-title" style="color:{$portlet_6_text};"><h4>{$lb_head_title}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label style="width:100%;">{$lb_head_description}</label><br /><br />{$lb_head_source_code}<br /><br /><textarea rows="6" class="form-control"><link rel="stylesheet" href="{$install_url}/templates/source/lightbox/css/jquery.fancybox.css" type="text/css" />
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/jquery.fancybox.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/video.js"></script>
<script type="text/javascript" src="{$install_url}/templates/source/lightbox/js/fancy-custom.js"></script></textarea><br />{$lb_head_code_notes}</li>
<li class="list-group-item"><label><a href="http://www.idevlibrary.com/docs/Lightboxes.pdf" target="_blank" class="btn btn-mini btn-success">{$lb_head_tutorial}</a></label></li>
</ul>

</div>
</div>
</div>
</div>

{section name=nr loop=$lightbox_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$lightbox_link_results[nr].lightbox_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$lb_body_title}:</label> {$lightbox_link_results[nr].lightbox_link_name}</li>
<li class="list-group-item"><label>{$lb_body_description}:</label> {$lightbox_link_results[nr].lightbox_description}</li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$lightbox_link_results[nr].lightbox_target_url}" target="_blank">{$lightbox_link_results[nr].lightbox_target_url}</a></li>
<li class="list-group-item"><a  href="media/lightboxes/{$lightbox_link_results[nr].lightbox_image}" title="<a href='{$lightbox_link_results[nr].lightbox_link}' target='_blank'>{$lightbox_link_text}</a>" class="fancy-image">
<img src="media/lightboxes/{$lightbox_link_results[nr].lightbox_thumbnail}" width="{$lightbox_link_results[nr].lightbox_thumb_width}" height="{$lightbox_link_results[nr].lightbox_thumb_height}" style="border:none;" /></a>
<br /><BR />{$lb_body_click}</li>
<li class="list-group-item"><label style="width:100%;">{$lb_body_source_code}</label><BR />
<textarea rows="4" class="form-control">{$lightbox_link_results[nr].lightbox_code}</textarea></li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}
{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$marketing_no_group}</legend> *}
{* <p>{$marketing_choose}</b><BR /><BR /><font color="#CC0000">{$marketing_notice}</font></p> *}
{/if}
{/if}

</div>
</div>
</div>
</div>