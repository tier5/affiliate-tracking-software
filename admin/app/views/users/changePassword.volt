{{ content() }}

<!-- BEGIN Change Password FORM -->
<form class="login-form" action="/users/changePassword" method="post" autocomplete="off">
    <h3 class="form-title font-green">Change Password</h3>
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span> Enter your new password. </span>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" /> </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="confirmPassword" value="<?=(isset($_POST['confirmPassword'])?$_POST["confirmPassword"]:'')?>" /> </div>

    <div class="form-actions">
        <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Change Password</button>
    </div>
</form>
<!-- END Change Password FORM -->