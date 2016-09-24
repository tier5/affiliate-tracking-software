{% if(CleanUrl === true) %}
<script>
	$().ready(function(){ // Remove query params from URL for preview
		window.history.replaceState({}, document.title, window.location.href.replace(window.location.search,''));
	});
</script>
{% endif %}
<div class="row small-vertical-margins">
    <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 col-xs-offset-1 col-md-offset-1">
    	{% if LogoPath != '' %}
        	<img class="logo-order" src="{{ LogoPath }}"  alt="{{ Name }}" />
        {% else  %}
        	<span></span>
        {% endif  %}
    </div>
    <div class="col-xs-7 col-sm-4 col-sm-offset-4 col-lg-3 col-md-offset-6">
        <span class="contact-text">Contact Us:</span> <span class="contact-phone">{{Phone}}</span>
    </div>
</div>
<div class="light-section">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center">Generate 5 Star Reviews Online Real Fast</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
            <h5 class="text-center">Because over 90% of all text messages are opened within the first few minutes, our proprietary mobile review funnel helps empower your staff and employees to help generate recent customer feedback and safely turn them into positive online reviews.</h5>
        </div>
        <div class="col-xs-3"></div>
    </div>
    <div class="row small-vertical-margins">

        <div class="col-xs-10 col-xs-offset-1">
            <a href="http://{{ SubDomain }}/session/sales">
                <button class="big-green-button thin-white-text SecondaryColor center-block">
                    Click Here To Sign Up Today
                </button>
            </a>
        </div>
        <div class="col-xs-2"></div>
    </div>
    <div class="row bottom-padding">
        <div class="col-xs-12">
            <img class="center-block" src="/img/agencysignup/sales/mac.png" alt="Dashboard" />
        </div>
    </div>
</div>

<div class="medium-section">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center">The Benefits of Reputation Management</h1>
        </div>
    </div>
    <div class="row large-vertical-margins">
        <div class="col-xs-2"></div>
        <div class="col-xs-4 light-section" style="margin-right: 15px; height: 275px;">
            <img class="center-block small-raise" src="/img/agencysignup/sales/icon_green_circle_chart.png" alt="Graph" />
            <div style="height: 70px"><h3 class="green-text text-center sub-header-text SecondaryColorText">Generate Fresh Customer Online Reviews Daily</h3></div>
            <div class="text-center description-text">The key to winning new business and boosting revenue is through social proof from your most recent happy visitors.  Help prospective customers choose you over your competitors by ranking higher and being the obvious choice.</div>
        </div>
        <div class="col-xs-4 light-section" style="margin-left: 15px; height: 275px;">
            <img class="center-block small-raise" src="/img/agencysignup/sales/icon_green_circle_people.png" alt="People" />
            <div style="height: 70px"><h3 class="green-text text-center sub-header-text SecondaryColorText">Increase Employee Accountability</h3></div>
            <div class="text-center description-text">Leverage your key staff and employees that work directly with customers through gamifcation of the feedback process.  Our powerful leader board module adds social proof to motivate and incentivize employees to actively participate in the review getting process.</div>
        </div>
        <div class="col-xs-2"></div>
    </div>
    <div class="row padding-bottom"></div>
</div>

