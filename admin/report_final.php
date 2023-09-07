<?php
    include('../connect_db.php');
   
    $sql_rp = "SELECT status,count(STATUS) FROM tbl_reports
                WHERE is_active = 'Y'
                GROUP BY status";
    $result_rp = mysqli_query($conn,$sql_rp);

    while($row_rp = mysqli_fetch_array($result_rp))
    {
      
        if($row_rp['status'] == 1){
            $status['count_one'] =  $row_rp['count(STATUS)'];
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

<section class="col-lg-12 connectedSortable">

    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-hockey-puck"></i>
                สรุปการแจ้งซ่อมทั้งหมด
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div><!-- /.card-header -->
        <div class="card-body" style="display: block;">

                <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1 mb-5">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">รายการซ่อมทั้งหมด</span>
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

            <div class="chartjs-size-monitor">
                <div class="chartjs-size-monitor-expand">
                    <div class=""></div>
                </div>
                <div class="chartjs-size-monitor-shrink">
                    <div class=""></div>
                </div>
            </div>
            <canvas id="donutChart"
                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 419px;"
                width="419" height="250" class="chartjs-render-monitor"></canvas>
        </div>
    </div>
    <!-- /.card -->
</section>



<?php include("../layouts/script.php"); ?>
<script>
     var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'รายการซ่อมทั้งหมด',
          'รอดำเนินการ',
          'กำลังดำเนินการ',
          'กำลังซ่อม',
          'ดำเนินการสำเร็จ'
      ],
      datasets: [
        {
          data: [<?php echo $status['rp_all'] ? $status['rp_all'] : 0 ?>,
          <?php echo $status['count_one'] ? $status['count_one'] : 0 ?>,
          <?php echo $status['count_two'] ? $status['count_two'] : 0 ?>,
          <?php echo $status['count_three'] ? $status['count_three'] : 0 ?>,
          <?php echo $status['count_four'] ? $status['count_four'] : 0 ?>],
          backgroundColor : ['#3BF71D', '#0FEBF9','#0F9DF9','#f39c12', '#00a65a'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }

    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })
</script>