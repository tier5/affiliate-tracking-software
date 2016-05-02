<ul class="pager">
  <li class="previous pull-left">
    <a href="/admin/admindashboard/list/<?=$agency_type_id?>" class="btn red btn-outline">&larr; Go Back</a>
  </li>
</ul>

<?php echo $this->getContent(); ?>


<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> View <?=($agency_type_id==1?'Agency':'Business')?> </span>
    </div>
  </div>
  <div class="portlet-body form">
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Name</label>
      <div class="col-md-8">
        <?=$agency->name?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="subscription_interval_id" class="col-md-4 control-label">Subscription</label>
      <div class="col-md-8">
        <?=($agency->subscription_id>0?$agency->subscription->name:'Free')?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Email</label>
      <div class="col-md-8">
        <a href="mailto:<?=$agency->email?>"><?=$agency->email?></a>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Phone</label>
      <div class="col-md-8">
        <?=$agency->phone?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Address</label>
      <div class="col-md-8">
        <?=$agency->address?><br />
        <?=$agency->locality?>, <?=$agency->state_province?> <?=$agency->postal_code?>
      </div>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>


<?php $v142529542315814749651iterated = false; ?><?php $v142529542315814749651iterator = $users; $v142529542315814749651incr = 0; $v142529542315814749651loop = new stdClass(); $v142529542315814749651loop->length = count($v142529542315814749651iterator); $v142529542315814749651loop->index = 1; $v142529542315814749651loop->index0 = 1; $v142529542315814749651loop->revindex = $v142529542315814749651loop->length; $v142529542315814749651loop->revindex0 = $v142529542315814749651loop->length - 1; ?><?php foreach ($v142529542315814749651iterator as $user) { ?><?php $v142529542315814749651loop->first = ($v142529542315814749651incr == 0); $v142529542315814749651loop->index = $v142529542315814749651incr + 1; $v142529542315814749651loop->index0 = $v142529542315814749651incr; $v142529542315814749651loop->revindex = $v142529542315814749651loop->length - $v142529542315814749651incr; $v142529542315814749651loop->revindex0 = $v142529542315814749651loop->length - ($v142529542315814749651incr + 1); $v142529542315814749651loop->last = ($v142529542315814749651incr == ($v142529542315814749651loop->length - 1)); ?><?php $v142529542315814749651iterated = true; ?>
<?php if ($v142529542315814749651loop->first) { ?>
<div class="portlet box red" style="clear: both;">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> Employee List </div>
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
    <th>Email</th>
    <th>Profile</th>
    <th>Status</th>
    <th>Location</th>
    <th>Actions</th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Profile</th>
    <th>Status</th>
    <th>Location</th>
    <th>Actions</th>
  </tr>
  </tfoot>
  <tbody>
<?php } ?>
    <tr>
      <td><?php echo $user->name; ?></td>
      <td><?php echo $user->email; ?></td>
      <td><?php echo $user->profile->name; ?></td>
      <td><?php echo ($user->active == 'Y' ? 'Active' : 'Archived'); ?></td>
      <td style="width: 100% !important;">
      <?php if ($user->profilesId == 1 || $user->profilesId == 4) { ?>
        <div><b>All</b></div>
      <?php } else { ?>
        <?php foreach ($user->locations as $location) { ?>
        <div><b><?php echo $location->name; ?></b><br /><?php echo $location->address; ?><br /><?php echo $location->locality; ?>, <?php echo $location->state_province; ?>, <?php echo $location->postal_code; ?></div>
        <?php } ?>
      <?php } ?></td>
      <td style="text-align: right;">
      <div class="actions">
        <div class="btn-group">
          <a data-toggle="dropdown" href="javascript:;" class="btn btn-sm green dropdown-toggle" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i></a>
          <ul class="dropdown-menu pull-right">
            <li><a class="" href="mailto:<?php echo $user->email; ?>"><i class="icon-envelope-letter"></i> Email</a></li>
            <li><a href="/admin/admindashboard/forgotPassword/<?=$agency_type_id?>/<?=$agency->agency_id?>/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to reset this employee\'s password?');" class=""><i class="icon-user"></i> Password</a></li>
            <li><a href="/admin/admindashboard/confirmation/<?=$agency_type_id?>/<?=$agency->agency_id?>/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to resend this employee\'s confirmation email?');" class=""><i class="icon-envelope"></i> Resend Credentials</a></li>
            <li><a href="/admin/admindashboard/login/<?=$agency_type_id?>/<?=$agency->agency_id?>/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to log in as this employee?');" class=""><i class="icon-paper-plane"></i> Manage</a></li>
          </ul>
        </div>
      </div>
      </td>
    </tr>
<?php if ($v142529542315814749651loop->last) { ?>
  </tbody>
  </table>
            </div>
          </div>
          <!-- End .panel -->
  </div>
</div>
<?php } ?>
<?php $v142529542315814749651incr++; } if (!$v142529542315814749651iterated) { ?>
    No employees
<?php } ?>