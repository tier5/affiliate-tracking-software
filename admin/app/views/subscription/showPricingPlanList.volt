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
                        <div class="caption font-dark">
                            <i class="fa fa-money"></i>
                            <span class="caption-subject bold uppercase">Subscriptions</span>
                        </div>
                        <div class="tools">
                            <a href="" class="btn default btn-lg apple-backgound subscription-btn">Create Subscription</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline collapsed" width="100%" id="sample_1" role="grid" aria-describedby="sample_1_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th>Subscription Name</th>
                                            <th>Subscription Type</th>
                                            <th>Active/Inactive</th>
                                            <th>Pricing Page</th>
                                            <th>Free Sign Up Link</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>    
                                        <tr role="row" class="odd">
                                            <td>Zach's Subscription</td>
                                            <td>Paid</td>
                                            <td><input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info"></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-edit"></i></a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        <tr role="row" class="odd">
                                            <td>Zach's Subscription</td>
                                            <td>Paid</td>
                                            <td><input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info"></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-edit"></i></a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        <tr role="row" class="odd">
                                            <td>Zach's Subscription</td>
                                            <td>Paid</td>
                                            <td><input type="checkbox" class="make-switch" checked="" data-on-color="primary" data-off-color="info"></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-edit"></i></a></td>
                                            <td><a href="" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-trash"></i></a></td>
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