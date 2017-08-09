<?php 
class Product_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	public function getAllProduct(){
		$rs = $this->db->where('active', '1')->where('show_in_shop', '1')->order_by('date_upd', 'desc')->get('tbl_product');
		
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}

	}
	public function getProduct($parent=0,$child=0,$sub_child=0,$color=''){

		if($parent != 0 && $child == 0 && $sub_child == 0){
			$rs = $this->db->select('tbl_product.*,tbl_product_attribute.id_product_attribute,tbl_product_attribute.id_color,tbl_product_attribute.id_size,tbl_color.*,tbl_size.*')->join('tbl_product_attribute','tbl_product_attribute.id_product = product_online.id_product')->join('tbl_color','tbl_product_attribute.id_color = tbl_color.id_color')->join('tbl_product','tbl_product.id=product_online.id_product')->join('tbl_size','tbl_product_attribute.id_size = tbl_size.id_size')->where('product_online.id_parent_menu',$parent)->group_by('tbl_product.id')->get('product_online');

		}else if($parent != 0 && $child != 0 && $sub_child == 0){
			$rs = $this->db->select('tbl_product.*,tbl_product_attribute.id_product_attribute,tbl_product_attribute.id_color,tbl_product_attribute.id_size,tbl_color.*,tbl_size.*')->join('tbl_product_attribute','tbl_product_attribute.id_product = product_online.id_product')->join('tbl_color','tbl_product_attribute.id_color = tbl_color.id_color')->join('tbl_product','tbl_product.id=product_online.id_product')->join('tbl_size','tbl_product_attribute.id_size = tbl_size.id_size')->where('product_online.id_parent_menu',$parent)->where('product_online.id_child_menu',$child)->group_by('tbl_product.id')->get('product_online');

		}else if($parent != 0 && $child != 0 && $sub_child != 0){
			$rs = $this->db->select('tbl_product.*,tbl_product_attribute.id_product_attribute,tbl_product_attribute.id_color,tbl_product_attribute.id_size,tbl_color.*,tbl_size.*')->join('tbl_product_attribute','tbl_product_attribute.id_product = product_online.id_product')->join('tbl_color','tbl_product_attribute.id_color = tbl_color.id_color')->join('tbl_product','tbl_product.id=product_online.id_product')->join('tbl_size','tbl_product_attribute.id_size = tbl_size.id_size')->where('product_online.id_parent_menu',$parent)->where('product_online.id_child_menu',$child)->where('product_online.id_subchild_menu',$sub_child)->group_by('tbl_product.id')->get('product_online');

		}else{
			$rs = $this->db->select('tbl_product.*,tbl_product_attribute.id_product_attribute,tbl_product_attribute.id_color,tbl_product_attribute.id_size,tbl_color.*,tbl_size.*')->join('tbl_product_attribute','tbl_product_attribute.id_product = product_online.id_product')->join('tbl_color','tbl_product_attribute.id_color = tbl_color.id_color')->join('tbl_product','tbl_product.id=product_online.id_product')->join('tbl_size','tbl_product_attribute.id_size = tbl_size.id_size')->where('product_online.id_parent_menu',$parent)->where('tbl_product.show_in_shop',1)->group_by('tbl_product.id')->get('product_online');

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

	public function getAvailableQty($id_pa)
	{
		$qty 		= 0; 
		$move 	    = $this->moveQty($id_pa);
		$cancle 	= $this->cancleQty($id_pa);
		$order	    = $this->orderQty($id_pa);
		$rs 		= $this->db->select_sum('qty')->join('tbl_zone', 'tbl_zone.id_zone = tbl_stock.id_zone')->where('id_product_attribute', $id_pa)->where('id_warehouse !=', 2)->get('tbl_stock');
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
		$rs = $this->db->select('tbl_product.id as product_id,
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
			tbl_size.id_size,
			tbl_size.size_name,
			')
		->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->where('tbl_product.id' ,$id)
		->order_by('tbl_product.id_category', 'desc')
		->get('tbl_product');		
		if( $rs->num_rows() == 1 )
		{
			return $rs->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function productImages($id_pd)
	{
		$rs = $this->db->where('id_product', $id_pd)->get('tbl_image');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getAttrs($id_pd)
	{
		$color 	= $this->hasAttr($id_pd, 'color') === TRUE ? 1 : 0;
		$size		= $this->hasAttr($id_pd, 'size') === TRUE ? 1 : 0;
		$attr		= $this->hasAttr($id_pd, 'attribute') === TRUE ? 1 : 0;
		$attrs 	= $color + $size + $attr;
		$rs		= FALSE;
		if( $color == 1 && $size == 0 && $attr == 0){ $rs = array('length' => 1, 'horizontal' => 'color', 'vertical' => '', 'tab' => ''); }
		if( $color == 0 && $size == 1 && $attr == 0){ $rs = array('length' => 1, 'horizontal' => 'size', 'vertical' => '', 'tab' => ''); }
		if( $color == 0 && $size == 0 && $attr == 1){ $rs = array('length' => 1, 'horizontal' => 'attribute', 'vertical' => '', 'tab' => ''); }		
		if( $color == 1 && $size == 1 && $attr == 0){ $rs = array('length' => 2, 'horizontal' => 'color', 'vertical' => 'size', 'tab' => ''); }
		if( $color == 1 && $size == 0 && $attr == 1){ $rs = array('length' => 2, 'horizontal' => 'color', 'vertical' => 'attribute', 'tab' => ''); }
		if( $color == 0 && $size == 1 && $attr == 1){ $rs = array('length' => 2, 'horizontal' => 'attribute', 'vertical' => 'size', 'tab' => ''); }		
		if( $color == 1 && $size == 1 && $attr == 1){ $rs = array('length' => 3, 'horizontal' => 'color', 'vertical' => 'size', 'tab' => 'attribute'); }
		
		return $rs;		
	}
	
	public function hasAttr($id_pd, $attribute = 'color')
	{
		$rs = FALSE;	
		switch( $attribute )
		{
			case 'color' :
			$attr = 'id_color';
			break;
			case 'size' :
			$attr = 'id_size';
			break;
			case 'attribute' :
			$attr = 'id_attribute';
			break;
			default :
			$attr = 'id_color';
			break;
		}
		$qs = $this->db->where('id_product', $id_pd)->where($attr.' !=', 0)->limit(1)->get('tbl_product_attribute');
		if( $qs->num_rows() == 1 )
		{
			$rs = TRUE; 
		}
		return $rs;	
	}
	
	public function getHeaderRow($id_pd, $horizontal = 'color', $vertical = 'size')
	{
		$qs 	= 'SELECT id_'.$horizontal.' AS id FROM tbl_product_attribute WHERE id_product = '.$id_pd.' GROUP BY id_'.$horizontal.' ORDER BY id_'.$horizontal.' ASC';
		$rs	= $this->db->query($qs);
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;	
		}
	}
	
	public function getVertical($id_pd, $vertical)
	{
		$qv = 'SELECT tbl_'.$vertical.'.id_'.$vertical.' AS id ';
		$qv .= 'FROM tbl_product_attribute JOIN tbl_'.$vertical.' ON tbl_product_attribute.id_'.$vertical.' = tbl_'.$vertical.'.id_'.$vertical;
		$qv .= ' WHERE id_product = '.$id_pd.' GROUP BY tbl_'.$vertical.'.id_'.$vertical.' ORDER BY position ASC';	
		$rs = $this->db->query($qv);
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getHorizontal($id_pd, $horizontal)
	{
		$qh = 'SELECT tbl_'.$horizontal.'.id_'.$horizontal.' AS id ';
		$qh .= 'FROM tbl_product_attribute JOIN tbl_'.$horizontal.' ON tbl_product_attribute.id_'.$horizontal.' = tbl_'.$horizontal.'.id_'.$horizontal;
		$qh .= ' WHERE id_product = '.$id_pd.' GROUP BY tbl_'.$horizontal.'.id_'.$horizontal.' ORDER BY position ASC';
		$rs = $this->db->query($qh);
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getTabs($id_pd, $tab = 'attribute')
	{
		$qs = 'SELECT tbl_'.$tab.'.id_'.$tab.' AS id ';
		$qs .= 'FROM tbl_product_attribute JOIN tbl_'.$tab.' ON tbl_product_attribute.id_'.$tab.' = tbl_'.$tab.'.id_'.$tab;
		$qs .= ' WHERE id_product = '.$id_pd.' GROUP BY tbl_'.$tab.'.id_'.$tab.' ORDER BY position ASC';
		$rs = $this->db->query($qs);
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_id_product_attribute_by_attrs($id_pd, $horizontal = 'color', $id_horizontal = 0, $vertical = '', $id_vertical = 0, $tab = '', $id_tab = 0)
	{
		$qs = 'SELECT id_product_attribute AS id_pa FROM tbl_product_attribute WHERE id_product = '.$id_pd.' AND id_'.$vertical.' = '.$id_vertical.' AND id_'.$horizontal.' = '.$id_horizontal.' AND id_'.$tab.' = '.$id_tab;	
		$rs	= $this->db->query($qs);
		if( $rs->num_rows() > 0 )
		{
			return $rs->row();
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


	public function getSize($parent=0,$child=0,$sub_child=0,$color=''){
		if($parent != 0 && $child == 0 && $sub_child == 0){
			$rs = $this->db->select('*')->join('tbl_product_attribute','tbl_product_attribute.id_size= tbl_size.id_size')->join('product_online','product_online.id_product = tbl_product_attribute.id_product')->where('product_online.id_parent_menu',$parent)->group_by('tbl_size.size_name')->get('tbl_size');

		}else if($parent != 0 && $child != 0 && $sub_child == 0){
			$rs = $this->db->select('*')->join('tbl_product_attribute','tbl_product_attribute.id_size= tbl_size.id_size')->join('product_online','product_online.id_product = tbl_product_attribute.id_product')->where('product_online.id_parent_menu',$parent)->where('product_online.id_child_menu',$child)->group_by('tbl_size.size_name')->get('tbl_size');

		}else if($parent != 0 && $child != 0 && $sub_child != 0){
			$rs = $this->db->select('*')->join('tbl_product_attribute','tbl_product_attribute.id_size= tbl_size.id_size')->join('product_online','product_online.id_product = tbl_product_attribute.id_product')->where('product_online.id_parent_menu',$parent)->where('product_online.id_child_menu',$child)->where('product_online.id_subchild_menu',$sub_child)->group_by('tbl_size.size_name')->get('tbl_size');

		}else{
			$rs = $this->db->select('*')->join('tbl_product_attribute','tbl_product_attribute.id_size= tbl_size.id_size')->join('product_online','product_online.id_product = tbl_product_attribute.id_product')->where('product_online.id_parent_menu',$parent)->group_by('tbl_size.size_name')->get('tbl_size');
		}

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}else{
			return array();
		}
	}
	
}/// End class

?>