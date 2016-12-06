<?php /* Smarty version 2.6.28, created on 2016-12-05 19:23:29
         compiled from file:footer.tpl */ ?>

</div>
</div>
</div>
</div>
						
<div class="footer-main <?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?> <?php else: ?> fullwidth-footer<?php endif; ?>">


<div class="<?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?> container <?php else: ?> fullwidth-footer-inner<?php endif; ?>">

	<div class="footer">

	<div class="footer-inner<?php if (! isset ( $this->_tpl_vars['cp_menu_location'] ) || ! isset ( $this->_tpl_vars['inner_page'] )): ?> collapsed<?php endif; ?>">

	<div class="footer-content">

	<div class="footer-content-inner">
<?php if (isset ( $this->_tpl_vars['show_footer_logo'] )): ?>
	<div class="bottom-section-inner-left">
    	<a href="index.php" target="_blank"><img class="img-responsive" alt="<?php echo $this->_tpl_vars['sitename']; ?>
" src="<?php echo $this->_tpl_vars['main_logo']; ?>
" /></a>
    </div>
<?php endif; ?>

	<div class="bottom-section-inner-center">
         <!-- <h5><?php echo $this->_tpl_vars['footer_site_navigation']; ?>
</h5> -->
          <ul>
            <li><a href="index.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_indexLink']; ?>
</span></a></li>
			<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?><li><a href="signup.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_signupLink']; ?>
</span></a></li><?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['contact_link'] )): ?><li><a href="mailto:<?php echo $this->_tpl_vars['alternate_email_address']; ?>
"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_emailLink']; ?>
</span></a></li><?php endif; ?>
          </ul>
        </div>
		
	<div class="bottom-section-inner-center">

        <!--  <ul style="margin-top: 36px;"> -->
		<ul>
		  <li><a href="account.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_accountLink']; ?>
</span></a></li>
		    <?php if (isset ( $this->_tpl_vars['use_faq'] ) && ( $this->_tpl_vars['faq_location'] == 1 )): ?><li><a href="faq.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;">FAQ</span></a><?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['testimonials'] ) && ( isset ( $this->_tpl_vars['testimonials_active'] ) )): ?><li><a href="testimonials.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_testimonials']; ?>
</span></a></li><?php endif; ?>
          </ul>
        </div>
	
	<?php if (isset ( $this->_tpl_vars['social_enabled'] )): ?>
        <div class="bottom-section-inner-right">
          <h5><?php echo $this->_tpl_vars['footer_connect']; ?>
</h5>
		<?php unset($this->_sections['nr']);
