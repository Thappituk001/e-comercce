<?php
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();	
		$this->load->model("login_model");
		$this->load->model('cart_model');
		// $this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->id_customer  = getIdCustomer();
		$this->id_cart 	    = getIdCart($this->id_customer);
		$this->cart_value	= $this->cart_model->cartValue($this->id_cart);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}

	public function index()
	{
		
		if( $this->input->post("user_name",true) && $this->input->post("password",true) )
		{

			$user 	= $this->input->post("user_name",true);
		    // $pass 	= md5(md5(md5($this->input->post("password"))));
			$pass 	= $this->input->post("password",true);
			$id_c	= $this->input->post("id_customer",true);
			$rmbm	= $this->input->post("rememberme",true);
			$rs 	= $this->login_model->getUser($user, $pass);
			//is member
			if( $rs )
			{
				if(@$rs->status=='0')
				{
					$user_info = $this->login_model->getUser_Info($rs->id_customer_online);
					
					$userdata = array(
						'id_customer'       => $user_info->id_customer,
						'first_name'		=> $user_info->fname,
						'last_name'			=> $user_info->lname,
						'email'				=> $rs->email,
						'tel'				=> $user_info->tel,
					);

					$id_cart_great  = $this->id_cart;
					$id_cart_member = $this->login_model->getIdCartMember($user_info->id_customer);

					$this->login_model->loged($user_info->id_customer);
					$this->login_model->switch_cart_item($id_cart_great,$id_cart_member);
					$this->session->set_userdata($userdata);
			
					echo "success";
				}
				else if(@$rs->status=='1')
				{
					echo "online";	
				}
			}
			else
			{
				echo "fail";	
			}
		}else{
			redirect('shop/', 'refresh');
		}
	}

	public function logOut()
	{
		$this->login_model->loged_out($_SESSION['id_customer']);
		
		$userdata = array('id_customer','first_name','last_name','email');
		$this->session->unset_userdata($userdata);
		
		redirect('shop/', 'refresh');
		
	}
	


}/// end class

?>