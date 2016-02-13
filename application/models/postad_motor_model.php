<?php 

class Postad_motor_model extends CI_Model{

	public function bike_type(){
    	$cv     =   $this->input->post("id");
    return $this->db->get_where("bike_type",array("brand_id" => $cv))->result();
    }

    public function bike_models(){
                $cv     =   $this->input->post("id");
    return $this->db->get_where("bike_model",array("btype_id" => $cv))->result();
    }

    /*car models*/
    public function car_models(){
        $cv     =   $this->input->post("id");
    return $this->db->get_where("car_model",array("brand_id" => $cv))->result();
    }

    /*get_plant_models*/
    public function get_plant_models(){
        $cv     =   $this->input->post("id");
    return $this->db->get_where("car_model",array("brand_id" => $cv))->result();
    }


    public function postad_creat(){
             /*AD type business or consumer*/
                    $cur_date = date("Y")."".date("m");
                        if($this->input->post('checkbox_toggle') == 'Yes'){
                                $ad_type = 'business';
                                
                                $target_dir = "./ad_images/business_logos/";
                            
                        // $target_file = $target_dir . basename($_FILES["file"]["name"]);
                        // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                                    if ($_FILES["file"]["name"] != '') {
                                       $new_name = explode(".", $_FILES["file"]["name"]);
                            $business_logo = "buslogo_".time().".".end($new_name);
                            move_uploaded_file($_FILES["file"]["tmp_name"],$target_dir."buslogo_".time().".".end($new_name));
                                    }
                                    else{
                                        $business_logo = '';
                                    }
                    
                        }
                        else{
                            $ad_type = 'consumer';
                            $business_logo = '';
                        }

                         /*web-link for free */
              if ($this->input->post('package_type') == 'free') {
                            $url = "";
                        }
                        /*web-link for free urgent*/
              if ($this->input->post('package_type') == 'free_urgent') {
                            $url = $this->input->post("freeurgent_weblink");
                        }
                        /*web-link for gold*/
              if ($this->input->post('package_type') == 'gold') {
                            $url = $this->input->post("gold_weblink");
                        }
                        /*web-link for gold + urgent*/
              if ($this->input->post('package_type') == 'gold_urgent') {
                            $url = $this->input->post("goldurgent_weblink");
                        }
                        /*web-link for platinum*/
              if ($this->input->post('package_type') == 'platinum') {
                            $url = $this->input->post("platinum_weblink");
                        }


                        $data = array('ad_prefix' => $cur_date,
                                    'login_id'  => $this->input->post('login_id'),
                                    'package_type'=> $this->input->post('package_type'),
                                    'urgent_package' => $this->input->post('package_urgent'),
                                    'package_name' => $ad_type."_".$this->input->post('package_name'),
                                    'deal_tag'    => $this->input->post('dealtag'),
                                    'deal_desc'   =>$this->input->post('dealdescription'),
                                     'currency'   =>$this->input->post('checkbox_toggle1'),
                                    'service_type'=> '',
                                    'services'    => $this->input->post('checkbox_motbike'),
                                    'price_type'  => $this->input->post('price_type'),
                                    'price'       => $this->input->post('priceamount'),
                                    'web_link'    => $url,
                                    'category_id' => $this->input->post('category_id'),
                                    'sub_cat_id'  => $this->input->post('sub_id'),
                                    'sub_scat_id' => $this->input->post('sub_sub_id'),
                                    'ad_type'     => $ad_type,
                                    'created_on'   => date('d-m-Y h:i:s'),
                                    'updated_on'   => date('d-m-Y h:i:s'),
                                    'terms_conditions' =>$this->input->post('terms_condition'),
                                    'ad_status'     => 1
                                    );
                // echo "<pre>"; print_r($data); exit;
                    $this->db->insert('postad', $data);

                       $insert_id = $this->db->insert_id();

                      if ($insert_id != '') {
                        $this->session->set_userdata("postad_success","Ad Posted Successfully!!");
                        $this->session->set_userdata("postad_time",time());
                       }

                       /*location map*/
                    $loc = array('ad_id' => $insert_id,
                                'loc_name' => $this->input->post('location'),
                                'latt' => $this->input->post('lattitude'),
                                'longg' => $this->input->post('longtitude')
                                );
                        $this->db->insert("location", $loc);

                        /*free package*/
                    if ($this->input->post('package_type') == 'free') {
                       $plat_data = array('ad_id' => $insert_id,
                                            'ad_validfrom' => date("d-m-Y H:i:s"),
                                            'ad_validto' => date('d-m-Y H:i:s', strtotime("+30 days")),
                                            'status' => 1,
                                            'posted_date' => date("d-m-Y H:i:s")
                                    );
                       $this->db->insert('free_ads', $plat_data);
                    }

