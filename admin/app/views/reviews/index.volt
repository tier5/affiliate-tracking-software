{{ content() }}

<header class="jumbotron subhead" id="reviews">
  <div class="hero-unit">
    <!--<a class="btn yellow" href="/reviews/sms_broadcast" style="float: right;"><i class="icon-envelope"></i>&nbsp; SMS Broadcast</a>-->

    <div class="row">
      <div class="col-md-5 col-sm-5">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Reviews <small>reputation summary</small></h3>
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

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="growth-bar" style="text-align: center;">
        OVERALL
      </div>
    </div>
  </div>

  <div class="row upsidedown">

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body">
          <div class="col-md-5 col-sm-5">
            <div class="number">
              <span data-value="<?=(isset($total_reviews)?$total_reviews:0)?>" data-counter="counterup"><?=(isset($total_reviews)?$total_reviews:0)?></span>
            </div>
          </div>
          <div class="col-md-7 col-sm-7">
            <div class="number-panel">
              <!-- 5 Stars -->
              <div class="row">
                <div class="left-text">5 Stars</div>
                <div class="bar-wrapper">
                  <div class="bar-background"></div>
                  <div class="bar-filled" style="width: <?=($reviews_five==0?100:0)?>%;"></div>
                </div>
                <div class="right-number"><?=($reviews_five > 0?$reviews_five:0)?></div>
              </div>
              <!-- 4 Stars -->
              <div class="row">
                <div class="left-text">4 Stars</div>
                <div class="bar-wrapper">
                  <div class="bar-background"></div>
                  <div class="bar-filled" style="width: <?=($reviews_four==0?100:20)?>%;"></div>
                </div>
                <div class="right-number"><?=($reviews_four > 0?$reviews_four:0)?></div>
              </div>
              <!-- 3 Stars -->
              <div class="row">
                <div class="left-text">3 Stars</div>
                <div class="bar-wrapper">
                  <div class="bar-background"></div>
                  <div class="bar-filled" style="width: <?=($reviews_three==0?100:40)?>%;"></div>
                </div>
                <div class="right-number"><?=($reviews_three > 0?$reviews_three:0)?></div>
              </div>
              <!-- 2 Stars -->
              <div class="row">
                <div class="left-text">2 Stars</div>
                <div class="bar-wrapper">
                  <div class="bar-background"></div>
                  <div class="bar-filled" style="width: <?=($reviews_two==0?100:60)?>%;"></div>
                </div>
                <div class="right-number"><?=($reviews_two > 0?$reviews_two:0)?></div>
              </div>
              <!-- 1 Star -->
              <div class="row">
                <div class="left-text">1 Star</div>
                <div class="bar-wrapper">
                  <div class="bar-background"></div>
                  <div class="bar-filled" style="width: <?=($reviews_one==0?100:80)?>%;"></div>
                </div>
                <div class="right-number"><?=($reviews_one > 0?$reviews_one:0)?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="portlet-title">
          <div class="caption">
            <span class="">TOTAL REVIEWS</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body">
          <div class="number">
            <span data-value="<?=number_format((float)$average_rating, 1, '.', '')?>" data-counter="counterup"><?=number_format((float)$average_rating, 1, '.', '')?></span>
          </div>
        </div>
        <div class="portlet-title">
          <div class="caption">
            <span class="">AVERAGE RATING</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body">
          <div class="number">
            <span data-value="<?=$negative_total?>" data-counter="counterup" style="color: #AF0000;"><?=$negative_total?></span>
          </div>
        </div>
        <div class="portlet-title">
          <div class="caption">
            <span class="">NEGATIVE REVIEWS SAVED</span>
          </div>
        </div>
      </div>
    </div>

  </div>




  <div class="row">

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered" id="pnlSMSSent">
        <div class="portlet-title">
          <div class="caption">
            <img src="/img/icon_bargraph.png" />
            <span class="caption-subject bold uppercase">Review Count By Site</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="row">
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($google_review_count > 0 ? number_format((float)($google_review_count), 0, '.', '') : '0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-google.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($yelp_review_count > 0 ? number_format((float)($yelp_review_count), 0, '.', '') : '0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-yelp.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($facebook_review_count > 0 ? number_format((float)($facebook_review_count), 0, '.', '') : '0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-facebook.gif" /> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered" id="pnlConversionRate">
        <div class="portlet-title">
          <div class="caption">
            <img src="/img/icon_pie.gif" />
            <span class="caption-subject bold uppercase">Review Percent By Site</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="row">
            <div class="col-md-4">
              <div class="easy-pie-chart">
                <div class="number lastmonth" data-percent="<?=($total_reviews > 0 ? number_format((float)($google_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                  <span><?=($total_reviews > 0 ? number_format((float)($google_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>%
                </div>
                <div class="pie-title"> <img src="/img/logo/icon-google.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="easy-pie-chart">
                <div class="number thismonth" data-percent="<?=($total_reviews > 0 ? number_format((float)($yelp_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                  <span><?=($total_reviews > 0 ? number_format((float)($yelp_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>%
                </div>
                <div class="pie-title"> <img src="/img/logo/icon-yelp.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="easy-pie-chart">
                <div class="number growth" data-percent="<?=($total_reviews > 0 ? number_format((float)($facebook_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                  <span><?=($total_reviews > 0 ? number_format((float)($facebook_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>%
                </div>
                <div class="pie-title"> <img src="/img/logo/icon-facebook.gif" /> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-4">
      <div class="portlet light bordered" id="pnlSMSSent">
        <div class="portlet-title">
          <div class="caption">
            <img src="/img/icon_graph.gif" />
            <span class="caption-subject bold uppercase">Ratings By Site</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="row">
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($google_rating > 0 ? number_format((float)($google_rating), 1, '.', '') : '0.0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-google.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($yelp_rating > 0 ? number_format((float)($yelp_rating), 1, '.', '') : '0.0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-yelp.gif" /> </div>
              </div>
            </div>
            <div class="margin-bottom-10 visible-sm"> </div>
            <div class="col-md-4">
              <div class="details">
                <div class="number transactions">
                  <span><?=($facebook_rating > 0 ? number_format((float)($facebook_rating), 1, '.', '') : '0.0' )?></span>
                </div>
                <div class="title"> <img src="/img/logo/icon-facebook.gif" /> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="growth-bar">
        <img src="/img/icon_reviews.png" /> Reviews &amp; Feedback
      </div>
    </div>
  </div>


  <div class="row">

    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_online_reviews"> Online Reviews </a></li>
            <li><a data-toggle="tab" href="#tab_positive_feedback"> Positive Feedback </a></li>
            <li><a data-toggle="tab" href="#tab_negative_feedback"> Negative Feedback </a></li>
          </ul>


          <div class="reviews-feedback tab-pane fade active in" id="tab_online_reviews">
            <div class="row">
              <div id="reportwrapper" class="reportwrapper">



                <div class="flexsearch">
                  <div class="flexsearch--wrapper">
                    <div class="flexsearch--input-wrapper">
                      <input class="flexsearch--input" type="search" placeholder="search">
                    </div>
                    <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
                  </div>
                </div>

                <!-- Start .panel -->
                <div class="panel-default toggle panelMove panelClose panelRefresh">
                  <div class="customdatatable-wrapper">
                    <table class="customdatatable table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th> </th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
            $rowclass = '';


            foreach($review_report as $data) {
           


              ?>
                      <tr>
                        <td>
                          <div class="review <?=$rowclass?>">
                            <div class="rowbuttons">
                              <a class="btnLink btnSecondary fb_link" 
                                <?php if($data->rating_type_id==1)
                                {?> onclick='facebookClickHandler(<?=$facebook_page_id;?>)' <?php } ?>  href="<?=($data->rating_type_id==2?'https://www.yelp.com/biz/'.$this->view->yelp_id:($data->rating_type_id==1?'http://www.facebook.com/'.$facebook_page_id:'https://www.google.com/search?q='.urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country).'&ludocid='.$google_place_id.'#lrd=3,5'))?>" target="_blank">View</a>

                             <!-- <a class="btnLink btnSecondary" onclick="view_review(<?php echo $data->review_id?>,'more')" id="vm_<?php echo $data->review_id?>">View</a>-->
                              
                                 
                              
                               <a class="btnLink btnSecondary" onclick="view_review(<?php echo $data->review_id?>,'less')" style="display:none;" id="vl_<?php echo $data->review_id?>">View less</a>

                             <!-- <a class="btnLink btnPrimary" href="<?=($data->rating_type_id==2?'https://www.yelp.com/biz/'.$this->view->yelp_id:($data->rating_type_id==1?'http://www.facebook.com/'.$data->  facebook_page_id:'https://www.google.com/search?q='.urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country).'&ludocid='.$google_place_id.'#lrd=3,5'))?>" target="_blank">Respond</a>-->

                              <a class="btnLink btnPrimary" 
                                <?php if($data->rating_type_id==1)
                                {?> onclick='facebookClickHandler(<?=$facebook_page_id;?>)' <?php } ?>  href="<?=($data->rating_type_id==2?'https://www.yelp.com/biz/'.$this->view->yelp_id:($data->rating_type_id==1?'http://www.facebook.com/'.$facebook_page_id:'https://www.google.com/search?q='.urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country).'&ludocid='.$google_place_id.'#lrd=3,5'))?>" target="_blank">Respond</a>




                            </div>
                            <div class="rowwrapper">
                              <div class="top">
                                <div class="logo"><a href="<?=($data->rating_type_id==2?'https://www.yelp.com/biz/'.$this->view->yelp_id:($data->rating_type_id==1?'http://facebook.com/'.$data->user_id:'https://www.google.com/search?q='.urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country).'&ludocid='.$google_place_id.'#lrd=3,5'))?>" target="_blank"><img src="/img/logo/icon-<?=($data->rating_type_id==2?'yelp':($data->rating_type_id==1?'facebook':'google'))?>.gif" /></a></span></div>
                                <div class="rating col-md-2"><input value="<?=$data->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /></div>
                                <div class="name col-md-5"><?=$data->user_name?></div>
                                <div class="date col-md-3"><?=date("m/d/Y", strtotime($data->time_created))?></div>
                              </div>
                              <div class="content">
                              <span id="less_<?php echo $data->review_id?>">
                                <?php
                               
                      $text = $data->review_text;
                                $text = $text." ";
                                $text = substr($text,0,90);
                                $text = substr($text,0,strrpos($text,' '));
                                $text = $text."...";
                                echo $text;
                                ?>
                                </span>

                                <span id="more_<?php echo $data->review_id?>" style="display:none;">
                                <?php

                      echo $text = $data->review_text;
                                
                                ?>
                                </span>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php
              //if ($rowclass == '') { $rowclass = 'darker'; } else { $rowclass = ''; }
            } ?>
                      </tbody>
                    </table>
                    <div class="table-bottom"></div>
                  </div> <!-- end customdatatable-wrapper -->
                </div>
                <!-- End .panel -->


              </div>
            </div>
          </div>

          <div id="tab_positive_feedback" class="reviews-feedback tab-pane fade">

            <!-- start tab_positive_feedback -->
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="portlet-body" id="reportwrapper">

                  <div class="flexsearch">
                    <div class="flexsearch--wrapper">
                      <div class="flexsearch--input-wrapper">
                        <input class="flexsearch--input" type="search" placeholder="search">
                      </div>
                      <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
                    </div>
                  </div>

                  <!-- Start .panel -->
                  <div class="panel-default toggle panelMove panelClose panelRefresh">
                    <div class="customdatatable-wrapper">
                      <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>CUSTOMER NAME</th>
                          <th>EMPLOYEE NAME</th>
                          <th>DATE FEEDBACK LEFT</th>
                          <th>FEEDBACK</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
              if($invitelist):
                foreach($invitelist as $invite):
                if ($invite->recommend && $invite->recommend=='Y') :
                        ?>
                        <tr>
                          <td><?=$invite->name?></td>
                          <td><?=$invite->sent_by?></td>
                          <td><?=($invite->date_viewed?date_format(date_create($invite->date_viewed),"m/d/Y"):'')?></td>
                          <td><?php
                    if ($invite->review_invite_type_id == 1) {
                            ?>
                            <span class="review_invite_type_id_1">Yes</span>
                            <?php
                    } else if ($invite->review_invite_type_id == 2) {
                            ?>
                            <input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" />
                            <?php
                    } else if ($invite->review_invite_type_id == 3) {
                            ?>
                            <span class="review_invite_type_id_3"><?=$invite->rating?></span>
                            <?php
                    }
                    ?></td>
                        </tr>
                        <?php
                endif;
                endforeach;
              endif;
              ?>
                        </tbody>
                      </table>
                      <div class="table-bottom"></div>
                    </div> <!-- end customdatatable-wrapper -->
                  </div>
                  <!-- End .panel -->

                </div>
              </div>
            </div>
            <!-- end tab_positive_feedback -->

          </div>



          <div id="tab_negative_feedback" class="reviews-feedback tab-pane fade">

            <!-- start tab_negative_feedback -->
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="portlet-body" id="reportwrapper">

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
                      <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>CUSTOMER NAME</th>
                          <th>EMPLOYEE NAME</th>
                          <th>DATE FEEDBACK LEFT</th>
                          <th>FEEDBACK</th>
                          <th>COMMENTS</th>
                          <th>STATUS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                if($invitelist):
                  foreach($invitelist as $invite):
                  if ($invite->recommend && $invite->recommend=='N') :
                        ?>
                        <tr>
                          <td><?=$invite->name?></td>
                          <td><?=$invite->sent_by?></td>
                          <td><?=($invite->date_viewed?date_format(date_create($invite->date_viewed),"m/d/Y"):'')?></td>
                          <td><?php
                      if ($invite->review_invite_type_id == 1) {
                            ?>
                            <span class="review_invite_type_id_1">No</span>
                            <?php
                      } else if ($invite->review_invite_type_id == 2) {
                            ?>
                            <input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" />
                            <?php
                      } else if ($invite->review_invite_type_id == 3) {
                            ?>
                            <span class="review_invite_type_id_3"><?=$invite->rating?></span>
                            <?php
                      }
                      ?></td>
                          <td>
                            <?php
                      $text = $invite->comments;
                            $text = $text." ";
                            $text = substr($text,0,10);
                            $text = substr($text,0,strrpos($text,' '));
                            $text = $text."...";
                            echo $text;
                            ?>
                            <a id="click<?=$invite->review_invite_id?>" href="#inline<?=$invite->review_invite_id?>" onclick="" class="fancybox btnLink" style="float: right;"><img src="/img/icon-eye.gif" /> View</a>
                            <div id="inline<?=$invite->review_invite_id?>" style="width:400px;display: none;">
                              <?=nl2br($invite->comments)?>
                            </div>
                          </td>
                          <td>
                            <a data-id="<?=$invite->review_invite_id?>" id="resolved<?=$invite->review_invite_id?>" href="#" onclick="" class="btnLink resolved" style="<?=(isset($invite->is_resolved) && $invite->is_resolved == 1?'':'display: none;')?>">Resolved</a>
                            <a data-id="<?=$invite->review_invite_id?>" id="unresolved<?=$invite->review_invite_id?>" onclick="" class="btnLink unresolved" style="<?=(isset($invite->is_resolved) && $invite->is_resolved == 1?'display: none;':'')?>">Unresolved</a>
                          </td>
                        </tr>
                        <?php
                  endif;
                  endforeach;
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
            <!-- col-lg-12 end here -->
          </div>
          <!-- end tab_negative_feedback -->



        </div>

      </div>
    </div>
  </div>
  </div>


  <?php if (isset($invitelist)) { ?>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="growth-bar">
        <img src="/img/icon_reviews.png" /> Review Invite List
      </div>
    </div>
  </div>
  <!-- / .row -->
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
              <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>NAME</th>
                  <th>PHONE</th>
                  <th>EMPLOYEE</th>
                  <th>DATE SENT</th>
                  <th>LINK CLICKED</th>
                  <th>STATUS</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($invitelist):
                //print_r($invitelist);exit;
                  foreach($invitelist as $invite):
                    ?>
                <tr>
                  <td><?=$invite->name?></td>
                  <td><?=$invite->phone?></td>
                  <td><?=$invite->sent_by?></td>
                  <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                  <td><?=($invite->date_viewed?'Yes':'No')?></td>
                  <td>
                  <?php

                      if ($invite->date_viewed) {
                                            if ($invite->review_invite_type_id == 1) {
                                            if ($invite->recommend && $invite->recommend=='N') {
                                            ?><span class="redfont">No</span><?php
                          } else {
                            ?><span class="greenfont">Yes</span><?php
                          }
                        } else if ($invite->review_invite_type_id == 2) {
                                            if ($invite->recommend && $invite->recommend=='N') {
                                            ?><input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true"/><?php
                          } else {
                            ?>
                                            <input value="<?=$invite->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true"/><?php
                          }
                        } else if ($invite->review_invite_type_id == 3) {
                                            if ($invite->recommend && $invite->recommend=='N') {
                                            ?><span class="review_invite_type_id_3 redfont"><?=$invite->
                                                rating?></span><?php
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
                  <?php 

                  /*
                      if ($invite->date_viewed) {
                    if (isset($invite->comments) && $invite->comments != '') {
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
                          echo '<strong>No feedback</strong>';
                    }
                    } else {
                    if ($location->message_tries>1 && $location->message_tries > $invite->times_sent) {
                    echo '<strong>In Process</strong>';
                    } else {
                    echo '<strong>No Feedback</strong>';
                    }
                    }*/
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

  function view_review(review_id,cond)
  {
      if(cond=='more')
      {
        $('#more_'+review_id).show();
        $('#less_'+review_id).hide();
        $('#vm_'+review_id).hide();
        $('#vl_'+review_id).show();
      }
      else
      {
        $('#more_'+review_id).hide();
        $('#less_'+review_id).show();
        $('#vm_'+review_id).show();
        $('#vl_'+review_id).hide();
      }
  }

  jQuery(document).ready(function($){
    $('.easy-pie-chart .number').easyPieChart({
      //animate: 1000,
      //size: 100,
      //lineWidth: 3,
      //barColor: '#F8CB00',


      // The color of the curcular bar. You can pass either a css valid color string like rgb, rgba hex or string colors. But you can also pass a function that accepts the current percentage as a value to return a dynamically generated color.
      barColor: '{{ secondary_color }}',
      // The color of the track for the bar, false to disable rendering.
      trackColor: '{{ primary_color }}',
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

    $('.fancybox').fancybox();

    $('.starfield').rating({displayOnly: true, step: 0.5});

    //#tab_online_reviews
    //#tab_positive_feedback
    //#tab_negative_feedback

    var tab_online_reviews_table = $('#tab_online_reviews .customdatatable').DataTable( {
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
      "pageLength": 5,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
      //"pageLength": 5
    });
    $('#tab_online_reviews .flexsearch--submit').click(function(e){
      tab_online_reviews_table.search($("#tab_online_reviews input.flexsearch--input").val()).draw();
    });

    var tab_positive_feedback_table = $('#tab_positive_feedback .customdatatable').DataTable( {
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
      "pageLength": 5,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
      //"pageLength": 5
    });
    $('#tab_positive_feedback .flexsearch--submit').click(function(e){
      tab_positive_feedback_table.search($("#tab_positive_feedback input.flexsearch--input").val()).draw();
    });

    var tab_negative_feedback_table = $('#tab_negative_feedback .customdatatable').DataTable( {
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
      "pageLength": 5,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
      //"pageLength": 5
    });
    $('#tab_negative_feedback .flexsearch--submit').click(function(e){
      tab_negative_feedback_table.search($("#tab_negative_feedback input.flexsearch--input").val()).draw();
    });

    var reportwrapperreview_table = $('#reportwrapperreview .customdatatable').DataTable( {
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
      "pageLength": 5,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
      //"pageLength": 5
    });
    $('#reportwrapperreview .flexsearch--submit').click(function(e){
      reportwrapperreview_table.search($("#reportwrapperreview input.flexsearch--input").val()).draw();
    });




    $("#reviews .next a").text('NEXT');
    $("#reviews .prev a").text('PREV');

    $("div.dataTables_filter input").unbind();



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