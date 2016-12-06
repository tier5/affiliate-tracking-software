{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{include file='file:header.tpl'}

{if isset($testimonials) && (isset($testimonials_active))}
		
<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$header_testimonials}</h1>
</div>
		
<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">
 
{section name=nr loop=$testi_results}

<div class="widget">
<div class="body">
<div class="background-icon-right"><span class="fa fa-quote-right"></span></div>
<p style="font-style:italic;">"{$testi_results[nr].testimonial}"</p>
<p class="pull-right">{$testi_results[nr].affiliate_name}{if isset($show_testimonials_link)} - <a href="{$testi_results[nr].website_url}" target="_blank">{$testi_visit}</a>{/if}</p>
</div>
</div>

{/section}

</div>    
</div>
</div>
</div>

{/if}

{include file='file:footer.tpl'}