{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$training_videos_title}</h1>
</div>

{if isset($uploaded_training_videos)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$training_videos_title}</h4></div></div>
<div class="portlet-body">

<table class="table table-primary table-bordered">
<tbody>
{$Uploaded_Video_Tutorials}
</tbody>
</table>

</div>
</div>
</div>
</div>
{/if}


{if isset($active_subscription)}

{foreach from=$video_results key=header item=table}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$header}</h4></div></div>
<div class="portlet-body">

<table class="table table-primary table-bordered">
<tbody>
{$table}
</tbody>
</table>

</div>
</div>
</div>
</div>
{/foreach}

{else}

{if isset($videos_enabled)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$training_videos_general}</h4></div></div>
<div class="portlet-body">

<table class="table table-primary table-bordered">
<tbody>
{$Table_Rows_General_Affiliate_Marketing}
</tbody>
</table>

</div>
</div>
</div>
</div>

{/if}
{/if}