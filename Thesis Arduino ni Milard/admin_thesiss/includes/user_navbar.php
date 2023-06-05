<?php
  include('security.php');
 ?>
   <!-- Sidebar -->
   <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="user_index.php">
  <div class="sidebar-brand-icon rotate-n-15">
    <i class="fas fa-trash"></i>
  </div>
  <div class="sidebar-brand-text mx-3">PBCM-DFS </div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->

<!-- Divider -->

<!-- Heading -->

<!-- Nav Item - Pages Collapse Menu -->


<!-- Divider -->


<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->


            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <img class="img-profile rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA90lEQVR4nO2UQYrCQBBFcwB3gxhT3YoMLryJ4kZvEqN7Vw5WRbyGx3AjVmUmXkLnDKM7pRA3MqKJHXDhhw9N96ce9G/a897KIpC4C4K/RuiYy0w74LhzE6CB3MPlAsHtbcCzw+XszAAQ2oNQWE/Ir6XzKggOdM8lILzOWqbIGaCekH+dbfCkUijArjBwBrBM0T/ZkdOSLVOkBasN4xAED84ApuhnaooCgOAfCC6MYM/naau8HJfUujYJ9fVMM7kAwPQTfM+sd0fVdFozQpvMALuiz3vDLwLGZmZAkHx9PArQ7IuUzAV/18Bx5ykI49asqf3o1b7lqU4EaowmppG4wAAAAABJRU5ErkJggg=="">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

          <form action="logout.php" method="POST"> 
          
            <button type="submit" name="logout_btn" class="btn btn-primary">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>