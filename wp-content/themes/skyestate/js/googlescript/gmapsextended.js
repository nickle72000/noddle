
var pin_images = gmapsextended_var.pin_images;
var images = jQuery.parseJSON(pin_images);
var ipad_time = 0;

if(  document.getElementById('btn-geolocation') ){
    google.maps.event.addDomListener(document.getElementById('btn-geolocation'), 'click', function () {  
         propposition(map);
    });  
}

jQuery('#btn-geolocation').click(function(){
     propposition(map);
})


function propposition(map){    
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(showPropPosition,errorCatch,{timeout:10000});
    }else{
        errorCatch();
    }
}

function errorCatch(){
    alert('The browser couldn\'t detect your position!');
}

function showPropPosition(pos){
   
    var shape = {
       coord: [1, 1, 1, 38, 38, 59, 59 , 1],
       type: 'poly'
   }; 
   
   var Prop_Point = new google.maps.LatLng( pos.coords.latitude, pos.coords.longitude);
   map.setCenter( Prop_Point );   
   
   var marker = new google.maps.Marker({
             position: Prop_Point,
             map: map,
             shape: shape,
             title: '',
             zIndex: 999999999,
             image:'',
             price:'',
             type:'',
             type2:'',
             link:'' ,
             infoWindowIndex : 99 ,
             radius: parseInt(gmapsextended_var.geolocation_radius,10)+" m "+gmapsextended_var.radius
            });
    
     var populationOptions = {
      strokeColor: '#67cfd8',
      strokeOpacity: 0.6,
      strokeWeight: 1,
      fillColor: '#67cfd8',
      fillOpacity: 0.2,
      map: map,
      center: MyPoint,
      radius: parseInt(gmapsextended_var.geolocation_radius,10)
    };
    var cityCircle = new google.maps.Circle(populationOptions);
    
        var label = new Label({
          map: map
        });
        label.bindTo('position', marker);
        label.bindTo('text', marker, 'radius');
        label.bindTo('visible', marker);
        label.bindTo('clickable', marker);
        label.bindTo('zIndex', marker);

}

jQuery('#gMapsContainer').click(function(event){
    var time_diff;

    time_diff=event.timeStamp-ipad_time;
    
    if(time_diff>3000){
       // alert(event.timeStamp-ipad_time);
        ipad_time=event.timeStamp;
        if(map.scrollwheel===false){
            map.setOptions({'scrollwheel': true});
        }else{
            map.setOptions({'scrollwheel': false});
        }
        jQuery('.tooltip').fadeOut("fast"); 


        if (Modernizr.mq('only all and (max-width: 1025px)')) {
     
           if(map.draggable === false){
                 map.setOptions({'draggable': true});
            }else{
                 map.setOptions({'draggable': false});
            }    
         }
         
     }     
});


if( document.getElementById('gmap') ){
    google.maps.event.addDomListener(document.getElementById('gmap'), 'mouseout', function () {           
        map.setOptions({'scrollwheel': true});
        google.maps.event.trigger(map, "resize");
    });  
}     


if( document.getElementById('googleMap') && map){      
    google.maps.event.addDomListener(document.getElementById('googleMap'), 'mouseout', function () {
        map.setOptions({'scrollwheel': false});
    }); 
}

if(  document.getElementById('gmap-menu') ){
    google.maps.event.addDomListener(document.getElementById('gmap-menu'), 'click', function (event) {
        infoBox.close();

        if (event.target.nodeName==='INPUT'){
            category=event.target.className; 

                if(event.target.name==="filter_action[]"){            
                    if(actions.indexOf(category)!==-1){
                        actions.splice(actions.indexOf(category),1);
                    }else{
                        actions.push(category);
                    }
                }

                if(event.target.name==="filter_type[]"){            
                    if(categories.indexOf(category)!==-1){
                        categories.splice(categories.indexOf(category),1);
                    }else{
                        categories.push(category);
                    }
                }

            show(actions,categories);
        }

    }); 
}

if( document.getElementById('maps-nav-prev')){
	google.maps.event.addDomListener(document.getElementById('maps-nav-prev'), 'click', function () {
        current_place--;

        if (current_place<1){
            current_place=gmarkers.length;
        }

        while(gmarkers[current_place-1].visible===false){
            current_place--; 
            if (current_place>gmarkers.length){
                current_place=1;
            }
        }

        google.maps.event.trigger(gmarkers[current_place-1], 'click');
	});
}

