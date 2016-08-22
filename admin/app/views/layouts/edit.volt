<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="">
      <a href="/location" class="btnLink" style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;">Back</a>
      <i class="icon-pencil fa-user"></i>
      <span class="caption-subject bold uppercase" style="display: inline-block; margin-left: 8px; margin-top: 11px;"> Edit </span>
    </div>
  </div>
  <div class="portlet-body form">
    {{ content() }}
    <form id="hiddenForm" class="form-horizontal" role="form" method="post" autocomplete="off">

      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Location Name:</label>
        <input class="col-md-8 form-control" type="name" autocomplete="off" placeholder="Location Name" style="width: 40%;" id="name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:(isset($location->name)?$location->name:''))?>" />
      </div>

      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Region</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <select name="region_id" id="region_id">
            <option value="">None</option>
            <?php
          foreach($regions as $data) {
            ?>
            <option value="<?=$data->region_id?>" <?php echo(isset($_POST['region_id']) && $_POST['region_id']==$data->region_id?'selected="selected"':($data->region_id==$location->region_id?'selected="selected"':''))?>><?=$data->name?></option>
            <?php
          }
          ?>
          </select>&nbsp;&nbsp;
          <a href="#createregion" class="fancybox btnLink">Create Region</a>
          <div id="createregion" style="width:400px;display: none;">
            Region Name: <input type="text" placeholder="Name" class="form-control" name="regionname" id="regionname" />
            <div style="margin-top: 10px;"><input id="btnCreateRegion" type="button btnLink" class="btn btn-big btn-success" value="Save" /></div>
          </div>
        </div>
      </div>

      <!-- BEGIN FORM -->
      <div class="register-form" id="locationform1" style="display: none;" >
        <div class="pnlAddLocation">
          <h3>Add Location</h3>
          <p class="hint">Look Up Your Location And Select The Correct Listing.</p>
          <div class="locationform">
            <div class="search_name">
              <input class="form-control placeholder-no-fix" type="text" placeholder="Location Name" id="location_name" name="location_name" value="<?=(isset($_POST['location_name'])?$_POST["location_name"]:'')?>" />
            </div>
            <div class="search_name_location">
              <input class="form-control placeholder-no-fix" type="text" placeholder="Postal Code" id="zip_code" name="zip_code" value="<?=(isset($_POST['zip_code'])?$_POST["zip_code"]:'')?>" />
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
        <div class="">
          <div class="pnlAddLocation short col-md-4">
            <div class="title">GOOGLE: <span class="title-answer">Found</span></div>
            <div class="field"><span class="name">Business Name:</span> <span id="googleName"><?=$location->name?></span></div>
            <div class="field bottom"><span class="name">Location:</span> <span id="googleAddress"><?=$location->address?> <?=$location->locality?>, <?=$location->state_province?> <?=$location->postal_code?></span></div>
            <div class="buttons"><a class="btnLink" id="googleLink" href="https://maps.google.com/?cid=<?=$google->external_id?>" target="_blank"><img src="/img/icon-eye.gif" /> View</a> <a class="btnLink" href="#" onclick="changeLocation();$('#locationform1').show();return false;"><img src="/img/icon-pencil.gif" /> Change Location</a></div>
            <br>Have a google my business account? <a href="#">Sign In</a></div>
          </div>

          <div class="pnlAddLocation short col-md-4">
            <div class="title">FACEBOOK: <span class="title-answer"><span class="facebooknotfound">Not </span>Found</span></div>
            <div class="field"><span class="name">Business Name:</span> <span id="facebookName" class="facebookfound"><?=$location->name?></span><input class="facebooknotfound" id="facebooksearchfield" type="name" value="<?=str_replace("\"","&quot;",$location->name)?>" /></div>
            <div class="field bottom"><span class="name">Location:</span> <span id="facebookLocation" class="facebookfound"><?=$location->address?> <?=$location->locality?>, <?=$location->state_province?> <?=$location->postal_code?></span><input id="facebooksearchfield2" type="name" value="<?=str_replace("\"","&quot;",$location->postal_code)?>" class="facebooknotfound" /></div>
            <div class="buttons facebookfound" <?=($facebook->external_id?'':' style="display: none;"')?>><a id="facebookLink" href="http://facebook.com/" target="_blank"><img src="/img/icon-eye.gif" />  View</a> <a class="btnLink" href="#" onclick="$('.facebookfound').hide();$('.facebooknotfound').show();return false;"><img src="/img/icon-pencil.gif" />  Update Location</a></div>
          <div class="buttons facebooknotfound" <?=($facebook->external_id?' style="display: none;"':'')?>><a class="btnLink" href="#" onclick="findBusiness('<?=$facebook->external_id?>');return false;"><img src="/img/icon-maglass.gif" />  Search For Business</a> <a class="btnLink" href="#" id="urllink" onclick="$('#urltype').val('facebook');"><img src="/img/icon-link.gif" /> Enter URL</a></div>
        <div class="facebook-results facebooknotfound">

        </div>
      </div>
      <input type="hidden" id="facebook_access_token" value="<?=$facebook->external_id?>" />

      <div class="pnlAddLocation short col-md-4">
        <div class="title">YELP: <span class="title-answer"><span class="yelpnotfound" <?=($yelp->external_id?' style="display: none;"':'')?>>Not </span>Found</span></div>
        <div class="field"><span class="name">Business Name:</span> <span id="yelpName" class="yelpfound" <?=($yelp->external_id?'':' style="display: none;"')?>><?=$location->name?></span><input id="yelpsearchfield" class="yelpnotfound" <?=($yelp->external_id?' style="display: none;"':'')?> type="name" value="<?=str_replace("\"","&quot;",$location->name)?>" /></div>
        <div class="field bottom"><span class="name">Location:</span> <span id="yelpLocation" class="yelpfound" <?=($yelp->external_id?'':' style="display: none;"')?>><?=$location->address?> <?=$location->locality?>, <?=$location->state_province?> <?=$location->postal_code?></span><input id="yelpsearchfield2" type="name" value="<?=str_replace("\"","&quot;",$location->postal_code)?>" class="yelpnotfound" <?=($yelp->external_id?' style="display: none;"':'')?> /></div>
        <div class="buttons yelpfound" <?=($yelp->external_id?'':' style="display: none;"')?>><a id="yelpLink" href="http://yelp.com/biz/" target="_blank"><img src="/img/icon-eye.gif" />  View</a> <a class="btnLink" href="#" onclick="$('.yelpfound').hide();$('.yelpnotfound').show();return false;"><img src="/img/icon-pencil.gif" />  Update Location</a></div>
      <div class="buttons yelpnotfound" <?=($yelp->external_id?' style="display: none;"':'')?>><a class="btnLink" href="#" onclick="findBusinessYelp();return false;"><img src="/img/icon-maglass.gif" />  Search For Business</a> <a class="btnLink" href="#" id="urllinkyelp" onclick="$('#urltype').val('yelp');"><img src="/img/icon-link.gif" /> Enter URL</a></div>
  <div class="yelp-results yelpnotfound">

  </div>
