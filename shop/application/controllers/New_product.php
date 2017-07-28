<?php 

class New_product extends CI_Controller
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
		
	}
	
	public function index()
	{
		
	}

	public function product(){
		$data['title']			= $this->title;		
		$data['view']			= 'module/new_product';
		$data['product_query']	= $this->product_model->getAllProduct();

		$data['color']			= $this->product_model->getColor();
		$data['size']			= $this->product_model->getSize();
		// print_r($this->product_model->getColor());
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['id_customer']    = $this->id_customer;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			=  $this->Menu_model->menus();

		$this->load->view("include/template", $data);
	}


	

   
}

 ?>