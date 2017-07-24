<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="application/javascript" src="https://api.ipify.org?format=json"></script>
        <script>
        function getQueryStrings() { 
                  var assoc  = {};
                  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
                  var queryString = location.search.substring(1); 
                  var keyValues = queryString.split('&'); 

                  for(var i in keyValues) { 
                    var key = keyValues[i].split('=');
                    if (key.length > 1) {
                      assoc[decode(key[0])] = decode(key[1]);
                    }
                  } 
          return assoc; 
        }

        function getCookie(name) {
            var dc = document.cookie;
            var prefix = name + "=";
            var begin = dc.indexOf("; " + prefix);
            if (begin == -1) {
                begin = dc.indexOf(prefix);
                if (begin != 0) return null;
            }
            else
            {
                begin += 2;
                var end = document.cookie.indexOf(";", begin);
                if (end == -1) {
                end = dc.length;
                }
            }
            // because unescape has been deprecated, replaced with decodeURI
            //return unescape(dc.substring(begin + prefix.length, end));
            return decodeURI(dc.substring(begin + prefix.length, end));
        } 

        
        /*function getIP(json) {
            document.getElementById("text").value = JSON.stringify(json, null, 2);
        
            for (key in json) {
                //document.write("<br>" + key + " : ", json[key]);
               
                    alert(json[key]);
            }
        }*/
        var qs = getQueryStrings();
            var affid = qs.id;
            alert(qs.id);
        
        var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
        var isFirefox = typeof InstallTrigger !== 'undefined';
        var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        var isChrome = !!window.chrome && !!window.chrome.webstore;
        var browser;

            if(isFirefox)
                browser = 'Firefox';
            else if(isSafari)
                browser = 'Safari';
            else if(isChrome)
                browser = 'Chrome';
            else if(isIE)
                browser = 'IE';
            //alert(browser);
        var checkCookie = 'affiliatedID'+affid;
        alert(checkCookie);
        var myCookie = getCookie(checkCookie);
        alert(myCookie);
        if (myCookie == null) {

            

            var now = new Date();
            var time = now.getTime();
            time += 3600 * 1000 * 24;
            now.setTime(time);



            document.cookie = "affiliatedID"+affid+"="+affid+"; expires="+now.toGMTString()+";path=/"; 

            $.getJSON("https://api.ipify.org/?format=json", function(e) {
               alert(e.ip);
               alert(666);
               $.post("{{ route('/affiliate') }}", {affilatedID: affid,affilatedIP: e.ip,affilatedbrowser:browser}, function(result){
                //$("span").html(result);
                 });
            });
            
            
        }
        else {
            alert('exist');
        }

        //alert(curdateplusone);
        

        /*$.ajax({
            url: "{{route('/affiliate')}}",
            type : "POST",
            data: {affilatedID: affid},
            success:function(data){
                console.log(data);
            }
        });*/

        </script>
       
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
