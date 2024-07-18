<?php

$SC_NUTSHELL_CODE_PAGE_SLUG="sc_nutshell_share_code";
$SC_NUTSHELL_CODE_FORM_ACTION="sc_nutshell_short_code_form_code_response";
$SC_NUTSHELL_CODE_FORM_NONCE_FIELD="sc_nutshell_short_code_nonce";
$SC_NUTSHELL_SETTINGS_ACTION="sc_nutshell_settings_response";
$SC_NUTSHELL_SETTINGS_NONCE_FIELD="sc_nutshell_settings_nonce";

function sc_nutshell_share_admin_list_codes(){
    global $wpdb;
    global $SC_NUTSHELL_CODE_PAGE_SLUG;
    $short_table=$wpdb->prefix."share_short";
    $sql="SELECT * FROM ".$short_table."  ORDER BY exercise_name";
    //$vars = ['publish'];
    $vars=[];
    $short_codes=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
    include "views/tpl_list.php";
}

function sc_nutshell_share_admin_add_edit_code($action){
    global $SC_NUTSHELL_CODE_PAGE_SLUG;
    global $SC_NUTSHELL_CODE_FORM_ACTION;
    global $SC_NUTSHELL_CODE_FORM_NONCE_FIELD;
    $form_fields=array();
    $id="";
    $exercise_name="";
    $title="";
    $url="";
    $form_id="";
    $short_code="";
    if ($action=="edit"){
        if (!isset($_GET["id"])){
            //die here
            echo "<h1>No id present</h1>";
            exit();
        }
        $id=$_GET["id"];
        $sh=sc_nutshell_code_get_from_db($id);
        if ($sh==FALSE){
            echo "<h1>No short code found</h1>";
            exit();
        }
        $exercise_name=$sh["exercise_name"];
        $title=$sh["title"];
        $url=$sh["url"];
        $form_id=$sh["form_id"];
        $short_code=$sh["short_code"];
        $form_fields=json_decode($sh["short_fields"],TRUE);
        for ($a=0;$a<count($form_fields);$a++){
            $ff=$form_fields[$a];
            if (!isset($ff["cond_field_id"])){
                $ff["cond_field_id"]="";
                $ff["cond_field_condition"]="";
            }
            if (!isset($ff["unit_field_id"])){
                $ff["unit_field_id"]="";
            }
            $form_fields[$a]=$ff;
        }

    }
    $form_fields[]=array("field_title"=>"","field_id"=>"","unit_field_id"=>"","cond_field_id"=>"","cond_field_condition"=>"");
    include "views/tpl_add_edit.php";
}

function sc_nutshell_share_admin_init(){
    $action="";
    if (isset($_GET["action"])){
        $action=$_GET["action"];
    }
    if ($action=="add" || $action=="edit"){
        sc_nutshell_share_admin_add_edit_code($action);
    }else{
        sc_nutshell_share_admin_list_codes();
    }
}

function sc_nutshell_share_admin_settings_init(){
    global $wpdb;
    global $SC_NUTSHELL_CODE_PAGE_SLUG;
    global $SC_NUTSHELL_SETTINGS_ACTION;
    global $SC_NUTSHELL_SETTINGS_NONCE_FIELD;
    $settings_table=$wpdb->prefix."share_settings";
    $sql="SELECT * FROM ".$settings_table;
    $vars=[];
    $settings=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
    $share_text=$settings[0]["share_text"];
    include "views/tpl_settings.php";
}

function sc_nutshell_plugin_setup_admin_menu(){
    global $SC_NUTSHELL_CODE_PAGE_SLUG;
    add_menu_page('SC-NUTSHELL SHARE','Sc Share','manage_options',$SC_NUTSHELL_CODE_PAGE_SLUG,'sc_nutshell_share_admin_init','',6);
    add_submenu_page($SC_NUTSHELL_CODE_PAGE_SLUG,'Sc Share Settings','Settings','manage_options',$SC_NUTSHELL_CODE_PAGE_SLUG."_settings",'sc_nutshell_share_admin_settings_init');
}

include "functions.php";

add_action('admin_menu','sc_nutshell_plugin_setup_admin_menu');
$code_form_action="admin_post_".$SC_NUTSHELL_CODE_FORM_ACTION;
add_action($code_form_action,'sc_nutshell_short_code_save');

$sc_settings_action="admin_post_".$SC_NUTSHELL_SETTINGS_ACTION;
add_action($sc_settings_action,'sc_nutshell_short_settings_save');
?>