                    /*free+urgent package*/
                    if ($this->input->post('package_type') == 'free_urgent') {
                       $plat_data = array('ad_id' => $insert_id,
                                            'ad_validfrom' => date("d-m-Y H:i:s"),
                                            'ad_validto' => date('d-m-Y H:i:s', strtotime("+30 days")),
                                            'status' => 1,
                                            'posted_date' => date("d-m-Y H:i:s")
                                    );
                       $this->db->insert('freeurgent_ads', $plat_data);
                    }

                    /*gold package*/
                    if ($this->input->post('package_type') == 'gold') {
                       $plat_data = array('ad_id' => $insert_id,
                                            'ad_validfrom' => date("d-m-Y H:i:s"),
                                            'ad_validto' => date('d-m-Y H:i:s', strtotime("+30 days")),
                                            'status' => 1,
                                            'posted_date' => date("d-m-Y H:i:s")
                                    );
                       $this->db->insert('gold_ads', $plat_data);
                    }

                    /*gold+urgent package*/
                    if ($this->input->post('package_type') == 'gold_urgent') {
                       $plat_data = array('ad_id' => $insert_id,
                                            'ad_validfrom' => date("d-m-Y H:i:s"),
                                            'ad_validto' => date('d-m-Y H:i:s', strtotime("+30 days")),
                                            'status' => 1,
                                            'posted_date' => date("d-m-Y H:i:s")
                                    );
                       $this->db->insert('goldurgent_ads', $plat_data);
                    }

                        /*platinum package*/
                    if ($this->input->post('package_type') == 'platinum') {
                       $plat_data = array('ad_id' => $insert_id,
                                        'marquee'=>$this->input->post('marquee_title'),
                                            'ad_validfrom' => date("d-m-Y H:i:s"),
                                            'ad_validto' => date('d-m-Y H:i:s', strtotime("+30 days")),
                                            'status' => 1,
                                            'posted_date' => date("d-m-Y H:i:s")
                                    );
                       $this->db->insert('platinum_ads', $plat_data);
                    }



                     /*image upload*/
                             $i=1;
                       foreach($this->input->post('pic_hide') as $rawData){ 
                                $filteredData = explode(',', $rawData);
                            $unencoded = base64_decode($filteredData[1]);
                            //Create the image 
                            $fp = fopen('./ad_images/'.time().$i.'.jpg', 'w');
                            $plat_img = array('ad_id' => $insert_id,
                                        'img_name' => time().$i.'.jpg',
                                        'img_time' => date('d-m-Y H:i:s'),
                                        'status' => 1,
                                        'bus_logo' => $business_logo
                                    );
                        $this->db->insert("ad_img", $plat_img);
                            fwrite($fp, $unencoded);
                            fclose($fp); 
                            $i++;
                       }

                        /*video upload platinum*/
                       if($this->input->post('file_video_platinum')){
                   $plat_video = array('ad_id' => $insert_id,
                                    'video_name' => $this->input->post('file_video_platinum'),
                                    'uploaded_time' => date('d-m-Y H:i:s')
                                );
                    $this->db->insert("videos", $plat_video);
                                }
                           

                    /*contact info*/
                    if ( $ad_type == 'consumer') {
                        $plat_cont = array('ad_id' => $insert_id,
                                    'contact_name' => $this->input->post('conscontname'),
                                    'email' => $this->input->post('consemail'),
                                    'mobile' => $this->input->post('conssmblno')
                                );
                        $this->db->insert("contactinfo_consumer", $plat_cont);
                    }

                    /*contact info*/
                    if ( $ad_type == 'business') {
                        $plat_cont = array('ad_id' => $insert_id,
                                    'bus_name' => $this->input->post('busname'),
                                    'contact_person' => $this->input->post('buscontname'),
                                    'email' => $this->input->post('busemail'),
                                    'mobile'=>$this->input->post('bussmblno')
                                );
                        $this->db->insert("contactinfo_business", $plat_cont);
                    }

