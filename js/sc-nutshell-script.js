jQuery(document).ready(function ($) {
    var icon_status = localStorage.getItem('facebookIconState');
    console.log('Facebook Icon Status:', icon_status);
    // Hide or show Facebook icon based on icon_status
    if (icon_status === "visible") {
        $("#facebookIcon").hide();
    } else if (icon_status === "hidden") {
        $("#facebookIcon").show();
    }

    // Retrieve Twitter icon state from localStorage
    var twitter_status = localStorage.getItem('twitterIconState');

    // Hide or show Twitter icon based on twitter_status
    if (twitter_status === "visible") {
        $("#twitterIcon").hide();

    } else if (twitter_status === "hidden") {
        $("#twitterIcon").show();
    }

    // Retrieve LinkedIn icon state from localStorage
    var linkedin_status = localStorage.getItem('linkedinIconState');

    // Hide or show LinkedIn icon based on linkedin_status
    if (linkedin_status === "visible") {
        $("#linkedinIcon").hide();
    } else if (linkedin_status === "hidden") {
        $("#linkedinIcon").show();
    }
});


