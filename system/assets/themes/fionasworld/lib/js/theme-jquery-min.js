jQuery(document).ready(function($){$("*:first-child").addClass("first-child"),$("*:last-child").addClass("last-child"),$("*:nth-child(even)").addClass("even"),$("*:nth-child(odd)").addClass("odd"),$(".section.overlay").wrapInner('<div class="overlay"></div>');var e=$(".footer-widgets-2 div.widget").length;$(".footer-widgets-2").addClass("cols-"+e),$.each(["show","hide"],function(e,i){var a=$.fn[i];$.fn[i]=function(){return this.trigger(i),a.apply(this,arguments)}}),$(".nav-footer ul.menu>li").after(function(){if(!$(this).hasClass("last-child")&&$(this).hasClass("menu-item")&&"none"!=$(this).css("display"))return'<li class="separator menu-item">|</li>'}),$(".section.expandable .expand").click(function(){var e=$(this).parents(".section-body").find(".content");console.log(e),e.hasClass("open")?(e.removeClass("open"),$(this).html('MORE <i class="fa fa-angle-down"></i>')):(e.addClass("open"),$(this).html('LESS <i class="fa fa-angle-up"></i>'))}),$(".gform_wrapper .gform_body li.gfield").each(function(){$(this).children("label").before($(this).children(".ginput_container"))}),$(".genesis-teaser").matchHeight(),$(".gallery .gallery-item .gallery-icon a img").each(function(){var e=$(this),i=e.attr("src"),a=$(this).parents(".gallery-icon").width();$.get(i,function(i){var t=$(i).find("svg");t.removeAttr("xmlns:a"),t.height(a),e.replaceWith(t)},"xml")}),$(".gallery .gallery-item .gallery-icon a svg").height(function(){return $(this).parents(".gallery-icon").width()})});