{{ content() }}

<header class="jumbotron subhead" id="reviews">
    <div class="hero-unit">
        <div class="row">
            <?php $this->partial("shared/smsQuota", $this->view->smsQuotaParameters); ?>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="growth-bar transparent pull-right">
                    <a href="/subscription/invoices" disabled class="btn default btn-lg apple-backgound subscription-btn">View Invoices</a>
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
                                    <div><span class="bold"><?php echo $this->view->subscriptionPlan['locations']; ?></span> Location(s)</div>
                                    <div><span class="bold"><?php echo $this->view->subscriptionPlan['sms_messages_per_location']; ?></span> Text Messages</div>
                                </div>
                                <div class="responsive-float-right subscription-panel-large-caption"><?php echo $this->view->paymentPlan; ?></div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="quota-display">
                                    <div class="subscription-panel-quota-caption midnight-text"><?php echo $this->view->smsQuotaParams['smsSentThisMonth']; ?>/<?php echo $this->view->subscriptionPlan['sms_messages_per_location']; ?></div>
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
                                            <div><img src="/img/cc/visa.png"></div>
                                            <div><span>XX/XX</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row small">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group">
                                            <input type="text" disabled class="form-control center" name="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" autocomplete="cc-exp" required="" aria-required="true">
                                        </div>
                                        <div class="form-group">
                                            <div><img src="/img/cc/visa.png"></div>
                                            <div><span>10/2016</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <button type="button" <?php echo $this->view->upgradeCreditCardStatus; ?> class="btn default btn-lg apple-backgound subscription-btn" data-toggle="modal" data-target="#updateCardModal">Update Card</button>
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
                                            <div><span id="change-plan-locations" class="bold"></span> Location(s)</div>
                                            <div><span id="change-plan-messages" class="bold"></span> Text Messages</div>
                                        </div>
                                        <div id="pricing-attr" 
                                             class="responsive-float-right subscription-panel-large-caption" 
                                             data-base="<?php echo $this->view->pricingPlan['base_price']; ?>"
                                             data-cpm="<?php echo $this->view->pricingPlan['charge_per_sms']; ?>"
                                             data-ad="<?php echo $this->view->pricingPlan['annual_plan_discount']; ?>">
                                            <sup class="subscription-panel-default-caption">$</sup><span id="change-plan-final-price"></span><sub class="subscription-panel-default-caption">/mo</sub>
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
                                                    <div><span id="slider-locations" class="bold"></span></div>
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
                                                    <div><span id="slider-messages" class="bold"></span></div>
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
                                            <div id="plan-type" class="btn-group btn-toggle subscription-toggle"> 
                                                <button class="btn active btn-primary" data-subscription='M'>Monthly</button>
                                                <button class="btn btn-default" data-subscription='Y'>Annually</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="growth-bar transparent center">
                                                <!-- data-toggle="modal" data-target="#updatePlanModal" -->
                                                <button id="submit-change-plan-btn" class="btn btn-block subscription-btn golden-poppy-backgound">Change Plan</button>
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
                        <button type="button" id="confirm-update-credit-card" class="btn default apple-backgound subscription-btn">Update</button>
                        <button type="button" id="cancel-update-credit-card" class="btn default apple-backgound subscription-btn" data-dismiss="modal">Cancel</button>
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
                                <div><span id="modal-locations" class="bold"></span> Location(s)</div>
                                <div><span id="modal-messages" class="bold"></span> Text Messages</div>
                            </div>
                            <div class="responsive-float-right subscription-panel-large-caption">
                                <sup class="subscription-panel-default-caption">$</sup><span id="modal-price"></span><sub class="subscription-panel-default-caption">/mo</sub>
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
    </div>
