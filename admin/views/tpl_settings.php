<?php
$file_relative_path="../../../../../share";
$thumbnail=$file_relative_path."/thumbnail.jpg";
$watermark=$file_relative_path."/watermark.png";
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
        width: calc(25% - 5px);
        margin-right: 5px;
    }
    .input-field.less-half.right {
        float: right;
        margin-right: 0;
    }
</style>









<div class="wrap">
    <h1>Settings</h1>
    <?php if(isset($_SESSION['sc_nutshell_settings_error'])){ ?>
        <p class="alert alert-danger">
            <?=$_SESSION['sc_nutshell_settings_error']; ?>
        </p>
        <?php unset($_SESSION['sc_nutshell_settings_error']);?>
    <?php } ?>
    <?php if(isset($_SESSION['sc_nutshell_settings_message'])){ ?>
        <p class="alert alert-success">
            <?=$_SESSION['sc_nutshell_settings_message']; ?>
        </p>
        <?php unset($_SESSION['sc_nutshell_settings_message']);?>
    <?php } ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="<?=$SC_NUTSHELL_SETTINGS_ACTION;?>">
        <div class="half-fields-container">
            <div class="input-field half">
                <div class="label">Share Link Text</div>
                <div class="field">
                    <input type="text" autocomplete="off" name="share_text" value="<?=$share_text;?>" />
                </div>
            </div>
        </div>
        <div class="half-fields-container">
            <div class="input-field half">
                <div class="label">Thumbnail image for missing user profile pic</div>
                <?php if ($thumbnail!=""){ ?>
                    <div style="margin-top: 10px;">
                        <img src="<?=$thumbnail;?>?x=<?=time();?>" style="max-width: 90%;" />
                    </div>
                <?php } ?>
                <div class="field">
                    <input type="file" autocomplete="off" name="thumbnail" accept="image/jpeg" />
                </div>
            </div>
            <div class="input-field half right">
                <div class="label">Watermark image</div>
                <?php if ($watermark!=""){ ?>
                    <div style="margin-top: 10px;">
                        <img src="<?=$watermark;?>?x=<?=time();?>" style="max-width: 90%;" />
                    </div>

                <?php } ?>
                <div class="field">
                    <input type="file" autocomplete="off" name="watermark" accept="image/png" />
                </div>
            </div>
        </div>
        <?php
        wp_nonce_field($SC_NUTSHELL_SETTINGS_ACTION,$SC_NUTSHELL_SETTINGS_NONCE_FIELD);
        submit_button();
        ?>
    </form>
    <button id="facebook_show" onclick="toggleFacebook()" type="button">Facebook Button is On</button>
    <button id="twitter_show" onclick="toggleTwitter()" type="button">Twitter Button is On</button>
    <button id="linkedin_show" onclick="toggleLinkedIn()" type="button">LinkedIn Button is On</button>
</div>
<script>
    // Set the initial state of the buttons when the page loads
    window.onload = function() {
        setButtonState('facebookIconState', 'facebook_show', 'Facebook');
        setButtonState('twitterIconState', 'twitter_show', 'Twitter');
        setButtonState('linkedinIconState', 'linkedin_show', 'LinkedIn');
    };

    function setButtonState(storageKey, buttonId, networkName) {
        var state = localStorage.getItem(storageKey);
        var button = document.getElementById(buttonId);
        button.innerHTML = state === 'visible' ? networkName + ' Button is Off' : networkName + ' Button is On';
    }

    function toggleFacebook() {
        var button = document.getElementById("facebook_show");
        var iconState = localStorage.getItem('facebookIconState');
        var newState = iconState === 'hidden' ? 'visible' : 'hidden';
        localStorage.setItem('facebookIconState', newState);
        button.innerHTML = newState === 'visible' ? 'Facebook Button is Off' : 'Facebook Button is On';
    }

    function toggleTwitter() {
        var button = document.getElementById("twitter_show");
        var iconState = localStorage.getItem('twitterIconState');
        var newState = iconState === 'hidden' ? 'visible' : 'hidden';
        localStorage.setItem('twitterIconState', newState);
        button.innerHTML = newState === 'visible' ? 'Twitter Button is Off' : 'Twitter Button is On';
    }

    function toggleLinkedIn() {
        var button = document.getElementById("linkedin_show");
        var iconState = localStorage.getItem('linkedinIconState');
        var newState = iconState === 'hidden' ? 'visible' : 'hidden';
        localStorage.setItem('linkedinIconState', newState);
        button.innerHTML = newState === 'visible' ? 'LinkedIn Button is Off' : 'LinkedIn Button is On';
    }
</script>



