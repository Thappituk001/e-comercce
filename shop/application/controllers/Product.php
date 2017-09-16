<?php 

class Product extends CI_Controller
{

	public $layout = "include/template";
	public $title = "สินค้า";

	
	public function __construct()
	{
		parent::__construct();		
		$this->load->model("main_model");
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->id_customer  = getIdCustomer();
		$this->id_cart 	    = getIdCart($this->id_customer['id']);
		$this->cart_value	= $this->cart_value	= 0;
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		$this->product_qty  = 0;
		
		
	}
	
	public function item($parent= 0,$child= 0,$sub_child= 0){
		$data['title']			= $this->title;		
		$data['view']			= 'module/product';
		
		$product		     = $this->product_model->getProduct($parent,$child,$sub_child);	
		$data['product']	    = $product;
		$this->product_qty      = count($product);
		$ColorGroup 			= array();
		$size 					= array();
		$id 					= array();
		$data['color']          = "";
		$data['size']			= array();
		
		//pass product color to array 
		if(!empty($product)){
			$id_s = '';
			foreach ($product as $pd) {
				if(!empty($pd)){
					
					if(!in_array($pd->id_color_group,$size, TRUE))
					{
						array_push($ColorGroup ,$pd->color_group_name);
					}//if color group
					
					if (!in_array($pd->id_size,$id, TRUE))
					{
						array_push($id,$pd->id_size);
						array_push($size ,array("id"=>$pd->id_size,"name"=>$pd->size_name));
					}//if size
				}//if !empty
			}//foreach
		}//!empty
		
		$data['color']  = array_unique($ColorGroup);
		$data['size']   = $size;
		
		//filter data
		if($this->input->get()){
			$present_qty = count($data['product']);
			$color = $this->input->get('color')==''?array():$this->input->get('color',true);
			$size  = $this->input->get('size')==''?array():$this->input->get('size',true);
			$minPrice = $this->input->get('minPrice')==''?0:$this->input->get('minPrice',true);
			$maxPrice = $this->input->get('maxPrice')==''?10000:$this->input->get('maxPrice',true);

			$productArray = array();
		
			
			if($present_qty = $this->product_qty){
				$n = 0;
				foreach ($data['product'] as $p) {
					
					if(
						(in_array($p->color_group_name,$color) && !empty($color)) || 
						(in_array($p->id_size,$size) && !empty($size)) &&
						($p->product_price >= $minPrice) &&
						($p->product_price <= $maxPrice) 
					 )
					{
						array_push($productArray, $p);
					}
				}
				
				$n++;
			}//if be present data

			$data['product'] = $productArray;
		}//if get filter

		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer['id'];
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			= $this->Menu_model->menus();

		$this->load->view("include/template",$data);
		
	}

	

	public function loadMoreItem()
	{
		
		$data = array();
		if( $this->input->post('offset') )
		{
			$result = $this->product_model->moreItem($this->input->post('offset'),$this->input->post('parent',true),$this->input->post('child',true),$this->input->post('sub_child',true));
			if( $result !== FALSE )
			{
				foreach( $result as $rs )
				{
					$promo = 0;
					if( $rs->discount_amount > 0 || $rs->discount_percent > 0 )
					{
						$promo = 1;
					}
					$arr = array(
						'link'				=>	'main/productDetail/'.$rs->product_id,
						'image_path'		=> get_image_path(get_id_cover_image($rs->product_id), 3),
						'style_id'			=> $rs->style_id,
						'promotion'			=> $promo,
						'new_product'		=> is_new_product($rs->product_id),
						'discount'			=> $rs->discount_amount+$rs->discount_percent,
						'discount_amount'	=> number_format($rs->discount_amount,2,'.',''),
						'discount_percent'	=> number_format($rs->discount_percent,2,'.',''),
						'discount_label'	=> discount_label($rs->discount_amount, $rs->discount_percent),
						 'available_qty'    => apply_stock_filter($this->product_model->getAvailableQty($rs->product_id)), 
						'product_code'		=> $rs->style_code,
						'product_name'		=> $rs->style_name,
						'sell_price'		=> sell_price($rs->product_price, $rs->discount_amount, $rs->discount_percent),
						'price'				=> number_format($rs->product_price,2,'.','')
						);	
					array_push($data, $arr);
				}//foreach 
				print_r(json_encode($data));
			}//$result !== FALSE 
			else{
				print_r(array());
			}
			
		}//$this->input->post('offset')

		
	}//function loadmore
	
	public function orderGrid(){
		

		$grid =$this->product_model->grid($this->input->post('id_style'));

		print_r(json_encode($grid));

	}//orderGrid

	public function fetchSize()
	{
		
		$select_color    = $this->input->post('color_select');
		$id_style		 = $this->input->post('id_style');
		$rs              = $this->product_model->getSizeByColor($select_color,$id_style);
		print_r(json_encode($rs));

	}


	public function addToCart()
	{	
		$data    = json_decode($this->input->post('dataChoosed'),true);

		foreach ($data as $key => $value) {
			echo $key." ".$value;
		}
		
	}

	public function getAvailable_qty(){
		$qty = $this->product_model->getAvailableQty_OnGrid($this->input->post('id_style',true),$this->input->post('id_color',true),$this->input->post('id_size',true));
		print_r($qty->qty);

	}

}

?>