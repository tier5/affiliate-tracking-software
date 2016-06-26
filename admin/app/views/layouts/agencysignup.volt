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
    <link href="/css/agencysignup.css" rel="stylesheet" type="text/css" />



    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>


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
            <div id="step1">Step 1</div>
            <div id="step2">Step 2</div>
            <div id="step3">Step 3</div>
            <div id="step4">Step 4</div>
            <div id="step5">Step 5</div>
        </div>
    </div>
</header>

<div class="content">
    {{ content() }}
</div>
<footer>
    <div class="copyright"> &copy; Copyright Review Velocity.  All Rights Reserved. </div>
</footer>

</body>

</html>