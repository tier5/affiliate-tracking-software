{{ content() }}

<header class="jumbotron subhead" id="reviews">
  <div class="hero-unit">
    <form class="form-horizontal" role="form" method="post" autocomplete="off" id="broadcastform">
      <div class="row">
        <div class="col-md-5 col-sm-5">
          <!-- BEGIN PAGE TITLE-->
          <h3 class="page-title"> SMS Broadcast</h3>
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
    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body" id="reportwrapperreview">

          <ul class="nav nav-tabs" style="margin-bottom: 25px;">
            <li class="active"><a style="border-top: 8px solid #283643 !important; padding: 14px 69px 53px;" href="/reviews/sms_broadcast"> NEW MESSAGES </a></li>
            <li><a style="border-top: 8px solid #F6F6F6 !important; padding: 14px 69px 53px;" href="/reviews/sent_message"> SENT MESSAGE </a></li>
          </ul>





          <div class="row">

            <div class="col-md-12 col-sm-12">
              <div id="pnlSMSSent" class="portlet light bordered">
                <div class="portlet-title" style="margin-top: 13px;">
                  <div class="caption">
                    <img src="/img/icon-user.gif" /> <span class="caption-subject bold uppercase" style="font-size: 14px !important;"> Search for Customers</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding-top: 20px;">
                    <div class="col-md-4">
                      <div class="details" style="border-right: 1px solid #e7ecf0; min-height: 200px;">

                        <div class="form-group">
                          <div style="margin-bottom: 15px;">
                            <b>Locations:</b>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div id="userlocationselect">
                                <table width="100%" colspan="5" border="0">
                                  <?php
                        $found = false;
                        foreach($locations as $data) {
                          $found = true;

                          //now check if this record should be checked
                          $checked = false;
                          //check post
                          if(!empty($_POST['locations'])) {
                            foreach($_POST['locations'] as $check) {
                              if ($check == $data->location_id) $checked = true;
                                  }
                                  }
                                  ?>
                                  <tr>
                                    <td><div style="margin-bottom: 5px !important;"><input type="checkbox" name="locations[]" value="<?=$data->location_id?>" <?=($checked?'checked="checked"':'')?> /></div></td>
                                    <td><div style="margin-bottom: 5px !important;"><?=$data->name?></div></td>
                                  </tr>
                                  <?php
                        }
                        if (!$found) {
                          ?>
                                  No locations found
                                  <?php
                        }
                        ?>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="margin-bottom-10 visible-sm"> </div>
                    <div class="col-md-4">
                      <div class="details" style="border-right: 1px solid #e7ecf0; min-height: 200px;">

                        <div class="form-group">
                          <div style="margin-bottom: 15px;">
                            <b>Review Type:</b>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div id="reviewtypeselect">

                                <table width="100%" colspan="5" border="0">
                                  <tr>
                                    <td><div style="margin-bottom: 5px !important;"><input type="checkbox" name="review_type_negative" value="1" <?=(isset($_POST['review_type_negative']) && $_POST['review_type_negative'] == 1?' checked="checked"':'')?> /></div></td>
                                    <td><div style="margin-bottom: 5px !important;">Left Negative Feedback</div></td>
                                  </tr>
                                  <tr>
                                    <td><div style="margin-bottom: 5px !important;"><input type="checkbox" name="review_type_positive" value="1" <?=(isset($_POST['review_type_positive']) && $_POST['review_type_positive'] == 1?' checked="checked"':'')?> /></div></td>
                                    <td><div style="margin-bottom: 5px !important;">Left Positive Review</div></td>
                                  </tr>
                                </table>

                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div style="display: none;" id="emailerror" class="error">
                            Invalid email.
                          </div>
                        </div>
                      </div>

                    </div>
                    <div class="margin-bottom-10 visible-sm"> </div>
                    <div class="col-md-4">


                      <div class="form-group">
                        <div style="margin-bottom: 15px;">
                          <b>Review Invite Date Sent Date Range:</b>
                        </div>
                        <div class="row">
                          <div class="col-md-12">

                            <div class="form-group">
                              <div class="col-md-12">
                                <b style="display: block; margin-bottom: 6px;">Start Date:</b>
                                <div data-date-format="mm-dd-yyyy" class="input-group date date-picker" style="width: 100%;">
                                  <input name="start_date" value="<?=(isset($_POST['start_date'])?$_POST['start_date']:'')?>" type="text" name="datepicker" readonly="" class="form-control" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error" style="background-color: #FFFFFF;" />
                              <span class="" style="position: absolute; right: 0;z-index: 9;">
                                <button type="button" class="btn default" style="background-color: Transparent; border: transparent;">
                                  <i class="fa fa-calendar"></i>
                                </button>
                              </span>
                                </div><span id="datepicker-error" class="help-block help-block-error"></span>
                                <!-- /input-group -->
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-12">
                                <b style="display: block; margin-bottom: 6px;">End Date:</b>
                                <div data-date-format="mm-dd-yyyy" class="input-group date date-picker" style="width: 100%;">
                                  <input name="end_date" value="<?=(isset($_POST['end_date'])?$_POST['end_date']:'')?>" type="text" name="datepicker" readonly="" class="form-control" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error" style="background-color: #FFFFFF;" />
                              <span class="" style="position: absolute; right: 0;z-index: 9;">
                                <button type="button" class="btn default" style="background-color: Transparent; border: transparent;">
                                  <i class="fa fa-calendar"></i>
                                </button>
                              </span>
                                </div><span id="datepicker-error" class="help-block help-block-error"></span>
                                <!-- /input-group -->
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-12">
                                <input type="submit" id="searchbutton" class="btnLink" value="Search" style="width: 100%; height: 43px; padding: 12px;" />
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


          <!-- Start the results  -->
          <?php if (!empty($_POST)) {
      if ($invitelist && $invitelist->count() > 0) {
          ?>
          <div class="row">

            <div class="col-md-12 col-sm-12" id="reviewsbottom">
              <div id="pnlSMSSent" class="portlet light bordered">
                <div class="portlet-title" style="margin-top: 13px;">
                  <div class="caption">
                    <img src="/img/icon-user.gif" /> <span class="caption-subject bold uppercase" style="font-size: 14px !important;"> Review Invite Customer List</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding-top: 20px;">
                    <?php
            //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
            ?>
                    <table id="" class="customdatatable table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Send
                          <div><input type="checkbox" value="1" name="markall" id="markall" <?=(isset($_POST['markall']) && $_POST['markall'] == 1?'checked="checked"':'')?> /> <i class="all" style="font-size: 12px; font-weight: normal;">(All)</i></div>
                        </th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Name</th>
                        <th>Date Sent</th>
                        <th>Recommended</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
              if($invitelist):
                foreach($invitelist as $invite):
                  ?>
                      <tr>
                        <?php
                    //now check if this record should be checked
                    $checked = false;
                    //check post
                    if(!empty($_POST['review_invite_ids'])) {
                      foreach($_POST['review_invite_ids'] as $check) {
                        if ($check == $invite->review_invite_id) $checked = true;
                        }
                        }
                        ?>
                        <td><input type="checkbox" name="review_invite_ids[]" value="<?=$invite->review_invite_id?>" <?=($checked?'checked="checked"':'')?> /></td>
                        <td><?=$invite->location_name?></td>
                        <td><?=$invite->phone?></td>
                        <td><?=$invite->name?></td>
                        <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                        <td><?php
                    if ($invite->recommend) {
                          if ($invite->recommend=='Y') {
                          echo 'Yes';
                          } else {
                          echo 'No';
                          }
                          }
                          ?>
                        </td>
                      </tr>
                      <?php
                endforeach;
              else:
                ?>
                      <tr>
                      </tr>
                      <?php
              endif;
              ?>
                      </tbody>
                    </table>

                  </div>
                </div>
                <div class="portlet-title" style="margin-top: 13px;">
                  <div class="caption">
                    <img src="/img/icon-phone.gif" /> <span class="caption-subject bold uppercase" style="font-size: 14px !important;"> SMS Message</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding-top: 20px;">

                    <div class="form-group">
                      <div class="">
                        <div class="col-md-12">
                          <textarea style="width: 100%;" name="SMS_message" class="form-control"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($location->SMS_message)?$location->SMS_message:'{location-name}: Hi {name}, We\'d really appreciate your feedback by clicking the link. Thanks! {link}'))?></textarea>
                          <i style="color: #c3c3c3; display: block; font-size: 12px; margin-top: 11px;">{location-name} will be the name of the location sending the SMS, {name} will be replaced with the name entered when sending the message and {link} will be the link to the review.</i>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-1 control-label" for="link" style="text-align: left;">Link:</label>
                      <div class="col-md-7">
                        <input type="text" placeholder="Link" class="form-control" value="<?=(isset($_POST['link'])?$_POST["link"]:'')?>" name="link" id="link" />
                      </div>
                      <div class="col-md-4">
                        <input type="submit" class="btnLink" value="Send SMS Message" style="height: 34px; padding: 6px; width: 100%;" id="sendbutton" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-1 control-label" for="name" style="text-align: left;">Name:</label>
                      <div class="col-md-3">
                        <input type="text" placeholder="Name" class="form-control" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" name="name" id="name" />
                      </div>
                      <label class="col-md-1 control-label" for="phone" style="text-align: left;">Phone:</label>
                      <div class="col-md-3">
                        <input type="text" placeholder="Phone" class="form-control" value="<?=(isset($_POST['phone'])?$_POST["phone"]:'')?>" name="phone" id="phone" />
                      </div>
                      <div class="col-md-4">
                        <button id="testbutton" type="submit" class="btnLink" value="Send Test SMS Message" style="height: 34px; padding: 6px; width: 100%;" >Send Test SMS Message</button>
                      </div>
                    </div>
                    <div class="form-group error" id="testerror" style="display: none;">
                      Please enter a valid name and phone.
                    </div>

                  </div>
                </div>

              </div>
            </div>

          </div>

          <?php
      } else {
        ?>
          <div class="">
            No customers found.
          </div>
          <?php
      } //end checking for results from the search
    }  //end checking the post
    ?>
          <!-- End the results  -->





        </div>
      </div>
    </div>
  </div>

  <input type="hidden" name="formposttype" id="formposttype" value="" />
  </form>
  </div>
