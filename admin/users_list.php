<?php
include('users_list_db.php');
?>
<section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-solid fa-users"></i>
                ผู้ใช้งานทั้งหมด
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
        <table id="userlist_table" class="table table-bordered table-striped dataTable">
                <thead>
                    <tr role="row" class="info">
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ชื่อ-สกุล</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">เบอร์โทรศัพท์</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">อีเมลล์</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">การใช้งาน</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">บทบาท</th>
                        <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_users as $row) { ?>
                    <tr>
                        <td>
                           
                                <?php echo $row['first_name'] .' '.$row['last_name'] ?>
                           
                        </td>
                        <td>
                            <?php echo $row['phone_number']  ?>
                        </td>
                        <td>
                            <?php echo $row['email']  ?>
                        </td>
                        <td class="project-state">
                            <?php echo($row['is_active'] == 'Y') ? 
                                '<span class="badge badge-success">เปิดใช้งาน</span>' : 
                                '<span class="badge badge-danger">ปิดใช้งาน</span>'; 
                            ?> 
                        </td>
                        <td> 
                                <input type="checkbox" id="toggle" onchange="toggle_check(<?= $row['id']?>)" 
                                <?php echo($row['is_active'] == 'Y') ? 'checked' : ''; ?> 
                                data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="mini">
                        </td>
                        <td>
                            <span class="badge badge-primary" style="font-size: 100%;">
                                    <?php echo $row['title']?>
                            </span>
                        </td>
                        <td class="project-actions text-right">

                            <a class="btn btn-primary btn-sm" onclick="modalInfo(<?= $row['id'].','.$row['role_id']?>)">
                                <i class="fas fa-folder">
                                </i>
                                ดูข้อมูล
                            </a>
                            <a class="btn btn-info btn-sm" onclick="modalEdit(<?= $row['id'].','.$row['role_id']?>)">
                                <i class="fas fa-pencil-alt">
                                </i>
                                แก้ไข
                            </a>
                            <a class="btn btn-danger btn-sm" onclick="delete_user(<?= $row['id']?>)">
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
    <!-- modal -->
        <div class="modal fade" id="modalEdit">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form accept-charset="utf-8">
                    
                        <div class="modal-header">
                            <h4 class="modal-title">ข้อมูลผู้ใช้งาน</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                
                        <div class="container">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">สกุล</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control"  id="phone_number" name="phone_number" value="" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">อีเมลล์</label>
                                    <input type="text" class="form-control"   id="email" name="email" value="" >
                                </div>
                                <div class="form-group col-md-12">
                                    <label>บทบาท</label>
                                    <select class="form-control select2bs4 select2-hidden-accessible" style="width: 100%;" 
                                        tabindex="-1" aria-hidden="true"id="select_role_id" name="role">
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal-footer" id="modal_footer">
                            <input type="hidden" id="user_submit_check" value="user_submit">
                            <input type="hidden" id="user_id" value="">
                            <button type="button" class="btn btn-warning" id="user_submit">แก้ไข</button>
                            <button type="button" class="btn btn-primary" id="user_submiting" disable style="display:none">กำลังแก้ไข</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     <!-- modal -->

    <?php include("../layouts/script.php"); ?>

    <script>
        // ปิดเปิดสถานะการใช้งาน
        function toggle_check(id) {
                $.ajax({
                method: 'POST',
                url: 'users_list_db.php',
                data: {
                    id: id,
                    update_status:"update"

                },
                success: function(data){
                    show_notify(data);
                    setTimeout(function(){location.href="index.php?act=users"} ,1000);   
                }
            });
        }
        //จบปิดเปิดสถานะการใช้งาน

        //แก้ไขข้อมูล
        $("#user_submit").click(function(){
            
            if($('#first_name').val().length<1){
                msg = "กรุณากรอกชื่อ";
                show_notify_erors(msg);
            }else{
                var v_f = true;
            }

            if($('#last_name').val().length<1){
                msg = "กรุณากรอกนามสกุล";
                show_notify_erors(msg);
            }else{
                var v_l = true;
            }
            
            if($('#phone_number').val().length<1){
                msg = "กรุณากรอกเบอร์โทรศัพท์";
                show_notify_erors(msg);
            }else{
                var v_p = true;
            }

            if($('#email').val().length<1){
                msg = "กรุณากรอกอีเมลล์";
                show_notify_erors(msg);
            }else{
                var v_e = true;
            }

            if($('#select_role_id').val().length<1){
                msg = "กรุณาเลือกบทบาท";
                show_notify_erors(msg);
            }else{
                var v_r = true;
            }

            if(v_f==true&&v_l==true&&v_p==true&&v_e==true&&v_r==true){
                $("#user_submiting").show();
                $("#user_submit").hide();
                $.ajax({
                            method: 'POST',
                            url: 'users_list_db.php',
                            data: {
                            user_submit:$('#user_submit_check').val(),
                            first_name:$('#first_name').val(),
                            last_name:$('#last_name').val(),
                            phone_number:$('#phone_number').val(),
                            email:$('#email').val(),
                            role:$('#select_role_id').val(),
                            id:$("#user_id").val()
                            },
                            dataType: 'JSON',
                            success: function(data){
                            if(data.status_code == 200){
                                show_notify(data.msg);
                                $("#modalEdit").modal('hide');
                                $('.modal-backdrop').remove();
                                setTimeout(function(){location.href="index.php?act=users"} ,1000);    
                            }else{
                                show_notify_erors(data.msg)
                                $("#modalEdit").modal('hide');
                                $('.modal-backdrop').remove();
                                setTimeout(function(){location.href="index.php?act=users"} ,1000);  
                            }
                            },error: function(err){
                                console.log(err);
                            }
                    });
            }
            
        });
        //ปิดแก้ไขข้อมูล

         //modal แก้ไข
        function modalEdit(id,role_id){
                $("#modal_footer").show();
                $("#user_id").val('');
                $("#first_name").val('');
                $("#last_name").val('');
                $("#phone_number").val('');
                $("#email").val('');
                $('#first_name').prop("disabled",false);
                $('#last_name').prop("disabled",false);
                $('#phone_number').prop("disabled",false);
                $('#email').prop("disabled",false);
                $('#select_role_id').prop("disabled",false);
                $("#user_submiting").hide();
                $("#user_submit").show();
                    $.ajax({
                        method: 'GET',
                        url: 'users_list_db.php',
                        data: {
                            get_submit:$('#user_submit_check').val(),
                            id:id
                        },
                        dataType: 'JSON',
                        success: function(data){  
                                $("#user_id").val(data[0].id);
                                $("#first_name").val(data[0].first_name);
                                $("#last_name").val(data[0].last_name);
                                $("#phone_number").val(data[0].phone_number);
                                $("#email").val(data[0].email);
                                $('#select_role_id').html('');
                                $.each(data[1], function (indexInArray, role) {
                                    if(parseInt(role.id) == role_id){
                                        $('#select_role_id').append('<option selected="selected" value="'+role.id+'">'+role.title+'</option>');
                                    }else{
                                        $('#select_role_id').append('<option value="'+role.id+'">'+role.title+'</option>');
                                    }
                                });
                            }
                    });
            $("#modalEdit").modal("show");
        }
        //ปิด modal แก้ไข

         //modal แสดงข้อมูล
        function modalInfo(id,role_id){
                $('#first_name').prop("disabled",true );
                $('#last_name').prop("disabled",true);
                $('#phone_number').prop("disabled",true);
                $('#email').prop("disabled",true);
                $('#select_role_id').prop("disabled",true );
                $("#modal_footer").hide();
                $("#user_id").val('');
                $("#first_name").val('');
                $("#last_name").val('');
                $("#phone_number").val('');
                $("#email").val('');
                    $.ajax({
                        method: 'GET',
                        url: 'users_list_db.php',
                        data: {
                            get_submit:$('#user_submit_check').val(),
                            id:id
                        },
                        dataType: 'JSON',
                        success: function(data){  
                                $("#user_id").val(data[0].id);
                                $("#first_name").val(data[0].first_name);
                                $("#last_name").val(data[0].last_name);
                                $("#phone_number").val(data[0].phone_number);
                                $("#email").val(data[0].email);
                                $('#select_role_id').html('');
                                $.each(data[1], function (indexInArray, role) {
                                    if(parseInt(role.id) == role_id){
                                        $('#select_role_id').append('<option selected="selected" value="'+role.id+'">'+role.title+'</option>');
                                    }
                                });
                            }
                    });

            $("#modalEdit").modal("show");
        }
         //ปิด modal แสดงข้อมูล

         //ลบข้อมูล
         function delete_user(id){
            Swal.fire({
                title: 'ต้องการลบข้อมูลนี้ใช่หรือไม่ ?',
                showDenyButton: true,
                confirmButtonText: 'ใช่',
                denyButtonText: `ไม่ใช่`,
                }).then((result) => {
                if (result.isConfirmed) {
                                $.ajax({
                                    method: 'POST',
                                    url: 'users_list_db.php',
                                    data: {
                                    user_delete:id,
                                    id:id
                                    },
                                    dataType: 'JSON',
                                    success: function(data){
                                        if(data.status_code == 200){
                                            show_notify(data.msg);
                                            setTimeout(function(){location.href="index.php?act=users"} ,3000);    
                                        }else{
                                            show_notify_erors(data.msg)
                                            setTimeout(function(){location.href="index.php?act=users"} ,3000);  
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
          //ปิด ลบข้อมูล
    </script>