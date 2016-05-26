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

        <div class="row subscription">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    <div class="search-btn">
                        <a class="btnLink" href="">View Invoices</a>
                    </div>
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

        <div class="row subscription">
            <div class="col-md-8 col-sm-8">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span>Currently Active Plan</span>
                        </div>
                    </div>
                    <div class="portlet-body active-plan">
                        <div class="panel panel-default subscription-panel">
                            <div class="panel-body">
                                <div class="col-sm-12 col-md-12">
                                    <div class="pull-left subscription-parameters">   
                                        <div><span>1</span> Location</div>
                                        <div><span>100</span> Text Messages</div>
                                    </div>
                                    <div class="pull-right subscription-type">FREE</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default quota-panel">
                            <div class="panel-body">
                                <div class="col-sm-12 col-md-12">
                                    <div class="subscription-parameters">
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
                    <div class="portlet-body credit-card-info">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="">No Payment Details</span>
                                </div>
                            </div>
                            <div class="portlet-body">
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
                                </div>
                            </div>
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

    <div class="row subscription">
        <div class="col-md-8 col-sm-8">
            <div class="portlet light bordered dashboard-panel">
                <div class="portlet-body change-plan">
                    <div class="panel panel-default subscription-panel">
                        <div class="panel-body">
                            <div class="col-sm-12 col-md-12">
                                <div class="pull-left subscription-parameters">   
                                    <div><span>100+</span> Location</div>
                                    <div><span>250+</span> Text Messages</div>
                                </div>
                                <div class="pull-right panel panel-default subscription-contact-us">
                                    <div class="panel-body">
                                        <div class="contact-us-header">CONTACT US</div>
                                        <div class="contact-us-subheader">for Enterprise Pricing</div>
                                    </div>
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

    <div class="row subscription">
        <div class="col-md-8 col-sm-8">
            <div class="portlet light bordered dashboard-panel">
                <div class="portlet-title">
                    <div class="caption">
                        <span>HOW MANY LOCATIONS DO YOU WANT?</span>
                    </div>
                </div>
                <div class="portlet-body add-locations">
                    <input id="ex13" type="text" data-slider-ticks="[0, 100, 200, 300, 400]" data-slider-ticks-snap-bounds="30" data-slider-ticks-labels='["$0", "$100", "$200", "$300", "$400"]'/>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="portlet light bordered dashboard-panel">
                <div class="portlet-body">
                    <div class="portlet light bordered dashboard-panel"></div>    
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
                <div class="portlet-body add-locations"></div>
            </div>
        </div>
    </div>
</header>
<script type="text/javascript">
jQuery(document).ready(function($){

    $("#ex13").slider({
        ticks: [0, 100, 200, 300, 400],
        ticks_labels: ['$0', '$100', '$200', '$300', '$400'],
        ticks_snap_bounds: 30
    });
  
});
</script>