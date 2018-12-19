$(document).ready(function () {
    $('.replyForm').hide();
    $(document).on("click", ".btn", function () {
        $(".replyForm").not("#" + $(this).attr('id') + ".replyForm").slideUp();
        var commentId = "#" + ($(this).attr('id'));
        $(commentId + '.replyForm').slideToggle();
    });
});