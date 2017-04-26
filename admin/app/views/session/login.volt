<div class="content">
    {{ content() }}

    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="/session/login?return={{ _GET['return'] is defined ? _GET['return'] : '' }}" method="post">
        <h3>{{ isEmailConfirmPage ? 'Thank You For Confirming Your Email Sign In' : 'Sign In' }}{% if subscription_not_valid %}==={% endif %}</h3>
        <p class="hint"> &nbsp; </p>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="{{ _POST['email'] is defined ? _POST["email"] : email }}" /> </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" value="{{ _POST['password'] is defined ? _POST["password"] : '' }}" /> </div>
        <div class="form-actions">
            <button type="submit" class="btnLink">Log in</button>
            <label class="rememberme check">
                <input type="checkbox" name="remember" value="1" {{ _POST['remember'] is defined and _POST["remember"] == "1" ? 'checked="checked"' : '' }} />Remember </label>
            <a href="/session/forgotPassword" id="forget-password" class="forget-password">Forgot Password?</a>
        </div>
        <!--
        <div class="create-account">
            <p>
                <a href="/session/signup" id="register-btn" class="uppercase">Create an account</a>
            </p>
        </div>-->

    </form>
    <!-- END LOGIN FORM -->

</div>
<script src="/js/login.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>
    var updateCard = function() {
        var handler = StripeCheckout.configure({
            key: '{{ _SESSION['stripe_publishable_key'] }}',
            email: '{{ _SESSION['email'] }}',
            allowRememberMe: false,
            /* GARY_TODO: Replace with agency logo */
            /*image: '/img/documentation/checkout/marketplace.png',*/
            locale: 'auto',
            token: function(token) {
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                $.post('/paymentProfile/index', {
                    tokenID: token.id,
                    email: token.email
                })
                .done(function (data) {
                    if (data.status !== true) {
                        alert("Update card failed!!!");
                    } else {
                        alert("Card updated! Try logging in again.");
                    }

                    window.location.reload();
                })
                .fail(function () {})
                .always(function () {});
            }
        });

        // Open Checkout with further options:
        handler.open({
            name: 'Update Payment Info',
            description: ''
        });

        // Close Checkout on page navigation:
        $(window).on('popstate', function() {
            handler.close();
        });
    };

    jQuery(document).ready(function ($) {
        {% if _SESSION['subscription_not_valid'] is defined %}
        updateCard();
        {% endif %}
    });
</script>