<?php
/**
    * Plugin Name: ClickFunnels
    * Plugin URI: https://www.clickfunnels.com
    * Description: Connect to your ClickFunnels account with simple authorization key and show any ClickFunnels page as your homepage or as 404 error pages or simply choose any of your pages and make clean URLs to your ClickFunnels pages. Don't have an account? <a target="_blank" href="https://www.clickfunnels.com">Sign up for your 2 week <em>free</em> trial now.</a>
    * Version: 2.0.10
    * Author: Etison, LLC
    * Author URI: https://www.clickfunnels.com
*/



define( "CF_URL", plugin_dir_url( __FILE__ ) );
define( "CF_PATH", plugin_dir_path( __FILE__ ) );
define( "CF_API_EMAIL", get_option( 'clickfunnels_api_email' ) );
define( "CF_API_AUTH_TOKEN", get_option( 'clickfunnels_api_auth' ) );
define( "CF_API_URL", "https://api.clickfunnels.com/" );
include "CF_API.php";
class ClickFunnels {
    public function __construct( CF_API $api ) {
        $this->api = $api;
        add_action( "init", array( $this, "create_custom_post_type" ) );
        add_action( "admin_enqueue_scripts", array( $this, "load_scripts" ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_filter( 'manage_edit-clickfunnels_columns', array( $this, 'add_columns' ) );
        add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
        add_action( 'manage_posts_custom_column', array( $this, 'fill_columns' ) );
        add_action( "template_redirect", array( $this, "do_redirects" ), 1, 2 );
        add_action( 'trashed_post', array( $this, 'post_trash' ), 10 );
        add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
        // check permalinks
        if ( get_option( 'permalink_structure' ) == '' ) {
            $this->add_notice( 'ClickFunnels needs <a href="options-permalink.php">permalinks</a> enabled!', 0 );
        }
        
        add_action( 'init',         array( $this, 'remove_cookie' ) );
        add_action( 'do_feed_rss',  array($this, 'do_feed_rss'), 1);
        add_action( 'do_feed_rss2', array($this, 'do_feed_rss'), 1);
        add_action( 'wp_footer',    array($this, 'add_custom_close_script'));
    }
    
    function add_custom_close_script(){
        
        $cf_options = get_option( "cf_options" );
        if ( $cf_options['clickgate']['page_id']):
            $page = $this->get_clickgate();
            if ( !empty( $page['post_id'] ) ):
                $thepage = explode( "{#}", $page['page_id'] );
                print_r($thepage);
                echo '<script>
                    
                    var timerId = setTimeout(function tick() {
                        var $iframe = jQuery(\'iframe[src^="' . $thepage[7] . '"]\');   //[src]
                        
                        if( $iframe.length == 0 ){
                            
                            var query = window.location.search.substring(1);
                            var vars = query.split("&");
                            for (var i=0; i < vars.length; i++) {
                                var pair = vars[i].split("=");
                                if(pair[0] == "redirect_to"){
                                    window.location.replace( pair[1] );
                                }
                            }
                        }
                        timerId = setTimeout(tick, 2000);
                    }, 5000);
                    
                </script>';
                
            endif;
        endif;
    }
    
    public function remove_cookie(){
        if(isset($_GET['remcook']) and $_GET['remcook'] == 1):
            $cf_options = get_option( "cf_options" );
            if ( $cf_options['clickgate']['page_id']):
                $page = $this->get_clickgate();
                if ( !empty( $page['post_id'] ) ):
                    $thepage = explode( "{#}", $page['page_id'] );
                    setcookie("clickgate_shown_$thepage[1]", "", -3600);
                    unset($_COOKIE["clickgate_shown_$thepage[1]"]);
                endif;
            endif;
        endif;
    }
    
    public function do_feed_rss(){
        $cf_options = get_option( "cf_options" );
        if ( $cf_options['clickgate']['page_id']):
            $page = $this->get_clickgate();
            if ( !empty( $page['post_id'] ) ):
                $thepage = explode( "{#}", $page['page_id'] );
                
                if(!isset($_COOKIE["clickgate_shown_$thepage[1]"])):
                    header( 'Location: ' . add_query_arg(
                                                         array('redirect_to' => add_query_arg( null, null),
                                                                'remcook' => '1'),
                                                         home_url()
                                                         )
                           );
                    exit();
                endif;
                
            endif;
        endif;
        
        return;
    }
    
    public function updated_message( $messages ) {
        $post_id = get_the_ID();
        // make sure this is one of our pages
        if ( get_post_meta( $post_id, "cf_thepage", true ) == "" )
            return $messages;
        $data = $this->get_url( $post_id );
        $view_html = " <a target='_blank' href='{$data['url']}'>Click to View</a> ";
        $messages['post'][1] = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';
        $messages['post'][4] = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';
        $messages['post'][1] = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';
        $messages['post'][6] = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';
        $messages['post'][10] = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';
        return $messages;
    }
    public function post_trash( $post_id ) {
        $cf_slug= get_post_meta( $post_id, 'cf_slug', true );
        $cf_options = get_option( "cf_options" );
        unset( $cf_options["pages"][$cf_slug] );
        update_option( "cf_options", $cf_options );
        if ( $this->is_404( $post_id ) ) {
            $this->set_404( "", "" );
        }
        else if ( $this->is_home( $post_id ) ) {
            $this->set_home( "", "" );
        }
        else if ( $this->is_clickgate( $post_id ) ) {
            $this->set_clickgate( "", "" );
        }
    }
    public function do_redirects() {
        global $page;
        //header('Content-Type: text/html; charset=utf-8');
        $current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // remove parameters
        $current = explode( "?", $current );
        $current = $current[0];
        $post_id = get_the_ID();
        $home_url = get_home_url()."/";
        $slug = str_replace( $home_url, "", $current );
        $slug= rtrim( $slug, '/' );
        $cf_options = get_option( "cf_options" );
        $thepage = explode( "{#}", $cf_options['clickgate']['page_id'] );
        $baseURL = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $baseURL_https = 'https://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $baseURL_none = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        if ( !empty( $cf_options["pages"][$slug] ) ) {
            $thepage = explode( "{#}", $cf_options["pages"][$slug] );
            echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4], $thepage[5], $thepage[0]  );
            exit();
        } else if ( is_404() ) {
                $page = $this->get_404();
                if ( !empty( $page['post_id'] ) ) {
                    $thepage = explode( "{#}", $page['page_id'] );
                    echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4], $thepage[5], $thepage[0] );
                    exit();
                }
                 else if (get_option( 'clickfunnels_404Redirect' ) == 'yesRedirect') {
                    wp_redirect( get_bloginfo( 'siteurl' ) , 301 );
                }
            }
        else if ( is_home() && $baseURL == $home_url || is_home() && $baseURL_https == $home_url || is_home() && $baseURL_none == $home_url
                || is_front_page() && $baseURL == $home_url || is_front_page() && $baseURL_https == $home_url || is_front_page() && $baseURL_none == $home_url) {
                $page = $this->get_home();
                if ( !empty( $page['post_id'] ) ) {
                    $thepage = explode( "{#}", $page['page_id'] );
                    echo $this->get_page_html( $thepage[0], $thepage[1], $thepage[4], $thepage[5], $thepage[0] );
                    exit();
                } else {
                    if ( $cf_options['clickgate']['page_id']) {
                        $page = $this->get_clickgate();
                        $cookiename = "_cf_clickgate_now";
                        if ( !empty( $page['post_id'] ) ) {
                            if(is_feed()){
                                
                                setcookie("clickgate_shown_$thepage[1]","seen",-2147483647);
                                unset($_COOKIE["clickgate_shown_$thepage[1]"]);
                                
                            } elseif (!$_COOKIE["clickgate_shown_$thepage[1]"]) {
                                
                                setcookie("clickgate_shown_$thepage[1]","seen",2147483647); // php cookies cannot go past year 2038
                                echo do_shortcode("[clickfunnels_clickgate url='$thepage[7]']");
                                
                            }
                        }
                    }
                }
            }

        else if ( $cf_options['clickgate']['page_id']) {
            
                if(!is_feed()):
                
                    $page = $this->get_clickgate();
                    $cookiename = "_cf_clickgate_now";
                    if ( !empty( $page['post_id'] ) ) {
                        $thepage = explode( "{#}", $page['page_id'] );
                        if(is_feed()){
                            
                            setcookie("clickgate_shown_$thepage[1]","seen",-2147483647);
                            unset($_COOKIE["clickgate_shown_$thepage[1]"]);
                            
                        } elseif (!$_COOKIE["clickgate_shown_$thepage[1]"]) {
                            
                            setcookie("clickgate_shown_$thepage[1]","seen",2147483647);
                            echo do_shortcode("[clickfunnels_clickgate url='$thepage[7]']");
                            
                        }
                    }
                
                endif;
            }

    }
    public function fill_columns( $column ) {

      if(!get_option( 'clickfunnels_api_email')) {
          update_option( 'clickfunnels_api_email', '');
      }
      if(!get_option( 'clickfunnels_api_auth')) {
          update_option( 'clickfunnels_api_auth', '');
      }
      if(!get_option( 'clickfunnels_siteURL')) {
          update_option( 'clickfunnels_siteURL', '');
      }
      if(!get_option( 'clickfunnels_404Redirect')) {
          update_option( 'clickfunnels_404Redirect', '');
      }
      if(!get_option( 'clickfunnels_agency_group_tag')) {
          update_option( 'clickfunnels_agency_group_tag', '');
      }
      if(!get_option( 'clickfunnels_agency_api_details')) {
          update_option( 'clickfunnels_agency_api_details', '');
      }
      if(!get_option( 'clickfunnels_agency_reset_data')) {
          update_option( 'clickfunnels_agency_reset_data', '');
      }
      if(!get_option( 'clickfunnels_agency_hide_settings')) {
          update_option( 'clickfunnels_agency_hide_settings', '');
      }

        $id = get_the_ID();
        $cf_type = get_post_meta( $id, 'cf_type', true );
        $cf_slug= get_post_meta( $id, 'cf_slug', true );
        $cf_thepage= get_post_meta( $id, 'cf_thepage', true );
        $cf_thefunnel= get_post_meta( $id, 'cf_thefunnel', true );
        if ( $cf_type =="hp" && !$this->is_home( $id ) ) {
            $cf_type = "notype";
        }
        if ( $cf_type =="np" && !$this->is_404( $id ) ) {
            $cf_type = "notype";
        }
        if ( $cf_type =="clickgate" && !$this->is_clickgate( $id ) ) {
            $cf_type = "notype";
        }
        if ( 'cf_post_name' == $column ) {
            $url = get_edit_post_link( get_the_ID() );
            $funnel_id = get_post_meta( get_the_ID(), 'cf_thefunnel', true );
            if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
                echo "[ Incorrect API ]  <a href='../wp-admin/edit.php?post_type=clickfunnels&page=cf_api'>Setup API Settings</a>";
            } else {
                $pagename = explode("{#}", $cf_thepage);
                if ($pagename[5] != '') {
                      echo '<strong><a href="' . $url .'">' .  $pagename[5]. '</a></strong>' ;
                }
                else {
                    echo '<a href="post.php?post='.get_the_ID().'&action=edit">undefined</a>';
                }
            }

        }
        if ( 'cf_post_funnel' == $column ) {
            $url = get_edit_post_link( get_the_ID() );
            $funnel_id = get_post_meta( get_the_ID(), 'cf_thefunnel', true );
            if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
                echo "[ Incorrect API ]  <a href='../wp-admin/edit.php?post_type=clickfunnels&page=cf_api'>Setup API Settings</a>";
            } else {
                $pagename = explode("{#}", $cf_thepage);
                if ($pagename[5] != '') {
                      if($pagename[10]) { echo '<span>'.$pagename[10].'</span>'; }
                      else { echo '<a href="post.php?post='.get_the_ID().'&action=edit&updatemeta=true">* Click to Update Page *</a>'; }
                }
                else {
                    echo 'undefined';
                }
            }

        }

        if ( 'cf_thepage' == $column ) {
            $url = get_edit_post_link( get_the_ID() );
            echo '<strong><a  href="' . $url .'">Edit Page</a></strong>' ;

        }
        if ( 'cf_openinEditor' == $column ) {
            $pagename = explode("{#}", $cf_thepage);
            echo "<strong><a href='https://www.clickfunnels.com/pages/$pagename[1]' target='_blank'>Open in Editor</a></strong>";
        }

        if ( 'cf_metaupdate' == $column ) {
            $metaCheck = explode("{#}", $cf_thefunnel);
            echo "<strong data-id='".get_the_ID()."' class='loadingcf_meta'><img style='opacity: .4;display: none' src='https://images.clickfunnels.com/ea/5d82007d3a11e5aecef7aa69d38501/ajax-loader.gif' /></strong><div style='display: none' class='metainfo_check' data-id='".get_the_ID()."'>$metaCheck[4]</div><div style='display: none' class='metainfo_id' data-id='".get_the_ID()."' data-pageid='$metaCheck[3]' data-pagekey='$metaCheck[1]'>$metaCheck[0]</div><div style='display: none' class='metainfo_pageid' data-id='".get_the_ID()."'>".get_the_ID()."</div>";
        }

        switch ( $cf_type ) {
        case "p":
            $post_type = "Page";
            $url = get_home_url()."/".$cf_slug;
            break;
        case "hp":
            $post_type = "<img src='https://images.clickfunnels.com/59/8ae200796511e581f93f593a07eabb/1445609147_house3.png' style='margin-right: 2px;margin-top: 3px;opacity: .7;width: 16px;height: 16px;' /> Set as Home Page";
            $url = get_home_url().'/';
            break;
        case "np":
            $post_type = "<img src='https://images.clickfunnels.com/c0/193250796611e599df696af00696f8/1445609787_attention_1.png' style='margin-right: 2px;margin-top: 3px;opacity: .7;width: 16px;height: 16px;' />  Set as 404 Page";
            $url = get_home_url().'/test-url-404-page';
            break;
        case "clickgate":
            $post_type = "<img src='https://images.clickfunnels.com/20/ca1d90796611e5a69ba592391e5dff/1445609517_landing-page.png' style='margin-right: 2px;margin-top: 3px;opacity: .7;width: 16px;height: 16px;' /> Set as ClickGate";
            $url = 'clickgate';
            break;
        default:
            $post_type = "Set Page Type";
            $url = null;
        }
        if ( 'cf_type' == $column ) {

            if ( $post_type !== 'Set Page Type') {
               echo "<strong>$post_type</strong>";
            }
            else {
                 echo "<strong>Page</strong>";
            }
        }
        if ( 'cf_path' == $column ) {
            if ( !empty( $url ) ) {
                if ($url == 'clickgate') {
                    echo "<strong  style='opacity: .7'>Shown on All Pages</strong>";
                }
                else {
                    echo "<strong><a href='$url' target='_blank'>View Page</a></strong>";
                }
            }
            else {
                $url = get_edit_post_link( get_the_ID() );
                echo '<strong><a href="' . $url .'" style="color: #E54E3F">Requires Custom URL</a></strong>' ;
            }
        }
    }

    private function get_url( $post_id ) {
        $cf_type = get_post_meta( $post_id, 'cf_type', true );
        $cf_slug= get_post_meta( $post_id, 'cf_slug', true );
        switch ( $cf_type ) {
        case "p":
            $data['post_type'] = "Page";
            $data['url'] = get_home_url()."/".$cf_slug;
            break;
        case "hp":
            $data['post_type']= "Home Page";
            $data['url'] = get_home_url().'/';
            break;
        case "np":
            $data['post_type'] = "404 Page";
            $data['url'] = get_home_url().'/test-url-random';
        case "clickgate":
            $data['post_type'] = "ClickGate";
            $data['url'] = get_home_url().'/?showclickgate=true';
        default:
            $data['post_type'] = "Set Page Type";
            $url = get_edit_post_link( get_the_ID() );
            $data['url'] = $url;

        }
        return $data;
    }
    public function get_funnels() {
        $cf_funnels = get_transient( "cf_funnels" );
        if ( !empty( $cf_funnels ) ) {
            return $cf_funnels;
        }
        else {
            $cf_funnels  = $this->api->get_funnels();
            return $cf_funnels;
        }
    }
    public function get_page_html( $funnel_id, $position, $meta, $postid, $thepage ) {
        $page_html = get_transient( "cf_page_html_{$funnel_id}" );
        if (get_post_meta( $postid, "cf_iframe_check", true ) == 'on') {
            $page_html = $this->api->get_page_iframe( get_post_meta( $postid, "cf_iframe_url", true ), $meta, get_post_meta( $postid, "cf_seo_title", true ), get_post_meta( $postid, "cf_seo_desc", true ), get_post_meta( $postid, "cf_seo_image", true ), $postid, get_post_meta( $postid, "cf_page_url", true ), get_post_meta( $postid, "cf_favicon", true ), get_post_meta( $postid, "cf_author", true ), get_post_meta( $postid, "cf_keywords", true ), get_post_meta( $postid, "cf_wptracking_code", true ), get_post_meta( $postid, "cf_favicon_choice", true ) );
            return $page_html;
        }
        else {
            if ( !empty( $page_html ) ) {
                return $page_html;
            }
            else {
                $page_html = $this->api->get_page_html( $funnel_id, $position, $meta, get_post_meta( $postid, "cf_seo_title", true ), get_post_meta( $postid, "cf_seo_desc", true), get_post_meta( $postid, "cf_seo_image", true ), get_post_meta( $postid, "cf_iframe_url", true ), $thepage, get_post_meta( $postid, "cf_page_url", true ), get_post_meta( $postid, "cf_favicon", true ), get_post_meta( $postid, "cf_author", true ), get_post_meta( $postid, "cf_keywords", true ), get_post_meta( $postid, "cf_wptracking_code", true ), get_post_meta( $postid, "cf_favicon_choice", true )  );
                return $page_html;
            }
        }
    }
    public function save_meta( $post_id, $post ) {
        if ( !isset( $_POST['clickfunnel_nonce'] ) || !wp_verify_nonce( $_POST['clickfunnel_nonce'], "save_clickfunnel" ) )
            return $post_id;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;
        if ( $post->post_type != 'clickfunnels' )
            return $post_id;
            // Get Unique Slug
            $cf_slug = $_POST['cf_slug'];
            $cf_type = $_POST['cf_type'];
            $cf_page = $_POST['cf_page'];
            $cf_thepage = $_POST['cf_thepage'];
            $cf_thefunnel = $_POST['cf_thefunnel'];
            $cf_seo_tags = $_POST['cf_seo_tags'];
            $cf_iframe_check = $_POST['cf_iframe_check'];
            $cf_iframe_url = $_POST['cf_iframe_url'];
            $cf_seo_title = $_POST['cf_seo_title'];
            $cf_seo_desc = $_POST['cf_seo_desc'];
            $cf_seo_image = $_POST['cf_seo_image'];
            $cf_page_url = $_POST['cf_page_url'];
            $cf_favicon = $_POST['cf_favicon'];
            $cf_author = $_POST['cf_author'];
            $cf_keywords = $_POST['cf_keywords'];
            $cf_footer_tracking = $_POST['cf_footer_tracking'];
            $cf_head_tracking = $_POST['cf_head_tracking'];
            $cf_wptracking_code = $_POST['cf_wptracking_code'];
            $cf_favicon_choice = $_POST['cf_favicon_choice'];
            update_post_meta( $post_id, "cf_type", $cf_type );
            update_post_meta( $post_id, "cf_page", $cf_page );
            update_post_meta( $post_id, "cf_thepage", $cf_thepage );
            update_post_meta( $post_id, "cf_slug", $cf_slug );
            update_post_meta( $post_id, "cf_thefunnel", $cf_thefunnel );
            update_post_meta( $post_id, "cf_seo_tags", $cf_seo_tags );
            update_post_meta( $post_id, "cf_iframe_check", $cf_iframe_check );
            update_post_meta( $post_id, "cf_iframe_url", $cf_iframe_url );
            update_post_meta( $post_id, "cf_seo_title", $cf_seo_title );
            update_post_meta( $post_id, "cf_seo_desc", $cf_seo_desc );
            update_post_meta( $post_id, "cf_seo_image", $cf_seo_image );
            update_post_meta( $post_id, "cf_page_url", $cf_page_url );
            update_post_meta( $post_id, "cf_favicon", $cf_favicon );
            update_post_meta( $post_id, "cf_author", $cf_author );
            update_post_meta( $post_id, "cf_head_tracking", $cf_head_tracking );
            update_post_meta( $post_id, "cf_footer_tracking", $cf_footer_tracking );
            update_post_meta( $post_id, "cf_keywords", $cf_keywords );
            update_post_meta( $post_id, "cf_wptracking_code", $cf_wptracking_code );
            update_post_meta( $post_id, "cf_favicon_choice", $cf_favicon_choice );
            $cf_options = get_option( "cf_options" );
            if(isset( $cf_options['pages'][$cf_slug] )):
                unset( $cf_options['pages'][$cf_slug] );
                update_option( "cf_options", $cf_options );
            endif;
        if ( $this->is_404( $post_id ) )
            $this->set_404( "", "" );
        if ( $this->is_home( $post_id ) )
            $this->set_home( "", "" );
        if ( $this->is_clickgate( $post_id ) )
            $this->set_clickgate( "", "" );
        switch ( $cf_type ) {
        case "p":
            $cf_options = get_option( "cf_options" );
            $cf_options['pages'][$cf_slug] = $cf_thefunnel;
            update_option( "cf_options", $cf_options );
            break;
        case "hp":  // home page
            $this->set_home( $post_id, $cf_thefunnel );
            break;
        case "np":  // 404 page
            $this->set_404( $post_id, $cf_thefunnel );
            break;
        case "clickgate":  // ClickGate
            $this->set_clickgate( $post_id, $cf_thefunnel );
            break;
        }
    }
    public function get_page( $page_slug ) {
        $cf_options = get_option( "cf_options" );
        return (isset($cf_options['pages'][$page_slug])) ? $cf_options['pages'][$page_slug] : array();
    }
    public function is_page( $page_slug ) {
        $cf_options = get_option( "cf_options" );
        if ( !empty( $cf_options['pages'][$page_slug] ) )
            return true;
        return false;
    }
    public function set_home( $post_id, $page_id ) {
        $cf_options = get_option( "cf_options" );
        $cf_options['home']['post_id'] = $post_id;
        $cf_options['home']['page_id'] = $page_id;
        update_option( "cf_options", $cf_options );
    }
    public function get_home() {
        $cf_options = get_option( "cf_options" );
        return (isset($cf_options['home'])) ? $cf_options['home'] : array();
    }
    public function is_home( $post_id ) {
        $cf_options = get_option( "cf_options" );
        if ( isset($cf_options['home']['post_id']) and $cf_options['home']['post_id'] == $post_id )
            return true;
        return false;
    }
    public function set_404( $post_id, $page_id ) {
        $cf_options = get_option( "cf_options" );
        $cf_options['404']['post_id'] = $post_id;
        $cf_options['404']['page_id'] = $page_id;
        update_option( "cf_options", $cf_options );
    }
    public function get_404() {
        $cf_options = get_option( "cf_options" );
        return $cf_options['404'];
    }
    public function is_404( $post_id ) {
        $cf_options = get_option( "cf_options" );
        if ( isset($cf_options['404']['post_id']) and $cf_options['404']['post_id'] == $post_id )
            return true;
        return false;
    }

    // ClickGate


    public function set_clickgate( $post_id, $page_id ) {
        $cf_options = get_option( "cf_options" );
        $cf_options['clickgate']['post_id'] = $post_id;
        $cf_options['clickgate']['page_id'] = $page_id;
        update_option( "cf_options", $cf_options );
    }
    public function get_clickgate() {
        $cf_options = get_option( "cf_options" );
        return $cf_options['clickgate'];
    }
    public function is_clickgate( $post_id ) {
        $cf_options = get_option( "cf_options" );
        if ( $cf_options['clickgate']['post_id'] == $post_id )
            return true;
        return false;
    }
    public function add_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];

        $new_columns['cf_post_name'] = "Page";
        // $new_columns['cf_thepage'] = "Edit";

        $new_columns['cf_post_funnel'] = "Funnel";

        $new_columns['cf_path'] = 'View';
        $new_columns['cf_openinEditor'] = 'Editor';
        $new_columns['cf_type'] = 'Type';
        $new_columns['cf_metaupdate'] = '';
        return $new_columns;
    }
    function view( $view, $data = array() ) {
        ob_start();
        extract( $data );
        include CF_PATH.$view.'.php';
        $content=ob_get_clean();
        return $content;
    }
    public function add_meta_box() {
        add_meta_box(
            'clickfunnels_meta_box', // $id
            'Setup Your ClickFunnels Page', // $title
            array( $this, "show_meta_box" ),
            'clickfunnels', // $page
            'normal', // $context
            'high' // $priority
        );
    }
    public function get_page_mode( $type = "edit" ) {
        global $pagenow;
        if ( !is_admin() ) return false;
        if ( $type == "edit" ) {
            return in_array( $pagenow, array( 'post.php', ) );
        } elseif ( $type == "new" ) { // check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        } else { // check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
        }
    }
    public function show_meta_box( $post ) {
        $id = $post->ID;
        $slug = get_post_meta( $id, "cf_slug", true );
        $thefunnel = get_post_meta( $id, "cf_thefunnel", true );
        $cf_seo_tags = get_post_meta( $id, "cf_seo_tags", true );
        $original_cf_type = get_post_meta( $id, "cf_type", true );
        if ( $this->get_page_mode( "new" ) )
            $original_cf_type = "p";
        if ( $original_cf_type == "p" ) {
            $data['cf_type'] = 'p';
            $data['cf_page'] =  '';
            if($this->is_page($slug)):
                $data['cf_page'] =  $this->get_page( $slug );
            endif;
            $data['cf_slug'] = $slug;
        }
        if ( $this->is_home( $id ) ) {
            $data['cf_type'] = 'hp';
            $data['cf_page'] = $this->get_home();
        }
        if ( $this->is_404( $id ) ) {
            $data['cf_type'] = 'np';
            $data['cf_page'] = $this->get_404();
        }
        if ( $this->is_clickgate( $id ) ) {
            $data['cf_type'] = 'clickgate';
            $data['cf_page'] = $this->get_clickgate();
        }
        if ( $this->get_page_mode( "edit" ) )
            $data['delete_link'] = get_delete_post_link( $id );
        else
            $data['delete_link'] ="";
        $data['cf_funnels'] = $this->get_funnels();
        echo $this->view( "admin", $data );
    }
    public function get_funnel_name( $funnel_id ) {
        $funnels = $this->get_funnels();
        foreach ( $funnels as $funnel )
            if ( $funnel->id == $funnel_id )
                return $funnel->name;
    }
    public function load_scripts() {
        global $post_type;
        if ( $post_type == "clickfunnels" ) {
            $this->load_actual_scripts();
        }
    }
    public function add_notice( $message, $type=1 ) {
        $this->message = $message;
        switch ( $type ) {
        case 1:
            add_action( "admin_notices", array( $this, "success_massage" ) );
            break;
        case 0:
            add_action( "admin_notices", array( $this, "error_message" ) );
            break;
        }
    }
    public function success_message() {
        echo '<div id="message" class="updated"><p><strong>Updated ClickFunnels Page.</strong></p></div>';
    }
    public function error_message() {
        echo '<div id="message" class="badAPI error notice" style="width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i> Error in ClickFunnels plugn, please check <a href="edit.php?post_type=clickfunnels&page=cf_api&error=compatibility">Settings > Compatibility Check</a> for details.</div>';
    }
    public function load_actual_scripts() {
        // wp_register_style( "clickfunnels_bootstrap", CF_URL."css/bootstrap.css" );
        // wp_enqueue_style( "clickfunnels_bootstrap" );
        // wp_enqueue_style( "clickfunnels_admin" );
        // wp_register_script( "clickfunnels_admin", CF_URL."js/admin.js" );
        // wp_enqueue_script( "clickfunnels_admin" );
    }
    public function remove_save_box() {
        global $wp_meta_boxes;
        foreach ( $wp_meta_boxes['clickfunnels'] as $k=>$v )
            foreach ( $v as $l=>$m )
                foreach ( $m as $o=>$p )
                    if ( $o !="clickfunnels_meta_box" )
                        unset( $wp_meta_boxes['clickfunnels'][$k][$l][$o] );
    }
    public function create_custom_post_type() {
        $labels = array(
            'name' => _x( 'ClickFunnels', 'post type general name' ),
            'singular_name' => _x( 'Pages', 'post type singular name' ),
            'add_new' => _x( 'Add New', 'Click Funnels' ),
            'add_new_item' => __( 'Add New ClickFunnels Page' ),
            'edit_item' => __( 'Edit ClickFunnels Page' ),
            'new_item' => __( 'Add New' ),
            'all_items' => __( 'Pages' ),
            'view_item' => __( 'View Click Funnels Pages' ),
            'search_items' => __( 'Search Click Funnels' ),
            'not_found' => __( 'Nothing found' ),
            'not_found_in_trash' => __( 'Nothing found in Trash' ),
            'parent_item_colon' => '',
            'hide_post_row_actions' => array(
                 'trash'
                 ,'edit'
                 ,'quick-edit'
               )
        );

        register_post_type( 'clickfunnels',
            array(
                'labels' =>  $labels,
                'public' => true,
                'menu_icon' => plugins_url( 'icon.png', __FILE__ ),
                'has_archive' => true,
                'supports' => array( '' ),
                'rewrite' => array( 'slug' => 'clickfunnels' ),
                'hide_post_row_actions' => array( 'trash' ),
                'register_meta_box_cb' => array( $this, "remove_save_box" )

            )
        );
    }
}
add_action( 'admin_menu', 'cf_plugin_submenu' );

