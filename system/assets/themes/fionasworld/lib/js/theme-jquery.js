jQuery(document).ready(function($) {
    $('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');

    $('.section.overlay').wrapInner('<div class="overlay"></div>');


    var numwidgets = $('.footer-widgets-2 section.widget').length;
    switch(numwidgets) {
        case 6:
            $('.footer-widgets-2 section.widget').addClass('col-md-2').addClass('col-sm-6').addClass('col-xs-12');
            break;
        case 4:
            $('.footer-widgets-2 section.widget').addClass('col-md-3').addClass('col-sm-6').addClass('col-xs-12');
            break;
        case 3:
            $('.footer-widgets-2 section.widget').addClass('col-md-4').addClass('col-sm-4').addClass('col-xs-12');
            break;
        case 2:
            $('.footer-widgets-2 section.widget').addClass('col-md-6').addClass('col-sm-6').addClass('col-xs-12');
            break;
        }
	$.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
          this.trigger(ev);
          return el.apply(this, arguments);
        };
      });

	$('.nav-footer ul.menu>li').after(function(){
		if(!$(this).hasClass('last-child') && $(this).hasClass('menu-item') && $(this).css('display')!='none'){
			return '<li class="separator menu-item">|</li>';
		}
	});
	
	$('.section.expandable .expand').click(function(){
	    var target = $(this).parents('.section-body').find('.content');
	    console.log(target);
	    if(target.hasClass('open')){
            target.removeClass('open');
            $(this).html('MORE <i class="fa fa-angle-down"></i>');
	    } else {
	        target.addClass('open');
	        $(this).html('LESS <i class="fa fa-angle-up"></i>');
	    }
	});

	$('.gform_wrapper .gform_body li.gfield').each(function(){
		$(this).children('label').before($(this).children('.ginput_container'));
	});

	$('.genesis-teaser').matchHeight();

    $('.gallery .gallery-item .gallery-icon a img').each(function() {
            var img = $(this);
            var image_uri = img.attr('src');
            var svgheight = $(this).parents('.gallery-icon').width();

            $.get(image_uri, function (data) {
                var svg = $(data).find('svg');
                svg.removeAttr('xmlns:a');
                svg.height(svgheight);
                img.replaceWith(svg);
            }, 'xml');
        });


    $('.gallery .gallery-item .gallery-icon a svg').height(function () {
        return $(this).parents('.gallery-icon').width();
    });
});