if( document.getElementById('maps-nav-next')){
	google.maps.event.addDomListener(document.getElementById('maps-nav-next'), 'click', function () {
		current_place++;  

     	if (current_place>gmarkers.length){
 			current_place=1;
      	}

      	while(gmarkers[current_place-1].visible===false){
    		current_place++; 
			if (current_place>gmarkers.length){
            	current_place=1;
        	}
      	}

		google.maps.event.trigger(gmarkers[current_place-1], 'click');
	});
}

if(  document.getElementById('map-filter') ){
    google.maps.event.addDomListener(document.getElementById('map-filter'), 'click', function (event) {
        infoBox.close();

        if (event.target.nodeName==='INPUT'){
            category=event.target.className; 

                if(event.target.name==="filter_purpose[]"){            
                    if(actions.indexOf(category)!==-1){
                        actions.splice(actions.indexOf(category),1);
                    }else{
                        actions.push(category);
                    }
                }

                if(event.target.name==="filter_cat[]"){            
                    if(categories.indexOf(category)!==-1){
                        categories.splice(categories.indexOf(category),1);
                    }else{
                        categories.push(category);
                    }
                }

            show(actions,categories);
        }

    }); 
}
  
function show(actions,categories) {
	"use strict";
	for (var i=0; i<gmarkers.length; i++) {
	   if(actions.indexOf(gmarkers[i].type2)===-1 ){
	  
				if ( gmarkers[i]!=='undefined' && categories.indexOf(gmarkers[i].type)===-1 ) {   
					gmarkers[i].setVisible(true);
				}else{
					gmarkers[i].setVisible(false);
				}
		  }else{
				gmarkers[i].setVisible(false);          
		   }       
		   
	}
}
  
function primarypin(img){
	"use strict";
  
	var customimage;
  
	if(img!==''){
		customimage = img;
	}else{
		customimage = interfeis_var.themeurl + 'images/gmaps/none.png';
	}
	
	if(typeof(customimage)=='undefined'){
		customimage = interfeis_var.themeurl + 'images/gmaps/none.png';	
	}
	
	img = {
      url: customimage, 
      size: new google.maps.Size(41, 57),
      origin: new google.maps.Point(0,0),
      anchor: new google.maps.Point(18,57 )
    };
    
    return img;
}

function secondarypin(img){
	"use strict";
	
	img = {
      url: interfeis_var.themeurl + 'images/gmaps/'+img+'.png', 
      size: new google.maps.Size(41, 57),
      origin: new google.maps.Point(0,0),
      anchor: new google.maps.Point(18,57 )
    };
    
    return img;
}
 
function Label(opt_options) {
  // Initialization
  this.setValues(opt_options);


  // Label specific
  var span = this.span_ = document.createElement('span');
  span.style.cssText = 'position: relative; left: -50%; top: 8px; ' +
  'white-space: nowrap;  ' +
  'padding: 2px; background-color: white;opacity:0.7';


  var div = this.div_ = document.createElement('div');
  div.appendChild(span);
  div.style.cssText = 'position: absolute; display: none';
};
Label.prototype = new google.maps.OverlayView;


// Implement onAdd
Label.prototype.onAdd = function() {
  var pane = this.getPanes().overlayImage;
  pane.appendChild(this.div_);


  // Ensures the label is redrawn if the text or position is changed.
  var me = this;
  this.listeners_ = [
    google.maps.event.addListener(this, 'position_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'visible_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'clickable_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'text_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'zindex_changed', function() { me.draw(); }),
    google.maps.event.addDomListener(this.div_, 'click', function() { 
      if (me.get('clickable')) {
        google.maps.event.trigger(me, 'click');
      }
    })
  ];
};


// Implement onRemove
Label.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);


  // Label is removed from the map, stop updating its position/text.
  for (var i = 0, I = this.listeners_.length; i < I; ++i) {
    google.maps.event.removeListener(this.listeners_[i]);
  }
};


// Implement draw
Label.prototype.draw = function() {
  var projection = this.getProjection();
  var position = projection.fromLatLngToDivPixel(this.get('position'));


  var div = this.div_;
  div.style.left = position.x + 'px';
  div.style.top = position.y + 'px';


  var visible = this.get('visible');
  div.style.display = visible ? 'block' : 'none';


  var clickable = this.get('clickable');
  this.span_.style.cursor = clickable ? 'pointer' : '';


  var zIndex = this.get('zIndex');
  div.style.zIndex = zIndex;


  this.span_.innerHTML = this.get('text').toString();
};

function close_adv_search(){

}

function show_advanced_search(closer){
	
}

function hide_advanced_search(){
	
}