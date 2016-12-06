{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$commission_group_name}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($commission_group_chosen)}
{if isset($commission_results_exist)}
<table id='dyntable_commission_list' class="table table-bordered">
<thead>
<tr>
<th>{$details_date}</th>
<th>{$details_status}</th>
<th>{$details_commission}</th>
<th>{$details_details}</th>
</tr>
</thead>
<tbody>

{section name=nr loop=$commission_group_results}
<tr>
<td>{$commission_group_results[nr].commission_results_date}</td>
<td>{$commission_group_results[nr].commission_results_type}</td>
<td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$commission_group_results[nr].commission_results_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
<td><a href="account.php?page=22&type={$commission_group_results[nr].commission_results_record_type}&id={$commission_group_results[nr].commission_results_record_id}" class="btn btn-xs btn-primary">{$details_details}</a></td>
</tr>
{/section}

</tbody>
</table>
    {else}    
    <p>{$details_none}</p>
    {/if}
	{else}
	<p>{$details_choose}</p>
{/if}

</div>
</div>
</div>
</div>