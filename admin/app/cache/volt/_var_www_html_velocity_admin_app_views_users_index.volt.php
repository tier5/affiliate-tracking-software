<?php echo $this->getContent(); ?>

<ul class="pager">
    <li class="pull-right">
      <a class="btn red btn-outline" href="/admin/users/<?=($profilesId==3?'':'admin')?>create">Create <?=($profilesId==3?'Employee':'Admin User')?></a>
    </li>
</ul>
<?php $v156328316287272492351iterated = false; ?><?php $v156328316287272492351iterator = $page->items; $v156328316287272492351incr = 0; $v156328316287272492351loop = new stdClass(); $v156328316287272492351loop->length = count($v156328316287272492351iterator); $v156328316287272492351loop->index = 1; $v156328316287272492351loop->index0 = 1; $v156328316287272492351loop->revindex = $v156328316287272492351loop->length; $v156328316287272492351loop->revindex0 = $v156328316287272492351loop->length - 1; ?><?php foreach ($v156328316287272492351iterator as $user) { ?><?php $v156328316287272492351loop->first = ($v156328316287272492351incr == 0); $v156328316287272492351loop->index = $v156328316287272492351incr + 1; $v156328316287272492351loop->index0 = $v156328316287272492351incr; $v156328316287272492351loop->revindex = $v156328316287272492351loop->length - $v156328316287272492351incr; $v156328316287272492351loop->revindex0 = $v156328316287272492351loop->length - ($v156328316287272492351incr + 1); $v156328316287272492351loop->last = ($v156328316287272492351incr == ($v156328316287272492351loop->length - 1)); ?><?php $v156328316287272492351iterated = true; ?>
<?php if ($v156328316287272492351loop->first) { ?>
<div class="portlet box red">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> <?=($profilesId==3?'Employee':'Admin User')?> List </div>
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
    <th>Type</th>
    <th>Location</th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Type</th>
    <th>Location</th>
    <th></th>
    <th></th>
  </tr>
  </tfoot>
  <tbody>
<?php } ?>
    <tr>
      <td><?php echo $user->name; ?></td>
      <td><?php echo $user->email; ?></td>
      <?php if ($user->profilesId == 1 || $user->profilesId == 4) { ?>
      <td>Admin</td>
      <?php } else { ?>
      <td>Employee</td>
      <?php } ?>
      <td>
      <?php if ($user->profilesId == 1 || $user->profilesId == 4) { ?>
        <div><b>All</b></div>
      <?php } else { ?>
        <?php foreach ($user->locations as $location) { ?>
        <div><b><?php echo $location->name; ?></b><br /><?php echo $location->address; ?><br /><?php echo $location->locality; ?>, <?php echo $location->state_province; ?>, <?php echo $location->postal_code; ?></div>
        <?php } ?>
      <?php } ?></td>
      <td><a class="btn" href="/admin/users/<?=($profilesId==3?'':'admin')?>edit/<?php echo $user->id; ?>"><i class="icon-pencil"></i></a></td>
      <td><a href="/admin/users/<?=($profilesId==3?'':'admin')?>delete/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btn" style="color: Red;"><i class="icon-close"></i></a></td>
    </tr>
<?php if ($v156328316287272492351loop->last) { ?>
  </tbody>
  </table>
            </div>
          </div>
          <!-- End .panel -->
  </div>
</div>
<?php } ?>
<?php $v156328316287272492351incr++; } if (!$v156328316287272492351iterated) { ?>
    No <?=($profilesId==3?'Employees':'Admin Users')?>
<?php } ?>
