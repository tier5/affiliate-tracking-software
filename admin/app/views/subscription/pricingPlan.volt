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
                            <span class="caption-subject bold uppercase">Subscription Name: <span class="caption-subject uppercase">Zach's Subscription</span></span>
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
                                        <input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Free SMS Messages on Trial Account</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control input-small">
                                            <option>10</option>
                                            <option>20</option>
                                            <option>30</option>
                                            <option>40</option>
                                            <option>50</option>
                                            <option>60</option>
                                            <option>70</option>
                                            <option>80</option>
                                            <option>90</option>
                                            <option>100</option>
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
                                        <input type="number" step="0.01" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Charge Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" class="form-control" placeholder="0.00">
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
                                        <input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Upgrade Discount %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="upgrade-discount-select" class="form-control input-small"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">My Cost Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Max SMS Messages</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="selector-messages" type="number" value="1" step="50" min="0" class="form-control" placeholder="1">
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
                                        <input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Annual Discount %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="annual-discount-select" class="form-control input-small"></select>
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
                                                        <div name="summernote" id="summernote_1"></div>
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
                                                            <th>Discount</th> 
                                                            <th>Base Price</th>
                                                            <th>SMS Charge</th>
                                                            <th>Total Price</th>
                                                            <th>Location Discount</th>
                                                            <th>Upgrade Discount</th>
                                                            <th>Discount Price</th>
                                                            <th>SMS Messages</th>
                                                            <th>SMS Cost</th>
                                                            <th>Profit Per Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="progression-table-rows"></tbody>
                                                </table>
                                            </div>
                                            <div class="row progression-controls">
                                                <div class="col-md-6 col-sm-6"></div>
                                                <div class="col-md-6 col-sm-6">
                                                    <a href="" id="remove-segment-btn" class="btn default btn-lg apple-backgound subscription-btn">Remove Last</a>  
                                                    <a href="" id="add-segment-btn" class="btn default btn-lg apple-backgound subscription-btn">Add New</a>
                                                    <a href="" id="save-progression-btn" class="btn default btn-lg apple-backgound subscription-btn">Save</a>
                                                    <a href="" id="start-over-btn" class="btn default btn-lg apple-backgound subscription-btn">Start Over</a>
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

        var progressionTouched = false;

        function populatePercentageBasedDropdowns() {

            for (var i = 1; i <= 100; i++) {
                $('#upgrade-discount-select').append($('<option>', {
                    value: i,
                    text: i.toString()
                }));
                $('#annual-discount-select').append($('<option>', {
                    value: i,
                    text: i.toString()
                }));
            }

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
                $('#selector-messages').val(smsMessagesSlider.getValue()).change();
            });
            $('#selector-messages').on('change', function (e) {
                smsMessagesSlider.setValue(parseInt(this.value), true, true);
                $('#slider-messages').text(this.value);
            });

            smsMessagesSlider.setValue(100, true, true);

            /* Message init */
            $('#slider-messages').text(smsMessagesSlider.getValue());

        }

        function addSegment(min, max) {

            var row = "";
            row += "<tr role=\"row\" class=\"odd\">";
            row += "    <td>";
            row += "        <form class=\"form-inline\" role=\"form\">";
            row += "            <div class=\"form-group\">";
            row += "                <input type=\"number\" value=\"" + min + "\" step=\"1\" min=\"" + min + "\" class=\"form-control input-xsmall\" placeholder=\"" + min + "\">";
            row += "            </div>";
            row += "            <span>To</span>";
            row += "            <div class=\"form-group\">";
            row += "                <input type=\"number\" value=\\" + max + "\" step=\"1\" min=\"" + (min + 1) + "\" class=\"form-control input-xsmall\" placeholder=\"" + max + "\">";
            row += "            </div>";
            row += "        </form>";
            row += "    </td>";
            row += "    <td>";
            row += "        <input type=\"number\" value=\"0\" step=\"1\" min=\"0\" max=\"100\" class=\"form-control input-xsmall\" placeholder=\"0\">";
            row += "    </td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "    <td>0</td>";
            row += "</tr>";

            $('#progression-table-rows').append(row);

            progressionTouched = true;

        }

        function initProgression() {

            var maxProgressionSegments = 10;

            $('#progression-table-rows').empty();

            for (var i = 0; i < 10; i++) {

                var min = (i * maxProgressionSegments) + 1;
                var max = ((i + 1) * maxProgressionSegments);
                addSegment(min, max);

            }

            progressionTouched = false;

        }

        function findMinMax() {
            var last = $('#progression-table-rows').find('tr').last();
            return {min: last.find('td input:nth-child(1)').attr('min'), max: last.find('td input:nth-child(2)').attr('min')};
        }

        function removeSegment() {
            $('#progression-table-rows').find('tr').last().remove;
            progressionTouched = false;
        }

        function initProgressionControls() {

            /* Progression control */
            $('#start-over-btn').click(function () {
                if (progressionTouched) {
                    initProgression();
                }
            });
            $('#add-segment-btn').click(function () {
                var minMax = findMinMax();
                addSegment(minMax.min, minMax.max);
            });
            $('#remove-segment-btn').click(function () {
                removeSegment();
            });
            $('#save-progression-btn').click(function () {

            });

        }

        function initPricingProfileParameters() {

            /* Init pricing profile editor */
            $('#summernote_1').summernote({
                height: 300,
                toolbar: [ 
                    ['font', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']]
                ]
            });

            /* Init drop downs */
            populatePercentageBasedDropdowns();

            /* Init slider */
            initSmsSlider();

            /* Init progression */
            initProgression();

            /* Init progression controls */
            initProgressionControls();

        }

        initPricingProfileParameters();

    });
</script>