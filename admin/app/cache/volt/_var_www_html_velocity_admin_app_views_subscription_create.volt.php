<ul class="pager">
  <li class="previous pull-left">
    <?php echo $this->tag->linkTo(array('/admin/subscription', '&larr; Go Back')); ?>
  </li>
</ul>

<?php echo $this->getContent(); ?>

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> Create Subscription </span>
    </div>
  </div>
  <div class="portlet-body form">
    <form class="form-horizontal" role="form" id="subscriptionform" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-4 control-label">Name</label>
        <div class="col-md-8">
          <?php echo $form->render('name', array('class' => 'form-control', 'placeholder' => 'Name', 'type' => 'name')); ?>
        </div>
      </div>
      <div class="form-group">
        <label for="subscription_interval_id" class="col-md-4 control-label">Interval</label>
        <div class="col-md-8">
          <?php echo $form->render('subscription_interval_id', array('class' => 'form-control')); ?>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="duration" class="col-md-4 control-label">Duration</label>
          <div class="col-md-8">
            <?php echo $form->render('duration', array('class' => 'form-control', 'placeholder' => 'Duration')); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <i>(If days, select a number between 7 and 365; If months, select a number between 1 and 12;)</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="amount" class="col-md-4 control-label">Amount</label>
        <div class="col-md-8">
          <?php echo $form->render('amount', array('class' => 'form-control', 'placeholder' => 'Amount')); ?>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="trial_length" class="col-md-4 control-label">Trial Length</label>
          <div class="col-md-8">
            <?php echo $form->render('trial_length', array('class' => 'form-control', 'placeholder' => 'Trial Length')); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <i>(Use this field if you want a trial period at a lower price.  The Trial Length is the number of payments (duration * Interval) that are considered trial.)</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="trial_amount" class="col-md-4 control-label">Trial Amount</label>
          <div class="col-md-8">
            <?php echo $form->render('trial_amount', array('class' => 'form-control', 'placeholder' => 'Trial Amount')); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <i>(Use this field if you want a trial period at a lower price.  The Trial Amount is the price charged during the trial.  Use zero for free.)</i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
          <?php echo $this->tag->submitButton(array('Save', 'class' => 'btn btn-big btn-success')); ?>
        </div>
      </div>
    </form>
  </div>
</div>