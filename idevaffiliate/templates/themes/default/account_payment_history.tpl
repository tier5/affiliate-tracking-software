{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$payment_title}

{if isset($payment_history_exists)}
<span class="pull-right">
<span class="label label-primary">{$payments_total} {$payment_commissions}</span>
<span class="label label-danger">{if $cur_sym_location == 1}{$cur_sym}{/if}{$payments_archived}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</span>
</span>
{/if}

</h1>
						
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($payment_history_exists)}

<table id='dyntable_payment_history'  class="table table-bordered table-hover tc-table">
<thead>
<tr>
<th>{$payment_date}</th>
<th>{$payment_commissions}</th>
<th>{$payment_amount}</th>
{if $invoice_enabled}<th></th>{/if}
</tr>
</thead>
<tbody>

</tbody>
</table>

{else}
{$payment_none}
{/if}

</div>
</div>
</div>
</div>