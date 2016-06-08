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
                <div class="growth-bar transparent pull-right">
                    <a href="/admin/subscription/invoices" class="btn default btn-lg apple-backgound subscription-btn">View Invoices</a>
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

        <div class="row subscription-panel-group plans-and-payments-row">
            <div class="col-md-12 col-lg-8">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span>Currently Active Plan</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="panel panel-default apple-backgound">
                            <div class="panel-body">
                                <div class="responsive-float-left subscription-panel-default-caption">
                                    <div><span class="bold">1</span> Location</div>
                                    <div><span class="bold">100</span> Text Messages</div>
                                </div>
                                <div class="responsive-float-right subscription-panel-large-caption">TRIAL</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="quota-display">
                                    <div class="subscription-panel-quota-caption midnight-text">25/100</div>
                                    <div class="subscription-panel-quota-caption midnight-text">Messages Sent</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-4">
                <div class="portlet light bordered dashboard-panel payment-info">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Payment Info</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="">No Payment Details</span>
                                </div>
                            </div>
                            <div class="panel-body credit-card-details">
                                <div class="row large">
                                    <div class="col-xs-8 col-md-8">
                                        <div class="form-group">
                                            <input type="text" disabled class="form-control center" name="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" autocomplete="cc-exp" required="" aria-required="true">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4 pull-right">
                                        <div class="form-group">
                                            <div><img src="/admin/img/cc/visa.png"></div>
                                            <div><span>10/2016</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row small">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group">
                                            <input type="text" disabled class="form-control center" name="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" autocomplete="cc-exp" required="" aria-required="true">
                                        </div>
                                        <div class="form-group">
                                            <div><img src="/admin/img/cc/visa.png"></div>
                                            <div><span>10/2016</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="modal" data-target="#updateCardModal">Update Card</button>
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

        <div class="row subscription-panel-group change-plans-row">
            <div class="col-md-12 col-lg-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-body">
                                <div class="panel panel-default apple-backgound">
                                    <div class="panel-body">
                                        <div class="responsive-float-left subscription-panel-default-caption">
                                            <div><span class="bold">100+</span> Location</div>
                                            <div><span class="bold">250+</span> Text Messages</div>
                                        </div>
                                        <div class="responsive-float-right subscription-panel-large-caption">
                                            <sup class="subscription-panel-default-caption">$</sup>17<sub class="subscription-panel-default-caption">/mo</sub>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span>HOW MANY LOCATIONS DO YOU WANT?</span>
                                </div>
                            </div>
                            <div class="portlet-body add-locations">
                                <div class="col-sm-9 col-md-10">
                                    <input id="ex13" type="text" />
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="panel panel-default subscription-panel apple-backgound">
                                        <div class="panel-body">
                                            <div class="col-sm-12 col-md-12 slider-quota-caption">
                                                <div>
                                                    <div><span class="bold">100+</span></div>
                                                    <div>Location</div>
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
                    <div class="col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span>HOW MANY TEXT MESSAGES DO YOU NEED PER LOCATION?</span>
                                </div>
                            </div>
                            <div class="portlet-body add-messages">
                                <div class="col-sm-9 col-md-10">
                                    <input id="ex14" type="text" />
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="panel panel-default subscription-panel apple-backgound">
                                        <div class="panel-body">
                                            <div class="col-sm-12 col-md-12 slider-quota-caption">
                                                <div>
                                                    <div><span class="bold">250+</span></div>
                                                    <div><span> Messages</span></div>
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
            <div class="col-md-12 col-lg-4">
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
                        <div class="portlet light bordered change-plan">
                            <div class="portlet-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <!-- <input type="checkbox" class="make-switch" data-on-text="Monthly" data-off-text="Annually"> -->
                                            <div class="btn-group btn-toggle subscription-toggle"> 
                                                <button class="btn active btn-primary">Monthly</button>
                                                <button class="btn btn-default">Annually</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="growth-bar transparent center">
                                                <button class="btn btn-block subscription-btn golden-poppy-backgound" data-toggle="modal" data-target="#updatePlanModal">Change Plan</button>
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

    <!-- Update credit card pop up -->
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
                        <button type="button" class="btn default apple-backgound subscription-btn">Update</button>
                        <button type="button" class="btn default apple-backgound subscription-btn" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Change plan pop up -->
    <div class="modal fade" id="updatePlanModal" tabindex="-1" role="dialog" aria-labelledby="updatePlanModalLabel">
        <div class="change-plan modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="growth-bar">
                        <div class="caption">
                            <span>CHANGE PLAN SUCCEEDED!!!</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default apple-backgound">
                        <div class="panel-body">
                            <div class="responsive-float-left subscription-panel-default-caption">
                                <div><span class="bold">100+</span> Location</div>
                                <div><span class="bold">250+</span> Text Messages</div>
                            </div>
                            <div class="responsive-float-right subscription-panel-large-caption">
                                <sup class="subscription-panel-default-caption">$</sup>17<sub class="subscription-panel-default-caption">/mo</sub>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="button" class="btn default golden-poppy-backgound subscription-btn" data-dismiss="modal">Finish</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        
        $('.subscription-toggle').click(function() {
            $(this).find('.btn').toggleClass('active');  
    
            if ($(this).find('.btn-primary').size()>0) {
                $(this).find('.btn').toggleClass('btn-primary');
            }
    
            $(this).find('.btn').toggleClass('btn-default');
       
        });
        
    });
</script>