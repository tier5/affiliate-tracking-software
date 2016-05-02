{{ content() }}

<header class="jumbotron subhead" id="dashboard">
	<div class="hero-unit">
		<!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Analytics
        <small>reports & statistics</small>
    </h3>
    <!-- END PAGE TITLE-->

    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat blue">
              <div class="visual">
                  <i class="fa fa-comments"></i>
              </div>
              <div class="details">
                  <div class="number">
                      <span data-value="<?=(isset($total_reviews)?$total_reviews:0)?>" data-counter="counterup"><?=(isset($total_reviews)?$total_reviews:0)?></span>
                  </div>
                  <div class="desc"> Total Reviews </div>
              </div>
              <a href="/admin/reviews/" class="more"> View more
                  <i class="m-icon-swapright m-icon-white"></i>
              </a>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat green">
              <div class="visual">
                  <i class="fa fa-bar-chart-o"></i>
              </div>
              <div class="details">
                  <div class="number">
                      <span data-value="<?=number_format((float)$average_rating, 1, '.', '')?>" data-counter="counterup"><?=number_format((float)$average_rating, 1, '.', '')?></span>
                  </div>
                  <div class="desc"> Average Rating </div>
              </div>
              <a href="/admin/reviews/" class="more"> View more
                  <i class="m-icon-swapright m-icon-white"></i>
              </a>
          </div>
      </div>
      <?php 
      if (isset($review_goal)) {
      ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat yellow-gold">
              <div class="visual">
                  <i class="fa fa-shopping-cart"></i>
              </div>
              <div class="details">
                  <div class="number"> 
                  <span data-value="<?=($review_goal > 0?$review_goal:0)?>" data-counter="counterup"><?=($review_goal > 0?$review_goal:0)?></span> </div>
                  <div class="desc"> Review Invite Goal </div>
              </div>
              <a href="/admin/settings/" class="more"> Change
                  <i class="m-icon-swapright m-icon-white"></i>
              </a>
          </div>
      </div>
      <?php 
      }
      ?>
      <?php 
      if (isset($percent_done)) {
      ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat purple">
              <div class="visual">
                  <i class="fa fa-globe"></i>
              </div>
              <div class="details monthly-review">
                <div class="easy-pie-chart">
                    <div data-percent="<?=number_format((float)$percent_done, 0, '.', '')?>" class="number monthly-review">
                        <span><?=number_format((float)$percent_done, 0, '.', '')?></span>% </canvas>
                    </div>
                </div>
              </div>
              <div class="more"> Monthly Review Invite Goal </div>
          </div>
      </div>
      <?php 
      }
      ?>
    </div>

    <div class="row">
      
      <div class="col-md-4 col-sm-4">
        <div class="portlet light bordered" id="pnlSMSSent">
          <div class="portlet-title">
            <div class="caption">
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Review Count By Site</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($google_review_count > 0 ? number_format((float)($google_review_count), 0, '.', '') : '0' )?></span> 
                   </div>
                  <div class="title"> Google </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($yelp_review_count > 0 ? number_format((float)($yelp_review_count), 0, '.', '') : '0' )?></span> 
                   </div>
                  <div class="title"> Yelp </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($facebook_review_count > 0 ? number_format((float)($facebook_review_count), 0, '.', '') : '0' )?></span> 
                   </div>
                  <div class="title"> Facebook </div>
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
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Review Percent By Site</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-4">
                <div class="easy-pie-chart">
                  <div class="number lastmonth" data-percent="<?=($total_reviews > 0 ? number_format((float)($google_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($total_reviews > 0 ? number_format((float)($google_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>% 
                  </div>
                  <div class="pie-title"> Google </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="easy-pie-chart">
                  <div class="number thismonth" data-percent="<?=($total_reviews > 0 ? number_format((float)($yelp_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($total_reviews > 0 ? number_format((float)($yelp_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>% 
                  </div>
                  <div class="pie-title"> Yelp </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="easy-pie-chart">
                  <div class="number growth" data-percent="<?=($total_reviews > 0 ? number_format((float)($facebook_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?>">
                    <span><?=($total_reviews > 0 ? number_format((float)($facebook_review_count / $total_reviews) * 100, 0, '.', '') : 0 )?></span>% 
                  </div>
                  <div class="pie-title"> Facebook </div>
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
              <i class="icon-cursor font-purple"></i>
              <span class="caption-subject font-purple bold uppercase">Ratings By Site</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($google_rating > 0 ? number_format((float)($google_rating), 1, '.', '') : '0.0' )?></span> 
                   </div>
                  <div class="title"> Google </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($yelp_rating > 0 ? number_format((float)($yelp_rating), 1, '.', '') : '0.0' )?></span> 
                   </div>
                  <div class="title"> Yelp </div>
                </div>
              </div>
              <div class="margin-bottom-10 visible-sm"> </div>
              <div class="col-md-4">
                <div class="details">
                  <div class="number transactions">
                    <span><?=($facebook_rating > 0 ? number_format((float)($facebook_rating), 1, '.', '') : '0.0' )?></span> 
                   </div>
                  <div class="title"> Facebook </div>
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
  $('.easy-pie-chart .number.monthly-review').easyPieChart({
      animate: 1000,
      size: 100,
      lineWidth: 3,
      barColor: '#F8CB00'
  });
});
</script>