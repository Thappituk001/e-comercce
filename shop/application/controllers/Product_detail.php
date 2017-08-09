<?php
class Product_detail extends CI_Controller
{
	public $layout = "include/template";
	public $title = "รายละเอียดสินค้า";
	
	public function __construct()
	{
		parent::__construct();		
		$this->home = base_url()."product_detail";
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->id_customer  = getIdCustomer();
		$this->id_cart 	    = getIdCart($this->id_customer);
		$this->cart_value	= $this->cart_model->cartValue($this->id_cart);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}
	
	public function index($id_pd)
	{
		$this->load->helper('value');
		$this->load->model('product_model');
		$data['pd'] 	= $this->product_model->getProductDetail($id_pd);
		$data['images']	= $this->product_model->productImages($id_pd);
		$data['pinfo']	= $this->product->model->getProductInfo($id_pd);
		$data['view']	= 'product_detail';
		$data['menus'] =  $this->Menu_model->menus();
		
		
		$this->load->view($this->layout, $data);
	}
	
}
/// end class

?>