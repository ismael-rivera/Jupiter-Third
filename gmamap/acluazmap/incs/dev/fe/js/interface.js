// JavaScript Document

function initialize(){
 
 var viewportwidth;
 var viewportheight;
  
 // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
  
 if (typeof window.innerWidth != 'undefined')
 {
      viewportwidth = window.innerWidth,
      viewportheight = window.innerHeight
 }
  
// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
 
     else if (typeof document.documentElement != 'undefined'
     && typeof document.documentElement.clientWidth !=
     'undefined' && document.documentElement.clientWidth != 0)
 {
       viewportwidth = document.documentElement.clientWidth,
       viewportheight = document.documentElement.clientHeight
 }
  
 // older versions of IE
  
 else
 {
       viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
       viewportheight = document.getElementsByTagName('body')[0].clientHeight
 }
return ('<p>Your viewport width is '+viewportwidth+'x'+viewportheight+'</p>');

};

//---------------------------------------------

function getRootURL() {
	
function disp_current_directory(){
var dirs=window.location.href.split('/'),
cdir=dirs[dirs.length-2];
return cdir;
}	
	
    var baseURL = location.href;
    var rootURL = baseURL.substring(0, baseURL.indexOf('/', 7));
	return rootURL + "/";		  
	
}

$(function() {



var directory1 = 'incs/dev/fe/img/';
var directory2 = getRootURL() + 'map/';
var directory3 = getRootURL() + 'plugins/';

$('table#delTable td a.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
				var id = $(this).parent().parent().attr('id');
				
				var data = 'id=' + id ;
				var parent = $(this).parent().parent();
				/*$.ajax(
				{
					   type: "POST",
					   url: "http://localhost22/test2/delete_row.php",
					   data: data,
					   cache: false,
					
					   success: function()
					   {
							parent.fadeOut('slow', function() {$(this).remove();});
					   }
				 });*/
				 
				$.post( directory3 + "markersonmap/delete_row.php", {id: id}, function(e){
				  //alert("Data: " + id);
				  parent.fadeOut('slow', function() {$(this).remove();});
				});
				
			 };
		});

//End all
});		