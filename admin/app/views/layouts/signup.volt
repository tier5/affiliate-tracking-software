<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    {{ get_title() }}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <link rel="stylesheet" href="/js/vendor/minicolors/jquery.minicolors.css" />
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/css/login.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />

    <link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />

    <link href="/css/main.css" rel="stylesheet" />
    <link href="/css/signup.css" rel="stylesheet" type="text/css" />
    <?php
if (isset($main_color_setting)) {
  list($r, $g, $b) = sscanf($main_color_setting, "#%02x%02x%02x");
  //echo "$main_color_setting -> $r $g $b";
    ?>
    <style> /*
  .page-header.navbar { background-color: <?=$main_color_setting?>; }
  body { background-color: rgba(<?=$r?>, <?=$g?>, <?=$b?>, 0.8); }
  .page-sidebar .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a { border-top: <?=$main_color_setting?>; color: #FFFFFF; }
  .page-sidebar .page-sidebar-menu > li.active.open > a, .page-sidebar .page-sidebar-menu > li.active > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a { background-color: <?=$main_color_setting?>; }
  li.nav-item:hover, li.nav-item a:hover, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a { background-color: <?=$main_color_setting?> !important; }
  .minicolors-swatch-color { background-color: <?=$main_color_setting?>; }

  li.nav-item:hover, li.nav-item a:hover,
  .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a,
  .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
    background: <?=$main_color_setting?> none repeat scroll 0 0 !important;
  }
  .page-sidebar .page-sidebar-menu > li.open > a > .arrow.open::before, .page-sidebar .page-sidebar-menu > li.open > a > .arrow::before, .page-sidebar .page-sidebar-menu > li.open > a > i, .page-sidebar .page-sidebar-menu > li > a > .arrow.open::before, .page-sidebar .page-sidebar-menu > li > a > .arrow::before, .page-sidebar .page-sidebar-menu > li > a > i, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > .arrow.open::before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > .arrow::before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > i, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > .arrow.open::before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > .arrow::before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > i { color: #FFFFFF !important; }

  body {
      background-color: <?=$main_color_setting?>;
  }
  .login {
    background-color: <?=$main_color_setting?> !important;
  }*/
    </style>
    <?php
}
?>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
</head>
<!-- END HEAD -->
<body class="signup">
<div class="topheader"></div>
<header>
    <div class="headercontent">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="/"><img src="<?=(false && isset($logo_setting) && $logo_setting != ''?$logo_setting:'/img/logo-white.gif')?>" alt="" /></a>
        </div>
        <!-- END LOGO -->
        <div class="steps">
            <div class="step"><img src="/img/step-<?=($current_step > 1?'on':'current')?>.gif" alt="Sign up form, Step 1 (Account)" /></div>
            <div class="divider"><img src="/img/step-line-<?=($current_step > 1?'on':'off')?>.gif" /></div>
            <div class="step"><img src="/img/step-<?=($current_step == 2?'current':($current_step > 2?'on':'off'))?>.gif" alt="Sign up form, Step 2 (Add Location)" /></div>
            <div class="divider"><img src="/img/step-line-<?=($current_step > 2?'on':'off')?>.gif" /></div>
            <div class="step"><img src="/img/step-<?=($current_step == 3?'current':($current_step > 3?'on':'off'))?>.gif" alt="Sign up form, Step 3 (Customize Survey)" /></div>
            <div class="divider"><img src="/img/step-line-<?=($current_step > 3?'on':'off')?>.gif" /></div>
            <div class="step"><img src="/img/step-<?=($current_step == 4?'current':($current_step > 4?'on':'off'))?>.gif" alt="Sign up form, Step 4 (Add Employee)" /></div>
            <div class="divider"><img src="/img/step-line-<?=($current_step > 4?'on':'off')?>.gif" /></div>
            <div class="step"><img src="/img/step-<?=($current_step == 5?'current':($current_step > 5?'on':'off'))?>.gif" alt="Sign up form, Step 5 (Share)" /></div>
        </div>
        <div class="steps-desc">
            <div id="step1">Account</div>
            <div id="step2">Add Location</div>
            <div id="step3">Customize Survey</div>
            <div id="step4">Add Employee</div>
            <div id="step5">Share</div>
        </div>
    </div>
</header>
<!-- BEGIN LOGIN -->
<div class="content">
    {{ content() }}
</div>
<footer>
    <div class="copyright"> &copy; Copyright Review Velocity.  All Rights Reserved. </div>
    <footer>
        <!--[if lt IE 9]>
        <script src="/assets/global/plugins/respond.min.js"></script>
        <script src="/assets/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/assets/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script type="text/javascript" src="/js/vendor/minicolors/jquery.minicolors.js"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script src="/js/card-js.min.js"></script>
        <script src="/js/login.js"></script>
</body>

</html>