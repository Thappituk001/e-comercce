<?php 
class Main extends CI_Controller
{

	public $layout = "include/template";
	public $title = "ยินดีต้อนรับ";
	
	public function __construct()
	{
		parent::__construct();		
		
		$this->load->model("main_model");
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');

		$this->home = base_url()."shop/main";

		$this->id_customer  = getIdCustomer();//great or member
		$this->id_cart 	    = getIdCart($this->id_customer['id']);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['new_arrivals'] 	= $this->main_model->new_arrivals()!= false?$this->main_model->new_arrivals():array();
		$data['features']		= $this->main_model->features()!= false?$this->main_model->features():array();
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer['id'];
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['view'] 			= 'main';	

		$data['menus'] =  $this->Menu_model->menus();

		// echo "<pre>";
		// print_r($data['new_arrivals']);
		// exit();


		$this->load->view("include/template", $data);
	}
	
	public function productDetail($id_pd)
	{
		$data['title']			= 'Product Details';
		$data['product'] 		= $this->product_model->getProductDetail($id_pd);
		$data['images']			= $this->product_model->productImages($id_pd);
		
		$data['grid']			= $this->product_model->grid($data['product'][0]->style_id);
		
		
		
		$data['view']			= 'product_detail';
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer['id'];
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] =  $this->Menu_model->menus();


		$this->load->view($this->layout, $data);	
	}


	public function loadMoreFeatures()
	{
		// $this->load->model('main_model');
		$data = array();
		if( $this->input->post('offset') )
		{
			$result = $this->main_model->moreFeatures($this->input->post('offset'));
			if( $result !== FALSE )
			{
				// print_r($result);
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
						'promotion'			=> $promo,
						'new_product'		=> is_new_product($rs->product_id),
						'discount'			=> $rs->discount_amount+$rs->discount_percent,
						'discount_amount'	=> number_format($rs->discount_amount,2,'.',''),
						'discount_percent'	=> number_format($rs->discount_percent,2,'.',''),
						'discount_label'	=> discount_label($rs->discount_amount, $rs->discount_percent),
						'product_code'	=> $rs->style_code,
						'product_name'	=> $rs->style_name,
						'sell_price'		=> sell_price($rs->product_price, $rs->discount_amount, $rs->discount_percent),
						'price'				=> number_format($rs->product_price,2,'.','')
						);	
					array_push($data, $arr);
				}//foreach 

				print_r(json_encode($data));
			}//$result !== FALSE 
			else{
				echo "none";
			}
			
		}//$this->input->post('offset')
		
	}//function loadmore
	
	
	
	
}/// end class


?>