function cf_plugin_submenu() {
    add_submenu_page(
        'edit.php?post_type=clickfunnels',
        __( 'ClickFunnels Shortcodes', 'clickfunnels-menu' ),
        __( 'Shortcodes', 'clickfunnels-menu' ),
        'manage_options',
        'clickfunnels_shortcodes',
        'clickfunnels_shortcodes'
    );
    if (get_option( 'clickfunnels_agency_hide_settings', '' ) != 'hide') {
        add_submenu_page(
            'edit.php?post_type=clickfunnels',
            __( 'Settings',
                'clickfunnels-menu' ),
            __( 'Settings', 'clickfunnels-menu' ),
            'manage_options',
            'cf_api',
            'cf_api_settings_page'
        );
    } else {
        add_submenu_page(
            null,
            __( 'Settings',
                'clickfunnels-menu' ),
            __( 'Settings', 'clickfunnels-menu' ),
            'manage_options',
            'cf_api',
            'cf_api_settings_page'
        );
    }
    add_submenu_page(
        null,
        __( 'Reset Data', 'clickfunnels-menu' ),
        __( 'Reset Data', 'clickfunnels-menu' ),
        'manage_options',
        'reset_data',
        'clickfunnels_reset_data_show_page'
    );
}

function clickfunnels_reset_data_show_page()
{
    include 'reset_data.php';
}
function cf_api_settings_page() {
    include 'access.php';
}
function clickfunnels_shortcodes() {
    include 'post_shortcode.php';
}

