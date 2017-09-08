<style>

  input[type="radio"] {
   display:inline-block;
   width:30px;
   height:30px;
   margin:-5px 5px 0 0;
   vertical-align:middle;
   cursor:pointer;
 }

 input[type="radio"]:checked {
  background:url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/check_radio_sheet.png) -57px top no-repeat;
}
</style>

<div class="modal fade" id="bankPickerModal" role="dialog">
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header" style="background-color:#585858">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เลือกธนาคาร</h4>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody>
          <?php foreach ($bank as $b): ?>
            <tr>
              <td style="padding-top:20%;padding-left:10px">
               <input type="radio" id="r1" name="optradio[]" value="<?php echo $b->id_account; ?>"/>
             </td>
             <td>
              <img id="bank_img" src="<?php echo  base_url()."img" ?>/bank/<?php echo $b->bankcode; ?>.png" alt="">
            </td>
            <td style="color:#0B0B3B;font-size:14px">
              <p><span id="b_name" ><?php echo $b->bank_name; ?></span></p>
              <p>เลขที่ : <span id="a_no" style="color:red;font-size:18px"><?php echo $b->acc_no; ?></span></p>
              <p>ชื่อบัญชี : <span id="a_name"><?php echo $b->acc_name; ?></span></p>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
   <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
   <button type="button" id="btnChooseBank" class="btn btn-success" data-toggle="modal" data-target="#paymentModal" data-dismiss="modal" onclick="checked()" disabled="">ต่อไป</button>
 </div>
</div>
</div>
</div>


<div class="modal fade" id="paymentModal" role="dialog">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#585858">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">แจ้งการโอนเงิน</h4>
      </div>
      <form id="uploadSlip" name="uploadSlip" enctype="multipart/form-data" method="post">

        <input type="text" class="hidden" id="bankSelect" value="">
        <input type="text" class="hidden" id="bankImg" value="">

        <div class="modal-body">
          <h3>จำนวนเงิน <span style="color:red;font-size:26px;font-weight:800"><?php echo number_format($total_amount, 2); ?> </span> บาท</h3>
          <legend></legend>
          <div class="row">
            <CENTER>
              <div class="col-md-5 col-xs-12 center-block">
                <img id="bn" src="" alt="" style="margin-bottom:10px">
              </div>
              <div class="col-md-7" style="color:#0B0B3B;font-size:14px">
                <h4>ธนาคาร: <span id="bank_name"></span></h4>
                <h4>เลขที่: <span id="bank_no" style="color:red;font-size:18px"></span></h4>
                <h4>ชื่อบัญชี: <span id="bank_acc"></span></h4>
              </div>
            </CENTER>
          </div>
          <legend></legend>
          <div class="row">
            <input type="file" name="image" id="image" accept="image/*" style="display:none;" />
            <div class="col-md-4 col-md-offset-1">
              <span>แนบสลิป</span>
            </div>
            <div class="col-md-7 ">
              <button type="button" class="btn btn-block btn-primary" id="btn-select-file" onClick="selectFile()">
                <i class="fa fa-file-image-o"></i> เลือกรูปภาพ</button>
                <div id="block-image" style="opacity:0;">
                  <div id="previewImg" ></div>
                  <span onClick="removeFile()" style="position:absolute; left:215px; top:-15px; cursor:pointer; color:red;">
                    <i class="fa fa-times fa-2x"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-4 col-md-offset-1" style="margin-top:5px">
                <span>ยอดเงิน</span>
              </div>
              <div class="col-md-7" style="margin-top:5px">
                <div class="input-group ">
                  <input type="text" class="form-control">
                  <span class="input-group-addon"><i class="fa fa-btc" aria-hidden="true"></i></span>
                </div>
              </div>

              <div class="col-md-4 col-md-offset-1" style="margin-top:5px">
                <span>วันที่โอน</span>
              </div>
              <div class="col-md-7" style="margin-top:5px">
                <div class="input-group ">
                  <input type="text" class="form-control">
                  <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                </div>
              </div>

              <div class="col-md-4 col-md-offset-1" style="margin-top:5px">
                <span>เวลาที่โอน</span>
              </div>
              <div class="col-md-7" style="margin-top:5px">
                <div class="input-group ">
                  <input type="text" class="form-control">
                  <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer">
           <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
           <button type="button" class="btn btn-info" data-toggle="modal" data-target="#transportPickerModal" data-dismiss="modal">เลือกช่องทางการจัดส่ง</button>         
         </div>
       </div>
     </form>
   </div>
 </div>


 <div class="modal fade" id="transportPickerModal" role="dialog">
   <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#585858">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ช่องทางการขนส่ง</h4>
      </div>
      <div class="modal-body">
       <table class="table">
        <tbody>
          <tr>
            <td>1</td>
            <td style="padding-left:10px">
              <input type="radio" name="transType[]" value="1" checked>
            </td>
            <td>
              <p>EMS</p>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td style="padding-left:10px">
              <input type="radio" name="transType[]" value="2">
            </td>
            <td>
              <p>Kerry Express</p>
            </td>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
  <div class="modal-footer">
   <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
   <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addrPickerModal" data-dismiss="modal">ต่อไป</button>
 </div>
</div>
</div>
</div>


