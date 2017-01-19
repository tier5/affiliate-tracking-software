{{ content() }}

<header class="jumbotron subhead" id="reviews">
  <div class="hero-unit">
    <!--<a class="btn yellow" href="/reviews/sms_broadcast" style="float: right;"><i class="icon-envelope"></i>&nbsp; SMS Broadcast</a>-->

    <div class="row">
      <div class="col-md-5 col-sm-5">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Contacts </h3>
        <!-- END PAGE TITLE-->
      </div>
      <?php
      if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
      if ($is_upgrade) {
      $percent = ($total_sms_month > 0 ? number_format((float)(($sms_sent_this_month_total+$sms_sent_this_month_total_non) / $total_sms_month) * 100, 0, '.', ''):100);
      if ($percent > 100) $percent = 100;
      ?>
      <div class="col-md-7 col-sm-7">
        <div class="sms-chart-wrapper">
          <div class="title">SMS Messages Sent</div>
          <div class="bar-wrapper">
            <div class="bar-background"></div>
            <div class="bar-filled" style="width: <?=$percent?>%;"></div>
            <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
            <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month_total+$sms_sent_this_month_total_non?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>
        </div>
        <div class="end-title">{{ total_sms_month }} ({{ non_viral_sms }} / <?=($sms_sent_this_month_total+$sms_sent_this_month_total_non)?>)<br/><span class="goal">Allowed</span></div>
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
        <div class="portlet-title">
          <div class="caption">
            <span class="caption-subject bold uppercase">Contact Information</span>
          </div>
        
        </div>

        <div style="border: 1px solid #e7ecf1 !important; display: table; width: 100%; margin-top: 25px;">
          <div style="width: 50%; float: left;border-right: 1px solid #e7ecf1 !important;">
            <div class="form-group" style="padding-top: 25px;">
              <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Name:</label>
              <div style="padding-top: 2px;" class="col-md-7">
                

                <?php
                if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['role'] == 'Super Admin') { ?>
                        <input id="name-control" type="text" value="<?=$review_invite->name?>" class="caption-subject form-control input-small" />

                <?php  }else{?>
                        <?=$review_invite->name?>
                <?php } ?>
              </div>
            </div>
            <div class="form-group" style="padding-top: 25px; padding-bottom: 25px;">
              <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Cell Phone Number:</label>
              <div style="padding-top: 2px;" class="col-md-7">
                <?php
                if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['role'] == 'Super Admin') { ?>
                        <input id="phone-control" type="text" value="<?=$review_invite->phone?>" class="caption-subject form-control input-small" />
                <?php  }else{?>
                        <?=$review_invite->phone?>
                <?php } ?>
              </div>
            </div>
            <div class="form-group" style="padding-top: 25px; padding-bottom: 25px;">
              <?php
                if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['role'] == 'Super Admin') { ?>
                <div style="padding-top: 2px;" class="col-md-12">
                          <button id="update-review-details" class=" btnLink btnSecondary " data-id="<?php echo $review_invite->review_invite_id; ?>" style="float: right;"data-id="">Update</button>                        
                </div>
              <?php  }?>
            </div>


            <?php
            if (isset($invitelist)) {
            $backgroundcolor = '#F6F6F6';
            foreach($invitelist as $invite) {
              ?>
            <div style="margin-top: 25px; background-color: <?=$backgroundcolor?>;">

              <div class="form-group" style="padding-top: 25px;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Date Added:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?=date_format(date_create($invite->date_sent),"m/d/Y")?>
                </div>
              </div>

              <div class="form-group" style="padding-top: 25px;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Feedback Date:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?=($invite->date_viewed?date_format(date_create($invite->date_viewed),"m/d/Y"):'')?>
                </div>
              </div>

              <div class="form-group" style="padding-top: 25px;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Employee:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?=$invite->sent_by?>
                </div>
              </div>

              <div class="form-group" style="padding-top: 25px;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Status:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?php
                    if ($invite->date_viewed) {

                  if ($invite->recommend && $invite->recommend=='N') {
                  ?>
                  <a data-id="<?=$invite->review_invite_id?>" id="resolved<?=$invite->review_invite_id?>" href="#" onclick="" class="btnLink resolved" style="<?=(isset($invite->is_resolved) && $invite->is_resolved == 1?'':'display: none;')?> float: right;">Resolved</a>
                  <a data-id="<?=$invite->review_invite_id?>" id="unresolved<?=$invite->review_invite_id?>" onclick="" class="btnLink unresolved" style="<?=(isset($invite->is_resolved) && $invite->is_resolved == 1?'display: none;':'')?> float: right;">Unresolved</a>
                  <?php
                      }

                      if ($invite->review_invite_type_id == 1) {
                  if ($invite->recommend && $invite->recommend=='N') {
                  ?><span class="redfont">No</span><?php
                        } else {
                          ?><span class="greenfont">Yes</span><?php
                        }
                      } else if ($invite->review_invite_type_id == 2) {
                  if ($invite->recommend && $invite->recommend=='N') {
                  ?><input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /><?php
                        } else {
                          ?><input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /><?php
                        }
                      } else if ($invite->review_invite_type_id == 3) {
                  if ($invite->recommend && $invite->recommend=='N') {
                  ?><span class="review_invite_type_id_3 redfont"><?=$invite->rating?></span><?php
                        } else {
                          ?><span class="review_invite_type_id_3 greenfont"><?=$invite->rating?></span><?php
                        }
                      }
                    } else {
                      if ($location->message_tries>1 && $location->message_tries > $invite->times_sent) {
                  echo '<strong>In Process</strong>';
                  } else {
                  echo '<strong>No Feedback</strong>';
                  }
                  }
                  ?>
                </div>
              </div>

              <div class="form-group" style="padding-top: 25px;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Feedback Link Clicked:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?php
                    if ($invite->date_viewed) {
                  ?><span class="greenfont">Yes</span><?php
                    } else {
                      ?><span class="redfont">No</span><?php
                    }
                    ?>
                </div>
              </div>

              <div class="form-group" style="clear: both; display: flex; padding-bottom: 25px; padding-top: 25px; margin-bottom: 0;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: right;">Review Link Clicked:</label>
                <div style="padding-top: 2px;" class="col-md-7">
                  <?php
                    foreach ($invite->review_sites as $rs) {
                  ?>
                  <img src="<?=$rs->icon_path?>" />
                  <?php
                    }
                    ?>
                </div>
              </div>

            </div>
            <?php
                if ($backgroundcolor == '#F6F6F6') {
                  $backgroundcolor = '#FFFFFF';
                } else {
                  $backgroundcolor = '#F6F6F6';
                }
              } // endforeach;
            } ?>


          </div>
          <div style="width: 50%; float: left;">
            <a style="float: right; margin-top: 25px; margin-right: 20px;" class="fancybox btnLink btnSecondary send-review-invite" href="/reviews/#sendreviewinvite" onclick="$('#smsrequestformname').val('<?=str_replace('"', "", str_replace("'", "", $review_invite->name))?>');$('#smsrequestformphone').val('<?=str_replace('"', "", str_replace("'", "", $review_invite->phone))?>');">Send Review Invite</a>
            <form action="/contacts/view/<?=$review_invite->review_invite_id?>" method="Post" >

              <?php
            if (isset($note_list)) {
              foreach($note_list as $note) {
                ?>
              <div class="form-group" style="padding-top: 25px; clear: both;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: left;">Notes</label>
                <div style="padding-top: 2px; float: right;" class="col-md-7">
                  <strong style="">Date Added: </strong> <?=date_format(date_create($note->date_created),"m/d/Y")?>
                </div>
              </div>
              <div class="form-group" style="padding: 20px; margin-top: 25px; margin-left: 15px; margin-right: 15px; clear: both; border: 1px solid #e7ecf1 !important;">
                <div class="">
                  <?=$note->note?>
                </div>
              </div>
              <?php
              } // endforeach;
            } ?>

              <div class="form-group" style="padding-top: 10px; clear: both;">
                <label class="col-md-5 control-label" style="font-weight: bold; text-align: left;">Add Notes</label>
                <div style="padding-top: 2px; float: right;" class="col-md-7">

                </div>
              </div>
              <div class="form-group" style="padding-top: 25px; clear: both;">
                <div class="col-md-12">
                  <textarea name="note" class="col-md-8 form-control"></textarea>
                </div>
              </div>
              <div class="form-group" style="padding-top: 5px; clear: both;">
                <div class="col-md-offset-2 col-md-10">
                  <input type="submit" style="height: 42px; line-height: 14px; margin-left: 20%; padding: 15px 36px; text-align: right;" value="Save" class="btnLink btnSecondary" />
                </div>
              </div>

            </form>
            <div></div>
          </div>
        </div>
      </div>
    </div>
    <!-- col-lg-12 end here -->
  </div>
  <!-- End .row -->

  </div>
