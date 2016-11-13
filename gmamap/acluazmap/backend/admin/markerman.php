<?php include('admin_header.php'); ?>

    <div class="container-fluid">
      <div class="row-fluid">
        <?php include('menu.php'); ?>
        <div id="manage_markers">
            <h3 unselectable="on">Markers on Map</h3>
            <?php include( '../../plugins/markersonmap/markersonmap.php'); ?>
        </div>
        <div class="span9">
          <!--<table border="1" id="flexme1">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th width="100">Name</th>
					<th width="100">Lat</th>
					<th width="100">Long</th>
                    <th width="50">Edit</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>This is data 1 with overflowing content</td>
					<td>This is data 2</td>
					<td>This is data 3</td>
					<td>This is data 4</td>
                    <td>This is data 4</td>
				</tr>
			</tbody>
		</table> -->
        </div><!--/span--> 
        
      </div><!--/row-->

      <hr>

<?php include('admin_footer.php'); ?>