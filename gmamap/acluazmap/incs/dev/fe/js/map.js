// JavaScript Document
//-----------------------------	

function getRootURL() {
	
function disp_current_directory(){
var dirs=window.location.href.split('/'),
cdir=dirs[dirs.length-2];
return cdir;
}	
	
    var baseURL = location.href;
    var rootURL = baseURL.substring(0, baseURL.indexOf('/', 7));
	
	if(rootURL === "http://demos.speggo.com"){
		return rootURL + "/" + disp_current_directory() + "/"; 
		} 
	else {
		return rootURL + "/";
		}		  
	
}

var directory1 = 'incs/dev/fe/img/';
var directory2 = getRootURL() + 'map/';
var uploadsdir = getRootURL() + 'uploads/'


var mapdir = getRootURL() + directory1 + 'arizona_map0011213';
var mapdirzoom = getRootURL() + 'img/arizona-physical-map';

var markerdir = getRootURL() + directory1 + '/' + 'markers' + '/'; 


    var infoBubble;
    var markers = [];
	var myLatlng = new google.maps.LatLng(-67, -67);
	var map=null; // mapa
    var ev=null; // event
	 
	
//-----------Marker MInfBub0.0.0--autoimport Marker Library-----------------------


    /*var script = '<script type="text/javascript" src="http://google-maps-' +
        'utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble';*/
		
    /*if (document.location.search.indexOf('compiled') !== -1) {
        script += '-compiled';
       }
      
	  script += '.js"><' + '/script>';
      document.write(script);*/
	  

//-----------------Begin The Meat---------------------------
   

