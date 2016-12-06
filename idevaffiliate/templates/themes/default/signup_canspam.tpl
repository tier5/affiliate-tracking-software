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
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$signup_canspam_title}</h4></div></div>
<div class="portlet-body">

<div class="form-group">
<div class="col-md-12">
<textarea class="form-control" name="canspam" rows="10" readonly>{$canspam_t}</textarea>
</div>
</div>
 
{if isset($canspam_required)}
<div class="form-group">
<div class="col-md-12">
<input type="checkbox" name="canspam_accepted" value="1"{$canspam_checked}> <span style="color:#CC0000;">*</span> {$signup_canspam_agree}
</div>
</div>
{/if}

</div>
</div>
</div>
</div>