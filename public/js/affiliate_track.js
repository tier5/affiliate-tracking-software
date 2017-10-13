var Affiliate = Affiliate || (function(){

        var _callback_url = 'https://www.affiliatemarketingpromotions.com';
//        var _callback_url = 'http://affiliate.reviewvelocity.local';
//         var _callback_url = 'http://localhost/reviewvelocity/public';

        var COOKIE_NAME = 'amp_affiliate';

        var LEAD_COOKIE_NAME = 'amp_lead';

        var COOKIE_LOG_ID = 'amp_log_id';

        var COOKIE_PRODUCT_URL = 'amp_product_url';

        var COOKIE_PRODUCT = 'amp_product';

        var _proxy = null;

        /**
         * Call any Api
         * @type {{xhr: null, request: request}}
         */
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

        /**
         * Call any Api
         * @type {{xhr: null, request: request}}
         */
        var Ajax1 = {
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

        /**
         * Get all Query String
         * @returns {{ query string }}
         */
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

        /**
         * Encode and Url parameter
         * @param val
         * @returns {string}
         */
        function encode(val) {
            if (window.encodeURIComponent) {
                return encodeURIComponent(val);
            } else {
                //noinspection JSDeprecatedSymbols
                return escape(val);
            }
        }

        /**
         * Decode any url parameter
         * @param val
         * @returns {string}
         */
        function decode(val) {
            if (window.decodeURIComponent()) {
                return decodeURIComponent(val);
            } else {
                //noinspection JSDeprecatedSymbols
                return unescape(val);
            }
        }

        /**
         * Set a cookie in the client browser
         * @param name , cookie name
         * @param val , cookie value
         * @param timeout , cookie timeout
         * @param id
         */
        function setCookie(name, val, timeout) {
            timeout = typeof timeout !== 'undefined' ? timeout : 1; // defaults to 1 day
            timeout *= 60*60*24*1000; // ms to seconds
            console.log("Setting cookie  " + name + " to value " + val);
            var now = new Date();
            var time = now.getTime();
            time += timeout;
            now.setTime(time);
            var cookieObj = name + "=" + encode(val) + ";expires=" + now.toUTCString()+';path=/';
            document.cookie = cookieObj;
        }

        /**
         * Get a Cookie form client browser
         * @param name
         * @returns {*}
         */
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

        /**
         * Delete a cookie
         * @param name
         */
        function deleteCookie(name) {
            console.log("Deleting Cookie " + name);
            if (_proxy && _proxy.deleteCookie) {
                _proxy.deleteCookie(name);
            } else {
                document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
            }
        }

        /**
         * Check any element is email or not
         * @param email
         * @returns {boolean}
         */
        function isEmail(email) {
            console.log('checking email');
            return /\S+@\S+\.\S+/.test(email);
        }

        /**
         * Remove any get parameter from a Url
         * @param key
         * @param sourceURL
         * @returns {*}
         */
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

        /**
         * Detect Browser and OS
         * @returns {browser,os}
         */
        function detectBrowser() {
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
            var returnArray = [];
            var browser = browserName+' V'+majorVersion;
            returnArray.push(browser);
            //Detect OS
            var OSName="Unknown OS";
            if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
            if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
            if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
            if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";
            returnArray.push(OSName);
            return returnArray;
        }

        /**
         * track Sales Page for log and link of order page
         * @param affiliate_id
         */
        function salesPageTrack(affiliate_id,log_id) {
            var campaign_url = '';
            var browser = detectBrowser();
            if(log_id > 0){
                var dataPost = 'key='+affiliate_id+'&urlKey='+Affiliate.key+'&dataId='+log_id;
                Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                    console.log(dataNew.message);
                    var dataPostOrder = 'campaign_id='+Affiliate.key;
                    Ajax.request(_callback_url + "/api/check/order_url","POST",dataPostOrder,function (dataNew) {
                        campaign_url = dataNew.data;
                    },function () {
                        //
                    });
                },function () {
                    //
                });
            } else {
                Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                    var dataPost = 'ip='+data.ip+'&key='+affiliate_id+'&browser='+browser[0]+'&urlKey='+Affiliate.key+'&os='+browser[1];
                    Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                        var cookie_affiliate = getCookie(COOKIE_NAME);
                        var cookie_log_id = getCookie(COOKIE_LOG_ID);
                        if(cookie_affiliate == ''){
                            setCookie(COOKIE_NAME,affiliate_id,30);
                        }
                        if(cookie_log_id == ''){
                            setCookie(COOKIE_LOG_ID,dataNew.data,30);
                        }
                        log_id = dataNew.data;
                        console.log(dataNew.message);
                        var dataPostOrder = 'campaign_id='+Affiliate.key;
                        Ajax.request(_callback_url + "/api/check/order_url","POST",dataPostOrder,function (dataNew) {
                            campaign_url = dataNew.data;
                        },function () {
                            //
                        });
                    },function () {
                        //
                    });
                },function(){
                    console.log('ip not found');
                });
            }
            window.onload = function() {
                setTimeout(function(){
                    var allitems = [];
                    allitems = Array.prototype.concat.apply(allitems, document.getElementsByTagName('a'));
                    for(var i = 0; i < allitems.length; i++) {
                        var anchor = allitems[i];
                        var oldAnchor = anchor.getAttribute("href");
                        if(oldAnchor === campaign_url){
                            var newAnchor = oldAnchor + '?affiliate_id='+affiliate_id+'&affiliate_log='+log_id;
                            anchor.setAttribute('href',newAnchor);
                        }
                    }
                }, 1000);
            };
        }

        /**
         * For Log any lead
         * @param id
         * @param lead
         */
        function leadLog(id,lead){
            if(isEmail(lead)){
                var lead_cookie = getCookie(LEAD_COOKIE_NAME);
                if(lead_cookie == ''){
                    setCookie(LEAD_COOKIE_NAME,lead,86400,null);
                    dataPost = 'dataId='+id+'&email='+window.btoa(lead);
                    Ajax.request(_callback_url + "/api/affiliate/lead","POST",dataPost,function (dataNew) {
                        console.log(dataNew.message);
                    },function () {
                        //
                    });
                } else {
                    if(lead != lead_cookie){
                        deleteCookie(LEAD_COOKIE_NAME);
                        setCookie(LEAD_COOKIE_NAME,lead,86400,null);
                        dataPost = 'dataId='+id+'&email='+window.btoa(lead);
                        Ajax.request(_callback_url + "/api/affiliate/lead","POST",dataPost,function (dataNew) {
                            console.log(dataNew.message);
                        },function () {
                            //
                        });
                    } else {
                        console.log('same Email');
                    }
                }
            }
        }

        /**
         * Track a Order Page or Upgrade page
         * @param affiliate_id
         * @param log_id
         */
        function orderPageTrack(affiliate_id,log_id) {
            if(affiliate_id != '' && log_id > 0){
                var dataPost = 'key='+affiliate_id+'&urlKey='+Affiliate.key+'&dataId='+log_id;
                Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                    var cookie_affiliate = getCookie(COOKIE_NAME);
                    var cookie_log_id = getCookie(COOKIE_LOG_ID);
                    if(cookie_affiliate == ''){
                        setCookie(COOKIE_NAME,affiliate_id,30);
                    }
                    if(cookie_log_id == ''){
                        setCookie(COOKIE_LOG_ID,log_id,30);
                    }
                    console.log(dataNew.message);
                    var allInputs = [];
                    allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=text]'));
                    allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=email]'));
                    for(var i = 0; i < allInputs.length; i++) {
                        var input = allInputs[i];
                        input.addEventListener('change', function() {
                            var lead = this.value;
                            leadLog(log_id,lead);
                        });
                    }
                },function () {
                    //
                });
            } else if (affiliate_id != '' && log_id == 0){
                var browser = detectBrowser();
                Ajax.request("https://api.ipify.org/?format=json","GET",null,function(data){
                    var dataPost = 'ip='+data.ip+'&key='+affiliate_id+'&browser='+browser[0]+'&urlKey='+Affiliate.key+'&os='+browser[1];
                    Ajax.request(_callback_url + "/api/affiliate/report","POST",dataPost,function (dataNew) {
                        var cookie_affiliate = getCookie(COOKIE_NAME);
                        var cookie_log_id = getCookie(COOKIE_LOG_ID);
                        if(cookie_affiliate == ''){
                            setCookie(COOKIE_NAME,affiliate_id,30);
                        }
                        if(cookie_log_id == ''){
                            setCookie(COOKIE_LOG_ID,dataNew.data,30);
                        }
                        console.log(dataNew.message);
                        var allInputs = [];
                        allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=text]'));
                        allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=email]'));
                        for(var i = 0; i < allInputs.length; i++) {
                            var input = allInputs[i];
                            input.addEventListener('change', function() {
                                var lead = this.value;
                                leadLog(dataNew.data,lead);
                            });
                        }
                    },function () {
                        //
                    });
                },function(){
                    console.log('ip not found');
                });
            } else {
                console.log('affiliate not found');
            }
        }
        
        /*
         * 
         * @param {selctor} obj
         * @param {string} evt
         * @return {nothing}
         */
        
        function fireEvent(obj, evt){
            var fireOnThis = obj;
            if( document.createEvent ) {
                var evObj = document.createEvent('MouseEvents');
                evObj.initEvent( evt, true, false );
                fireOnThis.dispatchEvent( evObj );
            }else if( document.createEventObject ) { //IE
                var evObj = document.createEventObject();
                fireOnThis.fireEvent( 'on' + evt, evObj );
            } 
        } 

        /**
         * main return function
         */
        return {
            //For track a sales page
            _sales : function () {
                console.log('Initialize sales script.');
                var qs = getQueryStrings();
                var affiliate_id = qs.affiliate_id;
                var cookie_affiliate = getCookie(COOKIE_NAME);
                if(cookie_affiliate != ''){
                    var log_id = getCookie(COOKIE_LOG_ID);
                    salesPageTrack(cookie_affiliate,log_id)
                } else if(cookie_affiliate == '' && affiliate_id != undefined){
                    salesPageTrack(affiliate_id,0);
                } else {
                    console.log('Normal visit');
                }

            },
            //For Track a any page
            init : function () {
                console.log('Initialize init script');
                var aff_id = getCookie(COOKIE_NAME);
                var aff_log = getCookie(COOKIE_LOG_ID);
                var qs = getQueryStrings();
                var aff_id_qs = qs.affiliate_id;
                var aff_log_qs = qs.affiliate_log;
                if(aff_id_qs != undefined){
                    var url = removeParam('affiliate_id',window.location.href);
                    var newUrl = removeParam('affiliate_log',url);
                    window.history.pushState(null, null, newUrl);
                }
                if(aff_id != '' && aff_log != ''){
                    orderPageTrack(aff_id,aff_log);
                } else if(aff_id == '' && aff_log == '' && aff_id_qs != undefined && aff_log_qs != undefined) {
                    orderPageTrack(aff_id_qs,aff_log_qs);
                } else if(aff_id == '' && aff_log == '' && aff_id_qs != undefined && aff_log_qs == undefined) {
                    orderPageTrack(aff_id_qs,0);
                } else {
                    console.log('Normal visit');
                }
            },
            //For Track a lead
            lead : function () {
                console.log('Initialize lead script');
                /*window.onload = function() {
                 console.log('here');
                 var allInputs = [];
                 allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=text]'));
                 allInputs = Array.prototype.concat.apply(allInputs, document.querySelectorAll('input[type=email]'));
                 for (var i = 0; i < allInputs.length; i++) {
                 var input = allInputs[i];
                 console.log(input);
                 input.addEventListener('change', function () {
                 var lead = this.value;
                 if (isEmail(lead)) {
                 var lead_cookie = getCookie(LEAD_COOKIE_NAME);
                 if (lead != lead_cookie) {
                 var log_id = getCookie(COOKIE_LOG_ID);
                 deleteCookie(LEAD_COOKIE_NAME);
                 setCookie(LEAD_COOKIE_NAME, lead, 30);
                 dataPost = 'dataId=' + log_id + '&email=' + lead;
                 Ajax.request(_callback_url + "/api/affiliate/lead", "POST", dataPost, function (dataNew) {
                 console.log(dataNew.message);
                 }, function () {
                 //
                 });
                 } else {
                 console.log('same lead');
                 }
                 }
                 });
                 }
                 }*/
            },
            //For Track any sales
            watch : function () {
                setTimeout(function(){
                    console.log('Initialize watch script');
                    var windowsLocation = window.location.href;
                    var previousUrl = getCookie(COOKIE_PRODUCT_URL);
                    var aff_log = getCookie(COOKIE_LOG_ID);
                    var order_id = getCookie(COOKIE_PRODUCT);
                    if(order_id == ''){
                        order_id = 0;
                    }
                    var dataPostOrder = 'current_url='+windowsLocation+'&campaign='+ Affiliate.key;
                    Ajax1.request(_callback_url + "/api/v2/check/order/url", "POST", dataPostOrder, function (dataNewProduct) {
                        deleteCookie(COOKIE_PRODUCT_URL);
                        deleteCookie(COOKIE_PRODUCT);
                    }, function () {
                        if(previousUrl != '' && aff_log != null){
                            if(previousUrl != windowsLocation) {
                                var dataPostProduct = 'previous_url=' + previousUrl + '&campaign=' + Affiliate.key + '&currentUrl=' + windowsLocation + '&log_id=' + aff_log + '&order_id=' + order_id;
                                Ajax1.request(_callback_url + "/api/v2/check/landing_page/url", "POST", dataPostProduct, function (dataNewProduct) {
                                    deleteCookie(COOKIE_PRODUCT_URL);
                                    if(order_id > 0){
                                        deleteCookie(COOKIE_PRODUCT);
                                    }
                                    setCookie(COOKIE_PRODUCT,dataNewProduct.data,30);
                                    console.log(dataNewProduct.message);
                                }, function () {
                                    //
                                });
                            } else {
                                console.log('Yes');
                            }
                        }
                    });
                }, 1000);
                window.onload = function() {
                    var windowsLocation = window.location.href;
                    var previousUrl = getCookie(COOKIE_PRODUCT_URL);
                    var allitems = [];
                    var anchor_allitems = [];
                    flag = false;
                    allitems = Array.prototype.concat.apply(allitems, document.getElementsByTagName('button'));
                    allitems = Array.prototype.concat.apply(allitems, document.querySelectorAll('input[type=submit]'));
                    allitems = Array.prototype.concat.apply(allitems, document.querySelectorAll('input[type=button]'));
                    for(var i = 0; i < allitems.length; i++) {
                        var anchor = allitems[i];
                        anchor.onclick = function(event) {
                            anchor_el = true;
                            if(flag === true){
                                flag = false;
                                return;
                            }
                            event.preventDefault();
                            var dataPost = 'previous_url=' + windowsLocation + '&campaign=' + Affiliate.key;
                            Ajax1.request(_callback_url + "/api/check/product", "POST", dataPost, function (dataNew) {
                                if (previousUrl) {
                                    deleteCookie(COOKIE_PRODUCT_URL);
                                }
                                setCookie(COOKIE_PRODUCT_URL, windowsLocation, 30);
                                flag = true;
                                fireEvent(anchor, 'click');
                            }, function () {
                                // if ajax request fail
                                flag = true;
                                fireEvent(anchor, 'click');
                            });
                        }
                    }
                    anchor_allitems = Array.prototype.concat.apply(anchor_allitems, document.getElementsByTagName('a'));
                    for(var i = 0; i < anchor_allitems.length; i++) {
                        var anchor_el = anchor_allitems[i];
                        anchor_el.onclick = function(event) {
                            if(flag === true){
                                flag = false;
                                return;
                            }
                            event.preventDefault();
                            var dataPost = 'previous_url=' + windowsLocation + '&campaign=' + Affiliate.key;
                            Ajax1.request(_callback_url + "/api/check/product", "POST", dataPost, function (dataNew) {
                                if (previousUrl) {
                                    deleteCookie(COOKIE_PRODUCT_URL);
                                }
                                setCookie(COOKIE_PRODUCT_URL, windowsLocation, 30);
                                flag = true;
                                fireEvent(anchor_el, 'click');
                            }, function () {
                                // if ajax request fail
                                flag = true;
                                fireEvent(anchor_el, 'click');
                            });
                        }
                        break;
                    }
                }
            }

        };
    })();
