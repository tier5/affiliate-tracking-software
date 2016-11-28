<form method="post" action="submitorder" id="payment-form">
    <?php
        if($_GET['sbyp'] || $_POST['sbyp'])
            $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
    ?>
    <input type="hidden" name="sbyp" value="{{ sbyp }}" />
    <span class="payment-errors"></span>
    <div class="row small-vertical-margins">
        <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 col-xs-offset-1 col-md-offset-1">
            <img class="logo-order" src="/img/logo-white.gif" alt="Get Mobile Reviews" />
        </div>
        <div class="col-xs-7 col-sm-4 col-sm-offset-4 col-lg-3 col-md-offset-6">
            <span class="contact-text">Contact Us:</span> <span class="contact-phone">(866) 700-9330</span>
        </div>
    </div>

    <div class="col-xs-12 col-md-8 col-lg-8 left-container small-vertical-margins">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    STEP 1: Contact Information
                </div>
            </div>
        </div>
        <div class="row form-group"></div>

        <div class="row subscription-panel-group">
            <div class="col-xs-12">
                <div class="portlet light bordered dashboard-panel">
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3">
                        	<label>First Name</label>
                        	<span class="required">*</span>
                        </div>
                        <div class="col-xs-7 col-lg-9">
                        	<input type="text" maxlength="30" class="form-control" placeholder="Please enter your first name" name="FirstName" value="{{ FirstName }}" required />
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3">
                        	<label>Last Name</label>
                        	<span class="required">*</span>
                        </div>
                        <div class="col-xs-7 col-lg-9">
                        	<input type="text" maxlength="30" class="form-control" placeholder="Please enter your last name" name="LastName" value="{{ LastName }}" required />
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3">
                        	<label>Email
                        		<span class="required">*</span></label>
                        		<span style="color:red; font-size:80%; float: right,bottom;  white-space:nowrap;" id="Email_availability_result"></span>
                      
                        </div>
                        <div class="col-xs-7 col-lg-9">
                        	<input type="text" id="OwnerEmail" maxlength="50" class="form-control" placeholder="Please enter your email" name="OwnerEmail" value="{{ OwnerEmail }}" required />
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3" >
                        	<label>
                        		<span class="required">Get A Company URL*</span></label>
                        		<span style="color:red; font-size:80%; float: right;  white-space:nowrap;" id="URL_availability_result"></span>
                        </div>
                        <div class="col-xs-7 col-lg-9">
                        	<input type="text" id="URL" maxlength="30" class="form-control website-url" name="URL" value="{{ URL }}" required />
                        	<span class="append_content hidden-xs">.getmobilereviews.com</span>
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3">
                        	<label class="">Password</label><label class=""></label>
                        	<span class="required">*</span>
                        </div>
                        <div class="col-xs-7 col-lg-9">
                        	<input id="Password" class="form-control"  maxlength="30" name="Password" type="password" required />
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3">
                        	<label class="">Confirm Password</label><label class=""></label>
                        	<span class="required">*</span>
                        	<span style="color:red; font-size:80%; float: right;  white-space:nowrap;" id="Confirm_password_result"></span>	
                        </div>
                        <div id="Password_match_result"></div>
                        <div class="col-xs-7 col-lg-9">
                        	<input id="ConfirmPassword" class="form-control" maxlength="30"  name="ConfirmPassword" type="password" required />
                        	
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    STEP 2: Payment Information
                </div>
            </div>
        </div>

        <div class="row subscription-panel-group change-plans-row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet light bordered dashboard-panel">
                            <div class="portlet-body">
                                <div class="panel panel-default apple-backgound">
                                    <div class="panel-body">
                                        <div class="green-header text-center">
                                            Security is our top priority at Get Mobile Reviews!
                                        </div>
                                        <div class="green-description text-justify">
                                            This website utilizes some of the most advanced techniques to protect your information and personal data including technical, administrative, and even physical safeguards against unauthorized access, misuse, and disclosure.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <img class="center-block" src="/img/agencysignup/credit_cards.png" alt="We accept Visa MasterCard American Express Discover" />
            </div>
        </div>

        <div class="row subscription-panel-group portlet light bordered small-vertical-margins">
            <div class="col-xs-12 col-lg-9">
                <div class="">
                    <div class="row contact-row">
                        <div class="col-xs-12 col-lg-3">
                        	<label>Card Number</label>
                        	<span class="required">*</span>
                        	<span style="color:red; font-size:80%; float: right;  white-space:nowrap;" id="Valid_credit_card_number_result"></span>	
                        </div>
                        <div class="col-xs-12 col-lg-9">
                        	<input name="CreditCardNumber" id="CreditCardNumber" maxlength="16" size="16" type="text" class="form-control" required data-stripe="number" />
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-12 col-lg-3"><label>Exp. Date</label><span class="required">*</span></div>
                        <div class="col-xs-6 col-lg-5">
                            <select class="form-control" data-stripe="exp_month">
                                {% for NumMonth, Month in tMonths %}
                                    <option value="{{ NumMonth }}">{{ Month }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="col-xs-6 col-lg-4">
                            <select class="form-control" data-stripe="exp_year">
                                {% for Year in tYears %}
                                    <option value="<?=substr($Year, -2); ?>">{{ Year }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-12 col-lg-3"><label>CVC</label></div>
                        <div class="col-xs-12 col-lg-9"><input maxlength="4" size="4" type="text" class="form-control" data-stripe="cvc" /></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3 ">
	            <span id="siteseal"><Br><br><Br>
	            	<script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=umKwirMP3LZxPHdMDSMSTvPxXVGgTtvG4cGYHfMfmcxcWXYeBD0QvXRH5MuQ"></script></span>
            </div>

            <div class="col-xs-12 deal-section text-justify">
                Complete White Label review acquisition, monitoring and marketing solution with 10 active locations & unlimited FREE trials. Get started today! $97 activation fee and $100/mo fee with 10 active accounts included.
            </div>


            <div class="col-xs-12 total-section">
                <div style="float: left;">Total Due Today:</div>
                <div style="float: right;">$197</div>
            </div>
            <div class="col-xs-12 col-xs-offset-1 submit-section">
                <button class="big-green-button submit" id="BigGreenSubmit">Submit Order</button><br>
                <center><div class="col-xs-10 ">By clicking this button, you agree to Get Mobile Reviews's<br /><A href="/session/RVreseller" target="blank">Reseller Terms</a>,  <A href="/session/RVantispam" target="blank">Anti-spam</a>,  <A href="/session/RVprivacy" target="blank">Privacy Policy</a>,  <A href="/session/RVterms" target="blank">Terms of Use</a>.</div>
            </center></div>
        </div>
    </div>
    <div class="col-xs-3 col-xs-offset-1 hidden-xs hidden-sm">
        <div class="row">
            <img class="center-block" src="/img/agencysignup/monitor_dashboard.png" alt="Dashboard" />
        </div>
        <div class="row right-header">
            What You Get With Get Mobile Reviews
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                White Label Reputation Management Agency Account With Your Own Company Branding and URL
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Complete Done-For-You Reputation Management Agency In A Box Solution From Lead to Fulfillment
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Employee Gamification With Leaderboards To Keep Businesses Engage
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Unlimited Trial Accounts To Help You Maximize Lead Conversion Potential
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Viral Account Engine Rewards Businesses For Giving Away Trial Accounts For You
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Built In Payment Processing To Efficiently Build Your Recurring Revenue
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Duplicate Account Prevention Ensures Exclusivity Of New Account Trials
            </div>
        </div>
        <div class="row right-header">
            Need Help?
        </div>
        <div class="row right-feature">
            <div class="col-xs-12">
                Call us at: (866) 700-9330 9am-5pm PST Monday - Friday
            </div>
        </div>
        <div class="row right-header">
            Customer Support
        </div>
        <div class="row right-feature">
            <div class="col-xs-12">
                <a href="mailto:support@reviewvelocity.co">support@reviewvelocity.co</a>
            </div>
        </div>
    </div>
</form>
<!-- BEGIN LOGIN -->
<div class="content">
    {{ content() }}
</div>
<footer>
    <div class="copyright"> &copy; Copyright Get Mobile Reviews.  All Rights Reserved. </div>
        <!--[if lt IE 9]>
        <script src="/assets/global/plugins/respond.min.js"></script>
        <script src="/assets/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/js/agencysignup.order.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script type="text/javascript" src="/js/vendor/minicolors/jquery.minicolors.js"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
</footer>

<!-- BEGIN LOGIN -->

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
  Stripe.setPublishableKey('{{ StripePublishableKey }}');
</script>

<script>
  window.intercomSettings = {
    app_id: "c8xufrxd"
  };
</script>

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/c8xufrxd';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

<script type="text/javascript" src="https://static.leaddyno.com/js"></script>
<script>
LeadDyno.key = "e968cc778d43209f7e7474d59c0bff8b13215c49";
LeadDyno.recordVisit();
LeadDyno.autoWatch();
</script>