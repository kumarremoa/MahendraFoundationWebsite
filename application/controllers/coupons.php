<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
	class Coupons extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model("admin_model");
			$this->load->model("coupons_model");
		}
		public function index(){
			if($this->input->post("sign_in")){
					$this->form_validation->set_rules("email","Email Id","required|valid_email");
					$this->form_validation->set_rules("password","Password","required");
					if($this->form_validation->run() == TRUE){
							$ins = $this->admin_model->login();
							if($ins == 1){
									$this->session->set_flashdata("msg","Login Success");
									redirect("admin_dashboard");
							}
							else{
									$this->session->set_flashdata("err","Login Failed");
									redirect("admin/index");
							}
					}
			}
		$this->load->view('admin_layout/login');
	}
	
		public function ListCoupons(){
		
			$coupons_list = $this->coupons_model->get_coupons();
			$data   =   array(
						"title"         =>     "Admin Dashboard",
						"metadesc"      =>     "Classifieds :: Admin Dashboard",
						"metakey"       =>     "Classifieds :: Admin Dashboard",
						"content"       =>     "coupons_list",
						"coupons_list"  =>     $coupons_list,
				);
			$this->load->view("admin_layout/inner_template",$data);
		}
		public function AddCoupon(){
			if($this->input->post()){
				//echo '<pre>';print_r($this->input->post());echo '</pre>';//exit;
				$this->form_validation->set_rules("c_value","Coupon Code","required");
				$this->form_validation->set_rules("c_prefix","Coupon Prefix","required");
				//$this->form_validation->set_rules("max_disc","Maximum Discount","required");
				//$this->form_validation->set_rules("c_type","Coupon Type ","required");
				//$this->form_validation->set_rules("c_count","Coupon Count","required");
				$this->form_validation->set_rules("c_status","Coupon Status","required");
				if($this->form_validation->run() == TRUE){
					$this->session->set_flashdata('msg','Coupon Code is Successfully Inserted');
					 $ins_status = $this->coupons_model->add_new_coupon();	
					 redirect('coupons/ListCoupons');
				}
				else{
					$this->session->set_flashdata('err','Some details are not valid, Please try again');
				}
			}
			$data   =   array(
					"title"         =>     "Admin Dashboard",
					"metadesc"      =>     "Classifieds :: Admin Dashboard",
					"metakey"       =>     "Classifieds :: Admin Dashboard",
					"content"       =>     "addNewCoupon",
			);
			$this->load->view("admin_layout/inner_template",$data);
		}
		function change_status(){
			$change_status = $this->coupons_model->change_status();
			$status = $this->input->post('status');
			$coupon = $this->input->post('coupon');
			if($status == 0){
				echo "<span class='btn btn-danger'><i class='halflings-icon plus-sign active_coupon' id='coupon_".$coupon."'title='Activate Coupon'></i></span>";
			}else{
				echo "<span class='btn btn-success'><i class='halflings-icon minus-sign inactive_coupon' id='coupon_".$coupon."'title='In-Activate Coupon'></i></span>";
			}
			//echo $this->db->last_query();
			//return $change_status;
		}
		function get_c_result(){
			if($this->input->post('c_code')){
				$c_code = $this->input->post('c_code');
				//$post_ad_amt = $this->input->post('post_ad_amt');
				$ad_id = $this->input->post('ad_id');
				$p_amt = $this->coupons_model->get_ad_amt($ad_id);
				//echo '<pre>';print_r($p_amt);echo '</pre>';
				$amt = $p_amt->u_pkg__pound_cost+$p_amt->cost_pound;
				$c_info = $this->coupons_model->get_c_result($c_code);
				if(count($c_info) == 1){
					$disc = $amt*($c_info->c_value)/100;
					if($c_info->max_cus == 0){
						
						$pkg_disc_amt = $amt-(($amt*($disc)/100));
						//echo $pkg_disc_amt ;
						$c_details = array(
										'c_code'		=>		$c_info->c_code,
										'c_value' 		=>		$c_info->c_value,
										'max_cus' 		=>		$c_info->max_cus,
										'used_count' 	=>		$c_info->used_count,
										'pkg_disc_amt'	=>		round($pkg_disc_amt,2),
										'disc'			=>		round($disc,2),
										'c_responce'	=>		'After Applying the Coupon <b>'.$c_info->c_code.'</b>, The Amount to be paid is '.$pkg_disc_amt
							); 
							$info = json_encode($c_details);
							echo $info;
					}else{
						if($c_info->max_cus > $c_info->used_count){
							$pkg_disc_amt = $amt-(($amt*($c_info->c_value))/100);
							//echo $pkg_disc_amt ;
							$c_details = array(
										'c_code'		=>		$c_info->c_code,
										'c_value' 		=>		$c_info->c_value,
										'max_cus' 		=>		$c_info->max_cus,
										'used_count' 	=>		$c_info->used_count,
										'pkg_disc_amt'	=>		round($pkg_disc_amt, 2),
										'disc'			=>		round($disc,2),
										'c_responce'	=>		'After Applying the Coupon <b>'.$c_info->c_code.'</b>, The Amount to be paid is '.round($pkg_disc_amt, 2)
							); 
							$info = json_encode($c_details);
							echo $info;
						 }else{
							 $c_details = array(
										'c_code'		=>		$c_info->c_code,
										'c_value' 		=>		0,
										'max_cus' 		=>		$c_info->max_cus,
										'used_count' 	=>		$c_info->used_count,
										'pkg_disc_amt'	=>		round($amt, 2),
										'c_responce'	=>		'The Coupon Code you have added is Expired or Invalid.' 
							); 
							$info = json_encode($c_details);
							echo $info;
						 }
					}
				}else{
					$c_details = array(
										'c_code'		=>		$c_info->c_code,
										'c_value' 		=>		0,
										'max_cus' 		=>		$c_info->max_cus,
										'used_count' 	=>		$c_info->used_count,
										'pkg_disc_amt'	=>		round($amt, 2),
										'c_responce'	=>		'The Coupon Code you have added is Expired or Invalid.' ,
							); 
							$info = json_encode($c_details);
							echo $info;
				}
			}
		}
	}
?>

