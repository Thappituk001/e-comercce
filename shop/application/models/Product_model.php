<?php 
class Product_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function getProduct($parent=0,$child=0,$sub_child=0){

		if($parent != 0 && $child == 0 && $sub_child == 0){
			
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				tbl_color.id_color,
				tbl_color.color_code,
				tbl_color.color_name,
				tbl_color.id_color_group,
				color_group.color_group_name,
				tbl_size.id_size,
				tbl_size.size_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('color_group','color_group.id_color_group = tbl_color.id_color_group')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->limit(16,0)
			->group_by('tbl_product.id')
			->order_by('tbl_product.id_category','desc')
			->get('tbl_product');		
			

		}else if($parent != 0 && $child != 0 && $sub_child == 0){
			
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				tbl_color.id_color,
				tbl_color.color_code,
				tbl_color.color_name,
				tbl_color.id_color_group,
				color_group.color_group_name,
				tbl_size.id_size,
				tbl_size.size_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('color_group','color_group.id_color_group = tbl_color.id_color_group')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->limit(16,0)
			->group_by('tbl_product.id')
			->order_by('tbl_product.id_category', 'desc')
			->get('tbl_product');

		}else if($parent != 0 && $child != 0 && $sub_child != 0){
			
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				tbl_color.id_color,
				tbl_color.color_code,
				tbl_color.color_name,
				tbl_color.id_color_group,
				color_group.color_group_name,
				tbl_size.id_size,
				tbl_size.size_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('color_group','color_group.id_color_group = tbl_color.id_color_group')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('product_online.id_subchild_menu',$sub_child)
			->limit(16,0)
			->group_by('tbl_product.id')
			->order_by('tbl_product.id_category', 'desc')
			->get('tbl_product');		
			

		}else{
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				tbl_color.id_color,
				tbl_color.color_code,
				tbl_color.color_name,
				tbl_color.id_color_group,
				color_group.color_group_name,
				tbl_size.id_size,
				tbl_size.size_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('color_group','color_group.id_color_group = tbl_color.id_color_group')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->limit(16,0)
			->group_by('tbl_product.id')
			->order_by('tbl_product.id_category', 'desc')
			->get('tbl_product');		
			
		}

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return array();
		}

	}

	public function getAvailableQty($id_pd)
	{
		$qty 		= 0; 
		$move 	    = $this->moveQty($id_pd);
		$cancle 	= $this->cancleQty($id_pd);
		$order	    = $this->orderQty($id_pd);
		$rs 		= $this->db->select_sum('qty')->join('tbl_zone', 'tbl_zone.id_zone = tbl_stock.id_zone')->where('id_product', $id_pd)->where('id_warehouse !=', 2)->get('tbl_stock');
		
		if( $rs->num_rows() == 1 && !is_null($rs->row()->qty))
		{
			$qty = $rs->row()->qty;
		}
		$qty 	= ($qty + $move + $cancle) - $order;
		
		return $qty;	
	}
	
	public function cancleQty($id_pa)
	{
		$qty 	= 0;
		$rs 	= $this->db->select_sum('qty')->where('id_product_attribute', $id_pa)->get('tbl_cancle');
		if( $rs->num_rows() == 1 && !is_null($rs->row()->qty) )
		{
			$qty = $rs->row()->qty;
		}
		return $qty;
	}
	
	public function moveQty($id_pa)
	{
		$qty = 0;
		$rs 	= $this->db->select_sum('qty_move')->where('id_product_attribute', $id_pa)->get('tbl_move');
		if( $rs->num_rows() == 1 && !is_null($rs->row()->qty_move))
		{
			$qty = $rs->row()->qty_move;
		}
		return $qty;
	}
	
	public function orderQty($id_pa)
	{
		$qty = 0;
		$this->db->select_sum('product_qty')->from('tbl_order_detail');
		$this->db->join('tbl_order', 'tbl_order.id_order = tbl_order_detail.id_order');
		$this->db->where('id_product_attribute', $id_pa)->where('valid_detail', 0);
		$this->db->where_not_in('current_state', array('6', '7', '8', '9'));
		$rs	= $this->db->get();
		if( $rs->num_rows() == 1 && !is_null($rs->row()->product_qty) )
		{
			$qty = $rs->row()->product_qty;
		}
		return $qty;
	}
	
	public function getProductDetail($id)
	{
		$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				tbl_color.id_color,
				tbl_color.color_code,
				tbl_color.color_name,
				tbl_color.id_color_group,
				color_group.color_group_name,
				tbl_size.id_size,
				tbl_size.size_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('color_group','color_group.id_color_group = tbl_color.id_color_group')
			->where('tbl_product.id',$id)
			->get('tbl_product');		
			

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}
	}
	
	public function productImages($id_pd)
	{
		$rs = $this->db->where('id_style', $id_pd)->get('tbl_image');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getProductInfo($id_pd)
	{
		$info = '';
		$rs = $this->db->select('product_detail')->where('id_product', $id_pd)->get('tbl_product_detail');
		if( $rs->num_rows() == 1)
		{
			$info = $rs->row()->product_detail;
		}
		return $info;
	}


	public function moreItem($offset,$parent=0,$child=0,$sub_child=0)
	{	
		
		if($parent != 0 && $child == 0 && $sub_child == 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}else if($parent != 0 && $child != 0 && $sub_child == 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}else if($parent != 0 && $child != 0 && $sub_child != 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				tbl_product.discount_percent,
				tbl_product.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('product_online.id_subchild_menu',$sub_child)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	
	}
	
	public function grid($id_style)
	{
		$rs = $this->db->select('tbl_color.id_color,tbl_color.color_name,tbl_size.id_size,tbl_size.size_name')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
		->where('tbl_product.id_style',$id_style)
		->get('tbl_product');

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	
	}


	public function getSizeByColor($select_color,$id_style)
	{
		

		$rs = $this->db->select('tbl_size.id_size,tbl_size.size_name')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->where('tbl_product.id_style',$id_style)
		->where('tbl_product.id_color',$select_color)
		->get('tbl_product');

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	


	}




}/// End class

?>