{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$traffic_title}</h1>								
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($traffic_logs_exist)}
<table id='dyntable_payment_Traffic' class="table table-bordered">
<thead>
<tr>
<th>{$traffic_date}</th>
<th>{$traffic_time}</th>
<th>{$traffic_ip}</th>
<th>{$traffic_refer}</th>

</tr>
</thead>
<tbody>

</tbody>

{else}
{$traffic_none}
{/if}
</table>
</div>
</div>
</div>
</div>