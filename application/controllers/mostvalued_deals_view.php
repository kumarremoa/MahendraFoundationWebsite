<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Mostvalued_deals_view extends CI_Controller{
        public function __construct(){
                parent::__construct();
                //$this->load->model("signup_model");
               }
        public function index(){
                $data   =   array(
                        "title"     =>  "Classifieds",
                        "content"   =>  "mostvalued_deals_view"
                );
                
                $this->load->view("classified_layout/inner_template",$data);
        }
}

