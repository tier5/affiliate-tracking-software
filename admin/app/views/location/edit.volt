<!-- BEGIN SAMPLE FORM PORTLET-->
<style type="text/css">
    .pnlAddLocation.short {
        min-height: 230px !important;
    }
</style>
<div class="portlet light bordered">
    {% if !ComingFromSignup %}
        <div class="portlet-title">
            <div class="">
                <a href="/location" class="btnLink" style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;">Back</a>
                <i class="icon-pencil fa-user"></i>
                <span class="caption-subject bold uppercase" style="display: inline-block; margin-left: 8px; margin-top: 11px;"> Edit </span>
            </div>
        </div>
    {% endif %}
    <div class="portlet-body form">
        {{ content() }}
        <form id="hiddenLocationForm" class="form-horizontal" role="form" method="post" autocomplete="off" {% if ComingFromSignup %} action="/session/signup3" {% endif %}>
            {% if !ComingFromSignup %}
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">Location Name:</label>
                    <div class="col-md-10" style="padding-top: 8px;">
                        <input class="col-md-8 form-control" type="name" autocomplete="off" placeholder="Location Name" style="width: 40%;" id="name" name="name" value="<?=(isset($_POST['name'])?$_POST[" name"]:(isset($location->name)?$location->name:''))?>"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">Region</label>
                    <div class="col-md-10" style="padding-top: 8px;">
                        <select name="region_id" id="region_id" class="col-md-8 form-control" style="width: 40%;">
                            <option value="">None</option>
                            <?php
              foreach($regions as $data) {
                ?>
                            <option value="<?=$data->region_id?>"
                            <?=(isset($_POST['region_id']) && $_POST['region_id']==$data->
                            region_id?'selected="selected"':($data->region_id==$location->region_id?'selected="selected"':''))?>><?=$data->
                            name?></option>
                            <?php
              }
              ?>
                        </select>&nbsp;&nbsp;
                        <a href="#" onclick="$('#createregion').show();$('.overlay').show();" class="btnLink">Create
                            Region</a>
                        <a href="#" id="btnDeleteRegion" class="btnLink">Delete Region</a>

                        <div id="createregion" style="display: none;">
                            <div class="closelink close" onclick="$('#createregion').hide();"></div>
                            <div class="col-md-12">
                                <div class="title"><h3>Create Region</h3></div>
                                <div class="field"><span class="name">Region Name:</span>
                                    <input type="text" value="" name="regionname" id="regionname"/>
                                    <input id="btnCreateRegion" type="button" class="btnLink" value="Save"/></div>
                            </div>
                        </div>
                        <!-- END FORM -->

                    </div>
                </div>


                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">Address:</label>
                    <div class="col-md-10" style="padding-top: 8px;">
                        <span class="googleAddress"><?=$location->address?> <?=$location->locality?>, <?=$location->
                            state_province?> <?=$location->postal_code?></span>
                    </div>
                </div>
            {% endif %}

            <!-- BEGIN FORM -->
            <div class="register-form" id="locationform1" style="display: none;">
                <div class="pnlAddLocation">
                    <h3>Add Location</h3>
                    <p class="hint">Look Up Your Location And Select The Correct Listing.</p>
                    <div class="locationform">
                        <div class="search_name">
                            <input class="form-control placeholder-no-fix" type="text" placeholder="Location Name" id="location_name" name="location_name" value="<?=(isset($_POST['location_name'])?$_POST[" location_name"]:'')?>"
                            />
                        </div>
                        <div class="search_name_location">
                            <input class="form-control placeholder-no-fix" type="text" placeholder="Postal Code" id="zip_code" name="zip_code" value="<?=(isset($_POST['zip_code'])?$_POST[" zip_code"]:'')?>"
                            />
                        </div>
                        <div class="search_name_btn">
                            <button type="submit" id="register-submit-btn" class="btnsignup uppercase">Search</button>
                        </div>
                    </div>
                </div>

                <div style="clear: both;">&nbsp;</div>
                <div id="loader" style="display: none;"></div>
                <div class="col-md-12" id="locationresults">
                    <div id="relevant-result-list" style="display: none;">
                    </div>
                </div>
                <div style="clear: both;">&nbsp;</div>
            </div>


            <div class="register-form" id="hiddenForm" style="display: block;">
                <div class="pnlAddLocation short col-md-4">
                    <div class="title">GOOGLE: <span class="title-answer">Successfully connected</span></div>
                    <div class="field"><span class="name">Business Name:</span> <span id="googleName">
                        <?=$objGoogleReviewSite->name ?: $location->name; ?>

                        </span></div>
                    <div class="field bottom">
                        <span class="name">
                            Location:
                        </span>
                        <span class="googleAddress" id="googleAddress">
                            <?=$objGoogleReviewSite->address ?: $location->address?>
                            <?=$objGoogleReviewSite->locality ?: $location->locality?>,
                            <?=$objGoogleReviewSite->state_province ?: $location->state_province?>
                            <?=$objGoogleReviewSite->postal_code ?: $location->postal_code?>
                        </span>
                    </div>
                    <div class="buttons">
                        <a class="btnLink" id="googleLink" href="<?=$objGoogleReviewSite->url ?: 'https://maps.google.com/?cid={$google->external_id}'; ?>" target="_blank"><img src="/img/icon-eye.gif"/>View</a>
                        {% if GoogleMyBusinessConnected %}
                             <a href="/location/disconnectGoogle/<?=$location->location_id; ?>/{{ ComingFromSignup }}" class="btnSecondary" id="gmb_signin">Disconnect?</a>
                        {% else %}
                            <a href="{{ authUrl }}" class="btnSecondary" target="_blank" id="gmb_signin">Authenticate Google My Business</a>
                        {% endif %}
                    </div>
                </div>

                <div class="pnlAddLocation short col-md-4">
                    <div class="title">FACEBOOK:
                        <span class="title-answer">
                            {% if !FacebookConnected %}
                            <span class="facebooknotfound">Not Found</span>
                            {% else %}
                            <span class="facebooknotfound">Successfully connected</span>
                            {% endif %}

                        </span>
                    </div>
                    {% if FacebookConnected %}
                    <div class="field"><span class="name">Business Name:</span>
                        <span id="facebookName" class="facebookfound"><?=$facebook->name; ?></span>
                    </div>
                    {% endif %}
                    <br />
