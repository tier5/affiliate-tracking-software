<ul class="pager">
  <li class="previous pull-left">
    <a href="/admindashboard/list/<?=$agency_type_id?>" class="btn red btn-outline">&larr; Go Back</a>
  </li>
</ul>

{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> <?=($agency_id>0?'Edit':'Create')?> <?=($agency_type_id==1?'Agency':'Business')?> </span>
    </div>
  </div>
  <div class="portlet-body form">
    <form class="form-horizontal" role="form" id="agencyform" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-4 control-label">Name</label>
        <div class="col-md-8">
          {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="subscription_id" class="col-md-4 control-label">Subscription</label>
        <div class="col-md-8">
          <select name="subscription_id" style="width: 100%;">
            <option value="">Free</option>
          <?php 
          foreach($subscriptions as $sub) {           
            ?>
            <option value="<?=$sub->subscription_id?>" <?=($agency->subscription_id==$sub->subscription_id?'selected="selected"':'')?>><?=$sub->name?></option>
            <?php 
          }
          ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="email" class="col-md-4 control-label">Email</label>
        <div class="col-md-8">
          {{ form.render("email", ["class": 'form-control', 'placeholder': 'Email', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="phone" class="col-md-4 control-label">Phone</label>
        <div class="col-md-8">
          {{ form.render("phone", ["class": 'form-control', 'placeholder': 'Phone', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="address" class="col-md-4 control-label">Address</label>
        <div class="col-md-8">
          {{ form.render("address", ["class": 'form-control', 'placeholder': 'Address', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="locality" class="col-md-4 control-label">City</label>
        <div class="col-md-8">
          {{ form.render("locality", ["class": 'form-control', 'placeholder': 'City', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="state_province" class="col-md-4 control-label">State/Province</label>
        <div class="col-md-8">
          {{ form.render("state_province", ["class": 'form-control', 'placeholder': 'State/Province', 'type': 'name']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="postal_code" class="col-md-4 control-label">Postal Code</label>
        <div class="col-md-8">
          {{ form.render("postal_code", ["class": 'form-control', 'placeholder': 'Postal Code', 'type': 'name']) }}
        </div>
      </div>
      <?php if ($agency_id>0) { ?>
      
      <?php } else { ?>
      <hr />
      <h4>Create Administrator</h4>      
      <div class="form-group">
        <label for="admin_name" class="col-md-4 control-label">Admin Full Name</label>
        <div class="col-md-8">
          <input class="form-control" type="text" placeholder="Admin Full Name" name="admin_name" value="<?=(isset($_POST['admin_name'])?$_POST["admin_name"]:'')?>" /> 
        </div>
      </div>
      <div class="form-group">
        <label for="admin_email" class="col-md-4 control-label">Admin Email</label>
        <div class="col-md-8">
          <input class="form-control" type="text" placeholder="Admin Email" name="admin_email" value="<?=(isset($_POST['admin_email'])?$_POST["admin_email"]:'')?>" /> 
        </div>
      </div>
      <?php } ?>
      <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
          {{ submit_button("Save", "class": "btn btn-big btn-success") }}
        </div>
      </div>
    </form>
  </div>
</div>