<?php
class Cart_model extends CI_Model
{
	public function __construct()
	{
		parent:: __construct();	
	}
	
	public function getCartProduct($id_cart)
	{
		$rs = $this->db->where('id_cart_online',$id_cart)->get('cart_product_online');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	public function getAttr($id_pa){
		
		$rs = $this->db->where('id_product_attribute', $id_pa)->get('tbl_product_attribute');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	public function createCart($data)
	{
		$rs = $this->db->insert('tbl_cart', $data);
		if( $rs )
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function addToCart($id_customer,$id_product)
	{
		if($this->session->userdata('id_customer')){
			$role = 'member';

			$cart_id = $this->getCart_ID($id_customer);
		
			$data = array(
				'id_cart_product_online' => '',
				'id_cart_online' => $cart_id,
				'id_pa'=>$id_product,
				'qty'=>'1',
				'date_add'=> date("Y-m-d H:i:s"),
				);
			$this->db->insert('cart_product_online', $data); 
			return "success";


		}else{
			$role = 'great';
			return $role;
		}

	}
	
	public function cartValue($id_cart = 0)
	{
		$value = 0.00;
		if( $id_cart != 0 )
		{
			$rs = $this->db->join('tbl_cart', 'tbl_cart.id_cart = tbl_cart_product.id_cart')->where('tbl_cart_product.id_cart', $id_cart)->get('tbl_cart_product');
			if( $rs->num_rows() > 0 )
			{
				foreach($rs->result() as $rd)
				{
					$id_pd 		= getIdProduct($rd->id_product_attribute);
					$qty 			= $rd->qty;
					$price 		= itemPrice($rd->id_product_attribute);
					$value 		+= ($price * $qty);
				}
			}
		}
		return $value;
	}
	
	public function cartQty($id_cart = 0)
	{
		$qty = 0;
		if( $id_cart != 0 )
		{
			$rs = $this->db->select_sum('qty')->where('id_cart_online', $id_cart)->get('cart_product_online');
			if( $rs->row()->qty != 0 && $rs->row()->qty !== NULL )
			{
				$qty = $rs->row()->qty;
			}
		}
		return $qty;
	}
	
	public function updateCartProduct($id_cart, $id_pa, $qty)
	{
		$rs = $this->db->where('id_cart', $id_cart)->where('id_product_attribute', $id_pa)->update('tbl_cart_product', array('qty'=>$qty));
		return $rs;	
	}
	
	public function deleteCartProduct($id_cart, $id_pa)
	{
		return $this->db->where('id_cart', $id_cart)->where('id_product_attribute', $id_pa)->delete('tbl_cart_product');	
	}

	public function getBox(){
		$rs = $this->db->get('box_attribute');

		if( $rs )
		{
			return $rs->result();;
		}
		else
		{
			return FALSE;
		}
	}

	public function createGreatID(){

		//not member
		$rs = $this->db->select('id_great')->where('ip_address', $this->input->ip_address())->get('great');

		if( $rs->num_rows() == 1 )
		{
			// have id great 
			$id_great = $rs->row()->id_great;
			return $id_great;
		}else{

			$data = array(
				'id_great' => '',
				'ip_address' => $this->input->ip_address() ,
				);

			$this->db->insert('great', $data); 
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}
		
	}

	public function createCartID($great_id){

		if($this->session->userdata('id_customer')){

		}else{
			$rs = $this->db->select('id_cart')->where('id_customer',0)->where('id_great',$great_id)->where('cart_status',0)->get('cart_online');

			if( $rs->num_rows() == 1 )
			{
				// have id cart 
				$id_cart = $rs->row()->id_cart;
				return $id_cart;
			}else{
				$data = array(
					'id_cart' => '',
					'id_customer' => '0',
					'id_great'=>$great_id,
					'date_add'=> date("Y-m-d H:i:s"),
					'cart_status'=>'0'
					);

				$this->db->insert('cart_online', $data); 
				$insert_id = $this->db->insert_id();
				return $insert_id;	
			}//else
		}//else
		
		
	}//function

	public function insertItem($cart_id,$id_product){


		$rs = $this->db->select('id_cart_product_online')->where('id_cart_online',$cart_id)->where('id_pa',$id_product)->get('cart_product_online');

		if($rs->num_rows() == 1){
			
			$this->db->where('id_cart_online', $cart_id);
			$this->db->where('id_pa',$id_product);
			$this->db->set('qty', 'qty+1', FALSE);
			$this->db->update('cart_product_online'); 
			return "update qty";

		}else{

			$data = array(
				'id_cart_product_online' => '',
				'id_cart_online' => $cart_id,
				'id_pa'=>$id_product,
				'qty'=>'1',
				'date_add'=> date("Y-m-d H:i:s"),
				);

			$this->db->insert('cart_product_online', $data); 
			return "insert";
		}//else

		
	}


	public function getCart_ID($id_customer){

		
			$rs = $this->db->select('id_cart')->where('id_great','0')->where('id_customer',$id_customer)->get('cart_online');
			if( $rs->num_rows() == 1 )
			{
				return $rs->row()->id_cart;
			}else{
				return '';
			}
		
	}


}// end class;


?>