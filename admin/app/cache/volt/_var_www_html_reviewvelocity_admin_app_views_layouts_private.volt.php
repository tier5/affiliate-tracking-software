<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <?php echo $this->tag->getTitle(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- include needed css in partial -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="/public/assets/global/plugins/seiyria-bootstrap-slider/dist/css/bootstrap-slider.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<link rel="stylesheet" href="/js/vendor/minicolors/jquery.minicolors.css" />
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
<link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->
<link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/js/vendor/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<link href="/css/admin.css" rel="stylesheet" type="text/css" />

    <!-- output css based on controller -->
    <?php echo $this->assets->outputCss(); ?>

    <link rel="shortcut icon" href="favicon.ico" />
    <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/js/vendor/fancybox/jquery.fancybox.pack.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <?php if ($main_color_setting) { ?>
        <style>
            .page-header.navbar {
                background-color: <?php echo $main_color_setting; ?>;
            }

            body {
                background-color: rgba(<?php echo $rgb; ?>, 0.8);
            }

            .page-sidebar .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                border-top: <?php echo $main_color_setting; ?>; color: #FFFFFF;
            }

            .page-sidebar .page-sidebar-menu > li.active.open > a,
            .page-sidebar .page-sidebar-menu > li.active > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a {
                background-color: <?php echo $main_color_setting; ?>;
            }

            li.nav-item:hover,
            li.nav-item a:hover,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                background-color: <?php echo $main_color_setting; ?> !important;
            }

            .minicolors-swatch-color {
                background-color: <?php echo $main_color_setting; ?>;
            }

            li.nav-item:hover,
            li.nav-item a:hover,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a,
            .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a {
                background: <?php echo $main_color_setting; ?> none repeat scroll 0 0 !important;
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
        </style>
    <?php } ?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo" style="margin-top: 0;">
            <a href="/">
                <img src="<?=(isset($logo_setting) && $logo_setting != ''?$logo_setting:'/assets/layouts/layout/img/logo.png')?>" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler"> </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <?php if ($haspaid) { ?>
                    <?php if (!$is_admin && $agencytype != 'agency') { ?>
                        <li class="" id="">
                            <a href="#sendreviewinvite" class="fancybox"><img src="/img/btn_send_review_invite.png" alt="Send Review Invite" /></a>
                        </li>
                    <?php } ?>
                    <?php if ($location_id) { ?>
                        <?php if ($locations) { ?>
                            <li class="location-header" id="">
                                <span id="locationset">
                                    Location: <?php echo $location->name; ?>
                                    <?php if ($this->length($locations) > '1') { ?>
                                        <a href="#" onclick="$('#locationset').hide();$('#locationnotset').show();return false;">Change</a>
                                    <?php } ?>
                                </span>
                                <span id="locationnotset" style="display: none;"><form action="/" method="post">
                                    Location:
                                    <select name="locationselect" id="locationselect">
                                        <?php if ($this->length($locations) > '1') { ?>
                                            <?php foreach ($locations as $loc) { ?>
                                                <option value='<?php echo $loc->location_id; ?>'><?php echo $loc->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <input type="submit" class="btn red" value="Change"></form>
                                </span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <li class="dropdown dropdown-user" style="margin-left: 20px;">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="username username-hide-on-mobile" style="color: #484848;"><i class="icon-user"></i> <?php echo $name; ?> </span>
                            <i class="fa fa-angle-down" style="color: #484848;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="#">
                                    <i class="icon-user"></i> My Profile </a>
                            </li>
                            <li>
                                <a href="/session/logout">
                                    <i class="icon-key"></i>
                                    <span class="title">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper hide">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler"> </div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>

                <?php if ($haspaid) { ?>
                    <?php if ($is_admin) { ?>
                        <li class="nav-item start">
                            <a href="/admindashboard/" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item start">
                            <a href="/admindashboard/list/2" class="nav-link nav-toggle">
                                <i class="icon-pointer"></i>
                                <span class="title">See All Businesses</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item start">
                            <a href="/admindashboard/list/1" class="nav-link nav-toggle">
                                <i class="icon-pointer"></i>
                                <span class="title">See All Agencies</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <?php if ($internalNavParams['hasPricingPlans']) { ?>
                            <li class="nav-item">
                                <a href='/businessPricingPlan' class="nav-link nav-toggle">
                                    <i class="icon-list"></i>
                                    <span class="title">Business Subscriptions</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="nav-item start">
                            <a href="/admindashboard/settings" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Settings</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    <?php } else { ?>
                        <?php if ($agencytype == 'agency') { ?>
                            <li class="nav-item start">
                                <a href="/agency" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Manage Businesses</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item start">
                                <a href="/" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Dashboard</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($location_id) { ?>
                            <?php if ($agencytype != 'agency') { ?>
                                <li class="nav-item">
                                    <a href="/reviews/" class="nav-link nav-toggle">
                                        <i class="icon-diamond"></i>
                                        <span class="title">Reviews</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/analytics/" class="nav-link nav-toggle">
                                        <i class="icon-bar-chart"></i>
                                        <span class="title">Analytics</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                <?php if ($is_business_admin) { ?>
                                    <li class="nav-item">
                                        <a href="/reviews/sms_broadcast" class="nav-link nav-toggle">
                                            <i class="icon-envelope"></i>
                                            <span class="title">SMS Broadcast</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/contacts" class="nav-link nav-toggle">
                                            <i class="icon-users"></i>
                                            <span class="title">Contacts</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/users/" class="nav-link nav-toggle">
                                            <i class="icon-user"></i>
                                            <span class="title">Employees</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($profile == 'Agency Admin' && $agencytype == 'business') { ?>
                            <li class="nav-item">
                                <a href="/location/" class="nav-link nav-toggle">
                                    <i class="icon-pointer"></i>
                                    <span class="title">Locations</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($profile != 'Employee') { ?>
                            <?php if ($agencytype == 'agency') { ?>
                                <?php $SettingsLocation = 'agency'; ?>
                            <?php } else { ?>
                                <?php $SettingsLocation = 'location'; ?>
                            <?php } ?>
                            <li class="nav-item">
                                <a href="/settings/<?php echo $SettingsLocation; ?>/" class="nav-link nav-toggle">
                                    <i class="icon-settings"></i>
                                    <span class="title">Settings</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/users/" class="nav-link nav-toggle">
                                    <i class="icon-user"></i>
                                    <span class="title">Admin Users</span>
                                    <span class="selected"></span>
                                </a>
                            </li>

                            <?php if ($internalNavParams['hasSubscriptions']) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo $internalNavParams['subscriptionController']; ?>" class="nav-link nav-toggle">
                                        <i class="icon-wallet"></i>
                                        <span class="title">Subscriptions</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <?php echo $this->getContent(); ?>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> <?php echo date('Y'); ?> &copy; Review Velocity </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<!-- include needed javascript from partial -->
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
<script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
<script src="/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="/assets/global/plugins/seiyria-bootstrap-slider/dist/bootstrap-slider.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/js/vendor/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.tableTools.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.responsive.js"></script>
<script type="text/javascript" src="/js/vendor/tables-data.js"></script>
<script type="text/javascript" src="/js/vendor/buttons.js"></script>
<script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/pages/scripts/dashboard.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script type="text/javascript" src="/js/vendor/minicolors/jquery.minicolors.js"></script>
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/star-rating.js" type="text/javascript"></script>
<!-- <script src="/js/pdf-viewer.js" type="text/javascript"></script> -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="/js/card-js.min.js"></script>
<script src="/js/login.js"></script>


<!-- add required js from controller -->
<?php echo $this->assets->outputJs(); ?>

TEST
<?php echo $num_signed_up; ?>
<?php echo $sms_sent_this_month_total; ?>
<?php echo $total_sms_month; ?>
<?php if ($haspaid) { ?>
    <?php if (!$is_admin && $agencytype != 'agency') { ?>
        <?php if ($location_id) { ?>
            <div id="sendreviewinvite" style="width:400px; display: none; color: #7A7A7A;">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject" style="text-align: left; text-transform: none; font-weight: normal; font-size: 27px !important;"> Send Review Invite </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <?php if (($agency->twilio_api_key != '' && $agency->twilio_auth_token != '' && ($agency->twilio_auth_messaging_sid != '' || $agency->twilio_from_phone != '')) || ($agency->parent_agency_id && $agency->agency_type_id == '2')) { ?>

                           <?php if ($num_signed_up) { ?>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12">
                                        <i>You are entitled to <?php echo $total_sms_month; ?> SMS messages per month.  You have sent <?php echo $sms_sent_this_month_total; ?> total SMS messages this month.</i>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($sms_sent_this_month < $total_sms_month) { ?>
                                <form class="form-horizontal" action="/location/send_review_invite" role="form" method="post" autocomplete="off" id="smsrequestform" >
                                    <div class="success" id="smsrequestformsuccess" style="display: none;">The review invite was sent.</div>
                                    <div class="error" id="smsrequestformerror" style="display: none;">There was a problem sending the review invite.</div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-md-3 control-label" style="text-align: left;">Location</label>
                                            <div class="col-md-9" style="margin-top: 8px; margin-bottom: 8px;">
                                                <?php echo $location->name; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Name" name="name" id="smsrequestformname" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <i>This is the name that will be used in the SMS text message.</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Phone" name="phone" id="smsrequestformphone" value="<?=(isset($_POST['phone'])?$_POST["phone"]:'')?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <i>The phone number that will recieve the SMS message.</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <textarea style="width: 100%;" class="form-control placeholder-no-fix" name="SMS_message"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($location->SMS_message)?$location->SMS_message:'{location-name}: Hi {name}, We\'d really appreciate your feedback by clicking the link. Thanks! {link}'))?></textarea>
                                                <i>{location-name} will be the name of the location sending the SMS, {name} will be replaced with the name entered when sending the message and {link} will be the link to the review.</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-8">
                                            <?php echo $this->tag->submitButton(array('Send', 'class' => 'btnLink', 'style' => 'float: right; height: 50px; padding: 10px 35px;')); ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } else { ?>
                                You have no more SMS messages to send this month.
                            <?php } ?>
                        <?php } else { ?>
                            You must have a Twilio SID and Auth Token to send SMS messages.  All SMS messages are sent using <a href="https://www.twilio.com/" target="_blank">Twilio</a>.  <a href="/settings/">Click here</a> to enter your Twilio SID and Auth Key now.  If you don't have an API key yet, <a href="https://www.twilio.com/try-twilio" target="_blank">click here</a> to sign up.
                        <?php } ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                    $('.fancybox').fancybox();

                    //callback handler for form submit
                    $("#smsrequestform").submit(function (e)
                    {
                        var postData = $(this).serializeArray();
                        var formURL = $(this).attr("action");
                        $.ajax(
                                {
                                    url: formURL,
                                    type: "POST",
                                    data: postData,
                                    success: function (data, textStatus, jqXHR)
                                    {
                                        //data: return data from server
                                        //console.log(data);
                                        if (data == 'true') {
                                            //$('#smsrequestformsuccess').show();
                                            $('#smsrequestformerror').hide();
                                            $('.fancybox-overlay').hide();
                                        } else {
                                            //if fails
                                            $('#smsrequestformerror').text(data);
                                            $('#smsrequestformsuccess').hide();
                                            $('#smsrequestformerror').show();
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown)
                                    {
                                        //if fails
                                        $('#smsrequestformsuccess').hide();
                                        $('#smsrequestformerror').show();
                                    }
                                });
                        e.preventDefault(); //STOP default action
                    });
                });
            </script>
        <?php } ?>
    <?php } ?>
<?php } ?>
</body>

</html>