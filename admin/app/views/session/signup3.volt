{{ content() }}


<!-- BEGIN FORM -->
<form
  class="register-form"
  id="signup3form"
  action="<?=((strpos($_SERVER['REQUEST_URI'],'location')>0)?'/location/create2/'.$location_id:'/session/signup3/'.(isset($subscription->subscription_id)?$subscription->subscription_id:'').(isset($_GET['code'])?'?code='.$_GET['code']:''))?>"
  method="post"
  style="display: block;"
  enctype="multipart/form-data">
    <h3>Customize Survey</h3>
    <p class="hint">This is the survey your recent customers will see.</p>
    <ul class="nav nav-tabs">
        <li><a href="#tab_sms" data-toggle="tab"> Text Message Preview </a></li>
        <li class="active"><a href="#tab_survey" data-toggle="tab"> Survey Preview </a></li>
    </ul>

    <div class="row" style="margin-left: 2px;">
        <div class="col-md-6 col-sm-6" style="margin-top: 30px;">
            <div class="form-group">
                <label class="control-label">Location Name:</label>
                <input
                  class="form-control placeholder-no-fix"
                  type="name"
                  required
                  autocomplete="off"
                  placeholder="Business Name"
                  id="name"
                  name="agency_name"
                  value="<?=(isset($_POST['agency_name'])?$_POST[" agency_name"]:(isset($location->name)?$location->name:''))?>"
                />
            </div>

            <div class="form-group">
                <div class="image-upload">
                    <label for="sms_message_logo_path">
                        <a class="btnLink btnSecondary">Add Logo</a>
                    </label>
                    <input type="file" id="sms_message_logo_path" name="sms_message_logo_path" style="display: none;">
                    <span class="help-block" style="">PNG, JPG or GIF</span>
                </div>
            </div>
            <div class="row">
<!--
<div class="form-group col-md-6 ">
<label class="control-label" for="sms_top_bar">Top Bar:</label>
<div class="color-select">
<input
type="text"
id="sms_top_bar"
name="sms_top_bar"
class="form-control"
data-control="hue"
value="<?=(isset($_POST['sms_top_bar'])?$_POST["sms_top_bar"]:(isset($location->sms_top_bar)?$location->sms_top_bar:'#454545'))?>"
style="margin: 4px;"
/>
</div>
</div>-->
              <div class="form-group col-md-6">
                <label class="control-label" for="sms_button_color">Button Color:</label>
                <div class="color-select">
                    <input
                      type="text"
                      id="sms_button_color"
                      name="sms_button_color"
                      class="form-control"
                      data-control="hue"
                      value="<?=(isset($_POST['sms_button_color'])?$_POST[" sms_button_color"]:(isset($location->sms_button_color)?$location->sms_button_color:'#454545'))?>"
                      style="margin: 4px;"
                    />
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label">Text Message Preview:</label>
              <textarea
                  class="form-control
                  placeholder-no-fix"
                  autocomplete="off"
                  name="sms_text_message_default"
                  id="sms_text_message_default"
                ><?php

                  if (isset($_POST['sms_text_message_default']))  {
                    echo $_POST["sms_text_message_default"];
                  } else {
                    if ( (isset($location->SMS_message)) ) {
                    	echo $location->SMS_message;
                    } else {
                    	echo $location->sms_text_message_default;
                    }
                  }

                    
                  
                ?></textarea>
                <p class="help-block">This is the default content of the text message you send to your customers, the max characters is 166.</p>
            </div>

            <div class="form-group">
                <label class="control-label" style="width: 100%;">Send Test Text Message:</label>
                <input
                  class="form-control
                  placeholder-no-fix"
                  type="name"
                  autocomplete="off"
                  placeholder="Cell Phone"
                  name="cell_phone"
                  id="cell_phone"
                  value="<?=(isset($_POST['cell_phone'])?$_POST[" cell_phone"]:'')?>"
                  style="float: left; width: 40%; margin-right: 15px;"
                />
                <a href="#" class="btnLink btnSecondary" id="sendsmslink" style="float: left;line-height: 19px;">Send</a>
            </div>
            <div id="divSMSResults" class="form-group" style="clear: both;"></div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="tab-content">

                <!-- START Survey Preview  -->
                <div class="tab-pane fade active in" id="tab_survey">
                    <div class="phone-wrapper">
                        <div class="phone">
                            <div class="phone-content">
                                <div class="review index">
                                    <div class="rounded-wrapper">
                                        <div class="rounded">
                                            <div class="page-logo business-name">
                                            </div>
                                            <div class="question">Would You Recommend Us?</div>
                                            <div class="row text-center">
                                                <a href="#" class="btn-lg btn-recommend">Yes</a>
                                            </div>
                                            <div class="row text-center last"><a href="#" class="btn-lg btn-nothanks">No Thanks</a></div>
                                        </div>
                                        <div class="subtext text-center">Next Step, Write A Review</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Survey Preview  -->

                <!-- START Text Message Preview  -->
                <div class="tab-pane fade" id="tab_sms">
                    <div class="phone-wrapper">
                        <div class="phone">
                            <div class="phone-content">
                                <div class="">
                                    <img src="/img/phone-sms.png"/>
                                </div>
                                <div class="content-top">Hi {name}, thanks for visiting
                                    <span class="business-name"></span> we'd really appreciate your feedback by clicking the following link {link}. Thanks!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Text Message Preview  -->

            </div>
        </div>
    </div>
    <div class="form-actions">
        <div class="error" id="fileerror" style="display: none;">
            Invalid file type. Only gif, png, jpg or jpeg file extensions are allowed.
        </div>
        <button type="submit" id="register-submit-btn" class="btnsignup uppercase">Next: Enter Employees</button>
    </div>
    <div style="clear: both;">&nbsp;</div>
