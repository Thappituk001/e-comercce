<?php 
class Member_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getOrder($id_customer){

		$qs	= $this->db->where('id_customer',$id_customer)->get('order_table');
		
		if( $qs->num_rows() > 0 )
		{
			return $qs->result();	
		}
		else
		{
			return false;
		}
	}

	public function getOrder_Detail(){

		$qs	= $this->db->where('id_order','917')->get('tbl_order_detail');
		if( $qs->num_rows() > 0 )
		{
			return $qs->result();	
		}
		else
		{
			return false;
		}
	}

	public function getAddress_Online($id_cus){

		$query = $this->db->select('customer_online_address.id_address , customer_online_address.address_no,customer_online_address.postcode,district.DISTRICT_NAME,amphur.AMPHUR_NAME,province.PROVINCE_NAME')->from('customer_online_address','customer_online_address.id_customer_online = customer_online.id_customer')->join('district','district.DISTRICT_ID = customer_online_address.subdistrict')->join('amphur','amphur.AMPHUR_ID = customer_online_address.district')->join('province','province.PROVINCE_ID = customer_online_address.proviance')->where('customer_online_address.id_customer_online',$id_cus)->get(); 

		if( $query->num_rows() > 0 )
		{
			return $query->result();	
		}
		else
		{
			return false;
		}
	}

}

?>