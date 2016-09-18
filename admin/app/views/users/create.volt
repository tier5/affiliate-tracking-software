<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="">
            <a style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;" class="btnLink btnSecondary" href="/users<?=($profilesId==3?'':'/admin')?>">Back</a>
            <i class="icon-pencil fa-user"></i>
            <span style="display: inline-block; margin-left: 8px; margin-top: 11px;" class="caption-subject bold uppercase"> Create </span>
        </div>
    </div>
    <div class="portlet-body form">
        {{ content() }}
        <form class="form-horizontal" role="form" id="userform" method="post" autocomplete="off">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-4">
                    {{ form.render("name", ["class": 'form-control', 'placeholder': 'Name', 'type': 'name']) }}
                </div>
            </div>
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
            <?php

        ?>
            <div class="form-group">
                <label for="profilesId" class="col-md-2 control-label">Role:</label>
                <div class="col-md-4">

                    <select name="type" id="type" class="form-control" style="width: 100%;">
                        <?php $Selected = $_POST['type'] == 'Admin' ? 'selected' : ''; ?>
                        <option value="Admin" {{ Selected }}>Admin</option>
                        <?php $Selected = $_POST['type'] == 'User' ? 'selected' : ''; ?>
                        <option value="User" {{ Selected }}>User</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Is Employee?</label>
                <?php $Selected = $_POST['is_employee'] == 'Yes' || $_GET['create_employee'] ? 'checked="checked"' : ''; ?>
                <label class="radio-inline">Yes <input type="radio" value="Yes" name="is_employee" {{ Selected }} /></label>
                <?php $Selected = ($_POST['is_employee'] == 'No' || !$_POST['is_employee']) && !$_GET['create_employee'] ? 'checked="checked"' : ''; ?>
                <label class="radio-inline">No <input type="radio" value="No" name="is_employee" {{ Selected }} /></label>
            </div>

            <div class="form-group">
                <label for="locations" class="col-md-2 control-label">Locations</label>
                <div class="col-md-8">
                    <div id="userlocationselect" style="display: none;">
                        <?php
          if ($profilesId!=3) {
            $checked = false;
            //check post also
            if(!empty($_POST['locations'])) {
              foreach($_POST['locations'] as $check) {
                if ($check == 'all') $checked = true;
              }
            }
            ?>
                        <div class="location-data">
                            <input type="checkbox" name="locations[]" value="all" <?=($checked?'checked="checked"':'')?>
                            /> All
                        </div>
                        <?php
          }

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
                            <input type="checkbox" name="locations[]" value="<?=$data->location_id?>" <?=($checked?'checked="checked"':'')?>
                            /> <?=$data->name?>
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
                    <div id="userlocationall"
                    <?=($profilesId==3?' style="display: none;"':'')?>>All
                </div>
            </div>
    </div>
    <div class="form-group">
        <div class="error" id="emailerror" style="display: none;">
            Invalid email.
        </div>
        <div class="col-md-offset-2 col-md-10">
            <input type="submit" class="btnLink btnSecondary" value="Save" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: right;"/>
        </div>
    </div>
    </form>
</div>
</div>

<script type="text/javascript">
    //Interactive Chart
    jQuery(document).ready(function ($) {
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }


        $("#userform").on("submit", function (e) {

            if ($('#email').val() != '' && !isEmail($('#email').val())) {
                e.preventDefault();
                $('#emailerror').show();
                return false;
            }
            $('#emailerror').hide();
            return true;
        });
    });
</script>