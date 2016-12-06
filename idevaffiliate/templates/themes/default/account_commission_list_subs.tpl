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
    {* <table class="table table-bordered tier"> *}
    <table id='dyntable_commission_list_subs' class="table table-striped table-bordered table-highlight-head valign" >
	<thead>
    <tr>
    <th>{$details_date}</th>
    <th>{$details_status}</th>
    <th>{$sub_tracking_id}</th>
    <th>{$details_commission}</th>
    <th>{$details_details}</th>
    </tr>
    </thead>
    <tbody>
        
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