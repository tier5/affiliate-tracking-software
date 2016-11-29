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

            <div class="modal fade" id="IncreaseLocations" tabindex="-1" role="dialog" aria-labelledby="increaseLocationsModalLabel">
        <div class="credit-card-details modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="growth-bar">
                        <div>Increase Locations</div>
                    </div>
                </div>
                <div class="modal-body center-block">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="/businessSubscription" style="text-decoration:none;" ><button type="button" class="btn btn-warning btn-lg center-block">Click here to increase your locations</button></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="IncreaseLocationsFree" tabindex="-1" role="dialog" aria-labelledby="increaseLocationsModalLabel">
        <div class="credit-card-details modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="growth-bar">
                        <div>Increase Locations</div>
                    </div>
                </div>
                <div class="modal-body center-block">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-warning btn-lg center-block">Please contact us if you need further locations. </button></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

          <div class="portlet light bordered dashboard-panel">
            <div class="table-header">
              <div class="title">LOCATION LIST</div>
              <div class="flexsearch">
                <div class="flexsearch--wrapper">
                  <div class="flexsearch--input-wrapper">
                    <input class="flexsearch--input" type="search" placeholder="search">
                  </div>
                  <a class="flexsearch--submit"><img src="/img/icon-maglass-search.gif" /></a>
                </div>
              </div>
              <div class="search-btn"><a class="btnLink btnSecondary" href="/location/create" style="width: 128px !important;">Create Location</a></div>
            </div>

            <!-- Start .panel -->
            <div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
              <div class="customdatatable-wrapper">
                <table class="customdatatable table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
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
                    <td><a href="/location/edit/<?=$location->location_id?>" class="btnLink btnSecondary"><img src="/img/icon-pencil.png" /></a></td>
                    <td><a href="/location/delete/<?=$location->location_id?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btnLink btnSecondary"><img src="/img/icon-delete.png" /></a></td>
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

            {% if DisplayLocationsPopup == 1 %}
            <script>
                $(function(){
                        $('#IncreaseLocations').modal('show');
                });
            </script>
            {% elseif DisplayLocationsPopup == 2 %}
                <script>
                $(function(){
                        $('#IncreaseLocationsFree').modal('show');
                });
            </script>
            {% endif %}
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
