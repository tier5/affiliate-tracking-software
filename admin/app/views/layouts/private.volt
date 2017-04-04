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
    <script src="/js/rollbar.js"></script>
 <?php
             if (strpos($_SERVER['REQUEST_URI'],'link/createlink')>0) {?>
 <link href="/css/style_review.css" rel="stylesheet" />

            <?php }?>
    <!-- Begin primary / secondary color css.  Should probably move -->
    <style type="text/css">
        .page-sidebar .page-sidebar-menu > li.active > a {
            /*background: {{ primary_color }} none repeat scroll 0 0 !important;*/
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
                /*color: #FFFFFF !important;*/
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.close-div').click(function(){
            $('.dropdown-box').slideToggle(300);
        });

        var user_id=$('#top_id').val();
        $('.close-all').click(function(){
            $('.top-banner').remove();
            $('.navbar-fixed-top').removeClass("top-banner-show");
            $('.page-container').removeClass("top-banner-show");

              $.ajax({
          url: '/review/close',
          method: "POST",
          async:false,
          data: { user_id : user_id},
          success:function(html)
              {
           //alert(html);  
             
             }
           });

        });

        $('.dismiss').click(function(){
            $('.top-banner').remove();
            $('.navbar-fixed-top').removeClass("top-banner-show");
            $('.page-container').removeClass("top-banner-show");

             $.ajax({
          url: '/review/dismiss',
          method: "POST",
          async:false,
          data: { user_id : user_id},
          success:function(html)
              {
           //alert(html);  
             
             }
           });

        });

        $('#click_rate').click(function(){
            //$('a .connect_btn').trigger("click");
            var href = $('.connect_btn').attr('href');
            window.location.href = href; 
        });
    });
</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white" data-ccprompt="{{ ccInfoRequired }}" data-paymentprovider="{{ paymentService }}">

 <a href="/location/edit/<?php echo $top_location_id?>/0/0" class="connect_btn" style="display:none;">click here to connect</a>

<input type="hidden" id="top_id" value="<?php echo $this->session->get('auth-identity')['id'];?>">
<?php 
  //echo $this->session->get('auth-identity')['id'];exit;
  //echo $top_banner;exit;
  //echo $top_banner_session;exit;
