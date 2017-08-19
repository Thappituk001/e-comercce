
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.16.0/jquery.validate.js"></script>

<div class="container main-container headerOffset" id="body">
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7 col-xs-5 text-center-xs hidden-xs">
      <h1 class="section-title-inner"><span><i class="fa fa-shopping-basket"></i> ตะกร้าสินค้า </span></h1>
    </div>
  </div>
  <?php $total_discount = 0; $total_price = 0; $total_amount = 0; ?>
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
          <?php if( isset( $cart_items ) && $cart_items !== FALSE ) : ?>	  

            <div class="cartContent w100 hidden-xs">
              <table class="cartTable table-responsive" id="cart-table" style="width:100%">
                <tbody>
                  <tr class="CartProduct cartTableHeader">
                    <td style="width:15%">สินค้า</td>
                    <td style="width:45%"></td>
                    <td style="width:15%">จำนวน</td>
                    <td style="width:10%">ส่วนลด</td>
                    <td style="width:10%">รวม</td>
                    <td style="width:5%" class="delete">&nbsp;</td>
                  </tr>

                  <?php foreach( $cart_items as $item ) : ?>	

                    <?php 
                    $id_pa = $item->id_pa; 
                    $id_pd = getIdProduct($id_pa);
                    $price = itemPrice($id_pa);
                    $discount = discountAmount($id_pa, $item->qty, $this->id_customer);
                    $total_sell = ($price * $item->qty) - $discount;
                    $dis 		= get_discount($this->id_customer, $id_pd); 
                    $total_price += $price * $item->qty;
                    $total_amount += $total_sell;
                    $total_discount += $discount;
                    $available_qty = apply_stock_filter($this->product_model->getAvailableQty($id_pa)); 
                    ?>									
                    <tr class="CartProduct" id="row_<?php echo $id_pa; ?>" style="font-size:12px;">
                    
                      <td class="CartProductThumb">
                        <div><img src="<?php echo get_image_path( get_id_image($id_pa), 2); ?>" alt="img"></div>
                        <input type="hidden" class="id_pa" value="<?php echo $id_pa; ?>"/>
                      </td>
                      <td>
                        <div class="CartDescription">
                          <h4><?php echo productReference($id_pa); ?> </h4>
                          <span class="size"><?php echo itemName($id_pa); ?></span>
                          <span class="size" style="display:block;"><?php echo attrType($id_pa); ?> : <?php echo attrLabel($id_pa); ?></span>
                          <span id="price_<?php echo $id_pa; ?>" class="price-standard <?php if( $dis['discount'] == 0 ) : ?>hide<?php endif; ?>"><?php echo $price; ?></span>
                          <div class="price" id="sell-price_<?php echo $id_pa; ?>"><?php echo number_format(sell_price($price, $dis['discount'], $dis['type']),2); ?></div>
                        </div>
                      </td>

                      <td>
                       <div class="input-group">
                        <span class="input-group-btn">
                          <button class="btn btn-xs decrease-btn" type="button"  onClick="decreaseQty(<?php echo $id_pa; ?>, 1)"><i class="fa fa-minus"></i></button>
                        </span>
                        <span id="Qty_<?php echo $id_pa; ?>" class="form-control" style="text-align:center; height:36px;"><?php echo $item->qty; ?></span>
                        <span class="input-group-btn">
                          <button class="btn btn-xs increase-btn" type="button" onClick="increaseQty(<?php echo $id_pa; ?>, <?php echo $available_qty; ?>)"><i class="fa fa-plus"></i></button>
                        </span>
                      </div><!-- /input-group -->
                      <span class="stock-label">คงเหลือ <?php echo number_format($available_qty); ?> ในสต็อก</span>
                    </td>
                    <td id="discount_<?php echo $id_pa; ?>"><?php echo number_format($discount, 2); ?></td>
                    <td id="total_sell_<?php echo $id_pa; ?>"><?php echo number_format($total_sell, 2); ?></td>
                    <td><a title="Delete" onClick="deleteCartRow(<?php echo $id_pa; ?>)"> <i class="fa fa-times fa-lg"></i></a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>          
          <!--cartContent-->
          <!-- For mobile -->
          <div class="cartContent w100 hide visible-xs" >
            <table class="table" style="width:100%">
             <tr>
               <td colspan="2" style="width:80%">สินค้า</td>
               <td style="width:20%; text-align:right;">จำนวน</td>
             </tr>
             <?php  foreach( $cart_items as $item ) :   
             $id_pa = $item->id_pa; 
             $id_pd = getIdProduct($id_pa);
             $price = itemPrice($id_pa);
             $discount = discountAmount($id_pa, $item->qty, $this->id_customer);
             $total_sell = ($price * $item->qty) - $discount;
             $dis 		= get_discount($this->id_customer, $id_pd); 
             $available_qty = apply_stock_filter($this->product_model->getAvailableQty($id_pa));  
             ?>
             <tr id="m-row_<?php echo $id_pa; ?>" style="font-size:12px; border-bottom:solid 1px #ccc;">
               <td style="width: 20%; text-align:center; vertical-align:middle;">
                 <div><img src="<?php echo get_image_path( get_id_image($id_pa), 1); ?>" alt="img"></div>
               </td>
               <td>
                 <div class="CartDescription">
                  <span style="font-size:16px; font-weight:bold; display:block;">
                    <?php echo productReference($id_pa); ?> 
                  </span>
                  <span class="size">
                    <?php echo itemName($id_pa); ?>
                  </span>
                  <span class="size" style="display:block;">
                    <?php echo attrType($id_pa); ?> : <?php echo attrLabel($id_pa); ?></span>
                    <span id="m-price_<?php echo $id_pa; ?>" class="price-standard <?php if( $dis['discount'] == 0 ) : ?>hide<?php endif; ?>">
                      <?php echo $price; ?>
                    </span>
                    <div class="price" id="m-sell-price_<?php echo $id_pa; ?>"><?php echo number_format(sell_price($price, $dis['discount'], $dis['type']),2); ?></div>
                    <span class="stock-label" style="top:0px;">คงเหลือ <?php echo number_format($available_qty); ?> ในสต็อก</span>
                    <span style="display:block; font-size:14px;">
                      <a title="Delete" onClick="deleteCartRow(<?php echo $id_pa; ?>)">ลบ</a></span>
                    </div>
                  </td>
                  <td align="center">
                   <button class="btn btn-xs increase-btn" type="button" onClick="increaseQty(<?php echo $id_pa; ?>, <?php echo $available_qty; ?>)"><i class="fa fa-plus"></i></button>
                   <span id="mQty_<?php echo $id_pa; ?>" class="form-control input-xs" style="text-align:center; margin-top:5px; margin-bottom:5px; border-radius:0px;"><?php echo $item->qty; ?></span>
                   <button class="btn btn-xs decrease-btn" type="button" onClick="decreaseQty(<?php echo $id_pa; ?>, 1)"><i class="fa fa-minus"></i></button>  
                 </td>
               </tr>

             <?php  endforeach; ?>		
           </table>			         	
         </div>

       <?php endif; ?>		                    

     </div>
   </div>
   <!--/row end-->

 </div>
 <?php $shipping_cost = delivery_cost($this->cart_qty); ?>
 <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
  <div class="contentBox">
    <div class="w100 costDetails">
      <div class="table-block" id="order-detail-content">
       <div class="w100 cartMiniTable">
        <table id="cart-summary" class="std table">
          <tbody>
            <tr>
              <td>สินค้ารวม</td>
              <td class="price" id="total-price"><?php echo number_format($total_price, 2); ?></td>
            </tr>
            <tr style="">
              <td>ค่าจัดส่ง</td>
              <td class="price" id="shipping-fee"><?php echo number_format($shipping_cost, 2); ?></td>
            </tr>
            <tr class="cart-total-price ">
              <td>ส่วนลดรวม</td>
              <td class="price" id="total-discount"><?php echo number_format($total_discount, 2); ?></td>
            </tr>
            <tr>
              <td>รวมทั้งสิ้น</td>
              <td class=" site-color" id="total-amount" style="font-size:22px; font-weight:bold;"><?php echo number_format( (($total_price - $total_discount) + $shipping_cost), 2); ?> </td>
            </tr>
            <tr>
              <td colspan="2">
                <?php if( isset( $cart_items) && $cart_items != FALSE ) : ?>
                  <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#transportPickerModal"> เลือกช่องทางการจัดส่ง</button>
                  <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#bankPickerModal">แจ้งการโอนเงิน</button>
                <?php endif; ?>
                <button type="button" class="btn btn-success btn-block" id="checkout-btn-bottom" onClick="goToHome()">กลับ</button>
              </td>
            </tr>
          </tbody>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- End popular -->