</header>
<script type="text/javascript">
  jQuery(document).ready(function($){


$('#update-review-details').click(function() {
            
            var name_control=$("#name-control").val();
            var phone_control=$("#phone-control").val();
            var review_id=$(this).data("id");
            
                $.ajax({
                type: 'POST',
                url: "/contacts/updateReviewDetails", 
                data:{review_id:review_id,name_control:name_control,phone_control:phone_control},
                success: function(result){
                    alert('Contact Updated successfully');
                    // if(result=="done"){
                    // alert('Contact Updated successfully');
                    // //location.reload(true);
                   
                    // }
                    // else
                    // {
                    // alert('Error updating contact');
                    // }
                    
                    }
                });
        });

    $('.starfield').rating({displayOnly: true, step: 0.5});


    $("a.resolved").click(function() {
      var id = $(this).attr("data-id");
      //console.log('id:'+id);

      $.ajax({
        url: "/reviews/resolved/"+id,
        cache: false,
        success: function(html){
          //done!
        }
      });

      $('#resolved'+id).hide();
      $('#unresolved'+id).show();

      return false;
    });


    $("a.unresolved").click(function() {
      var id = $(this).attr("data-id");
      //console.log('id:'+id);

      $.ajax({
        url: "/reviews/unresolved/"+id,
        cache: false,
        success: function(html){
          //done!
        }
      });

      $('#resolved'+id).show();
      $('#unresolved'+id).hide();

      return false;
    });
  });
</script>