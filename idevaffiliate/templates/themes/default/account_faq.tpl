{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($faq_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$faq_page_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{section name=nr loop=$faq_results}
<div class="alert alert-warning" style="color:{$bg_text_color};">
<strong>{$faq_results[nr].faq_question}</strong><br />
{$faq_results[nr].faq_answer}
</div>
{sectionelse}
<div class="alert alert-warning" style="color:{$bg_text_color};">{$faq_page_none}</div>
{/section}

</div>
</div>
</div>
</div>

{/if}