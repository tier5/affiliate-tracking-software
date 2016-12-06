<div class="login">
    <div class="pull-right">
        <a href="/businessPricingPlan" id="cancel-btn" class="btn default btn-lg apple-backgound subscription-btn">Cancel</a>
    </div>
    <div class="signup-footer">
        <div class="title">Try us for free</div>
        <div class="description">No credit card required.  All features included.</div>
    </div>
    <div class="content">
        <!-- BEGIN REGISTRATION FORM -->
        <form class="register-form" action="" method="post" style="display: block;">
            <h3>Account Details</h3>
            <p class="hint"> Enter your account details below: </p>
            <div class="form-group">
                <label class="control-label">Full Name:</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="name" value="" required />
            </div>
            <div class="form-group">
                <label class="control-label">Email:</label>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="" required />
            </div>
            <div class="form-group">
                <label class="control-label">Password:</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" value="" required/>
            </div>
            <div class="form-group">
                <label class="control-label">Re-type Your Password:</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="confirmPassword" value="" required />
            </div>

            <div class="card-js">
                <input class="card-number" id="card-number" name="card-number" value="" maxlength="19" />
                <input class="expiry-month" id="expiry-month" name="expiry-month" value="" />
                <input class="expiry-year" id="expiry-year" name="expiry-year" value="" />
                <input class="cvc" id="cvc" name="cvc" value="" />
            </div>
            <input type="hidden" id="expirationval-m" name="expirationval" value="" />
            <input type="hidden" id="expirationval-y" name="expirationval" value="" />
            <input type="hidden" name="sharecode" value="" />
            <div class="form-actions">
                <button type="submit" id="register-submit-btn" class="btnsignup uppercase">CREATE MY ACCOUNT</button>
                <div class="signup-footer">By clicking this button, you agree to Get Mobile Reviews's
                    <a href="#">Anti-span Policy</a> &amp; <a href="#">Terms of Use</a>.</div>
            </div>
            <div style="clear: both;">&nbsp;</div>
        </form>
        <!-- END REGISTRATION FORM -->
        <script type="text/javascript"></script>
    </div>
</div>
