<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/bill_helper.php";
require "../function/support_helper.php";
require "../function/sponsor_helper.php";
require "../function/lend_helper.php";



if( isset($_GET['check_order_state']) && isset($_GET['id_order']) )
{
	$state = 10;
	$qs = dbQuery("SELECT current_state FROM tbl_order WHERE id_order = ".$_GET['id_order']);
	if(dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		$state = $rs['current_state'];
	}
	echo $state;
}




if(isset($_GET['confirm_order'])&&isset($_GET['id_order']))
{ 
	//-----  ยืนยันว่าเปิดบิลแล้ว ตัดยอดออกจาก Buffer แล้วเพิ่มรายการลง stock movement
	$id_order 		= $_GET['id_order'];
	$id_employee 	= $_GET['id_employee'];
	$bill_discount 	= bill_discount($id_order);
	$order 			= new order($id_order);	
	$state 			= get_current_state($id_order);
	if( $state != 10)
	{
		setError('error', 'สถานะออเดอร์ถูกเปลี่ยนไปแล้ว');
		header("location: ../index.php?content=bill&id_order=$id_order&view_detail");
		exit;
	}
	else
	{
		$st = order_state_change($id_order, 9, $id_employee);
		startTransection();
		$success = TRUE;
		
		//----- ถ้าเป็นฝากขาย ลบยอดใน temp แล้วไปเพิ่มใน โซน ฝากขาย
		if( $order->role == 5 )
		{
			//----- หา id_zone ฝากขาย จาก tbl_order_consignment
			$id_zone		= getConsignmentIdZone( $id_order ); 
			if( $id_zone !== FALSE )
			{
				$qr = "SELECT tbl_qc.id_product_attribute, SUM(tbl_qc.qty) AS qty, id_zone, id_warehouse FROM tbl_qc JOIN tbl_temp ON tbl_qc.id_temp = tbl_temp.id_temp ";
				$qr .= "WHERE tbl_qc.id_order = ".$id_order." AND valid = 1 GROUP BY tbl_qc.id_product_attribute, tbl_temp.id_zone";
				$qs = dbQuery($qr);
				while( $row = dbFetchArray($qs) )
				{
					$id_pa					= $row['id_product_attribute']; 
					$qty 						= $row['qty'];
					$date_upd 				= date("Y-m-d H:i:s");
					$new_qty 				= $qty * (-1);
					$from_zone 			= $row['id_zone'];
					$id_warehouse 		= $row['id_warehouse'];
					$product 				= new product();
					$id_product 			= $product->getProductId($id_pa);
					$tmp_status				= 4;				 //----- สถานะของ temp = 4  คือ เปิดบิลแล้ว
					$rt 						= updateTemp(4, $id_order, $id_pa, $qty);
					$ra 						= update_buffer_zone($new_qty, $id_product, $id_pa, $id_order, $from_zone, $id_warehouse, $id_employee);
					$rb 						= stock_movement("out", 2, $id_pa, $id_warehouse, $qty, $order->reference, $date_upd, $from_zone);
					$rc 						= update_stock_zone($qty, $id_zone, $id_pa);
					$re 						= stock_movement("in", 1, $id_pa, 2, $qty, $order->reference, $date_upd, $id_zone);
					if( $ra !== TRUE || $rb !== TRUE || $rc !== TRUE || $re !== TRUE ){ $success = FALSE; }
				}
			
			}
			
		}
		else
		{		
				$qs = dbQuery("SELECT tbl_qc.id_product_attribute, SUM(tbl_qc.qty) AS qty, id_zone, id_warehouse FROM tbl_qc JOIN tbl_temp ON tbl_qc.id_temp = tbl_temp.id_temp WHERE tbl_qc.id_order = ".$id_order." AND valid = 1 GROUP BY tbl_qc.id_product_attribute, tbl_temp.id_zone");
		
			while( $row = dbFetchArray($qs) )
			{
				$id_product_attribute = $row['id_product_attribute']; 
				$qty = $row['qty'];
				$date_upd = date("Y-m-d H:i:s");
				$new_qty = $qty*(-1);
				$from_zone = $row['id_zone'];
				$id_warehouse = $row['id_warehouse'];
				$product = new product();
				$id_product = $product->getProductId($id_product_attribute);
				$ra = dbQuery("UPDATE tbl_temp SET status = 4  WHERE id_temp IN( SELECT id_temp FROM (SELECT id_temp FROM tbl_temp WHERE id_order = ".$id_order." AND id_product_attribute = ".$id_product_attribute." LIMIT ".$qty.") tmp)"); 
				$rb = update_buffer_zone($new_qty, $id_product, $id_product_attribute, $id_order, $from_zone, $id_warehouse, $id_employee);
				$rc = stock_movement("out", 3, $id_product_attribute, $id_warehouse, $qty, $order->reference, $date_upd, $from_zone);
				if( $ra !== TRUE || $rb !== TRUE || $rc !== TRUE ){ $success = FALSE; }
			}
			$rs = order_sold($id_order);
			if( $rs !== TRUE ){ $success = FALSE; }
		}
		
			if($order->role == 7)
			{
				$order_amount 		=  $order->getCurrentOrderAmount($id_order);		/// ตรวจสอบยอดเงินสั่งซื้อ
				$qc_amount 			= $order->qc_amount($id_order);							/// ตรวจสอบยอดเงินที่ qc ได้
				if($order_amount != $qc_amount)
				{											/// ถ้าไม่เท่ากันให้ทำการปรับปรุงยอดงบประมาณคงเหลือ
					$id_budget 			= get_id_support_budget_by_order($id_order);
					$amount 				= $order_amount - $qc_amount;							/// ยอดต่างระหว่างยอดเงินสั่งซื้อ กับ ยอดเงิน qc 
					$balance 			= get_support_balance($id_budget);						/// ดึงยอดงบประมาณคงเหลือขึ้นม
					$balance 			+= $amount;													/// บวกยอดต่างกลับเข้าไป กรณีที่ ยอดสั่งมากกว่ายอด qc ต้องคืนยอดต่างกลับเข้างบ
					update_support_balance($id_budget, $balance);					///  ปรับปรุงยอดงบประมาณคงเหลือ
				}		
				update_order_support_amount($id_order, $qc_amount);			/// ปรับปรุงยอดออเดอร์ใน order_support
				update_order_support_status($id_order, 1); 						/// อัพเดท สภานะของ order_support  0 = notvalid /  1 = valid / 2 = cancle
			}else if($order->role == 4){
				$order_amount 		=  $order->getCurrentOrderAmount($id_order); 	/// ตรวจสอบยอดเงินสั่งซื้อ
				$qc_amount = $order->qc_amount($id_order);							/// ตรวจสอบยอดเงินที่ qc ได้
				if($order_amount != $qc_amount){											/// ถ้าไม่เท่ากันให้ทำการปรับปรุงยอดงบประมาณคงเหลือ
					$id_budget 			= get_id_sponsor_budget_by_order($id_order);	
					$amount 				= $order_amount - $qc_amount;							/// ยอดต่างระหว่างยอดเงินสั่งซื้อ กับ ยอดเงิน qc 
					$balance 			= get_sponsor_balance($id_budget);						/// ดึงยอดงบประมาณคงเหลือขึ้นมา
					$balance 			+= $amount;													/// บวกยอดต่างกลับเข้าไป กรณีที่ ยอดสั่งมากกว่ายอด qc ต้องคืนยอดต่างกลับเข้างบ
					update_sponsor_balance($id_budget, $balance);					///  ปรับปรุงยอดงบประมาณคงเหลือ
				}
				update_order_sponsor_amount($id_order, $qc_amount);			/// ปรับปรุงยอดออเดอร์ใน order_sponsor
				update_order_sponsor_status($id_order, 1); 						///  อัพเดทสถานะของ order_sponsor  0 = notvalid /  1 = valid / 2 = cancle
			}else if( $order->role == 3){
				$rs = update_lend_qty($id_order);
				if( $rs !== TRUE ){ $success = FALSE; }					
			}
			
			clear_buffer($id_order); /// เคลียร์ buffer กรณีจัดขาดจัดเกินหรือแก้ไขออเดอร์ที่จัดแล้วเหลือสินค้าที่จัดแล้วค้างอยู่ที่ Buffer ให้ย้ายไปอยู่ใน cancle แทน
			
		if( $success === TRUE )
		{  
			commitTransection();		
			header("location: ../index.php?content=bill&id_order=$id_order&view_detail=y&message=success");
		}
		else
		{
			dbRollback();	
			dbQuery("UPDATE tbl_order SET current_state = 10 WHERE id_order = ".$id_order);
			dbQuery("DELETE FROM tbl_order_state_change WHERE id_order = ".$id_order." AND id_order_state = 9 AND id_employee = ".$id_employee);
			$err = "ทำรายการไม่สำเร็จ กรุณาลองใหม่อีกครั้ง";
			header("location: ../index.php?content=bill&id_order=$id_order&view_detail=y&error=$err");
		}
	}
}




