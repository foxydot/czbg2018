jQuery(function($){var o=$("main.content");$("main.content").append('<span class="load-more"></span>');var a=$("main.content .load-more"),n=2,e=!1,l={allow:!0,reallow:function(){l.allow=!0},delay:400};$(window).scroll(function(){if(!e&&l.allow){l.allow=!1,setTimeout(l.reallow,l.delay);if(2e3>$(a).offset().top-$(window).scrollTop()){e=!0;var t={action:"be_ajax_load_more",page:n,query:beloadmore.query};$.post(beloadmore.url,t,function(l){if(l.success){var t=$(l.data);o.append(t),$("main.conent").append(a),n+=1,e=!1}}).fail(function(o,a,n){})}}})});