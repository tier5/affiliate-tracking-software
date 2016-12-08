{{ content() }}

<style type="text/css">
    td.hide-large.show-small{
        font-weight:bold;
    }
    @media(min-width: 600px) {
        td.hide-large{
            display:none !important;
        }
        td.show-small{
            display:block;
        }
    }
</style>

<header class="jumbotron subhead" id="reviews">
    <div class="hero-unit">
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <h3 class="page-title">
                    Subscriptions   <small>for business</small>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet light bordered pricing-plans">
                    <div class="portlet-title">
                        <div class="caption font-dark subscription-caption">
                            <i class="fa fa-money"></i>
                            <span class="caption-subject bold uppercase">Subscriptions</span>
                        </div>
                        <div class="caption font-dark">
                            <div class="form-group">
                            <label>Subscription Name</label>
                            <input id="name-control" type="text" value="{% if !isNewRecord %}{{ name }}{% else %}My New Subscription{% endif %}" class="caption-subject form-control input-medium" placeholder="Subscription Name" {% if !isNewRecord %} readonly {% endif %}/>
                                <hr>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Enable Trial Account</label>
                                    </div>
                                    <div class="col-md-6">
                                        {% if enableTrialAccount %}
                                            <input id="enable-trial-account-control" type="checkbox" class="make-switch" checked data-on-color="primary" data-off-color="info">
                                        {% else  %}
                                            <input id="enable-trial-account-control" type="checkbox" class="make-switch" data-on-color="primary" data-off-color="info">
                                        {% endif  %}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Free SMS Messages on Trial Account</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" id="hidden-free-sms-messages" value="{{maxMessagesOnTrialAccount}}"/>
                                        <select id="free-sms-messages-control" class="form-control input-small" value="{{ maxMessagesOnTrialAccount }}">
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Base Price $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="base-price-control" type="number" value="{{ basePrice }}" step="0.01" min="0.00" class="form-control" placeholder="29.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Charge Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="charge-per-sms-control" type="number" value="{{ chargePerSms }}" step="0.01" min="0" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Enable Discount On Upgrade</label>
                                    </div>
                                    <div class="col-md-6">
                                        {% if enableDiscountOnUpgrade %}
                                            <input id="enable-discount-on-upgrade-control" type="checkbox" class="make-switch" checked data-on-color="primary" data-off-color="info">
                                        {% else  %}
                                            <input id="enable-discount-on-upgrade-control" type="checkbox" class="make-switch" data-on-color="primary" data-off-color="info">
                                        {% endif  %}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Upgrade Discount %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" name="upgradeDiscountValue" id="upgrade-discount-value" value="{{upgradeDiscount}}"/>
                                        <select id="upgrade-discount-control" name="upgradeDiscount" class="form-control input-small" value="{{ upgradeDiscount }}"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">My Cost Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="cost-per-sms-control" type="number" value="{{ costPerSms }}" step="0.01" min="0" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Max SMS Messages</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="max-sms-messages-control" type="number" value="{{ maxSmsMessages }}" step="50" min="0" class="form-control" placeholder="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Enable Annual Discount</label>
                                    </div>
                                    <div class="col-md-6">
                                        {% if enableAnnualDiscount %}
                                            <input id="enable-annual-discount-control" type="checkbox" class="make-switch" checked data-on-color="primary" data-off-color="info">
                                        {% else  %}
                                            <input id="enable-annual-discount-control" type="checkbox" class="make-switch" data-on-color="primary" data-off-color="info">
                                        {% endif  %}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Annual Discount %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" name="annualDiscountValue" id="annual-discount-value" value="{{ annualDiscount }}"/>
                                        <select id="annual-discount-control" name="annualDiscount" class="form-control input-small" value="{{ annualDiscount }}">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light bordered pricing-plans editor">
                                    <div class="portlet-title">
                                        <div class="caption font-dark subscription-caption">
                                            <span class="caption-subject bold uppercase">Pricing Details</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal form-bordered">
                                            <div class="form-body">
                                                <div class="form-group last">
                                                    <div class="col-md-12">
                                                        <div name="summernote" id="summernote_1">{{ pricingDetails }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row subscription-panel-group">
                            <div class="col-sm-12">
                                <div class="portlet light bordered dashboard-panel">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject bold uppercase">SMS MESSAGES</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body add-messages">
                                        <div class="col-sm-9 col-md-10">
                                            <input id="sms-messages-slider" type="text" />
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet light bordered pricing-plans">
                                    <div class="portlet-body progression">
                                        <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-bordered table-hover dataTable no-footer collapsed" width="100%" id="sample_1" role="grid" aria-describedby="sample_1_info" style="width: 100%;">
                                                    <thead>
                                                    <tr role="row">
                                                        <th>Number of <br />Locations</th>
                                                        <th>Discount <br /> Percentage</th>
                                                        <th>Base<br />Price</th>
                                                        <th>SMS<br />Charge</th>
                                                        <th>Total<br />Price</th>
                                                        <th>Location<br />Discount</th>
                                                        <th>Upgrade<br />Discount</th>
                                                        <th>Discount<br />Price</th>
                                                        <th>SMS<br />Messages</th>
                                                        <th>SMS<br />Cost</th>
                                                        <th>Profit Per<br />Location</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="progression-table-rows">
                                                        {% for progression in progressions %}
                                                        <tr role="row" class="odd">
                                                            <td class="show-small hide-large">Locations</td>
                                                            <td>
                                                                <form class="form-inline" role="form">
                                                                    <div class="form-group">
                                                                        <input type="number" value="{{ progression['min_locations'] }}" step="1" min="{{ progression['min_locations'] }}" class="form-control input-xsmall min-locations-control" placeholder="">
                                                                    </div>
                                                                    <span>To</span>
                                                                    <div class="form-group">
                                                                        <input type="number" value="{{ progression['max_locations'] }}" step="1" min="{{ progression['max_locations'] }}" class="form-control input-xsmall max-locations-control" placeholder="">
                                                                    </div>
                                                                </form>
                                                            </td>
                                                            <td class="show-small hide-large">Discount</td>
                                                            <td>
                                                                <select value="{{progression['location_discount_percentage'] }}" class="form-control input-small location-discount-control"></select>
                                                            </td>
                                                            <td class="show-small hide-large">Base Price</td>
                                                            <td class="base-price-column">${{ progression['base_price'] }}</td>
                                                            <td class="show-small hide-large">SMS Charge</td>
                                                            <td class="sms-charge-column">${{ progression['sms_charge'] }}</td>
                                                            <td class="show-small hide-large">Total Price</td>
                                                            <td class="total-price-column">${{ progression['total_price'] }}</td>
                                                            <td class="show-small hide-large">Location Discount</td>
                                                            <td class="location-discount-column">${{ progression['location_discount'] }}</td>
                                                            <td class="show-small hide-large">Upgrade Discount</td>
                                                            <td class="upgrade-discount-column">${{ progression['upgrade_discount'] }}</td>
                                                            <td class="show-small hide-large">Discount Price</td>
                                                            <td class="discount-price-column">${{ progression['discount_price'] }}</td>
                                                            <td class="show-small hide-large">SMS Messages</td>
                                                            <td class="sms-messages-column"><span class="value">{{ progression['sms_messages'] }}</span></td>
                                                            <td class="show-small hide-large">SMS Cost</td>
                                                            <td class="sms-cost-column">${{ progression['sms_cost'] }}</td>
                                                            <td class="show-small hide-large">Profit Per Location</td>
                                                            <td class="profit-per-location-column">{{ progression['profit_per_location'] }}</td>
                                                        </tr>
                                                        {% endfor %}
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row progression-controls">
                                                <div class="col-md-4 col-sm-4"></div>
                                                <div class="col-md-8 col-sm-8">
                                                    <button id="cancel-btn" class="btn default btn-lg apple-backgound subscription-btn">Cancel</button>
                                                    <button id="remove-segment-btn" class="btn default btn-lg apple-backgound subscription-btn" {{ gridEditStatus }}>Remove Last</button>
                                                    <button id="add-segment-btn" class="btn default btn-lg apple-backgound subscription-btn" {{ gridEditStatus }}>Add New</button>
                                                    {% if isCreateMode %}
                                                        <button id="save-plan-btn" class="btn default btn-lg apple-backgound subscription-btn" {{ gridEditStatus }}>Save</button>
                                                    {% else  %}
                                                        <button id="update-plan-btn" class="btn default btn-lg apple-backgound subscription-btn" {{ gridEditStatus }}>Update</button>
                                                    {% endif  %}
                                                    <button id="start-over-btn" class="btn default btn-lg apple-backgound subscription-btn" {{ gridEditStatus }}>Start Over</button>
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
</header>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        var maxProgressionSegments = 10;

        var annualDiscount = $('#annual-discount-value').val();
        var upgradeDiscount = $('#upgrade-discount-value').val();


        function generatePercentageOptions() {
            var options = "";
            for (var i = 0; i <= 100; i++) {
                options += "<option value=\"" + i.toString() + "\">" + +i.toString() + "</option>";
            }
            return options;
        }

        function findLocationRange() {
            var min = 1;
            var max = 10;

            var last = $('#progression-table-rows').find('tr').last();
            if (last.length > 0) {
                min = parseInt($(last.find('td input')[1]).val()) + 1;
                max = parseInt($(last.find('td input')[1]).val()) + maxProgressionSegments;
            }

            var obj = {
                min: min,
                max: max
            };
            return obj;
        }

        function getValueParameters() {
            return {
                name: $('input[id="name-control"]').val(),
                enableTrialAccount: $('input[id="enable-trial-account-control"]').bootstrapSwitch('state') ? true : false,
                enableDiscountOnUpgrade: $('input[id="enable-discount-on-upgrade-control"]').bootstrapSwitch('state') ? true : false,
                basePrice: $('input[id="base-price-control"]').val(),
                costPerSms: $('input[id="cost-per-sms-control"]').val(),
                maxMessagesOnTrialAccount: $('select[id="free-sms-messages-control"]').val(),
                upgradeDiscount: $('select[id="upgrade-discount-control"]').val(),
                chargePerSms: $('input[id="charge-per-sms-control"]').val(),
                maxSmsMessages: $('input[id="max-sms-messages-control"]').val(),
                enableAnnualDiscount: $('input[id="enable-annual-discount-control"]').bootstrapSwitch('state') ? true : false,
                annualDiscount: $('select[id="annual-discount-control"]').val(),
                upgradeDiscount: $('select[id="upgrade-discount-control"]').val(),
                pricingDetails: $('#summernote_1').code()
            };
        }

        function getProgressionDetails() {

            var progressionDetails = {};
            $('#progression-table-rows').find('tr').each(function(index) {
                progressionDetails['segment' + index] = {
                    minLocations: $(this).find('td form input.min-locations-control').first().val(),
                    maxLocations: $(this).find('td form input.max-locations-control').first().val(),
                    locationDiscountPercentage: $(this).find('td select.location-discount-control').first().val(),
                    basePrice: $(this).find('td.base-price-column').first().text(),
                    smsCharge: $(this).find('td.sms-charge-column').first().text(),
                    totalPrice: $(this).find('td.total-price-column').first().text(),
                    locationDiscount: $(this).find('td.location-discount-column').first().text(),
                    upgradeDiscount: $(this).find('td.upgrade-discount-control').first().text(),
                    smsMessages: $(this).find('td.sms-messages-column span.value').text(),
                    smsCost: $(this).find('td.sms-cost-column').first().text(),
                    profitPerLocation: $(this).find('td.profit-per-location-column').first().text().replace('$','')
                };
            });

            return progressionDetails;

        }

        function savePricingProfile(isCreate) {
        	if($('#max-sms-messages-control').val() < 50) {
        		alert("Maximum SMS messages must meet or exceed 50.");
        		return false;
        	}
            var parameters = {};
            $.extend(parameters, getValueParameters(), getProgressionDetails());

            var url = "/businessPricingPlan/createPricingPlan";
            if (!isCreate) {
                url = "/businessPricingPlan/updatePricingPlan";
            }

            $.post(url,
                    JSON.stringify(parameters),
                    function(data) {
                        if (data.status === true) {
                            window.location.href = "/businessPricingPlan";
                        } else {
                            alert(data.message);
                        }
                    }
            );
        }

        function initSwitchToggleBindings(options) {

            $('input[id="enable-trial-account-control"]').on('switchChange.bootstrapSwitch', function (event, state) {
                $("#free-sms-messages-control").prop('disabled', !state);
            });

            $('input[id="enable-discount-on-upgrade-control"]').on('switchChange.bootstrapSwitch', function (event, state) {
                $("#upgrade-discount-control").prop('disabled', !state);
            });

            $('input[id="enable-annual-discount-control"]').on('switchChange.bootstrapSwitch', function (event, state) {
                $("#annual-discount-control").prop('disabled', !state);
            });

            $('input[id="upgrade-discount-control"]').on('change', function (event) {
                if ($('input[id="enable-discount-on-upgrade-control"]').state) {
                    $('#progression-table-rows').find('tr td > select.location-discount-control').each(function (index) {
                        $(this).append(options);
                    });
                }
            });

        }

        function recalculateTotalSmsCharge() {
            var chargePerSms = parseFloat($('input[id="charge-per-sms-control"]').val());
            var smsMessages = parseFloat($('input[id="max-sms-messages-control"]').val());
            var smsCharge = parseFloat(chargePerSms) * parseInt(smsMessages);
            $('#progression-table-rows').find('tr td.sms-charge-column').each(function (index) {
                $(this).text(smsCharge.toFixed(2)); // Update the cell value
            });
        }

        function recalculateTotalCharge() {
            var basePrice = $('input[id="base-price-control"]').val();
            var chargePerSms = $('input[id="charge-per-sms-control"]').val();
            //var smsMessages = $('input[id="max-sms-messages-control"]').val();
            var smsMessages = $('#sms-messages-slider').val();
            var smsCharge = parseFloat(chargePerSms) * parseInt(smsMessages);
            var totalCharge = parseFloat(basePrice) + parseFloat(smsCharge);

            $('#progression-table-rows').find('tr td.total-price-column').each(function (index) {
                $(this).text("$" + totalCharge.toFixed(2)); // Update the cell value
            });
        }

        function refreshBasePrice() {
            var basePrice = parseFloat($('input[id="base-price-control"]').val());
            $('#progression-table-rows').find('tr td.base-price-column').each(function (index) {
                $(this).text(basePrice.toFixed(2)); // Update the cell value
            });
        }

        function refreshLocationDiscount(discountElement) {
            var discount = parseFloat(discountElement.val());
            var basePrice = parseFloat($('input[id="base-price-control"]').val());
            var locationDiscount = parseFloat(basePrice) * parseFloat(discount * 0.01);
            $(discountElement).parent().siblings(".location-discount-column").text(locationDiscount.toFixed(2));
        }

        function refreshAllLocationDiscounts() {
            $('#progression-table-rows').find('tr td select.location-discount-control').each(function (index) {
                refreshLocationDiscount($(this));
            });
        }

        function recalculateAllUpgradeDiscounts() {
            var discount = parseFloat($('select[id="upgrade-discount-control"]').val());
            var basePrice = parseFloat($('input[id="base-price-control"]').val());
            var upgradeDiscount = parseFloat(basePrice) * parseFloat(discount * 0.01);
            $('#progression-table-rows').find('tr td.upgrade-discount-column').each(function (index) {
                $(this).text(upgradeDiscount.toFixed(2));
            });
        };

        function recalculateDiscountedPrice(discountElement) {
            var basePrice = parseFloat($('input[id="base-price-control"]').val());
            var locationDiscount = parseFloat($(discountElement).siblings(".location-discount-column").text());
            var upgradeDiscount = parseFloat($(discountElement).siblings(".upgrade-discount-column").text());
            var smsCharge = parseFloat($(discountElement).siblings(".sms-charge-column").text());
            var discountedPrice = parseFloat(smsCharge + (basePrice - locationDiscount - upgradeDiscount));
            $(discountElement).text(discountedPrice.toFixed(2));
        }

        function recalculateAllDiscountedPrices() {
            $('#progression-table-rows').find('tr td.discount-price-column').each(function (index) {
                recalculateDiscountedPrice($(this));
                var profitElement = $(this).siblings(".profit-per-location-column");
                recalculateProfits(profitElement);
            });
        }

        function recalculateAllSmsCost() {
            var smsCost = parseFloat($('input[id="cost-per-sms-control"]').val());
            var maxSmsMessages = parseInt($('input[id="max-sms-messages-control"]').val());
            var total = parseFloat(smsCost * maxSmsMessages);
            $('#progression-table-rows').find('tr td.sms-cost-column').each(function (index) {
                $(this).text(total.toFixed(2));
                var profitElement = $(this).siblings(".profit-per-location-column");
                recalculateProfits(profitElement);
                setTimeout(function(){
                    recalculateProfits(profitElement);
                },2);
            });
        }

        function recalculateProfits(profitElement) {
            var smsCharge = parseFloat($(profitElement).parent().find('.sms-charge-column').text().replace('$',''));

            var sliderSMSAmount = $('#sms-messages-slider').val();

            console.log('sliderSMSAmount',sliderSMSAmount);

            var smsTotalCharge = sliderSMSAmount * parseFloat($('#charge-per-sms-control').val());

            var smsTotalChargeActualCost = sliderSMSAmount * parseFloat($('#cost-per-sms-control').val());

            console.log('smsTotalCharge',smsTotalCharge,'smsTotalChargeActualCost',smsTotalChargeActualCost);




            var locationDiscountPercentage = parseInt($(profitElement).parent().find('.location-discount-control').val());
            var basePrice = parseFloat($(profitElement).parent().find('.base-price-column').text().replace('$',''));
            var locationDiscount = parseFloat(basePrice * (locationDiscountPercentage / 100));
            $(profitElement).parent().find('.location-discount-column').text('$' + locationDiscount.toFixed(2).toString());
            //upgrade discount
            var upgradeDiscountPercentage = parseInt($('#upgrade-discount-control').val(),10);
            //set the display discount
            var upgradeDiscount = basePrice * (upgradeDiscountPercentage / 100);
            //this is the actual discount price
            var discountPrice = basePrice - locationDiscount + smsCharge - upgradeDiscount;

            $(profitElement).parent().find('.discount-price-column').text('$' + discountPrice.toFixed(2).toString());

            //set the upgrade discount
            $(profitElement).parent().find('.upgrade-discount-column').text('$' + upgradeDiscount.toFixed(2).toString());

            var priceUpgradeDiscount = parseFloat(basePrice - (basePrice * (upgradeDiscountPercentage / 100))).toFixed(2);
            $(profitElement).parent().find('.discount-price-column').text('$' + discountPrice.toFixed(2).toString());
            var smsCost = smsTotalChargeActualCost.toFixed(2);
            $(profitElement).parent().find('.sms-cost-column').text('$' + smsCost);
            var smsCharge = smsTotalCharge.toFixed(2);
            $(profitElement).parent().find('.sms-charge-column').text('$' + smsCharge);
            var smsCost = parseFloat(smsCost);
            var totalProfit = discountPrice - smsCost;
            $(profitElement).parent().find('.profit-per-location-column').text('$' + parseInt(totalProfit,10).toString());
            recalculateTotalCharge();
        }

        function getPlanCostPerSMSMessage(){
            return parseFloat(document.getElementById('charge-per-sms-control').value,10);
        }

        function getActualCostPerSMSMessage(){
            return parseFloat(document.getElementById('cost-per-sms-control').value,10);
        }

        function refreshDisplayPrices(){
            var planCost = getPlanCostPerSMSMessage();
            var actualCost = getActualCostPerSMSMessage();
            var sliderValue = smsMessagesSlider.getValue();
            var totalPlanCost = planCost * sliderValue;
            var actualPlanCost = actualCost * sliderValue;

            var profit = totalPlanCost - actualPlanCost;

            $('#progression-table-rows tr').each(function(){
                //var row = $(this);
                //$(this).find('.discount-price-column').text('$' + actualPlanCost.toFixed(2));
                //$(this).find('.profit-per-location-column:first').text('$' + profit.toFixed(2));
            });
            recalculateAllDiscountedPrices();
        }

        var smsMessagesSlider = null;

        function refreshSmsSliderControls(val) {
            if (smsMessagesSlider) {
                $('#slider-messages').text(val);
                smsMessagesSlider.setValue(parseInt(val), true, true);
                smsMessagesSlider.on('change', function () {
                    var sliderValue = smsMessagesSlider.getValue();
                    recalculateAllDiscountedPrices();
                    $('#slider-messages').text(smsMessagesSlider.getValue());
                });
            }
        }

        function rebuildSmsSlider(max, step) {

            if (smsMessagesSlider) {
                smsMessagesSlider.destroy();
            }

            // Compute ticks scales
            var ticks = [];
            var tick_labels = [];
            for(var i = step; i <= max; i += step) {
                ticks.push(i);
                tick_labels.push('<div>' + i + '</div><div class="tick-marker">|</div>');
            }

            smsMessagesSlider = new Slider("#sms-messages-slider", {
                tooltip: 'show',
                min: step,
                max: max,
                step: step,
                ticks: ticks,
                ticks_labels: tick_labels,
                ticks_snap_bounds: step
            });

        }

        function initValueBindings(options) {

            $('select.location-discount-control').on('change', function (event) {
                refreshLocationDiscount($(event.currentTarget));
                var discountElement = $(event.currentTarget).parent().siblings(".discount-price-column");
                recalculateDiscountedPrice(discountElement);
                var profitElement = $(event.currentTarget).parent().siblings(".profit-per-location-column");
                recalculateProfits(profitElement);
            });

            // Base price control
            $('input[id="base-price-control"]').on('change', function (event) {
                refreshBasePrice();
                recalculateTotalCharge();
                refreshAllLocationDiscounts();
                recalculateAllDiscountedPrices();
                refreshDisplayPrices();
            });

            // Charge per sms control
            $('input[id="charge-per-sms-control"]').on('change', function (event) {
                recalculateTotalSmsCharge();
                recalculateTotalCharge();
                refreshAllLocationDiscounts();
                recalculateAllDiscountedPrices();
                refreshDisplayPrices();
            });

            // Upgrade discount control
            $('select[id="upgrade-discount-control"]').on('change', function (event) {
                recalculateAllUpgradeDiscounts();
                recalculateAllDiscountedPrices();
                refreshDisplayPrices();
            });

            // Cost per sms control
            $('input[id="cost-per-sms-control"]').on('change', function (event) {
                recalculateAllSmsCost();
                refreshDisplayPrices();
            });

            // Max sms messages control
            $('input[id="max-sms-messages-control"]').on('change', function (event) {
                recalculateTotalSmsCharge();
                recalculateTotalCharge();
                refreshAllLocationDiscounts();
                recalculateAllDiscountedPrices();
                recalculateAllSmsCost();
                $('#progression-table-rows').find('tr td.sms-messages-column span.value').each(function () {
                    $(this).text($(event.currentTarget).val()); // Update the cell value
                });
                $('#slider-messages').text($(event.currentTarget).val());

                /* Refresh the slider */
                var messages = $(event.currentTarget).val();
                rebuildSmsSlider(messages, 50);
                refreshSmsSliderControls(messages);
                refreshDisplayPrices();
                recalculateAllDiscountedPrices();

            });
            if(upgradeDiscount && upgradeDiscount !== '') $('#upgrade-discount-control').val(parseInt(upgradeDiscount,10));
            if(annualDiscount && annualDiscount !== '') $('#annual-discount-control').val(parseInt(annualDiscount,10));
            setTimeout(function() {
                $('.location-discount-control').each(function () {
                    var value = null;
                    var value = $(this).attr('value');
                    $(this).find('option').each(function () {
                        if (parseInt($(this).val()) === parseInt(value)) $(this).attr('selected', 'selected');
                    });
                });
            },1);
        }

        function addSegment(min, max, options) {

            var row = "";
            row += "<tr role=\"row\" class=\"odd\">";
            row += '<td class="show-small hide-large">Base Price</td>';
            row += "    <td>";
            row += "        <form class=\"form-inline\" role=\"form\">";
            row += "            <div class=\"form-group\">";
            row += "                <input type=\"number\" value=\"" + min + "\" step=\"1\" min=\"" + min + "\" class=\"form-control input-xsmall min-locations-control\" placeholder=\"" + min + "\">";
            row += "            </div>";
            row += "            <span>To</span>";
            row += "            <div class=\"form-group\">";
            row += "                <input type=\"number\" value=\"" + max + "\" step=\"1\" min=\"" + (min + 1) + "\" class=\"form-control input-xsmall max-locations-control\" placeholder=\"" + max + "\">";
            row += "            </div>";
            row += "        </form>";
            row += "    </td>";
            row += '<td class="show-small hide-large">Location Discount</td>';
            row += "    <td>";
            row += "        <select class=\"form-control input-small location-discount-control\"></select>";
            row += "    </td>";
            row += '<td class="show-small hide-large">Base Price</td>';
            row += "    <td class=\"base-price-column\">0</td>";
            row += '<td class="show-small hide-large">SMS Charge</td>';
            row += "    <td class=\"sms-charge-column\">0</td>";
            row += '<td class="show-small hide-large">Total Price</td>';
            row += "    <td class=\"total-price-column\">0</td>";
            row += '<td class="show-small hide-large">Location Discount</td>';
            row += "    <td class=\"location-discount-column\">0</td>";
            row += '<td class="show-small hide-large">Upgrade Discount</td>';
            row += "    <td class=\"upgrade-discount-column\">0</td>";
            row += '<td class="show-small hide-large">Discount Price</td>';
            row += "    <td class=\"discount-price-column\">0</td>";
            row += '<td class="show-small hide-large">SMS Messages</td>';
            row += "    <td class=\"sms-messages-column\"><span class=\"value\">0</td>";
            row += '<td class="show-small hide-large">SMS Cost</td>';
            row += "    <td class=\"sms-cost-column\">0</td>";
            row += '<td class="show-small hide-large">Profit Per Location</td>';
            row += "    <td class=\"profit-per-location-column\">0</td>";
            row += "</tr>";

            $('#progression-table-rows').append(row);
            $('#progression-table-rows').find('tr td > select.location-discount-control').last().append(options);

            // If this is the second row, enable the remove button
            if ($('#progression-table-rows').find('tr').length === 2) {
                $('#remove-segment-btn').prop('disabled', false);
            }
        }

        function removeSegment() {
            var segment = $('#progression-table-rows').find('tr').last();
            if (segment.length > 0) {
                segment.remove();
            }
            // If this is the last row, disable the remove button
            if ($('#progression-table-rows').find('tr').length < 2) {
                var e = $('#remove-segment-btn');
                $('#remove-segment-btn').prop('disabled', true);
            }
        }

        function syncPricingPlan() {
            $('input[id="base-price-control"]').trigger("change");
            $('input[id="charge-per-sms-control"]').trigger("change");
            $('select[id="upgrade-discount-control"]').trigger("change");
            $('input[id="cost-per-sms-control"]').trigger("change");
            $('input[id="max-sms-messages-control"]').trigger("change");
        }

        function rebuildProgression(options) {
            /* Init progression */
            $('#progression-table-rows').empty();

            for (var i = 0; i < maxProgressionSegments; i++) {
                var min = (i * maxProgressionSegments) + 1;
                var max = ((i + 1) * maxProgressionSegments);
                addSegment(min, max, options);
            }

            /* Synchronize pricing plan values to columns */
            syncPricingPlan();

        }

        function initPricingPlanControls(options) {

            /* Init drop downs with a percentage range */
            $('#upgrade-discount-control').append(options);
            $('#annual-discount-control').append(options);

            /* Init switch toggle bindings */
            initSwitchToggleBindings(options);

            /* Init value field bindings */
            initValueBindings(options);
            var val = document.getElementById('max-sms-messages-control').value;
            /* Rebuild slider */
            rebuildSmsSlider(val, 50);
            //right here

            refreshSmsSliderControls(val);

        }

        function initProgressionControls(options) {
            appendDiscountPercentage(options);

            /* Init progression button controls */
            $('#cancel-btn').click(function () {
                window.location.href = "/businessPricingPlan";
            });
            $('#start-over-btn').click(function () {
                rebuildProgression(options);
            });
            $('#add-segment-btn').click(function () {
                var minMax = findLocationRange();
                addSegment(minMax.min, minMax.max, options);
                syncPricingPlan();
            });
            $('#remove-segment-btn').click(function () {
                removeSegment();
            });
            $('#save-plan-btn').click(function () {
                savePricingProfile(true);
            });
            $('#update-plan-btn').click(function () {
                savePricingProfile(false);
            });

        }

        function appendDiscountPercentage(options) {
            $('#progression-table-rows').find('tr td > select.location-discount-control').append(options);
        }

        function init() {

            /* Init pricing profile editor */
            $('#summernote_1').summernote({
                height: 300,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']]
                ]
            });

            /* Generate percentage range options */
            var options = generatePercentageOptions();

            /* Init pricing plan controls */
            initPricingPlanControls(options);

            /* Init progression controls */
            initProgressionControls(options);

            //this sets the dropdown value from the passed in value..after the options are added to the dropdown
            //I could have done this in the template too.. but this seems cleaner since the options aren't rendered from an
            //each statement above
            var initialFreeSMSValue = $('#hidden-free-sms-messages').val();
            $('#free-sms-messages-control option').each(function(){
                if(parseInt($(this).attr('value'),10) == parseInt(initialFreeSMSValue)) $(this).attr('selected','selected');
            });

            $('#max-sms-messages-control').bind('change blur',function(){
                //set the steps
                rebuildSmsSlider(parseInt($(this).val()),parseInt($(this).attr('step')));
                //set the value
                refreshSmsSliderControls(parseInt($(this).val()));
            });

            $('#max-sms-messages-control').trigger('change');

            $('#sms-messages-slider').bind('input propertychange paste change',function(){
                refreshDisplayPrices();
            });
            //initially refresh the prices
            refreshDisplayPrices();


        }

        init();

    });
</script>
