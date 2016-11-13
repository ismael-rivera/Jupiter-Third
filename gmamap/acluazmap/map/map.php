    <div id="content">
    <!--<div id="loadingDiv">-->
    <div id="map_canvas"></div>
    <!--<div id="map_canvas2" style="display:none"></div>-->
    <!--</div>-->
    
    <div id="map_console">
    <div id="relative_wrap">
    <span unselectable="on" href="" class="activate_console_btn" id="markers_tab"></span>
    <div class="slideout">
        <h3 unselectable="on">Toolbar</h3>
            <div class="tabcontent">
              <table id="toolbar_tbl">
                 <tr>
                 <td>
                 <ul class="tool_btns">
                  <li><div id="hand" class="tool_btn" onclick="markerButtonFunctions('pantool');"></div></li>
                  <li><div id="marker" class="tool_btn" onclick="markerButtonFunctions('marker');"></div></li>
                  <li><a href="<?php echo AZ_BACKEND . '/login.php'; ?>" id="moreicons" class="tool_btn"></a></li>
                  </ul>
                  </td>
                 </tr>
              </table>
              </div>
        <h3 unselectable="on">Markers on Map</h3>
            <div id="markerOutput" class="tabcontent">
              <div><textarea id="cursorField">&nbsp;</textarea></div>
              <?php include('plugins/markersonmap/markersonmap.php'); ?>
              <h3>Simple image gallery</h3>
	<p>
		<a class="fancybox-buttons" href="#" title="Lorem ipsum dolor sit amet"><img src="" alt="" />Help</a>
	</p>
            </div>         
    </div><!--End .slideout-->
    </div><!--End #relative_wrap-->
    </div><!--End #rmap_console-->
    </div><!--End #content-->
   
<?php include('frontend_footer.php'); ?>   
