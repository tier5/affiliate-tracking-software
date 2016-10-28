<?php

    $AgencyOrBusiessType = $this->view->agency_type_id == 1 ? "Agency" : "Business";
    $BackUrl = $AgencyOrBusiessType == 1 ? '/agency' : '/admindashboard/list/2';
    $BackUrl = $loggedUser->is_admin ? "/admindashboard/list/1" : $BackUrl;

?>
<ul class="pager">
    <li class="previous pull-left">
        <a href="<?=$BackUrl; ?>" class="btn red btn-outline">&larr; Go Back</a>
    </li>
</ul>

{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-user">
            <i class="icon-settings fa-user"></i>
            <span class="caption-subject bold uppercase">Edit <?=$AgencyOrBusiessType; ?> </span>
        </div>
    </div>
    <div class="portlet-body form">
        <form class="form-horizontal validated" role="form" id="agencyform" method="post" autocomplete="off">
            <div class="form-group">
                <label for="subscription_pricing_plan_id" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Subscription Pricing Plan</label>
                <div class="col-md-8">
                    {{ subscriptionPricingPlans }}
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Name</label>
                <div class="col-md-8">
                    {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name','required':'']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Email</label>
                <div class="col-md-8">
                    {{ form.render("email", ["class": 'form-control', 'placeholder': 'Email', 'type': 'name','required':'']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Phone</label>
                <div class="col-md-8">
                    {{ form.render("phone", ["class": 'form-control', 'placeholder': 'Phone', 'type': 'name']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Address</label>
                <div class="col-md-8">
                    {{ form.render("address", ["class": 'form-control', 'placeholder': 'Address', 'type': 'name']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="locality" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> City</label>
                <div class="col-md-8">
                    {{ form.render("locality", ["class": 'form-control', 'placeholder': 'City', 'type': 'name']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="state_province" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> State/Province</label>
                <div class="col-md-8">
                    {{ form.render("state_province", ["class": 'form-control', 'placeholder': 'State/Province', 'type': 'name']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="postal_code" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Postal Code</label>
                <div class="col-md-8">
                    {{ form.render("postal_code", ["class": 'form-control', 'placeholder': 'Postal Code', 'type': 'name']) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-4 col-md-8">
                    {{ submit_button("Save", "class": "btn btn-big btn-success") }}
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        $('#send-registration-email-control').change(function () {
            if ($(this).val() == 0) {
                $(".free_subscription_pricing_plan").addClass('show');
            } else {
                $(".free_subscription_pricing_plan").removeClass('show');
            }
        });

        $('#subscription_pricing_plan_id').change(function () {
            if ($(this).val() == 0) {
                $(".free_subscription_pricing_plan").addClass('show');
            } else {
                $(".free_subscription_pricing_plan").removeClass('show');
            }
        });

        $('.validated').validate();

    });
</script>
