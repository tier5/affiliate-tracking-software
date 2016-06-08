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
                    <a href="/admin/subscription/" class="btn default btn-lg apple-backgound subscription-btn">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet light bordered invoices">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <span class="caption-subject bold uppercase">Recent Payment</span>
                        </div>
                        <div class="tools"> </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="dt-buttons">
                                        <a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="sample_1"><span>Print</span></a>
                                        <a class="dt-button buttons-pdf buttons-html5 btn green btn-outline" tabindex="0" aria-controls="sample_1"><span>PDF</span></a>
                                        <a class="dt-button buttons-csv buttons-html5 btn purple btn-outline" tabindex="0" aria-controls="sample_1"><span>CSV</span></a>
                                    </div>
                                </div>
                            </div>-->
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline collapsed" width="100%" id="sample_1" role="grid" aria-describedby="sample_1_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <!-- <th class="all sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="First name: activate to sort column descending" style="width: 121px;" aria-sort="ascending">First name</th>
                                            <th class="min-phone-l sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Last name: activate to sort column ascending" style="width: 119px;">Last name</th>
                                            <th class="min-tablet sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 237px;">Position</th>
                                            <th class="desktop sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 81px;">Salary</th>-->
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Subscription Id</th>
                                            <th>Term/Status</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Invoice</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>    
                                        <tr role="row" class="odd">
                                            <td>Payment Made</td>
                                            <td>5/25/2016</td>
                                            <td>TS-81881805-81881805</td>
                                            <td>Pay Monthly</td>
                                            <td>Business Monthly</td>
                                            <td>USD 24.00</td>
                                            <td>
                                                <button class="btn default btn-lg apple-backgound subscription-btn view-pdf">View</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="" data-target="">PDF</button>
                                            </td>
                                        </tr>
                                        <tr role="row" class="even">
                                            <td>Payment Made</td>
                                            <td>5/25/2016</td>
                                            <td>TS-81881805-81881805</td>
                                            <td>Pay Monthly</td>
                                            <td>Business Monthly</td>
                                            <td>USD 24.00</td>
                                            <td>
                                                <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="" data-target="">View</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="" data-target="">PDF</button>
                                            </td>
                                        </tr>
                                        <tr role="row" class="odd">
                                            <td>Payment Made</td>
                                            <td>5/25/2016</td>
                                            <td>TS-81881805-81881805</td>
                                            <td>Pay Monthly</td>
                                            <td>Business Monthly</td>
                                            <td>USD 24.00</td>
                                            <td>
                                                <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="" data-target="">View</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn default btn-lg apple-backgound subscription-btn" data-toggle="" data-target="">PDF</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row midnight-background">
                                <div class="col-md-6 col-sm-6">
                                    <div class="dataTables_paginate paging_bootstrap_number" id="sample_1_paginate">
                                        <ul class="pagination" style="visibility: visible;">
                                            <li class="prev">
                                                <a href="#" title="Prev"><i class="fa fa-angle-left"></i></a>
                                            </li>
                                            <li><a href="#">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li class="active"><a href="#">4</a></li>
                                            <li><a href="#">5</a></li>
                                            <li><a href="#">6</a></li>
                                            <li class="next"><a href="#" title="Next"><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="numberSelect">
                                        <select name="sample_1_length" aria-controls="sample_1" class="form-control input-sm input-xsmall input-inline">
                                            <option value="5">5</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="-1">All</option>
                                        </select>
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

        /*
        * Here is how you use it
        */
        $(function(){    
            $('.view-pdf').on('click',function(){
                var pdf_link = $(this).attr('href');
                var iframe = '<div class="iframe-container"><iframe src="'+pdf_link+'"></iframe></div>'
                $.createModal({
                    title:'My Title',
                    message: iframe,
                    closeButton:true,
                    scrollable:false
                });
                return false;        
            });    
        })

    });
</script>