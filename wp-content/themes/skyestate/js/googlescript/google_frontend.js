// JavaScript Document
var map;
var map_open;
var pins = '';
var markers = '';
var category = null;
var width_browser = null;
var infobox_width = null;
var wraper_height = null;
var infoBox = null;
var info_image = null;
var bounds = new google.maps.LatLngBounds();
var gmarkers = [];
var current_place = 0;
var actions = [];
var categories= [];
var vertical_pan = -190;
var vertical_off = 0;
var selected_id = '';
var markercluster = null;

function gmapsinit(){
    "use strict";
   
    var gmapOpt = {
		zoom: parseInt(gmaps_var.gmaps_zoom),
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		panControl: true,
		panControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		mapTypeControlOptions: {
			position: google.maps.ControlPosition.RIGHT_BOTTOM
		},
		scrollwheel: false,
		draggable: true,
		flat:false,
		noClear:false,
		center: new google.maps.LatLng( gmaps_var.gmaps_latitude, gmaps_var.gmaps_longitude),
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl:false
	};
	
	if(document.getElementById('gMapsContainer')!==null){
		map = new google.maps.Map(document.getElementById('gMapsContainer'), gmapOpt);
		google.maps.visualRefresh = true;
	
		google.maps.event.addListener(map, 'tilesloaded', function() {
			jQuery('#gmap-loader').remove();
		});
	
		if (Modernizr.mq('only all and (max-width: 1025px)')) {
			   map.setOptions({'draggable': false});
		}
	   
		pins = gmaps_var.gmaps_markers;
		markers = jQuery.parseJSON(pins);
		setPins( map, markers );
	
		map_cluster();
		filtertoggle();
		ajaxchangepinmap();
	}
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < gmarkers.length; i++) {
    gmarkers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}
 
