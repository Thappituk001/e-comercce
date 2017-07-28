<?php
class Cart extends CI_Controller
{
	public $home;
	public $layout = "include/template";
	public $title = "ตะกร้าสินค้า";
	public $cart_items;
	public $width,$height,$long;

	public function __construct()
	{
		parent:: __construct();
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
	
	
	public function cart($id=0)
	{
		$data['title']			= 'Cart detail';
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			=  $this->Menu_model->menus();
		$data['view'] 			= 'cart';
		$data['attr']['attr_weight']	= array();
		$data['attr']['attr_width'] 	= array();	 
		$data['attr']['attr_height'] 	= array();	 
		$data['attr']['attr_long']  	= array();	 
	

		foreach ($this->cart_items as $item) {
			// $this->width  += 0;
			// $this->height += 0;
			// $this->long   += 0; 1892 / 1901
			@$rs = $this->cart_model->getAttr(@$item->id_pa);
			array_push($data['attr']['attr_weight'], $rs[0]->weight);
			array_push($data['attr']['attr_width'], $rs[0]->width);
			array_push($data['attr']['attr_height'], $rs[0]->height);
			array_push($data['attr']['attr_long'], $rs[0]->length);

			$data['carton'] = $this->cal_carton($rs[0]->weight,$rs[0]->width,$rs[0]->height,$rs[0]->length);

		}	
		
		
		$this->load->view($this->layout, $data);

	}
	public function getCartQty()
	{
		if( $this->input->post('id_cart') )
		{
			$id_cart = $this->input->post('id_cart');
			if( $id_cart != 0 )
			{
				$qty = $this->cart_model->cartQty($id_cart);
				echo $qty;	
			}
			else
			{
				echo 'no_item';
			}				
		}
	}
	
	public function updateCart()
	{
		if( $this->input->post('id_cart') )
		{
			$id_cart 	= $this->input->post('id_cart');
			$id_pa 	= $this->input->post('id_pa');
			$qty 		= $this->input->post('qty');	
			$rs = $this->cart_model->updateCartProduct($id_cart, $id_pa, $qty);
			if( $rs )
			{
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
		}
	}
	
	public function deleteCartProduct()
	{
		// if( $this->input->post('id_cart') && $this->input->post('id_pa') )
		{
			$id_cart = $this->input->post('id_cart');
			$id_pa 	= $this->input->post('id_pa');
			$rs = $this->cart_model->deleteCartProduct($id_cart, $id_pa);
			if( $rs )
			{
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
		}
		
	}
	
	private function cal_carton($item_weight,$item_width,$item_height,$item_long){

		$boxAttr    = $this->cart_model->getBox();
		$box_weight = 0;
		$box_width  = 0;
		$box_long   = 0;
		$box_height = 0;


		foreach ($boxAttr as $box) {
			$box_weight  = $box->box_weight;
			$box_width   = $box->box_width;
			$box_long    = $box->box_long;
			$box_height  = $box->box_height;

			if($item_width < $box_width && $item_long < $box_long){
				$box_width -= 10;
				$box_long  -= $item_long;
			}

			return $box_width;

		}
	}


	public function addToCart(){
		$id_product = $this->input->post('id_product');
		$id_customer = $this->input->post('id_customer');
		
		if(!$this->session->userdata('id_customer')){

			$great_id = $this->cart_model->createGreatID();

			$cart_id  = $this->cart_model->createCartID($great_id);
			
			$this->cart_model->insertItem($cart_id,$id_product);
			echo "success";

		}else{
			$id_customer = $this->session->userdata('id_customer');
		    $x = $this->cart_model->addToCart($id_customer,$id_product);
			print_r($x);
		}
		
		
	}


	public function test(){
		echo "test";
	}

	
}/// end class

?>