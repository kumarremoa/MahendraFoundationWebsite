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
						$(".fav"+adid+loginid).attr('title','Remove from Pickup Deals');
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
							$(".fav"+adid+loginid).attr('title','Add to Pickup Deals');
						}
					})
					
				}
			});
		});
	</script>
	<?php
		$fav_list = [];
		if (!empty($favourite_list )) {
			foreach ($favourite_list as $favourite_list1) {
				array_push($fav_list, $favourite_list1->ad_id);
			}
		}
		
		 ?>
	<?php
		$viewall_mostads1 = array_chunk($viewall_mostads, 10);
		 foreach ($viewall_mostads1 as $sval1) {
		 foreach ($sval1 as $sval) {
		 	$qry = mysql_query("select ad_id,COUNT(*) AS no_ratings, SUM(rating) AS rating_sum FROM review_rating WHERE ad_id = '$sval->ad_id' AND status = 1 GROUP BY ad_id");
		 	if (mysql_num_rows($qry) > 0) {
		 		$no_ratings = mysql_result($qry,0,'no_ratings');
		 		$rating_sum = mysql_result($qry,0,'rating_sum');
		 	}
		 	else{
		 		$no_ratings = 0;
		 		$rating_sum = 0;
		 	}
		 	if ($no_ratings != 0) {
		 		$avg_per = ($rating_sum/($no_ratings*5))*100;
		 		$total_rating = round(($avg_per/100)*5);
		 	}
		 	else{
		 		$total_rating = 0;
		 	}

		 	$personname = $sval->first_name;
			$city_name = $sval->loc_city;
			/*currency symbol*/ 
			if ($sval->currency == 'pound') {
				$currency = '<span class="pound_sym"></span>';
			}
			else if ($sval->currency == 'euro') {
				$currency = '<span class="euro_sym"></span>';
			}  ?>
	
	<!-- gold+urgent package starts -->
	<?php if (($sval->package_type == 2 || $sval->package_type == 5) && $sval->urgent_package != '0') {  ?>
	<div class="col-md-12">
		<div class="first_list gold_bgcolor">
			<div class="row">
				<div class="col-sm-4">
					<?php if ($sval->urg != '') { ?>
					<div class="featured-badge">
					</div>
					<?php } ?>
					<div class="img-hover view_img">
						<img src="<?php echo base_url(); ?>pictures/<?php echo $sval->img_name; ?>" class="img-responsive" alt="<?php echo $sval->img_name; ?>" title="<?php echo $sval->img_name; ?>">
						<div class="overlay"><a href="<?php echo base_url(); ?>description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
					</div>
					<div class="">
						<div class="price11">
							<span></span><b>
							<img src="<?php echo base_url(); ?>img/icons/thumb.png" class="pull-right" alt="thumb" title="thumb Icon"></b>
						</div>
					</div>
				</div>
				<div class="col-sm-8 middle_text">
					<div class="row">
						<div class="col-sm-8">
							<div class="row">
								<div class="col-xs-10">
									<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
								</div>
								<?php if (in_array($sval->ad_id, $fav_list)) { ?>
								<div class="col-xs-2">
									<div class="add-to-favourite-list pull-right">
										<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
										<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Remove from Pickup Deals"></span>
										<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
										<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
										</a>
									</div>
								</div>
								<?php }else{ ?>
								<div class="col-xs-2">
									<div class="add-to-favourite-list pull-right">
										<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
										<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to Pickup Deals"></span>
										<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
										<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
										</a>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="row">
								<?php if ($total_rating == 0) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 1) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 2) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 3) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 4) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 5) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<div class="col-xs-8">
									<div class="location pull-right ">
										<img src="<?php echo base_url(); ?>img/icons/location_map.png" title="Location" alt="map" class="map_icon">
										<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->location_name; ?>"> <?php echo $city_name; ?></a>
									</div>
								</div>
							</div>
						</div>
						<?php
							if ($sval->ad_type == 'business') {
								if ($sval->bus_logo != '') { ?>
						<div class="col-xs-4 serch_bus_logo">
							<img src="<?php echo base_url(); ?>pictures/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
						</div>
						<?php }
							else{ ?>
						<div class="col-xs-4 serch_bus_logo">
							<img src="<?php echo base_url(); ?>pictures/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
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
									<a href="<?php echo base_url(); ?>description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
								</div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="row">
								<?php if ($sval->category_id != '1') { ?>
								<div class="col-xs-10 col-xs-offset-1 amt_bg">
									<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
								</div>
								<?php } ?>
								<div class="col-xs-12">
									<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row-->
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="post-meta list_view_bottom gold_bgcolor">
					<ul>
						<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
						<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
						<li><i class="fa fa-user"></i><a href="#"><?php echo $personname; ?></a></li>
						<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
						<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
					</ul>
				</div>
			</div>
		</div>
		<hr class="separator">
	</div>
	<?php } ?>
	<!-- gold+urgent package end -->
	<!-- gold package starts -->
	<?php if (($sval->package_type == 2 || $sval->package_type == 5) && $sval->urgent_package == 0) {  ?>
	<div class="col-md-12">
		<div class="first_list gold_bgcolor">
			<div class="row">
				<div class="col-sm-4 ">
					<div class="img-hover view_img">
						<img src="<?php echo base_url(); ?>pictures/<?php echo $sval->img_name; ?>" class="img-responsive" alt="<?php echo $sval->img_name; ?>" title="<?php echo $sval->img_name; ?>">
						<div class="overlay"><a href="<?php echo base_url(); ?>description_view/details/<?php echo $sval->ad_id; ?>"><i class="top_20 fa fa-link"></i></a></div>
					</div>
					<div class="">
						<div class="price11">
							<span></span><b>
							<img src="<?php echo base_url(); ?>img/icons/thumb.png" class="pull-right" alt="thumb" title="thumb Icon"></b>
						</div>
					</div>
				</div>
				<div class="col-sm-8 middle_text">
					<div class="row">
						<div class="col-sm-8">
							<div class="row">
								<div class="col-xs-10">
									<h3 class="list_title"><?php echo substr($sval->deal_tag, 0,20); ?></h3>
								</div>
								<?php if (in_array($sval->ad_id, $fav_list)) { ?>
								<div class="col-xs-2">
									<div class="add-to-favourite-list pull-right">
										<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
										<span class="fav<?php echo $sval->ad_id.$login; ?> active_fav" title="Remove from Pickup Deals"></span>
										<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
										<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
										</a>
									</div>
								</div>
								<?php }else{ ?>
								<div class="col-xs-2">
									<div class="add-to-favourite-list pull-right">
										<a href="javascript:void(0);" id='<?php echo $sval->ad_id; ?>' class="favourite_label">
										<span class="fav<?php echo $sval->ad_id.$login; ?> inactive_fav" title="Add to Pickup Deals"></span>
										<input type="hidden" name="login_id" id="login_id" value="<?php echo @$login; ?>" />
										<input type='hidden' name="login_status" id="login_status" value="<?php echo @$login_status; ?>" />
										</a>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="row">
								<?php if ($total_rating == 0) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 1) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 2) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 3) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 4) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star-o"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if ($total_rating == 5) { ?>
									<div class="col-xs-4">
										<ul class="starts">
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
											<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>
										</ul>
									</div>
								<?php } ?>
								<div class="col-xs-8">
									<div class="location pull-right ">
										<img src="<?php echo base_url(); ?>img/icons/location_map.png" title="Location" alt="map" class="map_icon">
										<a href="javascript:void(0);" class="location loc_map" id="<?php echo $sval->latt.','.$sval->longg; ?>" data-toggle="modal" data-target="#map_location" title="<?php echo $sval->location_name; ?>"> <?php echo $city_name; ?></a>
									</div>
								</div>
							</div>
						</div>
						<?php
							if ($sval->ad_type == 'business') {
								if ($sval->bus_logo != '') { ?>
						<div class="col-xs-4 serch_bus_logo">
							<img src="<?php echo base_url(); ?>pictures/business_logos/<?php echo $sval->bus_logo; ?>" alt="<?php echo $sval->bus_logo; ?>" title="busniess logo" class="img-responsive">
						</div>
						<?php }
							else{ ?>
						<div class="col-xs-4 serch_bus_logo">
							<img src="<?php echo base_url(); ?>pictures/business_logos/trader.png" alt="intel" title="intel logo" class="img-responsive">
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
									<a href="<?php echo base_url(); ?>description_view/details/<?php echo $sval->ad_id; ?>" class="btn_v btn-3 btn-3d fa fa-arrow-right"><span>View Details</span></a>
								</div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="row">
								<?php if ($sval->category_id != '1') { ?>
								<div class="col-xs-10 col-xs-offset-1 amt_bg">
									<h3 class="view_price"><?php echo $currency.number_format($sval->price); ?></h3>
								</div>
								<?php } ?>
								<div class="col-xs-12">
									<a href="#" data-toggle="modal" data-target="#sendnow" class="send_now_show btn_v btn-4 btn-4a fa fa-arrow-right top_4"><span>Send Now</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row-->
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="post-meta list_view_bottom gold_bgcolor">
					<ul>
						<li><i class="fa fa-camera"></i><a href="#"><?php echo $sval->img_count; ?></a></li>
						<li><i class="fa fa-video-camera"></i><a href="#">0</a></li>
						<li><i class="fa fa-user"></i><a href="#"><?php echo $personname; ?></a></li>
						<li><i class="fa fa-clock-o"></i><span><?php echo date("M d, Y H:i:s", strtotime($sval->created_on)); ?></span></li>
						<li><span>Deal ID : <?php echo $sval->ad_prefix.$sval->ad_id; ?></span></li>
					</ul>
				</div>
			</div>
		</div>
		<hr class="separator">
	</div>
	<?php } ?>
	<!-- gold package end -->
	
	
	<?php
		} ?>
	<!-- free Add Start -->
	<div class="col-md-12 col-sm-12 col-xs-12 add_middle" >
		<?php foreach ($public_adview as $publicview) {
			echo $mid_ad = $publicview->mid_ad;
			}
			?>
	</div>
	<!-- free Add ends -->
	<?php } ?>
	<!-- free package ends -->
	<div class=''>
		<div class='col-md-12'>
			<?php echo $paging_links; ?>
		</div>
	</div>