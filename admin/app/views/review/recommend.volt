<!-- views/review/recommend.volt -->
{{ content() }}
<?php
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<div class="review recommend">
  <div class="rounded-wrapper">
    <div class="rounded" style="padding-bottom: 25px;">
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
      <div class="question">Choose Your Favorite App And Review Us!</div>

      <?php
  foreach($review_site_list as $rsl) {
    if ($rsl->review_site_id == \Vokuro\Models\Location::TYPE_FACEBOOK) {
        $FacebookLink = isMobile() ? "fb://profile/{$rsl->external_id}" : "http://facebook.com/{$rsl->external_id}/reviews";
      ?>
      <div class="row text-center" id="facebooklink"><a data-id="<?=$rsl->review_site_id?>" data-invite="<?=$invite->review_invite_id?>" href="<?=$FacebookLink; ?>" onclick="facebookClickHandler('<?=$rsl->external_id?>');" class="btn-lg btn-review track-link"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php
    } else if ($rsl->review_site_id == \Vokuro\Models\Location::TYPE_YELP) {
      ?>
      <?php if (!(strpos($rsl->external_id, '>') !== false)) { ?>
      <div class="row text-center" id="yelplink"><a data-id="<?=$rsl->review_site_id?>" data-invite="<?=$invite->review_invite_id?>" href="http://www.yelp.com/writeareview/biz/<?=$rsl->external_id?>" onclick="yelpClickHandler('<?=$rsl->external_id?>');" class="btn-lg btn-review track-link"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php } ?>
      <?php
    } else if ($rsl->review_site_id == \Vokuro\Models\Location::TYPE_GOOGLE) {
      ?>
      <div class="row text-center" id="googlelink"><a data-id="<?=$rsl->review_site_id?>" data-invite="<?=$invite->review_invite_id?>" href="https://www.google.com/search?q=<?=urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country)?>&ludocid=<?=$rsl->external_id?>#lrd=<?=$rsl->lrd?>,3,5" onclick="googleClickHandler('<?=$rsl->external_id?>', '<?=urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country)?>');" class="btn-lg btn-review track-link"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php
    } else {
      ?>
      <div class="row text-center"><a href="<?=$rsl->url?>" data-id="<?=$rsl->review_site_id?>" data-invite="<?=$invite->review_invite_id?>" class="btn-lg btn-review track-link"><img src="<?=$rsl->review_site->logo_path?>" alt="<?=$rsl->review_site->name?>" /></a></div>
      <?php
    }
  }
  ?>

    </div>
    <div class="subtext text-center">App Will Automatically Launch</div>
  </div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function($){
    $('.track-link').click(function(e) {
   
    //alert('ok');return false;
      //e.preventDefault();
      /*$.ajax({
        async: false,
        method: 'POST',
        url: '/review/track?d='+$(this).data("id")+'&i='+$(this).data("invite"),
        
      });*/

         $.ajax({
          url: '/review/track',
          method: "POST",
          async:false,
          data: { d : $(this).data("id"), i:$(this).data("invite")},
          success:function(html)
              {
            //alert(html);  
             
             }
           });

    });
  });
</script>