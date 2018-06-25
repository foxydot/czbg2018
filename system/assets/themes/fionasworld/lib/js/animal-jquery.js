jQuery(function($){
    var body = $('.single-animals');
    $(window).scroll(function(){
        var s = $(window).scrollTop();
        if (s >= $('.site-header').height()) {
            body.addClass("fixed-header");
        } else {
            body.removeClass("fixed-header");
        }
    });
});