<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>

    <link href="/css/admin.css" rel="stylesheet" type="text/css" />
    <link href="/css/agencyorder.css" rel="stylesheet" type="text/css" />
    <link href="/css/subscription.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="favicon.ico" />

    <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="/js/vendor/minicolors/jquery.minicolors.css" />

    <script type="text/javascript" src="/js/bootstrap.min.js"></script>

    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />



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
    </style>
</head>
<!-- END HEAD -->

<body>
    <div class="container">
    {{ flashSession.output() }}
    {% if DisplayTranslator %}
    <div class="row">
        <div class="col-xs-4 col-xs-offset-3 col-sm-offset-5 col-lg-offset-5 small-vertical-margins" style="margin-top: 30px; margin-right: 30px;">
            <div id="google_translate_element"></div>
            <script type="text/javascript">
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </div>
    </div>
    {% endif %}
    {{ content() }}
    </div>
<div style="clear: both;"></div>
<footer class="PrimaryColor">
    <div class="copyright"> &copy; Copyright Review Velocity.  All Rights Reserved.  <a data-toggle="modal" data-target="#Terms">Terms of Service</a> | <a data-toggle="modal" data-target="#Privacy">Privacy Policy</a></div>
</footer>

    <div id="Terms" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="TermsTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
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