{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}
<!DOCTYPE HTML><html><head>
<meta http-equiv="Content-Type" content="text/html; charset={$char_set}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>{$sitename} - {$header_title}</title>

	<link rel="stylesheet" href="templates/source/common/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="templates/source/common/bootstrap/css/bootstrap-social.css">
	<link rel="stylesheet" href="templates/source/common/font-awesome/font-awesome.css">
	<link rel="stylesheet" href="templates/source/common/morris_charts/css/morris.css">
	<link rel="stylesheet" href="templates/themes/{$active_theme}/css/fonts.css">
	<link rel="stylesheet" href="templates/themes/{$active_theme}/css/style.css">
	<link rel="stylesheet" href="templates/source/lightbox/css/jquery.fancybox.css" />
	<link rel="stylesheet" href="templates/source/lightbox/css/video-js.css" />
	<link rel="stylesheet" href="includes/video_source/skin/functional.css" />
	<link rel="stylesheet" href="templates/source/common/pace/css/pace.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/uniform.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/tagsinput.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/select2.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/duallistbox.css" />
    <link rel="stylesheet" href="admin/templates/css/fontawesome/font-awesome.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/datatables_bootstrap.css" />
    <link rel="stylesheet" href="admin/templates/css/plugins/datatables.css" />
    	
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="templates/source/common/css/ie-fix.css" />
	<![endif]-->
	
	<!--[if lte IE 8]>
	{literal}
	<script type="text/javascript" src="templates/source/common/js/easypiechart.ie-fix.js"></script>
	{/literal}
	<![endif]-->
	
	<!--[if lt IE 9]>
	{literal}
	<script type="text/javascript" src="templates/source/common/js/html5shiv.js"></script>
	<script type="text/javascript" src="templates/source/common/js/respond.min.js"></script>
	{/literal}
	<![endif]-->
		
	{literal}
	<script src="templates/source/lightbox/js/jquery-1.11.1.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.hc-sticky.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/css_browser_selector.js"></script>
	<script src="templates/source/lightbox/js/jquery.mousewheel-3.0.6.pack.js"></script>
	<script src="templates/source/lightbox/js/video.js"></script>
	<script src="templates/source/lightbox/js/jquery.fancybox.js"></script>
	<script src="templates/source/lightbox/js/jquery.fancybox-media.js"></script>
	<script src="templates/source/lightbox/js/fancy-custom.js"></script>
	<script src="includes/video_source/flowplayer.min.js"></script>
	<script src="templates/source/common/morris_charts/js/raphael-min.js"></script>
	<script src="templates/source/common/morris_charts/js/morris.js"></script>
    <script type="text/javascript" src="admin/templates/plugins/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="admin/templates/plugins/select2/select2.min.js"></script> 
    <script type="text/javascript" src="admin/templates/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="admin/templates/plugins/datatables/tabletools/TableTools.min.js"></script>
    <script type="text/javascript" src="admin/templates/plugins/datatables/colvis/ColVis.min.js"></script>
    <script type="text/javascript" src="admin/templates/plugins/datatables/DT_bootstrap.js"></script>
    <!-- Language variables for Datatables (Start) -->
    <script type="text/javascript">
    	var langDataTable = {};
    	langDataTable["sEmptyTable"] = "{/literal}{$lang_data_table.sEmptyTable}{literal}";
		langDataTable["sInfo"] = "{/literal}{$lang_data_table.sInfo}{literal}";
		langDataTable["sInfoEmpty"] = "{/literal}{$lang_data_table.sInfoEmpty}{literal}";
		langDataTable["sInfoFiltered"] = "{/literal}{$lang_data_table.sInfoFiltered}{literal}";
		langDataTable["sLengthMenu"] = "{/literal}{$lang_data_table.sLengthMenu}{literal}";
		langDataTable["sLoadingRecords"] =  "{/literal}{$lang_data_table.sLoadingRecords}{literal}";
		langDataTable["sProcessing"] = "{/literal}{$lang_data_table.sProcessing}{literal}";
		langDataTable["sSearch"] = "";
		langDataTable["sZeroRecords"] = "{/literal}{$lang_data_table.sZeroRecords}{literal}";
		langDataTable["sFirst"] = "{/literal}{$lang_data_table.sFirst}{literal}";
		langDataTable["sLast"] = "{/literal}{$lang_data_table.sLast}{literal}";
		langDataTable["sNext"] = "{/literal}{$lang_data_table.sNext}{literal}";
		langDataTable["sPrevious"] = "{/literal}{$lang_data_table.sPrevious}{literal}";
		langDataTable["sSortAscending"] = "{/literal}{$lang_data_table.sSortAscending}{literal}";
		langDataTable["sSortDescending"] = "{/literal}{$lang_data_table.sSortDescending}{literal}";
    </script>
    <!-- Language variables for Datatables (End) -->
    <script type="text/javascript" src="templates/source/common/js/dynamic_tables.js"></script>
	<script type="text/javascript" src="templates/themes/{/literal}{$active_theme}{literal}/js/custom.js"></script>
	{/literal}
    
    
</head><body style="background:{$background_color};">
	<div id="wrapper" {if $cp_fixed_left_menu == 0} class="simple-wrapper"{/if}>
    
  
	{include file='file:menu_top.tpl'}
    <div id="main-container" class="{if !isset($cp_page_width) } container {else}full-width-main-container{/if}">
    
    <div id="page-wrapper"{if !isset($cp_menu_location) || !isset($inner_page)} class="collapsed"{/if}>
    

	<div class="row {if $cp_menu_location == 1} wrapper-outer{/if}">
	
    {if $cp_menu_location == 1 } <div class="col-md-3 col-lg-2 sideBar hidden-xs {if isset($cp_fixed_left_menu)} fixed{/if}"> {/if}
    	{include file='file:menu_left_column.tpl'}
    {if $cp_menu_location == 1} </div> {/if}
    
    <div class="visible-xs-block mobileMenu">
    	<a class="btn btn-primary visible-xs-block" data-toggle="collapse" href="#optionMenu" aria-expanded="false" aria-controls="optionMenu">Menu</a>
        <div class="collapse" id="optionMenu">
    		{include file='file:menu_left_column.tpl'}
        </div>
    </div>
    
    <div class="pull-right col-xs-12 
    {if isset($inner_page) == 1} set1 {/if}
    
    {if $cp_menu_location == 0} 
    	navigation-at-top col-md-12 col-lg-12 set2 
    {else}
    	{if isset($inner_page) == 1} contentArea col-sm-8 col-md-9 col-lg-9  set3{/if}
    {/if}
    
    {if isset($inner_page) != 1} set4{/if}
    
    {if isset($cp_page_width) && isset($inner_page) } fullWidth {else} fixedWidth{/if}
    ">
    <!--
    {if $cp_menu_location == 0} navigation-at-top{/if}
    {if !isset($cp_page_width) && isset($inner_page) } col-lg-9 {else} col-lg-12{/if}
    -->
	