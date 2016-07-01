{% if !userId %}
<div class="signup-footer">
    <div class="title">Try us for free</div>
    <div class="description">No credit card required.  All features included.</div>
</div>
{% endif %}
<div class="content">


    {{ content() }}
    {% if maxLimitReached %}
    The max signup limit has been reached for today.  Please try again tomorrow.
    <p><a href="/session/login" id="register-back-btn" class="btn btn-default" style="margin-right: 50px;">Back</a></p>
    {% else %}

    <!-- BEGIN REGISTRATION FORM -->
    <form class="register-form" action="/session/submitSignup/{{token}}" method="post" style="display: block;">
        <h3>Account Details</h3>
        <p class="hint"> Enter your account details below: </p>
        <div class="form-group">
            <label class="control-label">Full Name:</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Email:</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Password:</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Re-type Your Password:</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="confirmPassword" value="<?=(isset($_POST['confirmPassword'])?$_POST["confirmPassword"]:'')?>" />
        </div>
        
        <!-- <div class="card-js"></div> -->
        
        <div class="form-actions">
            <button type="submit" id="register-submit-btn" class="btnsignup uppercase">CREATE MY ACCOUNT</button>
            <div class="signup-footer">By clicking this button, you agree to Review Velocity's
                <a href="#">Anti-span Policy</a> &amp; <a href="#">Terms of Use</a>.
            </div>
        </div>
        <div style="clear: both;">&nbsp;</div>
    </form>
    <!-- END REGISTRATION FORM -->
    <script type="text/javascript"></script>
    {% endif %}
</div>