                    /*motor point details*/
                    if ($this->input->post('category_id') == 'motorpoint') {
                        /*cars, vans, coaches, buses details*/
                         if ($this->input->post('sub_id') == '12' || $this->input->post('sub_id') == '15' || $this->input->post('sub_id') == '16') {
                            $cars_details = array('ad_id' => $insert_id,
                                            'reg_number' => $this->input->post('veh_regno'),
                                            'manufacture' => $this->input->post('manufacture'),
                                            'model' => $this->input->post('Model'),
                                            'color'=>$this->input->post('color'),
                                            'reg_year'=>$this->input->post('reg_year'),
                                            'fueltype'=>$this->input->post('FuelType'),
                                            'transmission'=>$this->input->post('Transmission'),
                                            'engine_size'=>$this->input->post('eng_size'),
                                            'noofdoors'=>$this->input->post('NoofDoors'),
                                            'noofseats'=>$this->input->post('NoofSeats'),
                                            'tot_miles'=>$this->input->post('tot_miles'),
                                            'mot_status'=>$this->input->post('mot_status'),
                                            'road_tax'=>$this->input->post('road_tax')
                                );
                        $this->db->insert("motor_car_van_bus_ads", $cars_details);
                        }

                        /*bike details*/
                        if ($this->input->post('sub_id') == '13') {
                            $bike_details = array('ad_id' => $insert_id,
		                                    'reg_number' => $this->input->post('veh_regno'),
		                                    'manufacture' => $this->input->post('manufacture'),
		                                    'bike_type' => $this->input->post('Type'),
		                                    'model'=>$this->input->post('Model'),
		                                    'color'=>$this->input->post('color'),
											'reg_year'=>$this->input->post('reg_year'),
											'fuel_type'=>$this->input->post('FuelType'),
											'no_of_miles'=>$this->input->post('tot_miles'),
											'engine_size'=>$this->input->post('eng_size'),
											'road_tax'=>$this->input->post('road_tax'),
											'condition'=>$this->input->post('Condition')
                                );
                        $this->db->insert("motor_bike_ads", $bike_details);
                        }

                        /*motor_boats details*/
                        if ($this->input->post('sub_id') == '19') {
                            $motor_boats = array('ad_id' => $insert_id,
                                            'manufacture' => $this->input->post('manufacture'),
                                            'year' => $this->input->post('year_boat'),
                                            'model'=>$this->input->post('Model'),
                                            'color'=>$this->input->post('color'),
                                            'fueltype'=>$this->input->post('FuelType'),
                                            'condition'=>$this->input->post('Condition')
                                );
                        $this->db->insert("motor_boats", $motor_boats);
                        }

                        /*Campervans and Motor homes details*/
                        if ($this->input->post('sub_id') == '14') {
                            $motor_homes = array('ad_id' => $insert_id,
                                            'typeofmotorhome'=>$this->input->post('Caravans'),
                                            'reg_number' => $this->input->post('veh_regno'),
                                            'manufacture' => $this->input->post('manufacture'),
                                            'model' => $this->input->post('Model'),
                                            'color'=>$this->input->post('color'),
                                            'reg_year'=>$this->input->post('reg_year'),
                                            'fueltype'=>$this->input->post('FuelType'),
                                            'transmission'=>$this->input->post('Transmission'),
                                            'engine_size'=>$this->input->post('eng_size'),
                                            'noofdoors'=>$this->input->post('NoofDoors'),
                                            'noofseats'=>$this->input->post('NoofSeats'),
                                            'tot_miles'=>$this->input->post('tot_miles'),
                                            'mot_status'=>$this->input->post('mot_status'),
                                            'road_tax'=>$this->input->post('road_tax')
                                );
                        $this->db->insert("motor_home_ads", $motor_homes);
                        }

                        /*plant machinery and farming vehicles*/
                        if ($this->input->post('sub_id') == '17' || $this->input->post('sub_id') == '18') {
                            $motor_homes = array('ad_id' => $insert_id,
                                            'manufacture'=> $this->input->post('manufacture'),
                                            'reg_year'=> $this->input->post('year_boat'),
                                            'model'=> $this->input->post('plant_model'),
                                            'color'=> $this->input->post('color'),
                                            'condition'=>$this->input->post('Condition')
                                );
                        $this->db->insert("motor_plant_farming", $motor_homes);
                        }

                            /*urgent lable expiry*/
                        if ($this->input->post('package_urgent') != '') {
                            $days = array_shift(explode("daysurgent", $this->input->post('package_urgent')));
                            $urgent_details = array('ad_id' => $insert_id,
                                        'valid_from' => date('d-m-Y H:i:s'),
                                        'valid_to' => date('d-m-Y H:i:s', strtotime("+$days days")),
                                        'no_ofdays' => $days,
                                        'status'=>1
                                    );
                            $this->db->insert("urgent_details", $urgent_details);
                        }
                    }

            
            }

}


 ?>