if(isset($_GET['clear_buffer'] ) && isset($_GET['id_order']) ){ 
	clear_buffer($_GET['id_order']);
	echo "success";
 }




if(isset($_GET['delete_cancle_item']) && isset($_GET['id_cancle']))
{
	$qs = dbQuery("DELETE FROM tbl_cancle WHERE id_cancle = ".$_GET['id_cancle']);
	if($qs)
	{
		echo "success";
	}
	else
	{
		echo "fail";	
	}
}

if( isset($_GET['delete_checked_cancle_item']) && isset( $_POST['check'] ) )
{
	$check = $_POST['check'];
	$sc = true;
	foreach($check as $id => $val)
	{
		$qs = dbQuery("DELETE FROM tbl_cancle WHERE id_cancle = ".$id);
		if(!$qs){ $sc = false; }
	}
	if($sc)
	{
		echo "success";
	}
	else
	{
		echo "fail";
	}
}



if( isset( $_GET['print_order']) && isset( $_GET['id_order'] ) )
{
	$id_order	= $_GET['id_order'];
	$order 		= new order($id_order);
	$print 		= new printer();
	$doc			= doc_type($order->role);
	$invoice		= getInvoice($id_order);
	echo $print->doc_header();
	$print->add_title($doc['title']);
	$header		= get_header($order);
	$print->add_header($header);
	if( $order->role == 5 )
	{
		$detail 	= dbQuery("SELECT tbl_qc.id_product_attribute, barcode, product_reference, product_price, SUM(qty) AS sold_qty, reduction_percent, reduction_amount FROM tbl_order_detail JOIN tbl_qc ON tbl_order_detail.id_order = tbl_qc.id_order AND tbl_order_detail.id_product_attribute = tbl_qc.id_product_attribute WHERE tbl_order_detail.id_order = ".$id_order." AND tbl_qc.valid = 1 GROUP BY tbl_qc.id_product_attribute");
	}else{
		$detail 		= dbQuery("SELECT * FROM tbl_order_detail_sold WHERE id_order = ".$id_order);
	}
	$total_row 	= dbNumRows($detail);
	$config 		= array("total_row"=>$total_row, "font_size"=>10, "sub_total_row"=>4);
	$print->config($config);
	$row 			= $print->row;
	$total_page 	= $print->total_page;
	$total_qty 	= 0;
	$total_amount 		= 0;
	$total_discount 	= 0;
	$bill_discount		= bill_discount($id_order);
	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("บาร์โค้ด", "width:15%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:40%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("ราคา", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ส่วนลด", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("มูลค่า", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);
	$print->add_subheader($thead);
	
	//***************************** กำหนด css ของ td *****************************//
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:right; border-left: solid 1px #ccc; border-top:0px;"
							);					
	$print->set_pattern($pattern);	
	
	//*******************************  กำหนดช่องเซ็นของ footer *******************************//
	$footer	= array( 
						array("ผู้รับของ", "ได้รับสินค้าถูกต้องตามรายการแล้ว","วันที่............................."), 
						array("ผู้ส่งของ", "","วันที่............................."),
						array("ผู้ตรวจสอบ", "","วันที่............................."),
						array("ผู้อนุมัติ", "","วันที่.............................")
						);						
	$print->set_footer($footer);		
	
	$n = 1;
	while($total_page > 0 )
	{
		echo $print->page_start();
			echo $print->top_page();
			echo $print->content_start();
				echo $print->table_start();
				$i = 0;
				$product = new product();
				while($i<$row) : 
					$rs = dbFetchArray($detail);
					if(count($rs) != 0) :
						$id_product = $product->getProductId($rs['id_product_attribute']);
						$product_name = "<input type='text' style='border:0px; width:100%;' value='".$product->product_reference($rs['id_product_attribute'])." : ".$product->product_name($id_product)."' />";
						$total 	= $rs['sold_qty'] * $rs['product_price'];
						$final_total	= ($rs['sold_qty'] * $rs['product_price']) - ( ( ($rs['product_price'] * $rs['sold_qty']) * ($rs['reduction_percent'] * 0.01) ) + ($rs['sold_qty'] * $rs['reduction_amount']) );
						$discount_amount = $total - $final_total;
						if($rs['reduction_percent'] != 0 )
						{ 
							$discount = $rs['reduction_percent']." %"; 
						}else if($rs['reduction_amount'] != 0){ 
							$discount = number_format($rs['reduction_amount'],2)." ฿"; 
						}else{ 
							$discount = 0.00; 
						}
						$data = array($n, $rs['barcode'], $product_name, $rs['product_price'], number_format($rs['sold_qty']), $discount, number_format($final_total, 2));
						$total_qty += $rs['sold_qty'];
						$total_amount += ($rs['sold_qty']* $rs['product_price']);
						$total_discount += $discount_amount;
					else :
						$data = array("", "", "", "","", "","");
					endif;
					echo $print->print_row($data);
					$n++; $i++;  	
				endwhile;
				echo $print->table_end();
				if($print->current_page == $print->total_page)
				{ 
					$qty = number_format($total_qty); 
					$amount = number_format($total_amount,2); 
					$total_discount_amount = number_format($total_discount+$bill_discount,2);
					$net_amount = number_format($total_amount - ($total_discount + $bill_discount) ,2);
					$remark = $order->comment; 
				}else{ 
					$qty = ""; 
					$amount = ""; 
					$total_discount_amount = "";
					$net_amount = "";
					$remark = ""; 
				}
				$sub_total = array(
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px; border-left:0px; width:60%; text-align:center;'>**** ส่วนลดท้ายบิล : ".number_format($bill_discount,2)." ****</td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
						array("<td rowspan='3' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:55%; font-size:10px;'><strong>หมายเหตุ : </strong>".$remark."</td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>ราคารวม</strong></td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$amount."</td>"),
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ส่วนลดรวม</strong></td>
						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$total_discount_amount."</td>"),
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ยอดเงินสุทธิ</strong></td>
						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$net_amount."</td>")
						);
			echo $print->print_sub_total($sub_total);				
			echo $print->content_end();
			echo $print->footer;
		echo $print->page_end();
		$total_page --; $print->current_page++;
	}
	echo $print->doc_footer();
}

