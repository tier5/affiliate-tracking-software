<!-- views/review/recommend.volt -->
{{ content() }}
<div class="review recommend">
<div class="rounded-wrapper">
<div class="rounded" style="padding-bottom: 25px;">
  <?php 
  if (isset($logo_setting) && $logo_setting != '') {
    ?>
    <div class="page-logo">
      <img src="<?=$logo_setting?>" alt="logo" class="logo-default" /> </a>
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
  <div class="question">Choose Your Favorite App And Review Us!</div>
  
  <?php 
  foreach($review_site_list as $rsl) { 
    if ($rsl->review_site_id == 1) {
      ?>
      <div class="row text-center" id="facebooklink"><a href="http://facebook.com/<?=$rsl->external_id?>" onclick="facebookClickHandler('<?=$location->facebook_page_id?>');" class="btn-lg btn-review"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php
    } else if ($rsl->review_site_id == 2) {
      ?>
      <?php if (!(strpos($rsl->external_id, '>') !== false)) { ?>
        <div class="row text-center" id="yelplink"><a href="http://www.yelp.com/writeareview/biz/<?=$rsl->external_id?>" onclick="yelpClickHandler('<?=$rsl->external_id?>');" class="btn-lg btn-review"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php } ?>
      <?php
    } else if ($rsl->review_site_id == 3) {
      ?>
      <div class="row text-center" id="googlelink"><a href="https://www.google.com/search?q=<?=urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country)?>&ludocid=<?=$rsl->external_id?>#" onclick="googleClickHandler('<?=$rsl->external_id?>', '<?=urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country)?>');" class="btn-lg btn-review"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>  
      <?php
    } else {
      ?>
      <div class="row text-center"><a href="<?=$rsl->url?>" class="btn-lg btn-review"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>  
      <?php
    }
  }
  ?>

</div>
  <div class="subtext text-center">App Will Automatically Launch</div>
</div>
</div>