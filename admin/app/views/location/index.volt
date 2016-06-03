<div id="reviews">
<div class="row">
  <div class="col-md-5 col-sm-5">
    <h3 class="page-title">Locations</h3>
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
if ($locs) {
?>

<div class="portlet light bordered dashboard-panel">
  <div id="map"></div>        
</div>        
<script>

function initMapList() {  
<?php
$started = false;
foreach($locs as $location) { 
  if (!$started) {
    ?>
  var myLatLng = {lat: <?=($location->latitude==''?'47.6750367':$location->latitude)?>, lng: <?=($location->longitude==''?'-122.127839':$location->longitude)?>};

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: myLatLng
  });
<?php
}
$started = true;
?>
  var marker = new google.maps.Marker({
    <?php 
    if ($location->latitude != '' and $location->longitude != '') { 
    ?>
    position: {lat: <?=$location->latitude?>, lng: <?=$location->longitude?>},
    <?php
    }
    ?>
    map: map,
    title: '<?=str_replace("'", "", $location->name)?>'
  });
<?php
}
?>
}

</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20&signed_in=true&callback=initMapList"></script>
        

<div class="portlet light bordered dashboard-panel">
<div class="table-header">
  <div class="title">LOCATION LIST</div>
  <div class="flexsearch">
    <div class="flexsearch--wrapper">
      <div class="flexsearch--input-wrapper">
        <input class="flexsearch--input" type="search" placeholder="search">
      </div>
      <a class="flexsearch--submit"><img src="/admin/img/icon-maglass-search.gif" /></a>
    </div>
  </div>
  <div class="search-btn"><a class="btnLink" href="/admin/location/create" style="width: 127px !important;">Create Location</a></div>
</div>

<!-- Start .panel -->
<div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
  <div class="customdatatable-wrapper">
    <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
      <th>Location Name</th>
      <th>State</th>
      <th>City</th>
      <th>Total Reviews</th>
      <th>Average Rating</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    </thead>
    <tbody>
      
<?php
$rowclass = '';
foreach($locs as $location) { 
  ?>
  <tr class="review <?=$rowclass?>">
    <td><?=$location->name?></td>
    <td><?=$location->state_province?></td>
    <td><?=$location->locality?></td>
    <td><?=($location->review_count > 0?$location->review_count:0)?></td>
    <td><?=($location->review_count > 0?number_format((float)($location->rating / $location->review_count), 1, '.', ''):'0.0')?></td>
    <td><a href="/admin/location/edit/<?=$location->location_id?>" class="btnLink"><img src="/admin/img/edit_green_button.png" /></a></td>
    <td><a href="/admin/location/delete/<?=$location->location_id?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btnLink"><img src="/admin/img/delete_green_button.png" /></a></td>
  </tr>
  <?php
    //if ($rowclass == '') { $rowclass = 'darker'; } else { $rowclass = ''; }
}
?>

    </tbody>
  </table>
</div>
<!-- end customdatatable-wrapper -->
</div>
<!-- End .panel -->
  
<script type="text/javascript">
jQuery(document).ready(function($){

  
  
  var locationlist_table = $('#locationlist .customdatatable').DataTable( {
      "paging": false,
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
  $('.flexsearch--submit').click(function(e){
      locationlist_table.search($("input.flexsearch--input").val()).draw();
  });

});
</script>

<?php } else { ?>
    No locations
    
<?php }  ?>


      </div>
    </div>
  </div>
</div>
</div>


</div>
      
