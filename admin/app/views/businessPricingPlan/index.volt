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
                            <a href="/businessPricingPlan/showNewPricingPlan" class="btn default btn-lg apple-backgound subscription-btn">Create Subscription</a>
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
                                        <th class="hide-small">Free Sign Up Link</th>
                                        <th>Edit</th>
                                        <th class="hide-small">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {% for pricingProfile in pricingProfiles %}
                                        <tr id="{{ pricingProfile.id }}" role="row">
                                            <td>{{ pricingProfile.name }}</td>
                                            {% if pricingProfile.enable_trial_account == true %}
                                                <td>Trial</td>
                                            {% else %}
                                                <td>Paid</td>
                                            {% endif %}
                                            {% if pricingProfile.enabled == true %}
                                                <td><input id="subscription{{ pricingProfile.id }}" class="update-enable-pricing-plan-control make-switch" type="checkbox" checked data-on-color="primary" data-off-color="info"></td>
                                            {% else %}
                                                <td><input id="subscription{{ pricingProfile.id }}" class="update-enable-pricing-plan-control make-switch" type="checkbox" data-on-color="primary" data-off-color="info"></td>
                                            {% endif %}
                                            <td><button class="btn default btn-lg apple-backgound subscription-btn" disabled>View Page</button></td>
                                            <td class="hide-small"><a href="http://{{ custom_domain }}.getmobilereviews.com/session/invite/{{ pricingProfile.getShortCode() }}" class="btn default btn-lg apple-backgound subscription-btn">View Page</a></td>
                                            <td><a href="/businessPricingPlan/editExistingPricingPlan/{{ pricingProfile.id }}" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-edit"></i></a></td>
                                            <td><button id="delete-pricing-plan-control" class="btn default btn-lg apple-backgound subscription-btn"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div class="row midnight-background">
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
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<script type="text/javascript">
    jQuery(document).ready(function ($) {



        function refreshDeleteHandlers() {

            $('tr').delegate('#delete-pricing-plan-control', 'click', function(event) {

                if(confirm("About to delete pricing profile! Are you sure?")) {

                    var id = $(event.currentTarget).closest('tr').attr('id');

                    $.ajax({
                        url: "/businessPricingPlan/deletePricingPlan/" + $(this).closest('tr').attr('id'),
                        type: 'DELETE',
                        success: function(data) {
                            if(data.status === true) {
                                $(event.currentTarget).closest('tr').remove();;
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Unable to delete pricing profile!");
                        }
                    });

                }

            });

        }

        function refreshSwitchHandlers() {

            $('input.update-enable-pricing-plan-control').on('switchChange.bootstrapSwitch', function (event, state) {
                console.log('the state is',state);
                var id = $(event.currentTarget).closest('tr').attr('id');
                $.ajax({
                    url: "/businessPricingPlan/updateEnablePricingPlan/" + id + "/" + state,
                    type: 'PUT',
                    success: function(data) {
                        if(data.status !== true) {
                            $(event.currentTarget).setState(!state);
                            alert(data.message);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Unable to delete pricing profile!");
                    }
                });

            });
        }
        refreshDeleteHandlers();
        refreshSwitchHandlers();

    });
</script>