</div>
<!--/rightSidebar-->

</div>
<!--/row-->

<div style="clear:both"></div>
</div>
<!-- /.main-container -->
<div class="modal fade" id="bankPickerModal" role="dialog">
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เลือกธนาคาร</h4>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody>
          <tr>
            <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio"></td>
            <td><img src="../../images/scb.jpg" alt=""></td>
            <td>
              <p>ธนาคารไทยพาณิชย์</p>
              <p>เลขที่ : 111-1111-1111</p>
              <p>ชื่อบัญชี : วอร์ริกสปอร์ท</p>
            </td>
          </tr>
          <tr>
            <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio"></td>
            <td><img src="../../images/ktb.jpg" alt=""></td>
            <td>
              <p>ธนาคารกรุงไทย</p>
              <p>เลขที่ : 222-1122-1122</p>
              <p>ชื่อบัญชี : วอร์ริกสปอร์ท</p>
            </td>
          </td>
        </tr>
        <tr>
          <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio" checked></td>
          <td><img src="../../images/kbank.jpg" alt=""></td>
          <td>
            <p>ธนาคารกสิกร</p>
            <p>เลขที่ : 233-1333-3333</p>
            <p>ชื่อบัญชี : วอร์ริกสปอร์ท</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#paymentModal" data-dismiss="modal">ต่อไป</button>
  </div>
