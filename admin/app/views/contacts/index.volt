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
        <div class="end-title">{{ total_sms_month }} ({{ non_viral_sms }} / <?=$sms_sent_this_month_total?>)<br/><span class="goal">Allowed</span></div>
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



  <?php if (isset($invitelist)) { ?>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body" id="reportwrapperreview">

          <div class="flexsearch">
            <div class="flexsearch--wrapper">
              <div class="flexsearch--input-wrapper">
                <input class="flexsearch--input" type="search" placeholder="search">
              </div>
              <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
            </div>
          </div>

          <!-- col-lg-12 start here -->
          <?php
          //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          ?>
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="customdatatable-wrapper">
              <table class="customdatatable table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>NAME</th>
                  <th>DATE ADDED</th>
                  <th>FEEDBACK DATE</th>
                  <th>EMPLOYEE</th>
                  <th>STATUS</th>
                  <th>FEEDBACK LINK CLICKED</th>
                  <th>REVIEW LINK CLICKED</th>
                  <th style="min-width: 85px; width: 85px;">VIEW CONTACT</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($invitelist):
                  foreach($invitelist as $invite):
                    ?>
                <tr>
                  <td><?=$invite->name?></td>
                  <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                  <td><?=($invite->date_viewed?date_format(date_create($invite->date_viewed),"m/d/Y"):'')?></td>
                  <td><?=$invite->sent_by?></td>
                  <td><?php

                      if ($invite->date_viewed) {
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
                    ?></td>
                  <td><?=($invite->date_viewed?'<span class="greenfont">Yes</span>':'<span class="redfont">No</span>')?></td>
                  <td><?php
                      foreach ($invite->review_sites as $rs) {
                    ?>
                    <img  src="<?=$rs->icon_path?>" />
                    <?php
                      }
                      ?></td>
                  <td style="min-width: 86px; width: 86px;"><a href="/contacts/view/<?=$invite->review_invite_id?>" class="btnLink btnSecondary" > View</a></td>
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
              <div class="table-bottom"></div>
            </div>
          </div>
          <!-- End .panel -->
        </div>
      </div>
    </div>
  </div>
  <!-- col-lg-12 end here -->
  </div>
  <!-- End .row -->
  <?php } ?>

  </div>
</header>
<script type="text/javascript">
  jQuery(document).ready(function($){
    var reportwrapperreview_table = $('.customdatatable').DataTable( {
      "paging": true,
      "ordering": false,
      "info": false,
      "language": {
        "search": "",
        "lengthMenu": "\_MENU_",
        paginate: {
          "next": "NEXT",
          "previous": "PREV"
        },
      },
      "pageLength": 25,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
      //"pageLength": 5
    });
    $('.flexsearch--submit').click(function(e){
      reportwrapperreview_table.search($("input.flexsearch--input").val()).draw();
    });




    $("#reviews .next a").text('NEXT');
    $("#reviews .prev a").text('PREV');

    $("div.dataTables_filter input").unbind();

    $('.starfield').rating({displayOnly: true, step: 0.5});

  });
</script>