var tableOffset = $("#table").offset().top;
var $header = $("#table > thead").clone();
var $fixedHeader = $("#header-fixed").append($header);

$(window).bind("scroll", function() {
    var offset = $(this).scrollTop();

    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
        $fixedHeader.show();
    }
    else if (offset < tableOffset) {
        $fixedHeader.hide();
    }
});
