<ul class="pager">
  <li class="previous pull-left">
    <a href="/admin/users<?=($profilesId==3?'':'/admin')?>">&larr; Go Back</a>
  </li>
</ul>

{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> Create <?=($profilesId==3?'Employee':'Admin User')?> </span>
    </div>
  </div>
  <div class="portlet-body form">
    <form class="form-horizontal" role="form" id="userform" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Name</label>
        <div class="col-md-4">
          {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name']) }}
        </div>
      </div>
      <!--<div class="form-group">
        <label for="profilesId" class="col-md-2 control-label">Role</label>
        <div class="col-md-4">
          <select name="profilesId" id="profilesId">
            <option value="">...</option>
            <?php if ($this->session->get('auth-identity')['profile'] == 'Super Admin') { ?>
            <option value="4" <?=(isset($_POST['profilesId']) && $_POST['profilesId']==4?'selected="selected"':($user->profilesId==4?'selected="selected"':''))?>>Super Admin</option>
            <?php } ?>
            <?php if ($this->session->get('auth-identity')['profile'] == 'Super Admin' || $this->session->get('auth-identity')['profile'] == 'Agency Admin') { ?>
            <option value="1" <?=(isset($_POST['profilesId']) && $_POST['profilesId']==1?'selected="selected"':($user->profilesId==1?'selected="selected"':''))?>>Agency Admin</option>
            <?php } ?>
            <option value="2" <?=(isset($_POST['profilesId']) && $_POST['profilesId']==2?'selected="selected"':($user->profilesId==2?'selected="selected"':''))?>>Business Admin</option>
            <option value="3" <?=(isset($_POST['profilesId']) && $_POST['profilesId']==3?'selected="selected"':($user->profilesId==3?'selected="selected"':''))?>>Employee</option>
          </select>
        </div>
      </div>-->
      <div class="form-group">
        <label for="email" class="col-md-2 control-label">Email</label>
        <div class="col-md-4">
          {{ form.render("email", ['class': 'form-control', 'placeholder': 'Email', 'type': 'email']) }}
        </div>
      </div>
      <div class="form-group">
        <label for="phone" class="col-md-2 control-label">Cell Phone</label>
        <div class="col-md-4">
          {{ form.render("phone", ['class': 'form-control', 'placeholder': 'Phone', 'type': 'tel']) }}
        </div>
      </div>
      <div class="form-group" <?=($profilesId==3?'':' style="display: none;"')?>>
        <label for="locations" class="col-md-2 control-label">Locations</label>
        <div class="col-md-8">
          <div id="userlocationselect" style="display: none;">
          <?php 
          $found = false;
          foreach($locations as $data) { 
            $found = true;
          
            //now check if this record should be checked
            $checked = false;
            //check post 
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
          <div id="userlocationall" <?=($profilesId==3?' style="display: none;"':'')?>>All</div>
        </div>
      </div>
      <div class="form-group">
        <div class="error" id="emailerror" style="display: none;">
          Invalid email.
        </div>
        <div class="col-md-offset-2 col-md-10">
          {{ submit_button("Save", "class": "btn btn-big btn-success") }}
        </div>
      </div>
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