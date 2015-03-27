jQuery(document).ready(function(){
	
	/*Add Class Js to html*/
	jQuery('html').addClass('js');	
	
	show_tab();
	
	show_toggle();
	
	toggle_menu();
	
	topcart_effects();
	
	topsearch_effects();
	
	show_lightbox();
	
	show_carousel();
	
	form_styling();
	
	fullwidthwrap();
	
	appear_effect();
	
	counter_effect();
	
	contact_agent();
	
	change_uberOrientation();
	
	ajaxquickview();
	
	slider_init();
	
	nvr_wrapping_product_thumb();
	
	nvr_button_scrolltop();
	
	nvr_login_process();
	
	nvr_register_process();
	
	nvr_change_pass();
	
	nvr_change_profile();
	
	nvr_paypal_process();
	
	nvr_ajax_make_featured();
});

jQuery(window).load(function(){
	header_effect();
	slider_init();
	show_menu();
	isotopeinit();
	parallax_effect();
	nvr_sliding_product_thumb();
});

jQuery(window).resize(function(){
	isotopeinit();
	fullwidthwrap();
	change_uberOrientation();
	jQuery('ul.topnav').css('top','');
});

function isMobile(){
	"use strict";
	var onMobile = false;
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) { onMobile = true; }
	return onMobile;
}

function header_effect(){
	"use strict";
	/*=================================== TOPSEARCH ==============================*/
	var headertext = jQuery('#headertext');
	var outerheader = jQuery('#outerheader');
	var outerheaderw = jQuery('#outerheaderwrapper');
	var outerslider = jQuery('#outerslider');
	var wpadminbar = jQuery('#wpadminbar');
	
	var headertextheight = headertext.height();
	var headertextinnerh = headertext.innerHeight();
	var adminbarinnerh = wpadminbar.innerHeight();
	var outerheaderinnerh = outerheader.innerHeight();
	var outerheadertop = outerheader.css("top");
	var windowheight = jQuery(window).height();
	var headertextoffset = headertext.offset().top;
	var outerheaderoffset = outerheader.offset().top;
	
	/* Deprecated in 1.1.5
	if(jQuery('body').hasClass('nvrlayout3')){
		var outersliderv3 = jQuery('.nvrlayout3 #outerslider');
		var outersliderinnerh = outersliderv3.innerHeight();
		var outersliderheight = outersliderinnerh+adminbarinnerh;
		
		if(outersliderheight>windowheight){
			outersliderheight = windowheight-adminbarinnerh;
		}
		outersliderv3.css({
			'height' : outersliderheight
		});
	}
	*/
	
	if(jQuery('body').hasClass('nvrlayout4')!=true && jQuery('body').hasClass('nvrlayout5')!=true && jQuery('body').hasClass('nvrlayout6')!=true && jQuery('body').hasClass('nvrlayout7')!=true){
		outerheaderw.css('height',outerheaderinnerh);
	}
	
	headertext.css('height', headertextheight);
	jQuery(window).scroll(function(evt){
		var scrolltop = jQuery(document).scrollTop();
		
		if(jQuery('body').hasClass('nvrlayout4')){
			if(scrolltop>headertextinnerh){
				headertext.addClass("sticky");
				outerheader.addClass("sticky");
				outerslider.addClass("sticky");
			}else{
				headertext.removeClass("sticky");
				outerheader.removeClass("sticky");
				outerslider.removeClass("sticky");
			}
		}else{
			
			if(scrolltop>(outerheaderoffset)){
				outerheader.addClass("sticky");
				var postopoffset = 0;
			}else{
				outerheader.removeClass("sticky");
				var postopoffset = outerheaderoffset;
			}
			if(jQuery('nav.gn-menu-wrapper').hasClass('gn-open-part') || jQuery('nav.gn-menu-wrapper').hasClass('gn-open-all')){
				var postop = postopoffset + outerheaderinnerh;
				jQuery('nav.gn-menu-wrapper').css('top', postop);
			}
		}
	});
}

function parallax_effect(){
	if(!isMobile()){
		jQuery('.parallax, .parallax-container').each(function(){
			jQuery(this).parallax("30%", 0.1);
		});
	}
}

function appear_effect(){
	"use strict";
	//Elements Fading
	jQuery('.element_from_top').each(function () {
		jQuery(this).appear(function() {
		  jQuery(this).delay(150).animate({opacity:1,top:"0px"},1000);
		});	
	});
	
	jQuery('.element_from_bottom').each(function () {
		jQuery(this).appear(function() {
		  jQuery(this).delay(150).animate({opacity:1,bottom:"0px"},1000);
		});	
	});
	
	
	jQuery('.element_from_left').each(function () {
		jQuery(this).appear(function() {
		  jQuery(this).delay(150).animate({opacity:1,left:"0px"},1000);
		});	
	});
	
	
	jQuery('.element_from_right').each(function () {
		jQuery(this).appear(function() {
		  jQuery(this).delay(150).animate({opacity:1,right:"0px"},1000);
		});	
	});
		
	jQuery('.element_fade_in').each(function () {
		jQuery(this).appear(function() {
		  jQuery(this).delay(150).animate({opacity:1,right:"0px"},1000);
		});	
	});
}

