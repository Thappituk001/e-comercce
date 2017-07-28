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
		$this->id_cart 	    = getIdCart($this->id_customer);
		$this->cart_value	= $this->cart_model->cartValue($this->id_cart);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		$this->product_qty      = 0;
		
	}
	
	public function index()
	{
		// $data['title']			= $this->title;		
		// $data['view']			= 'module/product';
		// $data['product']	= $this->product_model->getAllProduct();
		// $data['color']			= $this->product_model->getColor();
		// $data['size']			= $this->product_model->getSize();
		// $data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		// $data['id_customer']    = $this->id_customer;
		// $data['id_cart']		= $this->id_cart;
		// $data['cart_qty']		= $this->cart_qty;
		// $data['menus'] 			=  $this->Menu_model->menus();
		
		// $this->load->view("include/template", $data);
	}


	public function item($parent= 0,$child= 0,$sub_child= 0){
		$data['title']			= $this->title;		
		$data['view']			= 'module/product';
		
		$product		     = $this->product_model->getProduct($parent,$child,$sub_child);	
		$data['product']	    = $product;
		$this->product_qty      = count($product);

		$data['color']['color_group']			= '';
		$data['size']			= $this->product_model->getSize($parent,$child,$sub_child);

		// echo "<pre>";
		// print_r($product);
		// exit();

		// pass product color to array 
		if(!empty($product)){
			
			foreach ($product as $pd) {
				if(!empty($pd)){
					$s = array_search($pd->color_group,$data['color']);
					if(empty($s)){
						array_push($data['color'], $pd->color_group);
					}
				}
			}
		}

		if($this->input->get()){
			$present_qty = count($data['product']);
			$color = $this->input->get('color')==''?array():$this->input->get('color',true);
			$size  = $this->input->get('size')==''?array():$this->input->get('size',true);
			$minPrice = $this->input->get('minPrice')==''?0:$this->input->get('minPrice',true);
			$maxPrice = $this->input->get('maxPrice')==''?10000:$this->input->get('maxPrice',true);

			$productColor = array();

			if($present_qty = $this->product_qty){
			
				foreach ($data['product'] as $p) {
					if(!empty($color)){
						foreach ($color as $c) {
							if($p->color_group == $c){
								if(!empty($size)){
									if(!empty($minPrice) && !empty($maxPrice)){
										if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
											array_push($productColor,$p);
										}
									}else{
										array_push($productColor,$p);
									}// else !emply minPrice
								}else{
									
									if(!empty($minPrice) && !empty($maxPrice)){
										if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
											array_push($productColor,$p);
										}
									}else{
										array_push($productColor,$p);
									}// else !emply minPrice
								}//else !emply size
							}//if $c
						}//foreach color
					}else{
						if(!empty($size)){
							if(!empty($minPrice) && !empty($maxPrice)){
								if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
									array_push($productColor,$p);
								}
							}else{
								array_push($productColor,$p);
							}// else !emply minPrice
						}else{
							if(!empty($minPrice) && !empty($maxPrice)){
								if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
									array_push($productColor,$p);
								}
							}else{
								array_push($productColor,$p);
							}// else !emply minPrice
						}//else !emply size
					}//if !emply color

				}//foreach product
				$data['product'] = $productColor;	
			}//if
			else{
				$product	 = $this->product_model->getProduct($parent,$child,$sub_child);	
				foreach ($data['product'] as $p) {
					if(!empty($color)){
						foreach ($color as $c) {
							if($p->color_group == $c){
								if(!empty($size)){
									if(!empty($minPrice) && !empty($maxPrice)){
										if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
											array_push($productColor,$p);
										}
									}else{
										array_push($productColor,$p);
									}// else !emply minPrice
								}else{
									
									if(!empty($minPrice) && !empty($maxPrice)){
										if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
											array_push($productColor,$p);
										}
									}else{
										array_push($productColor,$p);
									}// else !emply minPrice
								}//else !emply size
							}//if $c
						}//foreach color
					}else{
						if(!empty($size)){
							if(!empty($minPrice) && !empty($maxPrice)){
								if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
									array_push($productColor,$p);
								}
							}else{
								array_push($productColor,$p);
							}// else !emply minPrice
						}else{
							if(!empty($minPrice) && !empty($maxPrice)){
								if($p->product_price>= $minPrice && $p->product_price <= $maxPrice ){
									array_push($productColor,$p);
								}
							}else{
								array_push($productColor,$p);
							}// else !emply minPrice
						}//else !emply size
					}//if !emply color
				}//foreach product
				$data['product'] = $productColor;
			}
		}//if
		

		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			= $this->Menu_model->menus();

		$this->load->view("include/template",$data);
		
	}

	public function orderGrid(){
		$id_product = $this->input->post('id_pd',true);
		echo "on orderGrid";
	}

	public function filter_product(){
		echo "filter";
	}
}

?>