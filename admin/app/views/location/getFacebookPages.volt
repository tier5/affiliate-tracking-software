<style type="text/css">
    .input-sm {
        margin-top: 5px !important;
        border: 1px solid black !important;
    }
</style>
<div id="businessList">
    {{ content() }}
    <div class="portlet light bordered dashboard-panel">
        <div class="table-header">
            <div class="title">BUSINESS LIST</div>
            <div class="flexsearch">
                <div class="flexsearch--wrapper">
                    <div class="flexsearch--input-wrapper">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
            <div class="customdatatable-wrapper">
                <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Business</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                $rowclass = '';
                foreach($tobjBusinesses as $objBusiness) { ?>
                    <tr class="review <?=$rowclass?>">
                        <?php echo "<td><a href=\"/location/pick{$objBusiness->type}Business/{$objBusiness->id}/{$LocationID}/{$RedirectToSession}\">{$objBusiness->name}</a></td>
                        "; ?>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
  jQuery(document).ready(function($){
    var locationlist_table = $('#businessList .customdatatable').DataTable( {
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
    $('.flexsearch--submit').click(function(e) {
      locationlist_table.search($("input.flexsearch--input").val()).draw();
      $('.input-sm:first').attr('placeholder', 'Search');
    });

  });
</script>

