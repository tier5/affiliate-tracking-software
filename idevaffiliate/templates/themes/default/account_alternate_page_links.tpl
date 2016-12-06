{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($alternate_keywords_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$alternate_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($display_custom_success)}
<div class="alert alert-success"><h4>{$custom_success_title}</h4>{$custom_success_message}</div> 
{elseif isset($display_custom_errors)}
<div class="alert alert-danger"><h4>{$custom_error_title}</h4>{$custom_error_list}</div> 
{/if}

<form action="account.php" method="post">
<input type="hidden" name="create_alternate" value="1">
<input type="hidden" name="page" value="35">
<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$alternate_option_1}</h4></div></div>
<div class="portlet-body">
{$alternate_info_1}<br /><br />
<input class="form-control" type="text" name="custom_link" value="http://" />
</div>
<div class="portlet-footer">
<div class="pull-left">
<a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank" class="btn btn-small btn-success">{$alternate_tutorial}</a> 
<input class="btn btn-primary" type="submit" value="{$alternate_button}" name="{$alternate_button}">
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
</div>
</form>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-primary">
<div class="portlet-body">			

{section name=nr loop=$clinks_results}
<div class="well">
<p><a href="{$clinks_results[nr].clink_url}" target="_blank">{$clinks_results[nr].clink_url}</a><span class="pull-right"><a href="account.php?page=35&custom_remove={$clinks_results[nr].clink_id}" class="btn btn-xs btn-danger">{$alternate_links_remove}</a></span></p>
<p><input class="form-control" type="text" name="sub_link" value="{$clinks_results[nr].clink_linkurl}" /></p>
</div>
{sectionelse}
{$alternate_none}<br /><br />
{/section}
<div class="alert alert-warning">{$alternate_links_note}</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">	

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$alternate_option_2}</h4></div></div>
<div class="portlet-body">
{$alternate_info_2} {$alternate_build}<br /><br />
{$alternate_variable}: url<br /><br />
<input class="form-control" type="text" name="sub_link" value="{$alternate_keyword_linkurl}" /><br />
{$alternate_example}: {$alternate_keyword_linkurl}&url=<b>http://www.yahoo.com</b>
</div>

<div class="portlet-footer">
<div class="pull-left">
<a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank" class="btn btn-small btn-success">{$alternate_tutorial}</a>
</div>
<div class="clearfix"></div>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</div>

{/if}