</div>
</div>
</div>


<div class="modal fade" id="paymentModal" role="dialog">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">แจ้งการโอนเงิน</h4>
      </div>
      <form id="uploadSlip" name="uploadSlip" enctype="multipart/form-data" method="post">
        <div class="modal-body">
          <h4>จำนวนเงิน  100.00 ฿</h4>
          <div class="row">
            <CENTER>
              <div class="col-md-5 col-xs-12 center-block">
                <img src="../../images/kbank.jpg" alt="" style="margin-top:30px;margin-bottom:10px">
              </div>

              <div class="col-md-7">
                <h4>ธนาคาร: กสิกรไทย สาขา อ้อมใหญ่</h4>
                <h4>เลขที่: 111-115-02455</h4>
                <h4>ชื่อบัญชี: วอร์ริก สปอร์ท จำกัด</h4>
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
           <button type="button" class="btn btn-success" >ตกลง</button>
         </div>
       </div>
     </form>
   </div>
 </div>


 <div class="modal fade" id="transportPickerModal" role="dialog">
   <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
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
   <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addrPickerModal" data-dismiss="modal">ต่อไป</button>
 </div>
</div>
</div>
</div>


<div class="modal fade" id="addrPickerModal" role="dialog">
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เลือกที่อยู่</h4>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody>
          <tr>
            <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio" checked></td>
            <td>
              <h4>คุณ มานพ ดวงดี</h4>
              <p>เลขที่ 11/241 หมู่1 ตำบลห้วยหวาย อ.เมือง จังหวัด มหาสารคาม 42571</p>
            </td>
          </tr>
          <tr>
            <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio"></td>
            <td>
              <h4>คุณ มานพ ดวงดี</h4>
              <p>เลขที่ 88 หมู่2 ตำบลยางน้อย อ.เมือง จังหวัด มหาสารคาม 42571</p>
            </td>
          </td>
        </tr>
        <tr>
          <td style="padding-top:40px;padding-left:10px"><input type="radio" name="optradio" ></td>
          <td>
            <h4>คุณ มานพ ดวงดี</h4>
            <p>เลขที่ 57/12 ตำบลนิคมคำสร้อย อ.เมือง จังหวัดมุกดาหาร 40210</p>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
  <div class="modal-footer">

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addNewAddr" data-dismiss="modal">เพิ่มที่อยู่</button>

    <button type="button" class="btn btn-default" onclick="">ตกลง</button>

  </div>
