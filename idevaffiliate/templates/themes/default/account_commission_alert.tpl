{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$commissionalert_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<p>{$commissionalert_info}</p>

<br />

<p>{$commissionalert_hint}</p>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<table class="table table-bordered">
<tbody>
    <tr>
      <td width="25%">{$commissionalert_profile}</td>
      <td width="75%"><input type="text" value="{$sitename}" id="form-control" name="banner_picked" class="form-control width300"></td>
    </tr>
    <tr>
      <td width="25%">{$commissionalert_username}</td>
      <td width="75%"><input type="text" value="{$username}" id="form-control" name="banner_picked" class="form-control width300"></td>
    </tr>
    <tr>
      <td width="25%">{$commissionalert_id}</td>
      <td width="75%"><input type="text" value="{$link_id}" id="form-control" name="banner_picked" class="form-control width300"></td>
    </tr>
    <tr>
      <td width="25%">{$commissionalert_source}</td>
      <td width="75%"><input type="text" value="{$base_url}" id="form-control" name="banner_picked" class="form-control width300"></td>
    </tr>
</tbody>
</table>

</div>

<div class="portlet-footer">
<div class="pull-left"><form method="POST" action="commissionalert/download.php"><input class="btn btn-primary" type="submit" value="{$commissionalert_download}"></form></div>
<div class="clearfix"></div>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</div>