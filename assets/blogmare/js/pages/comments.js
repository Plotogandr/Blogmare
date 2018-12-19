$(document).ready(function () {
    $('.replyForm').hide();
    $(document).on("click", ".btn", function () {
        var commentId = "#" + ($(this).attr('id'));
        $(commentId + '.replyForm').toggle();
    });
});