function show_menu(){
	"use strict";
	/*=================================== MENU ===================================*/
    jQuery("ul.sf-menu").supersubs({ 
    minWidth		: 12,		/* requires em unit. */
    maxWidth		: 15,		/* requires em unit. */
    extraWidth		: 0	/* extra width can ensure lines don't sometimes turn over due to slight browser differences in how they round-off values */
                           /* due to slight rounding differences and font-family */
    }).superfish();  /* 
						call supersubs first, then superfish, so that subs are 
                        not display:none when measuring. Call before initialising 
                        containing tabs for same reason. 
					 */
}

function change_uberOrientation(){
	"use strict";
	var winwidth = jQuery(window).width();
	if(jQuery('body').hasClass('nvrlayout4')){
		if(winwidth>1023){
			jQuery('#megaMenu').addClass('megaMenuVertical').removeClass('megaMenuHorizontal');
		}else{
			jQuery('#megaMenu').addClass('megaMenuHorizontal').removeClass('megaMenuVertical');
		}
	}
}

/* !Fullwidth wrap for shortcodes & templates */
function fullwidthwrap(){
	"use strict";
	
	var thebody = jQuery('body.novaro');
	var fullww = jQuery(".nvr-fullwidthwrap");
	if( fullww.length && jQuery(".nosidebar").length ){
		fullww.each(function(){
			var getfullwidthval = getfullwidthvalue(this);
			var $_this = jQuery(this);
			
			var outerwidth = getfullwidthval.width;
			var $offset_fs = getfullwidthval.offset_fs;
			$_this.css({
				width: outerwidth,
				"margin-left": -$offset_fs
			});
			
		});
	};
	
	var thepadding = 0;
	if(thebody.hasClass('nvr1100more')){
		thepadding = 16;
	}else{
		thepadding = 11;
	}
	var stripecontainer = jQuery(".stripecontainer");
	if( stripecontainer.length ){
		stripecontainer.each(function(){
			var getfullwidthval = getfullwidthvalue(this);
			var $_this = jQuery(this);
			
			var outerwidth = getfullwidthval.width;
			var $offset_fs = getfullwidthval.offset_fs + thepadding;
			if($_this.hasClass('fullwidth')){
				$_this.css({
					width: outerwidth,
					"padding-left" : 0,
					"padding-right": 0,
					"margin-left": -$offset_fs
				});
			}else{
				$_this.css({
					width: '100%',
					"padding-left" : $offset_fs,
					"padding-right": $offset_fs,
					"margin-left": -$offset_fs
				});
			}
			
		});
	};
};

function getfullwidthvalue(elem){
	"use strict";
	
	var $_this = jQuery( elem ),
	offset_wrap = $_this.position().left;

	var $offset_fs;
	var $body = jQuery('body');
	var $scrollBar = 0;
	var $paddingvc = 0;
	
	var containerwidth = jQuery('#outermain .container').width();
	var outerwidth = (jQuery('#outercontainer').width() - (jQuery('#outercontainer').width()%2));

	var paddingcol = parseInt(jQuery('.columns').css('padding-left'));

	if( jQuery('body').hasClass('boxed') ){
		$offset_fs = ((parseInt(outerwidth) - parseInt(containerwidth)) / 2);
	} else {
			var $windowWidth = (jQuery(window).width() <= parseInt(containerwidth)) ? parseInt(containerwidth) : jQuery(window).width();
			$offset_fs = Math.ceil( ((outerwidth + $scrollBar + $paddingvc - parseInt(containerwidth)) / 2) );
	};
	
	var returnval = {
		"width" : outerwidth,
		"offset_fs" : $offset_fs
	};
	
	return returnval;
}

function show_tab(){
	"use strict";
	/*jQuery tab */
	var pathurl = window.location.href.split("#tab");
	var deftab = "";
	
	jQuery(".tab-content").hide(); /* Hide all content */
	if(pathurl.length>1){ 
		deftab = "#"+pathurl[1];
		var pdeftab = jQuery("ul.tabs li a[href="+deftab+"]").parent().addClass("active").show();
		var tabcondeftab = ".tabcontainer "+deftab;
		jQuery(tabcondeftab).show();
	}else{
		jQuery("ul.tabs li:first").addClass("active").show(); /* Activate first tab */
		jQuery(".tab-content:first").show(); /* Show first tab content */
	}
	/* On Click Event */
	jQuery("ul.tabs li").click(function() {
		jQuery("ul.tabs li").removeClass("active"); /* Remove any "active" class */
		jQuery(this).addClass("active"); /* Add "active" class to selected tab */
		jQuery(".tab-content").hide(); /* Hide all tab content */
		var activeTab = jQuery(this).find("a").attr("href"); /* Find the rel attribute value to identify the active tab + content */
		jQuery(activeTab).fadeIn(200); /* Fade in the active content */
		return false;
	});
}

function toggle_menu(){
	"use strict";
	jQuery('a.nav-toggle').click(function(evt){
		var outerheader = jQuery('#outerheader');
		var outerheaderinnerh = outerheader.innerHeight();
		var topnavpos = outerheaderinnerh ;
		
		jQuery('.topnav').css('top', topnavpos);
		jQuery('.topnav').slideToggle('slow',function(){
			if(isMobile()){
				if(jQuery('.topnav').css('display')=='block'){
					jQuery('video.video').addClass('hidden');
				}else{
					jQuery('video.video').removeClass('hidden');
				}
			}
		});
		
		jQuery('.topnav li a').click(function(){
			jQuery('.topnav').slideUp('slow');
			jQuery('video.video').removeClass('hidden');
		});
	});
}