function initialize() {  
	
	    mapOptions = {
	    getTileUrl: function(coord, zoom) {
          var normalizedCoord = getNormalizedCoord(coord, zoom);
          if (!normalizedCoord) {
          return null;
          }
          var bound = Math.pow(2, zoom);
          return mapdir +
          '/' + zoom + '/' + normalizedCoord.x + '/' +
          (bound - normalizedCoord.y - 1) + '.png';
          },
          tileSize: new google.maps.Size(256, 256),
          maxZoom: 8,
          minZoom: 5,
          name: 'Arizona',
          center: myLatlng,
          zoom: 6,
		  backgroundColor: "transparent",  
		  disableDefaultUI: false,
		  streetViewControl: false,
          panControl: true,
		  rotateControl :false,
          panControlOptions: {
          position: google.maps.ControlPosition.LEFT_CENTER
          },
          zoomControl: true,
          zoomControlOptions: {
          style: google.maps.ZoomControlStyle.LARGE,
          position: google.maps.ControlPosition.LEFT_CENTER
          },
          mapTypeControl: false, 
          mapTypeControlOptions: {
            mapTypeIds: ['arizona']
          }
	    };
		
		/*mapOptions2 = {
	    getTileUrl: function(coord, zoom) {
          var normalizedCoord = getNormalizedCoord(coord, zoom);
          if (!normalizedCoord) {
          return null;
          }
          var bound = Math.pow(2, zoom);
          return mapdirzoom +
          '/' + zoom + '/' + normalizedCoord.x + '/' +
          (bound - normalizedCoord.y - 1) + '.png';
          },
          tileSize: new google.maps.Size(256, 256),
          maxZoom: 5,
          minZoom: 1,
          name: 'Arizona',
          center: myLatlng,
          zoom: 2,
		  backgroundColor: "transparent",  
		  disableDefaultUI: false,
          panControl: false,
		  rotateControl: false,
          panControlOptions: {
          position: google.maps.ControlPosition.LEFT_CENTER
          },
          zoomControl: true,
          zoomControlOptions: {
          style: google.maps.ZoomControlStyle.LARGE,
          position: google.maps.ControlPosition.LEFT_CENTER
          },
          mapTypeControl: false, 
          mapTypeControlOptions: {
            mapTypeIds: ['arizona']
          }
	    };*/
	
	arizonaMapType = new google.maps.ImageMapType(mapOptions);
    
	var infoWindow = new google.maps.InfoWindow;
   
	   
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
      
	    map.mapTypes.set('map', arizonaMapType);
        map.setMapTypeId('map');
	
	/*function newMap(){	 
        var arizonaMapType = new google.maps.ImageMapType(mapOptions2);
		
		map.mapTypes.set('map', arizonaMapType);
        map.setMapTypeId('map');
		}*/ 	
	  
//-----------------------------------------------

      /*google.maps.event.addListener(map, "mousemove", function(pt) { 
		  document.getElementById("cursorField").innerHTML = pt.latLng; 
        });*/
		
		
//----------Static Markers-----------------------------------

        /*var image =  directory1 + 'building.png';
        var myLatLngPhoenix = new google.maps.LatLng(-67.57, -69.36);
        var phoenixMarker = new google.maps.Marker({
            position: myLatLngPhoenix,
            map: map,
            icon: image
		});*/
		
		// Create info window. In content you can pass simple text or html code.
/*var infowindow = new google.maps.InfoWindow({
content: "<div>Hello! World</div>",
maxWidth: 10
});*/
		
		infoBub = new InfoBubble({
          minWidth: 300,
          borderRadius: 4,
          arrowSize: 10,
          borderWidth: 1,
          borderColor: '#295379',
		  content: "<table class=\"infoBub\" border=\"1\">" +
						  "<tr>" +
							"<th scope=\"col\">Name</th>"  + 
							"<th scope=\"col\">&nbsp;</th>"  +  
						  "</tr>" +
						  "<tr>" +
							"<td class=\"infobub_content_left\">" + name + "</td>" +
							"<td class=\"infobub_content_right\">&nbsp;</td>" +
						  "</tr>" +
						  "<tr>" +
							"<td>&nbsp;</td>" +
							"<td>&nbsp;</td>" +
						  "</tr>" +
				    "</table>"
        });
		// Add listner for marker. You can add listner for any object. It is just an example in which I am specifying that infowindow will be open on marker mouseover
/*google.maps.event.addListener(phoenixMarker, "click", function() {
infoBub.open(map, phoenixMarker);
});*/
		
		
		/*var image = directory1 + 'places.png';
        var myLatLng = new google.maps.LatLng(-68.98, -68.265);
        var tucsonMarker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: image
		});*/
		

// Add listner for marker. You can add listner for any object. It is just an example in which I am specifying that infowindow will be open on marker mouseover
/*google.maps.event.addListener(tucsonMarker, "click", function() {
infoBub.open(map, tucsonMarker);
});	*/	
		
		/*google.maps.event.addListener(phoenixMarker, 'dblclick', function() {
		return newMap();
        });*/
	     

//-----------Boundaries---------------------------------------------
		
		
		var strictBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(-80, -155), 
                    new google.maps.LatLng( 60, 110)
            );
			
		
		// Listen for the dragend event
            google.maps.event.addListener(map, 'bounds_changed', function() {
                if (strictBounds.contains(map.getCenter())) return;

                // We're out of bounds - Move the map back within the bounds
                var c = map.getCenter(),
                x = c.lng(),
                y = c.lat(),
                maxX = strictBounds.getNorthEast().lng(),
                maxY = strictBounds.getNorthEast().lat(),
                minX = strictBounds.getSouthWest().lng(),
                minY = strictBounds.getSouthWest().lat();

                if (x < minX) x = minX;
                if (x > maxX) x = maxX;
                if (y < minY) y = minY;
                if (y > maxY) y = maxY;

                map.setCenter(new google.maps.LatLng(y, x));
            });
	
	
	
  //-------------------------------------------------------------------------
          
	google.maps.event.addListener(map, "mousemove", function(pt) { 
          document.getElementById("cursorField").innerHTML = pt.latLng; 
        });

//---------------Begin Marker Stuff------------------------	

//---------Insert Info From Parsed XML extracted out of Database---GETDATA---------------	
		 

