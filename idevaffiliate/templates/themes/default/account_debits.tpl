{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$debit_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">
<div class="alert alert-info">{$debit_paragraph}</div>
<table id="dyntable_Pending_Debits" class="table table-bordered">
<thead>
<tr>
<th>{$debit_date_label}</th>
<th>{$debit_amount_label}</th>
<th>{$debit_reason_label}</th>
</tr>
</thead>
<tbody>

</tbody>
</table>

</div>
</div>
</div>
</div>