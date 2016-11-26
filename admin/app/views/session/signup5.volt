



<!-- BEGIN FORM -->
<form class="register-form5" action="/session/signup5/<?=(isset($subscription->subscription_id)?$subscription->subscription_id:'')?><?=(isset($_GET['code'])?'?code='.$_GET['code']:'')?>" method="post" style="display: block;">
  <h3>Invite friends</h3>

  {{ content() }}

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="portlet light bordered">
        <div class="portlet-body" id="reportwrapper">
          <div class="">
            <div class="row">
              <div class="col-md-12">
                <div class="share-text"><span class="invite-intro">Receive an additional <?=$additional_allowed?> SMS messages for every referral that signs up.</span>  Use the following links to share your referral URL: </div>
                <a target="_blank" class="share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?=$share_link?>">Share on Facebook <img src="/img/icon_sm_facebook.gif" /></a>
                <a target="_blank" class="share-link" href="https://twitter.com/home?status=<?=$share_message?>">Share on Twitter  <img src="/img/icon_sm_twitter.gif" /></a>
                <a target="_blank" class="share-link" href="https://plus.google.com/share?url=<?=$share_link?>">Share on Google+  <img src="/img/icon_sm_google.gif" /></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row" style="margin-bottom: 22px; margin-left: 17px; margin-right: 17px;">
    Enter the email address of your friends that would benefit from trying Get Mobile Reviews for free.  For each new signup you'll automatically receive <?=$additional_allowed?> additional text messages in your account.
  </div>

  <div class="row" id="shareform">
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_1" value="<?=(isset($_POST['email_1'])?$_POST["email_1"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_2" value="<?=(isset($_POST['email_2'])?$_POST["email_2"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_3" value="<?=(isset($_POST['email_3'])?$_POST["email_3"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_4" value="<?=(isset($_POST['email_4'])?$_POST["email_4"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_5" value="<?=(isset($_POST['email_5'])?$_POST["email_5"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_6" value="<?=(isset($_POST['email_6'])?$_POST["email_6"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_7" value="<?=(isset($_POST['email_7'])?$_POST["email_7"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_8" value="<?=(isset($_POST['email_8'])?$_POST["email_8"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_9" value="<?=(isset($_POST['email_9'])?$_POST["email_9"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_10" value="<?=(isset($_POST['email_10'])?$_POST["email_10"]:'')?>" />
    </div>
    <div class="form-group">
      <label class="control-label">I'd like to invite:</label>
      <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email_11" value="<?=(isset($_POST['email_11'])?$_POST["email_11"]:'')?>" />
    </div>
  </div>
  <div class="row">
    <button type="submit id="createlink" class="btnLink" style="float: right;">Send</a>
  </div>
  <div class="form-actions">
    <a href="/session/signup5/?q=s" id="signup5submitbtn" class="btnsignup uppercase">Finished: Take Me To My Dashboard</a>
  </div>
  <div style="clear: both;">&nbsp;</div>
</form>
<!-- END FORM -->
<script type="text/javascript">

</script>