function cf_get_file_contents ($url) {
   if(function_exists('file_get_contents')){
       $url_get_contents_data = file_get_contents($url);
   }
   elseif (function_exists('curl_exec')){
       $conn = curl_init($url);
       curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
       curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
       curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
       $url_get_contents_data = (curl_exec($conn));
       curl_close($conn);
   }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
       $handle = fopen ($url, "r");
       $url_get_contents_data = stream_get_contents($handle);
   }else{
       $url_get_contents_data = false;
   }
return $url_get_contents_data;
}


function get_file_content($url, $forceCURL = false)
{
   $internalServerURL = 'http://' . $_SERVER['SERVER_NAME'];
   $internalSecureServerURL = 'https://' . $_SERVER['SERVER_NAME'];

   $externalURL = strpos('http', $url) === 0 && !(strpos($internalServerURL, $url) === 0 || strpos($internalSecureServerURL, $url) === 0);

   if($externalURL && !ini_get('allow_url_fopen'))
   {
       return 'ERROR: Reading content from external URLs is not allowed on this server. Please contact your administrator or provider to resolve this issue!';
   }

   if(!defined('CONTENTMETHOD'))
   {
       $contentMethod = false;
       if(file_get_contents(__FILE__))
       {
           $contentMethod = 'file';
       }
       else if(function_exists('curl_version'))
       {
           $contentMethod = 'curl';
       }
       define('CONTENTMETHOD', $contentMethod);
   }
   if(!CONTENTMETHOD)
   {
       return false;
   }
   $content = '';
   if(CONTENTMETHOD === 'file' && !$forceCURL)
   {
       $content = file_get_contents($url);
   }
   else
   {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $content = curl_exec($ch);
       curl_close($ch);
   }
   return $content;
}
function clickfunnels_loadjquery($hook) {
    if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
        return;
    }
    wp_enqueue_script( 'jquery' );
}
add_action('admin_enqueue_scripts', 'clickfunnels_loadjquery');
$api = new CF_API();
$click = new ClickFunnels( $api );
// Register homepage/404 for reading settings
add_action('admin_init', 'clickfunnels_reading_settings_admin_init');
function clickfunnels_reading_settings_admin_init(){

    add_settings_section( 'clickfunnels_reading_settings_notify_home_section', '<img style="margin-right: 5px;margin-bottom: -10px" src="https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png" /> ClickFunnels Home / 404 / ClickGate Page Settings', 'clickfunnels_reading_settings_setting_input', 'reading' );
}

