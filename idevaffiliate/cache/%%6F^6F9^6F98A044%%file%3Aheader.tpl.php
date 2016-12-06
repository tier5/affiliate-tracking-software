<?php /* Smarty version 2.6.28, created on 2016-12-05 15:49:13
         compiled from file:header.tpl */ ?>
<!DOCTYPE HTML><html><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['char_set']; ?>
">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->_tpl_vars['sitename']; ?>
 - <?php echo $this->_tpl_vars['header_title']; ?>
</title>

	<link rel="stylesheet" href="templates/source/common/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="templates/source/common/bootstrap/css/bootstrap-social.css">
	<link rel="stylesheet" href="templates/source/common/font-awesome/font-awesome.css">
	<link rel="stylesheet" href="templates/source/common/morris_charts/css/morris.css">
	<link rel="stylesheet" href="templates/themes/<?php echo $this->_tpl_vars['active_theme']; ?>
/css/fonts.css">
	<link rel="stylesheet" href="templates/themes/<?php echo $this->_tpl_vars['active_theme']; ?>
/css/style.css">
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
	<?php echo '
	<script type="text/javascript" src="templates/source/common/js/easypiechart.ie-fix.js"></script>
	'; ?>

	<![endif]-->
	
	<!--[if lt IE 9]>
	<?php echo '
	<script type="text/javascript" src="templates/source/common/js/html5shiv.js"></script>
	<script type="text/javascript" src="templates/source/common/js/respond.min.js"></script>
	'; ?>

	<![endif]-->
		
	<?php echo '
	<script src="templates/source/lightbox/js/jquery-1.11.1.min.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/jquery.hc-sticky.min.js"></script>
	<script src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/css_browser_selector.js"></script>
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
    	langDataTable["sEmptyTable"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sEmptyTable']; ?>
<?php echo '";
		langDataTable["sInfo"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sInfo']; ?>
<?php echo '";
		langDataTable["sInfoEmpty"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sInfoEmpty']; ?>
<?php echo '";
		langDataTable["sInfoFiltered"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sInfoFiltered']; ?>
<?php echo '";
		langDataTable["sLengthMenu"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sLengthMenu']; ?>
<?php echo '";
		langDataTable["sLoadingRecords"] =  "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sLoadingRecords']; ?>
<?php echo '";
		langDataTable["sProcessing"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sProcessing']; ?>
<?php echo '";
		langDataTable["sSearch"] = "";
		langDataTable["sZeroRecords"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sZeroRecords']; ?>
<?php echo '";
		langDataTable["sFirst"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sFirst']; ?>
<?php echo '";
		langDataTable["sLast"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sLast']; ?>
<?php echo '";
		langDataTable["sNext"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sNext']; ?>
<?php echo '";
		langDataTable["sPrevious"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sPrevious']; ?>
<?php echo '";
		langDataTable["sSortAscending"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sSortAscending']; ?>
<?php echo '";
		langDataTable["sSortDescending"] = "'; ?>
<?php echo $this->_tpl_vars['lang_data_table']['sSortDescending']; ?>
<?php echo '";
    </script>
    <!-- Language variables for Datatables (End) -->
    <script type="text/javascript" src="templates/source/common/js/dynamic_tables.js"></script>
	<script type="text/javascript" src="templates/themes/'; ?>
<?php echo $this->_tpl_vars['active_theme']; ?>
<?php echo '/js/custom.js"></script>
	'; ?>

    
    
</head><body style="background:<?php echo $this->_tpl_vars['background_color']; ?>
;">
	<div id="wrapper" <?php if ($this->_tpl_vars['cp_fixed_left_menu'] == 0): ?> class="simple-wrapper"<?php endif; ?>>
    
  
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:menu_top.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div id="main-container" class="<?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?> container <?php else: ?>full-width-main-container<?php endif; ?>">
    
    <div id="page-wrapper"<?php if (! isset ( $this->_tpl_vars['cp_menu_location'] ) || ! isset ( $this->_tpl_vars['inner_page'] )): ?> class="collapsed"<?php endif; ?>>
    

	<div class="row <?php if ($this->_tpl_vars['cp_menu_location'] == 1): ?> wrapper-outer<?php endif; ?>">
	
    <?php if ($this->_tpl_vars['cp_menu_location'] == 1): ?> <div class="col-md-3 col-lg-2 sideBar hidden-xs <?php if (isset ( $this->_tpl_vars['cp_fixed_left_menu'] )): ?> fixed<?php endif; ?>"> <?php endif; ?>
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:menu_left_column.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php if ($this->_tpl_vars['cp_menu_location'] == 1): ?> </div> <?php endif; ?>
    
    <div class="visible-xs-block mobileMenu">
    	<a class="btn btn-primary visible-xs-block" data-toggle="collapse" href="#optionMenu" aria-expanded="false" aria-controls="optionMenu">Menu</a>
        <div class="collapse" id="optionMenu">
    		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:menu_left_column.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
    </div>
    
    <div class="pull-right col-xs-12 
    <?php if (isset ( $this->_tpl_vars['inner_page'] ) == 1): ?> set1 <?php endif; ?>
    
    <?php if ($this->_tpl_vars['cp_menu_location'] == 0): ?> 
    	navigation-at-top col-md-12 col-lg-12 set2 
    <?php else: ?>
    	<?php if (isset ( $this->_tpl_vars['inner_page'] ) == 1): ?> contentArea col-sm-8 col-md-9 col-lg-9  set3<?php endif; ?>
    <?php endif; ?>
    
    <?php if (isset ( $this->_tpl_vars['inner_page'] ) != 1): ?> set4<?php endif; ?>
    
    <?php if (isset ( $this->_tpl_vars['cp_page_width'] ) && isset ( $this->_tpl_vars['inner_page'] )): ?> fullWidth <?php else: ?> fixedWidth<?php endif; ?>
    ">
    <!--
    <?php if ($this->_tpl_vars['cp_menu_location'] == 0): ?> navigation-at-top<?php endif; ?>
    <?php if (! isset ( $this->_tpl_vars['cp_page_width'] ) && isset ( $this->_tpl_vars['inner_page'] )): ?> col-lg-9 <?php else: ?> col-lg-12<?php endif; ?>
    -->
	