</div>
</div>

<div style="clear: both;">&nbsp;</div>
<input type="hidden" name="form" value="2" />

{{ form.render("name") }}
  {{ form.render("phone") }}
  {{ form.render("address") }}
  {{ form.render("locality") }}
  {{ form.render("state_province") }}
  {{ form.render("postal_code") }}
  {{ form.render("country") }}
  {{ form.render("yelp_id") }}
  {{ form.render("facebook_page_id") }}
  {{ form.render("google_place_id") }}
  {{ form.render("latitude") }}
  {{ form.render("longitude") }}
  {{ form.render("google_api_id") }}
</form>

<div class="overlay" style="display: none;"></div>
<div id="page-wrapper" style="display: none;">
  <div class="closelink close"></div>
  <div class="col-md-12">
    <div class="yelp-results yelpnotfound">

    </div>
    <div class="title"><h3>Enter URL</h3></div>
    <div class="field"><span class="name">Enter URL:</span> <input type="text" value="" name="url" id="url" /> <a class="btnLink" href="#" onclick="searchByURL();return false;">Save</a></div>
  </div>
</div><input type="hidden" name="urltype" id="urltype" value="" />
<!-- END FORM -->



<div class="form-group">
  <div class="col-md-offset-2 col-md-10">
    {{ submit_button("Save", "class": "btnLink") }}
  </div>
</div>
</form>
</div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function($){

    $("#btnCreateRegion").click(function() {
      $.ajax({
        url: "/location/region?name="+$("#regionname").val(),
        type: "post",
        success: function (response) {
          var response = $.parseJSON(response);
          // the region was created, so add it to the drop down
          $('#region_id')
                  .append($("<option></option>")
                          .attr("value",response.id)
                          .text($("#regionname").val()));
          //console.log('Test:'+response.id);
          $("#region_id").val(response.id);
          $('.fancybox-overlay').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //nothing was created
          $('.fancybox-overlay').hide();
        }
      });
    });

    $('.fancybox').fancybox();
  });
</script>

<script src="/js/location.js" type="text/javascript"></script>

<script type="text/javascript">

  jQuery(document).ready(function($){

    // Scanning form validation and ajax submit
    $("#locationform1").validate({
      rules: {
        location_name: {
          required: true
        },
        zip_code: {
          required: true
        }
      },
      messages: {
        location_name: {
          required: "The location name is required"
        },
        zip_code: {
          required: "The postal code is required"
        }
      },
      submitHandler: function(form)
      {
//console.log('test');
        $.ajax({
          type: 'POST',
          beforeSend: function() {
            $("#loader").show();
            $("#relevant-result-list").hide();
            $("#scan-tool-landing-page").hide();
            $(".footerscan").hide();
            $("#hiddenForm").hide();
          },
          complete: function() {
            $("#loader").hide();
            $("#relevant-result-list").show();
            $(".footerscan").show();
          },
          url: '/session/googlesearchapi/',
          data: {
            location_name: $.trim($('#location_name').val()),
            zip: $.trim($('#zip_code').val())
          },
          dataType: 'json',
          //async  : false,
          success: function(response)
          {
            if (response.errorMsg) {
              $("#relevant-result-list").html(response.errorMsg);
            }
            else {
              $("#relevant-result-list").html(response.HTML);
            }
          },
          error: function(e) {
            // void
          }
        });
      }
    });

    $('#urllink, #urllinkyelp').on('click', function(e) {
      e.preventDefault();
      $('#page-wrapper').show();
      $('.overlay').show();
    });
    $('.overlay, .closelink').on('click', function(e) {
      e.preventDefault();
      $('#page-wrapper').hide();
      $('.overlay').hide();
    });
  });
</script>