$this->_sections['nr']['name'] = 'nr';
$this->_sections['nr']['loop'] = is_array($_loop=$this->_tpl_vars['social_icons']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nr']['show'] = true;
$this->_sections['nr']['max'] = $this->_sections['nr']['loop'];
$this->_sections['nr']['step'] = 1;
$this->_sections['nr']['start'] = $this->_sections['nr']['step'] > 0 ? 0 : $this->_sections['nr']['loop']-1;
if ($this->_sections['nr']['show']) {
    $this->_sections['nr']['total'] = $this->_sections['nr']['loop'];
    if ($this->_sections['nr']['total'] == 0)
        $this->_sections['nr']['show'] = false;
} else
    $this->_sections['nr']['total'] = 0;
if ($this->_sections['nr']['show']):

            for ($this->_sections['nr']['index'] = $this->_sections['nr']['start'], $this->_sections['nr']['iteration'] = 1;
                 $this->_sections['nr']['iteration'] <= $this->_sections['nr']['total'];
                 $this->_sections['nr']['index'] += $this->_sections['nr']['step'], $this->_sections['nr']['iteration']++):
$this->_sections['nr']['rownum'] = $this->_sections['nr']['iteration'];
$this->_sections['nr']['index_prev'] = $this->_sections['nr']['index'] - $this->_sections['nr']['step'];
$this->_sections['nr']['index_next'] = $this->_sections['nr']['index'] + $this->_sections['nr']['step'];
$this->_sections['nr']['first']      = ($this->_sections['nr']['iteration'] == 1);
$this->_sections['nr']['last']       = ($this->_sections['nr']['iteration'] == $this->_sections['nr']['total']);
?><a href="<?php echo $this->_tpl_vars['social_icons'][$this->_sections['nr']['index']]['link']; ?>
" target="_blank" style="padding-right:5px;"><img src="<?php echo $this->_tpl_vars['social_icons'][$this->_sections['nr']['index']]['image']; ?>
" width="32" height="32" style="border:none;"></a><?php endfor; endif; ?>
        </div>
	<?php endif; ?>

	
</div>
	
</div>
</div>
</div>
</div>


<div class="footer-content-end">

	<div class="<?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?> container <?php else: ?> fullwidth-footer-bottom<?php endif; ?> ">	
    
    <div class="row">
    
    <div class="col-md-12 ">




	<div class="col-md-8">

	<span class="pull-right">

	<?php echo $this->_tpl_vars['footer_copyright']; ?>
 <?php  echo date("Y");  ?> <a href="<?php echo $this->_tpl_vars['siteurl']; ?>
" target=_blank><?php echo $this->_tpl_vars['sitename']; ?>
</a> - <?php echo $this->_tpl_vars['footer_rights']; ?>


	
	</span>

	</div>

	

	</div>



	</div>

	</div></div>



</div>	  

</div>  



	

	<?php echo '

    <script src="templates/source/common/bootstrap/js/bootstrap.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/jquery.ui.touch-punch.min.js"></script>	
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/jquery.sparkline.min.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/jquery.easypiechart.min.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/excanvas.compiled.js"></script>	
	<script src="templates/source/common/pace/js/pace.min.js"></script>

    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
	
	<script type="text/javascript" src="admin/templates/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script> 
	
		if (window.location.href.match(\'/contact.php\')) 
		
		{ $("#main-container").addClass("contact-page");  } 
		
		else if (window.location.href.match(\'/account.php\')) { $("body").addClass("account-page");  }
		
		var winh = window.innerHeight;
		var overallh = $("body").height();
		var fooh = $(".footer-main").height();
		var overallhfoo = overallh + fooh;
		var navtoph = $(".navbar-top").height();
		
		var sideh = winh - navtoph;
		sideh = sideh - 100;
		
		
		if (overallh+50 < winh){ 
		//$(".footer-main").addClass("footer-pushed-bottom"); 
		//$( "#page-wrapper" ).css( { \'height\':(winh-fooh-220) }, { queue: false, duration: 800 });  
		}
		
		$(window).resize(function() {
		  
		var winh = window.innerHeight;
		var overallh = $("body").height();
		var fooh = $(".footer-main").height();
		var overallhfoo = overallh + fooh;
		
		
		if (overallh+50 < winh) 
		  { $(".footer-main").addClass("footer-pushed-bottom"); $( "#page-wrapper" ).css( { \'height\':(winh-fooh-220) }, { queue: false, duration: 800 });  }
		  
		});
			
		
		var wrh = $(".wrapper-outer").height();
		var nahh = $(".navbar-side").height();
		
		if (wrh > nahh)
		{  $(".navbar-side").addClass("navbar-side-bottom"); }
		
	   $(\'.navbar-outer\').slimScroll({
			position: \'right\',
			height: \'100%\',
			railVisible: false,
			alwaysVisible: false
		});
		
		$( ".fixed .slimScrollDiv" ).css( { \'height\':sideh }, { queue: false, duration: 800 });
		
		
		
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
			$( "#page-wrapper" ).css( { \'min-height\':(navheight+20) }, { queue: false, duration: 800 });  
			$( ".simple-wrapper #page-wrapper" ).css( { \'min-height\':(navnewheight+100) }, { queue: false, duration: 800 });
			$( ".fixed.navbar-side" ).css( { \'max-height\':$("#page-wrapper").height() }, { queue: false, duration: 800 });
			
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
				$( "#page-wrapper" ).css( { \'min-height\':(navheight+20) }, { queue: false, duration: 800 });  
				$( ".simple-wrapper #page-wrapper" ).css( { \'min-height\':(navnewheight+100) }, { queue: false, duration: 800 });
				$( ".fixed.navbar-side" ).css( { \'max-height\':$("#page-wrapper").height() }, { queue: false, duration: 800 });
			 }
		
		 		// use setTimeout() to execute
				//setTimeout(showpanel, 500)
				
				
				//if( $(\'.navbar-side\').length > 0 ){
					//$(\'.sideBar\').height( $(\'.wrapper-outer\').height());
					if($(\'.sideBar.fixed\').length > 0){
						$(\'.sideBar.fixed\').hcSticky({
							top:200,
							bottomEnd: 13,
							innerTop: 50
						});
					}
					
				//}
				
			});
		
		}

    </script>

	'; ?>

    
</body>
</html>