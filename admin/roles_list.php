
<?php
 include('role_db.php');

 $sql_show_roles = "SELECT * FROM roles";
 $result_roles= mysqli_query($conn,$sql_show_roles);
 $count= 1;
?>
<section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <button type="button" class="btn btn-success btn-sm mb-2" onclick="modalAdd()">
        + เพิ่มข้อมูล
    </button>
    <!-- <button type="button" class="btn btn-success toastrDefaultSuccess">
        Launch Success Toast
        </button> -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i>
                รายการบทบาท
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped dataTable">
                <thead>
                    <tr role="row" class="info">
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">ลำดับ</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ชื่อบทบาท</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">การใช้งาน</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_roles as $row) { ?>
                    <tr>
                        <td>
                            <?php echo $count++; ?>
                        </td>
                        <td>
                            <?php echo $row['title']?>
                        </td>
                        <td class="project-state">
                            <?php echo($row['status'] == 'Y') ? 
                                '<span class="badge badge-success">เปิดใช้งาน</span>' : 
                                '<span class="badge badge-danger">ปิดใช้งาน</span>'; 
                            ?> 
                        </td>
                        <td> 
                                <input type="checkbox" id="toggle" onchange="toggle_check(<?= $row['id']?>)" 
                                <?php echo($row['status'] == 'Y') ? 'checked' : ''; ?> 
                                data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="mini">
                        </td>
                        <td class="project-actions text-right">


                            <a class="btn btn-primary btn-sm"  onclick="modalInfo(<?= $row['id']?>)">
                                <i class="fas fa-folder">
                                </i>
                                ดูข้อมูล
                            </a>
                            <a class="btn btn-info btn-sm"  onclick="modalEdit(<?= $row['id']?>)">
                                <i class="fas fa-pencil-alt">
                                </i>
                                แก้ไข
                            </a>
                            <a class="btn btn-danger btn-sm" onclick="delete_role(<?= $row['id']?>)">
                                <i class="fas fa-trash">
                                </i>
                                ลบ
                            </a>
                        </td>

                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form accept-charset="utf-8">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มข้อมูล </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="container">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="recipient-name" class="col-form-label">ชื่อบทบาท</label>
                            <input type="text" class="form-control" id="title_role" name="title" value="" required>
                            <input type="hidden" class="form-control" id="role_id_update" value="">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer" id="modalfooter">
                    <input type="hidden" name="u_level" value="worker">
                    <!-- toastrDefaultSuccess -->
                    <button type="button" class="btn btn-success" id="role_submit">บันทึก</button>
                    <button type="button" class="btn btn-warning" id="role_submiting" style="display: none">กำลังบันทึก</button>
                    <button type="button" class="btn btn-warning" id="role_update" style="display: none">อัพเดท</button>
                    <button type="button" class="btn btn-success" id="role_updating" disable style="display: none">กำลังอัพเดท</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../layouts/script.php"); ?>

 
<script>

    function toggle_check(id) {
        $.ajax({
        method: 'POST',
        url: 'role_db.php',
        data: {
            id: id,
            update_role:"update"
        },
        success: function(data){
            show_notify(data);
            setTimeout(function(){location.href="index.php?act=roles"} ,1000);   
        }
    });
  }

  function modalAdd(){
    $("#role_submit").show();
    $("#role_submiting").hide();
    $('#title_role').prop("disabled",false);
    $('#modalfooter').show();
    $("#role_submit").show();
    $("#role_update").hide();
    $("#title_role").val('');
    $("#role_id_update").val('');
    $("#myModal").modal('show');
  }

    $("#role_submit").click(function(){
            if($('#title_role').val().length > 0){
                $("#role_submit").hide();
                $("#role_submiting").show();
                $.ajax({
                        method: 'POST',
                        url: 'role_db.php',
                        data: 'title_role='+$('#title_role').val(),
                        success: function(data){
                            show_notify(data);
                            $("#myModal").modal('hide');
                            $('.modal-backdrop').remove();
                            setTimeout(function(){location.href="index.php?act=roles"} ,1000);   
                        },error: function(err){
                            console.log(err);
                        }
                });
            }else{
                show_notify_erors("กรุณากรอกชื่อบทบาท");
            }
    });


    $("#role_update").click(function(){
          if($('#title_role').val().length > 0){
            $("#role_update").hide();
            $("#role_updating").show();
              $.ajax({
                      method: 'POST',
                      url: 'role_db.php',
                      data: {
                        update_title:$('#title_role').val(),
                        id:$('#role_id_update').val()
                      },
                      dataType: 'JSON',
                      success: function(data){
                        show_notify(data);
                        $("#myModal").modal('hide');
                        $('.modal-backdrop').remove();
                        setTimeout(function(){location.href="index.php?act=roles"} ,1000);   
                      },error: function(err){
                          console.log(err);
                      }
              });
            }else{
                show_notify_erors("กรุณากรอกชื่อบทบาท");
            }
    });
    

    function modalEdit(id){
        $("#role_update").show();
        $("#role_updating").hide();
        $('#title_role').prop("disabled",false);
        $('#modalfooter').show();
            $.ajax({
                method: 'GET',
                url: 'role_db.php',
                data: {
                     role_id:id,
                     id:id
                 },
                dataType: 'JSON',
                success: function(data){  
                    $("#role_submit").hide();
                    $("#role_update").show();
                    $("#title_role").val('');
                    $("#role_id_update").val('');
                    $("#title_role").val(data.title);
                    $("#role_id_update").val(data.id);
                  
                }
            });
            $("#myModal").modal("show");
    }


    function delete_role(id){
            Swal.fire({
                title: 'ต้องการลบข้อมูลนี้ใช่หรือไม่ ?',
                showDenyButton: true,
                confirmButtonText: 'ใช่',
                denyButtonText: `ไม่ใช่`,
                }).then((result) => {
                if (result.isConfirmed) {
                                $.ajax({
                                    method: 'POST',
                                    url: 'role_db.php',
                                    data: {
                                    role_delete:id,
                                    id:id
                                    },
                                    dataType: 'JSON',
                                    success: function(data){
                                        if(data.status_code == 200){
                                            show_notify(data.msg);
                                            setTimeout(function(){location.href="index.php?act=roles"} ,3000);    
                                        }else{
                                            show_notify_erors(data.msg)
                                            setTimeout(function(){location.href="index.php?act=roles"} ,3000);  
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

    function modalInfo(id){
               
                    $.ajax({
                        method: 'GET',
                        url: 'role_db.php',
                        data: {
                            role_id:id,
                            id:id
                        },
                        dataType: 'JSON',
                        success: function(data){  
                                $('#title_role').prop("disabled",true);
                                $("#title_role").val(data.title);
                                $('#modalfooter').hide();
                            }
                    });

            $("#myModal").modal("show");
        }

</script>
