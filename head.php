<?php
session_start();
error_reporting(0);

$user_id =$_SESSION["user_id"];

  if(empty($_SESSION["user_id"])){
    echo '<script>
          window.location = "../login.php";
        </script>';
  }

  
  if($_SESSION['line_id'] && !empty($_SESSION["user_id"]) && $_SESSION["role_name"] == "employee"){
    include('../connect_db.php');
    $line_id=$_SESSION['line_id'];
    $profile_img=$_SESSION['img_profile'];
    $sql_update = "UPDATE users SET line_id='$line_id',profile_img='$profile_img' WHERE id ='$user_id'";
    $result_update = mysqli_query($conn,$sql_update);
    unset($_SESSION['line_id']);
    if($result_update){
      echo '<script>
      window.location = "index.php?act=profile&&update_line=line_updated";
    </script>';
    }else{

    }
  }


  if($_SESSION['line_id'] && !empty($_SESSION["user_id"]) && $_SESSION["role_name"] == "admin"){
    unset($_SESSION['line_id']);
    echo '<script>
          window.location = "index.php?act=profile";
        </script>';
  }

  if($_SESSION['line_id'] && !empty($_SESSION["user_id"]) && $_SESSION["role_name"] == "technician"){
    unset($_SESSION['line_id']);
    echo '<script>
          window.location = "index.php?act=profile";
        </script>';
  }
?>


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | <?php echo $_SESSION["role_name"] ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">

  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">

  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/togggle/bootstrap-toggle.min.css">


</head>