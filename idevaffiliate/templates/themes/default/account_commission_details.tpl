{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$comdetails_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

<table class="table table-bordered">
<tr>
<td width="25%">{$comdetails_date}</td>
<td width="75%">{$commission_details_date}</td>
</tr>
<tr>
<td width="25%">{$comdetails_time}</td>
<td width="75%">{$commission_details_time}</td>
</tr>
<tr>
<td width="25%">{$comdetails_type}</td>
<td width="75%">{$commission_details_type}</td>
</tr>
<tr>
<td width="25%">{$comdetails_status}</td>
<td width="75%">{$commission_details_status}</td>
</tr>
<tr>
<td width="25%" ><strong>{$comdetails_amount}</strong></td>
<td width="75%" ><strong>{$commission_details_payment}</strong></td>
</tr>
</table>

</div>
</div>
</div>
</div>

{if isset($commission_details_show_extras)}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$comdetails_additional_title}</h4></div></div>
<div class="portlet-body">

<table class="table table-bordered">
<tr>
<td width="25%">{$comdetails_additional_ordnum}</td>
<td width="75%">{$commission_details_extras_ordernum}</td>
</tr>
<tr>
<td width="25%">{$comdetails_additional_saleamt}</td>
<td width="75%">{$commission_details_extras_saleamount}</td>
</tr>
{if isset($commission_details_optional_one)}
<tr>
<td width="25%">{$commission_details_optional_name_one}</td>
<td width="75%">{$commission_details_optional_value_one}</td>
</tr>
{/if}
{if isset($commission_details_optional_two)}
<tr>
<td width="25%">{$commission_details_optional_name_two}</td>
<td width="75%">{$commission_details_optional_value_two}</td>
</tr>
{/if}
{if isset($commission_details_optional_three)}
<tr>
<td width="25%">{$commission_details_optional_name_three}</td>
<td width="75%">{$commission_details_optional_value_three}</td>
</tr>
{/if}

{if isset($sub_affiliates_enabled)}
<tr>
<td width="25%" >{$sub_tracking_id}</td>
<td width="75%" >{$commission_details_subid}</td>
</tr>
{/if}
{if isset($custom_links_enabled)}
<tr>
<td width="25%">TID1</td>
<td width="75%">{$commission_details_tid1}</td>
</tr>
<tr>
<td width="25%">TID2</td>
<td width="75%">{$commission_details_tid2}</td>
</tr>
<tr>
<td width="25%">TID3</td>
<td width="75%">{$commission_details_tid3}</td>
</tr>
<tr>
<td width="25%">TID4</td>
<td width="75%">{$commission_details_tid4}</td>
</tr>
{/if}

</table>

</div>
</div>
</div>
</div>

{/if}