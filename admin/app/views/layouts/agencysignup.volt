<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <meta charset="utf-8" />
    {{ get_title() }}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
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
    <link rel="shortcut icon" href="/img/favicon.png" />
    <link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <link href="/css/admin.css" rel="stylesheet" type="text/css" />

    <link href="/css/agencysignup.css" rel="stylesheet" type="text/css" />

     <style type="text/css">
        .PrimaryColor {
            background-color: {{ PrimaryColor }} !important;
        }
        .SecondaryColor {
            background-color: {{ SecondaryColor }} !important;
        }
        .PrimaryColorText {
            color: {{ PrimaryColor }} !important;
        }
        .SecondaryColorText {
            color: {{ SecondaryColor }} !important;
        }
        body {
            border-top: 10px solid {{ PrimaryColor }} !important;
        }
        footer {
            background-color: {{ PrimaryColor }};
            color: #ffffff;
            height: 80px;
        }
    </style>

    {% if current_step %}
        <style type="text/css">
            body {
                background-color: #f2f2f2;
            }

        </style>
    {% endif %}

</head>
<body class="signup">
{% if current_step %}
    <header>
        <div class="headercontent">
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href="/"><img src="<?=(false && isset($logo_path) && $logo_path != ''?$logo_path:'/img/logo-white.gif')?>" alt="" /></a>
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
{% endif %}

<?php
?>

<div class="container">
    {{ flashSession.output() }}
    {{ content() }}
    </div>
<div style="clear: both;"></div>
<footer>
    <div class="copyright PrimaryColor"> &copy; Copyright {{agency_name}}.  All Rights Reserved.  <a data-toggle="modal" data-target="#Terms">Terms of Service</a> | <a data-toggle="modal" data-target="#Privacy">Privacy Policy</a></div>
</footer>

    <div id="Terms" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="TermsTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-title" id="TermsTitle">
                        Terms of Service Title
                    </div>
                </div>
                <div class="modal-body">
                    Terms of Service Content
                </div>
                <div class="modal-footer">
                    Terms of Service Footer
                </div>
            </div>
        </div>
    </div>

    <div id="Privacy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="PrivacyTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-title" id="PrivacyTitle">
                        Privacy Policy Title
                    </div>
                </div>
                <div class="modal-body">
                    Privacy Policy Content
                </div>
                <div class="modal-footer">
                    Privacy Policy Footer
                </div>
            </div>
        </div>
    </div>

</body>

</html>