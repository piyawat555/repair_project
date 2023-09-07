<!DOCTYPE html>
<html lang="en">
<?php 
  $act = (isset($_GET['act']) ? $_GET['act'] : '');
  if($act == "profile"){
    $menu = "profile";
  }else if($act == "history"){
    $menu = "history";
  }else{
    $menu = "index";
  }
?>
<?php include("../layouts/head.php"); ?>

<?php 
if($_SESSION["role_name"] != "employee"){
      echo '<script>
      window.location = "../login.php";
    </script>';
  }
?>
<?php
    include('../connect_db.php');

    $sql_rp = "SELECT status,count(STATUS) FROM tbl_reports
                    WHERE user_id=$user_id AND is_active = 'Y'
                GROUP BY status";
          
    $result_rp = mysqli_query($conn,$sql_rp);

    while($row_rp = mysqli_fetch_array($result_rp))
    {

        if($row_rp['status'] == 1){
            $status['count_one'] = $row_rp['count(STATUS)'];
            $status['rp_all'] += $row_rp['count(STATUS)'];
        }else if($row_rp['status'] == 2){
            $status['count_two']  =  $row_rp['count(STATUS)'];
            $status['rp_all'] += $row_rp['count(STATUS)'];
        }else if($row_rp['status'] == 3){
            $status['count_three']  =  $row_rp['count(STATUS)'];
            $status['rp_all'] += $row_rp['count(STATUS)'];
        }else{
            $status['count_four'] =  $row_rp['count(STATUS)'];
            $status['rp_all'] += $row_rp['count(STATUS)'];
        }
    }

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include("../layouts/navbar.php"); ?>
        <!-- /.navbar -->
        <?php include("../employee/menu.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">

                </div><!-- /.container-fluid -->
            </div>
            <!-- Left col -->
            <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">รายการแจ้งซ่อมทั้งหมด</span>
                                <span class="info-box-number text-center text-muted mb-0"><?php echo $status['rp_all'] ? $status['rp_all'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-2">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">รอดำเนินการ</span>
                                <span class="info-box-number text-center text-muted mb-0"><?php echo $status['count_one'] ? $status['count_one'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-2">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">กำลังดำเนินการ</span>
                                <span class="info-box-number text-center text-muted mb-0"><?php echo $status['count_two'] ? $status['count_two'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-2">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">กำลังซ่อม</span>
                                <span class="info-box-number text-center text-muted mb-0"><?php echo $status['count_three'] ? $status['count_three'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-2">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">ดำเนินการสำเร็จ</span>
                                <span class="info-box-number text-center text-muted mb-0"><?php echo $status['count_four'] ? $status['count_four'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                if($act == "profile"){
                  include('profile.php');          
                }else if($act == "history"){
                    include('history.php');
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

        $("#report_table").DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 4
            }],
            "order": [
                [0, 'desc']
            ],
            "displayLength": 25,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(4, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
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

                    return "$strDay $strMonthThai $strYear, เวลา $strHour:$strMinute น. ";

            }
      ?>
      <?php 

          if($_GET['login']=='done'){
            echo '
              <script>
              setTimeout(() => {
                  window.location.href = "../employee/index.php";
              }, "2000");
              toastr.success("ยินดีต้อนรับคุณ '.$_SESSION["user_name"].'");
              </script>';
          } 

          if($_GET['update_line']=='line_updated'){
            echo '
            <script>
            setTimeout(() => {
                window.location.href = "../employee/index.php?act=profile";
            }, "2000");
            toastr.success("เพิ่มไลน์สำเร็จ");
            </script>';
          }
          
      ?>
</body>

</html>