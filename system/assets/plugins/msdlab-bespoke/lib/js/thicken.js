jQuery(document).ready(function($) {
    var link = $("img").parent("a");
    var href = link.attr('href');
    if(href.indexOf('.png') !== -1 || href.indexOf('.jpg') !== -1 || href.indexOf('.jpeg') !== -1 || href.indexOf('.svg') !== -1){

        console.log(href);
        link.addClass("thickbox");
    }
});