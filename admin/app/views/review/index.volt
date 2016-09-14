<!-- views/review/index.volt -->
{{ content() }}
<div class="review index">
    <?php
//verify that we found a review invite
if (isset($invite)) {
  //we have an invite, so find what type of question we should ask
  $question_type = 1;
  if ($location && $location->review_invite_type_id > 0) {
    $question_type = $location->review_invite_type_id;
    }

    if ($question_type == 3) {
    //question type is 3 = NPS Rating
    ?>
    <div class="rounded-wrapper NPS">
        <div class="rounded">
            <?php
      if (isset($logo_setting) && $logo_setting != '') {
        ?>
            <div class="page-logo">
                <img src="<?=$logo_setting?>" alt="logo" class="logo-default"/> </a>
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
            <div class="question">How Likely Are You To Recommend Us To A Friend?</div>
            <div class="row text-center">
                <a href="/review/<?=(10 < $threshold?'nothanks':'recommend')?>?r=10&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">10 - Very Likely</a></div>
            <div class="row text-center">
                <a href="/review/<?=(9 < $threshold?'nothanks':'recommend')?>?r=9&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">9</a></div>
            <div class="row text-center">
                <a href="/review/<?=(8 < $threshold?'nothanks':'recommend')?>?r=8&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">8</a></div>
            <div class="row text-center">
                <a href="/review/<?=(7 < $threshold?'nothanks':'recommend')?>?r=7&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">7</a></div>
            <div class="row text-center">
                <a href="/review/<?=(6 < $threshold?'nothanks':'recommend')?>?r=6&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">6</a></div>
            <div class="row text-center">
                <a href="/review/<?=(5 < $threshold?'nothanks':'recommend')?>?r=5&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">5</a></div>
            <div class="row text-center">
                <a href="/review/<?=(4 < $threshold?'nothanks':'recommend')?>?r=4&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">4</a></div>
            <div class="row text-center">
                <a href="/review/<?=(3 < $threshold?'nothanks':'recommend')?>?r=3&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">3</a></div>
            <div class="row text-center">
                <a href="/review/<?=(2 < $threshold?'nothanks':'recommend')?>?r=2&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">2</a></div>
            <div class="row text-center">
                <a href="/review/<?=(1 < $threshold?'nothanks':'recommend')?>?r=1&a=<?=htmlspecialchars($_GET[" a"])?>"
                class="btn-lg btn-recommend">1 - Least Likely</a></div>
        </div>
        <div class="subtext text-center">Next Step, Write A Review</div>
    </div>
    <?php
  } elseif ($question_type == 2) {
    //question type is 2 = Star Rating
    ?>
    <div class="rounded-wrapper star">
        <div class="rounded">
            <?php
      if ($logo_setting != '') {
        ?>
            <div class="page-logo">
                <img src="{{ logo_setting }}" alt="logo" class="logo-default"/> </a>
            </div>
            <?php
      } else if ($name != '') {
        ?>
            <div class="page-logo">
                {{ name }}
            </div>
            <?php
      }
      ?>
            <div class="question">Please Rate Us?</div>
            <form action="/review/recommend" class="form-horizontal" role="form" method="get">
                <div class="row text-center">
                    <input id="input-2c" name="r" class="rating" min="0" max="5" step="0.5" data-size="xl" data-symbol="&#xf005;" data-glyphicon="false" data-rating-class="rating-fa"/>
                </div>
                <div class="row text-center last"><input type="submit" class="btn-lg btn-recommend" value="Submit"/>
                </div>
                <input type="hidden" name="a" value="<?=htmlspecialchars($_GET[" a"])?>" />
            </form>
        </div>
        <div class="subtext text-center">Next Step, Write A Review</div>
    </div>
    <?php
  } else {
    //question type is 1 = Question
    ?>
    <div class="rounded-wrapper">
        <div class="rounded">
            <?php
      if ($logo_setting != '') {
        ?>
            <div class="page-logo">
                <img src="{{ logo_setting }}" alt="logo" class="logo-default"/> </a>
            </div>
            <?php
      } else if ($name != '') {
        ?>
            <div class="page-logo">
                {{ name }}
            </div>
            <?php
      }
      ?>
            <div class="question">Would You Recommend Us?</div>
            <div class="row text-center"><a href="/review/recommend?a=<?=htmlspecialchars($_GET["a"])?>&r=5"
                class="btn-lg btn-recommend">Yes</a></div>
            <div class="row text-center last"><a href="/review/nothanks?a=<?=htmlspecialchars($_GET["a"])?>&r=1"
                class="btn-lg btn-nothanks">No Thanks</a></div>
        </div>
        <div class="subtext text-center">Next Step, Write A Review</div>
    </div>
    <?php
  }
} else {
  //no review invite was found
  ?>
    <div class="rounded-wrapper">
        <div class="rounded">
            <?php
      if (isset($logo_setting) && $logo_setting != '') {
        ?>
            <div class="page-logo">
                <img src="<?=$logo_setting?>" alt="logo" class="logo-default"/> </a>
            </div>
            <?php
      }
      ?>
            <div class="question">No review invite found.</div>
            <div class="row text-center">&nbsp;</div>
        </div>
    </div>
    <?php
} //end checking for a review invite
?>
</div>
