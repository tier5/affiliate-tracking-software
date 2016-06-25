{{ content() }}
<header class="jumbotron subhead" id="reviews">
    <div class="hero-unit">
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <!-- BEGIN PAGE TITLE-->
                <h3 class="page-title">
                    Subscriptions   <small>for business</small>
                </h3>
                <!-- END PAGE TITLE-->
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
                        <div class="caption font-dark subscription-name">
                            <span class="caption-subject bold uppercase subscription-name-caption">Subscription Name:    </span>
                            <input id="name-control" type="text" value="{{ name }}" class="caption-subject" placeholder="Subscription Name">
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
                                        <select id="free-sms-messages-control" class="form-control input-small" value="{{ maxMessagesOnTrialAccount }}">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="60">60</option>
                                            <option value="70">70</option>
                                            <option value="80">80</option>
                                            <option value="90">90</option>
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
                                        <input id="base-price-control" type="number" value="{{ basePrice }}" step="0.01" min="0.00" class="form-control" placeholder="0.00">
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
                                        <select id="upgrade-discount-control" class="form-control input-small" value="{{ updgradeDiscount }}"></select>
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
                                        <select id="annual-discount-control" class="form-control input-small" value="{{ annualDiscount }}"></select>
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
                                                            <th>Locations</th>
                                                            <th>Discount %</th> 
                                                            <th>Base Price $</th>
                                                            <th>SMS Charge $</th>
                                                            <th>Total Price $</th>
                                                            <th>Location Discount $</th>
                                                            <th>Upgrade Discount $</th>
                                                            <th>Discount Price $</th>
                                                            <th>SMS Messages</th>
                                                            <th>SMS Cost $</th>
                                                            <th>Profit Per Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="progression-table-rows"></tbody>
                                                </table>
                                            </div>
                                            <div class="row progression-controls">
                                                <div class="col-md-6 col-sm-6"></div>
                                                <div class="col-md-6 col-sm-6">
                                                    <button id="remove-segment-btn" class="btn default btn-lg apple-backgound subscription-btn">Remove Last</button>  
                                                    <button id="add-segment-btn" class="btn default btn-lg apple-backgound subscription-btn">Add New</button>
                                                    <button id="save-progression-btn" class="btn default btn-lg apple-backgound subscription-btn">Save</button>
                                                    <button id="start-over-btn" class="btn default btn-lg apple-backgound subscription-btn">Start Over</button>
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
    
        function generatePercentageOptions() {
            var options = "";
            for (var i = 1; i <= 100; i++) {
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
            
            return {
                min: min, 
                max: max
            };
        }
        
        function getValueParameters() {
            
            return {
                name: $('input[id="name-control"]').val(),
                enableTrialAccount: $('input[id="enable-trial-account-control"]').val() === "on" ? true : false,
                enableDiscountOnUpgrade: $('input[id="enable-discount-on-upgrade-control"]').val() === "on" ? true : false,
                basePrice: $('input[id="base-price-control"]').val(),
                costPerSms: $('input[id="cost-per-sms-control"]').val(),
                maxMessagesOnTrialAccount: $('select[id="free-sms-messages-control"]').val(),
                upgradeDiscount: $('select[id="upgrade-discount-control"]').val(),
                chargePerSms: $('input[id="charge-per-sms-control"]').val(), 
                maxSmsMessages: $('input[id="max-sms-messages-control"]').val(),
                enableAnnualDiscount: $('input[id="enable-annual-discount-control"]').val() === "on" ? true : false,     
                annualDiscount: $('select[id="annual-discount-control"]').val(),
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
                    upgradeDiscount: $(this).find('td.upgrade-discount-column').first().text(),
                    smsMessages: $(this).find('td.sms-messages-column').first().text(),
                    smsCost: $(this).find('td.sms-cost-column').first().text(),
                    profitPerLocation: $(this).find('td.profit-per-location-column').first().text()
                };
            });
        
            return progressionDetails;
            
        }
        
        function savePricingProfile() {
            var parameters = {};
            $.extend(parameters, getValueParameters(), getProgressionDetails());

            $.post("/businessPricingPlan/savePricingPlan", 
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

        function initValueBindings(options) {

            $('input[id="base-price-control"]').on('change', function (event) {
                $('#progression-table-rows').find('tr td.base-price-column').each(function (index) {
                    $(this).text($(event.currentTarget).val()); // Update the cell value
                });
            });
            
            $('input[id="charge-per-sms-control"]').on('change', function (event) {
                $('#progression-table-rows').find('tr td.sms-charge-column').each(function (index) {
                    $(this).text($(event.currentTarget).val()); // Update the cell value    
                });
            });
            
            $('select[id="upgrade-discount-control"]').on('change', function (event) {
                $('#progression-table-rows').find('tr td.upgrade-discount-column').each(function (index) {
                    $(this).text($(event.currentTarget).val()); // Update the cell value    
                });
            });
            
            $('input[id="cost-per-sms-control"]').on('change', function (event) {
                $('#progression-table-rows').find('tr td.sms-cost-column').each(function (index) {
                    $(this).text($(event.currentTarget).val()); // Update the cell value    
                });
            });
            
            $('input[id="max-sms-messages-control"]').on('change', function (event) {
                $('#progression-table-rows').find('tr td.sms-messages-column').each(function (index) {
                    $(this).text($(event.currentTarget).val()); // Update the cell value    
                });
            });
            
        }

        function initSmsSlider() {

            var smsMessagesSlider = new Slider("#sms-messages-slider", {
                tooltip: 'show',
                min: 50,
                max: 1001,
                step: 50,
                ticks: [50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000],
                ticks_labels: [
                    '<div>50</div><div class="tick-marker">|</div>',
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

            smsMessagesSlider.on('change', function () {
                $('#slider-messages').text(smsMessagesSlider.getValue());
                $('#max-sms-messages-control').val(smsMessagesSlider.getValue()).change();
            });
            $('#max-sms-messages-control').on('change', function (e) {
                smsMessagesSlider.setValue(parseInt(this.value), true, true);
                $('#slider-messages').text(this.value);
            });

            smsMessagesSlider.setValue(100, true, true);

            /* Message init */
            $('#slider-messages').text(smsMessagesSlider.getValue());

        }

        function addSegment(min, max, options) {

            var row = "";
            row += "<tr role=\"row\" class=\"odd\">";
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
            row += "    <td>";
            row += "        <select class=\"form-control input-small location-discount-control\"></select>";
            row += "    </td>";
            row += "    <td class=\"base-price-column\">0</td>";
            row += "    <td class=\"sms-charge-column\">0</td>";
            row += "    <td class=\"total-price-column\">0</td>";
            row += "    <td class=\"location-discount-column\">0</td>";
            row += "    <td class=\"upgrade-discount-column\">0</td>";
            row += "    <td class=\"discount-price-columns\">0</td>";
            row += "    <td class=\"sms-messages-column\">0</td>";
            row += "    <td class=\"sms-cost-column\">0</td>";
            row += "    <td class=\"profit-per-location-column\">0</td>";
            row += "</tr>";

            $('#progression-table-rows').append(row);
            $('#progression-table-rows').find('tr td > select.location-discount-control').last().append(options);
                
        }

        function removeSegment() {
            var segment = $('#progression-table-rows').find('tr').last();
            if (segment.length > 0) {
                segment.remove();
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

            /* Init slider */
            initSmsSlider();

        }

        function initProgressionControls(options) {

            /* Rebuild the progression */
            rebuildProgression(options);

            /* Init progression button controls */
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
            $('#save-progression-btn').click(function () {
                savePricingProfile();
            });

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

        }

        init();

    });
</script>