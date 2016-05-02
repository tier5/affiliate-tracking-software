<ul class="pager">
  <li class="previous pull-left">
    {{ link_to("/admin/stripe", "&larr; Go Back") }}
  </li>
</ul>

{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> Create Stripe Subscription </span>
    </div>
  </div>
  <div class="portlet-body form">
    <form class="form-horizontal" role="form" id="subscriptionform" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-4 control-label">Plan</label>
        <div class="col-md-8">
          {{ form.render("plan", ["class": 'form-control', 'placeholder': 'Plan', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="amount" class="col-md-4 control-label">Amount</label>
        <div class="col-md-8">
          {{ form.render("amount", ["class": 'form-control', 'placeholder': 'Amount']) }}
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="description" class="col-md-4 control-label">Description</label>
          <div class="col-md-8">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <textarea style="width: 100%;" name="description"><?=(isset($_POST['description'])?$_POST["description"]:(isset($subscription->description)?$subscription->description:''))?></textarea>
            <i></i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
          {{ submit_button("Save", "class": "btn btn-big btn-success") }}
        </div>
      </div>
    </form>
  </div>
</div>