<div class="modal fade" id="addrPickerModal" role="dialog">
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header" style="background-color:#585858">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เลือกที่อยู่</h4>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody>
          <tr>
            <td style="padding-top:40px;padding-left:10px">
              <input type="radio" name="optradio">
            </td>
            <td>
              <h4>คุณ มานพ ดวงดี</h4>
              <p>เลขที่ 11/241 หมู่1 ตำบลห้วยหวาย อ.เมือง จังหวัด มหาสารคาม 42571</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
     <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addNewAddr" data-dismiss="modal">เพิ่มที่อยู่</button>

     <button type="button" class="btn btn-default" onclick="">ตกลง</button>

   </div>
 </div>
</div>
</div>

<div class="modal fade" id="addNewAddr" role="dialog" >
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header" style="background-color:#585858">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เพิ่มที่อยู่</h4>
    </div>
    <?php 
    $attributes = array('id' => 'form_add_addr');
    echo form_open("shop/Register/add_address" , $attributes); 
    ?>
    <div class="modal-body">
      <div class="form-group">
        <div>
          <label for="fname">ชื่อ</label>
          <input name="fname" id="fname" class="form-control input" value="<?= set_value("fname") ?>" placeholder="First Name" type="text">
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="lname">สกุล</label>
          <input name="lname" id="lname" class="form-control input" value="<?= set_value("lname") ?>" placeholder="Last Name" type="text">
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="tel">โทร</label>
          <input name="tel" id="tel" class="form-control input" value="<?= set_value("tel") ?>" placeholder="tel" type="tel">
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="addr">ที่อยุ่</label>
          <textarea name="addr" id="addr" class="form-control input" rows="3" ></textarea>
        </div>
      </div>

      <div class="form-horizontal">
        <div class="form-group">
          <label class="control-label col-sm-3" for="Proviance">จังหวัด : </label>
          <div class="col-sm-7">
            <select name="Proviance" id="Proviance" class="form-control">
              <option value="0" selected="selected">---เลือกจังหวัด---</option>
            </select>
          </div>
          <input type="text" name="ProID" id="ProID" hidden="" />
        </div>
      </div>

      <div class="form-horizontal">
        <div class="form-group">
          <label class="control-label col-sm-3" for="District">อำเภอ : </label>
          <div class="col-sm-7">
            <select name="District" id="District" class="form-control">
              <option value="0" selected="selected">---เลือกอำเภอ---</option>
            </select>
          </div>
          <input type="text" name="DisID" id="DisID" hidden="" />
        </div>
      </div>

      <div class="form-horizontal">
        <div class="form-group">
          <label class="control-label col-sm-3" for="Subdistrict">ตำบล : </label>
          <div class="col-sm-7">
            <select name="Subdistrict" id="Subdistrict" class="form-control" >
              <option value="0" selected="selected">---เลือกจังตำบล---</option>
            </select>
          </div>
          <input type="text" name="SubID" id="SubID" hidden="" />
        </div>
      </div>

      <div class="form-horizontal">
        <div class="form-group">
          <label class="control-label col-sm-3" for="Postcode">POSTCODE : </label>
          <div class="col-sm-7">
            <select name="Postcode" id="Postcode" class="form-control">
              <option value="0" selected="selected">---เลือกรหัสไปรษณีย์---</option>
            </select>
          </div>
          <input type="text" name="PostID" id="PostID" hidden="" />
        </div>
      </div>
    </div>  <!-- modal body -->
    <div class="modal-footer">
     <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
     <button type="button" class="btn btn-info" id="smt_addr">ต่อไป</button>
   </div>
   <?php echo form_close(); ?>
 </div>
</div>
</div>


<script>

  $(document).ready(function() {
    $('input[name="optradio[]"]').on('change', function() {
      $("#btnChooseBank").prop('disabled', false);
    });
  }); 

  function checked(){
   var IsChecked = $('input[name="optradio[]"]:checked').val();

   $("#bankSelect").val(IsChecked);
   //   /invent/img/bank/KBANK.png

   var imgSRC = $('#bank_img').attr('src');
   var b_name = $("#b_name").html();
   var a_no   = $("#a_no").html();
   var a_name = $("#a_name").html();

   $("#bank_name").html(b_name);
   $("#bank_no").html(a_no);
   $("#bank_acc").html(a_name);

   $("#bn").attr("src",imgSRC);

 }

$("#smt_addr").click(function() {
  
  var base_url  = window.location.origin;
  var fname     = $("#fname").val();
  var lname     = $("#lname").val();
  var tel       = $("#tel").val();
  var addr      = $("#addr").val();
  var Proviance = $("#ProID").val();
  var District  = $("#DisID").val();
  var Subdistrict = $("#SubID").val();
  var Postcode  = $("#PostID").val();

  $.ajax({
    type: "POST",
    url:base_url+"/invent/shop/Register/add_address",
    data: ({
      fname:fname,
      lname:lname,
      tel:tel,
      addr:addr,
      Proviance:Proviance,
      District:District,
      Subdistrict:Subdistrict,
      Postcode:Postcode,
    }), 
    success: function(data){
      console.log(data);
    },
    error: function(e) {
      console.log("Error posting feed."); 
    }
  });

});
</script>























