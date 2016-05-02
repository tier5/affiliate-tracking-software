{{ content() }}

<header class="jumbotron subhead" id="reviews">
	<div class="hero-unit">
    <!--<a class="btn yellow" href="/admin/reviews/sms_broadcast" style="float: right;"><i class="icon-envelope"></i>&nbsp; SMS Broadcast</a>-->
		
    <div class="row">
      <div class="col-md-5 col-sm-5">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Contacts </h3>
        <!-- END PAGE TITLE-->
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
    


  <?php if (isset($invitelist)) { ?>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered dashboard-panel">
        <div class="portlet-body" id="reportwrapperreview">

        <div class="flexsearch">
          <div class="flexsearch--wrapper">
            <div class="flexsearch--input-wrapper">
              <input class="flexsearch--input" type="search" placeholder="search">
            </div>
            <a class="flexsearch--submit"><img src="/admin/img/icon-maglass-search.gif" /></a>
          </div>
        </div>

          <!-- col-lg-12 start here -->
          <?php 
          //(invites have the following fields: Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          ?>
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="customdatatable-wrapper">
              <table class="customdatatable table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>NAME</th>
                  <th>DATE ADDED</th>
                  <th>FEEDBACK DATE</th>
                  <th>EMPLOYEE</th>
                  <th>STATUS</th>
                  <th>FEEDBACK LINK CLICKED</th>
                  <th>REVIEW LINK CLICKED</th>
                  <th>VIEW CONTACT</th>
                </tr>
              </thead>
              <tbody>
                <?php                                                                
                if($invitelist):
                  foreach($invitelist as $invite): 
                    ?>
                    <tr>
                      <td><?=$invite->name?></td>
                      <td><?=date_format(date_create($invite->date_sent),"m/d/Y")?></td>
                      <td><?=date_format(date_create($invite->date_viewed),"m/d/Y")?></td>
                      <td><?=$invite->sent_by?></td>
                      <td><?=($invite->date_viewed?(isset($invite->comments) && $invite->comments != ''?'<span class="greenfont">Feedback Left</span>':'<span class="redfont">No feedback Left</span>'):'<span class="greenfont">In Process</span>')?></td>
                      <td><?=($invite->date_viewed?'Yes':'No')?></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <?php  
                  endforeach; 
                else: 
                  ?>
                  <tr>
                  </tr>                                     
                  <?php    
                endif;
                ?>                                
              </tbody>
              </table>
              <div class="table-bottom"></div>
            </div>
          </div>
          <!-- End .panel -->
        </div>
        </div>
      </div>
    </div>                       
    <!-- col-lg-12 end here -->
  </div>
  <!-- End .row -->
  <?php } ?>

	</div>
</header>
<script type="text/javascript">
jQuery(document).ready(function($){
  var reportwrapperreview_table = $('.customdatatable').DataTable( {
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
      reportwrapperreview_table.search($("input.flexsearch--input").val()).draw();
  });




  $("#reviews .next a").text('NEXT');
  $("#reviews .prev a").text('PREV');

  $("div.dataTables_filter input").unbind();
  

});
</script>