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
        <div id="pnlSMSSent" class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-user font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Search for Customers</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-4">
                <div class="details">

                  <div class="form-group">
                    <div class="">
                      <b>Locations:</b>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div id="userlocationselect">
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
                          <div class="location-data">
                            <input type="checkbox" name="locations[]" value="<?=$data->location_id?>" <?=($checked?'checked="checked"':'')?> /> <?=$data->name?>
                          </div>
                          <?php
                        } 
                        if (!$found) {
                          ?>
                          No locations found
                          <?php
                        }  
                        ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">

                  <div class="form-group">
                    <div class="">
                      <b>Review Type:</b>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div id="reviewtypeselect">
                          <div class="location-data">
                            <input type="checkbox" name="review_type_negative" value="1" <?=(isset($_POST['review_type_negative']) && $_POST['review_type_negative'] == 1?' checked="checked"':'')?> /> Left Negative Feedback
                          </div>
                          <div class="location-data">
                            <input type="checkbox" name="review_type_positive" value="1" <?=(isset($_POST['review_type_positive']) && $_POST['review_type_positive'] == 1?' checked="checked"':'')?> /> Left Positive Review
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div style="display: none;" id="emailerror" class="error">
                      Invalid email.
                    </div>
                    <div class="col-md-offset-2 col-md-10">
                      <input type="submit" class="btn btn-big btn-success" value="Search">      
                    </div>
                  </div>

              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                            

                  <div class="form-group">
                    <div class="">
                      <b>Review Invite Date Sent Date Range:</b>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        
                        <div class="form-group">
                          <div class="col-md-12">
                          <b>Start Date:</b>
                            <div data-date-format="mm-dd-yyyy" class="input-group date date-picker">
                              <input name="start_date" value="<?=(isset($_POST['start_date'])?$_POST['start_date']:'')?>" type="text" name="datepicker" readonly="" class="form-control" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error">
                              <span class="input-group-btn">
                                <button type="button" class="btn default">
                                  <i class="fa fa-calendar"></i>
                                </button>
                              </span>
                            </div><span id="datepicker-error" class="help-block help-block-error"></span>
                            <!-- /input-group -->
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="col-md-12">
                          <b>End Date:</b>
                            <div data-date-format="mm-dd-yyyy" class="input-group date date-picker">
                              <input name="end_date" value="<?=(isset($_POST['end_date'])?$_POST['end_date']:'')?>" type="text" name="datepicker" readonly="" class="form-control" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error">
                              <span class="input-group-btn">
                                <button type="button" class="btn default">
                                  <i class="fa fa-calendar"></i>
                                </button>
                              </span>
                            </div><span id="datepicker-error" class="help-block help-block-error"></span>
                            <!-- /input-group -->
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
        <!-- / .row -->     
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="light ">
              <div class="portlet-title">
                <div class="caption caption-md">
                  <i class="icon-bar-chart font-red"></i>
                  <span class="caption-subject font-red bold uppercase">Review Invite Customer List</span>
                </div>
              </div>
              <div class="portlet-body" id="reportwrapper">
                <!-- col-lg-12 start here -->
                <?php 
                //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
                ?>
                <!-- Start .panel -->
                <div class="panel-default toggle panelMove panelClose panelRefresh">
                  <div class="">
                    <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Send</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Name</th>
                        <th>Date Sent</th>
                        <th>Recommended?</th>
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
                            <td><input type="checkbox" name="review_invite_ids[]" value="<?=$invite->review_invite_id?>" <?=($checked?'checked="checked"':'')?> /> Yes</td>
                            <td><?=$invite->location_name?></td>
                            <td><?=$invite->phone?></td>
                            <td><?=$invite->name?></td>
                            <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                            <td><?php 
                            if ($invite->recommend) {
                              if ($invite->recommend=='Y') {
                                echo 'Yes';
                              } else {
                                ?>
                                No <a id="click<?=$invite->review_invite_id?>" href="#inline<?=$invite->review_invite_id?>" onclick="" class="fancybox" style="float: right;">View Feedback</a>
                                <div id="inline<?=$invite->review_invite_id?>" style="width:400px;display: none;">
                                <?=nl2br($invite->comments)?>
                                </div>
                                <?php
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
                <!-- End .panel -->
              </div>
            </div>
          </div>                       
          <!-- col-lg-12 end here -->
        </div>
        <!-- End .row -->

        
        <div class="form-group">
          <div class="">
            <div class="col-md-12">
            <div><b>SMS Message</b></div>
            <textarea style="width: 100%;" name="SMS_message"><?=(isset($_POST['SMS_message'])?$_POST["SMS_message"]:(isset($location->SMS_message)?$location->SMS_message:'{location-name}: Hi {name}, We\'d really appreciate your feedback by clicking the link. Thanks! {link}'))?></textarea>
              <i>{location-name} will be the name of the location sending the SMS, {name} will be replaced with the name entered when sending the message and {link} will be the link to the review.</i>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-offset-4 col-md-8">
            {{ submit_button("Send", "class": "btn btn-big btn-success") }}
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
});
</script>