<?php
    include('../connect_db.php');

    $sql_profile = "SELECT first_name,last_name,phone_number,email,line_id,profile_img,line_validate FROM users WHERE id =$user_id";
    $result_profile = mysqli_query($conn,$sql_profile);
    $profile = mysqli_fetch_array($result_profile);

?>
<section class="col-lg-12 connectedSortable">
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
            <i class="far fa-id-card"></i>
                โปรไฟล์ 
                
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12">

                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="<?php echo $profile['profile_img'] == null ?  '../dist/img/user2-160x160.jpg' : $profile['profile_img']?>"
                                alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">
                            <?php echo $profile['first_name'] . ' '. $profile['last_name']?></h3>
                        <a class="btn btn-primary btn-block" onclick="modalEdit()"><b>แก้ไขข้อมูล</b></a>
                    </div>

                </div>


                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">ข้อมูลส่วนตัว</h3>
                    </div>

                    <div class="card-body">
                        <strong><i class="fas fa-mail-bulk mr-1"></i>Email</strong>
                        <p class="text-muted">
                            <?php echo $profile['email'] ?>
                        </p>
                        <hr>
                        <strong><i class="fas fa-solid fa-mobile mr-1"></i>เบอร์โทรศัพท์</strong>
                        <p class="text-muted"> <?php echo $profile['phone_number'] ?></p>
                        <hr>
                        <strong><i class="fa fa-cogs mr-1"></i>รับการแจ้งเตือนผ่านไลน์</strong>
                        <?php
                                    if($profile['line_id'] == null && $profile['line_validate'] == 'N'){
                                        require_once('../line/LineLogin.php');
                                        $line = new LineLogin();
                                       
                                        $link = $line->getLink($user_id);
                                        echo '
                                        <p class="text-muted ">ยังไม่ได้ผูกบัญชีไลน์</p><a class="btn btn-primary btn-sm" href="',$link,'">
                                        <i class="fas fa-folder">
                                        </i>
                                            ผูกบัญชี
                                        </a>';
                                    }else if($profile['line_id'] != null && $profile['line_validate'] == 'N'){
                                        $line_id=$profile['line_id'];
                                        echo '<p class="text-muted">ผูกบัญชีไลน์แล้วแต่ยังไม่กดติดตามไลน์เพจ</p>
                                        <a class="btn btn-primary btn-sm" onclick="pushMessage(\''.$user_id.'\')">
                                        <i class="fas fa-folder">
                                        </i>
                                            ตรวจสอบการแจ้งเตือน
                                        </a>';
                                    }else{
                                        echo '<p class="text-muted">ผูกบัญชีสำเร็จ</p>';
                                    }
                                ?>
                        <!--  -->

                    </div>

                </div>

            </div>
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>


<div class="modal fade" id="modalStep" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">แสกน QR เพื่อเพิ่มเพื่อนบอท</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="../dist/img/QR_CODE_LINE.png" alt="">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div id="btn-check">
                 
                </div>
            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form accept-charset="utf-8">
                    
                        <div class="modal-header">
                            <h4 class="modal-title">ข้อมูลผู้ใช้งาน</h4>
                            <button type="button" class="close" onclick="closemodal()">&times;</button>
                        </div>
                
                        <div class="container">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $profile['first_name']?>" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">สกุล</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $profile['last_name'] ?>" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control"  id="phone_number" name="phone_number" value="<?php echo $profile['phone_number'] ?>" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">อีเมลล์</label>
                                    <input type="text" class="form-control"   id="email" name="email" value="<?php echo $profile['email'] ?>" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control"   id="password" name="password" value="" >
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal-footer" id="modal_footer">
                            <input type="hidden" id="user_id" value="<?php echo $user_id ?>">
                            <button type="button" class="btn btn-success" id="user_update">แก้ไขข้อมูล</button>
                            <button type="button" class="btn btn-primary" id="saving" style="display: none;" disabled>กำลังบันทึก</button>
                            <button type="button" class="btn btn-danger" onclick="closemodal()">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


<?php include("../layouts/script.php"); ?>

<script>

function pushMessage(user_id) {
    $.ajax({
        method: 'POST',
        url: '../line/pushMessage.php',
        data: {
            push_message: "ทดสอบการแจ้งเตือน",
            user_id: user_id
        },
        dataType: 'JSON',
        success: function(data) {

            if(data.status_code == 200){
                show_notify(data.msg);
                setTimeout(function() {
                    location.href = "index.php?act=profile"
                }, 3000);
            }else{
                show_notify_info(data.msg);
                $("#btn-check").html('');
                $("#btn-check").html('<button type="button" class="btn btn-primary" onclick="pushMessage(\''+user_id+'\')">ตรวจสอบการแจ้งเตือน</button>');
                $("#modalStep").modal('show');
            }
           
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function modalStep() {
    $("#modalStep").modal('show');
}

function modalEdit(user_id){
    $("#modalEdit").modal('show');
}

function closemodal(){
    setTimeout(function() {
     location.href = "index.php?act=profile"
     },1000);
    $("#modalEdit").modal('hide');
}

$("#user_update").click(function(){

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


            if(v_f==true&&v_l==true&&v_p==true&&v_e==true){
                $("#user_update").hide();
                $("#saving").show();
                $.ajax({
                            method: 'POST',
                            url: 'profile_db.php',
                            data: {
                            user_update:"update",
                            first_name:$('#first_name').val(),
                            last_name:$('#last_name').val(),
                            phone_number:$('#phone_number').val(),
                            email:$('#email').val(),
                            password:$('#password').val(),
                            id:$("#user_id").val()
                            },
                            dataType: 'JSON',
                            success: function(data){
                            if(data.status_code == 200){
                                show_notify(data.msg);
                                setTimeout(function() {
                                    location.href = "index.php?act=profile"
                                },3000);
                                $("#modalEdit").modal('hide'); 
                            }else{
                                show_notify_erors(data.msg)
                                setTimeout(function() {
                                    location.href = "index.php?act=profile"
                                },3000);
                                $("#modalEdit").modal('hide'); 
                            }
                            },error: function(err){
                                console.log(err);
                            }
                    });
            }
});


</script>