
// This sample uses the Place Autocomplete widget to allow the user to search
// for and select a place. The sample then displays an info window containing
// the place ID and other information about the place that the user has
// selected.

function initMap() {
  //console.log('latitude:' + latitude + ':longitude:' + longitude);
  var map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
    zoom: 13
  });

  var input = document.getElementById('pac-input');

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map
  });
  marker.addListener('click', function () {
    infowindow.open(map, marker);
  });

  autocomplete.addListener('place_changed', function () {
    infowindow.close();
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }

    // Set the position of the marker using the place ID and location.
    marker.setPlace({
      placeId: place.place_id,
      location: place.geometry.location
    });
    marker.setVisible(true);

    //console.log('place:'+place.toSource());
    infowindow.setContent('<div><strong>' + place.name + '</strong><br />' + place.formatted_address + '<br />' + '<a href="#" onclick="selectLocation(\'' + encode(place.place_id) + '\', \'' + encode(place.url) + '\', \'' + encode(place.name) + '\', \'' + encode(extractFromAdress(place.address_components, 'street_number')) + '\', \'' + encode(extractFromAdress(place.address_components, 'route')) + '\', \'' + encode(extractFromAdress(place.address_components, 'locality')) + '\', \'' + encode(extractFromAdress(place.address_components, 'administrative_area_level_1')) + '\', \'' + encode(extractFromAdress(place.address_components, 'postal_code')) + '\', \'' + encode(extractFromAdress(place.address_components, 'country')) + '\', \'' + encode(place.formatted_phone_number) + '\', \'' + encode(place.geometry.location.lat()) + '\', \'' + encode(place.geometry.location.lng()) + '\');return false;">Click here</a> to select this location.');
    infowindow.open(map, marker);
  });
}





  function selectLocation(googleid, url, name, street_number, route, locality, administrative_area_level_1, postal_code, country, formatted_phone_number, lat, lng) {
    //un-encode everything!
    googleid = unencode(googleid);
    url = unencode(url);
    var cid = url.replace("https://maps.google.com/?cid=", "");
    name = unencode(name);
    street_number = unencode(street_number);
    route = unencode(route);
    locality = unencode(locality);
    administrative_area_level_1 = unencode(administrative_area_level_1);
    postal_code = unencode(postal_code);
    country = unencode(country);
    formatted_phone_number = unencode(formatted_phone_number);
    lat = unencode(lat);
    lng = unencode(lng);
    //we have the id, so find the details
    /*
    console.log('cid:'+cid);
    console.log('url:'+url);
    console.log('googleid:'+googleid);
    console.log('name:'+name);
    
    console.log('street_number:'+street_number);
    console.log('route:'+route);
    console.log('locality:'+locality);
    console.log('administrative_area_level_1:'+administrative_area_level_1);
    console.log('postal_code:'+postal_code);
    console.log('country:'+country);
    
    console.log('formatted_phone_number:'+formatted_phone_number);
    
    console.log('lat:'+lat);
    console.log('lng:'+lng);
    */
       
    //populate the hidden form with the selected values
    document.getElementById("name").value = name;
    document.getElementById("phone").value = formatted_phone_number;
    document.getElementById("address").value = street_number + ' ' + route;
    document.getElementById("locality").value = locality;
    document.getElementById("state_province").value = administrative_area_level_1;
    document.getElementById("country").value = country;
    document.getElementById("postal_code").value = postal_code;
    //document.getElementById("yelp_id").value = yelp_id;
    //document.getElementById("facebook_page_id").value = facebook_page_id;
    document.getElementById("google_place_id").value = cid;
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lng;
    document.getElementById("google_api_id").value = googleid;
    
    $('#googleName').text(name);
    $('#googleAddress, .googleAddress').text(street_number + ' ' + route + ' ' + locality + ', ' + administrative_area_level_1 + ' ' + postal_code);
    $("#googleLink").attr("href", url);

    $('#facebookName').text(name);
    $('#facebookLocation').text(street_number + ' ' + route + ' ' + locality + ', ' + administrative_area_level_1 + ' ' + postal_code);
    document.getElementById("facebooksearchfield").value = name;
    document.getElementById("facebooksearchfield2").value = postal_code;
    //if (document.getElementById("facebook_page_id").value == '') {
      //we don't have facebook set, so try to find it
      findBusiness(document.getElementById("facebook_access_token").value);
    //}
    $('#yelpName').text(name);
    document.getElementById("yelpsearchfield").value = name;
    $('#yelpLocation').text(street_number + ' ' + route + ' ' + locality + ', ' + administrative_area_level_1 + ' ' + postal_code);
    document.getElementById("yelpsearchfield2").value = postal_code;
    //if (document.getElementById("yelp_id").value == '') {
      //we don't have facebook set, so try to find it
      findBusinessYelp();
    //}
    
      //show the form
    $('#select-google-maps').hide();
    $('#locationform1').hide();
    $('#hiddenForm').show();

  }
  function changeLocation() {

    //add the search input field, if it is not already there
    /*
    if (!document.getElementById("pac-input")) {
      //console.log('input not found  :-(');
      var container = document.getElementById("select-google-maps");
      var input = document.createElement("input");
      input.type = "text";
      input.name = "pac-input";
      input.id = "pac-input";
      container.appendChild(input);
    } else {
      //console.log('input was found  :-)');
    }

    //show the map
    $('#select-google-maps').show();
    $('#hiddenForm').hide();
    */
    $('#relevant-result-list').hide();
    $('#hiddenForm').hide();
    
  }
  function encode(val) {
    if (val) {
      return replaceAll(replaceAll(val.toString(), "\"", "%22"), "'", "%27");
    } else {
      return '';
    }
  }
  function unencode(val){
    return replaceAll(replaceAll(val.toString(), "%22", "\""), "%27", "'");
  }
  function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
  }
  function extractFromAdress(components, type){
    for (var i=0; i<components.length; i++)
      for (var j=0; j<components[i].types.length; j++)
          if (components[i].types[j]==type) return components[i].short_name;
            return "";
  }

     
  function findBusiness(facebook_access_token) {
    $('.facebook-results').html('Searching...');
    var found = false;
    var name = document.getElementById("facebooksearchfield").value;
    var url = "https://graph.facebook.com/search?q=" + encodeURIComponent(name) + "&type=page&limit=100&" + facebook_access_token;
    var http_request = new XMLHttpRequest();
    http_request.open("GET", url, true);
    http_request.onreadystatechange = function () {
        var done = 4, ok = 200;
        if (http_request.readyState == done && http_request.status == ok) {
          //console.log('my_JSON_object:'+http_request.responseText);
          my_JSON_object = JSON.parse(http_request.responseText);
          //displayResults(my_JSON_object);
          my_JSON_object.data.forEach(function (obj) {
            if (!found) {
              //console.log(obj.name); 
              if (obj.name == name) {
                //we found an exact match, so set the Facebook ID
                $("#facebookLink").attr("href", 'http://facebook.com/' + obj.id);
                document.getElementById("facebook_page_id").value = obj.id;
                $('.facebookfound').show();
                $('.facebooknotfound').hide();
                //console.log('Exact match!'); 
                found = true;

                return false; // here - will exit the each loop 
              }
            }
          });
          //image.parentNode.removeChild(image);
          if (!found) {
            //console.log('Not found...');
            $('.facebook-results').html('No match found.');
            $('.facebookfound').hide();
            $('.facebooknotfound').show();
          }
        } else {
          $('.facebook-results').html('No match found.');
          $('.facebookfound').hide();
          $('.facebooknotfound').show();
        }
    };
    http_request.send(null);
  }



     
  function findBusinessYelp() {
    $('.yelp-results').html('Searching...');
    var found = false;
    var name = document.getElementById("yelpsearchfield").value;
    var location = document.getElementById("yelpsearchfield2").value;
    var url = "/location/yelp?t=" + encodeURIComponent(name) + "&l=" + encodeURIComponent(location);
    var http_request = new XMLHttpRequest();
    http_request.open("GET", url, true);
    http_request.onreadystatechange = function () {
        var done = 4, ok = 200;
        if (http_request.readyState == done && http_request.status == ok) {
          //console.log('my_JSON_object:'+http_request.responseText);
          my_JSON_object = JSON.parse(http_request.responseText);
          //displayResults(my_JSON_object);
          my_JSON_object.businesses.forEach(function (obj) {
            if (!found) {
              //console.log(obj.name); 
              if (obj.name == name) {
                //we found an exact match, so set the Yelp ID
                $("#yelpLink").attr("href", 'http://www.yelp.com/biz/' + obj.id);
                document.getElementById("yelp_id").value = obj.id;
                $('.yelpfound').show();
                $('.yelpnotfound').hide();
                //console.log('Exact match!'); 
                found = true;

                return false; // here - will exit the each loop 
              }
            }
          });
          //image.parentNode.removeChild(image);
          if (!found) {
            //console.log('Not found...');
            $('.yelp-results').html('No match found.');
            $('.yelpfound').hide();
            $('.yelpnotfound').show();
          }
        } else {
          $('.yelp-results').html('No match found.');
          $('.yelpfound').hide();
          $('.yelpnotfound').show();
        }
    };
    http_request.send(null);
  }




  function getId(url) {
    var path = new URL(url).pathname;
    var parts = path.split('/');

    parts = parts.filter(function (part) {
      return part.length !== 0;
    });

    return parts[parts.length - 1];
  }



  function searchByURL() {
    if ($('#urltype').val() == 'facebook') {
      findFacebookByURL();
    } else {
      findYelpByURL();
    }
  }



  function findFacebookByURL() {
    $('.facebook-results').html('Searching...');
    var facebook_access_token = document.getElementById("facebook_access_token").value;
    var url = document.getElementById("url").value;
    var id = getId(url);
    var found = false;
    var url = "https://graph.facebook.com/search?q=" + encodeURIComponent(id) + "&type=page&limit=100&" + facebook_access_token;
    //console.log('url:' + url);
    var http_request = new XMLHttpRequest();
    http_request.open("GET", url, true);
    http_request.onreadystatechange = function () {
      var done = 4, ok = 200;
      if (http_request.readyState == done && http_request.status == ok) {
        //console.log('my_JSON_object:'+http_request.responseText);
        my_JSON_object = JSON.parse(http_request.responseText);
        //displayResults(my_JSON_object);
        my_JSON_object.data.forEach(function (obj) {
          if (!found) {
            //console.log(obj.name); 
            //if (obj.name == name) {
              //we found an exact match, so set the Facebook ID
              $("#facebookLink").attr("href", 'http://facebook.com/' + obj.id);
              document.getElementById("facebook_page_id").value = obj.id;
              $('.facebookfound').show();
              $('.facebooknotfound').hide();
              //console.log('Exact match!'); 
              found = true;
              $('#page-wrapper').hide();
              $('.overlay').hide();

              return false; // here - will exit the each loop 
            //}
          }
        });
        //image.parentNode.removeChild(image);
        if (!found) {
          //console.log('Not found...');
          $('.facebook-results').html('No match found.');
          $('.facebookfound').hide();
          $('.facebooknotfound').show();
        }
      } else {
        $('.facebook-results').html('No match found.');
        $('.facebookfound').hide();
        $('.facebooknotfound').show();
      }
    };
    http_request.send(null);
  }

