<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Reserved_searches extends CI_Controller{
        public function __construct(){
                parent::__construct();
                $this->load->model("classifed_model");
               }
        public function index(){
            if ($this->session->userdata('login_id') == '') {
                   redirect('login');
                }
				
				$log_name = @mysql_result(mysql_query("SELECT first_name FROM `login` WHERE `login_id` = '".$this->session->userdata('login_id')."' "), 0, 'first_name');
                $search_count = $this->classifed_model->savedsearch_count();
				$search_list = $this->classifed_model->savedsearch_list();
                $data   =   array(
                        "title"     =>  "Classifieds",
                        "content"   =>  "reserved_searches",
                        'log_name'=>$log_name,
                        'search_count' => $search_count,
                        'search_list' => $search_list
                );
                
                $this->load->view("classified_layout/inner_template",$data);
        }
}

