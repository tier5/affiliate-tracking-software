{{ content() }}
<!-- BEGIN FORM -->
<form class="register-form4" action="<?=((strpos($_SERVER['REQUEST_URI'],'location')>0)?'/location/create3/'.$location_id:'/session/signup4/'.(isset($subscription->subscription_id)?$subscription->subscription_id:'').(isset($_GET['code'])?'?code='.$_GET['code']:''))?>" method="post" style="display: block;">

  <h3 style="border-bottom: 1px solid #E7ECF0;">Enter Employees & Business Information</h3>

  <div class="top-row">
    <div class="form-group col-md-6">
      <label for="review_goal" class="col-md-8 control-label">How many reviews do you want monthly<span class="required">*</span>:</label>
      <div class="col-md-4">
        <input required="required" class="form-control placeholder-no-fix" type="number" min="1" placeholder="Review Goal" name="review_goal" id="review_goal" value="<?=(isset($_POST['review_goal'])?$_POST["review_goal"]:(isset($_GET['review_goal'])?$_GET["review_goal"]:(isset($location->review_goal)?$location->review_goal:'10')))?>" />
      </div>
    </div>

    <div class="form-group col-md-6">
      <label for="lifetime_value_customer" class="col-md-8 control-label">What is the lifetime value of a customer:</label>
      <div class="col-md-4">
        <input class="form-control placeholder-no-fix" type="text" placeholder="Lifetime Value" name="lifetime_value_customer" id="lifetime_value_customer" value="<?=(isset($_POST['lifetime_value_customer'])?$_POST["lifetime_value_customer"]:(isset($_GET['lifetime_value_customer'])?$_GET["lifetime_value_customer"]:(isset($location->lifetime_value_customer)?$location->lifetime_value_customer:'')))?>" />
      </div>
    </div>
  </div>

  <div class="col-md-12 employeetable">
    <div class="middle-row" style="border-bottom: 1px solid #E7ECF0;">
      <a href="#" id="createlink" class="btnLink btnSecondary" style="height: 32px !important; line-height: 16px; padding-left: 15px; padding-right: 15px;">Create Employee</a>
      <?php
  if (strpos($_SERVER['REQUEST_URI'],'location')>0 && isset($employees) && count($employees) > 0) {
      ?>
      <a href="#" id="selectlink" class="btnLink btnSecondary" style="height: 32px !important; line-height: 16px; padding-left: 15px; padding-right: 15px; margin-right: 20px;">Select Employees</a>
      <?php
  }
  ?>
      <h3>EMPLOYEE LIST</h3>
    </div>

    {% for user in users %}
      {% if loop.first %}
        <table cellspacing="0" width="100%" class="table table-striped table-bordered dataTable" style="width: 100%;">
        <thead>
        <tr role="row">
          <th class="" tabindex="0" aria-controls="basic-datatables" rowspan="1" colspan="1" style="width: 141px;" aria-sort="ascending" aria-label="Name: activate to sort column ascending">Name</th>
          <th class="" tabindex="0" aria-controls="basic-datatables" rowspan="1" colspan="1" style="width: 195px;" aria-label="Email: activate to sort column ascending">Email Address</th>
          <th class="" tabindex="0" aria-controls="basic-datatables" rowspan="1" colspan="1" style="width: 90px;" aria-label="Cell Phone: activate to sort column ascending">Cell Phone</th>
        </tr>
        </thead>
        <tbody>
      {% endif %}
      <tr>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.phone }}</td>
      </tr>
      {% if loop.last %}
        </tbody>
        </table>
      {% endif %}
    {% else %}
      No <?=($profilesId==3?'Employees':'Admin Users')?>
    {% endfor %}

  </div>


  <div class="form-actions">
    <button type="submit" id="register-submit-btn" class="btnsignup uppercase"><?=(strpos($_SERVER['REQUEST_URI'],'location')>0?'Save':'Next: Invite Friends')?></button>
  </div>
  <div style="clear: both;">&nbsp;</div>
