$(document).ready(function () {
    // Autovalidate username field on a delay
    var timer;
    $("#createblog").find('input[name=blog_name]').on('input change', function () {
        clearTimeout(timer); // Clear the timer so we don't end up with dupes.
        timer = setTimeout(function () { // assign timer a new timeout
            $("#createblog").find('input[name=blog_name]').valid();
        }, 500);
    });

    var validators = page.validators.createblog;
    $("#createblog").ufForm({
        validators: validators,
        msgTarget: $("#alerts-page"),
        keyupDelay: 500
    }).on("submitSuccess.ufForm", function () {
        window.location.replace('my_blog');
    });
});