<?php
?>
<style type="text/css">
    .input-field {
        width: 100%;
        margin-bottom: 10px;
    }
    .input-field .field {
        margin-top: 2px;
    }
    .input-field .field input {
        width: 100%;
    }
    .half-fields-container::after {
        display: block;
        content: " ";
        height: 0;
        clear: both;
    }
    .input-field.half {
        float: left;
        width: calc(50% - 5px);
        margin-right: 10px;
    }
    .input-field.half.right {
        float: right;
        margin-right: 0;
    }

    .input-field.less-half {
        float: left;
        width: calc(15% - 5px);
        margin-right: 5px;
    }
    .input-field.less-half.right {
        float: right;
        margin-right: 0;
    }
</style>

<div class="wrap">
    <?php if($action=="add"){ ?>
        <h1>Add New Short Code</h1>
    <?php } else { ?>
        <h1>Edit Short Code</h1>
    <?php } ?>
    
    <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="<?=$SC_NUTSHELL_CODE_FORM_ACTION;?>">
        <input type="hidden" name="id" value="<?=$id;?>" />
        <div class="half-fields-container">
            <div class="input-field half">
                <div class="label">Exercise name</div>
                <div class="field">
                    <input type="text" autocomplete="off" name="exercise_name" value="<?=$exercise_name;?>" />
                </div>
            </div>
            <div class="input-field half right">
                <div class="label">Title (to display on share)</div>
                <div class="field">
                    <input type="text" autocomplete="off" name="title" value="<?=$title;?>" />
                </div>
            </div>
        </div>
        <div class="input-field">
            <div class="label">Url</div>
            <div class="field">
                <input type="url" autocomplete="off" name="url" value="<?=$url;?>" />
            </div>
        </div>
        <div class="half-fields-container">
            <div class="input-field less-half">
                <div class="label">Formidable Form Id</div>
                <div class="field">
                    <input type="tel" autocomplete="off" name="form_id" value="<?=$form_id;?>" />
                </div>
            </div>
            <div class="input-field" style="float: left;width: 78%;">
                <div id="formFieldsContainers">
                    <?php foreach ($form_fields as $f) { ?>
                    <div class="half-fields-container">
                        <div class="input-field less-half">
                            <div class="label">Field Title</div>
                            <div class="field">
                                <input type="text" autocomplete="off" name="field_title[]" value="<?=$f["field_title"];?>" />
                            </div>
                        </div>
                        <div class="input-field less-half">
                            <div class="label">Field Id</div>
                            <div class="field">
                                <input type="tel" autocomplete="off" name="field_id[]" value="<?=$f["field_id"];?>" />
                            </div>
                        </div>
                        <div class="input-field less-half">
                            <div class="label">Unit Field Id</div>
                            <div class="field">
                                <input type="tel" autocomplete="off" name="unit_field_id[]" value="<?=$f["unit_field_id"];?>" />
                            </div>
                        </div>
                        <div class="input-field less-half">
                            <div class="label">Cond. Field Id</div>
                            <div class="field">
                                <input type="tel" autocomplete="off" name="cond_field_id[]" value="<?=$f["cond_field_id"];?>" />
                            </div>
                        </div>
                        <div class="input-field less-half">
                            <div class="label">Condition</div>
                            <div class="field">
                                <select name="cond_field_condition[]" class="form-control" style="width: 100%">
                                    <option value="yes"<?=($f["cond_field_condition"]=="yes"?" SELECTED":"");?>>Yes</option>
                                    <option value="no"<?=($f["cond_field_condition"]=="no"?" SELECTED":"");?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-field less-half">
                            <div class="label">Title Field Id</div>
                            <div class="field">
                                <input type="text" autocomplete="off" name="title_field_id[]" value="<?=$f["title_field_id"];?>" />
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="input-field">
                    <div class="field">
                        <button type="button" class="button button-primary" onclick="addFormField();">+ ADD FIELD</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        wp_nonce_field($SC_NUTSHELL_CODE_FORM_ACTION,$SC_NUTSHELL_CODE_FORM_NONCE_FIELD);
        submit_button();
        ?>
    </form>
    <?php if ($short_code!=""){ ?>
    <div style="margin-top: 20px;background-color: #FFFFFF;padding: 15px;">
        <div><?=$short_code;?></div>
        <div style="margin-top: 10px;">
            <?php
            $site_url=get_site_url();
            $user_id=get_current_user_id();
            $shareUrl=$site_url."/share/sc/".$user_id."/".$id."/".time();
            ?>
            <a target="_blank" href="<?=$shareUrl;?>"><?=$shareUrl;?></a>
        </div>
    </div>
    <?php } ?>
    <script type="text/javascript">
        function addFormField(){
            let html='<div class="half-fields-container">';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Field Title</div>';
            html+='<div class="field">';
            html+='<input type="text" autocomplete="off" name="field_title[]" value="" />';
            html+='</div>';
            html+='</div>';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Field Id</div>';
            html+='<div class="field">';
            html+='<input type="tel" autocomplete="off" name="field_id[]" value="" />';
            html+='</div>';
            html+='</div>';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Unit Field Id</div>';
            html+='<div class="field">';
            html+='<input type="tel" autocomplete="off" name="unit_field_id[]" value="" />';
            html+='</div>';
            html+='</div>';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Cond. Field Id</div>';
            html+='<div class="field">';
            html+='<input type="tel" autocomplete="off" name="cond_field_id[]" value="" />';
            html+='</div>';
            html+='</div>';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Cond. Field Id</div>';
            html+='<div class="field">';
            html+='<select name="cond_field_condition[]" class="form-control" style="width: 100%;">';
            html+='<option value="yes">Yes</option>';
            html+='<option value="no">No</option>';
            html+='</select>';
            html+='</div>';
            html+='</div>';
            html+='<div class="input-field less-half">';
            html+='<div class="label">Title Field Id</div>';
            html+='<div class="field">';
            html+='<input type="text" autocomplete="off" name="title_field_id[]" value="" />';
            html+='</div>';
            html+='</div>';
            html+='</div>';
            jQuery("#formFieldsContainers").append(html);
        }
    </script>
</div>