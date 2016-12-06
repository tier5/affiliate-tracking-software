<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
 <?php
             if (strpos($_SERVER['REQUEST_URI'],'link/createlink')>0) {?>
 <link href="/css/style_review.css" rel="stylesheet" />

            <?php }?>
    <!-- Begin primary / secondary color css.  Should probably move -->
    <style type="text/css">
        .page-sidebar .page-sidebar-menu > li.active > a {
            background: {{ primary_color }} none repeat scroll 0 0 !important;
        }

        .btnSecondary, .sms-chart-wrapper .bar-filled, .backgroundSecondary {
            background-color: {{ secondary_color }} !important;
        }
        .sms-chart-wrapper .bar-number .ball, .table-bottom, #reviews .pagination > li > a, .pagination > li > span, .growth-bar, .btnPrimary, .referral-link, .backgroundPrimary {
            background-color: {{ primary_color }} !important;
        }
        #reviews .pagination .active > a, .pagination .active > a:hover {
            color: {{ secondary_color }} !important;
        }
        .feedback_requests {
            color: {{ secondary_color }} !important;
        }
        .nav-tabs .active > a {
            border-top: 4px solid {{ primary_color }} !important;
        }

        div#image_container img.selected, div#image_container img:hover {
            border-color: {{ secondary_color }} !important;
        }
    </style>
    <meta charset="utf-8"/>
    {{ get_title() }}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- include needed css in partial -->
    {% include "partials/layouts/private-css.volt" %}

    <!-- output css based on controller -->
    {{ assets.outputCss() }}

    
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
    
    <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/js/vendor/fancybox/jquery.fancybox.pack.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    {% if main_color_setting %}
        <style>


            .page-sidebar .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                border-top: {{ main_color_setting }};
                color: #333;
            }

            .page-sidebar .page-sidebar-menu > li.active.open > a,
            .page-sidebar .page-sidebar-menu > li.active > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a {
                background-color: {{ main_color_setting }};
            }

            li.nav-item:hover,
            li.nav-item a:hover,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                background-color: {{ main_color_setting }} !important;
            }

            .minicolors-swatch-color {
                background-color: {{ main_color_setting }};
            }

            li.nav-item:hover,
            li.nav-item a:hover,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                background: {{ main_color_setting }} none repeat scroll 0 0 !important;
            }

            .page-sidebar .page-sidebar-menu > li.open > a > .arrow.open::before,
            .page-sidebar .page-sidebar-menu > li.open > a > .arrow::before,
            .page-sidebar .page-sidebar-menu > li.open > a > i,
            .page-sidebar .page-sidebar-menu > li > a > .arrow.open::before,
            .page-sidebar .page-sidebar-menu > li > a > .arrow::before,
            .page-sidebar .page-sidebar-menu > li > a > i,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > .arrow.open::before,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > .arrow::before,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.open > a > i,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > .arrow.open::before,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > .arrow::before,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > i {
                color: #FFFFFF !important;
            }
            .page-content-wrapper{
                margin-top:-95px;
                padding-top:75px;
            }

            .icon-settings {
                color: #6b788b !important;
            }
        </style>
    {% endif %}
    <style type="text/css">
    </style>
    <link rel="stylesheet" href="/dashboard/css?primary_color={{ primary_color }}&secondary_color={{ secondary_color }}">
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white" data-ccprompt="{{ ccInfoRequired }}" data-paymentprovider="{{ paymentService }}">

 
        <div class="page-content">
            {{ flashSession.output() }}
            {{ content() }}
        </div>

        <div class="page-footer">
    <div class="page-footer-inner"> {{ date("Y") }} &copy; <?=(isset($this->view->agencyName))?$this->view->agencyName: "Get Mobile Reviews";?></div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
 
</body>
</html>
