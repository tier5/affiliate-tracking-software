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
    
    <link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    

    <link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />

    <link href="/css/main.css" rel="stylesheet" />
    <link href="/css/signup.css" rel="stylesheet" type="text/css" />
    <?php
if (isset($main_color_setting)) {
  list($r, $g, $b) = sscanf($main_color_setting, "#%02x%02x%02x");
  //echo "$main_color_setting -> $r $g $b";
    ?>
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
        <?php
            if($logo_path == '/img/agency_logos/' || !$logo_path) {
                $MarginLeftSteps = "margin-left: 280px !important;";
                $MarginLeftWords = "margin-left: 269px !important;";
            } else {
                if($logo_path == '/assets/layouts/layout/img/logo.png') {
                    $MarginLeftSteps = 'margin-left: 35px !important;';
                    $MarginLeftWords = "margin-left: 244px !important;";
                } else {
                    $MarginLeftSteps = '';
                    $MarginLeftWords = '';
                }
            }

            $redCheckCircle = '<i class="fa fa-check-circle fa-2x" aria-hidden="true" style="color: #c01209;" title="Sign up form, Step 1 (Account)"></i>';
            $redCircle = '<i class="fa fa-circle fa-2x" aria-hidden="true" style="color: #c01209;"></i>';
            $greyCircle = '<i class="fa fa-circle fa-2x" aria-hidden="true" style="color: #e6ecf0;"></i>';
         ?>
        <!-- END LOGO -->
        <div id="label-wrapper">
            {% if logo_path AND logo_path != "/img/agency_logos/" %}
            <div class="logo" style="margin-top: 43px; margin-bottom: 43px;">
                <a href="/"><img class="center-block"  src="{{ logo_path }}" alt="" /></a>
            </div>
            {% endif %}
            <div id="label-container" class="center-block">
                <div class="steps">
                    <div class="step pull-left">
                        <?=($current_step > 1 ? $redCheckCircle : $redCircle)?>
                        
                    </div>
                    <div class="divider pull-left">
                        <img src="/img/step-line-<?=($current_step > 1?'on':'off')?>.gif" />
                    </div>
                    <div class="step pull-left">
                        <?=($current_step == 2 ? $redCircle : ($current_step > 2 ? $redCheckCircle : $greyCircle))?>
                    </div>
                    <div class="divider pull-left">
                        <img src="/img/step-line-<?=($current_step > 2?'on':'off')?>.gif" />
                    </div>
                    <div class="step pull-left">
                        <?=($current_step == 3 ? $redCircle : ($current_step > 3 ? $redCheckCircle : $greyCircle))?>
                    </div>
                    <div class="divider pull-left">
                        <img src="/img/step-line-<?=($current_step > 3?'on':'off')?>.gif" />
                    </div>
                    <div class="step pull-left">
                        <?=($current_step == 4 ? $redCircle : ($current_step > 4 ? $redCheckCircle : $greyCircle))?>
                    </div>
                    <div class="divider pull-left">
                        <img src="/img/step-line-<?=($current_step > 4?'on':'off')?>.gif" />
                    </div>
                    <div class="step pull-left">
                        <?=($current_step == 5 ? $redCircle : ($current_step > 5 ? $redCheckCircle : $greyCircle))?>
                    </div>
                </div>
                <div class="steps-desc">
                    <div id="step1">Account</div>
                    <div id="step2">Add Location</div>
                    <div id="step3">Customize Survey</div>
                    <div id="step4">Add Employee</div>
                    <div id="step5">Share</div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- BEGIN LOGIN -->
<div class="content">
    {{ content() }}
</div>
<footer>
    <div class="copyright"> &copy; Copyright <?php echo $this->view->parent_agency; ?>.  All Rights Reserved. </div>
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
        <script src="/js/signup.js"></script>
</footer>
</body>

</html>
