
<div class="content">
  {{ content() }}

  <?php
if ($maxlimitreached) {
  ?>
  The max signup limit has been reached for today.  Please try again tomorrow.

  <p><a href="/session/login" id="register-back-btn" class="btn btn-default" style="margin-right: 50px;">Back</a></p>
  <?php
} else {
?>


  <?php
  if (isset($subscription) && isset($agency->stripe_publishable_keys) && $agency->stripe_publishable_keys != '') {
  ?>

  <!-- BEGIN REGISTRATION FORM -->
  <form class="register-form" action="/session/subscribe/<?=(isset($subscription->subscription_stripe_id)?$subscription->subscription_stripe_id:'')?>" method="post" style="display: block;">
    <h3 class="font-green">Sign Up</h3>
    <div>
      <?=$subscription->description?>
    </div>
    <p class="hint"> Enter your personal details below: </p>
    <div class="form-group">
      <label class="control-label visible-ie8 visible-ie9">Full Name</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" />
    </div>

    <p class="hint"> Enter your account details below: </p>
    <div class="form-group">
      <label class="control-label visible-ie8 visible-ie9">Email</label>
      <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]:'')?>" required />
    </div>
    <div class="form-group">
      <label class="control-label visible-ie8 visible-ie9">Password</label>
      <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" required />
    </div>
    <div class="form-group">
      <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
      <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="confirmPassword" value="<?=(isset($_POST['confirmPassword'])?$_POST["confirmPassword"]:'')?>" required />
    </div>
    <div class="form-group">
      <label class="control-label visible-ie8 visible-ie9">Agency Name</label>
      <input class="form-control placeholder-no-fix" type="name" autocomplete="off" placeholder="Agency Name" name="agency_name" value="<?=(isset($_POST['agency_name'])?$_POST["agency_name"]:'')?>" required />
    </div>

    <div class="form-actions">
      <a href="/session/login" id="register-back-btn" class="btn btn-default" style="margin-right: 50px;">Back</a>
      <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
              data-key="<?=$agency->stripe_publishable_keys?>"
              data-description="<?=str_replace('"', '', $subscription->plan)?>"
      data-amount="<?=str_replace('.', '', str_replace(',', '', $subscription->amount))?>">
      </script>
      </div>

      </form>
        <!-- END REGISTRATION FORM -->
      <script type="text/javascript">

      </script>

      <?php
  } else {
    ?>
      Not configured.
      <?php
  }
  ?>
      <?php
} // end checking max limit reached
?>
    </div>