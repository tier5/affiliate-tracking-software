{{ content() }}

<header class="jumbotron subhead analyticspage" id="reviews">
	<div class="hero-unit">

    <div class="row">
      <div class="col-md-5 col-sm-5">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Analytics </h3>
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
      
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <img src="/admin/img/icon-phone.gif" />
              <span class="caption-subject bold uppercase">SMS SENT</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-6 col-sm-6">  
                <div class="row">              
                  <div class="col-md-6 col-sm-6 border-right">
                    <div class="report-num" style="color: #af0000;"><?=$sms_sent_this_month?></div>
                    <div class="report-title">This Month</div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="report-num" style="color: #af0000;"><?=$sms_sent_last_month?></div>
                    <div class="report-title">Last Month</div>
                  </div>
                </div> 
                <div class="row border-top left-bottom">
                  <div class="col-md-12 col-sm-12">
                    <div class="report-num"><?=$sms_sent_all_time?></div>
                    <div class="report-title">Since Start</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 border-panel">
                <div class="easy-pie-chart-big">
                  <div class="number lastmonth" data-percent="<?=($sms_sent_last_month>0?round($sms_sent_this_month / $sms_sent_last_month * 100):0)?>">
                    <span><?=($sms_sent_last_month>0?round($sms_sent_this_month / $sms_sent_last_month * 100):0)?><span class="percent-sign">%</span></span>
                  </div>
                  <div class="report-title" style="margin-top: 34px;margin-bottom: 5px;"> % Of Last Month </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <img src="/admin/img/icon-reviews.gif" />
              <span class="caption-subject bold uppercase">NEW REVIEWS</span>
            </div>
          </div>
          <div class="portlet-body">
            
            <div class="row">
              <div class="col-md-6 col-sm-6 border-panel">
                <div class="easy-pie-chart">
                  <div class="report-num" style="color: #af0000; font-size: 65px; margin-top: 10px;"><?=round(($total_reviews_this_month + $total_reviews_last_month) / 2, 1)?></div>
                  <div class="report-title" style="margin-top: 21px;margin-bottom: 5px;"> Average New Review Per Month </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">  
                <div class="row">              
                  <div class="col-md-6 col-sm-6 border-right">
                    <div class="report-num"><?=$total_reviews_this_month?></div>
                    <div class="report-title">This Month</div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="report-num"><?=$total_reviews_last_month?></div>
                    <div class="report-title">Last Month</div>
                  </div>
                </div> 
                <div class="row border-top left-bottom">
                  <div class="col-md-12 col-sm-12">
                    <div class="report-num"><?=(isset($review_count_all_time)?$review_count_all_time:0)?></div>
                    <div class="report-title">Total</div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>



    <div class="row">
      
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <img src="/admin/img/icon-reviews.gif" />
              <span class="caption-subject bold uppercase">FEEDBACK CONVERSION RATE</span>
            </div>
          </div>
          <div class="portlet-body">
            <?php 
            //calculate our values
            $thism = ($sms_sent_this_month > 0?round(($sms_converted_this_month / $sms_sent_this_month) * 100):0);
            $last = ($sms_sent_last_month > 0?round(($sms_converted_last_month / $sms_sent_last_month) * 100):0);
            $all_time = ($sms_sent_all_time > 0?round(($sms_converted_all_time / $sms_sent_all_time) * 100):0);
            ?>
            <div class="row">              
              <div class="col-md-6 col-sm-6 border-right">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$thism?>">
                    <span><?=$thism?></span>% 
                  </div>
                  <div class="pie-title"> This Month </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 border-panel" style="height: 107px;">
                <div class="report-num <?=($thism>=$last?'greenfont':'redfont')?>" style="margin-top: 15px;"><?=($thism>=$last?'&and;':'&or;')?> <?=abs($last - $thism)?>%</div>
                <div class="report-title">Previous 30 Days</div>
              </div>
            </div> 
            <div class="row border-top left-bottom">       
              <div class="col-md-6 col-sm-6 border-right">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$last?>">
                    <span><?=$last?></span>% 
                  </div>
                  <div class="pie-title"> Last Month </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$all_time?>">
                    <span><?=$all_time?></span>% 
                  </div>
                  <div class="pie-title"> Overall </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <img src="/admin/img/icon-arrow.gif" />
              <span class="caption-subject bold uppercase">CLICK THROUGH RATE</span>
            </div>
          </div>
          <div class="portlet-body">
            <?php 
            //calculate our values
            $thism = ($sms_sent_this_month > 0?round(($sms_click_this_month / $sms_sent_this_month) * 100):0);
            $last = ($sms_sent_last_month > 0?round(($sms_click_last_month / $sms_sent_last_month) * 100):0);
            $all_time = ($sms_sent_all_time > 0?round(($sms_click_all_time / $sms_sent_all_time) * 100):0);
            ?>
            <div class="row">              
              <div class="col-md-6 col-sm-6 border-right">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$thism?>">
                    <span><?=$thism?></span>% 
                  </div>
                  <div class="pie-title"> This Month </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 border-panel" style="height: 107px;">
                <div class="report-num <?=($thism>=$last?'greenfont':'redfont')?>" style="margin-top: 15px;"><?=($thism>=$last?'&and;':'&or;')?> <?=abs($last - $thism)?>%</div>
                <div class="report-title">Previous 30 Days</div>
              </div>
            </div> 
            <div class="row border-top left-bottom">       
              <div class="col-md-6 col-sm-6 border-right">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$last?>">
                    <span><?=$last?></span>% 
                  </div>
                  <div class="pie-title"> Last Month </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=$all_time?>">
                    <span><?=$all_time?></span>% 
                  </div>
                  <div class="pie-title"> Overall </div>
                </div>
              </div>
            </div>
            
            
          </div>
        </div>
      </div>

    </div>
    
    



    <div class="row">
      
      <div class="col-md-12 col-sm-12">
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption">
              <img src="/admin/img/icon-reviews.gif" />
              <span class="caption-subject bold uppercase">NUMBER OF REVIEW SITE CLICKS</span>
            </div>
          </div>
          <div class="portlet-body">
            <?php                                                                
            if($clickreport) {
              foreach($clickreport as $click_site) { 
                $percent = ($click_site->num_clicks / $clicktotal) * 100;
                $largestpercent = ($clicklargest / $clicktotal) * 100;
                //calculate width
                $width = 10;
                if ($click_site->num_clicks == $clicklargest) {
                  $width = 80;
                } else {
                  $width = 80 - ($largestpercent - $percent);
                }
                if ($width < 10) $width = 10;
                ?>
                <div class="click-wrapper"><div class="click-icon"><img src="<?=$click_site->icon_path?>" /></div><div class="click-site" style="width: <?=$width?>%;"><span class="num-count"><?=$click_site->num_clicks?></span></div><div class="click-percent"><?=round($percent)?>%</div></div>
                <?php  
              } //endforeach; 
            } // endif;
            ?>         
          </div>
        </div>
      </div>

    </div>

  <?php if (isset($invitelist)) { ?>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="growth-bar">
      <i class="icon-users"></i> Customer Review Site Clicks
      </div>
    </div>    
  </div>
  <div class="row" id="">
    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body" id="reportwrapperreview">

        <div class="flexsearch">
          <div class="flexsearch--wrapper">
            <div class="flexsearch--input-wrapper">
              <input class="flexsearch--input" type="search" placeholder="search">
            </div>
            <a class="flexsearch--submit"><img src="/admin/img/icon-maglass-search.gif" /></a>
          </div>
        </div>

          <!-- col-lg-12 start here -->
          <?php 
          //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          ?>
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="customdatatable-wrapper">
              <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>NAME</th>
                  <th>EMPLOYEE</th>
                  <th>FEEDBACK</th>
                  <th>DATE</th>
                  <th>REVIEW SITE CLICKED</th>
                </tr>
              </thead>
              <tbody>
                <?php                                                                
                if($invitelist):
                  foreach($invitelist as $invite): 
                    ?>
                    <tr>
                      <td><?=$invite->name?></td>
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
                      <td><?=($invite->date_viewed?date_format(date_create($invite->date_viewed),"m/d/Y"):'')?></td>
                      <td><?php
                      foreach ($invite->review_sites as $rs) {
                        ?>
                        <img src="<?=$rs->icon_path?>" /> 
                        <?php
                      }
                      ?></td>
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

  
  $('.easy-pie-chart-big .number').easyPieChart({
      //animate: 1000,
      //size: 100,
      //lineWidth: 3,
      //barColor: '#F8CB00',

      
      // The color of the curcular bar. You can pass either a css valid color string like rgb, rgba hex or string colors. But you can also pass a function that accepts the current percentage as a value to return a dynamically generated color.
      barColor: '#2E5390',
      // The color of the track for the bar, false to disable rendering.
      trackColor: '#D7DFED',
      // The color of the scale lines, false to disable rendering.
      scaleColor: false,
      // Defines how the ending of the bar line looks like. Possible values are: butt, round and square.
      lineCap: 'butt',
      // Width of the bar line in px.
      lineWidth: 15,
      // Size of the pie chart in px. It will always be a square.
      size: 125,
      // Time in milliseconds for a eased animation of the bar growing, or false to deactivate.
      animate: 1000,
  });
  $('.easy-pie-chart .number').easyPieChart({
      //animate: 1000,
      //size: 100,
      //lineWidth: 3,
      //barColor: '#F8CB00',

      
      // The color of the curcular bar. You can pass either a css valid color string like rgb, rgba hex or string colors. But you can also pass a function that accepts the current percentage as a value to return a dynamically generated color.
      barColor: '#2E5390',
      // The color of the track for the bar, false to disable rendering.
      trackColor: '#D7DFED',
      // The color of the scale lines, false to disable rendering.
      scaleColor: false,
      // Defines how the ending of the bar line looks like. Possible values are: butt, round and square.
      lineCap: 'butt',
      // Width of the bar line in px.
      lineWidth: 10,
      // Size of the pie chart in px. It will always be a square.
      size: 70,
      // Time in milliseconds for a eased animation of the bar growing, or false to deactivate.
      animate: 1000,
  });
});
</script>