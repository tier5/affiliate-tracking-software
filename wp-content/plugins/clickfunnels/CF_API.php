<?php
class CF_API {
    public function __construct() {
        $this->data['email'] = CF_API_EMAIL;
        $this->data['auth_token'] = CF_API_AUTH_TOKEN;
        $this->url = CF_API_URL;
    }
    public function get_funnels() {
        $url = $this->url.'funnels.json?';
        $data = $this->data;
        $query = http_build_query( $data );
        if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
        } else {
            $new_url = $url.$query;
            $response = wp_remote_get( $new_url );
            if( is_array($response) ) {
              $content = $response['body'];
            }
            $funnels = json_decode( $content );

            if ($funnels == '') {
              $response = wp_remote_get( $new_url );
              if( is_array($response) ) {
                $content = $response['body'];
              }
              $funnels = json_decode( $content );
            }
        }
        return $funnels;
    }
// Regular HTML Version
public function get_page_html( $funnel_id, $pagekey = 0, $meta = "", $title = "", $desc = "", $social = "", $cf_iframe_url = "", $pageid = "",$slug = "",$favicon = "https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png", $author = "", $keywords = "", $tracking = "", $favicon_choice = ""  ) {
    global $wp_query;
    if ($wp_query->is_404) {
        $wp_query->is_404 = false;
    }
    header("HTTP/1.1 200 OK");

    if ($slug == '') {
        $slug = $cf_iframe_url;
    }

    if ($favicon_choice == 'default') {
        $changed = false;
        
        if($site_icon = get_option('site_icon')):
            $favicon = wp_get_attachment_image_src($site_icon, 'full');
            $favicon = $favicon[0];
            $changed = true;
        else:
            $home_page_full_html_code = @file_get_contents(home_url());
            if($home_page_full_html_code !== false):
                preg_match_all('!<link(.*?)rel="(.*?)icon(.*?)"(.*?)href="(.*?)"(.*?)>!si', $home_page_full_html_code, $res);
                foreach($res as $row):
                    foreach($row as $item):
                        $img_res = @exif_imagetype($item);
                        if($img_res > 0 and $img_res < 18): // This is image
                            $favicon = $item;
                            $changed = true;
                            break;
                        endif;
                    endforeach;
                endforeach;
            endif;
        endif;
        
        if($changed):
            $favicon_url = '<link href="'.$favicon.'" rel="shortcut icon" type="image/x-icon" />';
            $favicon_js = '$("link[rel=\'icon\']").attr(\'href\',\''.$favicon.'\');';
        else:
            $favicon_url = '';
            $favicon_js = '$("link[rel=\'icon\']").remove();';
        endif;
    }  else {
        $favicon_url = '<link href="'.$favicon.'" rel="shortcut icon" type="image/x-icon" />';
        $favicon_js = '$("link[rel=\'icon\']").attr(\'href\',\''.$favicon.'\');';
    }

$og_url_content = $slug;
if(is_front_page()):
    $og_url_content = get_home_url();
endif;
$newHTML = '<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        '.$favicon_url.'
        <meta name="nodo-proxy" content="html">
        <!-- Meta Data -->
        <title>'.urldecode($title).'</title>
        <meta content="utf-8" http-equiv="encoding">
        <meta content="'.urldecode($desc).'" name="description">
        <meta content="'.urldecode($keywords).'" name="keywords">
        <!-- Open Graph -->
        <meta content="'.urldecode($author).'" name="author">
        <meta content="'.urldecode($title).'" property="og:title">
        <meta content="'.urldecode($desc).'" property="og:description">
        <meta content="'.$social.'" property="og:image">
        <meta property="og:url" content="'.$og_url_content.'">
        <meta property="og:type" content="website">
        <!-- WordPress Only Tracking Code -->
        '.$tracking.'
        <!-- Load ClickFunnels Javscript API -->
        <style type="text/css">#IntercomDefaultWidget{display:none;}#Intercom{display:none;}</style>
        <link href="https://assets3.clickfunnels.com/assets/lander.css" media="screen" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
        <script data-cfasync="false" type="text/javascript" src="https://static.clickfunnels.com/clickfunnels/landers/tmp/'.$pagekey.'.js"></script>
        <script data-cfasync="false" type="text/javascript">
            // Clean up duplicate meta data
            '.$favicon_js.'
            $(".metaTagTop").remove();
            $("title").last().remove();
            $("meta[property=\'og:description\']").last().remove();
            $("meta[property=\'og:title\']").last().remove();
            $("meta[property=\'og:type\']").last().remove();
            $("meta[property=\'og:url\']").last().remove();
        </script>
    </head>
    <body>
    </body>
</html>';

    return $newHTML;
}
// Iframe Alternative
public function get_page_iframe( $cf_iframe_url, $meta = "", $title = "", $desc = "", $social = "", $pageid = "",$slug = "",$favicon = "https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png", $author = "", $keywords = "", $tracking = "", $favicon_choice = ""   ) {
    global $wp_query;
    if ($wp_query->is_404) {
        $wp_query->is_404 = false;
    }
    header("HTTP/1.1 200 OK");

if($slug == '') {
    $slug = $cf_iframe_url;
}
if ($favicon_choice == 'default') {
    $favicon_url = '';
    $favicon_js = '$("link[rel=\'icon\']").remove();';
}
else {
     $favicon_url = '<link href="'.$favicon.'" rel="shortcut icon" type="image/x-icon" />';
     $favicon_js = '$("link[rel=\'icon\']").attr(\'href\',\''.$favicon.'\');';
}

$iframeVersion = '<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        '.$favicon_url.'
        <meta name="nodo-proxy" content="html">
        <!-- Meta Data -->
        <title>'.urldecode($title).'</title>
        <meta content="utf-8" http-equiv="encoding">
        <meta content="'.urldecode($desc).'" name="description">
        <meta content="'.urldecode($keywords).'" name="keywords">
        <!-- Open Graph -->
        <meta content="'.urldecode($author).'" name="author">
        <meta content="'.urldecode($title).'" property="og:title">
        <meta content="'.urldecode($desc).'" property="og:description">
        <meta content="'.$social.'" property="og:image">
        <meta property="og:url" content="'.$slug.'">
        <meta property="og:type" content="website">
        <!-- WordPress Only Tracking Code -->
        '.$tracking.'
        <style>
            * {
                margin: 0 !important;
                padding: 0 !important;
            }
           .container {
                position: relative;
                width: 100%;
                height: 100%;
            }
            .video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
        <script>
            $("body").ready(function () {
                $(".socialheader_desc").attr("content", $("meta[name=description]").attr("content"));
                $(".socialheader_title").attr("content", $("title").text());
                $(".video").load(function () {
                    $(this).height($(document).height());
                });
                $( window ).resize(function() {
                    $(".video").height($(window).height());
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <iframe class="video" width="100%" height="100%" src="'.$cf_iframe_url.'" frameborder="0" allowfullscreen></iframe>
        </div>
    </body>
</html>';

    return $iframeVersion;
}
// Click Gate
public function get_page_html_clickgate( $funnel_id, $position = 0, $meta = "", $title = "", $desc = "", $social = "", $cf_iframe_url = "", $pageid = "",$slug = "",$favicon = "https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png", $author = "", $keywords = "", $tracking = ""  ) {
    global $wp_query;
    if ($wp_query->is_404) {
        $wp_query->is_404 = false;
    }
    header("HTTP/1.1 200 OK");

if($slug == '') {
    $slug = $cf_iframe_url;
}

$newHTML = '<!DOCTYPE html>
<html>
    <head>
        <!-- Load ClickFunnels Javscript API -->
        <style>#IntercomDefaultWidget{display:none;}#Intercom{display:none;}</style>
        <link href="https://assets3.clickfunnels.com/assets/lander.css" media="screen" rel="stylesheet"/>
        <script type="text/javascript" src="https://static.clickfunnels.com/clickfunnels/landers/tmp/'.$position.'.js"></script>
        <script>
            // Clean up duplicate meta data
            $(".metaTagTop").remove();
            $("title").last().remove();
            $("meta[property=\'og:description\']").last().remove();
            $("meta[property=\'og:title\']").last().remove();
            $("meta[property=\'og:type\']").last().remove();
            $("meta[property=\'og:url\']").last().remove();
        </script>
    </head>
    <body>
        <!-- This Page is Powered by ClickFunnels.com -->
    </body>
</html>';


        return $newHTML;
    }
}