// ****************************************************************************************************************************
// Home and 404 Page Check
function clickfunnels_reading_settings_setting_input() {
    $cf_options = get_option( "cf_options" );
    $thehome = $cf_options['home'];
    if ( $thehome['page_id'] ) {
        echo '<span style="display: block;margin-bottom: 8px;font-weight: bold;color: #0073AA"><img style="margin-right: 4px;width: 13px;" src="https://images.clickfunnels.com/93/a832907e2d11e5bc8533cec9213fdd/1446134982_accept.png" /><a href="post.php?post='.$thehome['post_id'].'&action=edit">Home Page is Enabled</a></span>';
    } else {
        echo '<span style="display: block;margin-bottom: 8px;font-style: italic;opacity: .7">Home Page is Disabled</span>';
    }
    $the404 = $cf_options['404'];
    if ( $the404['page_id'] ) {
        echo '<span style="display: block;margin-bottom: 8px;font-weight: bold;color: #0073AA"><img style="margin-right: 4px;width: 13px;" src="https://images.clickfunnels.com/93/a832907e2d11e5bc8533cec9213fdd/1446134982_accept.png" /><a href="post.php?post='.$the404['post_id'].'&action=edit">404 Page is Enabled</a></span>';
    } else {
        echo '<span style="display: block;margin-bottom: 8px;font-style: italic;opacity: .7">404 Page is Disabled</span>';
    }
    $theClickGate = $cf_options['clickgate'];
    if ( $theClickGate['page_id'] ) {
        echo '<span style="display: block;margin-bottom: 8px;font-weight: bold;color: #0073AA"><img style="margin-right: 4px;width: 13px;" src="https://images.clickfunnels.com/93/a832907e2d11e5bc8533cec9213fdd/1446134982_accept.png" /><a href="post.php?post='.$theClickGate['post_id'].'&action=edit">ClickGate Page is Enabled</a></span>';
    } else {
        echo '<span style="display: block;margin-bottom: 8px;font-style: italic;opacity: .7">ClickGate is Disabled</span>';
    }
}


