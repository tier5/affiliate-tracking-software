{% if !userId %}
<div class="signup-footer">
    <div class="title">Try us for free</div>
    <div class="description">No credit card required.  All features included.</div>
   <!-- {% if agency_name %} {{ agency_name }} {% endif %}-->
</div>
{% endif %}
<div class="content">




    {{ content() }}
    {% if maxLimitReached %}
    The max signup limit has been reached for today.  Please try again tomorrow.
    <p><a href="/session/login" id="register-back-btn" class="btn btn-default" style="margin-right: 50px;">Back</a></p>
    {% else %}

    <div id="returnmessage"></div>

    <!-- BEGIN REGISTRATION FORM -->
    <form class="register-form" name="registration" id="register-form" action="/session/submitSignup/{{ subscription_id ? subscription_id : token}}" method="post" style="display: block;">
        {% if subscription_id %}
            <input type="hidden" name="subscription_id" value="{{ subscription_id }}"/>
        {% endif %}

        {% if short_code %}
            <input type="hidden" name="short_code" value="{{ short_code }}"/>
        {% endif %}

        <?php if($_GET['code']) { ?>
                <input type="hidden" name="sharing_code" value="<?=$_GET['code']; ?>" />
        <?php } ?>


        <h3>Account Details</h3>
        <p class="hint"> Enter your account details below: </p>
        <div class="form-group">
            <label class="control-label">Full Name:</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" id="name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" required />
        </div>
        <div class="form-group">
            <label class="control-label">Email:</label><span id="Email_availability_result" style="margin-left: 10px" ></span>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" id="email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]:'')?>" required />
        	<!--<div id='email_availability_result' ></div>-->
        </div>
        <div class="form-group">
            <label class="control-label">Password:</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" required />
        </div>
        <div class="form-group">
            <label class="control-label">Re-type Your Password:</label><span id="Confirm_password_result" style="margin-left: 10px;"></span>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" id="confirmPassword" name="confirmPassword" value="<?=(isset($_POST['confirmPassword'])?$_POST["confirmPassword"]:'')?>" required />
        </div>

        <!-- <div class="card-js"></div> -->

        <div class="form-actions">
            <button type="button" id="register-submit-btn" class="btnsignup uppercase">CREATE MY ACCOUNT</button>
            <div class="signup-footer">By clicking this button, you agree to {{ agency_name }}'s
                <a href="/session/antispam">Anti-spam Policy</a>, <a href="/session/privacy">Privacy Policy</a> &amp; <a href="/session/terms">Terms of Use</a>.
            </div>
        </div>
        <div style="clear: both;">&nbsp;</div>
    </form>
    <!-- END REGISTRATION FORM -->

    {% endif %}
</div>
<script src="/js/signup.js"></script>