<!--                    <div class="field bottom">
                        <span class="name">Location:</span>
                        <span id="facebookLocation" class="facebookfound" ><?=$facebook->name ? $facebook->address : $location->address?> <?=$facebook->name ? $facebook->locality . ', ' : $location->locality . ', '?><?=$facebook->name ? $facebook->state_province : $location->state_province?> <?=$facebook->name ? $facebook->postal_code : $location->postal_code?></span>
                    </div>
-->
                  <div class="buttons" {% if FacebookConnected %} style="margin-top: 43px" {% else %} style="margin-top: 70px;" {% endif %}>
                    {% if !FacebookConnected %}
                    <a href="/location/getAccessToken/<?=$location->location_id; ?>/{{ ComingFromSignup }}" id="btnAuthenticateFacebook" class="btnLink">Authenticate Facebook</a>
                    {% endif %}
                    {% if FacebookConnected %}
                        <a href="/location/disconnectFacebook/<?=$location->location_id; ?>/{{ ComingFromSignup }}" class="btnSecondary" id="gmb_signin">Disconnect?</a>
                    {% endif %}
                    </div>
                </div>


            <div class="facebook-results facebooknotfound">

            </div>
    </div>
    <input type="hidden" id="facebook_access_token" value="<?=$facebook_access_token?>"/>

    <div class="pnlAddLocation short col-md-4">
        <div class="title">YELP: <span class="title-answer"><span class="yelpnotfound" <?=(isset($yelp) && isset($yelp->
                external_id) && $yelp->external_id != ''?' style="display: none;"':'')?>>Not </span>Successfully connected</span></div>
        <div class="field"><span class="name">Business Name:</span>
            <span id="yelpName" class="yelpfound" <?=(isset($yelp) && isset($yelp->external_id) && $yelp->external_id !=
            ''?'':' style="display: none;"')?>><?=$location->name?></span>
            <input id="yelpsearchfield" class="yelpnotfound" <?=(isset($yelp) && isset($yelp->external_id) &&
            $yelp->external_id != ''?' style="display: none;"':'')?> type="name"
            value="<?=str_replace("\"","&quot;",$location->name)?>" />
        </div>
        <div class="field bottom"><span class="name">Location:</span>
            <span id="yelpLocation" class="yelpfound" <?=(isset($yelp) && isset($yelp->external_id) &&
            $yelp->external_id != ''?'':' style="display: none;"')?>><?=$location->address?> <?=$location->
            locality?>, <?=$location->state_province?> <?=$location->postal_code?></span>
            <input id="yelpsearchfield2" type="name" value="<?=str_replace(" \"","&quot;",$location->postal_code)?>"
            class="yelpnotfound" <?=(isset($yelp) && isset($yelp->external_id) && $yelp->external_id != ''?'
            style="display: none;"':'')?> />
        </div>
        <div class="buttons yelpfound"
        <?=(isset($yelp) && isset($yelp->external_id) && $yelp->external_id != ''?'':' style="display:
        none;"')?>><a id="yelpLink" href="http://yelp.com/biz/<?=(isset($yelp) && isset($yelp->external_id) && $yelp->external_id != ''?$yelp->external_id:'')?>" target="_blank"><img src="/img/icon-eye.gif"/>
            View</a>
        <a class="btnLink" href="#" onclick="$('.yelpfound').hide();$('.yelpnotfound').show();return false;"><img src="/img/icon-pencil.png"/>
            Update Location</a>

            {% if YelpConnected %}
                <a href="/location/disconnectYelp/<?=$location->location_id; ?>/{{ ComingFromSignup }}" class="btnSecondary" id="gmb_signin">Disconnect?</a>
            {% endif %}

            </div>
    <div class="buttons yelpnotfound"
    <?=(isset($yelp) && isset($yelp->external_id) && $yelp->external_id != ''?' style="display:
    none;"':'')?>><a class="btnLink" href="#" onclick="findBusinessYelp();return false;"><img src="/img/icon-maglass.gif"/>
        Search For Business</a>
    <a class="btnLink" href="#" id="urllinkyelp" onclick="$('#urltype').val('yelp');"><img src="/img/icon-link.gif"/>
        Enter URL</a></div>
</div>
</div>

<div style="clear: both;">&nbsp;</div>
<input type="hidden" name="form" value="2"/>

{{ form.render("phone") }}
  {{ form.render("address") }}
  {{ form.render("locality") }}
  {{ form.render("state_province") }}
  {{ form.render("postal_code") }}
  {{ form.render("country") }}
  {{ form.render("latitude") }}
  {{ form.render("longitude") }}

<input id="yelp_id" type="hidden" name="yelp_id" value="<?=(isset($yelp) && isset($yelp->api_id) && $yelp->api_id != ''?$yelp->api_id:'')?>"/>
<input id="facebook_page_id" type="hidden" name="facebook_page_id" value="<?=(isset($facebook) && isset($facebook->external_id) && $facebook->external_id != ''?$facebook->external_id:'')?>"/>
<input id="google_place_id" type="hidden" name="google_place_id" value="<?=(isset($google) && isset($google->external_id) && $google->external_id != ''?$google->external_id:'')?>"/>
<input id="google_api_id" type="hidden" name="google_api_id" value="<?=(isset($google) && isset($google->api_id) && $google->api_id != ''?$google->api_id:'')?>"/>

<div class="overlay" style="display: none;"></div>
<div id="page-wrapper" style="display: none;">
    <div class="closelink close"></div>
    <div class="col-md-12">
        <div class="yelp-results yelpnotfound">

        </div>
        <div class="title"><h3>Enter URL</h3></div>
        <div class="field"><span class="name">Enter URL:</span> <input type="text" value="" name="url" id="url"/>
            <a class="btnLink" href="#" onclick="searchByURL();return false;">Save</a></div>
    </div>
</div><input type="hidden" name="urltype" id="urltype" value=""/>
<!-- END FORM -->

<div class="row form-group">
    <div class="col-xs-4">
        {%  if include_customize_survey %}
            <button type="button" id="register-submit-btn" class="btnsignup uppercase" onclick="CustomizeButton();" style="width: 50% !important;">Next: Customize Survey</button>
        {%  endif %}
    </div>
    {% if ComingFromSignup %}
        <div class="form-actions col-md-12">
            <button type="button" onclick="window.location.href = '/session/signup3';" id="register-submit-btn" class="btnsignup uppercase">Next: Customize Survey</button>
        </div>
    {% else %}
        <div class="col-xs-6">
            <input type="submit" class="btnLink" value="Save" style="height: 42px; line-height: 14px; margin-left: 20%; padding: 15px 36px; text-align: center;"/>
        </div>
    {% endif %}
</div>
<div style="clear: both;">&nbsp;</div>
<input type="hidden" name="GoToNextStep" value="0" id="GoToNextStep"/>
</form>
</div>
</div>
<script type="text/javascript">
    function CustomizeButton() {
        $('#GoToNextStep').val(1);
        $('#hiddenLocationForm').submit();
    }
    jQuery(document).ready(function ($) {

        $("#btnCreateRegion").click(function () {
            $.ajax({
                url: "/location/region?name=" + $("#regionname").val(),
                type: "post",
                success: function (response) {
                    var response = $.parseJSON(response);
                    // the region was created, so add it to the drop down
                    $('#region_id')
                            .append($("<option></option>")
                                    .attr("value", response.id)
                                    .text($("#regionname").val()));
                    //console.log('Test:'+response.id);
                    $("#region_id").val(response.id);
                    $('#createregion').hide();
                    $('.overlay').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //nothing was created
                    $('#createregion').hide();
                    $('.overlay').hide();
                }
            });
        });

        $("#btnDeleteRegion").click(function () {
            $.ajax({
                url: "/location/regiondelete/" + $("#region_id").val(),
                type: "post",
                success: function (response) {
                    //var response = $.parseJSON(response);
                    // the region was created, so add it to the drop down
                    $("#region_id option[value='" + $("#region_id").val() + "']").remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //nothing was deleted
                }
            });
        });

        $('.fancybox').fancybox();
    });
</script>

<script src="/js/location.js" type="text/javascript"></script>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        // Scanning form validation and ajax submit
        $('#register-submit-btn').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                beforeSend: function () {
                    $("#loader").show();
                    $("#relevant-result-list").hide();
                    $("#scan-tool-landing-page").hide();
                    $(".footerscan").hide();
                    $("#hiddenForm").hide();
                },
                complete: function () {
                    $("#loader").hide();
                    $("#relevant-result-list").show();
                    $(".footerscan").show();
                    // $("#relevant-result-list").empty();
                },
                url: '/session/googlesearchapi/',
                data: {
                    location_name: $.trim($('#location_name').val()),
                    zip: $.trim($('#zip_code').val())
                },
                dataType: 'json',
                //async  : false,
                success: function (response) {
                    if (response.errorMsg) {
                        $("#relevant-result-list").html(response.errorMsg);
                    }
                    else {
                        $("#relevant-result-list").html(response.HTML);
                    }
                }
            });
        });

        $('#urllink, #urllinkyelp').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').show();
            $('.overlay').show();
        });
        $('.overlay, .closelink').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').hide();
            $('.overlay').hide();
        });

    });


</script>