
<div class="modal signUpContent fade" id="ModalLogin" tabindex="-1" role="dialog" >
  <div class="modal-dialog" style="min-width:300px;text-align:center;">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#585858">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h3 class="modal-title-site text-center"> Login to SHOP </h3>
      </div>
      <div class="modal-body">
        <div class="form-group login-username">
          <center>
            <div>
              <input name="username" id="username" class="form-control" placeholder="Enter Username" type="text">
              <span class="texx-danger"><?php echo form_error("username") ?></span>
            </div>
          </center>
        </div>
  
        <div class="form-group login-password">
          <center>
            <div>
              <input name="password" id="password" class="form-control" placeholder="Password" type="password">
               <span class="texx-danger"><?php echo form_error("password") ?></span>
            </div>
          </center>
        </div>
        <div class="form-group">
          <div class="checkbox login-remember" >
            <input name="rememberme" id="rememberme" value="forever" checked="checked" type="checkbox" style="width:20px;float:left;"><span> Remember Me </span>
          </div>
        </div>
        <div>
          <div>
           <button type="button" class="btn btn-block btn-lg btn-primary" onClick="login()">LOGIN</button>
         </div>
       </div>
       <!--userForm-->

     </div><!-- modal body -->
     <div class="modal-footer">
      <p class="text-center"> <a href="<?= base_url()?>shop/forgot_password">ลืมรหัสผ่าน ? </a></p>
    </div><!-- modal-footer -->
  </div><!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
</div><!-- /.Modal Login -->


<script>
 function login()
 {
  $("#err_user").css("display", "none");
  $("#err_pass").css("display", "none");

  var user = $("#username").val();
  var pass = $("#password").val();
  var id_customer = $("#id_customer").val();


  if( $("#rememberme").is(":checked") )
  {
    var rmbm = 1;
  }
  else
  {
    var rmbm = 0;
  }
 
  if( user == ""){ $("#err_user").css("display", ""); $("#username").focus(); return false;}
  if( pass == ""){ $("#err_pass").css("display", ""); $("#password").focus(); return false; }
  load_in();
    $.ajax({
      url:"<?php echo base_url(); ?>shop/member/loged_in",
      type:"POST", 
      cache: "false", 
      data:{ 
        "user_name" : user, 
        "password" : pass, 
        "rememberme" : rmbm,
        "id_customer":id_customer,
      },
      success: function(rs){
       load_out();
       // $('#ModalLogin').modal('toggle');
       // login_status(rs);	

       console.log(rs);

      },error:function(e) {
       console.log("error");
       load_out();
      }
  });
}

function login_status(msg)
{	

  if(msg == "success"){
    swal({
      title: "SUCCESS !",
      text: "เข้าสู่ระบบสำเร็จ",
      type: "success",
      timer:2000,
    });
    setTimeout(function(){
      window.location.href = "<?php echo site_url('shop/'); ?>";
    },2100);

  }else if(msg == "online"){
    swal({
      title: "WARNING !",
      text: "บัญชีมีการเข้าสู่ระบบอยู่",
      type: "warning",
      timer:2500,
    });
  }else if(msg == "fail"){
    swal({
      title: "Error!",
      text: "ยังไม่ได้เป็นสมาชิก",
      type: "error",
      timer:2500,
    });
  }

}

</script>