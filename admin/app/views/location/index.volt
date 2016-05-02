<div id="reviews">
{{ content() }}

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="portlet light bordered dashboard-panel">
      <div class="portlet-body">

<?php 
if ($locs) {
?>

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
  <div class="search-btn">{{ link_to("/admin/location/create", "Create Location", "class": "btnLink") }}</div>
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
    <td><a href="/admin/location/edit/<?=$location->location_id?>" class="btnLink"><img src="/admin/img/icon-pencil.gif" /></a></td>
    <td><a href="/admin/location/delete/<?=$location->location_id?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btnLink"><img src="/admin/img/icon-delete.gif" /></a></td>
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
      
