<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Update_profile extends CI_Controller{
        public function __construct(){
                parent::__construct();
                $this->load->model("profile_model");
               }
        public function index(){
                /*session expiry to login*/
                if ($this->session->userdata('login_id') == '') {
                   redirect('login');
                }
				
				$log_name = @mysql_result(mysql_query("SELECT first_name FROM `login` WHERE `login_id` = '".$this->session->userdata('login_id')."'"), 0, 'first_name');

                if ($this->session->userdata("postad_time") != '') {
                    $new_time = time() - $this->session->userdata("postad_time");
                    if ($new_time > 0) {
                        $this->session->unset_userdata('postad_success');
                    }
                }
                /*stored profile data*/
                // echo "<pre>"; print_r($this->session->all_userdata());
                // echo "<pre>"; print_r($this->profile_model->prof_data());
                // $data['prof_data'] = $this->profile_model->prof_data();

                $data   =   array(
                        "title"     =>  "Classifieds",
                        "content"   =>  "update_profile",
                        'prof_data' =>  $this->profile_model->prof_data(),
                        'log_name'=>$log_name
                );
                
                $this->load->view("classified_layout/inner_template",$data);
        }

        public function up_profile(){
                $res_prof = $this->profile_model->prof_update();
                if($res_prof == 1){
                $this->session->set_flashdata("msg","profile updated successfully!!");
               redirect(base_url()."update-profile");   
                }
                else{
                 $this->session->set_flashdata("err","Invalid inputs");
                 redirect(base_url()."update-profile");
                }
        }

        /*change password*/
        public function change_pwd(){
           $res_pwd = $this->profile_model->change_pwd_exist();
                if($res_pwd == 0){
                    $this->profile_model->change_pwd_up();
                    $this->session->set_flashdata("msg","Password Changed Successfully!!");
                    redirect(base_url()."update-profile");
                }else{
                    $this->session->set_flashdata("err","Incorrect Password");
                    redirect(base_url()."update-profile");
                }
        }

        /*deactivate account*/
        public function deactivate_account(){
            $rand_val = md5(rand(10000,99999));
            $inp            =       $this->profile_model->deactivate($rand_val);
            if ($inp == 1) {
                 $this->session->unset_userdata('login_id');
                $this->session->set_flashdata("msg","Account Deactivated Successfully!!");
                 echo json_encode('0');
            }
            else{
                $this->session->set_flashdata("err","internal error occured!!");
                 echo json_encode('1');

            }
        }

        /*re activate account*/
        public function re_activate(){
                 $uri            =       $this->uri->segment(3);
                $login_id       =        $this->uri->segment(4);

                $in = $this->profile_model->activate($uri, $login_id);
                if($in == 1){
                     $this->session->unset_userdata('login_id');
                    $this->session->set_flashdata("msg","Re-activated Successfully!!");
                    redirect('login');
                }
                else{
                    $this->session->set_flashdata("msg","Internal error");
                    redirect('login');
                }
        }
}

