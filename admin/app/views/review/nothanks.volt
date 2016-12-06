<!-- views/review/recommend.volt -->
{{ content() }}
<div class="review nothanks">
  <div class="rounded-wrapper">
    <div class="rounded">
      <?php
  if (isset($logo_path) && $logo_path != '') {
    ?>
      <div class="page-logo">
        <img src="<?=$logo_path?>" alt="logo" class="logo-default" /> </a>
      </div>
      <?php
  } else if (isset($name) && $name != '') {
    ?>
      <div class="page-logo">
        <?=$name?>
      </div>
      <?php
  }
  ?>
  
      <?php
if ($this->request->isPost()) {
      //the user just posted feedback, so say thanks
      ?>
      <div class="question">Thank you, for your feedback.</div>
      <?php
} else {
  $name = '';
  //verify that we found a review invite
  if ($invite) {
    //we have an invite, so find what type of question we should ask
    if (isset($name) && $name != '') {
      $name = $invite->name;
      }
      }
      ?>

      <div class="question"><?=($name != ''?$name.', we':'We')?> are sorry that you had a poor experience</div>
      <div class="question2">How can we improve?</div>
      <div class="row text-center last">
        <form action="/review/nothanks?r=<?=(isset($_GET["r"])?htmlspecialchars($_GET["r"]):'')?>&a=<?=htmlspecialchars($_GET["a"])?>" class="form-horizontal" role="form" method="post">
        <textarea name="comments" placeholder="Comments (please be as specific as possible)"></textarea>
        <div class="row text-center"><input type="submit" class="btn-lg btn-recommend" value="Submit" /></div>
        </form>
      </div>
      <?php
}
?>
    </div>
    <div class="subtext text-center">We will never share your personal information!</div>
  </div>
  <div class="footer">Powered by:
  418                                                                          <?php if($objAgency) { ?>
  419                                                                          <a href="<?=$objAgency->website; ?>" style="Margin:0;color:#2199e8;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none"><?=$objAgency->name; ?></a><?php }?></div>
</div>