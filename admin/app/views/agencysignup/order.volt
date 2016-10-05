<form method="post" action="submitorder" id="payment-form">
    <span class="payment-errors"></span>
    <div class="row small-vertical-margins">
        <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 col-xs-offset-1 col-md-offset-1">
            <img class="logo-order" src="/img/logo-white.gif" alt="Review Velocity" />
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
                        <div class="col-xs-5 col-lg-3"><label>First Name</label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input type="text" class="form-control" placeholder="Please enter your first name" name="FirstName" value="{{ FirstName }}" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3"><label>Last Name</label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input type="text" class="form-control" placeholder="Please enter your last name" name="LastName" value="{{ LastName }}" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3"><label>Email</label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input type="email" class="form-control" placeholder="Please enter your email" name="OwnerEmail" value="{{ OwnerEmail }}" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3"><label class="hidden-xs">Get A Company URL</label><label class="hidden-sm hidden-md hidden-lg">Domain</label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input type="text" id="URL" class="form-control website-url" name="URL" value="{{ URL }}" required /><span class="append_content hidden-xs">.getmobilereviews.com</span></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3"><label class="hidden-xs">Password</label><label class="hidden-sm hidden-md hidden-lg"></label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input id="Password" class="form-control" name="Password" type="password" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-5 col-lg-3"><label class="hidden-xs">Confirm Password</label><label class="hidden-sm hidden-md hidden-lg"></label><span class="required">*</span></div>
                        <div class="col-xs-7 col-lg-9"><input id="ConfirmPassword" class="form-control" name="ConfirmPassword"  type="password" required /></div>
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
                                            Security is our top priority at Review Velocity!
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
                        <div class="col-xs-12 col-lg-3"><label>Card Number</label><span class="required">*</span></div>
                        <div class="col-xs-12 col-lg-9"><input type="text" class="form-control" required data-stripe="number" /></div>
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
                        <div class="col-xs-12 col-lg-9"><input type="text" class="form-control" data-stripe="cvc" /></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3 hidden-xs hidden-sm">
                <img src="/img/agencysignup/secure_checkout.png" alt="Secure Checkout" id="SecureImage"/>
            </div>

            <div class="col-xs-12 deal-section text-justify">
                Review Velocity Business Listing & Reputation Software $1 Trial For 14 Days Then $47 a Month, when you order Today!
            </div>


            <div class="col-xs-12 total-section">
                <div style="float: left;">Total Due Today:</div>
                <div style="float: right;">$1</div>
            </div>

            <div class="col-xs-12 col-xs-offset-1 submit-section">
                <button class="big-green-button submit" id="BigGreenSubmit">
                    Submit Order
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-3 col-xs-offset-1 hidden-xs hidden-sm">
        <div class="row">
            <img class="center-block" src="/img/agencysignup/monitor_dashboard.png" alt="Dashboard" />
        </div>
        <div class="row right-header">
            What You Get With Review Velocity
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                White Label Local Business Listings & Reputation Scanning Tool With Your Branding
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Customized Landing Branded To Your Company On Your Own URL Ready For Paid Traffic
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Multiple Scanning Tool Embed Options For Your Website (Large Form, Slide-In Form, Slim Form, & Small Form)
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Immediate Email & SMS Notifications Of New Leads
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Review Monitoring On Top Directories & Niche Sites - Daily, Weekly, & Monthly Reporting To Customers
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Stripe Payment Integrations To Accept Payments
            </div>
        </div>
        <div class="row right-feature">
            <div class="col-xs-1">
                <img src="/img/agencysignup/green_checkmark.png" alt="Check!" />
            </div>
            <div class="col-xs-10">
                Agency Dashboard To Manage Prospects, Leads, & Customers
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  Stripe.setPublishableKey('{{ StripePublishableKey }}');

  $( document ).ready(function() {
        $('#BigGreenSubmit').click(function() {
            if ($('#Password').val() != $('#ConfirmPassword').val()) {
                alert('Password do not match');
                return;
            }

            if ($('#Password').val().length < 6) {
                alert('Password must be 6 characters or more.');
                return;
            }

            $('#payment-form').submit();
        });
    });

  $(function () {
      var $form = $('#payment-form');
      $form.submit(function (event) {
          // Disable the submit button to prevent repeated clicks:
          $form.find('.submit').prop('disabled', true);

          // Request a token from Stripe:
          Stripe.card.createToken($form, stripeResponseHandler);

          // Prevent the form from being submitted:
          return false;
      });
      function stripeResponseHandler(status, response) {
          // Grab the form:
          var $form = $('#payment-form');

          if (response.error) { // Problem!

              // Show the errors on the form:
              $form.find('.payment-errors').text(response.error.message);
              $form.find('.submit').prop('disabled', false); // Re-enable submission

          } else { // Token was created!

              // Get the token ID:
              var token = response.id;

              // Insert the token ID into the form so it gets submitted to the server:
              $form.append($('<input type="hidden" name="stripeToken">').val(token));

              // Submit the form:
              $form.get(0).submit();
          }
      };
  });

</script>

<script>
  window.intercomSettings = {
    app_id: "c8xufrxd"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/c8xufrxd';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>