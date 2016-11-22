{% set GeneralExpanded = 'active' %}
{% if tab == 'Stripe' %}
    {% set StripeExpanded = 'active' %}
    {% set GeneralExpanded = '' %}
{% endif %}

<header class="jumbotron subhead <?=(isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business'?'':'agency')?>settingspage" id="reviews">
    <div class="hero-unit">

        <div class="row">
            <div class="col-md-5 col-sm-5">
                <!-- BEGIN PAGE TITLE-->
                <h3 class="page-title"> Settings </h3>
                <!-- END PAGE TITLE-->
            </div>
            <?php
      if (isset($this->session->get('auth-identity')['agencytype']) &&
            $this->session->get('auth-identity')['agencytype'] == 'business') {
            if ($is_upgrade) {
            $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) *
            100, 0, '.', ''):100);
            if ($percent > 100) $percent = 100;
            ?>
            <div class="col-md-7 col-sm-7">
                <div class="sms-chart-wrapper">
                    <div class="title">SMS Messages Sent</div>
                    <div class="bar-wrapper">
                        <div class="bar-background"></div>
                        <div class="bar-filled" style="width: {{ percent }}%;"></div>
                        <div class="bar-percent" style="padding-left: {{ percent }}%;"><?=$percent?>%</div>
                        <div class="bar-number" style="margin-left: {{ percent }}%;">
                            <div class="ball"><?=$sms_sent_this_month_total?></div>
                            <div class="bar-text"
                            <?=($percent>60?'style="display: none;"':'')?>>This Month
                        </div>
                    </div>
                </div>
                <div class="end-title">{{ total_sms_month }} ({{ non_viral_sms }} / {{ viral_sms }})<br/><span class="goal">Allowed</span></div>
            </div>
        </div>
        <?php
        } else {
          $percent = ($total_sms_needed > 0 ? number_format((float)($sms_sent_this_month / $total_sms_needed) * 100, 0,
        '.', ''):100);
        if ($percent > 100) $percent = 100;
        ?>
        <div class="col-md-7 col-sm-7">
            <div class="sms-chart-wrapper">
                <div class="title">SMS Messages Sent</div>
                <div class="bar-wrapper">
                    <div class="bar-background"></div>
                    <div class="bar-filled" style="width: <?=$percent?>%;"></div>
                    <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
                    <div class="bar-number" style="margin-left: <?=$percent?>%;">
                        <div class="ball"><?=$sms_sent_this_month?></div>
                        <div class="bar-text"
                        <?=($percent>60?'style="display: none;"':'')?>>This Month
                    </div>
                </div>
            </div>
            <div class="end-title"><?=$total_sms_needed?><br/><span class="goal">Goal</span></div>
        </div>
    </div>
    <?php
        }
      } //end checking for business vs agency
      ?>
    </div>

    {{ content() }}

    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" method="post" autocomplete="off" enctype="multipart/form-data" id="settingsform">
                <ul class="nav nav-tabs">
                    <li class="{{ GeneralExpanded }}"><a href="#tab_general" data-toggle="tab"> General </a></li>
                    <li><a href="#tab_review_invite" data-toggle="tab"> Review Invite </a></li>
                    <li><a href="#tab_sms_message" data-toggle="tab"> SMS Message </a></li>
                    <li><a href="#tab_white_label" data-toggle="tab" <?=(isset($this->
                        session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business'?'style="display: none;"':'')?>>
                        White Label </a></li>
                    <li><a href="#tab_twilio" data-toggle="tab" <?=(isset($this->
                        session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business'?'style="display: none;"':'')?>>
                        Twilio </a></li>
                    <li class="{{ StripeExpanded }}"><a href="#tab_stripe" data-toggle="tab" <?=(isset($this->
                        session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business'?'style="display: none;"':'')?>>
                        Stripe </a></li>
                    <li class=""><a href="#tab_intercom" data-toggle="tab" <?=(isset($this->
                        session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business'?'style="display: none;"':'')?>>
                        Intercom </a></li>
                    <li><a href="#tab_notification" data-toggle="tab" <?=(isset($this->
                        session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business'?'':'style="display: none;"')?>>
                        Notifications </a></li>
                </ul>
                <div class="tab-content">
                    <!-- START General Settings  -->
                    <div class="tab-pane fade {{ GeneralExpanded }} in" id="tab_general">


                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Business Name</label>
                            <div class="col-md-8">
                                {{ agencyform.render("name", ["class": 'form-control', 'placeholder': 'Business Name', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Business Email</label>
                            <div class="col-md-8">
                                {{ agencyform.render("email", ["class": 'form-control', 'placeholder': 'Email', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-md-4 control-label">Business Phone</label>
                            <div class="col-md-8">
                                {{ agencyform.render("phone", ["class": 'form-control', 'placeholder': 'Phone', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-md-4 control-label">Business Address</label>
                            <div class="col-md-8">
                                {{ agencyform.render("address", ["class": 'form-control', 'placeholder': 'Address', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-md-4 control-label">Business Address 2</label>
                            <div class="col-md-8">
                                {{ agencyform.render("address2", ["class": 'form-control', 'placeholder': 'Address2', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="locality" class="col-md-4 control-label">Business City</label>
                            <div class="col-md-8">
                                {{ agencyform.render("locality", ["class": 'form-control', 'placeholder': 'City', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state_province" class="col-md-4 control-label">Business State/Province</label>
                            <div class="col-md-8">
                                {{ agencyform.render("state_province", ["class": 'form-control', 'placeholder': 'State/Province', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state_province" class="col-md-4 control-label">Business Country</label>
                            <div class="col-md-8">
                                {{ agencyform.render("country", ["class": 'form-control', 'placeholder': 'Country', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="col-md-4 control-label">Business Zip / Postal Code</label>
                            <div class="col-md-8">
                                {{ agencyform.render("postal_code", ["class": 'form-control', 'placeholder': 'Postal Code', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="col-md-4 control-label">Business Website</label>
                            <div class="col-md-8">
                                {{ agencyform.render("website", ["class": 'form-control', 'placeholder': 'Website', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="col-md-4 control-label">Email From Name</label>
                            <div class="col-md-8">
                                {{ agencyform.render("email_from_name", ["class": 'form-control', 'placeholder': 'Email From Name', 'type': 'name']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="col-md-4 control-label">From Email Address</label>
                            <div class="col-md-8">
                                {{ agencyform.render("email_from_address", ["class": 'form-control', 'placeholder': 'From Email Address', 'type': 'name']) }}
                            </div>
                        </div>
                    </div>
                    <!-- END General Settings  -->


                    <!-- START Review Invite Settings  -->
                    <div class="tab-pane fade" id="tab_review_invite">
                        <div class="form-group">
                            <div class="row">
                                <label for="review_invite_type_id" class="col-md-4 control-label">Review Invite
                                    Type</label>
                                <div class="col-md-8">
                                    <div id="image_container">
                                        <img src="/img/feedback_request.png" data-id="1" <?=(isset($_POST['review_invite_type_id']) && $_POST['review_invite_type_id'] == 1?' class="selected"':(isset($objAgency->
                                        review_invite_type_id) && $objAgency->review_invite_type_id == 1?'
                                        class="selected"':''))?> />
                                        <img src="/img/stars.png" data-id="2" <?=(isset($_POST['review_invite_type_id']) && $_POST['review_invite_type_id'] == 2?' class="selected"':(isset($objAgency->
                                        review_invite_type_id) && $objAgency->review_invite_type_id == 2?'
                                        class="selected"':''))?> />
                                        <img src="/img/nps.png" data-id="3" <?=(isset($_POST['review_invite_type_id']) && $_POST['review_invite_type_id'] == 3?' class="selected"':(isset($objAgency->
                                        review_invite_type_id) && $objAgency->review_invite_type_id == 3?'
                                        class="selected"':''))?> />
                                    </div>
                                    <input id="review_invite_type_id" name="review_invite_type_id" type="hidden" value="<?=(isset($_POST['review_invite_type_id'])?$_POST["review_invite_type_id"]:(isset($objAgency->review_invite_type_id)?$objAgency->review_invite_type_id:''))?>"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>

                        <?php
        //make sure we are at the location level settings
        if (isset($this->session->get('auth-identity')['agencytype']) &&
                        $this->session->get('auth-identity')['agencytype'] == 'business') {
                        $has_facebook = false;
                        ?>
                        <div class="form-group">
                            <div class="row">
                                <label for="rating_threshold_star" class="col-md-4 control-label">
                                    Review Order
                                    <i>This is the order of the site links on the SMS review page.</i>
                                </label>
                                <div class="col-md-8">
                                    <ul id="sortable">
                                        <?php
                if (isset($review_site_list)) {
                  foreach($review_site_list as $rsl) {
                    if ($rsl->review_site_id == \Vokuro\Models\Location::TYPE_FACEBOOK) $has_facebook = true;
                                          ?>
                        <li class="ui-state-default" id='<?=$rsl->location_review_site_id?>'>
                            <span class="site-wrapper"><img src="<?=$rsl->review_site->icon_path?>" class="imgicon"/> <?=$rsl->
                                review_site->name?></span>
                      <span class="review_site-buttons">
                        <?php if ($rsl->review_site_id <= 3) { ?>
                          <a class="btnLink" href="<?=$rsl->review_site->base_url?><?=$rsl->external_id?>" target="_blank"><img src="/img/icon-eye.gif"/> View</a>
                          <a class="btnLink" href="/location/edit/<?=$this->session->get('auth-identity')['location_id']?>"><img src="/img/icon-pencil.png"/> Update Location</a>
                          <?php } else { ?>
                          <a class="btnLink" href="<?=$rsl->url?>" target="_blank"><img src="/img/icon-eye.gif"/> View</a>
                          <?php } ?>
                      </span>
                      <span class="on-off-buttons">
                        <a data-id="<?=$rsl->location_review_site_id?>" id="on<?=$rsl->location_review_site_id?>" href="#" class="review_site_on" style="<?=(isset($rsl->is_on) && $rsl->is_on == 1?'':'display: none;')?>"><img src="/img/btn_on.gif" class="sort-icon"/></a>
                        <a data-id="<?=$rsl->location_review_site_id?>" id="off<?=$rsl->location_review_site_id?>" href="#" class="review_site_off" style="<?=(isset($rsl->is_on) && $rsl->is_on == 1?'display: none;':'')?>"><img src="/img/btn_off.gif" class="sort-icon"/></a>
                      </span>
                                              <img src="/img/btn_sort.gif" class="sort-icon"/>
                                          </li>
                                          <?php
                  }
                }
                ?>
                                    </ul>
                                    <input id="review_order" name="review_order" type="hidden" value=""/>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-4 control-label">
                                </label>
                                <div class="col-md-8">
                                    <a class="btnLink" id="btnAddReviewSite" href="" target="_blank">Add Review Site</a>
                                </div>
                            </div>
                        </div>
                        <?php
        if ($has_facebook) {
          ?>
                        <div class="form-group">
                            <div class="row">
                                <label for="rating_threshold_star" class="col-md-4 control-label">Authenticate
                                    Facebook</label>
                                <div class="col-md-8">
                                    <a href="/location/getAccessToken" id="btnAuthenticateFacebook" class="btnLink">Authenticate
                                        Facebook</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>Facebook needs to be authenticated to import reviews into this system.</i>
                                </div>
                            </div>
                        </div>
                        <?php
        }
        }
        ?>

                        <div class="form-group">
                            <div class="row">
                                <label for="rating_threshold_star" class="col-md-4 control-label">Rating Threshold (Star
                                    Rating)</label>
                                <div class="col-md-8">
                                    {{ form.render("rating_threshold_star", ["class": 'form-control', 'placeholder': 'Rating Threshold (Star Rating)']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>This is the review threshold for the "Star Rating" review invite type. Any
                                        reviews below the threshold will be sent to the comments form.</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="rating_threshold_nps" class="col-md-4 control-label">Rating Threshold (NPS
                                    Rating)</label>
                                <div class="col-md-8">
                                    {{ form.render("rating_threshold_nps", ["class": 'form-control', 'placeholder': 'Rating Threshold (NPS Rating)']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>This is the review threshold for the "NPS Rating" review invite type. Any reviews
                                        below the threshold will be sent to the comments form.</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="review_goal" class="col-md-4 control-label">New Monthly Reviews Goal</label>
                                <div class="col-md-8">
                                    {{ form.render("review_goal", ["class": 'form-control', 'placeholder': 'Review Goal']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i> A new monthly review goal provides you and your staff with a specific target of
                                        new reviews to receive each month online from your customers.</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Review Invite Settings  -->


                    <!-- START SMS Message Settings  -->
                    <div class="tab-pane fade in" id="tab_sms_message">
                        <div class="form-group">
                            <div class="row">
                                <label for="SMS_message" class="col-md-4 control-label">SMS Message</label>
                                <div class="col-md-8">
                                    <textarea style="width: 100%;" class="form-control" name="SMS_message"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($agency->
                                        SMS_message)?$agency->SMS_message:'{location-name}: Hi {name}, We\'d really appreciate your feedback by clicking the link. Thanks! {link}'))?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>The default content of the SMS message. This can be customized when sending the
                                        SMS. {location-name} will be the name of the location sending the SMS, {name}
                                        will be replaced with the name entered when sending the message and {link} will
                                        be the link to the review.</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="message_tries" class="col-md-4 control-label">Message Tries</label>
                                <div class="col-md-8">
                                    {{ form.render("message_tries", ["class": 'form-control', 'placeholder': 'Message Tries']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>
                                        The number of times an SMS message can be sent (up to three times) until a
                                        customer leaves feedback.
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="message_frequency" class="col-md-4 control-label">Message Frequency (in
                                    hours)</label>
                                <div class="col-md-8">
                                    {{ form.render("message_frequency", ["class": 'form-control', 'placeholder': 'Message Frequency']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>
                                        The number of hours until another message should be sent. (Only used when the
                                        "Message Tries" field is greater than one.)
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END SMS Message Settings  -->


                    <!-- START White Label Settings  -->
                    <div class="tab-pane fade" id="tab_white_label">
                        <div class="form-group">
                            <div class="row">
                                <label for="custom_domain" class="col-md-4 control-label">Custom Domain</label>
                                <div class="col-md-8">
                                    {{ form.render("custom_domain", ["class": 'form-control', 'placeholder': 'Custom Domain']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="logo_path" value="<?=$objAgency->logo_path; ?>">Logo</label>
                            <div class="col-md-8">
                            	<div class="page-logo" style="margin-top: 0;">
                                	<input type="file" id="logo_path" name="logo_path">
                            		{% if logo_path %}
            						<img src="{{ logo_path }}" alt="logo" class="logo-default" />
            						{% endif %}
                                	<p class="help-block">(max width: 200 pixels, max height: 30 pixels)<br /> (only: gif, png, jpg or jpeg) </p>
                            	</div>
                            </div>
                        </div>
        <!--
        <div class="form-group">
          <label class="col-md-4 control-label" for="sms_message_logo_path">SMS Message Logo</label>
          <div class="col-md-8">
            <input type="file" id="sms_message_logo_path" name="sms_message_logo_path">
            <p class="help-block"> some help text here. </p>
          </div>
        </div>-->
                        <hr>
                        <h4>Color Theme</h4>

                        <div class="form-group">
                            <div class="col-md-6" style="border-right: 1px silver solid;">
                                    <div id="primary-color-picker" style="height:50px; color:white;"></div>
                                    <input type="color" id="main_color_chooser" pattern="#[a-f0-9]{6}" name="main_color"
                                           value="<?=$objAgency->main_color; ?>"
                                    style="margin: 4px;" /> Primary Color
                                <input type="hidden" id="main_color" value="<?=$objAgency->main_color; ?>"/>
                            </div>
                            <div class="col-md-6">

                                    <div id="secondary-color-picker" style="height:50px; color:white;"></div>
                                    <input type="color" id="secondary_color_chooser" pattern="#[a-f0-9]{6}" name="secondary_color" style="margin: 4px;"
                                           value="<?=$objAgency->secondary_color; ?>"
                                    />

                                <input type="hidden" id="secondary_color" value="<?=$objAgency->secondary_color; ?>"/>
                                    Secondary Color
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="http://<?=$agency->custom_domain; ?>.getmobilereviews.com" target="_blank"><button class="btn btn-primary" type="button" style="margin-top: 20px;">Preview Landing Page</button></a>
                            </div>
                        </div>
                    </div>
                    <!-- END White Label Settings  -->

                    <!-- START Twilio Settings  -->
                    <div class="tab-pane fade" id="tab_twilio">
                        <div class="form-group">
                            <div class="row">
                                <label for="twilio_api_key" class="col-md-4 control-label">Twilio SID</label>
                                <div class="col-md-8">
                                    {{ form.render("twilio_api_key", ["class": 'form-control', 'placeholder': 'Twilio SID']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>
                                        All SMS messages are sent using
                                        <a href="https://www.twilio.com/" target="_blank">Twilio</a>. Enter your API key
                                        here. <a href="https://www.twilio.com/user/account" target="_blank">Click
                                            here</a> to find your Twilio SID and Auth Token. If you don't have an API
                                        key yet, <a href="https://www.twilio.com/try-twilio" target="_blank">click
                                            here</a> to sign up. Note: you must have a Twilio SID and Auth Key to send
                                        SMS messages.
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="twilio_auth_token" class="col-md-4 control-label">Twilio Auth Token</label>
                                <div class="col-md-8">
                                    {{ form.render("twilio_auth_token", ["class": 'form-control', 'placeholder': 'Twilio Auth Token']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="twilio_auth_messaging_sid" class="col-md-4 control-label">Twilio Messaging
                                    Service SID</label>
                                <div class="col-md-8">
                                    {{ form.render("twilio_auth_messaging_sid", ["class": 'form-control', 'placeholder': 'Twilio Messaging Service SID']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>Either the Twilio Messaging Service SID or the Twilio Phone number is required.
                                        The Twilio Messaging Service SID allows for dynamic phone numbers.
                                        <a href="https://www.twilio.com/copilot" target="_blank">Click here</a> to read
                                        more about this field.</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="twilio_from_phone" class="col-md-4 control-label">Twilio Phone
                                    Number</label>
                                <div class="col-md-8">
                                    {{ form.render("twilio_from_phone", ["class": 'form-control', 'placeholder': 'Twilio Phone Number']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i>This field is a phone number that is configured in Twilios as the from phone
                                        number. This field is only required if the Twilio Messaging Service SID is not
                                        used.</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Twilio Settings  -->


                    <!-- START Stripe Settings  -->
                    <div class="tab-pane fade in {{ StripeExpanded }}" id="tab_stripe">
                        <div class="form-group">
                            <!--<div class="row">
            <label for="stripe_account_id" class="col-md-4 control-label">Stripe Account ID</label>
            <div class="col-md-8">
              {{ form.render("stripe_account_id", ["class": 'form-control', 'placeholder': 'Stripe Account ID']) }}
            </div>
          </div>-->
                            <div class="row">
                                <div class="col-md-12">
                                    <i>
                                        Credit card processing is done using
                                        <a href="https://stripe.com/" target="_blank">Stripe</a>. You can get all your
                                        keys from
                                        <a href="https://dashboard.stripe.com/account/apikeys" target="_blank">your
                                            account page</a>.
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="stripe_account_secret" class="col-md-4 control-label">Stripe Secret
                                    Key</label>
                                <div class="col-md-8">
                                    {{ form.render("stripe_account_secret", ["class": 'form-control', 'placeholder': 'Stripe Secret Key']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="stripe_publishable_keys" class="col-md-4 control-label">Stripe Publishable
                                    Key</label>
                                <div class="col-md-8">
                                    {{ form.render("stripe_publishable_keys", ["class": 'form-control', 'placeholder': 'Stripe Publishable Key']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Stripe Settings  -->

                    <!-- START Intercom Settings  -->
                    <div class="tab-pane fade in" id="tab_intercom">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i>
                                        Support desk is done by using
                                        <a href="https://www.intercom.com/" target="_blank">Intercom</a>.
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="intercom_api_id" class="col-md-4 control-label">App ID</label>
                                <div class="col-md-8">
                                    {{ form.render("intercom_api_id", ["class": 'form-control', 'placeholder': 'API ID']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="intercom_security_hash" class="col-md-4 control-label">Secret Key</label>
                                <div class="col-md-8">
                                    {{ form.render("intercom_security_hash", ["class": 'form-control', 'placeholder': 'Secret Key']) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Intercom Settings  -->


                    <!-- START Notification Settings  -->
                    <div class="tab-pane fade in" id="tab_notification">
                        <div class="form-group">
                            <div class="col-md-12">
                                Notifications <i>The employees who get notified of feedback and new reviews.</i>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php
          if ($users) {
            ?>
                                <div class="panel-default toggle panelMove panelClose panelRefresh" id="notifications">
                                    <div class="customdatatable-wrapper" style="margin-top: 20px;">
                                        <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Email Alert</th>
                                                <th>SMS Alert</th>
                                                <th>All Reviews</th>
                                                <th>Individual Reviews</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
            $found = false;
            foreach($users as $data) {
              $found = true;

              //now check if this record should be checked
              $checked = false;
              $is_email_alert_on = false;
              $is_sms_alert_on = false;
              $is_all_reviews_on = false;
              $is_individual_reviews_on = false;
              foreach($agencynotifications as $an) {
                if ($an->user_id == $data->id) {
                                            $is_email_alert_on = ($an->email_alert==1?true:false);
                                            $is_sms_alert_on = ($an->sms_alert==1?true:false);
                                            $is_all_reviews_on = ($an->all_reviews==1?true:false);
                                            $is_individual_reviews_on = ($an->individual_reviews==1?true:false);
                                            }
                                            }

                                            ?>
                                            <tr>
                                                <td><?=$data->name?></td>
                                                <td>
                <span class="on-off-buttons">
                  <a data-id="<?=$data->id?>" data-type="ea" data-value="0" id="eaon<?=$data->id?>" href="#" class="email_alert_on" style="<?=($is_email_alert_on?'':'display: none;')?>"><img src="/img/btn_on.gif" class="sort-icon"/></a>
                  <a data-id="<?=$data->id?>" data-type="ea" data-value="1" id="eaoff<?=$data->id?>" href="#" class="email_alert_off" style="<?=($is_email_alert_on?'display: none;':'')?>"><img src="/img/btn_off.gif" class="sort-icon"/></a>
                </span>
                                                </td>
                                                <td>
                <span class="on-off-buttons">
                  <a data-id="<?=$data->id?>" data-type="sa" data-value="0" id="saon<?=$data->id?>" href="#" class="sms_alert_on" style="<?=($is_sms_alert_on?'':'display: none;')?>"><img src="/img/btn_on.gif" class="sort-icon"/></a>
                  <a data-id="<?=$data->id?>" data-type="sa" data-value="1" id="saoff<?=$data->id?>" href="#" class="sms_alert_off" style="<?=($is_sms_alert_on?'display: none;':'')?>"><img src="/img/btn_off.gif" class="sort-icon"/></a>
                </span>
                                                </td>
                                                <td>
                <span class="on-off-buttons">
                  <a data-id="<?=$data->id?>" data-type="ar" data-value="0" id="aron<?=$data->id?>" href="#" class="all_reviews_on" style="<?=($is_all_reviews_on?'':'display: none;')?>"><img src="/img/btn_on.gif" class="sort-icon"/></a>
                  <a data-id="<?=$data->id?>" data-type="ar" data-value="1" id="aroff<?=$data->id?>" href="#" class="all_reviews_off" style="<?=($is_all_reviews_on?'display: none;':'')?>"><img src="/img/btn_off.gif" class="sort-icon"/></a>
                </span>
                                                </td>
                                                <td>
                <span class="on-off-buttons">
                  <a data-id="<?=$data->id?>" data-type="ir" data-value="0" id="iron<?=$data->id?>" href="#" class="individual_reviews_on" style="<?=($is_individual_reviews_on?'':'display: none;')?>"><img src="/img/btn_on.gif" class="sort-icon"/></a>
                  <a data-id="<?=$data->id?>" data-type="ir" data-value="1" id="iroff<?=$data->id?>" href="#" class="individual_reviews_off" style="<?=($is_individual_reviews_on?'display: none;':'')?>"><img src="/img/btn_off.gif" class="sort-icon"/></a>
                </span>
                                                </td>
                                            </tr>
                                            <?php
            }
          ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
          } else {
              ?>
                                No employees found
                                <?php
            }
            ?>
                            </div>
                        </div>
                    </div>
                    <!-- END Notification Settings  -->


                </div>

                <div class="form-group">
                    <div class="error" id="fileerror" style="display: none;">
                        Invalid file type. Only gif, png, jpg or jpeg file extensions are allowed.
                    </div>
                    <div class="col-md-offset-4 col-md-8">
                        {{ submit_button("Save", "class": "btn btn-big btn-success btnLink") }}
                    </div>
                </div>
                {{ form.render("agency_id") }}
            </form>
        </div>
    </div>


    </div>
</header>


<?php
//make sure we are at the location level settings
if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
?>
<div class="overlay" style="display: none;"></div>
<div id="page-wrapper" class="create createreviewsiteform" style="display: none;">
    <form id="createreviewsiteform" class="register-form4" action="/settings/siteadd/<?=$this->session->get('auth-identity')['location_id']?>/" method="post" style="display: block;">
        <div class="closelink close"></div>
        <div class="col-md-12">
            <div class="row"><h3>Add Review Site</h3></div>
            <div class="form-group row">
                <label for="url" class="col-md-3 control-label">Review Site: </label>
                <div class="col-md-9">
                    <select name="review_site_id" id="review_site_id" required="required">
                        <option value="">Select Site</option>
                        <?php
          foreach($review_sites as $rs) {
            $found = false;
            if ($rs->review_site_id < 4) {
                        $found = true;
                        } else if (isset($review_site_list)) {
                        foreach($review_site_list as $rsl) {
                        if ($rsl->review_site_id == $rs->review_site_id) $found = true;
                        }
                        }
                        if (!$found) {
                        ?>
                        <option value="<?=$rs->review_site_id?>"><?=$rs->name?></option>
                        <?php
            }
          }
          ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="url" class="col-md-3 control-label">URL: </label>
                <div class="col-md-9">
                    <input type="url" name="url" id="url" value="" required="required"/>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <button id="createsite" type="submit" class="btnLink">Save</button>
                </div>
            </div>
            <div style="clear: both;">&nbsp;</div>
        </div>
        <input type="hidden" name="reviewgoal" id="reviewgoal" value=""/>
        <input type="hidden" name="lifetimevalue" id="lifetimevalue" value=""/>
    </form>
</div>
<?php
}
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#btnAddReviewSite').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').show();
            $('.overlay').show();

            $('#reviewgoal').val($('#review_goal').val());
            $('#lifetimevalue').val($('#lifetime_value_customer').val());
        });
        $('.overlay, .closelink').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').hide();
            $('.overlay').hide();
        });

        $("#createreviewsiteform").on('submit', function (ev) {
            ev.preventDefault();

            $.ajax({
                url: $('#createreviewsiteform').attr('action') + '' + $("#review_site_id").val() + '/?url=' + encodeURIComponent($("#url").val()),
                cache: false,
                success: function (data) {
                    //done!
                    //console.log('data:'+data);
                    //$.each(data, function(index, element) {
                    var element = $.parseJSON(data);
                    //first, remove the value we selected
                    $("#review_site_id option[value='" + $("#review_site_id").val() + "']").each(function () {
                        $(this).remove();
                    });

                    var id = $("#review_site_id").val();
                    var url = $("#url").val();
                    var newid = element.location_review_site_id;
                    var img_path = element.img_path;
                    var name = element.name;

                    //next, add this selection, to the settings page
                    $('ul#sortable').append('<li class="ui-state-default" id="' + newid + '"><span class="site-wrapper"><img src="' + img_path + '" class="imgicon" /> ' + name + '</span> <span class="review_site-buttons"><a class="btnLink" href="' + url + '" target="_blank"><img src="/img/icon-eye.gif" /> View</a></span> <span class="on-off-buttons"> <a data-id="' + newid + '" id="on' + newid + '" href="#" class="review_site_on" style=""><img src="/img/btn_on.gif"  class="sort-icon" /></a> <a data-id="' + newid + '" id="off' + newid + '" href="#" class="review_site_off" style="display: none;"><img src="/img/btn_off.gif"  class="sort-icon" /></a> </span> <img src="/img/btn_sort.gif" class="sort-icon" /> </li>');

                    $("a.review_site_on").click(function () {
                        return turnOn($(this).attr("data-id"));
                    });
                    $("a.review_site_off").click(function () {
                        return turnOff($(this).attr("data-id"));
                    });

                    //finally, close the form
                    $('#page-wrapper').hide();
                    $('.overlay').hide();
                    //});
                }
            });

            return false;
        });

        $("a.review_site_on").click(function () {
            return turnOn($(this).attr("data-id"));
        });

        function turnOn(id) {
            //console.log('id:'+id);

            $.ajax({
                url: "/settings/on/" + id,
                cache: false,
                success: function (html) {
                    //done!
                }
            });

            $('#on' + id).hide();
            $('#off' + id).show();

            return false;
        }

        $("a.review_site_off").click(function () {
            return turnOff($(this).attr("data-id"));
        });

        function turnOff(id) {
            //console.log('id:'+id);

            $.ajax({
                url: "/settings/off/" + id,
                cache: false,
                success: function (html) {
                    //done!
                }
            });

            $('#on' + id).show();
            $('#off' + id).hide();

            return false;
        }

        $("#notifications .on-off-buttons a").click(function () {
            id = $(this).attr("data-id");
            type = $(this).attr("data-type");
            value = $(this).attr("data-value");
            //console.log('id:'+id);
            $.ajax({
                url: "/settings/notification/" + id + "/" + type + "/" + value + "/",
                cache: false,
                success: function (html) {
                    //done!
                }
            });

            if (value == 1) {
                //console.log('#'+type+'on'+id);
                $('#' + type + 'on' + id).show();
                $('#' + type + 'off' + id).hide();
            } else {
                //console.log('#'+type+'off'+id);
                $('#' + type + 'on' + id).hide();
                $('#' + type + 'off' + id).show();
            }

            return false;
        });


        $("#settingsform").on("submit", function (e) {
            var idsInOrder = $("#sortable").sortable("toArray");
            //-----------------^^^^
            $('input#review_order').val(idsInOrder);
            //return false;

            if ($('#logo_path').val() != '') {
                var ext = $('#logo_path').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    e.preventDefault();
                    $('#fileerror').show();
                    return false;
                }
            }
            $('#fileerror').hide();
            return true;
        });



        $('div#image_container img').click(function () {
            // set the img-source as value of image_from_list
            $('div#image_container img').removeClass("selected");
            $(this).addClass("selected");
            $('input#review_invite_type_id').val($(this).attr("data-id"));
        });

        var selected = $('div#image_container img.selected');
        console.log(selected);
        if(selected.length < 1)  $('div#image_container img')[0].click();

        $("#sortable").sortable();
        $("#sortable").disableSelection();
    });

    $(function(){
        function colorToHex(color) {
            if (color.substr(0, 1) === '#') {
                return color;
            }
            var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);

            var red = parseInt(digits[2]);
            var green = parseInt(digits[3]);
            var blue = parseInt(digits[4]);

            var rgb = blue | (green << 8) | (red << 16);
            return digits[1] + '#' + rgb.toString(16);
        };

        $('#main_color_chooser').on('input', function(){
            document.getElementById('main_color').value = colorToHex($(this).val());
            $('#primary-color-picker').css('background-color', $(this).val());
        });

        $('#secondary_color_chooser').on('input', function () {
            document.getElementById('secondary_color').value = colorToHex($(this).val());
            $('#secondary-color-picker').css('background-color', $(this).val());
        });

    });

</script>
