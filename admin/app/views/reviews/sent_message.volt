{{ content() }}

<header class="jumbotron subhead" id="reviews">
  <div class="hero-unit">
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
        <div class="end-title">{{ total_sms_month }} ({{ non_viral_sms }} / {{ viral_sms }})<br/><span class="goal">Allowed</span></div>
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

          <ul class="nav nav-tabs" style="margin-bottom: 12px;">
            <li><a style="padding: 14px 69px 53px;" href="/reviews/sms_broadcast"> NEW MESSAGES </a></li>
            <li class="active"><a style="padding: 14px 69px 53px;" href="/reviews/sent_message"> SENT MESSAGE </a></li>
          </ul>


          <div class="row">

            <div class="col-md-12 col-sm-12" id="reviewsbottom">
              <div id="pnlSMSSent" class="portlet light bordered">


                <!-- Start the results  -->
                <?php
      if (isset($invitelist) && $invitelist->count() > 0) {
                ?>
                <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding-top: 20px;">
                    <?php
            //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
            ?>
                    <table id="" class="customdatatable table table-striped table-bordered dataTable no-footer table-responsive" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Message</th>
                        <th>Date Sent</th>
                        <th>Total Sent</th>
                        <th>Total Clicked</th>
                        <th>Click Through %</th>
                        <th>View</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
              if($invitelist):
                foreach($invitelist as $invite):
                  ?>
                      <tr>
                        <td><?=$invite->sms_message?></td>
                        <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                        <td><?=$invite->total_sent?></td>
                        <td><?=$invite->total_clicked?></td>
                        <td><?=($invite->total_sent>0?number_format($invite->total_clicked / $invite->total_sent * 100):0)?>%</td>
                        <td><a href="/reviews/sent_message_view/<?=$invite->sms_broadcast_id?>" class="btnLink btnSecondary" style="display: block;">View</a></td>
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

                <?php
      } else {
        ?>
                <div style="padding:10px;">
                  No sent messages found.
                </div>
                <?php
      } //end checking for results from the search
    ?>
                <!-- End the results  -->

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
  jQuery(document).ready(function($){

  });
</script>