</header>
<script type="text/javascript">
  jQuery(document).ready(function($){
    $('.date-picker').datepicker({
      orientation: "right",
      autoclose: true
    });

    $('.fancybox').fancybox();

    $('#testbutton').click(function (e) {
      //make sure we have name and phone, before submitting
      if ($('#name').val() != '' && $('#name').val() != '') {
        //we are good, go ahead and continue
        $('#testerror').hide();
        $('#formposttype').val('test');
        $('#broadcastform').submit();
      } else {
        //we are missing something, tell the user to enter the fields
        $('#testerror').show();
        e.preventDefault();
        return false;
      }
    });
    $('#sendbutton').click(function (e) {
      $('#formposttype').val('send');
    });
    $('#searchbutton').click(function (e) {
      $('#formposttype').val('');
    });

    $("#markall").click(function () {
      //console.log('testg:'+$(this).prop("checked"));
      //$("#pnlSMSSent input[type='checkbox']").prop('checked', $(this).prop("checked"));
      if ($(this).prop("checked")) {
        $("#reviewsbottom input[type='checkbox']").attr('checked', "checked");
        $("#reviewsbottom div.checker span").addClass('checked');
      } else {
        $("#reviewsbottom input[type='checkbox']").removeAttr('checked');
        $("#reviewsbottom div.checker span").removeClass('checked');
      }
    });
  });
</script>