</div>
</div>
</div>

<div class="modal fade" id="addNewAddr" role="dialog" >
 <div class="modal-dialog modal-xs">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">เพิ่มที่อยู่</h4>
    </div>
    <?php 
    $attributes = array('id' => 'form');
    echo form_open("shop/Register/register" , $attributes); 
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

      <div class="form-group">
        <div>
          <label >จังหวัด</label>
          <select name="Proviance" id="Proviance" style="width:150px">
            <option value=\"0\" selected=\"selected\">---เลือกจังหวัด---</option>
          </select>
          <input type="text" name="ProID" id="ProID" hidden="" />
        </div>
      </div>

      <div class="form-group">
        <div>
          <label >อำเภอ</label>
          <select name="District" id="District" style="width:150px">
            <option value=\"0\" selected=\"selected\">---เลือกจังอำเภอ---</option>
          </select>
          <input type="text" name="DisID" id="DisID" hidden="" />
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="Subdistrict">ตำบล</label>
          <select name="Subdistrict" id="Subdistrict" style="width:150px">
            <option value=\"0\" selected=\"selected\">---เลือกจังตำบล---</option>
          </select>

          <input type="text" name="SubID" id="SubID" hidden="" />
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="Postcode">รหัสไปรษณีย์</label>
          <select name="Postcode" id="Postcode" style="width:150px">
            <option value=\"0\" selected=\"selected\">---เลือกรหัสไปรษณีย์---</option>
          </select>
          <input type="text" name="PostID" id="PostID" hidden="" />
        </div>
      </div>
    </div>  <!-- modal body -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">บันทึก</button>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
</div>

<script>

  function cal_trans() {
    load_in();
    console.log("calulate");
    $.ajax({
      url:"<?php echo base_url(); ?>shop/cart/calculate",
      type:"POST",
      cache: "false", 
      data:{ "user_name" : "user"},
      success: function(rs){
        console.log(rs);
        load_out();
      }
    });
  }

  function deleteCartRow(id_pa)
  {
   console.log("del");
   var id_cart = $('#id_cart').val();
   $.ajax({
    url:"<?php echo base_url(); ?>shop/cart/deleteCartProduct"	,
    type:"POST", cache: "false", data:{ "id_cart" : id_cart, "id_pa" : id_pa },
    success: function(rs){
     console.log("res= "+rs);
     var rs = $.trim(rs);
     if( rs == 'success' )
     {
      console.log("del success");
      $("#row_"+id_pa).animate({opacity : 0}, 1000, function(){ $('#row_'+id_pa).remove(); recalCart(); });
      $("#m-row_"+id_pa).animate({opacity : 0}, 1000, function(){ $('#m-row_'+id_pa).remove(); recalCart(); });
    }
  }
});
 }

 function reloadCart()
 {
   load_in();
   $('#body').animate({opacity: 0.1}, 1000, function(){ window.location.reload(); });	
 }
 function decreaseQty(id_pa, min_qty)
 {
   var qty = parseInt(removeCommas($('#Qty_'+id_pa).text()));
   var min_qty = parseInt(min_qty);
   if( qty > min_qty)
   {
    qty -= 1;
    $('#Qty_'+id_pa).text(qty);
    $("#mQty_"+id_pa).text(qty);
    updateCart(id_pa, qty);
  }		
}

