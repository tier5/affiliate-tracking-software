{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($email_links_available)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$email_title}</h1>
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
<input type="hidden" name="page" value="10">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="email_picked" class="form-control">
{section name=nr loop=$email_results}
<option value="{$email_results[nr].email_group_id}">{$email_results[nr].email_group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$email_button}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($email_group_chosen)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$email_chosen_group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><div class="alert alert-info"><label style="width:100%;">{$email_ascii}</label><br/><textarea rows="2" class="form-control">{$email_chosen_url}</textarea><br />{$email_source}<br /></div></li>
<li class="list-group-item"><div class="alert alert-warning"><label style="width:100%;">{$email_html}</label><br/><textarea rows="2" class="form-control"><a href="{$email_chosen_url}{$rel_values}">{$email_chosen_group_name}</a></textarea><br />{$email_source}</div></li>
</ul>

</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_5};">
<div class="portlet-heading" style="background:{$portlet_5};"><div class="portlet-title" style="color:{$portlet_5_text};"><h4>{$email_notice}</h4></div></div>
<div class="portlet-body">

{$email_test}: <a href="{$email_chosen_url}{$rel_values}" target="_blank">{$email_chosen_display_tag}</a><br /><br />{$email_test_info}

</div>
</div>
</div>
</div>



{else}

{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$email_no_group}</legend> *}
{* <p><b>{$email_choose}</b><BR /><BR />{$email_notice}</p> *}
{/if}

</div>
</div>
</div>
</div>

{/if}