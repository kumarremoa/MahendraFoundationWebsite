<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Forgot_password extends CI_Controller{
        public function __construct(){
                parent::__construct();
                $this->load->model("login_model");
        }
        public function index(){
                $data   =   array(
                        "title"     =>  "Classifieds",
                        "content"   =>  "forgot_password"
                );

                if ($this->input->post('forgot')) {
                         $this->form_validation->set_rules("forgotemail","Email Id","required|valid_email");
                         if($this->form_validation->run() == TRUE){
                                redirect('forgot_password');
                         }
                }
                
                $this->load->view("classified_layout/inner_template",$data);
        }
        
        
}

?>