</form>
<!-- END FORM -->
<script type="text/javascript">
    //Interactive Chart
    jQuery(document).ready(function ($) {
        $('#sms_top_bar').minicolors();
        $('#sms_button_color').minicolors();

        $("#signup3form").on("submit", function (e) {
            var logo_path = $('#logo_path').val();
            if (logo_path && logo_path !== '') {
                var ext = $('#logo_path').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    e.preventDefault();
                    $('#fileerror').show();
                    return false;
                }
            }
            $('#fileerror').hide();
            return true;
        });


        $("#sms_top_bar").change(function () {
            $('.phone-header').css("border-top", '8px solid ' + $(this).val());
        });
        $("#sms_button_color").change(function () {
            $('.review .rounded .row .btn-recommend, .review .rounded .row .btn-recommend:hover').css("background", $("#sms_button_color").val());
        });
        $('.review .rounded .row .btn-recommend, .review .rounded .row .btn-recommend:hover').css("background", $("#sms_button_color").val());
        //$('.review .rounded .row .btn-recommend, .review .rounded .row .btn-recommend:hover').css( "background", 'none');
        //$('.review .rounded .row .btn-recommend, .review .rounded .row .btn-recommend:hover').css( "border-color", 'none');


        function changeColor(hex) {
            var bigint = parseInt(hex.replace('#', ''), 16);
            var r = (bigint >> 16) & 255;
            var g = (bigint >> 8) & 255;
            var b = bigint & 255;
            $('body').css("background-color", 'rgba(' + r + ', ' + g + ', ' + b + ', 0.8)');
            $('.page-sidebar .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a').css("border-top", hex);
            $('.page-sidebar .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a').css("color", "#FFFFFF");
            $('.page-sidebar .page-sidebar-menu > li.active.open > a, .page-sidebar .page-sidebar-menu > li.active > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a').css("background-color", hex);
            $('li.nav-item:hover, li.nav-item a:hover, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a').css("background-color", hex + ' !important');
            //console.log("background-color: "+hex+" !important;");
            $("li.nav-item:hover, li.nav-item a:hover, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a").css("cssText", "background-color: " + hex + " !important;");

            $('#sms_top_bar').val(hex);
            $('.minicolors-swatch-color').css("background-color", hex);
        }


        $(document).ready(function () {
            $('#name').on('keyup paste', namechange);
            $('#sms_text_message_default').on('keyup paste', namechange);
            $('#sms_text_message_default').click(function() {
              var activeTab = $('[href=#tab_sms]');
              activeTab && activeTab.tab('show');
            });
        });


        function namechange() {
            $('.business-name').text($('#name').val());
            $('.content-top').text($('#sms_text_message_default').val());
        }

        namechange();

        function readURL(input) {
            if (input.files) {
                for (var i = 0; i < input.files.length; i++) {
                    if (input.files[i]) {
                        preview(input, i);
                    }
                }
            }
        }

        function preview(input, i) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.page-logo.business-name').html('<img src="' + e.target.result + '" alt="Image currently not accessible" />');
            };

            reader.readAsDataURL(input.files[i]);
        }

        $('input[type=file]').on('change', function () {
            readURL(this);
        });


        $("#sendsmslink").click(function () {
            //e.preventDefault();
            var datavar = {
                "body": $('#sms_text_message_default').val(),
                "name": $('#name').val(),
                "cell_phone": $('#cell_phone').val(),
                "id": '<?=$id?>',
                "location_id": '<?=$location_id?>'
                
            };
            $.ajax({
                url: "/session/sendsms",
                data: datavar,
                success: function (result) {
                    $("#divSMSResults").html(result);
                }
            });
            return false;
        });

        $('#signup3form').validate();

    });
</script>
