jQuery(document).ready(function($) {
    $('.section-zoo-news .section-title h3').html(function(){
        var str = $(this).html().trim();
        var h = str.split(" ");
        console.log(h);
        return '<span class="lg">' + h[0] + '</span><span class="sm">' + h[1] + '</span>';
    });
});