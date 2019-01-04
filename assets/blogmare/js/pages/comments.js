$(document).ready(function () {
    $('.editForm').hide();
    $('.replyForm').hide();
    $(document).on('click', ".edit", function () {
        $(".editForm").not("#" + $(this).attr('id')).slideUp();
        $(".replyForm").not("#" + $(this).attr('id')).slideUp();
        var commentId = "#" + ($(this).attr('id'));
        $(commentId + '.editForm').slideToggle();
    });
    $(document).on("click", ".reply", function () {
        $(".replyForm").not("#" + $(this).attr('id')).slideUp();
        $(".editForm").not("#" + $(this).attr('id')).slideUp();
        var commentId = "#" + ($(this).attr('id'));
        $(commentId + '.replyForm').slideToggle();
    });

});
