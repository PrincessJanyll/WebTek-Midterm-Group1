jQuery(window).bind("scroll", function() {
    var headerheight = jQuery('header').height();
    var navbar = jQuery('.navbar').height();

    if (jQuery(this).scrollTop() > headerheight-navbar) {
        jQuery( '.navbar' ).addClass( "notinbanner" );
        jQuery( '.navbar' ).removeClass( "inbanner" );
        jQuery('.navbar-default .navbar-toggle .icon-bar').css({ "background": "#1a1a1a" });
        jQuery('.navbar-default .navbar-toggle').css({ "border-color": "#1a1a1a" });
        jQuery('.navbar,.dropdown-menu').css({ "background": "#fff" });
        jQuery('.navbar-default .navbar-nav>li>a,.fallback_cb>ul>li>a,.navbar-default .navbar-brand,.dropdown-menu li a').css({ "color": "#1a1a1a" });
        jQuery('.navbar-default .navbar-nav>li.active>a').css({"border-color": "#1a1a1a"});
        jQuery('.navbar-default .navbar-nav>.dropdown>a .caret,.navbar-default .navbar-nav>.dropdown>a:hover .caret,.navbar-default .navbar-nav>.open>a .caret, .navbar-default .navbar-nav>.open>a:hover .caret, .navbar-default .navbar-nav>.open>a:focus .caret,.navbar-default .navbar-nav>.dropdown>a:hover .caret, .navbar-default .navbar-nav>.dropdown>a:focus .caret').css({"border-top-color": "#1a1a1a","border-bottom-color": "#1a1a1a"});

    } else {
        jQuery('.navbar,.dropdown-menu').css({ "background": "transparent" });
        jQuery('.navbar-default .navbar-nav>li>a,.fallback_cb>ul>li>a,.navbar-default .navbar-brand,.dropdown-menu li a').css({ "color": "#fff" });
        jQuery('.navbar-default .navbar-nav>li.active>a').css({"border-color": "transparent"});
        jQuery('.navbar-default .navbar-nav>.dropdown>a .caret,.navbar-default .navbar-nav>.dropdown>a:hover .caret,.navbar-default .navbar-nav>.open>a .caret, .navbar-default .navbar-nav>.open>a:hover .caret, .navbar-default .navbar-nav>.open>a:focus .caret,.navbar-default .navbar-nav>.dropdown>a:hover .caret, .navbar-default .navbar-nav>.dropdown>a:focus .caret').css({"border-top-color": "#ffffff","border-bottom-color": "#ffffff"});
        jQuery( '.navbar' ).removeClass( "notinbanner" );
        jQuery( '.navbar' ).addClass( "inbanner" );
    }
});


jQuery(document).ready(function($) {
    
    var windowheight = jQuery(window).height();
    var bannerheight = jQuery('.frontpage-banner').height();
    var subpagebannerheight = jQuery('.subpage-banner').height();
    var bannerpadding = ((windowheight-bannerheight)/3);
    if (bannerpadding < 100) {
        bannerpadding = 100;
    }
    var subbannerpadding = ((windowheight-subpagebannerheight)/6);
    if (subbannerpadding < 100) {
        subbannerpadding = 100;
    }    
    jQuery('.frontpage-banner').css({ "padding-top": bannerpadding });
    jQuery('.frontpage-banner').css({ "padding-bottom": bannerpadding });
    jQuery('.subpage-banner').css({ "padding-top": subbannerpadding });
    jQuery('.subpage-banner').css({ "padding-bottom": subbannerpadding });
    
    jQuery("html").niceScroll({
		cursorcolor: "#1a1a1a",
		cursorborder: "#1a1a1a",
		cursoropacitymin: 0.2,
		cursorwidth: 5,
		zindex: 10,
		scrollspeed: 60,
		mousescrollstep: 40
	});
		
	window.sr = new scrollReveal();
	
	var navbar = jQuery('.navbar').height();
	jQuery('body').scrollspy({ 
		target: '.navbar-collapse',
	    offset: navbar
	});

    jQuery('a.scrolltrue').bind('click', function(event) {
    	event.preventDefault();
        var $href = jQuery(this);
        jQuery('html, body').stop().animate({
            scrollTop: jQuery($href.attr('href')).offset().top
        }, 1000, 'easeInOutQuad');
    });	
    
});		