

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
      <span class="caption-subject bold uppercase"> Edit Location </span>
    </div>
  </div>
  <div class="portlet-body form">
    <div id="select-google-maps" style="display: none;">
      Select your location in Google Maps:

      <input id="pac-input" class="controls" type="text" placeholder="Enter a location" value="<?=$location->name?>" />
        <div id="map"></div>

        <script type="text/javascript">
          var latitude = '<?=$location->latitude?>';
          var longitude = '<?=$location->longitude?>';
          var loaded = false;
        </script>
        <script src="/admin/js/location.js" type="text/javascript"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20&libraries=places&signed_in=true" async="" defer=""></script>
      </div>
    <form id="hiddenForm" class="form-horizontal" role="form" method="post" autocomplete="off">
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Region</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <select name="region_id" id="region_id">
            <option value="">None</option>            
            <?php 
            foreach($regions as $data) { 
              ?>
              <option value="<?=$data->region_id?>" <?=(isset($_POST['region_id']) && $_POST['region_id']==$data->region_id?'selected="selected"':($data->region_id==$location->region_id?'selected="selected"':''))?>><?=$data->name?></option>
              <?php
            }  
            ?>
          </select>&nbsp;&nbsp;
          <a href="#createregion" class="fancybox">Create Region</a>
          <div id="createregion" style="width:400px;display: none;">
          Region Name: <input type="text" placeholder="Name" class="form-control" name="regionname" id="regionname" />
          <div style="margin-top: 10px;"><input id="btnCreateRegion" type="button" class="btn btn-big btn-success" value="Save" /></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Google</label>
        <div class="col-md-10">
          <div id="googleName"><?=$location->name?></div>
          <div id="googleAddress"><?=$location->address?> <?=$location->locality?>, <?=$location->state_province?> <?=$location->postal_code?></div>
          <div class="">
            <a id="googleLink" href="https://maps.google.com/?cid=<?=$location->google_place_id?>" target="_blank">
              <i class="icon-eye"></i> View on Google
            </a>
          </div>
          <div class="">
            <a href="#" onclick="changeLocation();if(!loaded){loaded=true;initMap();}return false;">
              <i class="icon-pencil"></i> Change Location
            </a>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Facebook</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <span class="facebookfound" <?=($location->facebook_page_id?'':' style="display: none;"')?>>
            <a id="facebookLink" href="http://facebook.com/<?=$location->facebook_page_id?>" target="_blank">
              <i class="icon-eye"></i> View on Facebook
            </a>
            <div class="">
              <a href="#" onclick="$('.facebookfound').hide();$('.facebooknotfound').show();return false;">
                <i class="icon-pencil"></i> Change Facebook Page
              </a>
            </div>
          </span>
          <span class="facebooknotfound" <?=($location->facebook_page_id?' style="display: none;"':'')?>>
            Not found
            <div class="">
            Facebook Business Name: <input id="facebooksearchfield" type="name" value="<?=str_replace("\"","&quot;",$location->name)?>" />
            <div><a href="#" onclick="findBusiness('<?=$facebook_access_token?>');return false;"><i class="icon-magnifier"></i> Search For Business on Facebook</a></div>
            <div class="facebook-results">
              
            </div>
            </div>
          </span><input type="hidden" id="facebook_access_token" value="<?=$facebook_access_token?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="name" class="col-md-2 control-label">Yelp</label>
        <div class="col-md-10" style="padding-top: 8px;">
          <span class="yelpfound" <?=($location->yelp_id?'':' style="display: none;"')?>>
            <a id="yelpLink" href="http://yelp.com/biz/<?=$location->yelp_id?>" target="_blank">
              <i class="icon-eye"></i> View on Yelp
            </a>
            <div class="">
              <a href="#" onclick="$('.yelpfound').hide();$('.yelpnotfound').show();return false;">
                <i class="icon-pencil"></i> Change Yelp Page
              </a>
            </div>
          </span>
          <span class="yelpnotfound" <?=($location->yelp_id?' style="display: none;"':'')?>>
            Not found
            <div class="">
            Yelp Name: <input id="yelpsearchfield" type="name" value="<?=str_replace("\"","&quot;",$location->name)?>" />
            Yelp Location: <input id="yelpsearchfield2" type="name" value="<?=str_replace("\"","&quot;",$location->postal_code)?>" />
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