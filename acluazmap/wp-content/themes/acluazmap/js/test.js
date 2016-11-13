// JavaScript Document

 function initialize() {
		  
		//--------------------------			
		  
		  
        var arizonaState = new google.maps.LatLng(33.371, -111.533);
        var mapOptions = {
          zoom: 7,
		  minZoom: 7,
          center: arizonaState,
          mapTypeId: google.maps.MapTypeId.ROADMAP
	    }; 
        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

function addMarker(location){
       alert(position);
}
		
		google.maps.event.addListener(map, 'click', function(event) {
		     addMarker(event.latLng)
        });
		
 }