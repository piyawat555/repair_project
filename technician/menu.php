<?php
session_start();
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo $_SESSION["role_name"] ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="index.php" class="d-block"><?php echo $_SESSION["user_name"] ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">แจ้งซ่อม</li>
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($menu=="index"){echo "active";} ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                รายการแจ้งซ่อม
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?act=working" class="nav-link <?php if($menu=="working"){echo "active";} ?>">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                การดำเนินการของช่าง
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?act=profile" class="nav-link <?php if($menu=="profile"){echo "active";} ?>">
            <i class="nav-icon fas fa-solid fa-user"></i>
              <p>
                  โปรไฟล์
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" onclick="logout()" style="cursor:pointer">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p class="text">ออกจากระบบ</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
