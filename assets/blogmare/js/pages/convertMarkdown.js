$(document).ready(function () {
    var converter = Markdown.getSanitizingConverter();
    var text = $("#post-text").text();
    // document.getElementById('post-text');
    $("#post-text").replaceWith(converter.makeHtml(text));
});