function increaseQty(id_pa, max_qty)
{
 var qty = parseInt(removeCommas($('#Qty_'+id_pa).text()));
 var max_qty = parseInt(max_qty);
 if( qty < max_qty )
 {
  qty += 1 ;
  $('#Qty_'+id_pa).text(qty);
  $("#mQty_"+id_pa).text(qty);
  updateCart(id_pa, qty);
}		
}

function updateCart(id_pa, qty)
{
	var id_cart = $('#id_cart').val();
	$.ajax({
		url:"<?php echo base_url(); ?>shop/cart/updateCart",
    type:'POST', cache:'false', data:{ "id_cart" : id_cart, "id_pa" : id_pa, "qty" : qty },
    success: function(rs){
     var rs = $.trim(rs);
     if( rs != '' && rs != 'fail' )
     {
      recalCart();
    }
  }
});
}

function recalCart()
{
 var total_price 		= 0;
 var total_discount 	= 0;
 var shipping			= 50;
 var cartLabel		= 0;
 $('.id_pa').each(function(index, element) {
  var id = $(this).val();
  var qty = parseInt(removeCommas($('#Qty_'+id).text()));
  var price = parseFloat(removeCommas($('#price_'+id).text()));
  var sell_price = parseFloat(removeCommas($('#sell-price_'+id).text()));
  var discount = price - sell_price;
  var total_dis = discount * qty;
  var total_amount = sell_price * qty;
  $('#discount_'+id).text(addCommas(total_dis.toFixed(2)));
  $('#total_sell_'+id).text(addCommas(total_amount.toFixed(2)));
  total_price += price * qty;
  total_discount += total_dis;
  shipping += qty * 10;
  cartLabel += qty;
  $('#total-price').text(addCommas(total_price.toFixed(2)));
  $('#shipping-fee').text(addCommas(shipping.toFixed(2)));
  $('#total-discount').text(addCommas(total_discount.toFixed(2)));
  var total_amount = total_price - total_discount + shipping;
  $('#total-amount').text(addCommas(total_amount.toFixed(2)));
  $("#cartLabel").text(addCommas(cartLabel));
  $("#cartMobileLabel").text(addCommas(cartLabel));
});
 if( total_price == 0 && cartLabel == 0 )
 {
  var html = '<tr><td colspan="6">' + 
  '<div class="col-lg-12" style="padding-top: 50px; padding-bottom: 50px;">'+
  '<h4 class="style2 text-center"><span style="font-size:22px;">ไม่มีสินค้าในตะกร้า</span></h4>'+
  '<center style="margin-top: 20px;">'+
  '<button class="btn btn-primary btn-lg" onClick="goToHome()" style="width: 200px;">เลือกซื้อสินค้าต่อ</button>'+
  '</center>'+
  '</div></td></tr>';
  $('#cart-table').append(html);
  $('#cartLabel').css('visibility', 'hidden');	
  $("#total-price").text(0.00);
  $("#total-discount").text(0.00);
  $("#shipping-fee").text(0.00);
  $("#total-amount").text(0.00);		
  $("#checkout-btn-top").css("display", "none");
  $("#checkout-btn-bottom").css("display", "none");			 
}
}

