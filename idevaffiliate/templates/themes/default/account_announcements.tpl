{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{literal}
<script type="text/javascript">
window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
</script>

<script>
    
	window.fbAsyncInit = function() {
    	FB.init({
        	appId      : '{/literal}{$facebook_app_id}{literal}',
          	xfbml      : true,
          	version    : 'v2.7'
		});
	};

	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
        
	
	function share_on_facebook(message, link, source) {
		// check login status
		FB.login(
			function(response) {
				if (response.authResponse) {
					post_to_wall(message, link, source);
				} 
				else {
					alert(response.error.message);
				}
			},
			{
				scope: 'public_profile,publish_actions',
				auth_type: 'rerequest'
			}
		);
	}
	
	function post_to_wall(message, link, source) {
            /*
            //share
            FB.ui({
                method: 'share_open_graph',
                action_type: 'og.likes',
                action_properties: JSON.stringify({
                    object: link,
                    //image: source,
                    //href: link,
                    description: message,
                })
              }, function(response){
                    if (response && !response.error_code) {
                        console.log(response);
                        alert("{/literal}{$announcements_published}{literal}");
                    }
                    else {
                        alert(response.error_message);
                    } 
              });    
            */  
            
            //post on wall-- working but no image after post to wall
            FB.ui({
                method: 'feed',
                link: link,
                picture: source,
                //picture: 'http://www.ecdevelopment.org/wp-content/uploads/2015/04/hippie-flower.jpg',
                //caption: message,
                description: message,
                
            }, function(response){
                console.log(response);
                if (response && !response.error) {
                        alert("{/literal}{$announcements_published}{literal}");
                }
                else {
                        alert(response.error.message);
                }
            });
            
            /*
            //old code
            
            FB.api(
                    "/me/feed",
                    "POST",
                    {
                            "message": message,
                            "link": link,
                            "picture": source
                    },
                    function (response) {
                            if (response && !response.error) {
                                    alert("{/literal}{$announcements_published}{literal}");
                            }
                            else {
                                    alert(response.error.message);
                            }
                    }	
            );
            */    
	}
</script>
{/literal}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$menu_announcements}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($one_click_delivery)}

{section name=nr loop=$announcement_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$announcement_link_results[nr].group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$announcements_name}:</label> {$announcement_link_results[nr].announcement_name}</li>
<li class="list-group-item"><label>{$announcements_facebook_message}:</label><br/>{$announcement_link_results[nr].facebook_message}</li>
<li class="list-group-item"><label>{$announcements_facebook_link}:</label> {$announcement_link_results[nr].facebook_link}</li>
<li class="list-group-item"><label>{$announcements_facebook_picture}:</label><br/>{$announcement_link_results[nr].facebook_picture_img}</li>
<li class="list-group-item"><label>{$announcements_twitter_message}:</label><br/>{$announcement_link_results[nr].twitter_message}</li>
<li class="list-group-item"> 
<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text={$announcement_link_results[nr].encoded_twitter_message}&url=#">Tweet</a> <a href="#" style="margin-left: 5px; vertical-align: top; bottom: 15px; position: absolute;" onclick="share_on_facebook('{$announcement_link_results[nr].facebook_message|escape:'htmlall'}', '{$announcement_link_results[nr].facebook_link}', '{$announcement_link_results[nr].facebook_picture}'); return false;"><img src="{$base_url}/images/fb_share_button.png" /></a>
</li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_3};">
<div class="portlet-heading" style="background:{$portlet_3};"><div class="portlet-title" style="color:{$portlet_3_text};"><h4>{$choose_marketing_group}</h4></div></div>
<div class="portlet-body">

<form class="form-horizontal" role="form" method="post" action="account.php">
<input type="hidden" name="page" value="45">
<div class="form-group">
<label class="col-sm-3 control-label">{$marketing_group_title}</label>
<div class="col-sm-6">
<select name="announcements_picked" class="form-control">
{section name=nr loop=$announcement_results}
<option value="{$announcement_results[nr].group_id}">{$announcement_results[nr].group_name}</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-primary">{$marketing_button} {$menu_announcements}</button>
</div>
</div>
</form>

</div>
</div>
</div>
</div>

{if isset($announcement_group_chosen)}

{section name=nr loop=$announcement_link_results}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_4};">
<div class="portlet-heading" style="background:{$portlet_4};"><div class="portlet-title" style="color:{$portlet_4_text};"><h4>{$marketing_group_title}: {$announcement_link_results[nr].group_name}</h4></div></div>
<div class="portlet-body">

<ul class="list-group">
<li class="list-group-item"><label>{$announcements_name}:</label> {$announcement_link_results[nr].announcement_name}</li>
<li class="list-group-item"><label>{$announcements_facebook_message}:</label><br/>{$announcement_link_results[nr].facebook_message}</li>
<li class="list-group-item"><label>{$announcements_facebook_link}:</label> {$announcement_link_results[nr].facebook_link}</li>
<li class="list-group-item"><label>{$announcements_facebook_picture}:</label><br/>{$announcement_link_results[nr].facebook_picture_img}</li>
<li class="list-group-item"><label>{$announcements_twitter_message}:</label><br/>{$announcement_link_results[nr].twitter_message}</li>
<li class="list-group-item">
<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text={$announcement_link_results[nr].encoded_twitter_message}&url=#">Tweet</a> <a href="#" style="margin-left: 5px; vertical-align: top; bottom: 15px; position: absolute;" onclick="share_on_facebook('{$announcement_link_results[nr].facebook_message|escape:'htmlall'}', '{$announcement_link_results[nr].facebook_link}', '{$announcement_link_results[nr].facebook_picture}'); return false;"><img src="{$base_url}/images/fb_share_button.png" /></a>
</li>
</ul>

</div>
</div>
</div>
</div>

{/section}

{else}
{* turn this text on if you want *}
{* <legend style="color:{$legend};">{$marketing_no_group}</legend> *}
{* <p><b>{$marketing_choose}</b><BR /><BR />{$marketing_notice}</p> *}
{/if}
{/if}

</div>
</div>
</div>
</div>

