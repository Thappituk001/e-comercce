<?php
class Main_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function new_arrivals()
	{
		$items	= getConfig('NEW_PRODUCT_QTY');
		$days	= getConfig('NEW_ARRIVAL_DAYS');
		$from	= fromDate(beforeDate($days, date('Y-m-d')));
		$to		= toDate(NOW());
	
		$qs  = $this->db->select('tbl_product.id as product_id,
			tbl_product.code as product_code,
			tbl_product.name as product_name,
			tbl_product.price as product_price,
			promotion.discount_percent,
			promotion.discount_amount,
			tbl_style.id as style_id,
			tbl_style.code as style_code,
			tbl_style.name as style_name,
			tbl_color.id_color,
			tbl_color.color_code,
			tbl_color.color_name,
			tbl_size.id_size,
			tbl_size.size_name,
			')
		->join('product_online','product_online.id_product_online = tbl_product.id')
		->join('promotion','promotion.id_product = product_online.id_product','left')
		->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		
		->where('tbl_product.show_in_online',1)
		->where('tbl_product.is_deleted',0)
		->where('tbl_product.active',1)
		->order_by('tbl_product.id_category', 'desc')
		->get('tbl_product');		
		
		if( $qs->num_rows() > 0 )
		{
			return $qs->result();	
		}
		else
		{
			return false;
		}
	}

	
	public function features()
	{
		
		$rs  = $this->db->select('tbl_product.id as product_id,
			tbl_product.code as product_code,
			tbl_product.name as product_name,
			tbl_product.price as product_price,
			promotion.discount_percent,
			promotion.discount_amount,
			tbl_style.id as style_id,
			tbl_style.code as style_code,
			tbl_style.name as style_name,
			tbl_color.id_color,
			tbl_color.color_code,
			tbl_color.color_name,
			tbl_size.id_size,
			tbl_size.size_name,
			tbl_product_kind.id as kind_id,
			tbl_product_kind.code as kind_code,
			tbl_product_kind.name as kine_name,
			tbl_product_type.id as type_name,
			tbl_product_type.code as type_code,
			tbl_product_type.name as type_name,
			tbl_product_group.id as group_id,
			tbl_product_group.code as group_code,
			tbl_product_group.name as group_name,
			tbl_product_category.id as category_id,
			tbl_product_category.code as category_code,
			tbl_product_category.name as category_name,
			')
		->join('product_online','product_online.id_product_online = tbl_product.id')
		->join('promotion','promotion.id_product = product_online.id_product','left')
		->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->join('tbl_product_kind','tbl_product_kind.id = tbl_product.id_kind')
		->join('tbl_product_type','tbl_product_type.id = tbl_product.id_type')
		->join('tbl_product_group','tbl_product_group.id = tbl_product.id_group')
		->join('tbl_product_category','tbl_product_category.id = tbl_product.id_category')

		->where('tbl_product.show_in_online',1)
		->where('tbl_product.is_deleted',0)
		->where('tbl_product.active',1)
		->limit(8,0)
		->order_by('tbl_product.price', 'desc')
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
	
	public function moreFeatures($offset)
	{
		
		$rs  = $this->db->select('tbl_product.id as product_id,
			tbl_product.code as product_code,
			tbl_product.name as product_name,
			tbl_product.price as product_price,
			promotion.discount_percent,
			promotion.discount_amount,
			tbl_style.id as style_id,
			tbl_style.code as style_code,
			tbl_style.name as style_name,
			tbl_color.id_color,
			tbl_color.color_code,
			tbl_color.color_name,
			tbl_size.id_size,
			tbl_size.size_name
			')
		->join('product_online','product_online.id_product_online = tbl_product.id')
		->join('promotion','promotion.id_product = product_online.id_product','left')
		->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')

		->where('tbl_product.show_in_online',1)
		->where('tbl_product.is_deleted',0)
		->where('tbl_product.active',1)
		->limit(8,0)
		->order_by('tbl_product.price', 'desc')
		->limit(10,$offset)
		->get('tbl_product');		
		
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	
	}//moreFeatures

	public function moreItemByMenu($offset,$parent,$child,$sub_child)
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
				tbl_color.color_group,
				tbl_size.id_size,
				tbl_size.size_name,
				product_online.*
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
			->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
			->join('product_online','product_online.id_product = tbl_product.id')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('product_online.id_subchild_menu',$sub_child)
			->limit(2,$offset)
			->group_by('tbl_product.id')
			->order_by('tbl_product.id_category', 'desc')
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

}
/// end class


?>