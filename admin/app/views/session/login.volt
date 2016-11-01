<div class="content">
    {{ content() }}

    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="/session/login?return=<?=(isset($_GET['return'])?$_GET['return']:'')?>" method="post">
        <h3><?=(strpos($_SERVER['REQUEST_URI'],'admin/confirm/')>0?'Thank You For Confirming Your Email Sign In':'Sign In')?></h3>
        <p class="hint"> &nbsp; </p>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> Enter your email and password. </span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]: $email )?>" /> </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" /> </div>
        <div class="form-actions">
            <button type="submit" class="btnLink">Log in</button>
            <label class="rememberme check">
                <input type="checkbox" name="remember" value="1" <?=(isset($_POST['remember']) && $_POST["remember"]=="1"?'checked="checked"':'')?> />Remember </label>
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