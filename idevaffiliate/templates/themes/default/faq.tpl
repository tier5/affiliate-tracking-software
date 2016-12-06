{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{include file='file:header.tpl'}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$faq_page_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

         {if isset($use_faq) && ($faq_location == 1)}   
               
            {section name=nr loop=$faq_results}               
                    <p class="alert alert-warning"><font size="4"><b>{$faq_results[nr].faq_question}</b></font><BR />{$faq_results[nr].faq_answer}</p>                       
            {/section}

         {else}
<p class="well">Our Frequently Asked Questions Are Not Made Public</p>
         {/if}

		 </div>
		  </div>
		   </div>
		    </div>

{include file='file:footer.tpl'}