// Change this depending on the name of your PHP file
      /*downloadUrl( directory2 + "mgetdata.php", function(data) {  
		   
	    var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
		function get_bub_cont(n){
			  return "<table class=\"infoBub\" border=\"1\">" +
						  "<tr>" +
							"<th scope=\"col\">Name</th>"  + 
							"<th scope=\"col\">&nbsp;</th>"  +  
						  "</tr>" +
						  "<tr>" +
							"<td class=\"infobub_content_left\">" + n + "</td>" +
							"<td class=\"infobub_content_right\">&nbsp;</td>" +
						  "</tr>" +
						  "<tr>" +
							"<td>&nbsp;</td>" +
							"<td>&nbsp;</td>" +
						  "</tr>" +
					   "</table>"
					   }
		
		infoBubble = new InfoBubble({
          minWidth: 300,
		  background: 'img/infobub-bg.jpg',
          borderRadius: 4,
          arrowSize: 10,
		  hideCloseButton: true,
          borderWidth: 1,
          borderColor: '#2c2c2c',
		  content: get_bub_cont
          });
		
			
		for (var i = 0; i < markers.length; i++) {
          name = markers[i].getAttribute("name");     
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));		   
		  infoBubble.content(name);
		  var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
		  
		    var html = infoBubble.content;
		    bindInfoWindow(marker, map, infoBubble, html);
        }
		
			
		   	  
        
		
        
      });*/
	  
	  var customIcons = {
      info: {
        icon: markerdir + 'information.png',
        shadow: markerdir + 'shadow001.png'
      },
      finish: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
	  building:{
		icon: markerdir + 'building.png',
        shadow: markerdir + 'shadow001.png'  
	  },
	  place: {
		icon: markerdir + 'places.png',
        shadow: markerdir + 'shadow001.png'  
	  }
    };
	  
	  function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

		   
    function doNothing() {}	
	  
	  downloadUrl(directory2 + "mgetdata.php", function(data)
    {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++)
        {
			var name = markers[i].getAttribute("name");
            var location = markers[i].getAttribute("location");
			var date = markers[i].getAttribute("date");
			var mID = markers[i].getAttribute("mID");
            var type = markers[i].getAttribute("type");
			var infowindow = markers[i].getAttribute("infowindow");
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));
			var icon = customIcons[type] || {};
			//var icon = markers[i].getAttribute("icon");
			var thumb = markers[i].getAttribute("thumb");
			var image = markers[i].getAttribute("image");
			
	
					
			var html = "<table class=\"infoBub\" border=\"1\">" +
						  "<tr>" +
							"<th class=\"intit\" colspan=\"2\"><h3 class\"intit_left\">"  + location + "</h3><h3 class\"intit_right\">" + date + "</h3></th>"  + 
						  "</tr>" +
						  "<tr>" +
							"<td class=\"infobub_content_left\"><a class=\"infowimage\" href=\"" + uploadsdir + image + "\"><img src=\"" + uploadsdir + image + "\" alt=\"image of map location\" /></a></td>" +
							"<td class=\"infobub_content_right\"><h3 class=\"infotitle\">"+ name +"</h3>" + infowindow +"</td>" +
						  "</tr>" +
						  "<tr>" +
							"<td>&nbsp;</td>" +
							"<td>&nbsp;</td>" +
						  "</tr>" +
					   "</table>";	
/* + uploadsdir + image + */           
            var marker = new MarkerWithLabel
            ({
                map: map,
                position: point,
                icon: icon.icon,
                title:name,
				labelContent: mID,
                labelAnchor: new google.maps.Point(12, 0),
                labelClass: "labels", // the CSS class for the label
                labelStyle: {opacity: 1.0}
            });

            bindInfoBubble(marker, map, html);
            
        }
    });  

    var infoBubbleClass = (function(){
        var instance = null;
        return {
           getInstance:function(){
               if(instance===null){
                   instance = new InfoBubble
                    ({
                       map: map,
					   padding: 0,
					   minWidth: 300,
					   maxWidth: 800,
					   maxHeight: 600,
                       borderRadius: 10,
                       arrowSize: 10,
                       borderWidth: 1,
                       borderColor: '#2c2c2c',
                       arrowPosition: 50,
                       backgroundClassName: 'infowin'
					   
                    });                 
               }
               return instance;
           }
        };
    })();             
    
    function bindInfoBubble(marker, map, html)
    {
        google.maps.event.addListener(marker, 'click', function()
        {
            infoBubbleClass.getInstance().setContent(html);
            infoBubbleClass.getInstance().open(map, marker);
        });
		
		google.maps.event.addListener(infoBubbleClass.getInstance(), 'domready', function() {
            $('.infowimage').each(function() {
            $(this).fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
				
				});
            });
        });


    }
	
	// Close button
/*var close = this.close_ = document.createElement('IMG');
close.style['position'] = 'absolute';
close.style['width'] = this.px(12);
close.style['height'] = this.px(12);
close.style['border'] = 0;
close.style['zIndex'] = this.baseZIndex_ + 1;
close.style['cursor'] = 'pointer';
close.src = directory1 + 'delete.png';*/
	  

	 	
		
}/*-------------------------End Initializer---------------------------

___________           .___ .___       .__  __  .__       .__  .__                     
\_   _____/ ____    __| _/ |   | ____ |__|/  |_|__|____  |  | |__|_______ ___________ 
 |    __)_ /    \  / __ |  |   |/    \|  \   __\  \__  \ |  | |  \___   // __ \_  __ \
 |        \   |  \/ /_/ |  |   |   |  \  ||  | |  |/ __ \|  |_|  |/    /\  ___/|  | \/
/_______  /___|  /\____ |  |___|___|  /__||__| |__(____  /____/__/_____ \\___  >__|   
        \/     \/      \/           \/                 \/              \/    \/       

---------------------------------------------------------------------*/	
		