function setPins(map, locations) {
  "use strict";
  
	gmarkers = [];

   var shape = {
       coord: [1, 1, 1, 41, 41, 57, 57 , 1],
       type: 'poly'
   };
   
  
    var boxText = document.createElement("div");
    width_browser= jQuery(window).width();
   
	infobox_width=358;
	vertical_pan=-217;
	
    if (width_browser<400){
      infobox_width=200;
    }


    var infoboxOptions = {
		maxWidth: infobox_width,
		boxClass:"nvrinfobox",
		disableAutoPan: true,
		infoBoxClearance: new google.maps.Size(1, 1),
		isHidden: false,
		pane: "floatPane",
		zIndex: null,
		closeBoxURL: "",
		content: boxText,
		enableEventPropagation: false
	};
    infoBox = new InfoBox(infoboxOptions);         
                                

   for (var i = 0; i < locations.length; i++) {
        var proploc = locations[i];
        var propLatLng = new google.maps.LatLng(proploc.lat, proploc.long);
		var pin = new google.maps.Marker({
			position: propLatLng,
			map: map,
			shape: shape,
			title: decodeURIComponent(  proploc.title.replace(/\+/g,' ')),
			zIndex: proploc.counter,
			image:proploc.thumb,
			price:proploc.price,
			address:proploc.address,
			city:proploc.city,
			state:proploc.state,
			country:proploc.country,
			bed:proploc.bed,
			bath:proploc.bath,
			size:proploc.size,
			type:proploc.cat,
			type2:proploc.purpose,
			icon: primarypin(proploc.pin),
			link:proploc.link,
			infoWindowIndex : i ,
			animation: google.maps.Animation.DROP,
     	});
		
		gmarkers.push(pin);
		bounds.extend(propLatLng);
		
		google.maps.event.addListener(pin, 'click', function(event) {

			var ua = navigator.userAgent;
			var  event = (ua.match(/iPad/i)) ? "touchstart" : "click";

			jQuery('#gMapsContainer').animate({'height': '590px'}); 
			jQuery('#search_map_form').hide();
			jQuery('#advanced_search_map_form').hide();

			if(this.image===''){
				info_image='<img src="' + interfeis_var.themeurl + 'images/propdefault.jpg" alt="image" />';
			}else{
				info_image=this.image;
			}
                    
			var title   =  	decodeURIComponent( this.title.replace(/\+/g,' '));
			var price	=	decodeURIComponent( this.price.replace(/\+/g,' '));
			var type    =  	decodeURIComponent( this.type.replace(/-/g,' ') );
			var type2   =  	decodeURIComponent( this.type2.replace(/-/g,' ') );
			var address = 	decodeURIComponent( this.address.replace(/-/g,' ') );
			var city 	= 	decodeURIComponent( this.city.replace(/-/g,' ') );
			var state 	= 	decodeURIComponent( this.state.replace(/-/g,' ') );
			var country = 	decodeURIComponent( this.country.replace(/-/g,' ') );
			var completeaddress = address + ' ' + city + ' ' + state + ' ' + country;
			var in_type =   gmapsextended_var.in_text;
			
			var uppermeta = '';
			if(type2!==''){
				uppermeta += '<span class="meta-purpose">'+ type2 +'</span>';
			}
			
			if(price!==''){
				uppermeta += '<span class="meta-price">'+ price +'</span>';
			}
			
			if(uppermeta!=''){
				uppermeta = '<div class="nvr-upper-meta">'+ uppermeta +'</div>';
			}
                    
			if(type==='' || type2===''){
				in_type=" ";
			}

			var extra_adv_class='';

			// prevent ghost clicks on ipad
			if(event==='touchstart'){     //alert('touch');
				infoBox.setContent('<div class="info_details '+extra_adv_class+'" ><span class="fa fa-times" id="infocloser" onClick=\'javascript:infoBox.close();\' ></span><div class="nvr-prop-img">'+ uppermeta +'<a href="'+this.link+'">'+info_image+'</a></div><div class="nvr-prop-text"><h2 class="nvr-prop-title"><a href="'+this.link+'">'+title+'</a></h2><div class="nvr-prop-address"><i class="fa fa-map-marker"></i> '+ completeaddress +'</div><div class="clearfix"></div></div>' );
			}else{
				infoBox.setContent('<div class="info_details '+extra_adv_class+'" ><span class="fa fa-times" id="infocloser" onClick=\'javascript:infoBox.close();\' ></span><div class="nvr-prop-img">'+ uppermeta +'<a href="'+this.link+'">'+info_image+'</a></div><div class="nvr-prop-text"><h2 class="nvr-prop-title"><a href="'+this.link+'">'+title+'</a></h2><div class="nvr-prop-address"><i class="fa fa-map-marker"></i> '+ completeaddress +'</div><div class="clearfix"></div></div>' );
				/*infoBox.setContent('<div class="info_details '+extra_adv_class+'" ><span id="infocloser" onClick=\'javascript:infoBox.close();\' ></span><a href="'+this.link+'">'+info_image+'</a><a href="'+this.link+'" id="infobox_title">'+title+'</a><div class="prop_details"><span id="info_inside">'+type+" "+in_type+" "+type2+this.price+'</span></div>' );*/
   
			}

			infoBox.open(map, this);    
			map.setCenter(this.position);


			switch (infobox_width){
			  case 358:

				   vertical_pan=-127-vertical_off;

				   map.panBy(20,vertical_pan);
				   vertical_off=0;
				   break;
			  case 200: 
				   map.panBy(20,-170); 
				   break;               
			 }
			 close_adv_search();

    	});
   }
	var mapcenterlatlng = bounds.getCenter();
	map.setCenter(mapcenterlatlng);
	map.fitBounds(bounds);
}

function map_cluster(){
	if(gmaps_var.enable_pincluster=='1'){
		
		var optCluster = [{
			opt_textColor: 'white',
			url: interfeis_var.themeurl+'images/gmaps_cloud.png',
			height: 60,
			width: 60,
			textSize:15
		}];
		
		var optMarkerCluster = {
			maxZoom: gmaps_var.maxzoom_cluster, 
			gridSize: 50, 
			styles: optCluster
		};
		
		markercluster = new MarkerClusterer(map, gmarkers, optMarkerCluster);
	}

}

