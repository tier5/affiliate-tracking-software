{{ content() }}

<ul class="pager">
    <li class="pull-right">
        {{ link_to("/stripe/create", "Create Stripe Subscription", "class": "btn red btn-outline") }}
    </li>
</ul>
<div class="portlet box red">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> Stripe Subscription List </div>
      <div class="tools"> </div>
  </div>
  <div class="portlet-body">
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="">
  <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>Plan</th>
    <th>Amount</th>
    <th></th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Plan</th>
    <th>Amount</th>
    <th></th>
  </tr>
  </tfoot>
  <tbody>
<?php
foreach($subscriptions as $sub) { 
?>
    <tr>
      <td><?=$sub->plan?></td>
      <td><?=$sub->amount?></td>
      <td><a href="/session/subscribe/<?=$sub->subscription_stripe_id?>" target="_blank">View Form</a></td>
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
