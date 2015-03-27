// JavaScript Document
var map='';
var selected_city='';
var geocoder;
var gmarkers = [];

function gmapsinit(){
	"use strict";
	
	var latitude = admin_interfeis_var.gmaps_latitude;
	var longitude = admin_interfeis_var.gmaps_longitude;
	
	geocoder       		= new google.maps.Geocoder();
    var propertyPoint	= new google.maps.LatLng(latitude, longitude);
	
	var gmapsOptions = {
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		scrollwheel: true,
		draggable: true,
		flat:false,
		noClear:false,
		zoom: 17,
		center: propertyPoint
	};
	
	map = new google.maps.Map(document.getElementById('gMapContainer'), gmapsOptions);
    google.maps.visualRefresh = true;
	
	var marker = new google.maps.Marker({
    	position:propertyPoint
    });
	
    marker.setMap(map);
    gmarkers.push(marker);

    google.maps.event.addListener(map, 'click', function(event) {
    	propertyPointer(event.latLng);
    });
}

function propertyPointer(location) {
    "use strict";
	
    removePins();
    
	var marker = new google.maps.Marker({
        position: location,
        map: map
    });

    var infowindow = new google.maps.InfoWindow({
      content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()  
    });
	
    gmarkers.push(marker);
    infowindow.open(map,marker);
	
    document.getElementById("nvr_latitude").value=location.lat();
    document.getElementById("nvr_longitude").value=location.lng();
}


/*google.maps.event.addDomListener(document.getElementById('propertys-gmaps-option-meta-box').getElementsByClassName("handlediv")[0], 'click', function () {
    google.maps.event.trigger(map, "resize");
});*/


google.maps.event.addDomListener(window, 'load', gmapsinit);

jQuery('#admin_place_pin').click(function(event){
    event.preventDefault();
    get_LatLongfromAddress();  
}); 

function removePins(){
    console.log("in remove");
    for (i = 0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}

function get_LatLongfromAddress() {
  var address   = document.getElementById('nvr_address').value;
  var full_addr= address;
  
  var state     = document.getElementById('nvr_state').value;
  if(state){
       full_addr=full_addr +','+state;
  }
 
 
  var country   = document.getElementById('nvr_country').value;
  if(country){
       full_addr=full_addr +','+country;
  }
 
 
  geocoder.geocode( { 'address': full_addr}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });

            var infowindow = new google.maps.InfoWindow({
                content: 'Latitude: ' + results[0].geometry.location.lat() + '<br>Longitude: ' + results[0].geometry.location.lng()  
             });
              
            infowindow.open(map,marker);
            document.getElementById("nvr_latitude").value=results[0].geometry.location.lat();
            document.getElementById("nvr_longitude").value=results[0].geometry.location.lng();
    } else {
            alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}