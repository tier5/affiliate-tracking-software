{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($custom_links_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$keyword_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<div class="alert alert-warning">{$keyword_info}</div>

<table class="table table-bordered">
    <thead>
    <tr>
    <th width="100%" colspan="2">{$keyword_heading}</th>
    </tr>
    </thead>
	<tbody>
    <tr>
      <td width="25%"><strong>{$keyword_tracking} 1</strong></td>
      <td width="75%">tid1</td>
    </tr>
    <tr>
      <td width="25%"><strong>{$keyword_tracking} 2</strong></td>
      <td width="75%">tid2</td>
    </tr>
    <tr>
      <td width="25%"><strong>{$keyword_tracking} 3</strong></td>
      <td width="75%">tid3</td>
    </tr>
    <tr>
      <td width="25%"><strong>{$keyword_tracking} 4</strong></td>
      <td width="75%">tid4</td>
    </tr>
	</tbody>
</table>

<div class="space-12"></div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">
{$keyword_build}<br />
<input class="form-control" type="text" name="sub_link" value="{$custom_keyword_linkurl}"/><br />
{$keyword_example}: {$custom_keyword_linkurl}&tid1=<b>google</b>
</div>

<div class="portlet-footer">
<div class="pull-left"><a href="http://www.idevlibrary.com/docs/Custom_Links.pdf" target="_blank" class="btn btn-primary">{$keyword_tutorial}</a></div>
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