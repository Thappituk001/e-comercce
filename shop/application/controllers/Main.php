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
		$this->id_cart 	    = getIdCart($this->id_customer);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['new_arrivals'] 	= $this->main_model->new_arrivals()!= false?$this->main_model->new_arrivals():array();
		$data['features']		= $this->main_model->features()!= false?$this->main_model->features():array();
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['view'] 			= 'main';	

		$data['menus'] =  $this->Menu_model->menus();


		$this->load->view("include/template", $data);
	}
	
	public function productDetail($id_pd)
	{
		$data['title']			= 'Product Details';
		$data['pd'] 			= $this->product_model->getProductDetail($id_pd);
		$data['images']			= $this->product_model->productImages($id_pd);
		$data['count_attrs']	= $this->product_model->getAttrs($id_pd);
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['product_info']	= $this->product_model->getProductInfo($id_pd);
		$data['view']			= 'product_detail';
		
		$data['id_customer']    = $this->id_customer;
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
				foreach( $result as $rs )
				{
					$promo = 0;
					if( $rs->discount != 0 OR is_new_product($rs->id_product) )
					{
						$promo = 1;
					}
					$arr = array(
						'link'				=>	'main/productDetail/'.$rs->id_product,
						'image_path'		=> get_image_path(get_id_cover_image($rs->id_product), 3),
						'promotion'		=> $promo,
						'new_product'	=> is_new_product($rs->id_product) === TRUE ? 1 : 0,
						'discount'			=> intval($rs->discount),
						'discount_label'	=> discount_label($rs->discount, $rs->discount_type),
						'product_code'	=> $rs->product_code,
						'product_name'	=> $rs->product_name,
						'sell_price'		=> sell_price($rs->product_price, $rs->discount, $rs->discount_type),
						'price'				=> $rs->product_price
						);	
					array_push($data, $arr);
				}
				echo json_encode($data);

			}
			else
			{
				echo 'none';	
			}
		}
		else
		{
			echo 'none';
		}

	}
	
	// public function cart($id=0)
	// {
	// 	$data['title']			= 'Cart detail';
	// 	$data['cart_items']		= $this->cart_items;
	// 	$data['view'] 			= 'cart';			

	// 	$this->load->view($this->layout, $data);

	// }
	
}/// end class
// Message: Missing argument 1 for Main::cart(), called in C:\xampp\htdocs\invent\shop\system\core\CodeIgniter.php on line 514 and defined

?>