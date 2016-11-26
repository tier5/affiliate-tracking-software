{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> Send Review Invite </span>
    </div>
  </div>
  <div class="portlet-body form">
    <?php
  if ((isset($agency->twilio_api_key) && $agency->twilio_api_key != '' &&
    isset($agency->twilio_auth_token) && $agency->twilio_auth_token != '' &&
    ((isset($agency->twilio_auth_messaging_sid) && $agency->twilio_auth_messaging_sid != '') ||
    (isset($agency->twilio_from_phone) && $agency->twilio_from_phone != ''))
    ) || ((!isset($agency->parent_agency_id) || $agency->parent_agency_id == '') && $agency->agency_type_id = 2)) {
    ?>

    <?php
  if ($num_signed_up > 0) {
    ?>
    <div class="row" style="margin-bottom: 10px;">
      <div class="col-md-12">
        <i>You are entitled to <?=$total_sms_month?> SMS messages per month.  You have sent <?=$sms_sent_this_month_total?> total SMS messages this month.</i>
      </div>
    </div>
    <?php
  }
  ?>
    <?php
  if ($sms_sent_this_month_total < $total_sms_month) {
  ?>
    <form class="form-horizontal" action="/location/send_review_invite" role="form" method="post" autocomplete="off">
      <div class="form-group">
        <div class="row">
          <label class="col-md-4 control-label">Location</label>
          <div class="col-md-8" style="margin-top: 8px;margin-bottom: 8px;">
            <?=$this->session->get('auth-identity')['location_name']?>
          </div>
        </div>
        <div class="row">
          <label for="name" class="col-md-4 control-label">Name</label>
          <div class="col-md-8">
            <input class="form-control placeholder-no-fix" type="text" placeholder="Name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <i>This is the name that will be used in the SMS text message.</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="phone" class="col-md-4 control-label">Phone</label>
          <div class="col-md-8">
            <input class="form-control placeholder-no-fix" type="text" placeholder="Phone" name="phone" value="<?=(isset($_POST['phone'])?$_POST["phone"]:'')?>" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <i>The phone number that will recieve the SMS message.</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="SMS_message" class="col-md-4 control-label">SMS Message</label>
          <div class="col-md-8">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <textarea style="width: 100%;" name="SMS_message"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($location->SMS_message)?$location->SMS_message:'Hi {name}, thanks for visiting {location-name} we\'d really appreciate your feedback by clicking the following link {link}. Thanks!'))?></textarea>
            <i>{location-name} will be the name of the location sending the SMS, {name} will be replaced with the name entered when sending the message and {link} will be the link to the review.</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
          {{ submit_button("Send", "class": "btn btn-big btn-success") }}
        </div>
      </div>
    </form>

    <?php
  } else {
    ?>
    You have no more SMS messages to send this month.
    <?php
  }
  ?>

    <?php
  } else {
    ?>
    You must have a Twilio SID and Auth Token to send SMS messages.  All SMS messages are sent using <a href="https://www.twilio.com/" target="_blank">Twilio</a>.  <a href="/settings/">Click here</a> to enter your Twilio SID and Auth Key now.  If you don't have an API key yet, <a href="https://www.twilio.com/try-twilio" target="_blank">click here</a> to sign up.
    <?php
  }
  ?>
  </div>
</div>
