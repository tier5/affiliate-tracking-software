var Affiliate = Affiliate || (function(){

        var $;

        var _callback_url = 'http://localhost/reviewvelocity/public';

        var COOKIE_NAME = 'ats_affiliate';
        _initJQuery();

        function _initJQuery() {
            /* Load $ if not present */
            if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.10.1') {
                var script_tag = document.createElement('script');
                script_tag.setAttribute("type", "text/javascript");
                script_tag.setAttribute("src", "//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js");
                if (script_tag.readyState) {
                    script_tag.onreadystatechange = function () { // For old versions of IE
                        if (this.readyState == 'complete' || this.readyState == 'loaded') {
                            scriptLoadHandler();
                        }
                    };
                } else { // Other browsers
                    script_tag.onload = scriptLoadHandler;
                }
                (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
            } else {
                $ = window.jQuery;
                main();
            }
        }

        function scriptLoadHandler() {
            $ = window.jQuery.noConflict(true);
            main();
        }

        function main() {
            $(document).ready(function() {
                if (Affiliate) {
                    documentReadyHandler();
                }
            });
        }

        function documentReadyHandler() {

        }

        var Ajax = {
            xhr : null,
            request : function (url,method, data,success,failure){
                if (!this.xhr){
                    this.xhr = window.ActiveX ? new ActiveXObject("Microsoft.XMLHTTP"): new XMLHttpRequest();
                }
                var self = this.xhr;

                self.onreadystatechange = function () {
                    if (self.readyState === 4 && self.status === 200){
                        // the request is complete, parse data and call callback
                        var response = JSON.parse(self.responseText);
                        success(response);
                    }else if (self.readyState === 4) { // something went wrong but complete
                        failure();
                    }
                };
                this.xhr.open(method,url,true);
                this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                this.xhr.send(data);
            },
        };

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
        function encode(val) {
            if (window.encodeURIComponent) {
                return encodeURIComponent(val);
            } else {
                //noinspection JSDeprecatedSymbols
                return escape(val);
            }
        }

        function decode(val) {
            if (window.decodeURIComponent()) {
                return decodeURIComponent(val);
            } else {
                //noinspection JSDeprecatedSymbols
                return unescape(val);
            }
        }

        function setCookie(name, val, timeout,id) {
            timeout = typeof timeout !== 'undefined' ? timeout : 86400; // defaults to 1 day
            timeout *= 1000; // ms to seconds
            console.log("Setting cookie  " + name + " to value " + val);
            var now = new Date();
            var time = now.getTime();
            time += 3600 * timeout;
            now.setTime(time);
            /*var d = new Date();
            d.setTime(d.getTime() + timeout);*/
            var cookieObj = name + "=" + encode(val) + ",expires=" + now.toUTCString() + ",path=/,id="+id;
            if(Affiliate && Affiliate.domain) {
                cookieObj += ",domain=." + Affiliate.domain;
            }
            document.cookie = cookieObj;
        }

        function getCookie(name) {
            console.log("Getting Cookie " + name);
            var i, x, y, c = document.cookie.split(",");
            for (i = 0; i < c.length; i++) {
                x = c[i].substr(0, c[i].indexOf("="));
                y = c[i].substr(c[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x == name) {
                    return decode(y);
                }
            }
            return '';
        }

        function getCookieId() {
            var i, x, y, c = document.cookie.split(",");
            for (i = 0; i < c.length; i++) {
                x = c[i].substr(0, c[i].indexOf("="));
                y = c[i].substr(c[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x == 'id') {
                    return decode(y);
                }
            }
            return '';
        }

        function deleteCookie(name) {
            name = processCookieName(name);
            log("Deleting Cookie " + name);
            if (_proxy && _proxy.deleteCookie) {
                _proxy.deleteCookie(name);
            } else {
                document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
            }
        }

        return {
            _init : function(){
                console.log('script is initiated...');

                var qs = getQueryStrings();
                var affid = qs.id;

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
                var myCookie = getCookie(COOKIE_NAME);
                if(myCookie == ''){
                    var dataPost = '';
                    Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                        dataPost = 'ip='+data.ip+'&key='+affid+'&browser='+browser+'&urlKey='+Affiliate.key;
                        Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                            setCookie(COOKIE_NAME,affid,86400,dataNew.data);
                            console.log(dataNew.message);
                        },function () {
                            console.log('Api Failed');
                        })
                    },function(){
                        console.log('ip not found');
                    });
                } else {
                    var dataPost = '';
                    Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                        var logId = getCookieId();
                        dataPost = 'ip='+data.ip+'&key='+affid+'&browser='+browser+'&urlKey='+Affiliate.key+'&dataId='+logId;
                        Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                            console.log(dataNew.message);
                        },function () {
                            console.log('Api Failed');
                        })
                    },function(){
                        console.log('ip not found');
                    });
                }
            }
        };

    })();


