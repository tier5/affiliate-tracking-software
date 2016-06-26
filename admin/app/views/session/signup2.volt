{{ content() }}



<!-- BEGIN FORM -->
<form class="register-form" id="locationform1" action="/session/signup2/<?=(isset($subscription->subscription_id)?$subscription->subscription_id:'')?><?=(isset($_GET['code'])?'?code='.$_GET['code']:'')?>" method="post" style="display: block;">
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
        <button type="submit" id="register-submit-btn" class="btnsignup uppercase">Scan Now</button>
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
  <input type="hidden" name="form" value="1" />
</form>



<form class="register-form" id="hiddenForm" style="display: none;" action="<?=((strpos($_SERVER['REQUEST_URI'],'location')>0)?'/location/create':'/session/signup2/'.(isset($subscription->subscription_id)?$subscription->subscription_id:'').(isset($_GET['code'])?'?code='.$_GET['code']:''))?>" method="post" style="display: block;">
  <div class="col-md-12">
    <div class="pnlAddLocation short col-md-4">
      <div class="title">GOOGLE: <span class="title-answer">Found</span></div>
      <div class="field"><span class="name">Business Name:</span> <span id="googleName"></span></div>
      <div class="field bottom"><span class="name">Location:</span> <span id="googleAddress"></span></div>
      <div class="buttons"><a class="btnLink" id="googleLink" href="https://maps.google.com/?cid=" target="_blank"><img src="/img/icon-eye.gif" /> View</a> <a class="btnLink" href="#" onclick="changeLocation();$('#locationform1').show();return false;"><img src="/img/icon-pencil.gif" /> Change Location</a></div>
    </div>

    <div class="pnlAddLocation short col-md-4">
      <div class="title">FACEBOOK: <span class="title-answer"><span class="facebooknotfound">Not </span>Found</span></div>
      <div class="field"><span class="name">Business Name:</span> <span id="facebookName" class="facebookfound"></span><input class="facebooknotfound" id="facebooksearchfield" type="name" value="" /></div>
      <div class="field bottom"><span class="name">Location:</span> <span id="facebookLocation" class="facebookfound"></span><input id="facebooksearchfield2" type="name" value="" class="facebooknotfound" /></div>
      <div class="buttons facebookfound"><a id="facebookLink" href="http://facebook.com/" target="_blank"><img src="/img/icon-eye.gif" />  View</a> <a class="btnLink" href="#" onclick="$('.facebookfound').hide();$('.facebooknotfound').show();return false;"><img src="/img/icon-pencil.gif" />  Update Location</a></div>
      <div class="buttons facebooknotfound"><a class="btnLink" href="#" onclick="findBusiness('<?=$facebook_access_token?>');return false;"><img src="/img/icon-maglass.gif" />  Search For Business</a> <a class="btnLink" href="#" id="urllink" onclick="$('#urltype').val('facebook');"><img src="/img/icon-link.gif" /> Enter URL</a></div>
      <div class="facebook-results facebooknotfound">

      </div>
    </div>
    <input type="hidden" id="facebook_access_token" value="<?=$facebook_access_token?>" />

    <div class="pnlAddLocation short col-md-4">
      <div class="title">YELP: <span class="title-answer"><span class="yelpnotfound">Not </span>Found</span></div>
      <div class="field"><span class="name">Business Name:</span> <span id="yelpName" class="yelpfound"></span><input id="yelpsearchfield" class="yelpnotfound" type="name" value="" /></div>
      <div class="field bottom"><span class="name">Location:</span> <span id="yelpLocation" class="yelpfound"></span><input id="yelpsearchfield2" type="name" value="" class="yelpnotfound" /></div>
      <div class="buttons yelpfound"><a id="yelpLink" href="http://yelp.com/biz/" target="_blank"><img src="/img/icon-eye.gif" />  View</a> <a class="btnLink" href="#" onclick="$('.yelpfound').hide();$('.yelpnotfound').show();return false;"><img src="/img/icon-pencil.gif" />  Update Location</a></div>
      <div class="buttons yelpnotfound"><a class="btnLink" href="#" onclick="findBusinessYelp();return false;"><img src="/img/icon-maglass.gif" />  Search For Business</a> <a class="btnLink" href="#" id="urllinkyelp" onclick="$('#urltype').val('yelp');"><img src="/img/icon-link.gif" /> Enter URL</a></div>
      <div class="yelp-results yelpnotfound">

      </div>
    </div>
  </div>


  <div class="form-actions col-md-12" >
    <button type="submit" id="register-submit-btn" class="btnsignup uppercase">Next: Customize Survey</button>
  </div>
  <div style="clear: both;">&nbsp;</div>
  <input type="hidden" name="form" value="2" />
  <input type="hidden" id="name" name="name" />
  <input type="hidden" id="phone" name="phone" />
  <input type="hidden" id="address" name="address" />
  <input type="hidden" id="locality" name="locality" />
  <input type="hidden" id="state_province" name="state_province" />
  <input type="hidden" id="postal_code" name="postal_code" />
  <input type="hidden" id="country" name="country" />
  <input type="hidden" id="yelp_id" name="yelp_id" />
  <input type="hidden" id="facebook_page_id" name="facebook_page_id" />
  <input type="hidden" id="google_place_id" name="google_place_id" />
  <input type="hidden" id="latitude" name="latitude" />
  <input type="hidden" id="longitude" name="longitude" />
  <input type="hidden" id="google_api_id" name="google_api_id" />

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
            // $("#relevant-result-list").empty();
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