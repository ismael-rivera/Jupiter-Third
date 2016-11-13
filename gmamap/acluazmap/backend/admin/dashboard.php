<?php include('admin_header.php'); ?>
    <div class="container-fluid">
      <div class="row-fluid">
        <?php include('menu.php'); ?>
        <div class="span9">
          <div class="hero-unit">
            <h1>Dashboard</h1>
            <p><?php echo "Welcome <b>$session->username</b>, you are logged in. <br><br>"; ?></p>
            <p><a href="<?php echo Dir::gHUrl() . '/backend/admin/markerman.php'?>" class="btn btn-primary btn-large">Manage Markers</a></p>
          </div>
          <!--/row-->
          <!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

<?php include('admin_footer.php'); ?>
