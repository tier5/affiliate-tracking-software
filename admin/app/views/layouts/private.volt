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

    <link rel="shortcut icon" href="favicon.ico"/>
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
                padding-top:75px;n
            }
        </style>
    {% endif %}
    <style type="text/css">
    </style>
    <link rel="stylesheet" href="/dashboard/css?primary_color={{ primary_color }}&secondary_color={{ secondary_color }}">
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white" data-ccprompt="{{ ccInfoRequired }}" data-paymentprovider="{{ paymentService }}">
{% if BusinessDisableBecauseOfStripe AND 1 == 2 %}
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">There seems to be a problem with the stripe configuration.  Please contact customer support.</div>
        </div>
    </div>
    <?php die(); ?>
{% endif %}
<input type="hidden" id="primary_color" value="#{{primary_color }}" />
<input type="hidden" id="secondary_color" value="#{{secondary_color}}" />
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo" style="margin-top: 0;">
            <a href="/">
                <img src="<?=(isset($logo_setting) && $logo_setting != ''?$logo_setting:'/assets/layouts/layout/img/logo.png')?>" alt="logo" class="logo-default"/>
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
                {% if haspaid %}
                    {% if not is_admin and agencytype != 'agency' %}
                        <li class="" id="">
                            <a href="#sendreviewinvite" class="fancybox"><img src="/img/btn_send_review_invite.png" alt="Send Review Invite"/></a>
                        </li>
                    {% endif %}
                    {% if location_id %}
                        {% if locations %}
                            <li class="location-header" id="">
                            <span id="locationset">
                                Location: {{ location.name }}
                                {% if locations|length > '1' %}
                                    <a href="#" onclick="$('#locationset').hide();$('#locationnotset').show();return false;">Change</a>
                                {% endif %}
                            </span>
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
                    <li class="dropdown dropdown-user" style="margin-left: 20px;">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="username username-hide-on-mobile" style="color: #484848;"><i class="icon-user"></i> {{ name }} </span>
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
                {% endif %}
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
                    <div class="sidebar-toggler"></div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>

                {% if haspaid %}
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
                                <li class="nav-item">
                                    <a href="/reviews" class="nav-link nav-toggle">
                                        <i class="icon-diamond"></i>
                                        <span class="title">Reviews</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/analytics" class="nav-link nav-toggle">
                                        <i class="icon-bar-chart"></i>
                                        <span class="title">Analytics</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                {% if is_business_admin %}
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
                                    <li class="nav-item">
                                        <a href="/stripe/updatePayment" class="nav-link nav-toggle">
                                            <i class="icon-credit-card"></i>
                                            <span class="title">Update Payment Info</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                {% endif %}
                            {% endif %}
                        {% endif %}
                        {% if profile == "Agency Admin" and agencytype == "business" %}
                            <li class="nav-item">
                                <a href="/location/" class="nav-link nav-toggle">
                                    <i class="icon-pointer"></i>
                                    <span class="title">Locations</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        {% endif %}
                        {% if profile != "Employee" %}
                            {% if agencytype == "agency" %}
                                {% set SettingsLocation = "agency" %}
                            {% else %}
                                {% set SettingsLocation = "location" %}
                            {% endif %}
                            <li class="nav-item">
                                <a href="/settings/{{ SettingsLocation }}/" class="nav-link nav-toggle">
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

                            {% if internalNavParams['hasSubscriptions'] %}
                                <li class="nav-item">
                                    <a href="{{ internalNavParams['subscriptionController'] }}" class="nav-link nav-toggle">
                                        <i class="icon-wallet"></i>
                                        <span class="title">Subscriptions</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                            {% endif %}
                        {% endif %}
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
    <div class="page-footer-inner"> {{ date("Y") }} &copy; Review Velocity</div>
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
                    <a href="/settings/agency?tab=Stripe" style="text-decoration:none;" ><button type="button" class="btn btn-warning btn-lg center-block">Click here to update Stripe Information</button></a>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
{% endif %}
{% if haspaid %}
    {% if not is_admin and agencytype != "agency" %}
        {% if location_id %}
            <div id="sendreviewinvite" style="width:400px; display: none; color: #7A7A7A;">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject" style="text-align: left; text-transform: none; font-weight: normal; font-size: 27px !important;"> Send Review Invite </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        {% if (agency.twilio_api_key != ''
                        and agency.twilio_auth_token != ''
                        and (agency.twilio_auth_messaging_sid != '' or agency.twilio_from_phone != '' ))
                        or (agency.parent_agency_id and agency.agency_type_id == '2') %}

                            {% if num_signed_up %}d
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
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Name" name="name" id="smsrequestformname" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>"
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
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Phone" name="phone" id="smsrequestformphone" value="<?=(isset($_POST['phone'])? $_POST["phone"]:'')?>"
                                                />
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
                                                <textarea style="width: 100%;" class="form-control placeholder-no-fix" name="SMS_message"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($location->
                                                    SMS_message)?$location->SMS_message:'{location-name}: Hi {name}, We\'d really appreciate your feedback by clicking the link. Thanks! {link}'))?></textarea>
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
                            You must have a Twilio SID and Auth Token to send SMS messages.  All SMS messages are sent using
                            <a href="https://www.twilio.com/" target="_blank">Twilio</a>.  <a href="/settings/">Click
                            here</a> to enter your Twilio SID and Auth Key now. If you don't have an API key yet,
                            <a href="https://www.twilio.com/try-twilio" target="_blank">click here</a> to sign up.
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

            <script src="https://checkout.stripe.com/checkout.js"></script>

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
                                    console.log(data);
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
                                console.log(data);
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
                                /* TODO: Replace with agency logo */
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
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        //if fails
                                        $('#smsrequestformsuccess').hide();
                                        $('#smsrequestformerror').show();
                                    }
                                });
                        e.preventDefault(); //STOP default action
                    });
                });
            </script>
        {% endif %}
    {% endif %}
{% endif %}
{% if agencytype == "agency" AND AgencyInvalidStripe AND ShowAgencyStripPopup %}
    <script>
        $(function(){
                $('#updateStripeModal').modal('show');
        });
    </script>
{% endif %}
<script type="text/javascript">
    $(window).resize(function(){
        if(console) console.warn('We are hiding the chart here for now, eventually we want to re-paint it');
        $('#piechart > div > div').hide();

    });
</script>
</body>
</html>
