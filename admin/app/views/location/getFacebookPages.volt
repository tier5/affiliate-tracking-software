<style type="text/css">
    .input-sm {
        margin-top: 5px !important;
        border: 1px solid black !important;
    }
    .back-button {
        font-size: 50px;
        height: 60px;
        width: 49px !important;
        margin-bottom: 16px;
        color: #c01209;
        background-color: #fff;
    }
</style>

<div id="businessList">
    {{ content() }}

    <div class="portlet light bordered dashboard-panel">
        {% if RedirectToSession == 1 %}
        <script type="text/javascript">
          <?php 
          if (strpos($_SERVER['REQUEST_URI'], 'Google') !== false) {
            echo "window.skipURL = '/location/addFacebook/{$LocationID}'";
          } else if(strpos($_SERVER['REQUEST_URI'], 'Facebook') !== false) {
            echo "window.skipURL = '/location/addYelp/{$LocationID}'";
          } else if(strpos($_SERVER['REQUEST_URI'], 'Yelp') !== false) {
            echo "window.skipURL = '/session/signup3'";
          }
          ?>
        </script>
        <div class="row">
            <div class="col-md-6">
                <button type="button" onclick="window.location.href = window.skipURL;" id="register-submit-btn" class="btnsignup uppercase" style="margin-bottom: 20px;">SKIP</button>
            </div>
        </div>

        {% endif %}
        {% if tobjBusinesses is empty %}
        <div class="table-header">
        <h1>No Business Pages found</h1>
        </div>
        {% else %}
        <div class="table-header">
          <?php if($displayBackButton) { ?>
            <!--<button type="button" id="register-submit-btn" class="btnsignup uppercase" style="font-size: 20px;
          }
    height: 31px;
    width: 87px;
    margin-bottom: 16px;">
            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> BACK
            </button>-->

            <a href="{{ backURL }}">
            <button type="button" id="register-submit-btn" class="btnsignup uppercase back-button">
            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
            </button></a>
            <?php } ?>
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

                <table class="customdatatable table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Business</th>
                        <?php if(strpos($_SERVER['REQUEST_URI'], 'getGooglePages') !== false || strpos($_SERVER['REQUEST_URI'], 'getYelpPages') !== false) { ?> <th>Address</th> <?php } ?>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                $rowclass = '';
                foreach($tobjBusinesses as $objBusiness) { ?>
                    <tr class="review <?=$rowclass?>">
                        <?php echo "<td><a href=\"/location/pick{$objBusiness->type}Business/{$objBusiness->id}/{$LocationID}/{$RedirectToSession}\">{$objBusiness->name}</a></td>"; ?>
                        <?php if($objBusiness->type == 'Google' || $objBusiness->type == 'Yelp') { echo "<td>{$objBusiness->address}, {$objBusiness->locality} {$objBusiness->state_province} {$objBusiness->country}</td>";  } ?>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
        {% endif %}
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