<div class="light-section">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center">Start Generating Reviews In 3 Easy Steps</h1>
        </div>
        <div class="col-xs-12">
            <h5 class="text-center">This Proven Process Guarantees You'll See Results Fast</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1"></div>

        <div class="col-xs-6 medium-vertical-margins">
            <div class="col-xs-1">
                <div style="" class="green-header text-center green-circle">1</div>
            </div>
            <div class="col-xs-11">
                <h3 class="sub-header-text">Start the process by simply adding in your recent customers name and their mobile phone number.</h3>
                <span class="description-text">Then sit back while our proprietary review funnel does the rest.  You can even assign customer feedback to a specific department or employee.  Zero technical skills required.</span>
            </div>
        </div>
        <div class="col-xs-4">
            <img class="center-block" src="/img/agencysignup/sales/phone_add_customer.png" alt="Add Customer" />
        </div>
        <div class="col-xs-1"></div>
    </div>
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10 orange-border"></div>
        <div class="col-xs-1"></div>
    </div>

    <div class="row medium-vertical-margins" style="margin-bottom: 0px;">
        <div class="col-xs-1"></div>

        <div class="col-xs-4">
            <img class="center-block" src="/img/agencysignup/sales/phone_recommend_us.png" alt="Would You Recommend Us?" />
        </div>

        <div class="col-xs-6 medium-vertical-margins">
            <div class="col-xs-1">
                <div class="green-header text-center green-circle">2</div>
            </div>
            <div class="col-xs-11">
                <h3 class="sub-header-text">Customers receive a simple one question survey via text message asking for feedback.</h3>
                <span class="description-text">Your recent customer responds sharing their experience with your company, and based on predetermined settings, are routed to post a review online or to a branded page matching the sentiment of their feedback!</span>
            </div>
        </div>
        <div class="col-xs-1"></div>
    </div>

    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10 orange-border"></div>
        <div class="col-xs-1"></div>
    </div>

    <div class="row medium-vertical-margins" style="margin-bottom: 0px;">
        <div class="col-xs-1 col-centered">
            <div class="green-header text-center green-circle">3</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            <div class="col-xs-11">
                <h3 class="sub-header-text">Positive responders are immediately asked to leave a review on top sites like Google, Yelp, or Social Sites like Facebook.</h3>
            </div>
        </div>

        <div class="col-xs-5">
            <div class="col-xs-11">
                <h3 class="sub-header-text">Negative responders are kept inside the system and directed to a survey form allowing them to share their experience with just you.</h3>
            </div>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            <div class="col-xs-11 description-text">
                Our proprietary software automatically launches the application or page instantly on your customer's mobile device or desktop browser.
            </div>
        </div>

        <div class="col-xs-5">
            <div class="col-xs-11 description-text">
                Our proven feedback funnel catches a potential negative review and alerts you to an issue before they show up online as a 1 star review.
            </div>
        </div>
    </div>

    <div class="row medium-vertical-margins" style="margin-bottom: 0px;">
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            <div class="col-xs-11">
                <img class="center-block" src="/img/agencysignup/sales/phone_review_site_promotion.png" alt="Promote your sites!" />
            </div>
        </div>


        <div class="col-xs-5">
            <div class="col-xs-11">
                <img class="center-block" src="/img/agencysignup/sales/phone_improve.png" alt="Improve your business!" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10 orange-border"></div>
        <div class="col-xs-1"></div>
    </div>

    <div class="row bottom-padding"></div>

</div>


