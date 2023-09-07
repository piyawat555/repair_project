<!DOCTYPE html>
<html lang="en">
<?php 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');
  if($act == "working"){
    $menu = "working";
  }else if($act == "roles"){
    $menu = "roles";
  }else if($act == "users"){
    $menu = "users";
  }else if($act == "history"){
    $menu = "history";
  }else if($act == "profile"){
    $menu = "profile";
  }else if($act == "report_final"){
    $menu = "report_final";
  }else{
    $menu = "index";
  }
  
?>
<?php include("../layouts/head.php"); ?>

<?php 

  if($_SESSION["role_name"] != "admin"){
      echo '<script>
      window.location = "../login.php";
    </script>';
  }

?>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include("../layouts/navbar.php"); ?>
        <!-- /.navbar -->
        <?php include("../admin/menu.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">

                </div><!-- /.container-fluid -->
            </div>
            <!-- Left col -->
            <?php 
              if($act == "working"){
                  include('working_list.php');
              }else if($act == "roles"){
                include('roles_list.php');
              }else if($act == "users"){
                include('users_list.php');
              }else if($act == "add_technician"){
                include('add_technician.php');
              }else if($act == "history"){
                include('history.php');
              }else if($act == "report_final"){
                include('report_final.php');
              }else if($act == "profile"){
                include('profile.php');
              }else{
                  include('report_list.php');
              }                           
            ?>

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include("../layouts/footer.php"); ?>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
      
        $(function () {
            $("#example1").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
              order: [[0, 'asc']],
              "buttons": ["copy", "excel", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#userlist_table").DataTable({
                "columnDefs": [
                    { "visible": false, "targets": 5 }
                ],
                "order": [[ 5, 'asc' ]],
                "displayLength": 25,
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
        
                    api.column(5, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                            );
                            last = group;
                        }
                    } );
                }
            });


            $("#working_tbl").DataTable({
                "columnDefs": [
                    { "visible": false, "targets": 6 }
                ],
                "order": [[ 1, 'desc' ]],
                "displayLength": 25,
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
        
                    api.column(6, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                            );
                            last = group;
                        }
                    } );
                }
            });

        });

        function logout(){
            Swal.fire({
                  title: 'ต้องการออกจากระบบใช่หรือไม่ ?',
                  showDenyButton: true,
                  confirmButtonText: 'ใช่',
                  denyButtonText: `ไม่ใช่`,
                  }).then((result) => {
                  if (result.isConfirmed) {
                                  $.ajax({
                                      method: 'POST',
                                      url: '../logout.php',
                                      success: function(data){
                                        location.href="../login.php?logout=done"
                                      },error: function(err){
                                        show_notify_info(err);
                                      }
                              });
                      } else if (result.isDenied) {
                        show_notify_info("ยกเลิกเรียบร้อยแล้ว");
                      }
                  })
        }

    </script>
    
      <?php 
    
        if($_GET['login']=='done'){
          echo '
            <script>
            setTimeout(() => {
                window.location.href = "../admin/index.php";
            }, "2000");
            toastr.success("ยินดีต้อนรับคุณ '.$_SESSION["user_name"].'");
            </script>';
        } 

        if($_GET['add']=='erros'){  
          echo '
          <script>
          setTimeout(() => {
              window.location.href = "index.php";
          }, "2000");
          toastr.erors("เกิดข้อผิดพลาดกรุณาลองใหม่อีกครั้ง");
          </script>';
        }

      ?>

      <?php
          function DateThai($strDate)
            {

                    $strYear = date("Y",strtotime($strDate))+543;

                    $strMonth= date("n",strtotime($strDate));

                    $strDay= date("j",strtotime($strDate));

                    $strHour= date("H",strtotime($strDate));

                    $strMinute= date("i",strtotime($strDate));

                    $strSeconds= date("s",strtotime($strDate));

                    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

                    $strMonthThai=$strMonthCut[$strMonth];

                    return "$strDay $strMonthThai $strYear,$strHour:$strMinute น.";

            }
      ?>


</body>

</html>