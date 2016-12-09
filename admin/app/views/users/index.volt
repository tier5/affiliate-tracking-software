<style>
/*.portlet.light {
    padding: 12px 0 15px;
}*/

#locationlist .table_user tr td{
 vertical-align:middle;
}

#locationlist .table_user tr td a{
  display:inline-block;
  padding: 5px;
}

.avgfeedbck ul{
    list-style-type: none;
    padding: 0;
}

.avgfeedbck ul li{
      display: inline-block;
      vertical-align: top;
}

.number-view {
    display: block;
    border: 3px solid;
    border-radius: 50% !important;
    width: 25px;
    height: 25px;
    text-align: center;
    font-size: 15px;
    font-weight: bold;
}
</style>
<div id="reviews">

  <div class="row">
    <div class="col-md-5 col-sm-5">
      <h3 class="page-title">All Employees</h3>
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

{{ content() }}

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="portlet light bordered dashboard-panel">
      <div class="portlet-body">

        <?php
if (strpos($_SERVER['REQUEST_URI'],'users/admin')>0) {

        } else {
        ?>
        <div class="reportheader">
          <div class="table-header">
            <div class="title reporttitle"><img src="/img/icon_bargraph.png" /> REPORTING PERIOD</div>
            <div class="header-buttons">
              <a class="btnLink <?=(!isset($_GET['t']) || (isset($_GET['t']) && $_GET['t'] == 'm')?'btnSecondary':'off')?>" href="/<?=($profilesId==3?'':'admin')?>users?t=m">Current Month</a>
              <a class="btnLink <?=(isset($_GET['t']) && $_GET['t'] == 'lm'?'btnSecondary':'off')?>" href="/<?=($profilesId==3?'':'admin')?>users?t=lm">Last Month</a>
              <a class="btnLink <?=(isset($_GET['t']) && $_GET['t'] == 'l'?'btnSecondary':'off')?>" href="/<?=($profilesId==3?'':'admin')?>users?t=l">Lifetime</a>
              <form id="reviewreportform" action="/<?=($profilesId==3?'':'admin')?>users?t=c" method="post" >
                Custom <input class="form-control" type="name" value="<?=(isset($_POST['start'])?$_POST['start']:'')?>" name="start" id="start" />
                To <input class="form-control" type="name" value="<?=(isset($_POST['end'])?$_POST['end']:'')?>" name="end" id="end" />
                <input type="submit" class="btnLink btnSecondary" value="Go" />
              </form>
            </div>
          </div>
        </div>
        <div class="portlet light bordered dashboard-panel">
          <?php
if ($users_report) {
?>

          <div class="table-header">
            <div class="title" style="text-align: center; width: 100%;">EMPLOYEE LEADERBOARD</div>
          </div>

          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh" id="employeeleaderboard">
            <div class="customdatatable-wrapper" style="margin-top: 20px;">
              <table class="customdatatable table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                <th>Rank</th>
                <th>Name</th>
                <!--<th>Reviews Sent</th>
                <th>Reviews Received</th>-->
                <th>total</total>
                <th>Customer Satisfaction</th>
                </tr>
                </thead>
                <tbody>

                <?php
$rowclass = '';
$i = 0;
  $star_rating=array();
  foreach($users_report as $rp)
  {
    array_push($star_rating,$rp->id);
  }





foreach($users_report as $user) {
  $i++;
  $total=$user->sms_sent_this_month+$user->sms_received_this_month;
  $star_rating=0;

  
  ?>
                <tr>
                  <td class="<?=$class?>"><?=$i?><?=($i==1?'st':($i==2?'nd':($i==3?'rd':'th')))?></td>
                  <td class="<?=$class?>"><?=$user->name?></td>
                  <!--<td class="<?=$class?>"><?=$user->sms_sent_this_month?></td>
                  <td class="<?=$class?>"><?=($user->sms_received_this_month)?></td>-->
                  <td><?php echo $total;?></td>
                  <td class="<?=$class?> avgfeedbck">

                  <?php if($review_invite_type_id==1){?>
                  <?=($user->sms_received_this_month > 0?(number_format(($user->positive_feedback_this_month / $user->sms_received_this_month) * 100, 1) . '%'):'0.0%')?> - <span style="text-transform: capitalize;"> yes </span>

                  <?php } elseif($review_invite_type_id==2){
                        if(!empty($rating) && array_key_exists($user->id, $rating))
                        {
                          $get_rating=explode('-',$rating[$user->id]);
                          $full_star=floor($get_rating[0]/$get_rating[1]);
                          $half_star=$get_rating[0]%$get_rating[1];
                       

                  ?>

                    <ul class="ratings">
                    <?php for($l=0;$l<$full_star;$l++)
                    {?>
                      <li><img src="/img/star.png" class="img-responsive"></li>
                      <?php } if($half_star!=0) {?>
                      <li><img src="/img/star2.png" class="img-responsive"></li>
                      <?php }?>

                      <?php echo number_format($get_rating[0]/$get_rating[1],1); ?>
                    </ul>
                  <?php } } else {

                    if(!empty($rating_number) && array_key_exists($user->id, $rating_number))
                        {
                          $get_rating_number=explode('-',$rating_number[$user->id]);
                          $number=round($get_rating_number[0]/$get_rating_number[1]);
                  ?>

                      <span class="number-view" style="border-color:#fd8e13; color:#fd8e13;"><?php echo $number;?></span>
                  <?php } else { ?>
                     <span class="number-view" style="border-color:#fd8e13; color:#fd8e13;">0</span>
                  <?php } } ?>

                  </td>
                  </td>
                </tr>
                <?php
}
?>

                </tbody>
              </table>
              <div class="table-bottom"></div>
            </div>
            <!-- end customdatatable-wrapper -->
          </div>
          <!-- End .panel -->

          <script type="text/javascript">
            jQuery(document).ready(function($){
              var employeeleaderboard_table = $('#employeeleaderboard .customdatatable').DataTable( {
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
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                //"pageLength": 5
              });
            });
          </script>

          <?php } else { ?>
          No Leaderboard
          <?php }  ?>
        </div>

        <?php
		  }
		  
		if ($this->session->get('auth-identity')['role'] != "User") {
		  if (isset($users) && $users) {
		?>

        <div class="portlet light bordered dashboard-panel">
          <div class="table-header">
            <div class="title">Employee List</div>
            <div class="flexsearch">
              <div class="flexsearch--wrapper">
                <div class="flexsearch--input-wrapper">
                  <input class="flexsearch--input" type="search" placeholder="search">
                </div>
                <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
              </div>
            </div>
            <div class="search-btn" style="width: 136px !important;"><a class="btnLink btnSecondary" style="width: 134px !important;text-align: center;" href="/users/<?=($profilesId==3?'':'admin')?>create?create_employee=1">Create User</a></div>
          </div>

          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
            <div class="customdatatable-wrapper">
              <table class="customdatatable table table-striped table-bordered table-responsive table_user" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Rank</th>
                  <th>Name</th>
                  <?php
      				if (strpos($_SERVER['REQUEST_URI'],'users/admin')>0) {
                  ?>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Employee?</th>
                  <th>Locations</th>
                  <th>Link</th>
                  <th>Action</th>
                  <?php
     				 } else {
        		  ?>
                  <!--<th>Reviews Sent</th>
                  <th>Reviews Received</th>-->
                  <th>Total</th>
                  <th>Customer Satisfaction</th>
                  <?php
      				}
      			  ?>
                </tr>
              </thead>
              <tbody>
              <?php
				$rowclass = '';
				$i=0;
				foreach($users as $user) {
					$i++;
			  ?>
                <tr>
                  <td class="<?=$class?>"><?=$i?><?=($i==1?'st':($i==2?'nd':($i==3?'rd':'th')))?></td>
                  <td><?=$user->name?></td>
                  <?php
   					 if (strpos($_SERVER['REQUEST_URI'],'users/admin')>0) {
                  ?>
                  <td><?=$user->email;?></td>
                  <td><?=$user->role; ?></td>
                  <td><?=($user->is_employee == 1 ? "Yes" :"No" )?></td>
                  <td>
                    <?=($user->is_all_locations==1?'<div>All</div>':'')?>
                    <?php foreach($user->locations as $location) { ?>
                    <div><?=$location->name?></div>
                    <?php }  ?>
                  </td>

                  <?php 
                    $code=$user->id."-".$user->name;
                  ?>
                  <td><span class="btnSecondary link_url btnLink" data-title="/link/createlink/<?=base64_encode($code)?>" style="cursor:pointer;">Link</span></td>
                  <?php
				    } else {
            $total=$user->sms_sent_this_month+$user->sms_received_this_month;
				  ?>
				      <!--<td class="<?=$class?>"><?=$user->sms_sent_this_month?></td>
				      <td class="<?=$class?>"><?=($user->sms_received_this_month)?></td>

              -->
              <td><?php echo $total;?></td>
				       <td class="<?=$class?> avgfeedbck">

                  <?php if($review_invite_type_id==1){?>
                  <?=($user->sms_received_this_month > 0?(number_format(($user->positive_feedback_this_month / $user->sms_received_this_month) * 100, 1) . '%'):'0.0%')?> - <span style="text-transform: capitalize;"> yes </span>

                  <?php } elseif($review_invite_type_id==2){
                        if(!empty($rating) && array_key_exists($user->id, $rating))
                        {
                          $get_rating=explode('-',$rating[$user->id]);
                          $full_star=floor($get_rating[0]/$get_rating[1]);
                          $half_star=$get_rating[0]%$get_rating[1];
                       

                  ?>

                    <ul class="ratings">
                    <?php for($l=0;$l<$full_star;$l++)
                    { ?>
                      <li><img src="/img/star.png" class="img-responsive"></li>
                      <?php } if($half_star!=0) {?>
                      <li><img src="/img/star2.png" class="img-responsive"></li>
                      <?php }?>

                      <?php echo number_format($get_rating[0]/$get_rating[1],1); ?>
                    </ul>
                  <?php } } else {

                       if(!empty($rating_number) && array_key_exists($user->id, $rating_number))
                        {
                        $get_rating_number=explode('-',$rating_number[$user->id]);
                          $number=round($get_rating_number[0]/$get_rating_number[1]);
                  ?>

                      <span class="number-view" style="border-color:#fd8e13; color:#fd8e13;"><?php echo $number;?></span>
                  <?php } else { ?>
                    <span class="number-view" style="border-color:#fd8e13; color:#fd8e13;">0</span>
                  <?php
                  } } ?>

              </td>
				
				
				  <?php
				    }
				  ?>
                  {% if profilesId == 1 OR profilesId == 2 %}
                  <td width="10%"><a href="/users/adminedit/<?=$user->id?>" class="btnLink btnSecondary"><img src="/img/icon-pencil.png" /></a>
                  <a href="/users/delete/<?=$user->id?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btnLink btnSecondary"><img src="/img/icon-delete.png" /></a></td>
                {% endif %}
                </tr>
                <?php
				  }
				?>

              </tbody>
            </table>
            <div class="table-bottom"></div>
          </div>
          <!-- end customdatatable-wrapper -->
        </div>
      <!-- End .panel -->
    
      <script type="text/javascript">
        jQuery(document).ready(function($){

          $('.starfield').rating({displayOnly: true, step: 0.5});


          var locationlist_table = $('#locationlist .customdatatable').DataTable( {
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
            locationlist_table.search($("input.flexsearch--input").val()).draw();
          });

        });
      </script>

      <?php } else { ?>
      No <?=($profilesId==3?'Employees':'Admin Users')?>
      <?php } } ?>



        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script type="text/javascript">
  jQuery(document).ready(function($){

    $('.starfield').rating({displayOnly: true, step: 0.5});

    $('.link_url').click(function(){

      var url=$(this).data('title');
      var popup = window.open(url, "_blank", "width=800, height=800") ;
      popup.location = url;
    })
  });

  
</script>
