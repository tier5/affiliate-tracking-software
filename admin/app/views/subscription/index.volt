{{ content() }}

<header class="jumbotron subhead" id="reviews">
    <div class="hero-unit">
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <!-- BEGIN PAGE TITLE-->
                <h3 class="page-title"> Subscriptions</h3>
                <!-- END PAGE TITLE-->
            </div>    
            <?php 
            if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
            if ($is_upgrade) {
            $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) * 100, 0, '.', ''):100);
            if ($percent > 100) $percent = 100;
            ?>
            <div class="col-md-7 col-sm-7">
                <div class="sms-chart-wrapper">
                    <div class="title">SMS Messages Sent</div>
                    <div class="bar-wrapper">
                        <div class="bar-background"></div>
                        <div class="bar-filled" style="width: <?=$percent?>%;"></div>
                        <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
                        <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month_total?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>            
                    </div>
                    <div class="end-title"><?=$total_sms_month?><br /><span class="goal">Allowed</span></div>
                </div>
            </div>    
            <?php 
            } else {
            $percent = ($total_sms_needed > 0 ? number_format((float)($sms_sent_this_month / $total_sms_needed) * 100, 0, '.', ''):100);
            if ($percent > 100) $percent = 100;
            ?>
            <div class="col-md-7 col-sm-7">
                <div class="sms-chart-wrapper">
                    <div class="title">SMS Messages Sent</div>
                    <div class="bar-wrapper">
                        <div class="bar-background"></div>
                        <div class="bar-filled" style="width: <?=$percent?>%;"></div>
                        <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
                        <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>            
                    </div>
                    <div class="end-title"><?=$total_sms_needed?><br /><span class="goal">Goal</span></div>
                </div>
            </div>    
            <?php 
            }
            } //end checking for business vs agency
            ?>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="growth-bar transparent responsive-float"> 
                    <button type="button" class="btn default btn-lg apple subscription-btn">View Invoices</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    PLANS & PAYMENTS
                </div>
            </div>    
        </div>

        <!-- <div class="row subscription"> -->
        <div class="row">
            <div class="col-md-8 col-sm-8">
                <div class="portlet light bordered dashboard-panel fill-vertical">
                    <div class="portlet-title">
                        <div class="caption">
                            <span>Currently Active Plan</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="panel panel-default subscription-panel">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <!-- <div class="pull-left subscription-parameters"> -->
                                    <div class="pull-left subscription-parameters">
                                        <div><span>1</span> Location</div>
                                        <div><span>100</span> Text Messages</div>
                                    </div>
                                    <!-- <div class="pull-right subscription-type">FREE</div> -->
                                    <div class="pull-right">FREE</div>
                                </div>
                            </div>
                            <!-- <div class="panel panel-default quota-panel"> -->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <!-- <div class="subscription-parameters"> -->
                                    <div>
                                        <div>25/100</div>
                                        <div>Messages Sent</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Payment Info</span>
                        </div>
                    </div>
                    <!-- <div class="portlet-body credit-card-info"> -->
                    <div class="portlet-body">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="">No Payment Details</span>
                                </div>
                            </div>
                            <!-- <div class="portlet-body">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="input-group">
                                            <div class="row">
                                                <div class="col-md-9 col-sm-9">
                                                    <input disabled type="text" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX"/>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <input disabled type="text" class="form-control" placeholder="Visa"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="growth-bar" style="color: #283643; font-size: 12px;">10/2016</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="growth-bar" style="text-align: center;">   
                                                        <div class="search-btn">
                                                            <a class="btnLink" href="">Update Card</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    CHANGE PLAN
                </div>
            </div>    
        </div>

        <!-- <div class="row subscription"> -->
        <div class="row">    
            <div class="col-md-8 col-sm-8">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <!-- <div class="portlet-body change-plan">
                            <div class="panel panel-default subscription-panel"> -->
                            <div class="portlet-body">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12">
                                            <!-- <div class="pull-left subscription-parameters">   
                                                <div><span>100+</span> Location</div>
                                                <div><span>250+</span> Text Messages</div>
                                            </div>
                                            <div class="pull-right panel panel-default subscription-contact-us">
                                                <div class="panel-body">
                                                    <div class="contact-us-header">CONTACT US</div>
                                                    <div class="contact-us-subheader">for Enterprise Pricing</div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <!-- <div class="portlet-body change-plan">
                            <div class="panel panel-default subscription-panel"> -->
                            <div class="portlet-body">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12">
                                            <!-- <div class="pull-left subscription-parameters">   
                                                <div><span>100+</span> Location</div>
                                                <div><span>250+</span> Text Messages</div>
                                            </div>
                                            <div class="pull-right panel panel-default subscription-contact-us">
                                                <div class="panel-body">
                                                    <div class="contact-us-header">CONTACT US</div>
                                                    <div class="contact-us-subheader">for Enterprise Pricing</div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <!-- <div class="portlet-body change-plan">
                            <div class="panel panel-default subscription-panel"> -->
                            <div class="portlet-body">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12">
                                            <!-- <div class="pull-left subscription-parameters">   
                                                <div><span>100+</span> Location</div>
                                                <div><span>250+</span> Text Messages</div>
                                            </div>
                                            <div class="pull-right panel panel-default subscription-contact-us">
                                                <div class="panel-body">
                                                    <div class="contact-us-header">CONTACT US</div>
                                                    <div class="contact-us-subheader">for Enterprise Pricing</div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="">Price Includes</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <ul>
                                    <li>For Websites with up to 3000 Unique Visitors per Month</li>
                                    <li>Visitor, Lead, and Conversion Tracking</li>
                                    <li>Unlimted Affiliates</li>
                                    <li>Email and Phone Installation Support</li>
                                    <li>Email & Phone Ongoing Support</li>
                                    <li>Live Chat Support 7 Days/Week</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet light bordered dashboard-panel change-plan">
                            <div class="portlet-body">    
                                <div class="btn-group" role="group" aria-label="Basic">
                                    <button autofocus type="button" class="btn btn-default">Monthly</button>
                                    <button type="button" class="btn btn-default">Annually</button>
                                </div>
                                <div class="growth-bar" style="margin: 10px 0; text-align: center;">   
                                    <div class="search-btn">
                                        <a class="btnLink" href="">Change Plan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 
            <div class="row subscription">
                <div class="col-md-8 col-sm-8">
                    <div class="portlet light bordered dashboard-panel">
                        <div class="portlet-title">
                            <div class="caption">
                                <span>HOW MANY LOCATIONS DO YOU WANT?</span>
                            </div>
                        </div>
                        <div class="portlet-body add-locations">
                            <div class="col-sm-9 col-md-9">
                                <input id="ex13" type="text" />
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="panel panel-default subscription-panel">
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="subscription-parameters">   
                                                <div><span style="font-weight: bold;">100+</span></div>
                                                <div><span> Location</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="portlet light bordered dashboard-panel change-plan">
                        <div class="portlet-body">    
                            <div class="btn-group" role="group" aria-label="Basic">
                                <button autofocus type="button" class="btn btn-default">Monthly</button>
                                <button type="button" class="btn btn-default">Annually</button>
                            </div>
                            <div class="growth-bar" style="margin: 10px 0; text-align: center;">   
                                <div class="search-btn">
                                    <a class="btnLink" href="">Change Plan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        
            <div class="row subscription">
                <div class="col-md-8 col-sm-8">
                    <div class="portlet light bordered dashboard-panel">
                        <div class="portlet-title">
                            <div class="caption">
                                <span>HOW MANY TEXT MESSAGES DO YOU NEED PER LOCATION?</span>
                            </div>
                        </div>
                        <div class="portlet-body add-messages">
                            <div class="col-sm-9 col-md-9">
                                <input id="ex14" type="text" />
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="panel panel-default subscription-panel">
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="subscription-parameters">   
                                                <div><span style="font-weight: bold;">250+</span></div>
                                                <div><span> Messages</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            </header>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                    var slider = new Slider("#ex13", {
                        tooltip: 'show',
                        min: 0,
                        max: 100,
                        step: 1,
                        ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                        ticks_labels: [
                            '<div>0</div><div class="tick-marker">|</div>',
                            '<div>10</div><div class="tick-marker">|</div>',
                            '<div>20</div><div class="tick-marker">|</div>',
                            '<div>30</div><div class="tick-marker">|</div>',
                            '<div>40</div><div class="tick-marker">|</div>',
                            '<div>50</div><div class="tick-marker">|</div>',
                            '<div>60</div><div class="tick-marker">|</div>',
                            '<div>70</div><div class="tick-marker">|</div>',
                            '<div>80</div><div class="tick-marker">|</div>',
                            '<div>90</div><div class="tick-marker">|</div>',
                            '<div>100</div><div class="tick-marker">|</div>'
                        ],
                        ticks_snap_bounds: 1
                    });

                    var slider = new Slider("#ex14", {
                        tooltip: 'show',
                        min: 100,
                        max: 1000,
                        step: 50,
                        ticks: [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000],
                        ticks_labels: [
                            '<div>100</div><div class="tick-marker">|</div>',
                            '<div>150</div><div class="tick-marker">|</div>',
                            '<div>200</div><div class="tick-marker">|</div>',
                            '<div>250</div><div class="tick-marker">|</div>',
                            '<div>300</div><div class="tick-marker">|</div>',
                            '<div>350</div><div class="tick-marker">|</div>',
                            '<div>400</div><div class="tick-marker">|</div>',
                            '<div>450</div><div class="tick-marker">|</div>',
                            '<div>500</div><div class="tick-marker">|</div>',
                            '<div>550</div><div class="tick-marker">|</div>',
                            '<div>600</div><div class="tick-marker">|</div>',
                            '<div>650</div><div class="tick-marker">|</div>',
                            '<div>700</div><div class="tick-marker">|</div>',
                            '<div>750</div><div class="tick-marker">|</div>',
                            '<div>800</div><div class="tick-marker">|</div>',
                            '<div>850</div><div class="tick-marker">|</div>',
                            '<div>900</div><div class="tick-marker">|</div>',
                            '<div>950</div><div class="tick-marker">|</div>',
                            '<div>1000</div><div class="tick-marker">|</div>'
                        ],
                        ticks_snap_bounds: 1
                    });

                });
            </script>