// ****************************************************************************************************************************
// Blog Post Embed Shortcode
function clickfunnels_embed( $atts ) {
    $a = shortcode_atts( array(
        'height' => '650',
        'scroll' => 'on',
        'url' => 'https://clickfunnels.com/',
    ), $atts );

    return "<iframe src='{$a['url']}' width='100%' height='{$a['height']}' frameborder='0' scrolling='{$a['scroll']}'></iframe>";
}
add_shortcode( 'clickfunnels_embed', 'clickfunnels_embed' );

// ****************************************************************************************************************************
// ClickPop Shortcode
function clickfunnels_clickpop_script() {
    wp_register_script( 'cf_clickpop', 'https://app.clickfunnels.com/assets/cfpop.js', array(), '1.0.0', true );
    wp_enqueue_script( 'cf_clickpop' );
}
add_action( 'wp_enqueue_scripts', 'clickfunnels_clickpop_script' );
function clickfunnels_clickpop( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'exit' => 'false',
        'delay' => '',
        'id' => '',
        'subdomain' => '',
    ), $atts );
    if ($a['delay'] != '') {
        $delayTime = "{$a['delay']}000";
        $delay_js = "<script>window.onload=function(){setTimeout(clickpop_timed_click, $delayTime);}; function clickpop_timed_click(){for (links=document.getElementsByTagName('a'), i=0; i < links.length; ++i) link=links[i], null !=link.getAttribute('href') && link.getAttribute('href').match(/\/optin_box\/(([a-zA-Z]|\d){16})/i) && (cf_showpopup(link.getAttribute('href'))); function openPopup(e){if (ID=e.hashCode(), currentPopup=ID, cf_iframe=document.getElementById(ID), null==document.getElementById(ID)){var t=document.getElementsByTagName(\"body\"), n=e; document.body.innerHTML +='<iframe src=\"' + n + '?iframe=true\" id=\"' + ID + '\" style=\"position: fixed !important; left: 0px; top: 0px !important; width: 100%; border: none; z-index: 999999999999999 !important; visibility: hidden; \"></iframe>'}document.getElementById(ID).style.width=viewWidth + \"px\", document.getElementById(ID).style.height=viewHeight + \"px\", document.getElementById(ID).style.visibility=\"visible\", makeWindowModal(); var i=document.documentElement, t=document.body, o=i && i.scrollLeft || t && t.scrollLeft || 0, d=i && i.scrollTop || t && t.scrollTop || 0; document.getElementById(ID).style.top=0 + \"px\", document.getElementById(ID).style.left=o + \"px\"; var l=0; return reanimateMessageIntervalID=setInterval(function(){iframe=document.getElementById(ID), void 0 !=iframe && iframe.contentWindow.postMessage(\"reanimate\", \"*\"), ++l >=15 && clearInterval(reanimateMessageIntervalID)}, 1e3), !1}function cf_showpopup(url){openPopup(url);}}</script>";
    } else {
        $delayTime = '';
        $delay_js = "";
    }
    if (strpos($a['subdomain'], '.') !== false) {
        return "<a href='https://{$a['subdomain']}/optin_box/{$a['id']}' data-exit='{$a['exit']}'>$content</a>$delay_js";
    }
    else {
      return "<a href='https://{$a['subdomain']}.clickfunnels.com/optin_box/{$a['id']}' data-exit='{$a['exit']}'>$content</a>$delay_js";
    }

}
add_shortcode( 'clickfunnels_clickpop', 'clickfunnels_clickpop' );

