<?php
function generate_short_code($id){
    return "[sc-nutshell id=\"".$id."\"]";
}

function sc_nutshell_get_code_from_post(){
    $id=trim($_POST["id"]);
    $exercise_name=trim($_POST["exercise_name"]);
    $title=trim($_POST["title"]);
    $url=trim($_POST["url"]);
    $form_id=trim($_POST["form_id"]);
    $field_title=$_POST["field_title"];
    $field_id=$_POST["field_id"];
    $unit_field_id=$_POST["unit_field_id"];
    $cond_field_id=$_POST["cond_field_id"];
    $cond_field_condition=$_POST["cond_field_condition"];
    $title_field_id=$_POST["title_field_id"];
    $form_fields=array();
    $findex=0;
    foreach ($field_title as $t){
        $i=trim($field_id[$findex]);
        if (trim($t)=="" || $i==""){
            $findex++;
            continue;
        }
        $ui=trim($unit_field_id[$findex]);
        $ci=trim($cond_field_id[$findex]);
        $cfc=trim($cond_field_condition[$findex]);
        $tfi=trim($title_field_id[$findex]);
        $form_fields[]=array("field_title"=>trim($t),"field_id"=>$i,"unit_field_id"=>$ui,"cond_field_id"=>$ci,"cond_field_condition"=>$cfc,"title_field_id"=>$tfi);
        $findex++;
    }
    $short_code="";
    return array(
        "id"=>$id,
        "exercise_name"=>$exercise_name,
        "title"=>$title,
        "url"=>$url,
        "form_id"=>$form_id,
        "form_fields"=>$form_fields,
        "short_code"=>$short_code,
    );
}

function sc_nutshell_code_get_from_db($id){
    global $wpdb;
    $short_table=$wpdb->prefix."share_short";
    $sql="SELECT * FROM ".$short_table."  WHERE id=%s";
    $vars = [$id];
    $short_codes=$wpdb->get_results($wpdb->prepare($sql,$vars),ARRAY_A);
    if (count($short_codes)>0){
        return $short_codes[0];
    }
    return FALSE;
}

function sc_nutshell_short_code_redirect($cv,$msg,$type){
    global $SC_NUTSHELL_CODE_PAGE_SLUG;
    $_SESSION["sc_nutshell_message"]=$msg;
    $_SESSION["sc_nutshell_code"]=$cv;
    $action="add";
    if ($cv["id"]!=""){
        $action="edit";
    }
    $url="admin.php?page=".$SC_NUTSHELL_CODE_PAGE_SLUG."&message=".$type;
    $url.="&action=".$action;
    if ($cv["id"]!=""){
        $url.="&id=".$cv["id"];
    }
    //echo $url;
    wp_redirect(admin_url($url));
    exit();
}

function sc_nutshell_short_code_save(){
    global $wpdb;
    global $SC_NUTSHELL_CODE_PAGE_SLUG,$SC_NUTSHELL_CODE_FORM_ACTION,$SC_NUTSHELL_CODE_FORM_NONCE_FIELD;
    if (!wp_verify_nonce($_POST[$SC_NUTSHELL_CODE_FORM_NONCE_FIELD],$SC_NUTSHELL_CODE_FORM_ACTION)){
        wp_die("Unable to verify nonce");
    }
    $cv=sc_nutshell_get_code_from_post();
    if ($cv["exercise_name"]==""){
        sc_nutshell_short_code_redirect($cv,"Please enter exercise name","error");
    }
    if ($cv["title"]==""){
        sc_nutshell_short_code_redirect($cv,"Please enter title","error");
    }
    if ($cv["url"]==""){
        sc_nutshell_short_code_redirect($cv,"Please enter url","error");
    }
    if ($cv["form_id"]==""){
        sc_nutshell_short_code_redirect($cv,"Please enter form id","error");
    }
    $short_table=$wpdb->prefix."share_short";
    if ($cv["id"]!=""){
        $wpdb->update($short_table, array(
            "exercise_name" => $cv["exercise_name"],
            "form_id" => $cv["form_id"],
            "title" => $cv["title"],
            "short_fields" => json_encode($cv["form_fields"]),
            "url" => $cv["url"],
            "short_code" => $cv["short_code"],
        ),array(
            "id"=>$cv["id"]
        ));
    }else{
        $wpdb->insert($short_table, array(
            "exercise_name" => $cv["exercise_name"],
            "form_id" => $cv["form_id"],
            "title" => $cv["title"],
            "short_fields" => json_encode($cv["form_fields"]),
            "url" => $cv["url"],
            "short_code" => $cv["short_code"],
        ));
        $cv["id"]=$wpdb->insert_id;
    }

    $cv["short_code"]=generate_short_code($cv["id"]);
    $wpdb->update($short_table, array(
        "short_code" => $cv["short_code"],
    ),array(
        "id"=>$cv["id"]
    ));

    $url="admin.php?page=".$SC_NUTSHELL_CODE_PAGE_SLUG;
    wp_redirect(admin_url($url));
    exit();
}

function sc_nutshell_short_settings_save(){
    global $wpdb;
    global $SC_NUTSHELL_CODE_PAGE_SLUG,$SC_NUTSHELL_SETTINGS_ACTION,$SC_NUTSHELL_SETTINGS_NONCE_FIELD;
    if (!wp_verify_nonce($_POST[$SC_NUTSHELL_SETTINGS_NONCE_FIELD],$SC_NUTSHELL_SETTINGS_ACTION)){
        wp_die("Unable to verify nonce");
    }
    $file_relative_path=__DIR__."/../../../../share";
    //echo $file_relative_path."<br />";
    //$files = scandir($file_relative_path);
    //print_r($files);
    //wp_die("hello");
    
    $thumbnail=$file_relative_path."/thumbnail.jpg";
    $watermark=$file_relative_path."/watermark.png";
    $error_msg="";
    try{
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
            if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["size"]>0){
                $size=getimagesize($_FILES["thumbnail"]["tmp_name"]);
                $width=$size[0];
                $height=$size[1];
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"],$thumbnail);
            }
            if (isset($_FILES["watermark"]) && $_FILES["watermark"]["size"]>0){
                move_uploaded_file($_FILES["watermark"]["tmp_name"],$watermark);
            }

            $settings_table=$wpdb->prefix."share_settings";
            $wpdb->update($settings_table, array(
                "share_text" => $_POST["share_text"],
            ),array(
                "id"=>"1"
            ));
        }
        $_SESSION["sc_nutshell_settings_message"]="Settings updated";
    }catch(Exception $ex){
        $error_msg=$ex->getMessage();
        $_SESSION["sc_nutshell_settings_error"]=$error_msg;
        wp_die($error_msg);
    }
    $url="admin.php?page=".$SC_NUTSHELL_CODE_PAGE_SLUG."_settings";
    wp_redirect(admin_url($url));
    exit();
}

?>