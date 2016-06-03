<div id="reviews">
<div class="row">
  <div class="col-md-5 col-sm-5">
    <h3 class="page-title"><?=($profilesId==3?'Employee':'Admin Users')?></h3>
  </div>    
  <?php 
  if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
    if ($is_upgrade) {
      $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) * 100, 0, '.', ''):100);
      if ($percent > 100) $percent = 100;
      ?>
      <div class="col-md-7 col-sm-7">
        <div class="sms-chart-wrapper">
          <div class="title">SMS Messages Sent</div>
          <div class="bar-wrapper">
            <div class="bar-background"></div>
            <div class="bar-filled" style="width: <?=$percent?>%;"></div>
            <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
            <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month_total?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>            
          </div>
          <div class="end-title"><?=$total_sms_month?><br /><span class="goal">Allowed</span></div>
        </div>
      </div>    
      <?php 
    } else {
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
      <?php 
    }
  } //end checking for business vs agency
  ?>
</div>
</div>


<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="">
      <a style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;" class="btnLink" href="/admin/users<?=($profilesId==3?'':'/admin')?>">Back</a>
      <img src="/admin/img/admin_user_edit.png" />
      <span style="display: inline-block; margin-left: 8px; margin-top: 11px;" class="caption-subject bold uppercase"> Edit </span>
    </div>
  </div>
  <div class="portlet-body form">
    {{ content() }}
    <form class="form-horizontal" id="userform" role="form" method="post" autocomplete="off">
    <div class="form-group" style="padding-top: 30px;">
      <label for="name" class="col-md-2 control-label">Name:</label>
      <div class="col-md-4">
        {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name']) }}
      </div>
    </div>
    <div class="form-group">
      <label for="email" class="col-md-2 control-label">Email:</label>
      <div class="col-md-4">
        {{ form.render("email", ['class': 'form-control', 'placeholder': 'Email', 'type': 'email']) }}
      </div>
    </div>
    <div class="form-group">
      <label for="phone" class="col-md-2 control-label">Cell Phone:</label>
      <div class="col-md-4">
        {{ form.render("phone", ['class': 'form-control', 'placeholder': 'Cell Phone', 'type': 'tel']) }}
      </div>
    </div>
    <?php 
    if ($profilesId!=3) {
      ?>
      <div class="form-group">
        <label for="profilesId" class="col-md-2 control-label">Type:</label>
        <div class="col-md-4">
          <select name="type" id="type" class="form-control" style="width: 100%;">
            <option value="">Admin</option>
            <option value="1" <?=(isset($_POST['type']) && $_POST['type']=='1'?'selected="selected"':($user->is_employee==1?'selected="selected"':''))?>>Admin & Employee</option>
          </select>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="form-group">
      <label for="locations" class="col-md-2 control-label">Location:</label>
      <div class="col-md-8">
        <div id="userlocationselect" style="display: none;">
        <?php 
        if ($profilesId!=3) {
          $checked = false;
          if ($user->is_all_locations==1) $checked = true;
          //check post also
          if(!empty($_POST['locations'])) {
            foreach($_POST['locations'] as $check) {
              if ($check == 'all') $checked = true;
            }
          }
          ?>
          <div class="location-data">
            <input type="checkbox" name="locations[]" value="all" <?=($checked?'checked="checked"':'')?> /> All
          </div>
          <?php
        }
        
        $found = false;
        foreach($locations as $data) { 
          $found = true;
          
          //now check if this record should be checked
          $checked = false;
          foreach($userlocations as $ul) { 
            if ($ul->location_id == $data->location_id) $checked = true;
          }
          //check post also
          if(!empty($_POST['locations'])) {
            foreach($_POST['locations'] as $check) {
              if ($check == $data->location_id) $checked = true;
            }
          }
          ?>
          <div class="location-data">
            <input type="checkbox" name="locations[]" value="<?=$data->location_id?>" <?=($checked?'checked="checked"':'')?> /> <?=$data->name?>
          </div>
          <?php
        } 
        if (!$found) {
          ?>
          No locations found
          <?php
        }  
        ?>
        </div>
        <div id="userlocationall" style="display: none;">All</div>
      </div>
    </div>
    <div class="form-group">
      <div class="error" id="emailerror" style="display: none;">
        Invalid email.
      </div>
      <div class="col-md-6">
        <input type="submit" class="btnLink" value="Save" style="height: 42px; line-height: 14px; padding: 15px 36px; float: right;" />
      </div>
    </div>
    {{ form.render("id") }}
  </form>
  </div>
</div>
<script type="text/javascript">
//Interactive Chart
jQuery(document).ready(function($){
  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  
  

  $("#userform").on("submit", function(e){

    if($('#email').val() != '' && !isEmail($('#email').val())){
      e.preventDefault();
      $('#emailerror').show();
      return false;
    }
    $('#emailerror').hide();
    return true;
  });
});
</script>