<?php 
class Member extends CI_Controller
{	

	public $layout = "include/template";
	public $title = "Member Info";
	


	public function __construct()
	{
		parent::__construct();		
		$this->load->model("Member_model");
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
		$data['title']			= $this->title;
		$data['view'] 			= 'module/member_info';	
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['order_detail']	= $this->Member_model->getOrder_Detail();	
		$data['address']		= $this->Member_model->getAddress_Online($this->id_customer);

		$data['menus'] =  $this->Menu_model->menus();
		
		$this->load->view("include/template", $data);

	}
}

?>