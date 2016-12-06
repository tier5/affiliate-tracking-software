{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="row" style="margin-top:25px;">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_3};">
<div class="portlet-heading" style="background:{$portlet_3};"><div class="portlet-title" style="color:{$portlet_3_text};"><h4>{$tc_reaccept_title}</h4></div></div>

<form method="POST" value="account.php" class="form-horizontal">
<div class="portlet-body">
<div class="alert alert-info">{$tc_reaccept_sub_title}</div>
<div class="form-group">
<div class="col-md-12">
<textarea rows="10" name="terms" class="form-control" readonly>{$terms_t}</textarea>
</div>
</div>
</div>

<div class="portlet-footer">
<div class="pull-left">
<input type="submit" class="btn btn-primary" name="Re-Accept" value="{$tc_reaccept_button}" />
</div>
<div class="clearfix"></div>
</div>

<input type="hidden" name="terms_accepted" value="true">

</form>

</div>
</div>
</div>