{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($recurring_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$recurring_title}

<span class="pull-right">
<span class="label label-danger">{$recurring_total}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$recurring_total_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</span>
</span>
</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($recurring_commissions_exist)}

<table class="table table-bordered">
<thead>
<tr>
<th>{$recurring_date}</th>
<th>{$recurring_status}</th>
<th>{$recurring_payout}</th>
<th>{$recurring_amount}</th>
</tr>
</thed>
<tbody>
{section name=nr loop=$recurring_list_results}
<tr>
<td>{$recurring_list_results[nr].recurring_results_date}</td>
<td>{$recurring_every} {$recurring_list_results[nr].recurring_results_duration} {$recurring_days}</td>
<td>{$recurring_in} {$recurring_list_results[nr].recurring_results_next} {$recurring_days}</td>
<td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$recurring_list_results[nr].recurring_results_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
</tr>
{/section}
</tbody>
</table>

{else}

{$recurring_none}
{/if}

</div>
</div>
</div>
</div>

{/if}