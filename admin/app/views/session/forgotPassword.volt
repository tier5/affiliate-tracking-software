
<div class="content">
{{ content() }}

<!-- BEGIN FORGOT PASSWORD FORM -->
<form class="forget-form" action="/session/forgotPassword" method="post" style="display: block;">
    <h3>Forgot Password</h3>
    <p> Enter your e-mail address below to reset your password. </p>
    <div class="form-group">
        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]:'')?>" /> </div>
    <div class="form-actions">
        <a id="register-back-btn" class="btn btn-default" href="/session/login">Back</a>
        <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
    </div>
</form>
<!-- END FORGOT PASSWORD FORM -->

</div>