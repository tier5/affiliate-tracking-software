<div class="topheader"></div>


<div class="main-container">
    <header>
        <div class="headercontent">
            <div class="logo">
                <img src="/img/logo-white.gif" alt="Review Velocity" />
            </div>
            <div class="contactnumber">
                <span class="contact-text">Contact Us:</span> <span class="contact-phone">(866) 700-9330</span>
            </div>
        </div>

    </header>
    <div class="col-xs-8 left-container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    STEP 1: Contact Information
                </div>
            </div>
        </div>
        <div class="row form-group">

        </div>

        <div class="row subscription-panel-group">
            <div class="col-xs-12">
                <div class="portlet light bordered dashboard-panel">
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>First Name</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Please enter your first name" name="FirstName" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Last Name</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Please enter your last name" name="LastName" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Email</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="email" class="form-control" placeholder="Please enter your email" name="Email" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Phone</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Please enter your phone" name="Phone" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Get A Company URL</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="text" id="URL" class="form-control" name="URL" required /></div>
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
                                        <div class="green-header">
                                            Security is our top priority at Review Velocity!
                                        </div>
                                        <div class="green-description">
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
            <div class="col-xs-9">
                <div class="">
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Card Number</label><span class="required">*</span></div>
                        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Please enter your first name" name="CardNumber" required /></div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Card Type</label><span class="required">*</span></div>
                        <div class="col-xs-9">
                            <select name="CardType" class="form-control">
                                <option value="0">Please select card type</option>
                            {% for Card in tCardTypes %}
                                <option value="{{ Card }}">{{ Card }}</option>
                            {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>Expiration Date</label><span class="required">*</span></div>
                        <div class="col-xs-5">
                            <select name="MonthExpiration" class="form-control">
                            {% for NumMonth, Month in tMonths %}
                                <option value="{{ NumMonth }}">{{ Month }}</option>
                            {% endfor %}
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <select name="CardType" class="form-control">
                            {% for Year in tYears %}
                                <option value="{{ Year }}">{{ Year }}</option>
                            {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="row contact-row">
                        <div class="col-xs-3"><label>CVV Code</label></div>
                        <div class="col-xs-9"><input type="text" class="form-control" name="CVV" /></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <img src="/img/agencysignup/secure_checkout.png" alt="Secure Checkout" id="SecureImage"/>
            </div>

            <div class="col-xs-12 deal-section">
                Review Velocity Business Listing & Reputation Software $1 Trial For 14 Days Then $47 a Month, when you order Today!
            </div>


            <div class="col-xs-12 total-section">
                <div style="float: left;">Total Due Today:</div>
                <div style="float: right;">$1</div>
            </div>

            <div class="col-xs-12 submit-section">
                <button class="big-green-button">
                    Submit Order
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-4 right-container">
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
</div>