// ClickGate Shortcode
function clickfunnels_clickgate( $atts ) {
    $a = shortcode_atts( array(
        'url' => '',
    ), $atts );
    return "<script>window.onload=function(){openPopup('{$a['url']}')}; function clickpop_timed_click(){for (links=document.getElementsByTagName('a'), i=0; i < links.length; ++i) link=links[i], null !=link.getAttribute('href') && link.getAttribute('href').match(/\/optin_box\/(([a-zA-Z]|\d){16})/i) && (cf_showpopup(link.getAttribute('href'))); function openPopup(e){if (ID=e.hashCode(), currentPopup=ID, cf_iframe=document.getElementById(ID), null==document.getElementById(ID)){var t=document.getElementsByTagName(\"body\"), n=e; document.body.innerHTML +='<iframe src=\"' + n + '?iframe=true\" id=\"' + ID + '\" style=\"position: fixed !important; left: 0px; top: 0px !important; width: 100%; border: none; z-index: 999999999999999 !important; visibility: hidden; \"></iframe>'}document.getElementById(ID).style.width=viewWidth + \"px\", document.getElementById(ID).style.height=viewHeight + \"px\", document.getElementById(ID).style.visibility=\"visible\", makeWindowModal(); var i=document.documentElement, t=document.body, o=i && i.scrollLeft || t && t.scrollLeft || 0, d=i && i.scrollTop || t && t.scrollTop || 0; document.getElementById(ID).style.top=0 + \"px\", document.getElementById(ID).style.left=o + \"px\"; var l=0; return reanimateMessageIntervalID=setInterval(function(){iframe=document.getElementById(ID), void 0 !=iframe && iframe.contentWindow.postMessage(\"reanimate\", \"*\"), ++l >=15 && clearInterval(reanimateMessageIntervalID)}, 1e3), !1}function cf_showpopup(url){openPopup(url);}}</script>";

}
add_shortcode( 'clickfunnels_clickgate', 'clickfunnels_clickgate' );

