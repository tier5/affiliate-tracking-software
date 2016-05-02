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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/admin/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/admin/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <link rel="stylesheet" href="/admin/js/vendor/minicolors/jquery.minicolors.css" />
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/admin/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="/admin/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link href="/admin/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/admin/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
    <?php 
    if(strpos($_SERVER['REQUEST_URI'],'location/create')>0) {
      ?>
      <link href="/admin/css/signup.css" rel="stylesheet" type="text/css" />
      <?php 
    }
    ?>
    <link href="/admin/css/admin.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="favicon.ico" /> 
    <!--<script src="/admin/assets/global/plugins/jquery.min.js" type="text/javascript"></script>-->    
    <script type="text/javascript" src="/admin/js/vendor/jquery-2.1.1.min.js"></script>
    <link rel="stylesheet" href="/admin/js/vendor/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="/admin/js/vendor/fancybox/jquery.fancybox.pack.js"></script>    
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <?php
    if (isset($main_color_setting)) {
      list($r, $g, $b) = sscanf($main_color_setting, "#%02x%02x%02x");
      //echo "$main_color_setting -> $r $g $b";
      ?>
<style>
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
</style>
      <?php
    }
    ?>
 </head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="/admin/">
                    <img src="<?=(isset($logo_setting) && $logo_setting != ''?$logo_setting:'/admin/assets/layouts/layout/img/logo.png')?>" alt="logo" class="logo-default" /> </a>
                <div class="menu-toggler sidebar-toggler"> </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                        <?php 
                 if (isset($haspaid) && $haspaid == false) {
                 
                 } else {       
                    if ((isset($this->session->get('auth-identity')['is_admin']) && $this->session->get('auth-identity')['is_admin'] > 0)
                        || (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency') ) {

                    } else {
                      if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) {
                      ?>
                      <li class="" id="">
                          <a href="/admin/location/send_review_invite" class=""><img src="/admin/img/btn_send_review_invite.png" alt="Send Review Invite" /></a>
                      </li>
                      <?php } 

                        if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) {
                           //get location list from the identity
                           $loclist = $this->session->get('auth-identity')['locations'];

                           if ($loclist) {
                            ?>
                            <li class="location-header" id="">
                            <span id="locationset">
                            Location: <?=$this->session->get('auth-identity')['location_name']?>  <?php if (count($loclist) > 1) { ?><a href="#" onclick="$('#locationset').hide();$('#locationnotset').show();return false;">Change</a><?php } ?>
                            </span>
                            <span id="locationnotset" style="display: none;"><form action="/admin/" method="post">
                            Location: <select name="locationselect" id="locationselect">
                            <?php 
                            if (count($loclist) > 1) {
                              foreach ($loclist as $loc) {
                                echo "<option value='$loc->location_id' ".($loc->location_id==$this->session->get('auth-identity')['location_id']?' selected="selected"':'').">$loc->name</option>\n";
                              }
                            }
                            ?>
                            </select>
                            <input type="submit" class="btn red" value="Change"></form>
                            </span>
                            </li>
                            <?php 
                          }
                        }
                        ?>
                    <?php 
                  } //end looking for admin  
                  ?>                  
                  <li class="dropdown dropdown-user" style="margin-left: 20px;">
                      <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                          <span class="username username-hide-on-mobile" style="color: #32c5d2;"><i class="icon-user"></i> <?=$this->session->get('auth-identity')['name']?> </span>
                          <i class="fa fa-angle-down" style="color: #32c5d2;"></i>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-default">
                          <li>
                              <a href="#">
                                  <i class="icon-user"></i> My Profile </a>
                          </li>
                          <li>
                              <a href="/admin/session/logout">
                                <i class="icon-key"></i>
                                <span class="title">Log Out</span>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <?php
                }  //end checking if paid
                    ?>
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
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <li class="sidebar-toggler-wrapper hide">
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                        <div class="sidebar-toggler"> </div>
                        <!-- END SIDEBAR TOGGLER BUTTON -->
                    </li>
                    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element 
                    <li class="sidebar-search-wrapper">-->
                        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box 
                        <form class="sidebar-search  " action="page_general_search_3.html" method="POST">
                            <a href="javascript:;" class="remove">
                                <i class="icon-close"></i>
                            </a>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <a href="javascript:;" class="btn submit">
                                        <i class="icon-magnifier"></i>
                                    </a>
                                </span>
                            </div>
                        </form>-->
                        <!-- END RESPONSIVE QUICK SEARCH FORM 
                    </li>-->
                    <?php
                    $openfolder = '';
                    if(strpos($_SERVER['REQUEST_URI'],'analytics')>0)
                    {
                      $openfolder = 'analytics';
                    } 
                    else if(strpos($_SERVER['REQUEST_URI'],'subscription')>0)
                    {
                      $openfolder = 'subscription';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'stripe')>0)
                    {
                      $openfolder = 'stripe';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'reviews/sms_broadcast')>0)
                    {
                      $openfolder = 'sms_broadcast';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'reviews')>0)
                    {
                      $openfolder = 'reviews';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'users/admin')>0)
                    {
                      $openfolder = 'adminusers';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'users')>0)
                    {
                      $openfolder = 'users';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'settings')>0)
                    {
                      $openfolder = 'settings';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'contacts')>0)
                    {
                      $openfolder = 'contacts';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'location')>0)
                    {
                      $openfolder = 'location';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'admindashboard/list/2')>0 || 
                            strpos($_SERVER['REQUEST_URI'],'admindashboard/create/2')>0 || 
                            strpos($_SERVER['REQUEST_URI'],'admindashboard/view/2')>0)
                    {
                      $openfolder = 'admindashboardbusinesses';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'admindashboard/list/1')>0 || 
                            strpos($_SERVER['REQUEST_URI'],'admindashboard/create/1')>0 || 
                            strpos($_SERVER['REQUEST_URI'],'admindashboard/view/1')>0)
                    {
                      $openfolder = 'admindashboardagencies';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'admindashboard/payments')>0)
                    {
                      $openfolder = 'admindashboardpayments';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'admindashboard/settings')>0)
                    {
                      $openfolder = 'admindashboardsettings';
                    }
                    else if(strpos($_SERVER['REQUEST_URI'],'admindashboard')>0)
                    {
                      $openfolder = 'admindashboard';
                    }
                    else 
                    {
                      $openfolder = 'dashboard';
                    }
                    ?>
                    <?php 
                  if (isset($haspaid) && $haspaid == false) {
                    //the user has not paid
                  } else {

                    if (isset($this->session->get('auth-identity')['is_admin']) && $this->session->get('auth-identity')['is_admin'] > 0) {
                      ?>
                      <li class="nav-item start <?=($openfolder=='admindashboard'?'active open':'')?>">
                          <a href="/admin/admindashboard/" class="nav-link nav-toggle">
                              <i class="icon-home"></i>
                              <span class="title">Dashboard</span>
                              <?=($openfolder=='admindashboard'?'<span class="selected"></span>':'')?>
                          </a>
                      </li>
                      <li class="nav-item start <?=($openfolder=='admindashboardbusinesses'?'active open':'')?>">
                          <a href="/admin/admindashboard/list/2" class="nav-link nav-toggle">
                              <i class="icon-pointer"></i>
                              <span class="title">See All Businesses</span>
                              <?=($openfolder=='admindashboardbusinesses'?'<span class="selected"></span>':'')?>
                          </a>
                      </li>
                      <li class="nav-item start <?=($openfolder=='admindashboardagencies'?'active open':'')?>">
                          <a href="/admin/admindashboard/list/1" class="nav-link nav-toggle">
                              <i class="icon-pointer"></i>
                              <span class="title">See All Agencies</span>
                              <?=($openfolder=='admindashboardagencies'?'<span class="selected"></span>':'')?>
                          </a>
                      </li>
                      <li class="nav-item start <?=($openfolder=='settings'?'active open':'')?>">
                          <a href="/admin/admindashboard/settings" class="nav-link nav-toggle">
                              <i class="icon-settings"></i>
                              <span class="title">Settings</span>
                              <?=($openfolder=='settings'?'<span class="selected"></span>':'')?>
                          </a>
                      </li>
                      <li class="nav-item <?=($openfolder=='subscription'?'active open':'')?>">
                        <a href="/admin/subscription/" class="nav-link nav-toggle">
                          <i class="icon-wallet"></i>
                          <span class="title">Subscriptions</span>
                          <?=($openfolder=='subscription'?'<span class="selected"></span>':'')?>
                        </a>
                       </li>
                      
                      <?php 
                    } else {


                      if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency') {
                        ?>
                        <li class="nav-item start <?=($openfolder=='dashboard'?'active open':'')?>">
                          <a href="/admin/agency" class="nav-link nav-toggle">
                              <i class="icon-home"></i>
                              <span class="title">Manage Businesses</span>
                              <?=($openfolder=='dashboard'?'<span class="selected"></span>':'')?>
                          </a>
                        </li>
                        <?php 
                      } else {
                        ?>
                      <li class="nav-item start <?=($openfolder=='dashboard'?'active open':'')?>">
                          <a href="/admin/" class="nav-link nav-toggle">
                              <i class="icon-home"></i>
                              <span class="title">Dashboard</span>
                              <?=($openfolder=='dashboard'?'<span class="selected"></span>':'')?>
                          </a>
                      </li>
                      <?php 
                        
                      }

                      if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) {
                      ?>

                  <?php 
                  if (!(isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency')) {
                  ?>
                    
                    <li class="nav-item <?=($openfolder=='reviews'?'active open':'')?>">
                      <a href="/admin/reviews/" class="nav-link nav-toggle">
                          <i class="icon-diamond"></i>
                          <span class="title">Reviews</span>
                          <?=($openfolder=='reviews'?'<span class="selected"></span>':'')?>
                      </a>
                    </li>
                    <li class="nav-item <?=($openfolder=='analytics'?'active open':'')?>">
                      <a href="/admin/analytics/" class="nav-link nav-toggle">
                        <i class="icon-bar-chart"></i>
                        <span class="title">Analytics</span>
                        <?=($openfolder=='analytics'?'<span class="selected"></span>':'')?>
                      </a>
                      </li>
                    <?php

                      //only the business Admin gets access to locations
                      if (strpos($this->session->get('auth-identity')['profile'], 'Admin') > 0) {
                      ?>
                        <li class="nav-item <?=($openfolder=='sms_broadcast'?'active open':'')?>">
                          <a href="/admin/reviews/sms_broadcast" class="nav-link nav-toggle">
                              <i class="icon-envelope"></i>
                              <span class="title">SMS Broadcast</span>
                              <?=($openfolder=='sms_broadcast'?'<span class="selected"></span>':'')?>
                          </a>
                        </li>
                        <li class="nav-item <?=($openfolder=='contacts'?'active open':'')?>">
                          <a href="/admin/contacts" class="nav-link nav-toggle">
                              <i class="icon-users"></i>
                              <span class="title">Contacts</span>
                              <?=($openfolder=='contacts'?'<span class="selected"></span>':'')?>
                          </a>
                        </li>
                      <?php 
                      }
                      //only the Agency Admin gets access to employees
                      if (strpos($this->session->get('auth-identity')['profile'], 'Admin') > 0) {
                      ?>
                      <li class="nav-item <?=($openfolder=='users'?'active open':'')?>">
                        <a href="/admin/users/" class="nav-link nav-toggle">
                            <i class="icon-user"></i>
                            <span class="title">Employees</span>
                            <?=($openfolder=='users'?'<span class="selected"></span>':'')?>
                        </a>
                      </li>
                      <?php 
                      }
                    
                    }
                    ?>
                    <?php
                    ?>
                    <?php 
                    }
                    ?>
                    <?php
                    //only the Business Admin gets access to locations
                    if ($this->session->get('auth-identity')['profile'] == 'Agency Admin' &&
                        (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business')) {
                    ?>
                    <li class="nav-item <?=($openfolder=='location'?'active open':'')?>">
                      <a href="/admin/location/" class="nav-link nav-toggle">
                          <i class="icon-pointer"></i>
                          <span class="title">Locations</span>
                          <?=($openfolder=='location'?'<span class="selected"></span>':'')?>
                      </a>
                    </li>
                    <?php 
                    }
                    ?>
                    <?php 
                    //if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) {
                    ?>
                      <?php
                      //only the Agency Admin gets access to locations
                      if ($this->session->get('auth-identity')['profile'] != 'Employee' &&
                          !(isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business')) {
                      ?>
                    <li class="nav-item <?=($openfolder=='stripe'?'active open':'')?>">
                    <a href="/admin/stripe/" class="nav-link nav-toggle">
                      <i class="icon-credit-card"></i>
                      <span class="title">Subscriptions</span>
                      <?=($openfolder=='stripe'?'<span class="selected"></span>':'')?>
                    </a>
                    </li>               
                      <?php 
                      }
                      //only the Agency Admin gets access to locations
                      if ($this->session->get('auth-identity')['profile'] != 'Employee') {
                      ?> 
                    <li class="nav-item <?=($openfolder=='settings'?'active open':'')?>">
                      <a href="/admin/settings/location/" class="nav-link nav-toggle">
                          <i class="icon-settings"></i>
                          <span class="title">Settings</span>
                          <?=($openfolder=='settings'?'<span class="selected"></span>':'')?>
                      </a>
                    </li>  
                    <li class="nav-item <?=($openfolder=='adminusers'?'active open':'')?>">
                      <a href="/admin/users/admin/" class="nav-link nav-toggle">
                        <i class="icon-user"></i>
                        <span class="title">Admin Users</span>
                        <?=($openfolder=='adminusers'?'<span class="selected"></span>':'')?>
                      </a>
                    </li>
                      <?php 
                      }
                      ?>
                    <?php 
                   // }
                  } //end checking for super admin
                } // end checking if the user has paid
                  ?>
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
              {{ content() }}
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner"> {{ date("Y") }} &copy; Review Velocity </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
    <!--[if lt IE 9]>
<script src="/admin/assets/global/plugins/respond.min.js"></script>
<script src="/admin/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/admin/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
    <script src="/admin/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="/admin/js/vendor/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/admin/js/vendor/dataTables.tableTools.js"></script>
    <script type="text/javascript" src="/admin/js/vendor/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="/admin/js/vendor/dataTables.responsive.js"></script>
    <script type="text/javascript" src="/admin/js/vendor/tables-data.js"></script>
    <script type="text/javascript" src="/admin/js/vendor/buttons.js"></script>
    <script src="/admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/admin/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/admin/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/admin/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/admin/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/admin/assets/pages/scripts/dashboard.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/admin/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/admin/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
    <script src="/admin/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <script type="text/javascript" src="/admin/js/vendor/minicolors/jquery.minicolors.js"></script>
    <script src="/admin/js/main.js" type="text/javascript"></script>
    <script src="/admin/js/star-rating.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <?php if ($openfolder=='location') { ?>
    <?php } ?>
    <script src="/admin/js/card-js.min.js"></script>
    <script src="/admin/js/login.js"></script>
</body>

</html>