//---------------Control Tools------------------------------------------------------		

	/**
 * Global marker object that holds all markers.
 * @type {Object.<string, google.maps.LatLng>} 
 */
    markers = {};
    var ctrlbtn = null;
	
	
function markerButtonFunctions(ctrlbtn){
	
	    var markerId = 0;
		 
	     function enableMarkers(position) {
			  
			 //var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
             marker = new google.maps.Marker({
		        icon: directory1 + '/markers/finish.png',
		        animation: google.maps.Animation.DROP,
                position: position,
                map: map,
				id: markerId++
              });
             map.panTo(position);
			 var NewMapCenter = map.getCenter();             
			 var Newlat = NewMapCenter.lat();
             var Newlng = NewMapCenter.lng();
		     //alert(Newlat + ', ' + Newlng);
			 
			  markers[marker.id] = markerId;
			 
			 //alert(markers[marker.id]);
			 //alert(markers.toSource());
			 
			 //var markerdata = markers.toSource();
			 var mlatdata = Newlat;
			 var mlngdata = Newlng;
			 var middata = markers[marker.id];
			 
			 $.post( directory2 + "msavedata.php", {
				 mlatdata:mlatdata, 
				 mlngdata:mlngdata, 
				 middata:middata 
				 }, 
				 function(data){ 
	         $("#markerOutput").html(data).show; 
	         });
			 
			  
             //alert(markers[marker.id].lat);


			    
		}//End enableMarkers()
		
		if(ctrlbtn === "marker"){
			placeMarkerListener = google.maps.event.addListener(map, 'click', function(event) {
				lat = event.latLng.lat(); // lat of clicked point
                lng = event.latLng.lng(); // lng of clicked point
				enableMarkers(event.latLng);
			});	
			cursorDraggableListener = google.maps.event.addListener(map, 'mouseover', function(event) {
			map.setOptions({draggableCursor:'crosshair'});
			});
			}
		
		 var getMarkerUniqueId= function(lat, lng) {
         return lat + '_' + lng;
         }
		 
		 
		
		function outputMarkerInfo(){
			     
			   for (var key in markers) {
				  var obj = markers[key];
				  for (var prop in obj) {
				  alert("<td class='"+ prop +"'>" + obj[prop] + "</td>");
				  }
               }
			     //document.getElementById("markerOutput").textContent = markers.toSource();;
				 //document.getElementById("markerOutput").textContent = markers.toSource();	
				 //document.write(markers.toSource());
				 
		 }
		
		        /*for(var key in markers) {
                //alert(markers[key].lat);
                document.getElementById("markerOutput").textContent = markers[lat]; 
				 }*/
		  
	  
		 if(ctrlbtn === "pantool"){
			google.maps.event.removeListener(placeMarkerListener);
			google.maps.event.removeListener(cursorDraggableListener);
			handDraggableListener = google.maps.event.addListener(map, 'mouseover', function(event) {
			map.setOptions({draggableCursor:'pointer'})
			});
			google.maps.event.addListener(marker, 'click', function(event) {
			   alert(marker.id);
			});
		  }
		  
		 if(ctrlbtn === "eraser"){
			google.maps.event.removeListener(placeMarkerListener);
			google.maps.event.removeListener(cursorDraggableListener);  
			google.maps.event.removeListener(handDraggableListener);
		    eraseMarkerListener = google.maps.event.addListener(map, 'click', function(event) {
				enableEraser();
			});	
		 } 
		 function enableEraser(){
			 alert('erased')
			 }

	 
}


/*------------------------------end markerButtonFunctions-----------------------------*/
	 

  // Normalizes the coords that tiles repeat across the x axis (horizontally)
      // like the standard Google map tiles.
      function getNormalizedCoord(coord, zoom) {
        var y = coord.y;
        var x = coord.x;

        // tile range in one direction range is dependent on zoom level
        // 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
        var tileRange = 2 << zoom;

        // don't repeat across y-axis (vertically)
        if (y < 0 || y >= tileRange) {
          return null;
        }

        // repeat across x-axis
        if (x < 0 || x >= tileRange) {
          /*x = (x % tileRange + tileRange) % tileRange;*/
		  return null;
        }

        return {
          x: x,
          y: y
        };
		
	  }
	  
	   

    /*function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }*/

    
	
	
	
	/*function toggleDiv(divid){
		
    	
	    if(document.getElementById(divid).style.display == 'none'){
			document.getElementById(divid).style.display = 'block';
	    }else{
	      document.getElementById(divid).style.display = 'none';
	    }
	
	
    }*/ 
	

	  	
	  	
