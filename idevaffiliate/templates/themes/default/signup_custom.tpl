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
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$custom_fields_title}</h4></div></div>
<div class="portlet-body">

{section name=nr loop=$custom_input_results}
<div class="form-group">
<label class="col-md-3 control-label">{$custom_input_results[nr].custom_title}{if ($custom_input_results[nr].custom_required == 1)} <span style="color:#CC0000;">*</span>{/if}</label>
<div class="col-md-6"> <input type="text" name="{$custom_input_results[nr].custom_name}" class="form-control" value="{$custom_input_results[nr].custom_value}" /></div>
</div>
{/section}

</div>
</div>
</div>
</div>