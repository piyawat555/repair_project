<?php
    include('../connect_db.php');

    $sql_user_report = "SELECT tr.*,ts.status_name,u.first_name,u.last_name,u.phone_number FROM tbl_reports tr 
                            INNER JOIN tbl_status ts ON ts.status_id = tr.status 
                                INNER JOIN users u ON u.id = tr.user_id
                                    WHERE tr.status>2 AND tr.technician_id=$user_id AND tr.is_active='Y'";
    $reports = mysqli_query($conn,$sql_user_report);

?>
<section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tachometer-alt"></i>
                รายการดำเนินการซ่อม
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
            <table id="working_list" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr role="row" class="info">
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">รหัสการแจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">วันที่แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ปัญหาที่พบ - รายละเอียดปัญหา</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานที่</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ผู้แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">--</th>
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
                                <?php echo $list_report['first_name'] . ' ' .$list_report['last_name'] ?> <br>
                                <small> <?php echo "เบอร์ติดต่อ : " .$list_report['phone_number'] ?> </small> 
                            </td>
                            <td>
                                <span class="badge badge-primary" style="font-size: 100%;">
                                    <?php echo $list_report['status_name'] ?>
                                </span>
                            </td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="index.php?act=history&code=<?php echo $list_report['code']; ?>">
                                    <i class="fas fa-folder">
                                    </i>
                                    ดูข้อมูล
                                </a>
                                <?php  if($list_report['status']==3){ ?>
                                    <a class="btn btn-info btn-sm" onclick="modalCloseWork('<?= $list_report['code']?>')">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        ปิดงาน
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php  }?>
                    </tbody>
                </table>
            </div><!-- /.card-body -->
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>

    <!-- modal -->
    <div class="modal fade" id="modalCloseWork">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form accept-charset="utf-8">
                    
                        <div class="modal-header">
                            <h4 class="modal-title">กรอกข้อมูลปิดงาน</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                
                        <div class="container">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">สาเหตุ</label>
                                    <input type="text" class="form-control" id="cause" name="cause" value="" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">วิธีแก้ไข</label>
                                    <input type="text" class="form-control" id="solution" name="solution" value="" >
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal-footer" id="modal_footer">
                            <input type="hidden" name="code_close" id="code_close" >
                            <button type="button" class="btn btn-success" id="close_submit">ปิดงาน</button>
                            <button type="button" class="btn btn-warning" id="closing" style="display: none">กำลังปิดงาน</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     <!-- modal -->

<?php include("../layouts/script.php"); ?>

<script>
    function modalCloseWork(code){
        $("#code_close").val('');
        $("#cause").val('');
        $("#solution").val('');
        $("#closing").hide();
        $("#close_submit").show();
        $("#code_close").val(code);
        $("#modalCloseWork").modal("show");
    }

    $("#close_submit").click(function(){
        
        if($("#cause").val().length > 0){
            var c_v = true;
        }else{
            toastr.error("กรุณากรอกสาเหตุที่พบ");
        }

        if($("#solution").val().length > 0){
            var s_v = true;
        }else{
            toastr.error("กรุณากรอกการแก้ปัญหาที่พบ");
        }

        if(s_v == true && c_v == true){
            $("#closing").show();
            $("#close_submit").hide();

            $.ajax({
                method: 'POST',
                url: 'technician_db.php',
                data: {
                    cause:$('#cause').val(),
                    solution:$('#solution').val(),
                    code:$('#code_close').val()
                    },
                dataType: 'JSON',
                success: function(data){
                    if(data.status_code == 200){
                        show_notify(data.msg);
                        $("#modalCloseWork").modal('hide');
                        $('.modal-backdrop').remove();
                        setTimeout(function(){location.href="index.php?act=working"} ,3000);    
                    }else{
                        how_notify_erors(data.msg)
                        $("#modalCloseWork").modal('hide');
                        $('.modal-backdrop').remove();
                        setTimeout(function(){location.href="index.php?act=working"} ,3000);  
                    }
                },error: function(err){
                        console.log(err);
                }
            });
        }
    
    });
</script>
<?php

if($_GET['act']=='working'&&$_GET['add']=='success'){  
    echo '
    <script>
    setTimeout(() => {
        window.location.href = "index.php?act=working";
    }, "3000");
    toastr.success("รับงานสำเร็จ");
    </script>';
}

?>