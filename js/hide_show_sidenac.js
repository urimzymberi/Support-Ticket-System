if ($(window).width() > 1024) {
    var b = true;
} else {
    var b = false;
}
$("#close_sidenav").click(function () {
    if (b) {
        $("#side_nav").animate(
            {
                marginLeft: "-=200px",
                opacity: "0.5",
            },
            560
        );
        $(".logo1").animate(
            {
                opacity: "1",
            },
            560
        );
        $(".main").animate(
            {
                marginLeft: "0",
            },
            500
        );
        b = false;
    } else {
        $("#side_nav").animate(
            {
                position: "fixed",
                top: "0",
                left: "0",
                margin: "0",
                padding: "0",
                opacity: "1",
            },
            500
        );

        $(".logo1").animate(
            {
                opacity: "0",
            },
            560
        );
        $(".main").animate(
            {
                marginLeft: "200px",
            },
            560
        );
        b = true;
    }
});