if($agencytype=='business' && (!$facebookMyBusinessConnected ||  !$GoogleMyBusinessConnected || !$YelpMyBusinessConnected ) && $top_banner!=1 && ($top_banner_session!=2)){

 if(!$facebookMyBusinessConnected &&  !$GoogleMyBusinessConnected && !$YelpMyBusinessConnected )
{ ?>
<div class="top-banner">you must connect your Google,Facebook,Yelp account to monitor these review sites <a   id="click_rate">click here to connect</a>
  <span class="close-div">X</span>
    <div class="dropdown-box">
        <ul>
            <li><a href="#" class="close-all">Close</a></li>
            <li><a href="#" class="dismiss">Dismiss</a></li>
        </ul>
    </div>
</div>
<?php } else { 

$conn_account='';
    if(!$GoogleMyBusinessConnected)
    {
    $conn_account.="Google,";
    }
    if(!$facebookMyBusinessConnected)
    {
    $conn_account.="Facebook,";
    }
    if(!$YelpMyBusinessConnected)
    {
    $conn_account.="Yelp,";
    }
    $conn_account=rtrim($conn_account,",");

?>
<div class="top-banner">
    you must connect your <?php echo $conn_account;?> account to monitor these review sites 
    <a id="click_rate">click here to connect</a>
    <span class="close-div">X</span>
    <div class="dropdown-box">
        <ul>
            <li><a href="#" class="close-all">Close</a></li>
            <li><a href="#" class="dismiss">Dismiss</a></li>
        </ul>
    </div>
</div>  

<?php } }?>
<!-- Top banner ends -->
{% if BusinessDisableBecauseOfStripe AND 1 == 2 %}
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">There seems to be a problem with the stripe configuration.  Please contact customer support.</div>
        </div>
    </div>
    <?php die(); ?>
{% endif %}
<input type="hidden" id="primary_color" value="{{ primary_color }}" />
<input type="hidden" id="secondary_color" value="{{ secondary_color}}" />
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top <?php if($agencytype=='business' && (!$facebookMyBusinessConnected ||  !$GoogleMyBusinessConnected || !$YelpMyBusinessConnected ) && $top_banner!=1 && ($top_banner_session!=2)){ ?>top-banner-show <?php } ?>">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo" style="margin-top: 0;">
            <a href="/">
            {% if logo_path %}
                 {% if agencytype == "agency" %}
                <img src="<?=$this->view->logo_path;?>" alt="logo" 
                 style="max-width: 200px;" class="logo-default"/>

                {% else %}

                <img src="<?=$this->view->logo_path;?>" alt="logo" 
                style="max-width: 200px;" class="logo-default not_agency"/>
                {% endif %}
            {% endif %}
               
            </a>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                {% if not is_admin and agencytype != 'agency' AND NOT NonTrial %}

                    {% if ReachedMaxSMS %}
                    <li class="" id="">
                        <a href="" onclick="$('#IncreaseSMS').modal('show');" class=""><img src="/img/btn_send_review_invite.png" alt="Send Review Invite"/></a>
                    </li>
                    {% else %}
                    <li class="" id="">
                        <a href="#sendreviewinvite" class="fancybox"><img src="/img/btn_send_review_invite.png" alt="Send Review Invite"/></a>
                    </li>
                    {% endif %}
                {% endif %}
                {% if location_id %}
                    {% if locations %}
                        <li class="location-header" id="">
                           {% if not is_admin and agencytype != 'agency' %}
                        <span id="locationset">
                            Location: {{ location.name }}
                            {% if locations|length > '1' %}
                                <a href="#" onclick="$('#locationset').hide();$('#locationnotset').show();return false;">Change</a>
                            {% endif %}
                        </span>
                        {% endif %}
                        <span id="locationnotset" style="display: none;"><form action="/" method="post">
                                Location:
                                <select name="locationselect" id="locationselect">
                                    {% if locations|length > '1' %}
                                        {% for loc in locations %}
                                            <option value='{{ loc.location_id }}'>{{ loc.name }}</option>
                                        {% endfor %}
                                    {% endif %}
                                </select>
                                <input type="submit" class="btn red" value="Change"></form>
                        </span>
                        </li>
                    {% endif %}
                {% endif %}
                {% if agencytype == "agency" %}
                <li class="dropdown dropdown-user" style="margin-left: 30px;">
                <a href="/notification/allnotification/{{NumberAgency}}" class="dropdown-toggle">
                        <span class="username username-hide-on-mobile" style="color: #484848;"><i class="icon-bell"></i> ( {{ NumberOfNotification }} )</span>

                    </a>
                </li>
                {% endif %}
                <li class="dropdown dropdown-user" style="margin-left: 20px;">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-on-mobile" style="color: #484848;"><i class="icon-user"></i> {{ name }} </span>
                        <i class="fa fa-angle-down" style="color: #484848;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <?php if($objAgency->parent_id > 0 || $objAgency->parent_id == -1) { ?>
                        <li>
                            <a href="/users/adminedit/<?=$loggedUser->id ?>">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="/session/logout">
                                <i class="icon-key"></i>
                                <span class="title">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container <?php if($agencytype=='business' && (!$facebookMyBusinessConnected ||  !$GoogleMyBusinessConnected || !$YelpMyBusinessConnected ) && $top_banner!=1 && ($top_banner_session!=2)){ ?>top-banner-show <?php } ?>">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper hide">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler"></div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>
                {% if is_admin %}
                    <li class="nav-item start">
                        <a href="/admindashboard" class="nav-link nav-toggle">
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
                    {% if internalNavParams['hasPricingPlans'] %}
                        <li class="nav-item">
                            <a href='/businessPricingPlan' class="nav-link nav-toggle">
                                <i class="icon-list"></i>
                                <span class="title">Business Subscriptions</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    {% endif %}
                    <li class="nav-item start">
                        <a href="/admindashboard/settings" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Settings</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                {% else %}     
                    {% if agencytype == "agency" %}
                        <li class="nav-item start">
                            <a href="/agency" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Manage Businesses</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                         <li class="nav-item start">
                            <a href="/agency/customnumber" class="nav-link nav-toggle">
                                <i class="fa fa-globe"></i>
                                <span class="title">Custom SMS Number</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    {% elseif agencytype == "business" %}
                        <li class="nav-item start">
                            <a href="/" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    {% else %}
                        <li class="nav-item start">
                            <a href="/admindashboard" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    {% endif %}

                    {% if location_id %}
                        {% if agencytype != "agency" %}

                        <?php if($facebookMyBusinessConnected ||  $GoogleMyBusinessConnected || $YelpMyBusinessConnected){ ?>
                            <li class="nav-item">
                                <a href="/reviews" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">Reviews</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a href="/analytics" class="nav-link nav-toggle">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Analytics</span>
                                    <span class="selected"></span>
                                </a>
                            </li>

                            {% if is_business_admin or (profile == "User") %}
                                <?php if(strpos($userLogged->role, "Admin") !== false || !$this->view->is_employee) { ?>
                               {% if is_business_admin %} 
                                <li class="nav-item">
                                    <a href="/reviews/sms_broadcast" class="nav-link nav-toggle">
                                        <i class="icon-envelope"></i>
                                        <span class="title">SMS Broadcast</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                {% endif %}
                                <?php } ?>
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
                            {% endif %}
                        {% endif %}
                    {% endif %}
                        <?php         
                    if(strpos($userLogged->role, "Admin") !== false || !$userLogged->is_employee) { ?>
                    {% if profile == "Business Admin" and agencytype == "business" %}
                        <li class="nav-item">
                            <a href="/location/" class="nav-link nav-toggle">
                                <i class="icon-pointer"></i>
                                <span class="title">Locations</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        
                    {% endif %}
                    <?php } ?>
                    {% if profile != "User" %}
                        {% if agencytype == "agency" %}
                            {% set SettingsLocation = "agency" %}
                        {% else %}
                            {% set SettingsLocation = "location" %}
                        {% endif %}
                        <?php 
                         
                        if(strpos($userLogged->role, "Admin") !== false || !$userLogged->is_employee) { ?>
                        <li class="nav-item">
                            <a href="/settings/{{ SettingsLocation }}/" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Settings</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <?php } ?>

                        <?php if(strpos($userLogged->role, "Admin") !== false || !$userLogged->is_employee) { ?>
                        {% if agencytype != "agency" %}
                        <li class="nav-item">
                            <a href="/users/admin" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">Users</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        {% endif %}

                        {% if internalNavParams['hasSubscriptions'] AND SubscriptionLevel != "FR" %}
                            <li class="nav-item">
                                <a href="{{ internalNavParams['subscriptionController'] }}" class="nav-link nav-toggle">
                                    <i class="icon-wallet"></i>
                                    <span class="title">Subscriptions</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        {% endif %}
                        <?php } ?>
                    {% endif %}
                {% endif %}
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
            {{ flashSession.output() }}
            {{ content() }}
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> {{ date("Y") }} &copy; <?=(isset($this->view->agencyName))?$this->view->agencyName: "Get Mobile Reviews";?></div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<!-- include needed javascript from partial -->
{% include "partials/layouts/private-js.volt" %}

<!-- add required js from controller -->
{{ assets.outputJs() }}
{% if agencytype == "agency" %}
    <div class="modal fade" id="updateStripeModal" tabindex="-1" role="dialog" aria-labelledby="updateStripeModalLabel">
        <div class="credit-card-details modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="growth-bar">
                        <div>Update Stripe Information</div>
                    </div>
                </div>
                <div class="modal-body center-block">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="/settings/agency?tab=Stripe" style="text-decoration:none;" ><button type="button" class="btn btn-warning btn-lg center-block">Click here to update Stripe information</button></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" style="margin-top: 30px;">
                            <a onclick="DismissStripe();" style="text-decoration:none;" ><button type="button" class="btn btn-link btn-lg center-block">Dismiss message</button></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
{% endif %}
    {% if not is_admin and agencytype != "agency" %}
         <div class="modal fade" id="IncreaseSMS" tabindex="-1" role="dialog" aria-labelledby="increaseSMSModalLabel">
        <div class="credit-card-details modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="growth-bar">
                        <div>Increase SMS</div>
                    </div>
                </div>
                <div class="modal-body center-block">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="/businessSubscription" style="text-decoration:none;" ><button type="button" class="btn btn-warning btn-lg center-block">Click here to increase your sms amount</button></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
        {% if location_id %}
            <div id="sendreviewinvite" style="width:400px; display: none; color: #7A7A7A;">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light">
                    {% if not NonTrial %}
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject" style="text-align: left; text-transform: none; font-weight: normal; font-size: 27px !important;"> Send Review Invite </span>
                        </div>
                    </div>
                    {% endif %}
                    <div class="portlet-body form">
                        {%
                            if (twilio_auth_token != ''
                            and twilio_from_phone != '')
                        %}


                            {% if num_signed_up %}
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12">
                                        <i>You are entitled to {{ total_sms_month }} SMS messages per month. You have
                                            sent {{ sms_sent_this_month_total }} total SMS messages this month.</i>
                                    </div>
                                </div>
                            {% endif %}
                            {% if sms_sent_this_month < total_sms_month %}
                                <form class="form-horizontal" action="/location/send_review_invite" role="form" method="post" autocomplete="off" id="smsrequestform">
                                    <div class="success" id="smsrequestformsuccess" style="display: none;">The review
                                        invite was sent.
                                    </div>
                                    <div class="error" id="smsrequestformerror" style="display: none;">There was a
                                        problem sending the review invite.
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-md-3 control-label" style="text-align: left;">Location</label>
                                            <div class="col-md-9" style="margin-top: 8px; margin-bottom: 8px;">
                                                {{ location.name }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                               <!--  <input class="form-control placeholder-no-fix" type="text" placeholder="Name" name="name" id="smsrequestformname" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>"
                                                /> -->

                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Name" name="name" id="smsrequestformname" value=""
                                                />
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
                                                <!--<input class="form-control placeholder-no-fix" type="text" placeholder="Phone" name="phone" id="smsrequestformphone" value="<?=(isset($_POST['phone'])? $_POST["phone"]:'')?>"
                                                />-->

                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Mobile" name="phone" id="smsrequestformphone" value=""
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <i>The Mobile number that will receive the SMS message.</i>
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
                                                <textarea	style="width: 100%;" class="form-control placeholder-no-fix" name="SMS_message">{% if location.SMS_message %}{{location.SMS_message}}{% else %}Hi {name}, thanks for visiting {location-name} we'd really appreciate your feedback by clicking the following link {link}. Thanks! {% endif %}</textarea>
                                                <i>{location-name} will be the name of the location sending the SMS,
                                                    {name} will be replaced with the name entered when sending the
                                                    message and {link} will be the link to the review.</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-8">
                                            {{ submit_button("Send", "class": "btnLink", "style": "float: right; height: 50px; padding: 10px 35px;") }}
                                        </div>
                                    </div>
                                </form>
                            {% else %}
                                You have no more SMS messages to send this month.
                            {% endif %}
                        {% else %}
                            Your text messaging service isn’t currently set up. Please contact support to have your text messaging service setup for your account.
                            <!--You must have a Twilio SID and Auth Token to send SMS messages.  All SMS messages are sent using
                            <a href="https://www.twilio.com/" target="_blank">Twilio</a>.  <a href="/settings/">Click
                            here</a> to enter your Twilio SID and Auth Key now. If you don't have an API key yet,
                            <a href="https://www.twilio.com/try-twilio" target="_blank">click here</a> to sign up.-->
                        {% endif %}
                    </div>
                </div>
            </div>
            <div class="modal fade" id="updateCardModal" tabindex="-1" role="dialog" aria-labelledby="updateCardModalLabel">
                <div class="credit-card-details modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="growth-bar">
                                <div>Update Card</div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="card-js" data-capture-name="true" data-icon-colour="#65CE4D"></div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="button" id="confirm-update-credit-card" class="btn default apple-backgound subscription-btn">
                                    Update
                                </button>
                                {% if ccInfoRequired == "closed" %}
                                    <button type="button" id="cancel-update-credit-card" class="btn default apple-backgound subscription-btn" data-dismiss="modal">
                                        Cancel
                                    </button>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
             if (strpos($_SERVER['REQUEST_URI'],'link/createlink')==0) {?>
            <script src="https://checkout.stripe.com/checkout.js"></script>
            <?php }?>

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    function getCCParams() {
                        var cardNumber = $('#updateCardModal .card-number').val();
                        var cardName = $('#updateCardModal .name').val();
                        var expirationDate = $('#updateCardModal .expiry').val();
                        var csv = $('#updateCardModal .cvc').val();
                        return {
                            cardNumber: cardNumber,
                            cardName: cardName,
                            expirationDate: expirationDate,
                            csv: csv
                        };
                    };

                    function updateCard() {
                        $.post('/businessSubscription/updatePaymentProfile', getCCParams())
                                .done(function (data) {
                                    if (data.status !== true) {
                                        alert("Update card failed!!!")
                                    } else {
                                        $('#updateCardModal').modal('hide');
                                    }
                                })
                                .fail(function () {
                                })
                                .always(function () {
                                });
                    }

                    function updateStripeCard(token) {
                        $.post('/businessSubscription/updatePaymentProfile', {
                            tokenID: token.id,
                            email: token.emai1
                        })
                            .done(function (data) {
                                /*if (data.status !== true) {
                                    alert("Update card failed!!!")
                                }*/
                                window.location = "/businessSubscription";
                            })
                            .fail(function () {
                            })
                            .always(function () {
                            });
                    }

                    $('.fancybox').fancybox();

                    var bodyElem = document.getElementsByTagName("body")[0];
                    if (bodyElem.dataset.ccprompt === "open") {
                        if(bodyElem.dataset.paymentprovider === "AuthorizeDotNet")
                            $('#updateCardModal').modal('show');
                        else if(bodyElem.dataset.paymentprovider === "Stripe") {
                            var handler = StripeCheckout.configure({
                                    key: '{{ stripePublishableKey }}',
                                    email: '{{ businessEmail }}',
                                    allowRememberMe: false,
                                    /* GARY_TODO: Replace with agency logo */
                                    /*image: '/img/documentation/checkout/marketplace.png',*/
                                    locale: 'auto',
                                    token: function(token) {
                                    // You can access the token ID with `token.id`.
                                    // Get the token ID to your server-side code for use.
                                    updateStripeCard(token);
                                }
                            });

                            // Open Checkout with further options:
                            handler.open({
                                name: 'Update Payment Info',
                                description: '',
                            });

                            // Close Checkout on page navigation:
                            $(window).on('popstate', function() {
                                handler.close();
                            });
                        }
                    }

                    $('#confirm-update-credit-card').click(function () {
                        updateCard();
                    });

                    //callback handler for form submit
                    $("#smsrequestform").submit(function (e) {
                        var postData = $(this).serializeArray();
                        var formURL = $(this).attr("action");
                        $.ajax(
                                {
                                    url: formURL,
                                    type: "POST",
                                    data: postData,
                                    success: function (data, textStatus, jqXHR) {

                                        $('#smsrequestformname').val('');

                                        $('#smsrequestformphone').val('');
                                        $('#smsrequestformerror').html(data);
                                        $('#smsrequestformsuccess').hide();
                                        $('#smsrequestformerror').show();

                                        setTimeout(function(){
                                        $('.fancybox-close').click();
                                             $('#smsrequestformsuccess').html('');
                                              $('#smsrequestformerror').html('');
                                            }, 2000);
                                       
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        //if fails
                                        $('#smsrequestformsuccess').hide();
                                        $('#smsrequestformerror').show();

                                        setTimeout(function(){
                                        $('.fancybox-close').click();
                                        $('#smsrequestformsuccess').html('');
                                              $('#smsrequestformerror').html('');
                                            }, 2000);
                                    }
                                });
                        e.preventDefault(); //STOP default action
                    });
                });
            </script>
        {% endif %}
    {% endif %}