</header>
<script type="text/javascript">

    jQuery(document).ready(function ($) {

        function initSubscriptionParameters() {
            /* Message and locations init */
            $('#change-plan-locations').text(slider13.getValue());
            $('#slider-locations').text(slider13.getValue());
            $('#modal-locations').text(slider13.getValue());
            $('#change-plan-messages').text(slider14.getValue());
            $('#slider-messages').text(slider14.getValue());
            $('#modal-messages').text(slider13.getValue());
            
            /* Slider initializations */
            slider13.setValue(1, true, true);
            slider14.setValue(100, true, true);
            
            /* Calculate the initial plan value */
            calculatePlanValue();
        }

        function calculatePlanValue() {
            var priceElem = document.getElementById("pricing-attr");
            var priceDisplay = document.getElementById("change-plan-final-price");
            var modalPriceDisplay = document.getElementById("modal-price");

            var locations = slider13.getValue();
            var messages = slider14.getValue();

            var costPerLocation = (messages * priceElem.dataset.cpm);
            var totalCost = (costPerLocation * locations);

            var price =
                    parseFloat(priceElem.dataset.base) + // base price
                    totalCost; // charges for messages across all locations

            var planType = $(document.getElementById("plan-type")).find('.btn-primary').text();
            if (planType === 'Annually') {
                price = ((price * 12) * parseFloat(1 - priceElem.dataset.ad)) / 12;  // Apply the discount
            }

            $(priceDisplay).text(price.toFixed(2));
            $(modalPriceDisplay).text(price.toFixed(2));
        };

        function getSubscriptionParams() {
            var locations = slider13.getValue();
            var messages = slider14.getValue();
            var planType = $(document.getElementById("plan-type")).find('.btn-primary')[0].dataset.subscription;
            var price = $(document.getElementById("change-plan-final-price")).text();
            ;
            return {locations: locations, messages: messages, planType: planType, price: price};
        };
        
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
        
        function pingPaymentProfile() {
            $.post('/subscription/hasPaymentProfile', getCCParams())
                .done(function (data) {
                    if (data.status === true) {
                        changePlan()
                    } else {
                        $('#updateCardModal').data('paymentProfile', "new");
                        $('#updateCardModal').modal('show');
                    }
                })
                .fail(function () {})
                .always(function () {});
        }
        
        function addPlanWithPaymentProfile() {
            var allParams = $.extend({}, getCCParams(), getSubscriptionParams());
            $.post('/subscription/addPlanWithPaymentProfile', allParams)
                .done(function (data) {
                    if (data.status === true) {
                        $('#updatePlanModal').modal('show');
                    } else {
                        alert("Change plan failed!!!");
                    }
                })
                .fail(function () {})
                .always(function () {});
        }
        
        function changePlan() {  
            $.post('/subscription/changePlan', getSubscriptionParams())
                .done(function (data) {
                    if (data.status === true) {
                        $('#updatePlanModal').modal('show');
                    } else {
                        alert("Change plan failed!!!");
                    }
                })
                .fail(function () {})
                .always(function () {});
        }
        
        function updateCard() {
            $.post('/subscription/updatePaymentProfile', getCCParams())
                .done(function (data) {
                    if (data.status === true) {
                        alert("Update card failed!!!")
                    } 
                })
                .fail(function () {})
                .always(function () {});
        }

        var slider13 = new Slider("#ex13", {
            tooltip: 'show',
            min: 1,
            max: 100,
            step: 1,
            ticks: [1, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
            ticks_labels: [
                '<div>1</div><div class="tick-marker">|</div>',
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

        var slider14 = new Slider("#ex14", {
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
        
        slider13.on('change', function () {
            $('#change-plan-locations').text(slider13.getValue());
            $('#slider-locations').text(slider13.getValue());
            $('#modal-locations').text(slider13.getValue());
            calculatePlanValue();
        });
        slider14.on('change', function () {
            $('#change-plan-messages').text(slider14.getValue());
            $('#slider-messages').text(slider14.getValue());
            $('#modal-messages').text(slider13.getValue());
            calculatePlanValue();
        });

        $('#confirm-update-credit-card').click(function () {
            if ($('#updateCardModal').data('paymentProfile') === "new") {
                addPlanWithPaymentProfile();
            } else {
                updateCard();
            }
            $('#updateCardModal').modal('hide');  // We can hide the modal here, we don't need any paramters    
        });
        
        $('.subscription-toggle').click(function () {
            $(this).find('.btn').toggleClass('active');

            if ($(this).find('.btn-primary').size() > 0) {
                $(this).find('.btn').toggleClass('btn-primary');
            }

            $(this).find('.btn').toggleClass('btn-default');

            calculatePlanValue();
        });
        
        $('#submit-change-plan-btn').click(function () {
            pingPaymentProfile();
        });
        
        initSubscriptionParameters();
        
    });
</script>