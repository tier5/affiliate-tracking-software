{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($tier_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$tier_stats_title}

<span class="pull-right">
<span class="label label-danger">{$tier_stats_accounts}: {$number_of_tier_accounts}</span>
</span>
</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<p><a href="account.php?page=12" class="btn btn-primary">{$tier_stats_grab_link}</a></p>

{if isset($tier_accounts_exist)}
<table id='dyntable_payment_Tier' class="table table-bordered">
   <thead>
    <tr>
        <th><strong>{$tier_stats_username}</strong></th>
        <th><strong>{$tier_stats_current}</strong></th>
        <th data-hide="phone"><strong>{$tier_stats_previous}</strong></th>
        <th data-hide="phone"><strong>{$tier_stats_totals}</strong></th>
    </tr>
</thead>
<tbody>
{section name=nr loop=$tier_results}
{if isset($display_tier_contact_info)}
    <tr>
        <td><a href="mailto:{$tier_results[nr].tier_email}">{$tier_results[nr].tier_username}</a></td>
        <td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_current_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
        <td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_archived_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
        <td><strong>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_total_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</strong></td>
    </tr>
{else}
    <tr>
        <td>{$tier_results[nr].tier_username}</td>
        <td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_current_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
        <td>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_archived_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
        <td><strong>{if $cur_sym_location == 1}{$cur_sym}{/if}{$tier_results[nr].tier_total_payments}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</strong></td>
    </tr>
{/if}
{/section}
</tbody>
</table>
{/if}

</div>
</div>
</div>
</div>

{/if}