</form>
<!-- END FORM -->
<div class="overlay" style="display: none;"></div>
<div id="page-wrapper" class="create" style="display: none;">
  <form id="createemployeeform" class="register-form4" action="<?=((strpos($_SERVER['REQUEST_URI'],'location')>0)?'/users/createemployee2/'.$location_id:'/users/createemployee/'.(isset($subscription->subscription_id)?$subscription->subscription_id:'').(isset($_GET['code'])?'?code='.$_GET['code']:''))?>" method="post" style="display: block; padding-top: 0;">
    <div class="closelink close"></div>
    <div class="col-md-12">
      <div class="title"><h3>Create Employee</h3></div>
      <div class="row" style="clear: both;">
        <div class="field">
          <span class="name" style="display: inline-block; min-width: 100px;">Full Name:</span> <input type="text" name="name" id="name" value="" required="required" style="min-width: 200px;padding: 0 0 0 5px;" />
        </div>
      </div>
      <div class="row" style="clear: both;">
        <div class="field">
          <span class="name" style="display: inline-block; min-width: 100px;">Email Address:</span> <input type="email" name="email" id="email" value="" required="required" data-control="mycitycontrol" style="min-width: 200px;padding: 0 0 0 5px;" />
        </div>
      </div>
      <div class="row" style="clear: both;">
        <div class="field">
          <span class="name" style="display: inline-block; min-width: 100px;">Cell Phone:</span> <input type="tel" name="phone" id="phone" value="" style="min-width: 200px;padding: 0 0 0 5px;" />
        </div>
      </div>
      <div class="row" style="clear: both;">
        <div class="field"><button id="createuser" type="submit" class="btnLink btnSecondary">Save</button></div>
      </div>
      <div style="clear: both;">&nbsp;</div>
    </div>
    <input type="hidden" name="reviewgoal" id="reviewgoal" value="" />
    <input type="hidden" name="lifetimevalue" id="lifetimevalue" value="" />
    <input type="hidden" name="type" value="1" />
  </form>
</div>
<div id="page-wrapper2" class="create" style="display: none;">
  <form id="selectemployeeform" class="register-form4" action="/location/selectemployees/<?=$location_id?>" method="post" style="display: block; padding-top: 0;">
    <div class="closelink close"></div>
    <div class="col-md-12">
      <div class="title"><h3 style="margin-bottom: 0; margin-top: 0; padding-bottom: 5px;">Select Employees</h3></div>
      <div class="scroll-wrapper" style="height: 400px; overflow-y: scroll; overflow-x: hidden;">
        <?php
    if (isset($employees) && count($employees) > 0) {
        foreach($employees as $user) {
        ?>
        <div class="" style="clear: both;">
          <div class="field" style="margin: 3px 0;">
            <?php
            $checked = false;
            foreach($user->locations as $location) {
            if ($location_id == $location->location_id) $checked = true;
            }
            ?>
            <span class="name" style="display: inline-block; min-width: 20px;"><input type="checkbox" name="employees[]" value="<?=$user->id?>" <?=($checked?'checked="checked"':'')?> /></span> <span style="min-width: 200px; padding: 0 0 0 5px; display: inline-block;"><?=$user->name?></span>
          </div>
        </div>
        <?php
      }
    }
    ?>
      </div>
      <div class="row" style="clear: both;">
        <div class="field"><button id="selectuser" type="submit" class="btnLink btnSecondary">Save</button></div>
      </div>
      <div style="clear: both;">&nbsp;</div>
    </div>
    <input type="hidden" name="reviewgoal" id="reviewgoal2" value="" />
    <input type="hidden" name="lifetimevalue" id="lifetimevalue2" value="" />
  </form>
</div>
<script type="text/javascript">
  jQuery(document).ready(function($){
    $('#createlink').on('click', function(e) {
      e.preventDefault();
      $('#page-wrapper').show();
      $('.overlay').show();

      $('#reviewgoal').val($('#review_goal').val());
      $('#lifetimevalue').val($('#lifetime_value_customer').val());
    });
    $('.overlay, .closelink').on('click', function(e) {
      e.preventDefault();
      $('#page-wrapper').hide();
      $('#page-wrapper2').hide();
      $('.overlay').hide();
    });
    $('#selectlink').on('click', function(e) {
      e.preventDefault();
      $('#page-wrapper2').show();
      $('.overlay').show();

      $('#reviewgoal2').val($('#review_goal').val());
      $('#lifetimevalue2').val($('#lifetime_value_customer').val());
    });


    $.validator.addMethod("checkEmail",
            function(value, element) {
              var result = false;
              $.ajax({
                type:"POST",
                async: false,
                url: "/users/checkemail", // script to validate in server side
                data: {email: value},
                success: function(data) {
                  result = (data == true) ? true : false;
                }
              });
              // return true if email is exist in database
              return result;
            },
            "The email is already taken. Try another."
    );

    // validate signup form on keyup and submit
    $("#createemployeeform").validate({
      rules: {
        "email": {
          required: true,
          checkEmail: true
        }
      }
    });

    $("#email").on("focusout keyup", function(e){ e.stopPropagation(); });


  });
</script>