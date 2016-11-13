// JavaScript Document
//-----------------------------	

function ifLoggedIn(logged,loggednot){
	if(wpAjax.islogin_data == 'yes'){
		return logged;
		} else {
		return loggednot;	
		}
	}

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

var directory1 = 'wp-content/themes/acluazmap/img/';
var directory2 = getRootURL() + 'wp-content/themes/acluazmap/';
var uploadsdir = getRootURL() + 'uploads/'
var adminURL = getRootURL() + 'wp-admin/'



var mapdir = getRootURL() + directory1 + 'arizona_map0011213';
var mapdirzoom = getRootURL() + 'img/arizona-physical-map';

var markerdir = getRootURL() + directory1 + '/' + 'markers' + '/'; 


    var infoBubble;
    var markers = [];
	var myLatlng = new google.maps.LatLng(-67, -67);
	var map=null; // mapa
    var ev=null; // event
	 
   

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
          maxZoom: 6,
          minZoom: 5,
          name: 'Arizona',
          center: myLatlng,
          zoom: 5,
		  backgroundColor: "transparent",  
		  disableDefaultUI: false,
		  streetViewControl: false,
          panControl: false,
		  disableDoubleClickZoom: true,
		  draggable: false,
          scrollwheel: false,
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
		
	
	arizonaMapType = new google.maps.ImageMapType(mapOptions);
    
	var infoWindow = new google.maps.InfoWindow;
   
	   
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
      
	    map.mapTypes.set('map', arizonaMapType);
        map.setMapTypeId('map');
	
	
		
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
	  },
	  police:{
		icon: markerdir + 'police.png',
        shadow: markerdir + 'shadow001.png'  
		  },
	  police_red:{
		icon: markerdir + 'police_red.png',
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
			var postid        =  markers[i].getAttribute("postid");
            var name          =  markers[i].getAttribute("name");
            var type          =  markers[i].getAttribute("type");
			var location      =  markers[i].getAttribute("location");
			var date          =  markers[i].getAttribute("date");
			var infowin_left  =  markers[i].getAttribute("infowin_left");
			var infowin_right =  markers[i].getAttribute("infowin_right");
			
			
			function decideCategory(){
				if (!markers[i].getAttribute("cat")){
					return 'info';
					} else {
					
					var str = markers[i].getAttribute("cat")
                    var result = str.replace(/(\s|&nbsp;|&\#160;)+/gi, "_");	
					return result.toLowerCase();
						}
				}
			var cat = decideCategory();
            var point =  new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng"))
					);
					
			var markernumber =  markers[i].getAttribute("marker_number");		
			//var icon = customIcons.cat.icon;
			//var icon  =  markers[i].getAttribute("icon");
			//var thumb     =  markers[i].getAttribute("thumb");
			var image     =  markers[i].getAttribute("image");
			
			//if(name == '' || infowin_left == '' || infowin_right == ''){
			var islogin_data = {
                          action: 'is_user_logged_in'
                       };
//alert (wpAjax.islogin_data);

nl = '<li><a href="'+ adminURL +'" id="moreicons" class="tool_btn"></a></li>'+
							   '<li class="must_admin">Some crucial information for this marker is missing.<br /> Please Login to fill in the blanks.</li>'; 
l = '<li class="must_admin">Some crucial information for this marker is missing.<br /> Please <a href="wp-admin/post.php?post='+ postid +'&action=edit">click here</a> to go to this markers edit page and fill in the blanks.</li>';

nli = "<td>&nbsp;</td>";	
li = "<td class=\"edit_small\"><a href=\"wp-admin/post.php?post="+ postid +"&action=edit\">Click Here</a> to edit marker</td>";
							   			  
                    if(name == '' || infowin_left == '' || infowin_right == ''){
						  // user is not logged in, show login form here
					var html = '<table id="toolbar_tbl_pop" border="1">' +
                         '<tr>' +
                           '<td>' +
						    '<ul class="tool_btns">' +
						     ifLoggedIn(l,nl) +
							'</ul>' + 
                           '</td>'+
                         '</tr>'+
                       '</table>';
					  } else {
					// user is logged in, do your stuff here
					var html = "<table class=\"infoBub\" border=\"0\">" +
						  "<tr>" +
							"<th class=\"intit\" colspan=\"2\"><h3 class\"intit_left\">"  + location + "</h3><h3 class\"intit_right\">" + date + "</h3></th>"  + 
						  "</tr>" +
						  "<tr>" +
							"<td class=\"infobub_content_left\">" + infowin_left + "</td>" +
							"<td class=\"infobub_content_right\"><h3 class=\"infotitle\">"+ name +"</h3>" + infowin_right +"</td>" +
						  "</tr>" +
						  "<tr>" +
							"<td>&nbsp;</td>" +
							ifLoggedIn(li,nli) +
						  "</tr>" +
					   "</table>";}	
				 	   
                    
             	
			
			
/* + uploadsdir + image + */
			
            
			function get_markerNumber(a){
				if(!a || a == ''){
					a = 'Number-Me';
					return a;
					} else {
					return a;	
						}
				}
				
            nlin = false;	
            lin =  true;		
            var marker = new RichMarker
            ({
                map: map,
                position: point,
                icon: customIcons[cat].icon,
				
				content: '<div id="m-'+postid+'" class="my-marker">' +
			             '<div class ="labels">'+ get_markerNumber(markernumber) +'</div>' +	
                         '<div><img src="' + customIcons[cat].icon +'"/></div>' +
			             '</div>',
				
				flat: true,
                title:name
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
					   minHeight: 150,
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


    }//end bindInfoBubble()
	
	
	
		  

	 	
		
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
		        icon: directory1 + '/markers/downloadicon.png',
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
			 var catdata = 'new';
			 //var middata = markers[marker.id];
			 
			 
			 $.post( directory2 + "msavedata.php", {
				 mlatdata:mlatdata, 
				 mlngdata:mlngdata,
				 catdata:catdata  
				 } 
				/* function(data){ 
	         $("#markerOutput").html(data).show; 
	         }*/
			 );
			 
			  
             //alert(markers[marker.id].lat);


			    
		}//End enableMarkers()
		
		if(ctrlbtn === "marker"){
			google.maps.event.clearListeners(map, 'mouseover');
			google.maps.event.addListener(map, 'click', function(event) {
				lat = event.latLng.lat(); // lat of clicked point
                lng = event.latLng.lng(); // lng of clicked point
				enableMarkers(event.latLng);
			});	
			google.maps.event.addListener(map, 'mouseover', function(event) {
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
				 
		 }
		
		  
	  
		 if(ctrlbtn == "pantool"){
			
			google.maps.event.clearListeners(map, 'click');
			google.maps.event.clearListeners(map, 'mouseover');
			google.maps.event.addListener(map, 'mouseover', function(event) {
			map.setOptions({draggableCursor:'all-scroll'})
			});
			
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
	  
	   

	

	  	
	  	
