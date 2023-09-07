<?php
    include('../connect_db.php');
        $sql_user_report = "SELECT tr.*,ts.status_name FROM tbl_reports tr 
            INNER JOIN tbl_status ts ON ts.status_id = tr.status
                WHERE user_id = $user_id AND is_active = 'Y'";
    $reports = mysqli_query($conn,$sql_user_report);
?>
<section class="col-lg-12 connectedSortable">
        <button type="button" class="btn btn-success btn-sm mb-2" onclick="modalReport()">
                + แจ้งซ่อม
        </button>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-solid fa-table"></i>
                รายการแจ้งซ่อม
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
        <table id="report_table" class="table table-bordered table-striped dataTable">
                <thead>
                    <tr role="row" class="info">
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">รหัสการแจ้ง</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">วันที่แจ้ง</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 15%;">ปัญหาที่พบ - รายละเอียดปัญหาที่พบ</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานที่</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">--</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                        foreach($reports as $list_report) {
                   ?>
                    <tr>
                        <td>
                            <?php echo $list_report['code'] ?>
                        </td>
                        <td>
                            <?php echo DateThai($list_report['date_report']); ?>
                        </td>
                        <td> 
                            <?php echo $list_report['title'] ?> <br>
                            <small> <?php echo $list_report['description'] ?> </small> 
                        </td>
                        <td> 
                            <?php echo $list_report['location'] ?>
                        </td>
                        <td>
                            <span class="badge badge-primary">
                                <?php echo $list_report['status_name'] ?>
                            </span>
                        </td>
                        <td class="project-actions text-center">
                                <a class="btn btn-primary btn-sm" href="index.php?act=history&code=<?php echo $list_report['code']; ?>">
                                    <i class="fas fa-folder">
                                    </i>
                                    ดูข้อมูล
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="delete_report('<?= $list_report['code']?>')">
                                    <i class="fas fa-trash">
                                    </i>
                                    ลบ
                                </a>
                        </td>
                    </tr>
                    <?php  }?>
                </tbody>
            </table>
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>

<div class="modal fade" id="modalReport">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form accept-charset="utf-8">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">กรอกข้อมูลแจ้งซ่อม</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="container">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">ปัญหาที่พบ</label>
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $user_id ?>" required>
                            <input type="hidden" class="form-control" id="n_user" name="n_user" value="<?php echo $_SESSION["user_name"] ?>" required>
                            <input type="text" class="form-control" id="title" name="title" value="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">สถานที่</label>
                            <input type="text" class="form-control" id="location" name="location" value="" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="recipient-name" class="col-form-label">รายละเอียดปัญหาที่พบ</label>
                            <input type="text" class="form-control" id="desc" name="desc" value="" required>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <!-- toastrDefaultSuccess -->
                    <button type="button" class="btn btn-success" id="rp_submit">แจ้งปัญหา</button>
                    <button type="button" class="btn btn-info" id="rp_submiting" disable style="display:none;">กำลังแจ้งปัญหา</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../layouts/script.php"); ?>

<script>
    
    function modalReport(){
        $("#title").val('');
        $("#location").val('');
        $("#desc").val('');
        $("#rp_submiting").hide();
        $("#rp_submit").show();
        $("#modalReport").modal("show");
    }

    $("#rp_submit").click(function(){
        if($('#title').val().length > 0){
            var ltitle_v = true;
        }else{
            show_notify_erors("กรุณากรอกปัญหาที่พบ");
        }

        if($('#location').val().length > 0){
            var location_v = true;
        }else{
            show_notify_erors("กรุณากรอกชื่อสถานที่");
        }

        if($('#desc').val().length > 0){
            var dec_v = true;
        }else{
            show_notify_erors("กรุณากรอกรายละเอียดปัญหาที่พบ");
        }

        if(ltitle_v==true&&location_v==true&&dec_v==true){
             $("#rp_submiting").show();
             $("#rp_submit").hide();
            $.ajax({
                method: 'POST',
                url:'report_db.php',
                data:{
                    rp_submit:$("#user_id").val(),
                    title:$("#title").val(),
                    location:$("#location").val(),
                    desc:$("#desc").val(),
                    name_user:$("#n_user").val(),
                },
                dataType: 'JSON',
                success:function(data){
                    if(data.status_code == 200){
                        show_notify(data.msg);
                        $("#modalReport").modal("hide");
                            setTimeout(() => {
                                window.location.href = "index.php";
                            },"3000");
                    }else{
                        show_notify_erors(data.msg);
                    }
                },error: function(err){
                   console.log(err);
                }
            });
        }
    });
    

    function delete_report(code){
            Swal.fire({
                title: 'ต้องการลบข้อมูลนี้ใช่หรือไม่ ?',
                showDenyButton: true,
                confirmButtonText: 'ใช่',
                denyButtonText: `ไม่ใช่`,
                }).then((result) => {
                if (result.isConfirmed) {
                                $.ajax({
                                    method: 'POST',
                                    url: 'report_db.php',
                                    data: {
                                    report_delete:code,
                                    code:code
                                    },
                                    dataType: 'JSON',
                                    success: function(data){
                                        if(data.status_code == 200){
                                            show_notify(data.msg);
                                            setTimeout(function(){location.href="index.php"} ,3000);    
                                        }else{
                                            show_notify_erors(data.msg)
                                            setTimeout(function(){location.href="index.php"} ,3000);  
                                        }
                                    },error: function(err){
                                        console.log(err);
                                    }
                            });
                    } else if (result.isDenied) {
                        show_notify_info("ยกเลิกเรียบร้อยแล้ว");
                    }
                })
         }
   
</script>
