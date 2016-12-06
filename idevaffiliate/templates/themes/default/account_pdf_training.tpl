{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$pdf_title} {$pdf_training}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{$pdf_description_1} {$pdf_description_2}<br /><br />
<a target="_blank" href="http://www.adobe.com/products/acrobat/readstep2.html"><img border="0" src="images/get_adobe_reader.gif" width="112" height="33"></a>
<br /><br />

<table class="table table-bordered">
<thead>
<tr>
<th><b>{$pdf_file_name}</b></th>
<th><b>{$pdf_file_size}</b></th>
<th><b>{$pdf_file_description}</b></th>
</tr>
</thead>
<tbody>
{section name=nr loop=$pdf_results}
    <tr>
      <td><a href="media/pdf/{$pdf_results[nr].pdf_filename}" target="_blank">{$pdf_results[nr].pdf_filename}</a></td>
      <td>{$pdf_results[nr].pdf_size} {$pdf_bytes}</td>
      <td>{$pdf_results[nr].pdf_desc}&nbsp;&nbsp;</td>
    </tr>
{/section}
</tbody>
</table>

</div>
</div>
</div>
</div>