$(document).ready(function(){

//************ for address all input ****************

$("#Proviance").change(function(){
  console.log($(this).val());
  $.ajax({
    url:"<?= base_url();?>shop/register/getdata",
    type:"GET",
    cache:true, 
    data: {"ID" : $(this).val(),"TYPE" : "District"}, 
    dataType: "JSON", 
    success: function(jd) {

      $("#District").empty();
      $("#Subdistrict").empty();
      $("#Postcode").empty();
      $("#DisID").val("");
      $("#SubID").val("");
      $("#PostID").val(""); 

      var opt="<option value=\"0\" selected=\"selected\">---เลือกจังอำเภอ---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["AMPHUR_ID"] +"'>"+val["AMPHUR_NAME"]+"</option>"
      });
      $("#District").html( opt );
    },error: function(e) {
      console.log(" Proviance error");
    }
  }); 
  $("#ProID").val($(this).val()); 
});

$("#District").change(function(){
  $("#Subdistrict").empty();
  $("#Postcode").empty();
  $("#SubID").val("");
  $("#PostID").val("");
  $.ajax({
    url: "<?= base_url();?>shop/Register/getData",
    global: false,
    type: "GET",
    data: ({ID : $(this).val(),TYPE : "Subdistrict"}),
    dataType: "JSON",
    async:false,
    success: function(jd) {
      var opt="<option value=\"0\" selected=\"selected\">---เลือกตำบล---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["DISTRICT_ID"] +"'>"+val["DISTRICT_NAME"]+"</option>"
      });
      $("#Subdistrict").html( opt );
    },error: function(e) {
      console.log("District error");
    }
  });
  $("#DisID").val($(this).val());
});

$("#Subdistrict").change(function(){
  $("#PostID").val("");
  $.ajax({
    url: "<?= base_url();?>shop/Register/getData",
    type: "GET",
    data: ({ID : $("#District").val(),TYPE : "Postcode"}),
    dataType: "JSON",
    success: function(jd) {
      var opt="<option value=\"0\" selected=\"selected\">---เลือกรหัสไปรษณีย์---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["POST_CODE"] +"'>"+val["POST_CODE"]+"</option>"
      });
      $("#Postcode").html( opt );
    },error: function(e) {
      console.log(" Subdistrict error");
    }
  });
  $("#SubID").val($("#Subdistrict").val());
});

$("#Postcode").change(function(){
  $("#PostID").val($(this).val());
});
});


$("#addNewAddr" ).on('shown', function(){
  runAble();
}());


function runAble(){

  $.ajax({
    url:"<?= base_url();?>shop/register/getdata",
    type:"GET",
    cache:false, 
    data:{ "TYPE" : "Proviance"},
    success: function(jd){
      // console.log(jd);
      var opt="<option value=\"0\" selected=\"selected\">---เลือกจังหวัด---</option>";
      $.each(JSON.parse(jd), function(key, val){
        opt +="<option value='"+ val["PROVINCE_ID"] +"'>"+val["PROVINCE_NAME"]+"</option>"
      });

      $("#Proviance").html( opt );

    },error:function(e){
      console.log("error");
    }
  }); 
}; 

function removeFile()
{
  $("#previewImg").html('');
  $("#block-image").css("opacity","0");
  $("#btn-select-file").css('display', ''); 
  $("#image").val('');
}

function selectFile()
{
  $("#image").click();  
}

function readURL(input) 
{
 if (input.files && input.files[0]) {
  var reader = new FileReader();
  reader.onload = function (e) {
    $('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="200px" alt="รูปสลิปของคุณ" />');
  }
  reader.readAsDataURL(input.files[0]);
}
}
$("#image").change(function(){
  if($(this).val() != '')
  {
    var file    = this.files[0];
    var name    = file.name;
    var type    = file.type;
    var size    = file.size;
    if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
    {
      swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
      $(this).val('');
      return false;
    }
    if( size > 2000000 )
    { 
      swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error"); 
      $(this).val(''); 
      return false;
    }
    readURL(this);
    $("#btn-select-file").css("display", "none");
    $("#block-image").animate({opacity:1}, 1000);
  }
});

</script>

