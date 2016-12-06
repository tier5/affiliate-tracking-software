{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

</div>
</div>
</div>
</div>
						
<div class="footer-main {if !isset($cp_page_width) } {else} fullwidth-footer{/if}">


<div class="{if !isset($cp_page_width) } container {else} fullwidth-footer-inner{/if}">

	<div class="footer">

	<div class="footer-inner{if !isset($cp_menu_location) || !isset($inner_page)} collapsed{/if}">

	<div class="footer-content">

	<div class="footer-content-inner">
{if isset($show_footer_logo)}
	<div class="bottom-section-inner-left">
    	<a href="index.php" target="_blank"><img class="img-responsive" alt="{$sitename}" src="{$main_logo}" /></a>
    </div>
{/if}

	<div class="bottom-section-inner-center">
         <!-- <h5>{$footer_site_navigation}</h5> -->
          <ul>
            <li><a href="index.php"><span style="color: {$top_menu_text};">{$header_indexLink}</span></a></li>
			{if !isset($affiliateUsername)}<li><a href="signup.php"><span style="color: {$top_menu_text};">{$header_signupLink}</span></a></li>{/if}
			{if isset($contact_link)}<li><a href="mailto:{$alternate_email_address}"><span style="color: {$top_menu_text};">{$header_emailLink}</span></a></li>{/if}
          </ul>
        </div>
		
	<div class="bottom-section-inner-center">

        <!--  <ul style="margin-top: 36px;"> -->
		<ul>
		  <li><a href="account.php"><span style="color: {$top_menu_text};">{$header_accountLink}</span></a></li>
		    {if isset($use_faq) && ($faq_location == 1)}<li><a href="faq.php"><span style="color: {$top_menu_text};">FAQ</span></a>{/if}
			{if isset($testimonials) && (isset($testimonials_active))}<li><a href="testimonials.php"><span style="color: {$top_menu_text};">{$header_testimonials}</span></a></li>{/if}
          </ul>
        </div>
	
	{if isset($social_enabled)}
        <div class="bottom-section-inner-right">
          <h5>{$footer_connect}</h5>
		{section name=nr loop=$social_icons}<a href="{$social_icons[nr].link}" target="_blank" style="padding-right:5px;"><img src="{$social_icons[nr].image}" width="32" height="32" style="border:none;"></a>{/section}
        </div>
	{/if}

	
</div>
	
</div>
</div>
</div>
</div>


<div class="footer-content-end">

	<div class="{if !isset($cp_page_width) } container {else} fullwidth-footer-bottom{/if} ">	
    
    <div class="row">
    
    <div class="col-md-12 ">




	<div class="col-md-8">

	<span class="pull-right">

	{$footer_copyright} {php} echo date("Y"); {/php} <a href="{$siteurl}" target=_blank>{$sitename}</a> - {$footer_rights}

	
	</span>

	</div>

	

	</div>



	</div>

	</div></div>



</div>	  

</div>  



	

	{literal}

    <script src="templates/source/common/bootstrap/js/bootstrap.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.ui.touch-punch.min.js"></script>	
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.sparkline.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.easypiechart.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/excanvas.compiled.js"></script>	
	<script src="templates/source/common/pace/js/pace.min.js"></script>

    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
	
	<script type="text/javascript" src="admin/templates/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script> 
	
		if (window.location.href.match('/contact.php')) 
		
		{ $("#main-container").addClass("contact-page");  } 
		
		else if (window.location.href.match('/account.php')) { $("body").addClass("account-page");  }
		
		var winh = window.innerHeight;
		var overallh = $("body").height();
		var fooh = $(".footer-main").height();
		var overallhfoo = overallh + fooh;
		var navtoph = $(".navbar-top").height();
		
		var sideh = winh - navtoph;
		sideh = sideh - 100;
		
		
		if (overallh+50 < winh){ 
		//$(".footer-main").addClass("footer-pushed-bottom"); 
		//$( "#page-wrapper" ).css( { 'height':(winh-fooh-220) }, { queue: false, duration: 800 });  
		}
		
		$(window).resize(function() {
		  
		var winh = window.innerHeight;
		var overallh = $("body").height();
		var fooh = $(".footer-main").height();
		var overallhfoo = overallh + fooh;
		
		
		if (overallh+50 < winh) 
		  { $(".footer-main").addClass("footer-pushed-bottom"); $( "#page-wrapper" ).css( { 'height':(winh-fooh-220) }, { queue: false, duration: 800 });  }
		  
		});
			
		
		var wrh = $(".wrapper-outer").height();
		var nahh = $(".navbar-side").height();
		
		if (wrh > nahh)
		{  $(".navbar-side").addClass("navbar-side-bottom"); }
		
	   $('.navbar-outer').slimScroll({
			position: 'right',
			height: '100%',
			railVisible: false,
			alwaysVisible: false
		});
		
		$( ".fixed .slimScrollDiv" ).css( { 'height':sideh }, { queue: false, duration: 800 });
		
		
		
		$(".menu-button").click(function () {
		$(".b-right ul.prime").toggle(500);
		});
		
		$(".menu-button-top").click(function () {
			
		$(this).toggleClass("open");	
		$(".more-option").toggle(500);
		});
		
		if( window.innerWidth>768)
		
		{
			$(".more-option").remove();
		}
		
		if( window.innerWidth>768)
		
		{
		
		$(".navbar-side li a").click(function() {

		  // place this within dom ready function
		  function showpanel() {     
			var navheight = $(".navbar-side").height();
			var navnewheight = $(".navbar-side .navbar-collapse").height();
			$( "#page-wrapper" ).css( { 'min-height':(navheight+20) }, { queue: false, duration: 800 });  
			$( ".simple-wrapper #page-wrapper" ).css( { 'min-height':(navnewheight+100) }, { queue: false, duration: 800 });
			$( ".fixed.navbar-side" ).css( { 'max-height':$("#page-wrapper").height() }, { queue: false, duration: 800 });
			
			//var pagwarh = $("#page-wrapper").height();
			
			//if ( pagwarh > navheight) { }
			
		 }
		
		 // use setTimeout() to execute
		 //setTimeout(showpanel, 500)
		
		});
		
		$(document).ready(function(){

			  // place this within dom ready function
			 function showpanel() {     
				var navheight = $(".navbar-side").height();
				var navnewheight = $(".navbar-side .navbar-collapse").height();
				$( "#page-wrapper" ).css( { 'min-height':(navheight+20) }, { queue: false, duration: 800 });  
				$( ".simple-wrapper #page-wrapper" ).css( { 'min-height':(navnewheight+100) }, { queue: false, duration: 800 });
				$( ".fixed.navbar-side" ).css( { 'max-height':$("#page-wrapper").height() }, { queue: false, duration: 800 });
			 }
		
		 		// use setTimeout() to execute
				//setTimeout(showpanel, 500)
				
				
				//if( $('.navbar-side').length > 0 ){
					//$('.sideBar').height( $('.wrapper-outer').height());
					if($('.sideBar.fixed').length > 0){
						$('.sideBar.fixed').hcSticky({
							top:200,
							bottomEnd: 13,
							innerTop: 50
						});
					}
					
				//}
				
			});
		
		}

    </script>

	{/literal}
    
</body>
</html>