{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($offline_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$offline_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">
<div class="alert alert-warning">{$offline_paragraph_one} {$offline_paragraph_two}</div>
<table class="table table-bordered">
<tr>
<td width="100%">{$offline_send}<br />{$offline_location}</td>
</tr>
<tr>
<td width="100%"><a href="{$offline_location}" target="_blank" class="btn btn-primary btn-mini">{$offline_page_link}</a></td>
</tr>
</table>

</div>
</div>
</div>
</div>

{/if}