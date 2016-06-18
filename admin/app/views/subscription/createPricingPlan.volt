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
                                        <label class="control-label">Number of Free SMS Messages on Trial Account</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control xinput-xsmall">
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
                                        <input type="number" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Charge Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="0.00">
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
                                        <input type="number" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">My Cost Per SMS $</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Max SMS Messages</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control xinput-xsmall">
                                            <option>100</option>
                                            <option>200</option>
                                            <option>300</option>
                                            <option>400</option>
                                            <option>500</option>
                                            <option>600</option>
                                            <option>700</option>
                                            <option>800</option>
                                            <option>900</option>
                                            <option>1000</option>
                                        </select>
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

        /*
         * Here is how you use it
         */
        $(function () {
            $('.view-pdf').on('click', function () {
                var pdf_link = $(this).attr('href');
                var iframe = '<div class="iframe-container"><iframe src="' + pdf_link + '"></iframe></div>'
                $.createModal({
                    title: 'My Title',
                    message: iframe,
                    closeButton: true,
                    scrollable: false
                });
                return false;
            });
        })

    });
</script>