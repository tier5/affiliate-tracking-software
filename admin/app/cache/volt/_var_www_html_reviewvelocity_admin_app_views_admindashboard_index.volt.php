<?php echo $this->getContent(); ?>

<header class="jumbotron subhead" id="dashboard">
  <div class="hero-unit">
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Dashboard
      <small>dashboard & statistics</small>
    </h3>
    <!-- END PAGE TITLE-->

    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlSMSSent">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Businesses</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$total_businesses?></span>
                  </div>
                  <div class="title"> Total Active </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$new_businesses?></span>
                  </div>
                  <div class="title"> New This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$lost_businesses?></span>
                  </div>
                  <div class="title"> Lost This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$churn_rate?></span>
                  </div>
                  <div class="title"> Churn Rate </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlClickThroughRate">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Agencies</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$total_agencies?></span>
                  </div>
                  <div class="title"> Total Active </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$new_agencies?></span>
                  </div>
                  <div class="title"> New This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$lost_agencies?></span>
                  </div>
                  <div class="title"> Lost This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$churn_rate_agencies?></span>
                  </div>
                  <div class="title"> Churn Rate </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>




    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlSMSSent">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">SMS Sent</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$sms_sent_total?></span>
                  </div>
                  <div class="title"> Total </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$sms_sent_last_month?></span>
                  </div>
                  <div class="title"> Last Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$sms_sent_this_month?></span>
                  </div>
                  <div class="title"> This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$sms_sent_this_month - $sms_sent_last_month?></span>
                  </div>
                  <div class="title"> Monthly Growth </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlClickThroughRate">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Click Through Rate</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=($sms_sent_total > 0 ? number_format((float)($click_through_total / $sms_sent_total) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_total > 0 ? number_format((float)($click_through_total / $sms_sent_total) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Total </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=($sms_sent_last_month > 0 ? number_format((float)($click_through_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_last_month > 0 ? number_format((float)($click_through_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Last Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number thismonth" data-percent="<?=($sms_sent_this_month > 0 ? number_format((float)($click_through_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_this_month > 0 ? number_format((float)($click_through_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number growth" data-percent="<?=($sms_sent_this_month > 0 ? number_format((float)($click_through_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 ) - ($sms_sent_last_month > 0 ? number_format((float)($click_through_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_this_month > 0 ? number_format((float)($click_through_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 ) - ($sms_sent_last_month > 0 ? number_format((float)($click_through_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Monthly Growth </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>




    <div class="row">

      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlConversionRate">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Conversion Rate</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=($sms_sent_total > 0 ? number_format((float)($conversion_total / $sms_sent_total) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_total > 0 ? number_format((float)($conversion_total / $sms_sent_total) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Total </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=($sms_sent_last_month > 0 ? number_format((float)($conversion_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_last_month > 0 ? number_format((float)($conversion_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Last Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number thismonth" data-percent="<?=($sms_sent_this_month > 0 ? number_format((float)($conversion_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_this_month > 0 ? number_format((float)($conversion_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number growth" data-percent="<?=($sms_sent_this_month > 0 ? number_format((float)($conversion_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 ) - ($sms_sent_last_month > 0 ? number_format((float)($conversion_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($sms_sent_this_month > 0 ? number_format((float)($conversion_this_month / $sms_sent_this_month) * 100, 0, '.', '') : 0 ) - ($sms_sent_last_month > 0 ? number_format((float)($conversion_last_month / $sms_sent_last_month) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Monthly Growth </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered" id="pnlNewReviews">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase"> New Reviews </span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$total_reviews?></span>
                  </div>
                  <div class="title"> Overall </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$total_reviews_last_month?></span>
                  </div>
                  <div class="title"> Last Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="details">
                  <div class="number transactions">
                    <span><?=$total_reviews_this_month?></span>
                  </div>
                  <div class="title"> This Month </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-3">
                <div class="easy-pie-chart">
                  <div class="number growth" data-percent="<?=($total_reviews > 0 ? number_format((float)($total_reviews_this_month / $total_reviews) * 100, 0, '.', ''):0) - ($total_reviews > 0 ? number_format((float)($total_reviews_last_month / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($total_reviews > 0 ? number_format((float)($total_reviews_this_month / $total_reviews) * 100, 0, '.', ''):0) - ($total_reviews > 0 ? number_format((float)($total_reviews_last_month / $total_reviews) * 100, 0, '.', '') : 0 )?></span>%
                  </div>
                  <div class="pie-title"> Monthly Growth </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>



    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-soft">
          <div class="visual">
            <i class="fa fa-crosshairs"></i>
          </div>
          <div class="details monthly-review">
            <div class="easy-pie-chart">
              <div data-percent="<?=($sms_sent_total > 0 ?
                                      (number_format((float)($total_reviews / $sms_sent_total) * 100, 0, '.', '') > 100 ?
                                        100 :
                                        number_format((float)($total_reviews / $sms_sent_total) * 100, 0, '.', '')) :
                                        0 )?>" class="number monthly-review">
                <span><?=($sms_sent_total > 0 ?
                                      (number_format((float)($total_reviews / $sms_sent_total) * 100, 0, '.', '') > 100 ?
                                        100 :
                                        number_format((float)($total_reviews / $sms_sent_total) * 100, 0, '.', '')) :
                                        0 )?></span>% </canvas>
              </div>
            </div>
          </div>
          <div class="more"> Total Review Conversions </div>
        </div>
      </div>
    </div>



  </div>
</header>