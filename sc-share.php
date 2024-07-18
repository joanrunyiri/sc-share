<?php
/**
 * Plugin Name: SC-NUTSHELL SHARE PLUGIN
 * Description: Plugin to generate sharing link for sc-nutshell
 * Version: 1.0
 */

function sc_nutshell_shortcode($atts=[],$content=null,$tag='') {
    global $wpdb;
    //$user_id="347";
    $user_id=get_current_user_id();
    $html="<div>";
    if ($user_id==0){
        $html.="<b>You must be logged in to get the share link</b>";
    }else{
        $site_url=get_site_url();
        $icons_url=$site_url."/share/icons/";

        $short_table=$wpdb->prefix."share_short";
        $sql="SELECT * FROM ".$short_table." WHERE id=%d";
        $vars=[$atts["id"]];
        $short_codes=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
        $form_id="0";
        if ($short_codes!=NULL && count($short_codes)>0){
            $form_id=$short_codes[0]["form_id"];
        }

        $sql="SELECT * FROM ".$wpdb->prefix."frm_items WHERE form_id=%d AND user_id=%d ORDER BY updated_at DESC LIMIT 0,1";
        $vars=[$form_id,$user_id];

        $items=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
        $item_id="0";
        if ($items!=NULL && count($items)>0){
            $item_id=$items[0]["id"];
        }
        // $full_link = $site_url . "/share/sc/".$user_id. "/".$atts["id"]."/".$atts2["id"]."/".$item_id."/".time();

        $full_link=$site_url."/share/sc/".$user_id."/".$atts["id"]."/".$item_id."/".time();
        $settings_table=$wpdb->prefix."share_settings";
        $sql="SELECT * FROM ".$settings_table;
        $vars=[];
        $settings=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
        $share_text=$settings[0]["share_text"];

        // Output Twitter Card meta tags
        echo '<meta name="twitter:card" content="summary_large_image" />';
        echo '<meta name="twitter:title" content="' . esc_attr($share_text) . '" />';
        echo '<meta name="twitter:description" content="please add text' . esc_attr($share_text) . '" />';
        echo '<meta name="twitter:image" content="' . esc_url($full_link) . '" />';
        echo '<meta name="twitter:align" content="right" />';




        //$html.="<div><b>Share this link on social media</b></div>";
        $html.="<div><b>".$share_text."</b></div>";
        $html.=$full_link;
        $enc_link=urlencode($full_link);
        //https://www.facebook.com/sharer/sharer.php?u=https://sc-share.com
        //https://twitter.com/intent/tweet?url=https://sc-share.com
        //https://pinterest.com/pin/create/button/?url=https://sc-share.com
        //https://www.linkedin.com/shareArticle?mini=true&url=https://sc-share.com
        //social rocket buttons
        $html.="<div style='margin-top: 10px;'>";
        $html.="<div><br></div>";
        $html .= '<div id="share-icons-container" style="margin-top: 10px; display: inline-block;">';
        $html .= '<div id="facebookIcon" style="display: inline-block;">';
        $html .= get_sn_share_tag("facebook.png","https://www.facebook.com/share.php?u=".$enc_link);
        $html .= '</div>';
//$html.=get_sn_share_tag("instagram.png","https://www.instagram.com");
        $html .= '<div id="twitterIcon" style="display: inline-block;">';
        $html .= get_sn_share_tag("twitter2.png","https://twitter.com/intent/tweet?url=".$enc_link);


        $html .= '</div>';
//$html.=get_sn_share_tag("reddit.png","https://reddit.com/submit?url=".$enc_link);
        $html .= '<div id="linkedinIcon" style="display: inline-block;">';
        $html .= get_sn_share_tag("linkedin.png","https://www.linkedin.com/cws/share?url=".$enc_link);
        $html .= '</div>';


        // $html.='<div class="social-rocket-inline-buttons">';
        // //$html.='<h4 class="social-rocket-buttons-heading">Share</h4>';
        // $html.='<div class="social-rocket-buttons">';
        // $html.='<div class="social-rocket-button social-rocket-button-square social-rocket-facebook " data-network="facebook"><a class="social-rocket-button-anchor" href="http://www.facebook.com/share.php?u="'.$enc_link.'"  target="_blank" aria-label="Share"><i class="fab fa-facebook-f social-rocket-button-icon"></i><span class="social-rocket-button-cta">Share</span></a></div>';
        // $html.='<div class="social-rocket-button social-rocket-button-square social-rocket-linkedin " data-network="linkedin"><a class="social-rocket-button-anchor" href="https://www.linkedin.com/cws/share?url='.$enc_link.'"  target="_blank" aria-label="Share"><i class="fab fa-linkedin-in social-rocket-button-icon"></i><span class="social-rocket-button-cta">Share</span></a></div>';
        // $html.='<div class="social-rocket-button social-rocket-button-square social-rocket-reddit " data-network="reddit"><a class="social-rocket-button-anchor" href="http://reddit.com/submit?url='.$enc_link.'"  target="_blank" aria-label="Share"><i class="fab fa-reddit-alien social-rocket-button-icon"></i><span class="social-rocket-button-cta">Share</span></a></div>';
        // $html.='<div class="social-rocket-button social-rocket-button-square social-rocket-twitter " data-network="twitter"><a class="social-rocket-button-anchor" href="https://twitter.com/intent/tweet?url='.$enc_link.'"  target="_blank" aria-label="Tweet"><i class="fab fa-twitter social-rocket-button-icon"></i><span class="social-rocket-button-cta">Tweet</span></a></div>';
        // $html.='</div>'; //social-rocket-buttons end
        // $html.='</div>'; //social-rocket-inline-buttons end
        $html.="</div>";
        //social rocket buttons end
    }
    $html.="</div>";



    return $html;
}

function get_sn_share_tag($icon_file,$url){
    $site_url=get_site_url();
    $icon_url=$site_url."/share/icons/".$icon_file;
    $html="";
    $html.="<a target='_blank' href='".$url."'>";

    $html.="<img src='".$icon_url."' style='width: 30px;height: 30px;' />";
    $html.="</a>";
    $html.="&nbsp;&nbsp;";
    return $html;
}
function sc_nutshell_enqueue_scripts() {
// Enqueue jQuery from Google CDN
    wp_enqueue_script('google-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null, true);

// Enqueue your custom script
    wp_enqueue_script('sc-nutshell-js', plugin_dir_url(__FILE__) . 'js/sc-nutshell-script.js', array('google-jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'sc_nutshell_enqueue_scripts');

function sc_nutshell_shortcodes_init() {
    add_shortcode('sc-nutshell','sc_nutshell_shortcode');
}

add_action('init','sc_nutshell_shortcodes_init');
include "admin/init.php";
?>