function show_toggle(){
	"use strict";
	/*jQuery toggle*/
	jQuery(".toggle_container").hide();
	var isiPhone = /iphone/i.test(navigator.userAgent.toLowerCase());
	if (isiPhone){
		jQuery("h2.trigger").click(function(){
			if( jQuery(this).hasClass("active")){
				jQuery(this).removeClass("active");
				jQuery(this).next().css('display','none');
			}else{
				jQuery(this).addClass("active");
				jQuery(this).next().css('display','block');
			}
		});
	}else{
		jQuery("h2.trigger").click(function(){
			jQuery(this).toggleClass("active").next().slideToggle("slow");
		});
	}
}

function counter_effect(){
	"use strict";
	//Animated Counters
	jQuery(".counters").appear(function() {
		var counter = jQuery(this).html();
		jQuery(this).countTo({
			from: 0,
			to: counter,
			speed: 2000,
			refreshInterval: 60,
		});
	});
}

function show_lightbox(){
	"use strict";
	/*=================================== PRETTYPHOTO ===================================*/
	jQuery('a[data-rel]').each(function() {jQuery(this).attr('rel', jQuery(this).data('rel'));});
	jQuery("a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'slow',gallery_markup:'',slideshow:2000, social_tools: ''});
}

function show_carousel(){
	"use strict";
	var ctype = {
		"pcarousel" : {
			"index" : '.pcarousel .flexslider-carousel, .postcarousel .flexslider-carousel',
			"minItems" : 2,
			"maxItems" : 5,
			"itemWidth" : 197
		},
		"propcarousel" : {
			"index" : '.propcarousel .flexslider-carousel',
			"minItems" : 1,
			"maxItems" : 3,
			"itemWidth" : 365
		},
		"bcarousel" : {
			"index" : '.brand .flexslider-carousel',
			"minItems" : 2,
			"maxItems" : 5,
			"itemWidth" : 197
		}
	}
	
	for(var key in ctype){
		var carousel = ctype[key];
		jQuery(carousel.index).flexslider({
			animation: "slide",
			animationLoop: true,
			directionNav: true,
			controlNav: false,
			prevText : '',
			nextText : '',
			itemWidth: carousel.itemWidth,
			itemMargin: 0,
			minItems: carousel.minItems,
			maxItems: carousel.maxItems
		 });
	}
}

function slider_init(){
	"use strict";
	var slidereffect 			= interfeis_var.slidereffect;
    var slider_interval 		= interfeis_var.slider_interval;
    var slider_disable_nav 		= interfeis_var.slider_disable_nav;
    var slider_disable_prevnext	= interfeis_var.slider_disable_prevnext;
    
    if(slider_disable_prevnext=="0"){
        var direction_nav = true;
    }else{
        var direction_nav = false;
    }
    
    if(slider_disable_nav=="0"){
        var control_nav = true;
    }else{
        var control_nav = false;
    }

    jQuery('.flexslider').flexslider({
        animation: slidereffect,
        slideshowSpeed: slider_interval,
        directionNav: direction_nav,
        controlNav: control_nav,
        smoothHeight: true,
		pauseOnHover: true,
		prevText : '',
		nextText : '',
		start : function(){
			jQuery('#slideritems').removeClass('preloader');
		}
    });
	
	jQuery('#carouselitems.flexsliderprop').flexslider({
        animation: 'slide',
        slideshowSpeed: slider_interval,
        directionNav: direction_nav,
        controlNav: control_nav,
        smoothHeight: true,
		pauseOnHover: true,
		prevText : '',
		nextText : '',
		minItems : 2,
		maxItems : 5,
		itemWidth: 177,
        itemMargin: 1,
		start : function(){
			jQuery('#slideritems').removeClass('preloader');
		},
		asNavFor: '#slideritems.flexsliderprop'
    });
	
	jQuery('#slideritems.flexsliderprop').flexslider({
        animation: slidereffect,
        slideshowSpeed: slider_interval,
        directionNav: direction_nav,
        controlNav: control_nav,
        smoothHeight: true,
		pauseOnHover: true,
		prevText : '',
		nextText : '',
		start : function(){
			jQuery('#slideritems').removeClass('preloader');
		},
		sync: "#carouselitems.flexsliderprop"
    });
}

function isotopeinit(){
	"use strict";
	
	var pffilter = jQuery('.portfolio_filter');
	var pffilterbtn = pffilter.children('.portfolio-cat-filter').children('li');
	var pffiltercontent = pffilter.find('#nvr-pf-filter');
    pffiltercontent.isotope({
        itemSelector : '.element'
    });
	
	pffiltercontent.infinitescroll({
		loading: {
			finishedMsg: interfeis_var.loadfinish,
			msg: null,
			msgText: interfeis_var.pfloadmore,
			img: interfeis_var.themeurl + 'images/pf-loader.gif'
		  },
			navSelector  : '#loadmore-paging',    // selector for the paged navigation 
			nextSelector : '#loadmore-paging .loadmorebutton a:first',  // selector for the NEXT link (to page 2)
			itemSelector : '.element',     // selector for all items you'll retrieve
			bufferPx: 40
		},
       	// call Isotope as a callback
		function ( newElements ) {

			var $newElems = jQuery( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function(){
				$newElems.animate({ opacity: 1 });
				pffiltercontent.isotope( 'appended', $newElems, true );
				pffiltercontent.isotope('reLayout');
				show_lightbox();
				jQuery('#loadmore-paging').css('display','block');
			});
		}
	);
	
	pffilterbtn.click(function(){
        pffilterbtn.removeClass('selected');
		var filterbtn = jQuery(this);
        filterbtn.addClass('selected');
        var selector = filterbtn.find('a').attr('data-option-value');
        pffiltercontent.isotope({ filter: selector });
        return false;
    });
	
	var postisotope = jQuery('.postscontainer.mason').isotope({
		itemSelector : '.articlewrapper'
	});
	
	postisotope.infinitescroll({
		loading: {
			finishedMsg: interfeis_var.loadfinish,
			msg: null,
			msgText: interfeis_var.postloadmore,
			img: interfeis_var.themeurl + 'images/pf-loader.gif'
		  },
			navSelector  : '#loadmore-paging',    // selector for the paged navigation 
			nextSelector : '#loadmore-paging .loadmorebutton a:first',  // selector for the NEXT link (to page 2)
			itemSelector : '.articlewrapper',     // selector for all items you'll retrieve
			bufferPx: 40
		},
       	// call Isotope as a callback
		function ( newElements ) {
			
			slider_init();

			var $newElems = jQuery( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function(){
				$newElems.animate({ opacity: 1 });
				postisotope.isotope( 'appended', $newElems, true );
				postisotope.isotope('reLayout');
				jQuery('#loadmore-paging').css('display','block');
			});
		}
	);
	
	var propfilter = jQuery('.property_filter');
	var propfilterbtn = propfilter.children('.property-cat-filter').children('li');
	var propfiltercontent = propfilter.find('#nvr-prop-filter');
    propfiltercontent.isotope({
        itemSelector : '.element'
    });
	
	propfilterbtn.click(function(){
        propfilterbtn.removeClass('selected');
		var filterbtn = jQuery(this);
        filterbtn.addClass('selected');
        var selector = filterbtn.find('a').attr('data-option-value');
        propfiltercontent.isotope({ filter: selector });
        return false;
    });
	
	var prodisotope = jQuery('.nvr-productmasonry ul.products, .product_filter ul.products').isotope({
		itemSelector : 'li.product'
	});
	
	var activefilter = '*';
	
	jQuery('.isotope-filter li').click(function(){
        jQuery('.isotope-filter li').removeClass('selected');
        jQuery(this).addClass('selected');
        var selector = jQuery(this).find('a').attr('data-option-value');
		var selectortext = jQuery(this).find('a').text();
		jQuery(this).parents('.filterlist').find('a.filterbutton').html(selectortext);
        prodisotope.isotope({ filter: selector });
		activefilter = selector;
        return false;
    });
	
	prodisotope.infinitescroll({
		loading: {
			finishedMsg: 'All Products Loaded',
			msg: null,
			msgText: 'Loading More Products',
			img: interfeis_var.themeurl + 'images/pf-loader.gif'
		  },
			navSelector  : '#loadmore-paging',    // selector for the paged navigation 
			nextSelector : '#loadmore-paging .loadmorebutton a:first',  // selector for the NEXT link (to page 2)
			itemSelector : 'li.product',     // selector for all items you'll retrieve
			bufferPx: 40
		},
       	// call Isotope as a callback
		function ( newElements ) {
			
			var $newElems = jQuery( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function(){
				$newElems.animate({ opacity: 1 });
				prodisotope.isotope( 'insert', $newElems, true ).isotope({filter : activefilter});
				prodisotope.isotope('reLayout');
				ajaxquickview();
				jQuery('#loadmore-paging').css('display','block');
			});
		}
	);
	
	jQuery(window).unbind('.infscr');
	
	jQuery('#loadmore-paging .loadmorebutton a:first').click(function(evt){
		pffiltercontent.infinitescroll('retrieve');
		postisotope.infinitescroll('retrieve');
		prodisotope.infinitescroll('retrieve');
		return false;
	});
	jQuery(document).ajaxError(function(e,xhr,opt){
		if(xhr.status==404){jQuery('#loadmore-paging a').remove();}
	});
}

function form_styling(){
	"use strict";
	/* Select */
	var selects = jQuery('select.nvrselector');
	selects.wrap('<div class="nvr_selector" />');
	var selector = jQuery('.nvr_selector');
	selector.prepend('<span />');
	selector.append('<i class="fa fa-chevron-down" />');
	selector.each(function(){
		var selval = jQuery(this).find('select option:selected').text();
		var sel = jQuery(this).children('select');
		var selclass = sel.attr('class');
		jQuery(this).children('span').text(selval);
		jQuery(this).addClass(selclass);
		sel.css('width','100%');
		sel.change(function(){
			var selvals = jQuery(this).children('option:selected').text();
			jQuery(this).parent().children('span').text(selvals);
		});
	});
	
	var rangeslider = jQuery('.rangeslider');
	var rangecontainer = rangeslider.parent('.rangeslidercontainer');
	var txtlowprice = rangecontainer.children('.adv_filter_price_min');
	var txthiprice = rangecontainer.children('.adv_filter_price_max');
	var spnlowprice = rangecontainer.find('.text_price_min');
	var spnhiprice = rangecontainer.find('.text_price_max');
	var defminprice = Number(interfeis_var.search_minprice);
	var defmaxprice = Number(interfeis_var.search_maxprice);
	var hdnminprice = txtlowprice;
	var hdnmaxprice = txthiprice;
	var minprice = Number(hdnminprice.val());
	var maxprice = Number(hdnmaxprice.val());
	
	if(rangeslider.length){
		rangeslider.noUiSlider({
			start: [ minprice, maxprice ],
			step: 10000,
			range: {
				'min': defminprice,
				'max': defmaxprice
			},
			connect: true,
			// Set some default formatting options.
			// These options will be applied to any Link
			// that doesn't overwrite these values.
			format: wNumb({
				decimals: 1
			})
		});
		
		rangeslider.Link('lower').to(txtlowprice);
		rangeslider.Link('upper').to(txthiprice);
		rangeslider.Link('lower').to(spnlowprice, setFormat);
		rangeslider.Link('upper').to(spnhiprice, setFormat);
	}
	
}

function setFormat(value){
	"use strict";
	jQuery(this).html( formatMoney(value, ''));   
}

function formatMoney(n, currency) {
    "use strict";
	return currency + " " + n.replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
}

/*=================================== CUSTOM CART ===================================*/
function update_custom_cart(){
	"use strict";
	
	var numberPattern = /\d+/g;
	
	var the_cart = jQuery("#topminicart"),
		dropdown_cart = the_cart.find(".cartlistwrapper:eq(0)"),
		subtotal = the_cart.find('.cart_subtotal'),
		cart_widget = jQuery('.widget_shopping_cart'),
		cart_qty = the_cart.find('.cart_totalqty');
		
		var new_subtotal = dropdown_cart.find('.total');
		new_subtotal.find('strong').remove();
		subtotal.html( new_subtotal.html() );
		
		var the_quantities = dropdown_cart.find('.quantity');
		var totalqty = 0;
		the_quantities.each(function(idx,el){
			var qtytext = jQuery(el).html().match(numberPattern);
			var qtyint = parseInt(qtytext[0]);
			totalqty = totalqty + qtyint;
		});
		cart_qty.html(totalqty);
		
}

/*=================================== TOPCART ==============================*/
function topcart_effects(){
	"use strict";
	
	jQuery('body').bind('added_to_cart', update_custom_cart);
	
	var btncart = jQuery("#topminicart");
	var catcont = jQuery("#topminicart .cartlistwrapper");
	
	btncart.mouseenter(function(){
		catcont.stop().fadeIn(100,'easeOutCubic');
	});
	btncart.mouseleave(function(){
		catcont.stop().fadeOut(100,'easeOutCubic');
	});
}

function topsearch_effects(){
	"use strict";
	
	var btnsearch = jQuery('.searchbox .submit');
	var searcharea = jQuery('.searchbox .searcharea');
	var textsearch = jQuery('.searchbox .txtsearch');
	
	btnsearch.on('click', function(evt){
		if(textsearch.val()==''){
			evt.preventDefault();
			if(searcharea.hasClass('shown')){
				searcharea.removeClass('shown');
				searcharea.fadeOut();
			}else{
				searcharea.addClass('shown');
				searcharea.fadeIn();
			}
		}
	});
}

/*=================================== QUICK VIEW ===================================*/
/*
$("img.hidden").reveal("fadeIn", 1000);
*/

function chgpicturecallback(){
	"use strict";
	
	if(jQuery.isFunction(jQuery.fn.wc_variation_form)){
		jQuery('form.variations_form').wc_variation_form();
	}
	jQuery('form.variations_form .variations select').change();
	jQuery('.quickview-container .images a').removeAttr('rel');
	var mainimage = jQuery('.quickview-container .images a.woocommerce-main-image');
	mainimage.on('click',function(evt){
		evt.preventDefault();
	}).css('cursor','default');
	jQuery('.thumbnails a').on('click',function(evt){
		evt.preventDefault();
		var imgsrc = jQuery(this).attr('href');
		mainimage.find('img').attr("src",imgsrc);
	});
	
	// Quantity buttons
	jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');

	// Target quantity inputs on product pages
	jQuery("input.qty:not(.product-quantity input.qty)").each(function(){

		var min = parseFloat( jQuery(this).attr('min') );

		if ( min && min > 0 && parseFloat( jQuery(this).val() ) < min ) {
			jQuery(this).val( min );
		}

	});
	
	var qcselector = jQuery('.quickview-container .nvr_selector');
	qcselector.each(function(){
		var selval = jQuery(this).find('select option:selected').text();
		var sel = jQuery(this).children('select');
		var selclass = sel.attr('class');
		jQuery(this).children('span').text(selval);
		jQuery(this).addClass(selclass);
		sel.css('width','100%');
		sel.change(function(){
			var selvals = jQuery(this).children('option:selected').text();
			jQuery(this).parent().children('span').text(selvals);
		});
	});
}

function contact_agent(){
	"use strict";
    
	var contactsubmit = jQuery('#contact-button');
    contactsubmit.click(function(evt){
		evt.preventDefault();
		
        var contact_name    =   jQuery('#contact-name').val();
        var contact_email   =   jQuery('#contact-email').val();
        var contact_phone   =   jQuery('#contact-phone').val();
        var contact_message =   jQuery('#contact-message').val();
        var agent_email     =   jQuery('#contact-agentemail').val();
        var property_id     =   jQuery('#contact-propid').val();
        
        var nonce           =   jQuery('#contact-nonce').val();
        var ajaxurl         =   interfeis_var.adminurl+'admin-ajax.php';
		var sendingtext		=	interfeis_var.sendingtext;
      
        
        jQuery.ajax({
        type: 'POST', 
        dataType: 'json',
        url: ajaxurl,
        data: {
            'action'    		:   'nvr_propsingle_contactagent',
            'name'      		:   contact_name,
            'email'     		:   contact_email,
            'phone'     		:   contact_phone,
            'comment'   		:   contact_message,
            'agentemail'		:   agent_email,
            'propid'    		:   property_id,
            'contact-nonce'     :   nonce
        },
		beforeSend:function(data){
			jQuery('#alert-agent-contact').empty().append(sendingtext);
		},
        success:function(data) {
           // This outputs the result of the ajax request
           if(data.sent){
                jQuery('#contact-name').val('');
                jQuery('#contact-email').val('');
                jQuery('#contact-phone').val('');
                jQuery('#contact-message').val('');
           }
           jQuery('#alert-agent-contact').empty().append(data.response);
        },
            error: function(errorThrown){ 
				
        	}
     	});       
    });
}

function ajaxquickview(){
	"use strict";
	
	jQuery('a.nvr_quickview').click(function(evt){
		
		evt.preventDefault();
		
		var pfitem = jQuery(this);
		var pfurl = jQuery(this).attr('href');
		var pajaxholder = jQuery('div.quickview-ajax-holder');
		var ajaxbutton = pajaxholder.find('a.btnajax');
		var pajaxdata = pajaxholder.find('div.quickview-ajax-data');
		
		var cactive = false;
		var loadedimages = 0;
		var loadedpercent = 0;
		var outerheader = jQuery('#outerheader');
		var wpadminbar = jQuery('#wpadminbar');
		
		var adminbarinnerh = wpadminbar.innerHeight();
		var outerheaderinnerh = outerheader.innerHeight();
		var topscrolledge = adminbarinnerh+outerheaderinnerh;
		
		pajaxholder.removeClass('preloader');
		
		if(pfitem.hasClass('active')){
			
		}else{
		
			pfitem.addClass('active');

			ajaxbutton.click(function(){
					
				pajaxholder.delay(400).fadeOut(600, function(){
					pajaxdata.empty();
					pajaxholder.perfectScrollbar('destroy');
				});
				
				pfitem.removeClass('active') ;
			  
				return false;
			});
			
			pajaxholder.fadeIn(600, function(){ 
				pajaxdata.css('visibility', 'visible');
				pajaxdata.fadeOut(100);
				pajaxholder.addClass('preloader');
				
				var jqxhr = jQuery.ajax({
					url : pfurl,
					cache : false,
					dataType : 'html',
					async : true,
					beforeSend : function(){
						pajaxdata.empty();
					},
					xhr: function(){
						var xhr = new window.XMLHttpRequest();
						/*Upload progress*/
						xhr.upload.addEventListener("progress", function(evt){
							if (evt.lengthComputable) {
								var percentComplete = evt.loaded / evt.total;
								/*Do something with upload progress*/
								console.log(percentComplete);
							}
						}, false);
						/*Download progress*/
						xhr.addEventListener("progress", function(evt){
							if (evt.lengthComputable) {
								var percentComplete = Math.round((evt.loaded/evt.total)*100);
								/*Do something with download progress*/
								console.log(percentComplete);
							}
						}, false);
						return xhr;
					}
				});
				
				jqxhr.done(function(data, textStatus){
					
					var content = data;
					
					pajaxdata.append(content);
					
					chgpicturecallback();
					
					pajaxdata.imagesLoaded()
					.always( function( instance ) {
						console.log('loading images');
					})
					.done( function( instance ) {
						pajaxholder.perfectScrollbar({includePadding : true});
						pajaxdata.delay(1000).fadeIn(900,function(){ 
							pajaxholder.removeClass('preloader'); 
							pajaxholder.scrollTop(0);
							pajaxholder.perfectScrollbar('update');
							slider_init();
						});
	
						jQuery('.element_fade_in').each(function () {
							jQuery(this).appear(function() {
								jQuery(this).delay(100).animate({opacity:1,right:"0px"},1000);
							});
						});
						
					})
					.fail( function() {
						console.log('all images loaded, at least one is broken');
					})
					.progress( function( instance, image ) {
						var totalimage = instance.images.length;
						var result = image.isLoaded ? 'loaded' : 'broken';
						if(result=='loaded'){
							loadedimages++;
						}
						loadedpercent = Math.round((loadedimages/totalimage)*100);
						console.log( 'image is ' + result + ' for ' + image.img.src );
					});
			
				});
				
				jqxhr.fail(function(error, textStatus){
					alert( "Request failed: " + textStatus );
				});
			
			});
		
		}
		
		return false;
	
	});
}

function nvr_wrapping_product_thumb(){
	"use strict";
	
	var thumbcontainer = jQuery('.single-product .type-product .images .thumbnails');
	thumbcontainer.addClass('flexslider');
	thumbcontainer.wrapInner('<ul class="slides"></ul>');
	thumbcontainer.find('a.zoom').wrap('<li></li>');
}

function nvr_sliding_product_thumb(){
	"use strict";
	var thumbcontainer = jQuery('.single-product .type-product .images .thumbnails');
	thumbcontainer.flexslider({
    	animation: "slide",
    	animationLoop: false,
    	itemWidth: 129,
    	itemMargin: 15
	});
}

function nvr_button_scrolltop(){
	"use strict";
	jQuery('.scrollToTop').click(function(){
		jQuery('html, body').animate({scrollTop : 0},800);
		return false;
	});
}

function nvr_login_process(){
	"use strict";
	jQuery('#forgot_pass').click(function(evt){
		evt.preventDefault();
		
		jQuery('#login-div').css('display','none');
		jQuery('#forgot-pass-div').css('display','block');
	});
	
	jQuery('#return_login').click(function(evt){
		evt.preventDefault();
		
		jQuery('#login-div').css('display','block');
		jQuery('#forgot-pass-div').css('display','none');
	});
	
	jQuery('#wp-login-but').click(function(evt){
		evt.preventDefault();

		var  login_user          =  jQuery('#login_user').val(); 
		var  login_pwd           =  jQuery('#login_pwd').val(); 
		var  security            =  jQuery('#security-login').val();
		var  ispop               =  jQuery('#loginpop').val();
		var  ajaxurl         	=   interfeis_var.adminurl+'admin-ajax.php';
		
		jQuery('#login_message_area').empty().append('<div class="login-alert"></div>');
		jQuery.ajax({    
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: {
				'action'            :   'nvr_ajax_login',
				'login_user'        :   login_user,
				'login_pwd'         :   login_pwd,
				'ispop'             :   ispop,
				'security-login'    :   security,
			},
			beforeSend:function(){
				jQuery('#login_message_area').empty().append('<div class="login-alert">'+interfeis_var.sendingtext+'<div>');
			},
			success:function(data) {
			   jQuery('#login_message_area').empty().append('<div class="login-alert">'+data.message+'<div>');
						 
					if (data.loggedin == true){

						interfeis_var.userid = data.newuser;
						jQuery('#ajax_login_container').remove();
						jQuery('#cover').remove();
						
						document.location.href = data.redirect;
						
						
						jQuery('#user_not_logged_in').hide();
						jQuery('#user_logged_in').show();
						
					}else{
						jQuery('#login_user').val(''); 
						jQuery('#login_pwd').val(''); 
					}
					
			},
			error: function(errorThrown){
			
			}
		});  
	
	});
}

function nvr_register_process(){
	"use strict";
	jQuery('#wp-submit-register').click(function(evt){
		evt.preventDefault();
		
		var user_login_register =  jQuery('#user_login_register').val(); 
		var user_email_register =  jQuery('#user_email_register').val(); 
		var nonce               =  jQuery('#security-register').val();
		var ajaxurl             =  interfeis_var.adminurl+'admin-ajax.php'; 
		
		
		jQuery.ajax({
			type: 'POST', 
			url: ajaxurl,
			data: {
				'action'                    :   'nvr_ajax_register',
				'user_login_register'       :   user_login_register,
				'user_email_register'       :   user_email_register,
				'security-register'         :   nonce
			  
			},
			beforeSend:function(){
				jQuery('#register_message_area').empty().append('<div class="login-alert">'+interfeis_var.sendingtext+'</div>');
			},
			success:function(data) {
			   // This outputs the result of the ajax request
			   jQuery('#register_message_area').empty().append('<div class="login-alert">'+data+'</div>');
			   jQuery('#user_login_register').val(''); 
			   jQuery('#user_email_register').val(''); 
			},
			error: function(errorThrown){ alert(errorThrown);}
		});
	
	});

}

function nvr_change_pass(){
	"use strict";
	jQuery('#change_pass').click(function(){
		var  oldpass         =  jQuery('#oldpass').val(); 
		var  newpass         =  jQuery('#newpass').val(); 
		var  renewpass       =  jQuery('#renewpass').val(); 
		var  securitypass    =  jQuery('#security-pass').val();
		var  ajaxurl         =  interfeis_var.adminurl+'admin-ajax.php'; 
		
		 jQuery.ajax({    
		 	type: 'POST',
		 	url: ajaxurl,
			data: {
				 'action'            :   'nvr_ajax_update_pass',
				 'oldpass'           :   oldpass,
				 'newpass'           :   newpass,
				 'renewpass'         :   renewpass,   
				 'security-pass'     :   securitypass
			},
			beforeSend:function(){
				jQuery('#profile_pass').empty().append('<div class="login-alert">'+interfeis_var.sendingtext+'<div>');
			},
			success:function(data) {
				jQuery('#profile_pass').append('<div class="login-alert">'+data+'<div>');
				jQuery('#oldpass,#newpass,#renewpass').val('');
			
			},
			error: function(errorThrown){ }
		}); 
	}); 
}
   
///////////////////////////////////////////////////////////////////////////////////////////  
////////  update profile
////////////////////////////////////////////////////////////////////////////////////////////   
function nvr_change_profile(){
	"use strict";
	
	var stripebtn = jQuery('.stripe_buttons');
	
	jQuery('#pack_select').change(function(evt){
		var stripe_pack_id,stripe_ammount,the_pick;
        jQuery( "#pack_select option:selected" ).each(function() {
            stripe_pack_id = jQuery(this).val();
            stripe_ammount = parseFloat( jQuery(this).attr('data-price'))*100;
            the_pick = jQuery(this).attr('data-pick');
        });
		
		if(typeof the_pick=="undefined"){
			jQuery('#pick_pack').css('display','none');
		}else{
			jQuery('#pick_pack').css('display','block');
		}
        console.log("pack_id: "+stripe_pack_id+" ammount: "+stripe_ammount);
        jQuery('#pack_id').val(stripe_pack_id);
        jQuery('#pay_ammout').val(stripe_ammount);
        jQuery('#stripe_form').attr('data-amount',stripe_ammount);
        
		stripebtn.css('display','none');
		jQuery('#'+the_pick).css('display','block');
	});
	
	jQuery('#update_profile').click(function(evt){
											 
		evt.preventDefault();
		
	   var  firstname       	=  jQuery('#firstname').val(); 
	   var  secondname      	=  jQuery('#secondname').val();
	   var  useremail       	=  jQuery('#useremail').val();
	   var  userphone       	=  jQuery('#userphone').val();
	   var  userskype       	=  jQuery('#userskype').val();
	   var  userfacebook       	=  jQuery('#userfacebook').val();
	   var  usertwitter       	=  jQuery('#usertwitter').val();
	   var  userpinterest      	=  jQuery('#userpinterest').val();
	   var  userlinkedin       	=  jQuery('#userlinkedin').val();
	   var  useryoutube       	=  jQuery('#useryoutube').val();
	   var  userinstagram      	=  jQuery('#userinstagram').val();
	   var  usertitle       	=  jQuery('#usertitle').val();
	   var  description     	=  jQuery('#userbio').val();
	   var  ajaxurl         	=  interfeis_var.adminurl+'admin-ajax.php'; 
	   var  securityprofile 	=  jQuery('#security-profile').val();
	   var  aboutme         	=  jQuery('#about_me').val();
	   var  mobile          	=  jQuery('#usermobile').val();
	   var  profile_image_id  	=  jQuery('#profile-image_id').val();
	   var  profile_image_url  	=  jQuery('#profile-image').attr('data-profileurl');
	   var upload_picture 		=  jQuery('#profile-image').css('background-image');
	   upload_picture = upload_picture.replace('url(','').replace(')','');
	   
	   
	
	   jQuery.ajax({    
		type: 'POST',
		url: ajaxurl,
		data: {
			'action'            :   'nvr_ajax_update_profile',
			'firstname'         :   firstname, 
			'secondname'        :   secondname, 
			'useremail'         :   useremail, 
			'userphone'         :   userphone, 
			'userskype'         :   userskype, 
			'userfacebook'      :   userfacebook, 
			'usertwitter'       :   usertwitter, 
			'userpinterest'     :   userpinterest, 
			'userlinkedin'      :   userlinkedin, 
			'useryoutube'       :   useryoutube, 
			'userinstagram'     :   userinstagram, 
			'usertitle'         :   usertitle, 
			'description'       :   description, 
			'upload_picture'    :   upload_picture,
			'profile_image_id'  :   profile_image_id,
			'profile_image_url' :   profile_image_url,
			'aboutme'           :   aboutme,
			'mobile'            :   mobile, 
			'security-profile'  :   securityprofile
		}, 
		
		 success:function(data) {
		   jQuery('#profile_message').append('<div class="login-alert">'+data+'<div>');                     
		},
		error: function(errorThrown){
			
		}
		});  
	})
}

function nvr_ajax_make_featured(){
	"use strict";
	
	jQuery('.make_featured').click(function(evt){
		evt.preventDefault();
		
		var selectedspan	= jQuery(this);
		var prop_id 		= selectedspan.attr('data-postid');
		var ajaxurl      	=   interfeis_var.adminurl+'admin-ajax.php';     
	
		jQuery.ajax({    
			type: 'POST',
			url: ajaxurl, 
		data: {
			'action'        :   'nvr_ajax_make_prop_featured',
			'propid'        :   prop_id
		},
		success:function(data) { 
			if(data==='done'){
				selectedspan.empty().text('Property is featured');
				var featured_list_no = parseInt( jQuery('#featured_list_no').text(),10 );
				jQuery('#featured_list_no').text(featured_list_no-1);
			}else{
				selectedspan.empty().removeClass('make_featured').addClass('featured_exp').text('You have used all the "Featured" listings in your package.');
			}
		  
		},
		error: function(errorThrown){
	
		}
	
		});//end ajax
      
	});

}

function progressHandlingFunction(e){
	if(e.lengthComputable){
		jQuery('#profile_message').attr({value:e.loaded,max:e.total});
	}
}

function nvr_paypal_process(){
	"use strict";
	
	jQuery('#pick_pack').click(function(){
        if (jQuery('#pack_recuring').is(':checked')) {
            recuring_pay_pack_via_paypal();
        } else {
            pay_pack_via_paypal();
        } 
    });
}

function recuring_pay_pack_via_paypal(){
    "use strict";
	
	var ajaxurl      =   interfeis_var.adminurl+'admin-ajax.php';     
	var packName     =   jQuery('#pack_select :selected').text();
	var packId       =   jQuery('#pack_select :selected').val(); 
	
	jQuery.ajax({    
		type: 'POST',
		url: ajaxurl, 
		data: {
			'action'        :   'ajax_paypal_pack_recuring_generation',
			'packName'      :   packName,
			'packId'        :   packId
		},
		success:function(data) {             
		  window.location.href =data;
		},
		error: function(errorThrown){
		
		}
	});

}

function pay_pack_via_paypal(){
	"use strict";
	
	var ajaxurl 	=  interfeis_var.adminurl+'admin-ajax.php';
	var packName 	= jQuery('#pack_select :selected').text();
	var packId 		= jQuery('#pack_select :selected').val();

	jQuery.ajax({    
		type: 'POST',
		url: ajaxurl, 
		data: {
			'action'        :   'ajax_paypal_pack_generation',
			'packName'      :   packName,
			'packId'        :   packId
		},
		success:function(data) {             
		  window.location.href =data;
		},
		error: function(errorThrown){
		
		}
	});

}