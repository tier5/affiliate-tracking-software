{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$signup_terms_title}</h4></div></div>
<div class="portlet-body">

<div class="form-group">
<div class="col-md-12">
<textarea class="form-control" name="terms" rows="10" readonly>{$terms_t}</textarea>
</div>
</div>
 
{if isset($terms_required)}
<div class="form-group">
<div class="col-md-12">
<input type="checkbox" name="accepted" value="1"{$terms_checked}> <span style="color:#CC0000;">*</span> {$signup_terms_agree}
</div>
</div>
{/if}

</div>
</div>
</div>
</div>