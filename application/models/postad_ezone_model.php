<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Postad_ezone_model extends CI_Model{
       public function postad_creat(){
             /*AD type business or consumer*/
                    $cur_date = date("Y")."".date("m");
                        if($this->input->post('checkbox_toggle') == 'Yes'){
                                $ad_type = 'business';
                                
                                $target_dir = "./pictures/business_logos/";
                            
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

                         /*is free or not*/
                        if (($this->input->post('package_type') == 4) && $this->input->post('package_urgent') == 0) {
                            $isfree = 1;
                            $payment = 1;
                        }
                        else{
                            $isfree = 0;
                            $payment = 0;
                        }
                         /*web-link for free */
              if ($this->input->post('package_type') == 4) {
                            $url = "";
                        }
                        /*web-link for gold*/
              if ($this->input->post('package_type') == 5) {
                            $url = $this->input->post("gold_weblink");
                        }
                        /*web-link for platinum*/
              if ($this->input->post('package_type') == 6) {
                            $url = $this->input->post("platinum_weblink");
                        }
                        /*accessories type*/
                       if ($this->input->post('sub_sub_id') == 431 || $this->input->post('sub_sub_id') == 432
                        || $this->input->post('sub_sub_id') == 433 || $this->input->post('sub_sub_id') == 434 || 
                        $this->input->post('sub_sub_id') == 435) {
                            $service_type = $this->input->post("accessoriestype");
                        }
                        else if($this->input->post('sub_id') == 70){
                             $service_type = $this->input->post("peripheraltype");
                        }
                        else if($this->input->post('sub_id') == 71){
                             $service_type = $this->input->post("component_type");
                        }
                        else if($this->input->post('sub_id') == 72){
                             $service_type = $this->input->post("software_type");
                        }
                        else{
                             $service_type = '';
                        }
                                 




                        $data = array('ad_prefix' => $cur_date,
                                    'login_id'  => $this->input->post('login_id'),
                                    'package_type'=> $this->input->post('package_type'),
                                    'urgent_package' => $this->input->post('package_urgent'),
                                    'package_name' => $ad_type."_".$this->input->post('package_name'),
                                    'deal_tag'    => $this->input->post('dealtag'),
                                    'deal_desc'   =>$this->input->post('dealdescription'),
                                     'currency'   =>$this->input->post('checkbox_toggle1'),
                                    'service_type'=> $service_type,
                                    'services'    => $this->input->post('checkbox_motbike'),
                                    'price_type'  => $this->input->post('price_type'),
                                    'price'       => $this->input->post('priceamount'),
                                    'web_link'    => $url,
                                    'category_id' => $this->input->post('category_id'),
                                    'sub_cat_id'  => $this->input->post('sub_id'),
                                    'sub_scat_id' => $this->input->post('sub_sub_id'),
                                    'ad_type'     => $ad_type,
                                    'created_on'   => date('d-m-Y H:i:s'),
                                    'updated_on'   => date('d-m-Y H:i:s'),
                                    'terms_conditions' =>$this->input->post('terms_condition'),
                                    'payment_status' => $payment,
                                    'ad_status'     => 0,
                                    'is_free' => $isfree
                                    );
                // echo "<pre>"; print_r($data); exit;
                    $this->db->insert('postad', $data);

                       $insert_id = $this->db->insert_id();
                       $this->session->set_userdata("last_insert_id", $insert_id);
                     
                       /*location map*/
                    $loc = array('ad_id' => $insert_id,
                                'loc_name' => $this->input->post('location'),
                                'latt' => $this->input->post('lattitude'),
                                'longg' => $this->input->post('longtitude'),
                                'loc_city' => $this->input->post('loc_city'),
                                'location_name' => $this->input->post('location_name')
                                );
                        $this->db->insert("location", $loc);

                      /*platinum package*/
                    if ($this->input->post('package_type') == 6) {
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
                            $fp = fopen('./pictures/'.time().$i.'.jpg', 'w');
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

                    /*ezone details*/
                    if ($this->input->post('category_id') == '8') {
                        if($this->input->post('sub_id') == 72){
                             $pets_details = array('ad_id' => $insert_id,
                                        'brand_name' => $this->input->post('brandname'),
                                        'size' => $this->input->post('no_of_pcs'),
                                        'model_name'=>$this->input->post('modelname'),
                                        'operating_system'=>$this->input->post('softwareos'),
                                        'warranty'=>$this->input->post('subscripvalidity'),
                                        'manufacture'=>$this->input->post('media_format')
                                    );
                            $this->db->insert("ezone_details", $pets_details);
                        }
                        else{
                             $pets_details = array('ad_id' => $insert_id,
                                    'brand_name' => $this->input->post('brandname'),
                                    'size' => $this->input->post('screensize'),
                                    'color' => $this->input->post('color'),
                                    'model_name'=>$this->input->post('modelname'),
                                    'operating_system'=>$this->input->post('opersys'),
                                    'made_in'=>$this->input->post('ezone_madein'),
                                    'storage'=>$this->input->post('storage'),
                                    'warranty'=>$this->input->post('warranty'),
                                    'manufacture'=>$this->input->post('ezone_manufacture')
                                );
                        $this->db->insert("ezone_details", $pets_details);
                        }
                    }

                   

                     if ($insert_id != '') {
                        $this->session->set_userdata("postad_success","Ad Posted Successfully!!");
                        $this->session->set_userdata("postad_time",time());
                       }

            
            }


             /* phone and tables*/
             /*mobile phones */
            public function busconcount_mphones(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_mphones(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_mphones(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 383 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_mphones_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "383");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function mphones_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "383");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_mphones_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "383");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function mphones_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "383");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* phone and tables*/
             /*Tablets & iPads */
            public function busconcount_tabipad(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_tabipad(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_tabipad(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 384 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_tabipad_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "384");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function tabipad_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "384");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_tabipad_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "384");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function tabipad_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "384");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* phone and tables*/
             /*Tablets & iPads */
            public function busconcount_bluetooth(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_bluetooth(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_bluetooth(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 385 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_bluetooth_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "385");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function bluetooth_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "385");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_bluetooth_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "385");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function bluetooth_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "385");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* phone and tables*/
             /*Wearable Devices */
            public function busconcount_wearabledevices(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_wearabledevices(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_wearabledevices(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 59 AND sub_scat_id = 392 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_wearabledevices_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "392");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function wearabledevices_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "392");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_wearabledevices_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "392");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function wearabledevices_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "59");
                $this->db->where("ad.sub_scat_id", "392");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
             /* Home Appliances*/
             /* Air Conditioners */
            public function busconcount_airconditioners(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_airconditioners(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_airconditioners(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 393 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_airconditioners_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "393");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function airconditioners_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "393");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_airconditioners_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "393");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function airconditioners_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "393");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
             /* Home Appliances*/
             /* Air Coolers */
            public function busconcount_aircoolers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_aircoolers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_aircoolers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 394 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_aircoolers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "394");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function aircoolers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "394");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_aircoolers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "394");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function aircoolers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "394");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            /* Home Appliances*/
             /* Fans */
            public function busconcount_fans(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_fans(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_fans(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 395 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_fans_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "395");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function fans_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "395");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_fans_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "395");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function fans_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "395");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
             /* Home Appliances*/
             /* Refrigerators */
            public function busconcount_refrigerators(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_refrigerators(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_refrigerators(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 396 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_refrigerators_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "396");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function refrigerators_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "396");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_refrigerators_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "396");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function refrigerators_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "396");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            /* Home Appliances*/
             /* Washing Machines */
            public function busconcount_washingmachines(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_washingmachines(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_washingmachines(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 397 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_washingmachines_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "397");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function washingmachines_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "397");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_washingmachines_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "397");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function washingmachines_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "397");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            /* Home Appliances*/
             /* Electric Iron */
            public function busconcount_electriciron(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_electriciron(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_electriciron(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 398 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_electriciron_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "398");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function electriciron_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "398");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_electriciron_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "398");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function electriciron_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "398");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /* Vacuum Cleaners */
            public function busconcount_vacuumcleaners(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_vacuumcleaners(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_vacuumcleaners(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 399 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_vacuumcleaners_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "399");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function vacuumcleaners_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "399");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_vacuumcleaners_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "399");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function vacuumcleaners_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "399");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /*Water Heaters*/
            public function busconcount_waterheaters(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_waterheaters(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_waterheaters(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 400 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_waterheaters_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "400");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function waterheaters_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "400");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_waterheaters_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "400");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function waterheaters_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "400");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /*Room Heaters*/
            public function busconcount_roomheaters(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_roomheaters(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_roomheaters(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 401 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_roomheaters_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "401");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function roomheaters_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "401");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_roomheaters_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "401");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function roomheaters_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "401");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Home Appliances*/
             /*Sewing Machine*/
            public function busconcount_sewingmachine(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_sewingmachine(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_sewingmachine(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 402 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_sewingmachine_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "402");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function sewingmachine_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "402");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_sewingmachine_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "402");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function sewingmachine_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "402");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /* Dryers */
            public function busconcount_dryers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_dryers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_dryers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 403 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_dryers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "403");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function dryers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "403");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_dryers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "403");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function dryers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "403");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /* Emergency Light */
            public function busconcount_emergencylight(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_emergencylight(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_emergencylight(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 404 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_emergencylight_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "404");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function emergencylight_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "404");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_emergencylight_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "404");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function emergencylight_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "404");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Appliances*/
             /* inverters */
            public function busconcount_inverters(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_inverters(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_inverters(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 494 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_inverters_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "494");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function inverters_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "494");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_inverters_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "494");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function inverters_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "494");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Home Appliances*/
             /* Others Home Applications */
            public function busconcount_othershomeapp(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_othershomeapp(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_othershomeapp(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 60 AND sub_scat_id = 495 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_othershomeapp_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "495");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function othershomeapp_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "495");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_othershomeapp_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "495");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function othershomeapp_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "60");
                $this->db->where("ad.sub_scat_id", "495");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Microwave Ovens & OTG */
            public function busconcount_microwaveovens(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_microwaveovens(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_microwaveovens(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 405 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_microwaveovens_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "405");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function microwaveovens_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "405");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_microwaveovens_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "405");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function microwaveovens_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "405");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Food Processors */
            public function busconcount_foodprocessors(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_foodprocessors(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_foodprocessors(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 406 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_foodprocessors_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "406");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function foodprocessors_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "406");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_foodprocessors_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "406");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function foodprocessors_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "406");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Mixer Grinder Juicers */
            public function busconcount_mixergrinderjuicers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_mixergrinderjuicers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_mixergrinderjuicers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 407 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_mixergrinderjuicers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "407");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function mixergrinderjuicers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "407");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_mixergrinderjuicers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "407");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function mixergrinderjuicers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "407");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Cookers & Steamers */
            public function busconcount_cookersteamers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_cookersteamers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_cookersteamers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 408 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_cookersteamers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "408");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function cookersteamers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "408");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_cookersteamers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "408");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function cookersteamers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "408");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Toasters & Sandwich Makers */
            public function busconcount_toastersandwichmakers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_toastersandwichmakers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_toastersandwichmakers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 409 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_toastersandwichmakers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "409");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function toastersandwichmakers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "409");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_toastersandwichmakers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "409");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function toastersandwichmakers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "409");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Blenders & Choppers */
            public function busconcount_blenderschoppers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_blenderschoppers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_blenderschoppers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 410 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_blenderschoppers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "410");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function blenderschoppers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "410");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_blenderschoppers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "410");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function blenderschoppers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "410");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Small Appliances*/
             /* Grills & Tandooris */
            public function busconcount_grillstandooris(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_grillstandooris(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_grillstandooris(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 411 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_grillstandooris_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "411");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function grillstandooris_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "411");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_grillstandooris_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "411");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function grillstandooris_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "411");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            

             /* Small Appliances*/
             /* Coffee Tea Makers & Kettles */
            public function busconcount_kettles(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_kettles(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_kettles(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 412 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_kettles_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "412");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function kettles_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "412");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_kettles_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "412");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function kettles_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "412");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Small Appliances*/
             /* Fryers & Snack makers */
            public function busconcount_fryersmakers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_fryersmakers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_fryersmakers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 413 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_fryersmakers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "413");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function fryersmakers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "413");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_fryersmakers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "413");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function fryersmakers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "413");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Small Appliances*/
             /* Water Purifiers */
            public function busconcount_waterpurifiers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_waterpurifiers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_waterpurifiers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 414 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_waterpurifiers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "414");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function waterpurifiers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "414");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_waterpurifiers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "414");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function waterpurifiers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "414");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Small Appliances*/
             /* Dishwashers */
            public function busconcount_dishwashers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_dishwashers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_dishwashers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 415 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_dishwashers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "415");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function dishwashers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "415");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_dishwashers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "415");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function dishwashers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "415");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Small Appliances*/
             /* Flour Mill */
            public function busconcount_flourmill(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_flourmill(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_flourmill(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 416 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_flourmill_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "416");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function flourmill_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "416");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_flourmill_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "416");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function flourmill_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "416");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Small Appliances*/
             /* Stabilizers */
            public function busconcount_stabilizers(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_stabilizers(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_stabilizers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 496 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_stabilizers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "496");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function stabilizers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "496");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_stabilizers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "496");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function stabilizers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "496");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Small Appliances*/
             /* Others Small Applications */
            public function busconcount_othersmallapp(){
                        $data = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function sellerneeded_othersmallapp(){
                        $date = date("Y-m-d H:i:s");
                        $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                        (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                        $rs = $this->db->get();
                        return $rs->result();
                    }
            public function deals_pck_othersmallapp(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 61 AND sub_scat_id = 497 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_othersmallapp_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "497");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function othersmallapp_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "497");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_othersmallapp_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "497");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function othersmallapp_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "61");
                $this->db->where("ad.sub_scat_id", "497");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Accessories  */

            /*Tablet & Mobile Accessories */
            public function busconcount_amobiletablet(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_amobiletablet(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_amobiletablet(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 429 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_amobiletablet_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "429");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function amobiletablet_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "429");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_amobiletablet_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "429");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function amobiletablet_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "429");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Computer Accessories */
            public function busconcount_acomputer(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_acomputer(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_acomputer(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 432 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_acomputer_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "432");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function acomputer_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "432");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_acomputer_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "432");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function acomputer_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "432");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Headphones & Earphones */
            public function busconcount_aheadphomes(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_aheadphomes(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_aheadphomes(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 433 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_aheadphomes_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "433");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function aheadphomes_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "433");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_aheadphomes_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "433");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function aheadphomes_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "433");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Audio Video Accessories */
            public function busconcount_aaudiovideo(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_aaudiovideo(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_aaudiovideo(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 434 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_aaudiovideo_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "434");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function aaudiovideo_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "434");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_aaudiovideo_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "434");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function aaudiovideo_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "434");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Camera Accessories */
            public function busconcount_acamera(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_acamera(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_acamera(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 435 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_acamera_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "435");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function acamera_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "435");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_acamera_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "435");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function acamera_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "435");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Battery */
            public function busconcount_abattery(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_abattery(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_abattery(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 436 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_abattery_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "436");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function abattery_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "436");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_abattery_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "436");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function abattery_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "436");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Others Accassories */
            public function busconcount_otheraccessories(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_otheraccessories(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_otheraccessories(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 63 AND sub_scat_id = 437 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_otheraccessories_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "437");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function otheraccessories_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "437");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_otheraccessories_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "437");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function otheraccessories_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "63");
                $this->db->where("ad.sub_scat_id", "437");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }



            /* Personal Care */

            /* Shavers */
            public function busconcount_pcshavers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcshavers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcshavers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 438 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcshavers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "438");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcshavers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "438");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcshavers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "438");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcshavers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "438");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Trimmers */
            public function busconcount_pctrimmers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pctrimmers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pctrimmers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 439 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pctrimmers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "439");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pctrimmers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "439");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pctrimmers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "439");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pctrimmers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "439");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Body Groomers */
            public function busconcount_pcbodygromers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcbodygromers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcbodygromers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 440 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcbodygromers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "440");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcbodygromers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "440");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcbodygromers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "440");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcbodygromers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "440");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }



            /* Hair Dryers */
            public function busconcount_pchairdryers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pchairdryers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pchairdryers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 441 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pchairdryers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "441");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pchairdryers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "441");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pchairdryers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "441");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pchairdryers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "441");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Hair Stylers */
            public function busconcount_pchairstylers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pchairstylers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pchairstylers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 442 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pchairstylers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "442");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pchairstylers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "442");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pchairstylers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "442");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pchairstylers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "442");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }



            /* Epilators */
            public function busconcount_pcepilators(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcepilators(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcepilators(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 443 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcepilators_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "443");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcepilators_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "443");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcepilators_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "443");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcepilators_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "443");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }



            /* Pedometers */
            public function busconcount_pcpedometers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcpedometers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcpedometers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 444 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcpedometers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "444");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcpedometers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "444");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcpedometers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "444");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcpedometers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "444");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Monitors */
            public function busconcount_pcmonitors(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcmonitors(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcmonitors(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcmonitors_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcmonitors_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcmonitors_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcmonitors_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Massagers */
            public function busconcount_pcmassagers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcmassagers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcmassagers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 445 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcmassagers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcmassagers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcmassagers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcmassagers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "445");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

             /* Others Personal Care */
            public function busconcount_pcothers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcothers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcothers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 64 AND sub_scat_id = 446 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcothers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "446");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcothers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "446");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcothers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "446");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcothers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "64");
                $this->db->where("ad.sub_scat_id", "446");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }

            /* Home Entertainment */

            /* LCD & LED Televisions */
            public function busconcount_helcdled(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_helcdled(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_helcdled(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 447 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_helcdled_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "447");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function helcdled_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "447");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_helcdled_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "447");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function helcdled_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "447");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Gaming */
            public function busconcount_hegaming(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_hegaming(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_hegaming(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 451 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_hegaming_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "451");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function hegaming_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "451");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_hegaming_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "451");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function hegaming_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "451");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Musical Instruments */
            public function busconcount_hemusicalinst(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_hemusicalinst(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_hemusicalinst(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 452 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_hemusicalinst_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "452");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function hemusicalinst_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "452");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_hemusicalinst_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "452");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function hemusicalinst_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "452");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Others Home Entertainement */
            public function busconcount_heothers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_heothers(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_heothers(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 65 AND sub_scat_id = 453 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_heothers_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "453");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function heothers_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "453");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_heothers_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "453");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function heothers_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "65");
                $this->db->where("ad.sub_scat_id", "453");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Photography */

             /* Digital SLR Cameras */
            public function busconcount_pdigitalslr(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pdigitalslr(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pdigitalslr(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 454 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pdigitalslr_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "454");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pdigitalslr_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "454");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pdigitalslr_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "454");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pdigitalslr_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "454");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


             /* Point & Shoot Cameras */
            public function busconcount_ppointshoot(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_ppointshoot(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_ppointshoot(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 455 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_ppointshoot_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "455");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function ppointshoot_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "455");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_ppointshoot_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "455");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function ppointshoot_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "455");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


            /* Camcorders */
            public function busconcount_pcamcorders(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND ad_status = 1 AND expire_data >='$data' AND(ad_type = 'business' || ad_type = 'consumer')) AS allbustype,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'business') AS business,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND ad_status = 1 AND expire_data >='$data' AND ad_type = 'consumer') AS consumer");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function sellerneeded_pcamcorders(){
                $date = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND services = 'Seller' AND ad_status = 1 AND expire_data >='$date') AS seller,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND services = 'Needed' AND ad_status = 1 AND expire_data >='$date') AS needed");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function deals_pck_pcamcorders(){
                $data = date("Y-m-d H:i:s");
                $this->db->select("(SELECT COUNT(ud.valid_to) AS aa FROM postad AS ad LEFT JOIN urgent_details AS ud ON ud.ad_id = ad.ad_id AND ud.valid_to >='$data'
                WHERE ad.category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND ad.urgent_package != '0' AND ad.ad_status = 1 AND ad.expire_data >= '$data') AS urgentcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND package_type = '6'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS platinumcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND package_type = '5'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS goldcount,
                (SELECT COUNT(*) FROM postad WHERE category_id = '8' AND sub_cat_id = 66 AND sub_scat_id = 456 AND package_type = '4'  AND urgent_package = '0' AND ad_status = 1 AND expire_data >='$data') AS freecount");
                $rs = $this->db->get();
                return $rs->result();
            }
            public function count_pcamcorders_view(){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "456");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get();

                return $m_res->result();
            }
            public function pcamcorders_view($data){
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "join");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'join');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "456");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                $this->db->group_by(" img.ad_id");
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get("postad AS ad", $data['limit'], $data['start']);

                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }
            public function count_pcamcorders_search(){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->from("postad AS ad");
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "456");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                /*deal title ascending or descending*/
                    if ($dealtitle == 'atoz') {
                        $this->db->order_by("ad.deal_tag","ASC");
                    }
                    else if ($dealtitle == 'ztoa'){
                        $this->db->order_by("ad.deal_tag", "DESC");
                    }
                    /*deal price ascending or descending*/
                    if ($dealprice == 'lowtohigh'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                    }
                    else if ($dealprice == 'hightolow'){
                        $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                    }
                    else{
                        $this->db->order_by("ad.ad_id", "DESC");
                    }
                    $this->db->order_by('ad.approved_on', 'DESC');
                    $m_res = $this->db->get();
                     // echo $this->db->last_query(); exit;
                    if($m_res->num_rows() > 0){
                        return $m_res->result();
                    }
                    else{
                        return array();
                    }
                }
            public function pcamcorders_search($data){
                $search_bustype = $this->session->userdata('search_bustype');
                $dealurgent = $this->session->userdata('dealurgent');
                $dealtitle = $this->session->userdata('dealtitle');
                $dealprice = $this->session->userdata('dealprice');
                $recentdays = $this->session->userdata('recentdays');
                $location = $this->session->userdata('location');
                $seller = $this->session->userdata('seller_deals');
                $this->db->select("ad.*, img.*, COUNT(`img`.`ad_id`) AS img_count, loc.*, lg.*,ud.valid_to AS urg");
                $this->db->select("DATE_FORMAT(STR_TO_DATE(ad.created_on,
                '%d-%m-%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') as dtime", FALSE);
                $this->db->join("ad_img AS img", "img.ad_id = ad.ad_id", "left");
                $this->db->join('location as loc', "loc.ad_id = ad.ad_id", 'left');
                $this->db->join('login as lg', "lg.login_id = ad.login_id", 'join');
                $this->db->join("urgent_details AS ud", "ud.ad_id=ad.ad_id AND ud.valid_to >= '".date("Y-m-d H:i:s")."'", "left");
                $this->db->where("ad.category_id", "8");
                $this->db->where("ad.sub_cat_id", "66");
                $this->db->where("ad.sub_scat_id", "456");
                $this->db->where("ad.ad_status", "1");
                $this->db->where("ad.expire_data >= ", date("Y-m-d H:i:s"));
                
                if (!empty($seller)) {
                    $this->db->where_in('ad.services', $seller);
                }
                if ($search_bustype) {
                    if ($search_bustype == 'business' || $search_bustype == 'consumer') {
                        $this->db->where("ad.ad_type", $search_bustype);
                    }
                }
                /*package search*/
                if (!empty($dealurgent)) {
                    $pcklist = [];
                    if (in_array("0", $dealurgent)) {
                        $this->db->where('ad.urgent_package !=', '0');
                    }
                    else{
                        $this->db->where('ad.urgent_package =', '0');
                    }
                    if (in_array(4, $dealurgent)){
                        array_push($pcklist, 4);
                    }
                    if (in_array(5, $dealurgent)){
                        array_push($pcklist, 5);
                    }
                    if (in_array(6, $dealurgent)){
                        array_push($pcklist, 6);
                    }
                    if (!empty($pcklist)) {
                        $this->db->where_in('ad.package_type', $pcklist);
                    }
                    
                }

                /*deal posted days 24hr/3day/7day/14day/1month */
                if ($recentdays == 'last24hours'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 day"))));
                }
                else if ($recentdays == 'last3days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-3 days"))));
                }
                else if ($recentdays == 'last7days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-7 days"))));
                }
                else if ($recentdays == 'last14days'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-14 days"))));
                }   
                else if ($recentdays == 'last1month'){
                    $this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(ad.`created_on`, '%d-%m-%Y %h:%i:%s')) >=", strtotime(date("d-m-Y H:i:s", strtotime("-1 month"))));
                }

                /*location search*/
                if ($location) {
                    $this->db->where("(loc.loc_name LIKE '$location%' OR loc.loc_name LIKE '%$location' OR loc.loc_name LIKE '%$location%')");
                }


                $this->db->group_by(" img.ad_id");
                    /*deal title ascending or descending*/
                        if ($dealtitle == 'atoz') {
                            $this->db->order_by("ad.deal_tag","ASC");
                        }
                        else if ($dealtitle == 'ztoa'){
                            $this->db->order_by("ad.deal_tag", "DESC");
                        }
                        /*deal price ascending or descending*/
                        if ($dealprice == 'lowtohigh'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "ASC");
                        }
                        else if ($dealprice == 'hightolow'){
                            $this->db->order_by("CAST(`ad`.`price` AS UNSIGNED)", "DESC");
                        }
                        else{
                            $this->db->order_by("ad.ad_id", "DESC");
                        }
                $this->db->order_by('ad.approved_on', 'DESC');
                $m_res = $this->db->get('postad AS ad', $data['limit'], $data['start']);
                 // echo $this->db->last_query(); exit;
                if($m_res->num_rows() > 0){
                    return $m_res->result();
                }
                else{
                    return array();
                }
            }


}
?>