var isMobile = {
  Android: function () {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function () {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function () {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function () {
    return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function () {
    return navigator.userAgent.match(/IEMobile/i);
  },
  any: function () {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};


$(document).ready(function () {

    if(isMobile.any()) {
   alert("This is a Mobile Device");
}

  if (isMobile.Android()) {
    //hide non-google options
   // $('#facebooklink, #yelplink').hide();
  }

  //set the left menu state from session, if set
  // Check browser support
  if (typeof (Storage) != "undefined") {
    // Retrieve
    var hasclass = localStorage.getItem("toggleState");
    //console.log('hasclass:' + hasclass);
    if (hasclass == 'true') {
      $('body').addClass('page-sidebar-closed');
      $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
      //$('.nav-link .title').hide();
    } else {
      //do nothing, already open
      //$('body').removeClass('page-sidebar-closed');
      //$('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
    }
  }

  // executes when HTML-Document is loaded and DOM is ready
  $(".page-logo .sidebar-toggler").click(function () {
    // Check browser support
    if (typeof (Storage) != "undefined") {
      // Store
      var hasClass = !$('body').hasClass("page-sidebar-closed");
      //console.log('hasClass toggle:' + hasClass);
      localStorage.setItem("toggleState", hasClass);
    }
  });

  userTypeChange();
  $("#profilesId").change(function () {
    userTypeChange();
  });
});

function userTypeChange() {  
  var val = $('#profilesId').val();
  //console.log('Test:' + val);
  if (val == 1 || val == 4) {
    //console.log('true');
    $('#userlocationselect').hide();
    $('#userlocationall').show();
  } else {
    //console.log('false');
    $('#userlocationselect').show();
    $('#userlocationall').hide();
  }
}

function facebookClickHandler(facebook_page_id) {
  //alert('fb://profile/' + facebook_page_id);return false;
  deeplink.setup({
    iOS: {
      appId: "284882215",
      appName: "facebook",
      storeUrl: "http://facebook.com/" + facebook_page_id,
    },
    android: {
      appId: "com.facebook.katana",
      storeUrl: "http://facebook.com/" + facebook_page_id,
    }
  });
  deeplink.open('fb://profile/' + facebook_page_id, 'intent://page/' + facebook_page_id + '#Intent;scheme=fb;package=com.facebook.katana;end;');
  return false;
}


function yelpClickHandler(yelp_id) {
  deeplink.setup({
    iOS: {
      appId: "284910350",
      appName: "yelp",
      storeUrl: "http://www.yelp.com/biz/" + yelp_id,
    },
    android: {
      appId: "com.yelp.android",
      storeUrl: "http://www.yelp.com/biz/" + yelp_id,
    }
  });
  deeplink.open('yelp:///biz/add/review?biz_id=' + yelp_id, 'intent://biz/' + yelp_id + '#Intent;scheme=yelp-app-indexing;package=com.yelp.android;end;');
  return false;
}


function googleClickHandler(google_place_id, address) {
  deeplink.setup({
    iOS: {
      appId: "585027354",
      appName: "Google Maps",
      storeUrl: "https://maps.google.com/?cid=" + google_place_id,
    },
    android: {
      appId: "com.google.android.apps.maps",
      storeUrl: "https://maps.google.com/?cid=" + google_place_id,
    }
  });
  deeplink.open('comgooglemaps://maps.google.com/geo:0,0?q=' + address + '', 'intent://maps.google.com/geo:0,0?q=' + address + ';/#Intent;scheme=geo;package=com.google.android.apps.maps;end;');//comgooglemaps://?q=' + google_place_id);
  return false;
}

//set active link in left menu
$('a[href="' + this.location.pathname + '"]').parents('li,ul').addClass('active');