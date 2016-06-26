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

          <div class="reportheader">
            <div class="table-header">
              <div class="title reporttitle"><i class="icon-envelope"></i> SENT MESSAGES</div>
              <div class="header-buttons">
                <a style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;" class="btnLink" href="/reviews/sent_message">Back</a>
              </div>
            </div>
          </div>


          <div class="row">

            <div class="col-md-12 col-sm-12" id="reviewsbottom">
              <div id="locationlist" class="portlet light bordered">


                <!-- Start the results  -->
                <?php
      if (isset($invitelist) && $invitelist->count() > 0) {
                ?>
                <div class="flexsearch">
                  <div class="flexsearch--wrapper">
                    <div class="flexsearch--input-wrapper">
                      <input class="flexsearch--input" type="search" placeholder="search">
                    </div>
                    <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
                  </div>
                </div>

                <div class="row">
                  <div class="" style="padding-top: 50px;">
                    <div class="form-group">
                      <div class="col-md-1">
                        <strong style="padding-top: 18px;display: block;">Message</strong>
                      </div>
                      <div class="col-md-11" style="">
                        <div class="col-md-12" style="border: 1px solid #e8ebf0; border-radius: 4px !important; padding: 17px 13px;">
                          <?=(isset($sms_broadcast->sms_message)?$sms_broadcast->sms_message:'')?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- col-lg-12 start here -->
                <?php
          //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          ?>
                <!-- Start .panel -->
                <div class="panel-default toggle panelMove panelClose panelRefresh">
                  <div class="customdatatable-wrapper">

                    <div class="row">
                      <div class="col-md-12 col-sm-12" style="padding-top: 0;">
                        <?php
            //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
            ?>
                        <table id="" class="customdatatable table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                          <thead>
                          <tr>
                            <th>Name</th>
                            <th>Cell Phone</th>
                            <th>Clicked</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
              if($invitelist):
                foreach($invitelist as $invite):
                  ?>
                          <tr>
                            <td><?=$invite->name?></td>
                            <td><?=$invite->phone?></td>
                            <td><?=($invite->date_viewed?'Yes':'No')?></td>
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
  });
</script>