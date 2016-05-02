{{ content() }}

<ul class="pager">
    <li class="pull-right">
        {{ link_to("/admin/subscription/create", "Create Subscriptions", "class": "btn red btn-outline") }}
    </li>
</ul>
<div class="portlet box red">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> Subscription List </div>
      <div class="tools"> </div>
  </div>
  <div class="portlet-body">
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="">
  <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>Name</th>
    <th>Interval</th>
    <th>Duration</th>
    <th>Amount</th>
    <th>Trial Length</th>
    <th>Trial Amount</th>
    <th></th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Name</th>
    <th>Interval</th>
    <th>Duration</th>
    <th>Amount</th>
    <th>Trial Length</th>
    <th>Trial Amount</th>
    <th></th>
  </tr>
  </tfoot>
  <tbody>
<?php
foreach($subscriptions as $sub) { 
?>
    <tr>
      <td><?=$sub->name?></td>
      <td><?=$sub->interval->name?></td>
      <td><?=$sub->duration?></td>
      <td><?=$sub->amount?></td>
      <td><?=$sub->trial_length?></td>
      <td><?=$sub->trial_amount?></td>
      <td><a href="/admin/session/signup/<?=$sub->subscription_id?>" target="_blank">View Form</a></td>
    </tr>
<?php
}
?>
  </tbody>
  </table>
            </div>
          </div>
          <!-- End .panel -->
  </div>
</div>
