$(document).ready(function ($) {
    var timer;
    $("#createpost").find('input[name=post_title], input[name=post_text]').on('input change', function () {
        clearTimeout(timer); // Clear the timer so we don't end up with dupes.
        timer = setTimeout(function () { // assign timer a new timeout
            $("#createpost").find('input[name=post_title]').valid();
            $("#createpost").find('input[name=post_text]').valid();
        }, 500);
    });

    var validators = page.validators.createpost;
    $("#createpost").ufForm({
        validators: validators,
        msgTarget: $("#alerts-page"),
        keyupDelay: 500
    }).on("submitSuccess.ufForm", function () {
        window.location.reload();
    });
});