function filtertoggle(){
	"use strict";
	jQuery('#filtertab').click(function(evt){
		evt.preventDefault();
		jQuery(this).toggleClass("active");
		jQuery('#advanced-search-ammenities').slideToggle('slow');
	});
	
	jQuery('#toggle-advanced-search').click(function(evt){
		evt.preventDefault();
		jQuery('#advanced-search-box').toggleClass('active');
		jQuery('#frmadvsearch').slideToggle(800);
	});
}

function ajaxchangepinmap(){
	"use strict";
	
	var btnpropfilter = jQuery('#adv_quick_search');
	btnpropfilter.click(function(evt){
		
		evt.preventDefault();
		deleteMarkers();
		
		if(markercluster){
			markercluster.clearMarkers();
		}
		
		var filter_city			= jQuery('#adv_filter_city').val();
		var filter_keywords		= jQuery('#adv_filter_keywords').val();
        var filter_purpose		= jQuery('#adv_filter_purpose').val();
        var filter_type			= jQuery('#adv_filter_type').val();
        var filter_status		= jQuery('#adv_filter_status').val();
        var filter_numroom		= jQuery('#adv_filter_numroom').val();
        var filter_numbath		= jQuery('#adv_filter_numbath').val();
		var filter_pricemin		= jQuery('#adv_filter_price_min').val();
		var filter_pricemax		= jQuery('#adv_filter_price_max').val();
		var filter_ammenity = [];
        jQuery('input.adv_filter_ammenity:checked').each(function(i){
          filter_ammenity[i] = jQuery(this).val();
        });
		var filter_nonce		= jQuery('#adv_filter_nonce').val();
        
        var nonce           	= jQuery('#adv_filter_nonce').val();
        var ajaxurl         	= interfeis_var.adminurl+'admin-ajax.php';
		
		var propcontainer 		= jQuery('#nvr-prop-search');
		
		var ajaxdata = {
			'action'    			: 'nvr_changepinmap',
			'adv_filter_keywords'	: filter_keywords,
			'adv_filter_city'		: filter_city,
			'adv_filter_purpose'	: filter_purpose,
			'adv_filter_type'		: filter_type,
			'adv_filter_status'		: filter_status,
			'adv_filter_numroom'	: filter_numroom,
			'adv_filter_numbath'	: filter_numbath,
			'adv_filter_price_min'	: filter_pricemin,
			'adv_filter_price_max'	: filter_pricemax,
			'adv_filter_ammenity'	: filter_ammenity,
			'adv_filter_nonce'		: filter_nonce
		};
		var jqxhr = jQuery.ajax({
			type : 'POST',
			url : ajaxurl,
			data : ajaxdata,
			cache : false,
			dataType : 'json',
			async : true,
			xhr: function(){
				var xhr = new window.XMLHttpRequest();
				
				xhr.upload.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						
						console.log(percentComplete);
					}
				}, false);
				
				xhr.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = Math.round((evt.loaded/evt.total)*100);
						
						console.log(percentComplete);
					}
				}, false);
				return xhr;
			}
		});
		
		jqxhr.done(function(data, textStatus){
			propcontainer.empty();
			markers = data.markers;
			if(typeof(markers.length)=='undefined'){alert('Properties Not Found');}
			setPins( map, markers );
			
			map_cluster();
			propcontainer.append(data.propbox);
			
		});
		
		jqxhr.fail(function(error, textStatus){
			alert( "Request failed: " + textStatus );
		});
		return false;
	
	});
}
                      
function stopPropagation(evt){
	if(!evt){
		evt=window.event;
	}
	evt.cancelBubble=true;
	if(evt.stopPropagation){
		evt.stopPropagation();
	}
}
google.maps.event.addDomListener(window, 'load', gmapsinit);