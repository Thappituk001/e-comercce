<?php

function get_customer_discount($id_cus, $id_pd)
{
	$disc = 0;  /// ส่งคืนส่วนลดที่ดีที่สุด เป็น เปอร์เซ็นต์ เสมอ
	$cates = get_product_category($id_pd);
	if( $cates !== FALSE )
	{
		$init = array();
		foreach( $cates as $cate )
		{
			array_push($init, $cate->id_category);
		}
		$c =& get_instance();
		$c->db->select('discount');
		$c->db->where('id_customer', $id_cus);
		$c->db->where_in('id_category', $init);
		$c->db->order_by('discount', 'desc');
		$rs = $c->db->get('tbl_customer_discount');
		if( $rs->num_rows() > 0 )
		{
			$disc = $rs->row()->discount;	
		}
	}
	return $disc;
}

function getIdCustomer()
{
	$ci =& get_instance();
	$id_customer = $ci->session->userdata('id_customer');
	// $ci->session->unset_userdata('id_customer');
	if(!$ci->session->userdata('id_customer')){// is not member ?
		//not member
		$rs = $ci->db->select('id_great')->where('ip_address', $ci->input->ip_address())->get('great');

		if( $rs->num_rows() == 1 )
		{
			// have id great 
			$id_great = $rs->row()->id_great;
			return array("id"=>$id_great,"role"=>"great");
		}else{
			$data = array(
			   'ip_address' => $ci->input->ip_address() 
			);
			$ci->db->insert('great', $data); 
			$insert_id = $ci->db->insert_id();
			return $insert_id;
		}
		
	}else{
		return array("id"=>$id_customer,"role"=>"member"); //member
	}
}

function getBank()
{
	$rs = get_instance()->db->select('tbl_bank_account.*')
	->where('tbl_bank_account.active',1)
	->get('tbl_bank_account');
	if( $rs->num_rows() > 0 )
	{
		return $rs->result();
	}
	
}

function customerName($id)
{
	$name = '';
	$c =& get_instance();
	$rs = $c->db->select('first_name, last_name')->where('id_customer', $id)->get('tbl_customer');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->first_name.' '.$rs->row()->last_name;
	}
	return $name;
}

function userNameByCustomer($id)
{
	$userName = '';
	$c =& get_instance();
	$rs = $c->db->select('user_name')->where('id_customer', $id)->get('tbl_user');
	if( $rs->num_rows() == 1 )
	{
		$userName = $rs->row()->user_name;
	}
	return $userName;
}

function getIdSaleByCustomer($id_cus)
{
	$id_sale = 0;
	$rs = get_instance()->db->select('id_sale')->where('id_customer', $id_cus)->get('tbl_customer');
	if( $rs->num_rows() == 1 )
	{
		$id_sale = $rs->row()->id_sale;
	}
	return $id_sale;
}
		

?>