{% if agencytype == "agency" AND AgencyInvalidStripe AND ShowAgencyStripePopup %}
    <script>
        $(function(){
               // $('#updateStripeModal').modal('show');
        });
    </script>
{% endif %}
<script type="text/javascript">
    $(window).resize(function(){
        if(console) console.warn('We are hiding the chart here for now, eventually we want to re-paint it');
        $('#piechart > div > div').hide();

    });

    function DismissStripe() {
        $.ajax({
            url: '/settings/dismissstripe'
        }).done(function() {
            $('#updateStripeModal').modal('hide');
        });
    }
</script>
<?php

if($_SERVER['HTTP_HOST']=="getmobilereviews.com"){
   if($objAgency->parent_id > 0) {

        $IntercomAPIID = $objParentAgency->intercom_api_id && $objParentAgency->intercom_security_hash ? $objParentAgency->intercom_api_id : '';

        $IntercomSecurityHash = $objParentAgency->intercom_api_id && $objParentAgency->intercom_security_hash ? $objParentAgency->intercom_security_hash : '';

    } elseif($objAgency->parent_id <= 0 || $loggedUser->is_admin) {

        $IntercomAPIID = "c8xufrxd";

        $IntercomSecurityHash = "g0NfX42bvMsg4jf86dI1Q1TwImVss6Mugy-hcvac";

    }

    if($IntercomAPIID && $IntercomSecurityHash) { ?>

    <script>

      window.intercomSettings = {

        app_id: "<?=$IntercomAPIID; ?>",

        name: "<?php echo $loggedUser->name ?>", // Full name

        email: "<?php echo $loggedUser->email ?>", // Email address

        <?php

          if ($loggedUser->create_time) { ?>

          create_time: <?php echo strtotime($loggedUser->create_time) ?>, // Signup date as a Unix timestamp

        <?php } ?>

        user_hash: "<?php echo hash_hmac('sha256',$loggedUser->email,$IntercomSecurityHash );?>"

      };

    </script>

    <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/c8xufrxd';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<?php }} ?>






</body>
</html>