<div class="medium-section top-padding">
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-4">
            <img class="center-block" src="/img/agencysignup/sales/business_dude.png" alt="Owner" />
        </div>
        <div class="col-xs-5">
            <div clasw="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-11">
                    <h3 class="sub-header-text">Owners and Managers Say Our Reputation Management Software Helps Them:</h3>
                </div>
            </div>

            <div class="row bottom-black-border" style="padding-bottom: 20px;">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="description-text">
                        <ul>
                            <li>Increase online reviews</li>
                            <li>Employee accountability</li>
                            <li>More customers and revenue</li>
                        </ul>
                    </span>
                </div>
            </div>
            {# Beging review #1 #}
            <div class="row medium-vertical-margins" style="margin-bottom: 10px;">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="title-text">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...</span>
                </div>
            </div>

            <div class="row extra-small-vertical-margins">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="subtitle-text">By: Ipsum - May 21, 2016</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="description-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis molestie massa, ut gravida felis rhoncus eget. Maecenas lectus libero, luctus at nisl vel, iaculis blandit erat. Donec eu tellus in tortor iaculis semper. Ut bibendum tortor convallis turpis pellentesque, eget rhoncus metus mollis.</span>
                </div>
            </div>

            {# Beging review #2 #}
            <div class="row medium-vertical-margins" style="margin-bottom: 10px;">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="title-text">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...</span>
                </div>
            </div>

            <div class="row extra-small-vertical-margins">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="subtitle-text">By: Ipsum - May 21, 2016</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <span class="description-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis molestie massa, ut gravida felis rhoncus eget. Maecenas lectus libero, luctus at nisl vel, iaculis blandit erat. Donec eu tellus in tortor iaculis semper. Ut bibendum tortor convallis turpis pellentesque, eget rhoncus metus mollis.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="light-section top-padding bottom-padding">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Industries We Serve</h1>
        </div>
    </div>

    {# Row of 4 images #}
    <div class="row" style="margin-top: 50px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/auto.png" alt="Auto" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/restaurants.png" alt="Restaurants" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/sales_and_marketing.png" alt="" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/professional.png" alt="Professional" /></div>
    </div>

    {# Row of 4 descriptions #}
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-2 text-center description-text">Auto Repair / Towing / New & Used Car Dealers / Auto Body </div>
        <div class="col-xs-2 text-center description-text">Restaurants / Cafe / Bars / Coffee Shops</div>
        <div class="col-xs-2 text-center description-text">Sales Services / Marketing Companies / Consulting Services</div>
        <div class="col-xs-2 text-center description-text">Professional Services / Attorneys / Schools / Accounting</div>
    </div>

    {# Row of 5 #}
    <div class="row" style="margin-top: 50px;">
        <div class="col-xs-1"></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/healthcare.png" alt="Healthcare" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/mortgage.png" alt="Mortgage" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/real_estate.png" alt="Real Estate" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/web.png" alt="Website Designers" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/seo.png" alt="SEO" /></div>
    </div>

    {# Row of 5 descriptions #}
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-2 text-center description-text">Healthcare Services / Cosmetic Dentists / Chiropractors / Medial Devices</div>
        <div class="col-xs-2 text-center description-text">Mortgage Companies / Mortgage Consultants / Debt Consolidation</div>
        <div class="col-xs-2 text-center description-text">Real Estate Agents / Brokerage Firms & Real Estate Teams</div>
        <div class="col-xs-2 text-center description-text">Website Designers / Software Developers / Photographers</div>
        <div class="col-xs-2 text-center description-text">Search Engine Optimization / Reputation Management</div>
    </div>

    {# Row of 4 #}
    <div class="row" style="margin-top: 50px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/insurance.png" alt="Insurance" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/apartment.png" alt="Apartment" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/roofing.png" alt="Roofing" /></div>
        <div class="col-xs-2"><img class="center-block" src="/img/agencysignup/sales/heating.png" alt="Heating" /></div>
    </div>

    {# Row of 4 descriptions #}
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-2 text-center description-text">Auto Insurance / Home Insurance / Life Insurance / Business Insurance Service / Health Insurance</div>
        <div class="col-xs-2 text-center description-text">Apartments & Housing Rental / Hotels & Motels</div>
        <div class="col-xs-2 text-center description-text">Roofing Contractor / Plumbers / General Contractors & Builders</div>
        <div class="col-xs-2 text-center description-text">Heating and Air / Air Conditioning / Solar Energy Equipment</div>
    </div>
</div>

<div class="medium-section top-padding">
    <div class="row">
        <div class="col-xs-12 text-center ">
            <h1> Generate 5 Star Reviews In The Next Few Minutes</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6 text-center extra-small-vertical-margins">
            <h3 class="description-text">With our automated account creation wizard and powerful mobile review funnel you'll have fresh online reviews coming in from your most recent customers in no time.</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
            <button class="big-green-button small-vertical-margins SecondaryColor" style="width: 100%; margin-left: 0px; height: 80px;">
                Get Started Today
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 small-vertical-margins" style="margin-bottom: 0px;">
            <img class="center-block" src="/img/agencysignup/sales/people_jobs.png" alt="Careers" />
        </div>
    </div>
</div>
