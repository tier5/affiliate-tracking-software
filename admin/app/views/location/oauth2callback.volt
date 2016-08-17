<div id="reviews">
    {{ content() }}

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="portlet light bordered dashboard-panel">
                <div class="table-header">
                    <div class="title" style="width: auto;">AUTHENTICATE GOOGLE</div>
                </div>
                <div class="google-description" style="margin-top: 14px; margin-bottom: 20px;">Select the location.</div>
                <div class="portlet-body">

                    <?php
if (isset($locs)) {
?>

                    <div class="portlet light bordered dashboard-panel">
                        <!-- Start .panel -->
                        <div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
                            <div class="customdatatable-wrapper">
                                <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Business</th>
                                        <th>Location</th>
                                        <th>Select</th>
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
                                        <td><a href="/admin/location/oauth2callback?l=<?=$location->location_id?>&a=<?=urlencode($accessToken)?>" class="btnLink">Select</a></td>
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

                        <?php } else { ?>
                        <div>No locations.  <a href="/admin/settings/location/">Click here</a> to go back to the settings page.</div>

                        <?php }  ?>


                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

