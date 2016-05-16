<div id="reviews">
  
<div class="row">
  <div class="col-md-5 col-sm-5">
    <h3 class="page-title">Employee</h3>
  </div>    
  <?php 
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
</div>  
  
{{ content() }}

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="portlet light bordered dashboard-panel">
      <div class="portlet-body">


<div class="reportheader">
  <div class="table-header">
    <div class="title reporttitle"><img src="/admin/img/icon_bargraph.gif" /> REPORTING PERIOD</div>
    <div class="header-buttons">
       <a class="btnLink <?=(!isset($_GET['t']) || (isset($_GET['t']) && $_GET['t'] == 'm')?'':'off')?>" href="/admin/<?=($profilesId==3?'':'admin')?>users?t=m">Current Month</a>
       <a class="btnLink <?=(isset($_GET['t']) && $_GET['t'] == 'lm'?'':'off')?>" href="/admin/<?=($profilesId==3?'':'admin')?>users?t=lm">Last Month</a>
       <a class="btnLink <?=(isset($_GET['t']) && $_GET['t'] == 'l'?'':'off')?>" href="/admin/<?=($profilesId==3?'':'admin')?>users?t=l">Lifetime</a>
       <form id="reviewreportform" action="/admin/<?=($profilesId==3?'':'admin')?>users?t=c" method="post" >
       Custom <input class="form-control" type="name" value="<?=(isset($_POST['start'])?$_POST['start']:'')?>" name="start" id="start" />
       To <input class="form-control" type="name" value="<?=(isset($_POST['end'])?$_POST['end']:'')?>" name="end" id="end" />
       <input type="submit" class="btnLink" value="Go" />
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
    <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
      <th>Rank</th>
      <th>Name</th>
      <th>Total</th>
      <th>Average Feedback</th>
    </tr>
    </thead>
    <tbody>
      
<?php
$rowclass = '';
$i = 0;
foreach($users_report as $user) { 
  $i++;
  ?>
  <tr>
    <td><?=$i?><?=($i==1?'st':($i==2?'nd':($i==3?'rd':'th')))?></td>
    <td><?=$user->name?></td>
    <td><?=$user->sms_sent_all_time?></td>
    <td>
      <?php 
      if ($user->review_invite_type_id == 1) {
        ?>  
        <?=($user->avg_feedback?round($user->avg_feedback).'% - <span class="greenfont">Yes</span>':'')?>
        <?php 
      } else if ($user->review_invite_type_id == 2) {
        ?><input value="<?=$user->avg_feedback?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /> <span style="margin-left: 5px;"><?=$user->avg_feedback?></span><?php
      } else {
        if ($user->avg_feedback > 0) {
        if ($user->avg_feedback <= 5) {
          ?><span class="review_invite_type_id_3 redfont"><?=$user->avg_feedback?></span><?php
        } else {
          ?><span class="review_invite_type_id_3 greenfont"><?=$user->avg_feedback?></span><?php
        }}
      } 
      ?>
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
if ($users) {
?>

<div class="portlet light bordered dashboard-panel">
<div class="table-header">
  <div class="title"><?=($profilesId==3?'Employee':'Admin User')?> List</div>
  <div class="flexsearch">
    <div class="flexsearch--wrapper">
      <div class="flexsearch--input-wrapper">
        <input class="flexsearch--input" type="search" placeholder="search">
      </div>
      <a class="flexsearch--submit"><img src="/admin/img/icon-maglass-search.gif" /></a>
    </div>
  </div>
  <div class="search-btn" style="width: 136px !important;"><a class="btnLink" style="width: 134px !important;" href="/admin/users/<?=($profilesId==3?'':'admin')?>create">Create <?=($profilesId==3?'Employee':'Admin User')?></a></div>
</div>

<!-- Start .panel -->
<div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
  <div class="customdatatable-wrapper">
    <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
      <th>Name</th>
      <th>Location</th>
      <th>Feedback Sent</th>
      <th>Average Feedback</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    </thead>
    <tbody>
      
<?php
$rowclass = '';
foreach($users as $user) { 
  ?>
  <tr>
    <td><?=$user->name?></td>
    <td>
    <?php if ($user->profilesId == 1 || $user->profilesId == 4) { ?>
      <div><b>All</b></div>
    <?php } else { ?>
      <?php foreach($user->locations as $location) { ?>
      <div><?=$location->name?></div>
      <?php }  ?>
    <?php } ?>
    </td>
    <td><?=$user->sms_sent_all_time?></td>
    <td>
      <?php 
      if ($user->review_invite_type_id == 1) {
        ?>  
        <?=($user->avg_feedback?round($user->avg_feedback).'% - <span class="greenfont">Yes</span>':'')?>
        <?php 
      } else if ($user->review_invite_type_id == 2) {
        ?><input value="<?=$user->avg_feedback?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /> <span style="margin-left: 5px;"><?=$user->avg_feedback?></span><?php
      } else {
        if ($user->avg_feedback > 0) {
        if ($user->avg_feedback <= 5) {
          ?><span class="review_invite_type_id_3 redfont"><?=$user->avg_feedback?></span><?php
        } else {
          ?><span class="review_invite_type_id_3 greenfont"><?=$user->avg_feedback?></span><?php
        }}
      } 
      ?>
    </td>
    <td><a href="/admin/users/<?=($profilesId==3?'':'admin')?>edit/<?=$user->id?>" class="btnLink"><img src="/admin/img/icon-pencil.gif" /></a></td>
    <td><a href="/admin/users/<?=($profilesId==3?'':'admin')?>delete/<?=$user->id?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btnLink"><img src="/admin/img/icon-delete.gif" /></a></td>
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
<?php }  ?>



      </div>
    </div>
  </div>
</div>
</div>

</div>
  
<script type="text/javascript">
jQuery(document).ready(function($){

  $('.starfield').rating({displayOnly: true, step: 0.5});
});
</script>