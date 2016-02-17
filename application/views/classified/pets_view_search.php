		<style type="text/css">
		.inactive_fav {
			background: url(<?php echo base_url(); ?>img/icons/favinactive.png);
			width: 31px;
			height: 31px;
			display: block;
			cursor: pointer;
		}
		.active_fav {
			background: url(<?php echo base_url(); ?>img/icons/favactive.png);
			width: 31px;
			height: 31px;
			display: block;
			cursor: pointer !important;
		}
		</style>
		<script type="text/javascript">
		$(function(){
				$(".favourite_label").click(function(){
					var adid = $(this).attr('id');
				var log = $("#login_status").val();
				if (log == 'no') {
					window.location.href = "<?php echo base_url(); ?>login";
				}
				var loginid = $("#login_id").val();
				var val = $(".fav"+adid+loginid).hasClass('active_fav');
				
				/*adding to favourite*/
				if (val == false) {
					$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>description_view/add_favourite",
					data: {
						ad_id: adid, 
						login_id: loginid
					},
					// dataType: "json",
					success: function (data) {
						$(".fav"+adid+loginid).removeClass('inactive_fav');
						$(".fav"+adid+loginid).addClass('active_fav');
					}
				})
				
				}
				else{
					/*deleting from favourite*/
						$.ajax({
						type: "POST",
						url: "<?php echo base_url();?>description_view/remove_favourite",
						data: {
							ad_id: adid, 
							login_id: loginid
						},
						// dataType: "json",
						success: function (data) {
							$(".fav"+adid+loginid).removeClass('active_fav');
							$(".fav"+adid+loginid).addClass('inactive_fav');
						}
					})
					
				}
			});
		});
		</script>
		<?php
		$fav_list = [];
		if (!empty($favourite_list)) {
			foreach ($favourite_list as $favourite_list1) {
				array_push($fav_list, $favourite_list1->ad_id);
			}
		}
		
		 ?>
		<!-- platinum+urgent package start -->
                                    <?php
                                    $pets_result1 = array_chunk($pets_result, 10);
                                     foreach ($pets_result1 as $sval1) {
                                     foreach ($sval1 as $sval) {
                                    	/*currency symbol*/ 
                                    	if ($sval->currency == 'pound') {
                                    		$currency = '£';
                                    	}
                                    	else if ($sval->currency == 'euro') {
                                    		$currency = '€';
                                    	}
                                    	if ($sval->package_type == 'platinum' && $sval->urgent_package != '') { ?>
                                    <div class="col-md-12">
										<div class="first_list">
											<div class="row">
												<div class="col-sm-4">
													<div class="featured-badge">
														<span>Urgent</span>
													</div>
													<div class="xuSlider">
														<ul class="sliders">
															<?php 
															$pic = mysql_query("select * from ad_img WHERE ad_id = '$sval->ad_id'");
															while ($res = mysql_fetch_object($pic)) { ?>
															<li><img src="ad_images/<?php echo $res->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $res->img_name; ?>"></li>
															<?php	
																}
															 ?>
														</ul>
														<div class="direction-nav">
															<a href="javascript:;" class="prev icon-circle-arrow-left icon-4x"><i>Previous</i></a>
															<a href="javascript:;" class="next icon-circle-arrow-right icon-4x"><i>Next</i></a>
														</div>
														<div class="control-nav">
															<li data-id="1"><a href="javascript:;">1</a></li>
															<li data-id="2"><a href="javascript:;">2</a></li>
															<li data-id="3"><a href="javascript:;">3</a></li>
															<li data-id="4"><a href="javascript:;">4</a></li>
															<li data-id="5"><a href="javascript:;">5</a></li>
														</div>	
													</div>
													<div class="">
														<div class="price11">
															<span></span><b>
															<img src="img/icons/crown.png" class="pull-right" alt="Crown" title="Crown Icon"></b>
														</div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?></p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div><!-- End Row-->
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom" >
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">1</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
										<!-- End Item Gallery List View-->
									</div>
									<?php }    ?>
									<!-- platinum+urgent package end -->
									
									<!-- platinum package start-->
									<?php if ($sval->package_type == 'platinum' && $sval->urgent_package == '') {  ?>
                                    <div class="col-md-12">
										<div class="first_list">
											<div class="row">
												<div class="col-sm-4">
													<div class="xuSlider">
														<ul class="sliders">
															<?php 
															$pic = mysql_query("select * from ad_img WHERE ad_id = '$sval->ad_id'");
															while ($res = mysql_fetch_object($pic)) { ?>
															<li><img src="ad_images/<?php echo $res->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $res->img_name; ?>"></li>
															<?php	
																}
															 ?>
														</ul>
														<div class="direction-nav">
															<a href="javascript:;" class="prev icon-circle-arrow-left icon-4x"><i>Previous</i></a>
															<a href="javascript:;" class="next icon-circle-arrow-right icon-4x"><i>Next</i></a>
														</div>
														<div class="control-nav">
															<li data-id="1"><a href="javascript:;">1</a></li>
															<li data-id="2"><a href="javascript:;">2</a></li>
															<li data-id="3"><a href="javascript:;">3</a></li>
															<li data-id="4"><a href="javascript:;">4</a></li>
															<li data-id="5"><a href="javascript:;">5</a></li>
														</div>	
													</div>
													<div class="">
														<div class="price11">
															<span></span><b>
															<img src="img/icons/crown.png" class="pull-right" alt="Crown" title="Crown Icon"></b>
														</div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?></p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom" >
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">1</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
									</div>
									<?php } ?>
									<!-- platinum package end -->

									<!-- gold+urgent package starts -->
									<?php if ($sval->package_type == 'gold' && $sval->urgent_package != '') {  ?>
									<div class="col-md-12">
										<div class="first_list gold_bgcolor">
											<div class="row">
												<div class="col-sm-4">
													<div class="featured-badge">
														<span>Urgent</span>
													</div>
													<div class="img-hover view_img">
														<li><img src="ad_images/<?php echo $sval->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $sval->img_name; ?>"></li>
														<div class="overlay"><a href="description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
													</div>
													<div class="">
														<div class="price11">
															<span></span><b>
															<img src="img/icons/thumb.png" class="pull-right" alt="thumb" title="thumb Icon"></b>
														</div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?></p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div><!-- End Row-->
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom gold_bgcolor">
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
									</div>
									<?php } ?>
									<!-- gold+urgent package end -->
									
									<!-- gold package starts -->
									<?php if ($sval->package_type == 'gold' && $sval->urgent_package == '') {  ?>
									<div class="col-md-12">
										<div class="first_list gold_bgcolor">
											<div class="row">
												<div class="col-sm-4 ">
													<div class="img-hover view_img">
														<li><img src="ad_images/<?php echo $sval->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $sval->img_name; ?>"></li>
														<div class="overlay"><a href="description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
													</div>
													<div class="">
														<div class="price11">
															<span></span><b>
															<img src="img/icons/thumb.png" class="pull-right" alt="thumb" title="thumb Icon"></b>
														</div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?></p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div><!-- End Row-->
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom gold_bgcolor">
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
									</div>
									<?php } ?>
									<!-- gold package end -->
									
									<!-- free+urgent package starts -->
									<?php if ($sval->package_type == 'free' && $sval->urgent_package != '') {  ?>
									<div class="col-md-12">
										<div class="first_list">
											<div class="row">
												<div class="col-sm-4 view_img">
													<div class="featured-badge">
														<span>Urgent</span>
													</div>
													<div class="img-hover">
														<li><img src="ad_images/<?php echo $sval->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $sval->img_name; ?>"></li>
														<div class="overlay"><a href="description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?></p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div><!-- End Row-->
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom" >
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
									</div>
									<?php } ?>
									<!-- free+urgent package ends -->
									
									<!-- free package starts -->
									<?php if ($sval->package_type == 'free' && $sval->urgent_package == '') {  ?>
									<div class="col-md-12">
										<div class="first_list">
											<div class="row">
												<div class="col-sm-4 view_img">
													<div class="img-hover">
														<li><img src="ad_images/<?php echo $sval->img_name; ?>" class="img-responsive" alt="Slider1" title="<?php echo $sval->img_name; ?>"></li>
														<div class="overlay"><a href="description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
													</div>
												</div>
												<div class="col-sm-8 middle_text">
													<div class="row">
														<div class="col-sm-8">
															<div class="row">
																<div class="col-xs-8">
																	<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
																</div>
																<?php if (in_array($sval->ad_id, $fav_list)) { ?>
																	<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php }else{ ?>
																		<div class="col-xs-4">
																	<div class="add-to-favourite-list pull-right">
																		<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
																		<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to favourite"></span>
																		<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
																		<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
																		</a>
																	</div>
																</div>
																<?php } ?>
																
															</div>
															<div class="row">
																<div class="col-xs-4">
																	<ul class="starts">
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star"></i></a></li>
																		<li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
																	</ul>
																</div>
																<div class="col-xs-8">
																	<div class="location pull-right ">
																		<i class="fa fa-map-marker "></i> 
																		<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->loc_name; ?>"> Location</a>
																	</div>
																</div>
															</div>
														</div>
														
														<?php
														if ($sval->ad_type == 'business') {
															if ($sval->bus_logo != '') { ?>
															<div class="col-xs-4 serch_bus_logo">
															<img src="ad_images/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
															</div>
															<?php }
															else{ ?>
																<div class="col-xs-4 serch_bus_logo">
																<img src="ad_images/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
																</div>
														<?php	}
															}
																 ?>
													</div>
													<hr class="separator">
													<div class="row">
														<div class="col-xs-8">
															<div class="row">
																<div class="col-xs-12">
																	<p class=""><?php echo substr(strip_tags($sval->deal_desc), 0,46); ?> </p>
																</div>
																<div class="col-xs-12">
																	<a href="description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
																</div>
															</div>
														</div>
														<div class="col-xs-4">
															<div class="row">
																<div class="col-xs-10 col-xs-offset-1 amt_bg">
																	<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
																</div>
																<div class="col-xs-12">
																	<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div><!-- End Row-->
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="post-meta list_view_bottom" >
													<ul>
														<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
														<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
														<li><i class="fa fa-user"></i><a href="#"><?php echo $log_name; ?></a></li>
														<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
														<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
													</ul>                      
												</div>
											</div>
										</div><hr class="separator">	
									</div>
									<?php
											}
										} ?>
										<!-- free Add Start -->
									<div class="col-md-8 col-md-col-2 middle_ad" >
										<?php foreach ($public_adview as $publicview) {
										  	 echo $mid_ad = $publicview->mid_ad;
										  }
										   ?>
									</div>
									<!-- free Add ends -->
									<?php } ?>
									<!-- free package ends -->