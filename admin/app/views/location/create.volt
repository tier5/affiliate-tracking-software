<ul class="pager">
  <li class="previous pull-left">
    {{ link_to("/admin/location", "&larr; Go Back") }}
  </li>
</ul>

{{ content() }}

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> Create Location </span>
    </div>
  </div>
  <div class="portlet-body form">
    <div id="select-google-maps">
    Select your location in Google Maps:

    <input id="pac-input" class="controls" type="text"
        placeholder="Enter a location">
      <div id="map"></div>

      <script type="text/javascript">
        var latitude = '39.739696';
        var longitude = '-104.97901139999999';
      </script>
      <script src="/admin/js/location.js" type="text/javascript"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20&libraries=places&signed_in=true&callback=initMap" async="" defer=""></script>
    </div>
    <form id="hiddenForm" style="display: none;" class="form-horizontal" role="form" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Google</label>
        <div class="col-md-10">
          <div id="googleName"></div>
          <div id="googleAddress"></div>
          <div class=""><a id="googleLink" href="https://maps.google.com/?cid=" target="_blank"><i class="icon-eye"></i> View</a></div>
          <div class=""><a href="#" onclick="changeLocation();return false;"><i class="icon-pencil"></i> Change Location</a></div>
        </div>
      </div>
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Facebook</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <span class="facebookfound" style="display: none;">
            <a id="facebookLink" href="http://facebook.com/" target="_blank">
              <i class="icon-eye"></i> View on Facebook
            </a>
            <div class="">
              <a href="#" onclick="$('.facebookfound').hide();$('.facebooknotfound').show();return false;">
                <i class="icon-pencil"></i> Change Facebook Page
              </a>
            </div>
          </span><input type="hidden" id="facebook_access_token" value="<?=$facebook_access_token?>" />
          <span class="facebooknotfound">
            Not found
            <div class="">
            Facebook Business Name: <input id="facebooksearchfield" type="name" value="" />
            <div><a href="#" onclick="findBusiness('<?=$facebook_access_token?>');return false;"><i class="icon-magnifier"></i> Search For Business on Facebook</a></div>
            <div class="facebook-results">
              
            </div>
            </div>
          </span>
        </div>
      </div>
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Yelp</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <span class="yelpfound" style="display: none;">
            <a id="yelpLink" href="http://yelp.com/biz/" target="_blank">
              <i class="icon-eye"></i> View on Yelp
            </a>
            <div class="">
              <a href="#" onclick="$('.yelpfound').hide();$('.yelpnotfound').show();return false;">
                <i class="icon-pencil"></i> Change Yelp Page
              </a>
            </div>
          </span>
          <span class="yelpnotfound">
            Not found
            <div class="">
            Yelp Name: <input id="yelpsearchfield" type="name" value="" />
            Yelp Location: <input id="yelpsearchfield2" type="name" value="" />
            <div><a href="#" onclick="findBusinessYelp();return false;"><i class="icon-magnifier"></i> Search For Business on Yelp</a></div>
            <div class="yelp-results">
              
            </div>
            </div>
          </span>
        </div>
      </div>
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
      <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
          {{ submit_button("Save", "class": "btn btn-big btn-success") }}
        </div>
      </div>
    </form>
  </div>
</div>