// ****************************************************************************************************************************
// ClickOptin Shortcode
function clickfunnels_clickoptin( $atts ) {
    $a = shortcode_atts( array(
        'button_text' => 'Subscribe To Our Mailing List',
        'button_color' => 'blue',
        'placeholder' => 'Enter Your Email Address Here',
        'id' => '#',
        'subdomain' => '#',
        'input_icon' => 'show',
        'redirect' => '',
    ), $atts );
    if ($a['button_text'] == '') {
        $button_text = 'Subscribe To Our Mailing List';
    } else {
        $button_text = $a['button_text'];
    }

    if ($a['placeholder'] == '') {
        $placeholder = 'Enter Your Email Address Here';
    } else {
        $placeholder = $a['placeholder'];
    }

    if (strpos($a['subdomain'], '.') !== false) {
        $subdomain = $a['subdomain'];
    }
    else {
      $subdomain = $a['subdomain'] . '.clickfunnels.com';
    }

    $js_file_url = plugins_url( 'jquery.js', __FILE__ );

    return "<div id='clickoptin_cf_wrapper_".$a['id']."' class='clickoptin_".$a['theme_style']."'>
    <input type='text' id='clickoptin_cf_email_".$a['id']."' placeholder='".$placeholder."' class='clickoptin_".$a['input_icon']."' />
    <span class='clickoptin_".$a['button_color']."' id='clickoptin_cf_button_".$a['id']."'>".$button_text."</span>
</div>
<script>
    if (!window.jQuery) {
      var jq = document.createElement('script'); jq.type = 'text/javascript';
      jq.src = '" . $js_file_url . "';
      document.getElementsByTagName('head')[0].appendChild(jq);
      var jQueries = jQuery.noConflict();

        jQueries(document).ready(function($) {
            jQueries( '#clickoptin_cf_button_".$a['id']."' ).click(function() {
                var check_email = jQueries( '#clickoptin_cf_email_".$a['id']."' ).val();
                if (check_email != '' && /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(check_email)) {
                    jQueries( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_green');
                    if('".$a['redirect']."' == 'newtab') {
                        window.open('https://".$subdomain."/instant_optin/".$a['id']."/'+jQueries( '#clickoptin_cf_email_".$a['id']."' ).val(), '_blank');
                    }
                    else {
                        window.location.href = 'https://".$subdomain."/instant_optin/".$a['id']."/'+jQueries( '#clickoptin_cf_email_".$a['id']."' ).val();
                    }
                }
                else {
                   jQueries( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_red');
                }
            });
        });
    }
    else {
      var jq = document.createElement('script'); jq.type = 'text/javascript';
      jq.src = '" . $js_file_url . "';
      document.getElementsByTagName('head')[0].appendChild(jq);
      var $ = jQuery.noConflict();

        $(document).ready(function($) {
            $( '#clickoptin_cf_button_".$a['id']."' ).click(function() {
                var check_email = $( '#clickoptin_cf_email_".$a['id']."' ).val();
                if (check_email != '' && /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(check_email)) {
                    $( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_green');
                    if('".$a['redirect']."' == 'newtab') {
                        window.open('https://".$subdomain."/instant_optin/".$a['id']."/'+$( '#clickoptin_cf_email_".$a['id']."' ).val(), '_blank');
                    }
                    else {
                        window.location.href = 'https://".$subdomain."/instant_optin/".$a['id']."/'+$( '#clickoptin_cf_email_".$a['id']."' ).val();
                    }
                }
                else {
                   $( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_red');
                }
            });
        });
    }


</script>
<style>
    #clickoptin_cf_wrapper_".$a['id']." * {
        margin: 0;
        padding: 0;
        position: relative;
        font-family: Helvetica, sans-serif;
    }
    #clickoptin_cf_wrapper_".$a['id']." {
        padding: 5px 15px;
        border-radius: 4px;
        width: 100%;
        margin: 20px 0;
    }
    #clickoptin_cf_wrapper_".$a['id'].".clickoptin_dropshadow_off {
        box-shadow: none;
    }
    #clickoptin_cf_email_".$a['id']." {
        display: block;
        background: #fff;
        color: #444;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        font-size: 15px;
        border: 2px solid #eee;
        text-align: left;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_show {
        background: #fff url(https://cdn2.iconfinder.com/data/icons/ledicons/email.png) no-repeat right;
        background-position: 97% 50%;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_cf_email_red {
        border: 2px solid #E54E3F;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_cf_email_green {
        border: 2px solid #339933;
    }
    #clickoptin_cf_button_".$a['id']." {
        display: block;
        font-weight: bold;
        background: #0166AE;
        border: 1px solid #01528B;
        border-bottom: 3px solid #01528B;
        color: #fff;
        border-radius: 5px;
        padding: 8px;
        width: 100%;
        font-size: 16px;
        margin-top: 8px;
        cursor: pointer;
        text-align: center;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_red {
        background: #F05A38;
        border: 1px solid #D85132;
        border-bottom: 3px solid #D85132;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_green {
        background: #339933;
        border: 1px solid #2E8A2E;
        border-bottom: 3px solid #2E8A2E;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_black {
        background: #23282D;
        border: 1px solid #111;
        border-bottom: 3px solid #111;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_grey {
        background: #fff;
        color: #0166AE;
        border: 1px solid #eee;
        border-bottom: 3px solid #eee;
    }
</style>";
}
add_shortcode( 'clickfunnels_clickoptin', 'clickfunnels_clickoptin' );

// ****************************************************************************************************************************
// ClickFunnels Shortcode Widget
add_filter('widget_text', 'do_shortcode');
class clickfunnels_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'clickfunnels_widget',
            __('ClickFunnels Shortcode', 'clickfunnels_widget_domain'),
            array( 'description' => __( 'Paste your ClickFunnels shortcodes here to embed an iframe, a ClickPop link or show a ClickForm box in your sidebar or footer.', 'clickfunnels_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $shortcode = apply_filters( 'widget_title', $instance['shortcode'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) ) echo '<h3 style="text-align: center;">'.$title.'</h3>';
        if ( ! empty( $shortcode ) ) echo do_shortcode(htmlspecialchars_decode(($shortcode)));
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( '', 'clickfunnels_widget_domain' );
        }
        if ( isset( $instance[ 'shortcode' ] ) ) {
            $shortcode = $instance[ 'shortcode' ];
        }
        else {
            $shortcode = __( '', 'clickfunnels_widget_domain' );
        }
        // Widget admin form
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Headline:' ); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'shortcode' ); ?>"><?php _e( 'Shortcode:' ); ?></label>
                <textarea style="height: 130px;font-size: 12px;color: #555;" class="widefat" id="<?php echo $this->get_field_id( 'shortcode' ); ?>" name="<?php echo $this->get_field_name( 'shortcode' ); ?>" ><?php echo esc_attr( $shortcode ); ?></textarea>
            </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ?  $new_instance['title']  : '';
        $instance['shortcode'] = ( ! empty( $new_instance['shortcode'] ) ) ? $new_instance['shortcode']  : '';
        return $instance;
    }
}



function clickfunnels_widget_load() {
    register_widget( 'clickfunnels_widget' );
}
add_action( 'widgets_init', 'clickfunnels_widget_load' );


// Check for agency check if null make it ''
function clickfunnels_plugin_activated() {
    if (!get_option('clickfunnels_agency_group_tag')) {
        update_option('clickfunnels_agency_group_tag', 'off');
    }
}
register_activation_hook( __FILE__, 'clickfunnels_plugin_activated' );

// Pretty up the manage CF pages area
add_action('all_admin_notices', 'clickfunnels_edit_page_settings');
function clickfunnels_edit_page_settings() {
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if (isset($_GET['post_type']) and $_GET['post_type'] == 'clickfunnels' && strpos($url,'edit.php') !== false && !isset($_GET['page'])) {
    ?>
        <script>
            jQuery(function() {
                jQuery('.wrap h1').attr('style', 'font-weight: bold;');
                jQuery('.wrap h1').first().prepend('<img src="https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png" style="margin-right: 5px;margin-bottom: -7px" />');
                jQuery('.wrap h1').first().append('<a href="https://support.clickfunnels.com/support/solutions/5000164139" target="_blank" class="page-title-action">Support Desk</a>');
                jQuery('.wrap h1').first().append('<a href="#" target="_blank" id="cf_updatemetadatas" class="page-title-action">Check Meta Data</a>');
            });
        </script>
    <?php
    }
}

// Click Refresh Pages Button
add_action('all_admin_notices', 'clickfunnels_check_meta_data');
function clickfunnels_check_meta_data() {
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if (isset($_GET['post_type']) and $_GET['post_type'] == 'clickfunnels' && strpos($url,'edit.php') !== false && !isset($_GET['page'])) {
    ?>
        <style>#cf_metaupdate { width: 75px; font-size: 11px; color: #777; } #cf_thepage { width: 100px;}</style>
        <script>
            jQuery(function() {
                 jQuery('#cf_updatemetadatas').click(function(e) {
                    e.preventDefault();
                    jQuery('.cf_metaupdate img').fadeIn();

                    // Generate Links to Test
                    var url_start = 'https://api.clickfunnels.com/funnels/';
                    var url_end = '.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
                    var urls = [];
                    get_urls( jQuery( '.metainfo_id' ).toArray() );
                    function get_urls( divs ) {
                      for ( var i = 0; i < divs.length; i++ ) {
                        urls.push( url_start+divs[ i ].innerHTML+url_end+'{#}'+divs[ i ].getAttribute('data-id')+'{#}'+divs[ i ].getAttribute('data-pageid')+'{#}'+divs[ i ].getAttribute('data-pagekey'));
                      }
                    }
                    // Ping URL
                    var request_image = function(url) {
                        return new Promise(function(resolve, reject) {
                          var img = new Image();
                          img.onload = function() { resolve(img); };
                          img.onerror = function() { reject(url); };
                          img.src = url + '?random-no-cache=' + Math.floor((1 + Math.random()) * 0x10000).toString(16);
                        });
                      };
                      var ping = function(url) {
                        return new Promise(function(resolve, reject) {
                          var start = (new Date()).getTime();
                          var response = function() {
                              var delta = ((new Date()).getTime() - start);
                              delta /= 4;
                              resolve(delta);
                          };
                          request_image(url).then(response).catch(response);
                          setTimeout(function() { reject(Error('Timeout')); }, 5000);
                        });
                      };


                    // Loop through urls and get a JSON request to match meta data.
                    for (var i=0;i<urls.length;i++) {
                        data = urls[i].split('{#}');
                        url = data[0];
                        id = data[1];
                        pageid = data[2];
                        pagekey = data[2];
                        GetJSONResult(url, id, pageid, pagekey);
                    }
                    function GetJSONResult(url, postid, pageid, pagekey)
                    {
                       jQuery.getJSON(url,
                        function(data){
                            jQuery.each(data.funnel_steps, function() {
                                if ( this.wp_friendly == true && this.pages != '' && this.id == pageid ) {

                                    if (encodeURI(this.pages[0].metatags) == jQuery(".metainfo_check[data-id='"+postid+"']").html()) {
                                        var do_ping_error = function() {
                                            ping('https://api.clickfunnels.com/s3_proxy/'+pagekey+'?preview=true').then(function(delta) {}).catch(function(error) {});
                                        };
                                        jQuery(".loadingcf_meta[data-id='"+postid+"']").attr('style', 'color: #339933');
                                        jQuery(".loadingcf_meta[data-id='"+postid+"']").html('Success');
                                        setTimeout(function() {
                                            jQuery(".loadingcf_meta[data-id='"+postid+"']").fadeOut();
                                        }, 2500);

                                        do_ping_error();
                                    }
                                    else {
                                        var do_ping = function() {
                                           ping('https://api.clickfunnels.com/s3_proxy/'+pagekey+'?preview=true').then(function(delta) {}).catch(function(error) {});
                                        };
                                        do_ping();
                                        jQuery(".loadingcf_meta[data-id='"+postid+"']").attr('style', 'color: #3C9BD5');
                                        jQuery(".loadingcf_meta[data-id='"+postid+"']").parent().parent().attr('style', 'color: #FFFDD1');
                                        jQuery(".loadingcf_meta[data-id='"+postid+"']").html('<a href="post.php?post='+postid+'&action=edit&updatemeta=true">Update</a>');
                                    }
                                }
                            })
                        }).done(function() {
                            // show a message
                        });
                    }
                });
            });
        </script>
    <?php
    }
}

