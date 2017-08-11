var Affiliate = Affiliate || (function(){

        var $;

       // var _callback_url = 'https://www.interwebleads.com';
        var _callback_url = 'http://localhost/reviewvelocity/public';

        var COOKIE_NAME = 'ats_affiliate';

        var LEAD_COOKIE_NAME = 'ats_lead';

        var COOKIE_LOG_ID = 'ats_log_id';

        var COOKIE_PRODUCT_URL = 'ats_product_url';

        var COOKIE_PRODUCT = 'ats_product';

        _initJQuery();

        function _initJQuery() {
            /* Load $ if not present */
            if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.10.1') {
                var script_tag = document.createElement('script');
                script_tag.setAttribute("type", "text/javascript");
                script_tag.setAttribute("src", "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js");
                if (script_tag.readyState) {
                    script_tag.onreadystatechange = function () { // For old versions of IE
                        if (this.readyState == 'complete' || this.readyState == 'loaded') {
                             scriptLoadHandler();
                        }
                    };
                } else {
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
                        console.log(self.responseText);
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
            time += timeout;
            now.setTime(time);
            /*var d = new Date();
            d.setTime(d.getTime() + timeout);*/
            var cookieObj = name + "=" + encode(val) + ";expires=" + now.toUTCString()+';path=/';
            /*if(Affiliate && Affiliate.domain && name == COOKIE_NAME) {
                cookieObj += ";domain=." + Affiliate.domain;
            }*/
            document.cookie = cookieObj;
        }

        function getCookie(name) {
            console.log("Getting Cookie " + name);
            var i, x, y, c = document.cookie.split(";");
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
        var _proxy = null;
        function deleteCookie(name) {
            console.log("Deleting Cookie " + name);
            if (_proxy && _proxy.deleteCookie) {
                _proxy.deleteCookie(name);
            } else {
                document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
            }
        }

        function isEmail(email) {
            return /\S+@\S+\.\S+/.test(email);
        }

        function leadLog(id,lead){
            if(isEmail(lead)){
                var lead_cookie = getCookie(LEAD_COOKIE_NAME);
                if(lead_cookie == ''){
                    setCookie(LEAD_COOKIE_NAME,lead,86400,null);
                    dataPost = 'dataId='+id+'&email='+lead;
                    Ajax.request(_callback_url + "/api/affiliate/lead","POST",dataPost,function (dataNew) {
                        console.log(dataNew.message);
                    },function () {
                        //
                    });
                } else {
                    if(lead != lead_cookie){
                        deleteCookie(LEAD_COOKIE_NAME);
                        setCookie(LEAD_COOKIE_NAME,lead,86400,null);
                        dataPost = 'dataId='+id+'&email='+lead;
                        Ajax.request(_callback_url + "/api/affiliate/lead","POST",dataPost,function (dataNew) {
                            console.log(dataNew.message);
                        },function () {
                            //
                        });
                    } else {
                        console.log('same Api');
                    }
                }
            }
        }
        function removeParam(key, sourceURL) {
            var rtn = sourceURL.split("?")[0],
                param,
                params_arr = [],
                queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
            if (queryString !== "") {
                params_arr = queryString.split("&");
                for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                    param = params_arr[i].split("=")[0];
                    if (param === key) {
                        params_arr.splice(i, 1);
                    }
                }
                if(params_arr.length > 0 ){
                    rtn = rtn + "?" + params_arr.join("&");
                } else {
                    rtn = rtn;
                }
            }
            return rtn;
        }

        return {
            _init : function(){
                console.log('_init is initiated...');

                var qs = getQueryStrings();
                var affid = qs.affiliate_id;

                if(qs.affiliate_id != undefined){
                    var url = removeParam('affiliate_id',window.location.href);
                    window.history.pushState(null, null, url);
                }

                var nVer = navigator.appVersion;
                var nAgt = navigator.userAgent;
                var browserName  = navigator.appName;
                var fullVersion  = ''+parseFloat(navigator.appVersion);
                var majorVersion = parseInt(navigator.appVersion,10);
                var nameOffset,verOffset,ix;

                // In Opera, the true version is after "Opera" or after "Version"
                if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
                    browserName = "Opera";
                    fullVersion = nAgt.substring(verOffset+6);
                    if ((verOffset=nAgt.indexOf("Version"))!=-1)
                        fullVersion = nAgt.substring(verOffset+8);
                }
                // In MSIE, the true version is after "MSIE" in userAgent
                else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
                    browserName = "Microsoft Internet Explorer";
                    fullVersion = nAgt.substring(verOffset+5);
                }
                // In Chrome, the true version is after "Chrome"
                else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
                    browserName = "Chrome";
                    fullVersion = nAgt.substring(verOffset+7);
                }
                // In Safari, the true version is after "Safari" or after "Version"
                else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
                    browserName = "Safari";
                    fullVersion = nAgt.substring(verOffset+7);
                    if ((verOffset=nAgt.indexOf("Version"))!=-1)
                        fullVersion = nAgt.substring(verOffset+8);
                }
                // In Firefox, the true version is after "Firefox"
                else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
                    browserName = "Firefox";
                    fullVersion = nAgt.substring(verOffset+8);
                }
                // In most other browsers, "name/version" is at the end of userAgent
                else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) <
                    (verOffset=nAgt.lastIndexOf('/')) )
                {
                    browserName = nAgt.substring(nameOffset,verOffset);
                    fullVersion = nAgt.substring(verOffset+1);
                    if (browserName.toLowerCase()==browserName.toUpperCase()) {
                        browserName = navigator.appName;
                    }
                }
                // trim the fullVersion string at semicolon/space if present
                if ((ix=fullVersion.indexOf(";"))!=-1)
                    fullVersion=fullVersion.substring(0,ix);
                if ((ix=fullVersion.indexOf(" "))!=-1)
                    fullVersion=fullVersion.substring(0,ix);

                majorVersion = parseInt(''+fullVersion,10);
                if (isNaN(majorVersion)) {
                    fullVersion  = ''+parseFloat(navigator.appVersion);
                    majorVersion = parseInt(navigator.appVersion,10);
                }
                var browser = browserName+' V'+majorVersion;

                //Detect OS
                var OSName="Unknown OS";
                if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
                if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
                if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
                if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";

                var myCookie = getCookie(COOKIE_NAME);
                if(myCookie == ''){
                    var dataPost = '';
                    Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                        dataPost = 'ip='+data.ip+'&key='+affid+'&browser='+browser+'&urlKey='+Affiliate.key+'&os='+OSName;
                        Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                            setCookie(COOKIE_NAME,affid,86400,dataNew.data);
                            setCookie(COOKIE_LOG_ID,dataNew.data,86400);
                            console.log(dataNew.message);
                            $('input[type=text],input[type=email]').on('change',function () {
                                var lead = $(this).val();
                                leadLog(dataNew.data,lead);
                            });
                        },function () {
                            //
                        });
                    },function(){
                        console.log('ip not found');
                    });
                } else {
                    var logId = getCookie(COOKIE_LOG_ID);
                    var dataPost = '';
                    var affiliate_id = myCookie;
                    Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                        dataPost = 'ip='+data.ip+'&key='+affiliate_id+'&browser='+browser+'&urlKey='+Affiliate.key+'&dataId='+logId;
                        Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                            console.log(dataNew.message);
                            $('input[type=text],input[type=email]').on('change',function () {
                                var lead = $(this).val();
                                leadLog(logId,lead);
                            });
                        },function () {
                            //
                        });
                    },function(){
                        console.log('ip not found');
                    });
                }
            },

            _watch : function () {
                console.log('_watch is initiated...');
                var windowsLocation = window.location.href;
                var url_cookie = getCookie(COOKIE_PRODUCT_URL);
                if(url_cookie){
                    if(url_cookie != windowsLocation) {
                        var dataPostProduct = 'url=' + url_cookie + '&campaign=' + Affiliate.key + '&currentUrl=' + windowsLocation;
                        Ajax.request(_callback_url + "/api/check/landing_page/url", "POST", dataPostProduct, function (dataNewProduct) {
                            if(getCookie(COOKIE_PRODUCT)){
                                deleteCookie(COOKIE_PRODUCT);
                            }
                            setCookie(COOKIE_PRODUCT, dataNewProduct.data, 86400);
                        }, function () {
                            //
                        });
                    } else {
                        console.log('Yes');
                    }
                }
                window.onload = function() {
                    var allitems = [];
                    allitems = Array.prototype.concat.apply(allitems, document.getElementsByTagName('a'));
                    allitems = Array.prototype.concat.apply(allitems, document.getElementsByTagName('button'));
                    allitems = Array.prototype.concat.apply(allitems, document.querySelectorAll('input[type=submit]'));
                    allitems = Array.prototype.concat.apply(allitems, document.querySelectorAll('input[type=button]'));
                    for(var i = 0; i < allitems.length; i++) {
                        var anchor = allitems[i];
                        anchor.onclick = function() {
                            setTimeout(function(){
                                var dataPost = 'url=' + windowsLocation + '&campaign=' + Affiliate.key + '&currentUrl=0';
                                Ajax.request(_callback_url + "/api/check/landing_page/url", "POST", dataPost, function (dataNew) {
                                    if (url_cookie) {
                                        deleteCookie(COOKIE_PRODUCT_URL);
                                    }
                                    setCookie(COOKIE_PRODUCT_URL, windowsLocation, 86400);
                                }, function () {
                                    //
                                });
                            }, 1000);
                        }
                    }
                }
            },

            _checkout : function () {
                console.log('_checkout is initiated...');
                var url_cookie = getCookie(COOKIE_PRODUCT_URL);
                var product = getCookie(COOKIE_PRODUCT);
                var log = getCookie(COOKIE_LOG_ID);
                var windowsLocation = window.location.href;
                if(url_cookie && product && log){
                    if(url_cookie != windowsLocation) {
                        var dataPost = 'product_id='+product+'&log_id='+log;
                        Ajax.request(_callback_url + "/api/check/thank_you","POST",dataPost,function (dataNew) {
                            deleteCookie(COOKIE_PRODUCT);
                            deleteCookie(COOKIE_PRODUCT_URL);
                        },function () {
                            //
                        });
                        /*var dataPostProduct = 'url=' + url_cookie + '&campaign=' + Affiliate.key + '&currentUrl=' + windowsLocation;
                        Ajax.request(_callback_url + "/api/check/landing_page/url", "POST", dataPostProduct, function (dataNewProduct) {

                            var dataPost = 'product_id='+dataNewProduct.data+'&log_id='+log;
                            Ajax.request(_callback_url + "/api/check/thank_you","POST",dataPost,function (dataNew) {
                                deleteCookie(COOKIE_PRODUCT);
                                deleteCookie(COOKIE_PRODUCT_URL);
                            },function (data) {
                                console.log('Api Failed =>    '+ data.message);
                            });
                        }, function (data) {
                            if(product){
                                var dataPost = 'product_id='+product+'&log_id='+log;
                                Ajax.request(_callback_url + "/api/check/thank_you","POST",dataPost,function (dataNew) {
                                    deleteCookie(COOKIE_PRODUCT);
                                    deleteCookie(COOKIE_PRODUCT_URL);
                                },function (data) {
                                    console.log('Api Failed =>    '+ data.message);
                                });
                            }
                        });*/
                    } else {
                        console.log('Current url is a product url');
                    }
                } else {
                    console.log('No Product Found')
                }
            }
        };
    })();