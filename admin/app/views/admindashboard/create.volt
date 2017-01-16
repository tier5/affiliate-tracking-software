<?php


    $CreateType = $agency_type_id == 1 ? 'Agency' : 'Business';
    if($loggedUser->is_admin) {
        $BackUrl = $CreateType == 'Business' ? '/admindashboard/list/2' : '/admindashboard/list/1';
    } else {
        $BackUrl = '/agency';
    }
?>
<ul class="pager">
    <li class="previous pull-left">
        <a href="<?=$BackUrl; ?>" class="btn red btn-outline">&larr; Go Back</a>
    </li>
</ul>
{{ content() }}

<?php if(!isset($isSuccess)) { ?>

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-user">
            <i class="icon-settings fa-user"></i>
            <span class="caption-subject bold uppercase"> Create <?=$AgencyOrBusiessType; ?> </span>
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
                <label for="name" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> <?=$CreateType?> Name</label>
                <div class="col-md-8">
                    {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name','required':'']) }}
                </div>
            </div>
            <?php if($CreateType == 'Business') { ?>
                <div class="form-group">
                    <label for="admin_name" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Admin Full Name</label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" placeholder="Admin Full Name" name="admin_name" required value="<?=(isset($_POST['admin_name'])?$_POST["admin_name"]:'')?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="admin_email" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Admin Email</label>
                    <div class="col-md-8">
                        <input class="form-control" type="email" placeholder="Admin Email" name="admin_email" required value="<?=(isset($_POST['admin_email'])?$_POST["admin_email"]:'')?>" />
                    </div>
                </div>
                <div class="free_subscription_pricing_plan show">
                    <hr/>
                    <h4>Free Subscription Plan</h4>
                    <div class="form-group">
                        <label for="locations" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Locations</label>
                        <div class="col-md-8">
                            <input
                              class="form-control"
                              type="number"
                              min="0" 
                              value="<?=(!$free_locations) ? 1 : $free_locations ;?>"
                              placeholder="Number of locations"
                              required name="free_locations"
                              />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sms_messages" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> SMS Messages</label>
                        <div class="col-md-8">
                            <input
                              class="form-control"
                              type="number"
                              min="0"
                              placeholder="Number of messages"
                              value="<?=$_POST['sms_messages'] ? $_POST['sms_messages'] : 100; ?>"
                              required name="sms_messages"
                              />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="send_registration_email" class="col-md-4 control-label">Send Registration Email</label>
                    <div class="col-md-8">
                        <input id="send-registration-email-control" type="checkbox" name="send_registration_email" class="make-switch" checked data-on-color="primary" data-off-color="info">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-8">
                        <input type="button" value="Save"  class="btn saveBtn btn-big btn-success" />
                    </div>
                </div>
                

            <?php } else { // Start form for agency ?>
            
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
                <?php if (CreateOrEdit > 0) { ?>
    
                <?php } else { ?>
                    <div class="free_subscription_pricing_plan show">
                        <hr/>
                        <h4>Free Subscription Plan</h4>
                        <div class="form-group">
                            <label for="locations" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Locations</label>
                            <div class="col-md-8">
                                <input
                                  class="form-control"
                                  type="number"
                                  min="0" 
                                  value="<?=(!$free_locations) ? 1 : $free_locations ;?>"
                                  placeholder="Number of locations"
                                  required name="free_locations"
                                  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_messages" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> SMS Messages</label>
                            <div class="col-md-8">
                                <input
                                  class="form-control"
                                  type="number"
                                  min="0"
                                  placeholder="Number of messages"
                                  value="<?=$_POST['sms_messages'] ? $_POST['sms_messages'] : 100; ?>"
                                  required name="sms_messages"
                                  />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <h4>Create Administrator</h4>
                    <div class="form-group">
                        <label for="admin_name" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Admin Full Name</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" placeholder="Admin Full Name" name="admin_name" required value="<?=(isset($_POST['admin_name'])?$_POST["admin_name"]:'')?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="admin_email" class="col-md-4 control-label"><?=$AgencyOrBusiessType; ?> Admin Email</label>
                        <div class="col-md-8">
                            <input class="form-control" type="email" placeholder="Admin Email" name="admin_email" required value="<?=(isset($_POST['admin_email'])?$_POST["admin_email"]:'')?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_registration_email" class="col-md-4 control-label">Send Registration Email</label>
                        <div class="col-md-8">
                            <input id="send-registration-email-control" type="checkbox" name="send_registration_email" class="make-switch" checked data-on-color="primary" data-off-color="info">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-8">
                        {{ submit_button("Save", "class": "btn btn-big btn-success") }}
                    </div>
                </div>
            <?php } //agency part end ?>
        </form>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        
        $('.saveBtn').click(function(){
            var email = $('input[name="admin_email"]').val();
                $.ajax({
                    url: '/agency/emailisexist/' + email,
                    success:function(res){
                        if(res != 'exist'){
                            $('#agencyform').submit();
                        }else{
                            $('#admin_email-error').html('This email is already taken, try another one.').show();
                        }
                        
                    }
                });
        });

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