//// ปริ๊นใบกำกับมีบาร์โค้ด
if( isset( $_GET['print_order_barcode']) && isset( $_GET['id_order'] ) )
{
	$id_order	= $_GET['id_order'];
	$order 		= new order($id_order);
	$print 		= new printer();
	$doc			= doc_type($order->role);
	echo $print->doc_header();
	$print->add_title($doc['title']);
	$header		= get_header($order);
	$print->add_header($header);
	$detail 		= dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order);
	$total_row 	= dbNumRows($detail);
	$config 		= array("total_row"=>$total_row, "font_size"=>10, "sub_total_row"=>4);
	$print->config($config);
	$row 			= $print->row;
	$total_page 	= $print->total_page;
	$total_qty 	= 0;
	$total_amount 		= 0;
	$total_discount 	= 0;
	$bill_discount		= bill_discount($id_order);
	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("บาร์โค้ด", "width:15%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:25%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("ราคา", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ออเดอร์", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวนที่ได้", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ส่วนลด", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("มูลค่า", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);
	$print->add_subheader($thead);
	
	//***************************** กำหนด css ของ td *****************************//
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px; padding:0px; text-align:center; vertical-align:middle;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:right; border-left: solid 1px #ccc; border-top:0px;"
							);					
	$print->set_pattern($pattern);	
	
	//*******************************  กำหนดช่องเซ็นของ footer *******************************//
	$footer	= array( 
						array("ผู้รับของ", "ได้รับสินค้าถูกต้องตามรายการแล้ว","วันที่............................."), 
						array("ผู้ส่งของ", "","วันที่............................."),
						array("ผู้ตรวจสอบ", "","วันที่............................."),
						array("ผู้อนุมัติ", "","วันที่.............................")
						);						
	$print->set_footer($footer);		
	
	$n = 1;
	while($total_page > 0 )
	{
		echo $print->page_start();
			echo $print->top_page();
			echo $print->content_start();
				echo $print->table_start();
				$i = 0;
				$product = new product();
				while($i<$row) : 
					$rs = dbFetchArray($detail);
					if(count($rs) != 0) :
						$sold 				= get_sold_data($id_order, $rs['id_product_attribute']);
						$barcode		= $sold == false ? $rs['barcode'] : ($rs['barcode'] == '' ? '' : "<img src='".WEB_ROOT."library/class/barcode/barcode.php?text=".$rs['barcode']."' style='height:8mm;' />");
						$pre = ""; $post = "";
						if($sold != false && $sold['sold_qty'] != $rs['product_qty'])
						{
							$pre = "<span style='color: red; text-decoration: underline;'>";
							$post = "</span>";
						}
						$product_name = $product->product_reference($rs['id_product_attribute']);
						$total 				= $sold == false ? 0.00 : $sold['sold_qty'] * $rs['product_price'];
						$sold_qty		= $sold == false ? 0 : $sold['sold_qty'] ;
						$final_total		= $sold == false ? 0.00 : ($sold['sold_qty'] * $rs['product_price']) - ( ( ($rs['product_price'] * $sold['sold_qty']) * ($rs['reduction_percent'] * 0.01) ) + ($sold['sold_qty'] * $rs['reduction_amount']) );
						$discount_amount = $total - $final_total;
						$discount 		= $sold == false ? show_discount($rs['reduction_percent'], $rs['reduction_amount']) : show_discount($sold['reduction_percent'], $sold['reduction_amount']); 
						$data 			= array(
													$n, 
													$barcode, 
													$pre. $product_name .$post,
													$pre. number_format($rs['product_price'],2) .$post, 
													$pre. number_format($rs['product_qty']) .$post, 
													$pre. number_format($sold_qty) .$post, 
													$pre. $discount .$post, 
													$pre. number_format($final_total, 2) .$post
													);
						$total_qty 		+= $sold == false ? 0 : $sold['sold_qty'];
						$total_amount 	+= $sold == false ? 0 : $sold['sold_qty']* $rs['product_price'];
						$total_discount += $discount_amount;
					else :
						$data = array("", "", "", "","", "", "","");
					endif;
					echo $print->print_row($data);
					$n++; $i++;  	
				endwhile;
				echo $print->table_end();
				if($print->current_page == $print->total_page)
				{ 
					$qty = number_format($total_qty); 
					$amount = number_format($total_amount,2); 
					$total_discount_amount = number_format($total_discount+$bill_discount,2);
					$net_amount = number_format($total_amount - ($total_discount + $bill_discount) ,2);
					$remark = $order->comment; 
				}else{ 
					$qty = ""; 
					$amount = ""; 
					$total_discount_amount = "";
					$net_amount = "";
					$remark = ""; 
				}
				$sub_total = array(
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px; border-left:0px; width:60%; text-align:center;'>**** ส่วนลดท้ายบิล : ".number_format($bill_discount,2)." ****</td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
						array("<td rowspan='3' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:55%; font-size:10px;'><strong>หมายเหตุ : </strong>".$remark."</td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>ราคารวม</strong></td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$amount."</td>"),
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ส่วนลดรวม</strong></td>
						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$total_discount_amount."</td>"),
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ยอดเงินสุทธิ</strong></td>
						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$net_amount."</td>")
						);
			echo $print->print_sub_total($sub_total);				
			echo $print->content_end();
			echo $print->footer;
		echo $print->page_end();
		$total_page --; $print->current_page++;
	}
	echo $print->doc_footer();
}

if(isset($_GET['repay'])){
	$id_order = $_GET['id_order'];
	$order_valid = $_POST['order_valid'];
	if($order_valid == "1"){
		dbQuery("UPDATE tbl_order SET valid = 1 WHERE id_order = $id_order");
		$message = "บันทึกการชำระเงินเรียบร้อยแล้ว";
		header("location: ../index.php?content=repay&id_order=$id_order&view_detail=y&message=$message");
	}else{
		$message = "บันทึกการชำระเงินไม่สำเร็จ";
		header("location: ../index.php?content=repay&id_order=$id_order&view_detail=y&error=$message");
	}
}


if(isset($_GET['clear_filter']))
{
	$cookie = array("fromDate", "toDate", "reference", "invoice_no", "noInvoice", "cusName", "empName", "order", "consign", "sponsor", "support", "reform", "sortDate");
	foreach($cookie as $name)
	{
		deleteCookie($name);
	}
	echo 'success';
}

?>
