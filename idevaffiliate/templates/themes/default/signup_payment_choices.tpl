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
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$commission_method}</h4></div></div>
<div class="portlet-body">
    
<div class="form-group">
<label class="col-md-3 control-label">{$how_will_you_earn} <span style="color:#CC0000;">*</span></label>
<div class="col-md-6">              
 <select name="payme" class="form-control">
{if isset($commission_option_percentage)}
<option value="1"{$payme_selected_1}>{$signup_commission_style_PPS}: {$bot1}%</option>
{/if}
{if isset($commission_option_flatrate)}
<option value="2"{$payme_selected_2}>{$signup_commission_style_PPS}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$bot2}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</option>
{/if}
{if isset($commission_option_perclick)}
<option value="3"{$payme_selected_3}>{$signup_commission_style_PPC}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$bot3}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</option>
{/if}
</select>
</div>
</div>

</div>
</div>
</div>
</div>