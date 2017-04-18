<!-- views/review/recommend.volt -->
{{ content() }}

<div class="review recommend">
  <div class="rounded-wrapper">
    <div class="rounded" style="padding-bottom: 25px;">
  {% if logo_path is defined and logo_path != '' %}
      <div class="page-logo">
        <img src="<?=$logo_path?>" alt="logo" class="logo-default" /> </a>
      </div>
  {% elseif name is defined and name != '' %}
      <div class="page-logo">
        {{ name }}
      </div>
  {% endif %}
      <div class="question">Choose Your Favorite App And Review Us!</div>

      {% if review_site_list and review_site_list|length > 0 %}
          {% for rsl in review_site_list %}
            {% if rsl.review_site_id == facebook_type_id %}

                {% if rsl.url is defined and rsl.url !== '' %}
                  {% set FacebookLink = rsl.url %}
                <div class="row text-center" id="facebooklink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="{{ FacebookLink }}" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
                {% else %}
                  {% set FacebookLink = "fb://profile/" ~ rsl.external_id %}
                <div class="row text-center" id="facebooklink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="{{ FacebookLink }}" onclick="facebookClickHandler('{{ rsl.external_id }}');" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
                {% endif %}

            {% elseif rsl.review_site_id == yelp_type_id %}

                {% if rsl.url !== null and rsl.url !== '' %}

                <div class="row text-center" id="yelplink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="{{ rsl.url }}" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
                
                {% elseif !(strpos(rsl.external_id, '>') !== false) %}
                
                <div class="row text-center" id="yelplink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="http://www.yelp.com/writeareview/biz/{{ rsl.external_id }}" onclick="yelpClickHandler('{{ rsl.external_id }}');" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>

                {% endif %}
              
            {% elseif rsl.review_site_id == google_type_id %}
                {% if rsl.url !== null and rsl.url !== '' %}
                    {% set googleLink = rsl.url %}
                {% else %}
                    {% set googleLinkEncode = location.name
                    ~ ', ' ~ location.address
                    ~ ', ' ~ location.locality
                    ~ ', ' ~ location.state_province
                    ~ ', ' ~ location.postal_code
                    ~ ', ' ~ location.country|url_encode %}
                    {% set googleLink = 'https://www.google.com/search?q='
                    ~ '&' ~ 'ludocid=' 
                    ~ googleLinkEncode
                    ~ rsl.external_id ~ '#lrd='
                    ~ rsl.lrd ~ ',3,5' %}
                {% endif %}
              <div class="row text-center" id="googlelink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="{{ googleLink }}" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
            {% elseif rsl.review_site_id == other_type_id %}
              <div class="row text-center" id="googlelink"><a data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" href="{{ googleLink }}" class="btn-lg btn-review track-link"><img src="{{ rsl.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
            {% else %}
              <div class="row text-center"><a href="{{ rsl.url }}" data-id="{{ rsl.review_site_id }}" data-invite="{{ invite.review_invite_id }}" class="btn-lg btn-review track-link"><img src="{{ rsl.review_site.logo_path }}" alt="{{ rsl.review_site.name }}" /></a></div>
            {% endif %}
          {% endfor %}
        {% endif %}
    </div>
    <div class="subtext text-center">App Will Automatically Launch</div>
  </div>
  <br>
<br>

  {% if parent_agency.name %}
  <div class="footer">Powered by:
  <a href="{{ parent_agency.website }}" style="Margin:0;color: #333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">{{ parent_agency.name }}</a></div>
  {% endif %}
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
           // alert(html);  
             
             }
           });

    });
  });
</script>