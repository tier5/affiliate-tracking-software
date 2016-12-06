{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_marketing_videos}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_3};">
<div class="portlet-heading" style="background:{$portlet_3};"><div class="portlet-title" style="color:{$portlet_3_text};"><h4>{$choose_marketing_group}</h4></div></div>
<div class="portlet-body">

<form class="form-horizontal" role="form" method="post" action="account.php">
<input type="hidden" name="page" value="47">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="mv_picked" class="form-control">
{section name=nr loop=$mv_results}
<option value="{$mv_results[nr].mv_group_id}">{$mv_results[nr].mv_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_marketing_videos}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($mv_group_chosen)}

{section name=nr loop=$mvideos_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$mvideos_link_results[nr].mvideos_group_name}</h4></div>
<div class="pull-right" style="color:{$portlet_4_text};">{$mv_head_description}</div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$mv_body_title}:</label> {$mvideos_link_results[nr].mvideos_link_name}<span class="pull-right"><a href="video_preview.php?affiliate_id={$link_id}&video_id={$mvideos_link_results[nr].mvideos_link_id}" class="btn btn-success btn-sm" target="_blank">{$mv_preview_button}</a></span></li>
<li class="list-group-item"><label>{$marketing_target_url}:</label> <a href="{$mvideos_link_results[nr].mvideos_target_url}" target="_blank">{$mvideos_link_results[nr].mvideos_target_url}</a></li>

<li class="list-group-item"><label style="width:100%;">{$mv_head_source_code}</label><BR />
<textarea rows="8" class="form-control"><link rel="stylesheet" href="{$install_url}/templates/source/videos/css/elite.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="{$install_url}/templates/source/videos/css/font-awesome.css" type="text/css">
<link rel="stylesheet" href="{$install_url}/templates/source/videos/css/jquery.mCustomScrollbar.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="{$install_url}/templates/source/videos/js/froogaloop.js" type="text/javascript"></script>
<script src="{$install_url}/templates/source/videos/js/jquery.mCustomScrollbar.js" type="text/javascript"></script> 
<script src="{$install_url}/templates/source/videos/js/THREEx.FullScreen.js"></script>
<script src="{$install_url}/templates/source/videos/js/videoPlayer.js" type="text/javascript"></script>
<script src="{$install_url}/templates/source/videos/js/Playlist.js" type="text/javascript"></script>
<script type="text/javascript" src="{$install_url}/templates/source/videos/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="{$install_url}/video.php?id={$link_id}&video_id={$mvideos_link_results[nr].mvideos_link_id}"></script></textarea></li>

<li class="list-group-item"><label style="width:100%;">{$mv_body_source_code}</label><BR />
<textarea rows="2" class="form-control"><div id="idev_video_player"></div></textarea></li>
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


</div>
</div>
</div>
</div>