function findYelpByURL() {
    $('.yelp-results').html('Searching...');
    var url = document.getElementById("url").value;
    var id = getId(url);
    var url = "/location/yelpurl?i=" + encodeURIComponent(id);
    var http_request = new XMLHttpRequest();
    http_request.open("GET", url, true);
    http_request.onreadystatechange = function () {
        var done = 4, ok = 200;
        if (http_request.readyState == done && http_request.status == ok) {
            obj = JSON.parse(http_request.responseText);
            if(obj.error) {
                $('.yelp-results').html('No match found.');
                $('.yelpfound').hide();
                $('.yelpnotfound').show();
            }
            else {
                $.ajax({
                    url: "/admin/location/updateLocation?location_id=61&yelp_id=" + obj.id,
                }).done(function (data) {
                    if(data == "SUCCESS") {
                        $("#yelpLink").attr("href", 'http://www.yelp.com/biz/' + obj.id);
                        document.getElementById("yelp_id").value = obj.id;
                        $('.yelpfound').show();
                        $('.yelpnotfound').hide();
                        $('#page-wrapper').hide();
                        $('.overlay').hide();
                    } else {
                        $('.yelp-results').html('No match found.');
                        $('.yelpfound').hide();
                        $('.yelpnotfound').show();
                    }
                });
                return true;
            }
        }
    }
    http_request.send(null);
}
