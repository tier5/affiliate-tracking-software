{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{include file='file:header.tpl'}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$private_heading}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_5};">
<div class="portlet-heading" style="background:{$portlet_5};"><div class="portlet-title" style="color:{$portlet_5_text};"><h4>{$private_required_heading}</h4></div></div>
<div class="portlet-body">

{if isset($display_signup_errors)}
<div class="alert alert-danger">
<h4>{$error_title}</h4>
{$error_list}
</div>                           
{/if}
{if isset($contact_email_received)}
<div class="alert alert-success">
{$contact_received_display}
</div>   
{/if}
						
<p>{$private_info}</p><br />
						
<form class="form-horizontal" role="form" method="post" action="private.php">
<div class="form-group">
<label class="col-sm-3 control-label">{$private_code_title}</label>
<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$private_code_title}" name="signup_code" value="{if isset($signup_code)}{$signup_code}{/if}" />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label"></label>
<div class="col-sm-6">
<input class="btn btn-inverse" type="submit" value="{$private_button}">
</div>
</div>

<input type="hidden" name="email_contact" value="1">
<input name="token_affiliate_private" value="{$private_token}" type="hidden">
</form>

</div>
</div>
</div>
</div>

{include file='file:footer.tpl'}