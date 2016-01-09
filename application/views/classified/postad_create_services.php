	<title>365 Deals :: PostaDeal</title>
	
	
<link rel='stylesheet' type='text/css' href='imgupload/free.css' />
<link rel='stylesheet' type='text/css' href='imgupload/freeurgent.css' />
<link rel='stylesheet' type='text/css' href='imgupload/gold.css' />
<link rel='stylesheet' type='text/css' href='imgupload/goldurgent.css' />
<link rel='stylesheet' type='text/css' href='imgupload/platinum.css' />
<link rel='stylesheet' type='text/css' href='imgupload/jquery.fancybox.min.css' />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script src="https://dl.dropboxusercontent.com/u/2241077/jquery.dragbetter.js"></script>
<script src="imgupload/imageupload.js"></script>

<script type="text/javascript">
	/*accept number only*/
	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		}

		$(function(){
			$('#del_img').click(function(){
				$("#file_input").val(""); 
				$("#file").val(""); 
				$('#file_remove').removeClass('error-view');
				$('span#file-error').hide();
				$('img#blah').css('display', 'none');
	             $('#blah').css('border', 'none')
				$('#blah').css('border-radius', 'none');
				$('#del_img').css('display', 'none');
			});
		});

		$(document).ready(function(){
      $('#content').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
    });
</script>

<script type='text/javascript'>
/* Free */

jQuery(document).ready(function($) {

	// Shared callback handler for processing output
	var outputHandlerFunc = function(imgObj) {

		var sizeInKB = function(bytes) {return (typeof bytes == 'number') ? (bytes/1024).toFixed(2) + 'Kb' : bytes;};

		var getThumbnail = function(original, maxWidth, maxHeight, upscale) {
			var canvas = document.createElement("canvas"), width, height;
			if (original.width<maxWidth && original.height<maxHeight && upscale == undefined) {
				width = original.width;
				height = original.height;
			}
			else {
				width = maxWidth;
				height = parseInt(original.height*(maxWidth/original.width));
				if (height>maxHeight) {
					height = maxHeight;
					width = parseInt(original.width*(maxHeight/original.height));
				}
			}
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(original, 0, 0, width, height);
			$(canvas).attr('title','Original size: ' + original.width + 'x' + original.height);
			return canvas;
		}



		$(new Image()).on('load', function(e) {
console.log('imgobj',e)
			var $wrapper = $('<li class="new-item"><div class="list-content"><span class="preview"></span><span class="options"><span class="imagedelete" title="Remove image"></span></span></div></li>').appendTo('#output_free ul');
			$('.imagedelete',$wrapper).one('click',function(e) {

				var f_count = document.getElementById('image_count').value;
			document.getElementById('image_count').value = parseInt(f_count) - 1;

				$wrapper.toggleClass('new-item').addClass('removed-item');
				$wrapper.one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(e) {
			   		$wrapper.remove();
			   	});
			});

			var thumb = getThumbnail(e.target,50,50);
			var $link = $('<a rel="fancybox">').attr({
				target:"_blank",
				href: imgObj.imgSrc
			}).append(thumb).appendTo($('.preview', $wrapper));

		}).attr('src',imgObj.imgSrc);

	}

	$("a[rel=fancybox]").fancybox();

    var fileReaderAvailable = (typeof FileReader !== "undefined");
    var clipBoardAvailable = (window.clipboardData !== false);
    var pasteAvailable = Boolean(clipBoardAvailable & fileReaderAvailable & !eval('/*@cc_on !@*/false'));

	if (fileReaderAvailable) {

		// Enable drop area upload
		$('#dropzone_free').imageUpload({
			errorContainer: $('span','#errormessages_free'),
			trigger: 'dblclick',
			enableCliboardCapture: pasteAvailable,
			onBeforeUpload: function() {$('body').css('background-color','green');console.log('start',Date.now());},
			onAfterUpload: function() {$('body').css('background-color','#eee');console.log('end',Date.now());},
			outputHandler:outputHandlerFunc
		})

		$('#dropzone_free').prev('#free_wrapper').find('#textbox_free').append('<p class="large">Drag and Drop<br>Image File here</p><p class="small">Doubleclick<br>for file requester</p>');
	}
	else {
		$('body').addClass('nofilereader');
	}

	if (!pasteAvailable) {
		$('body').addClass('nopaste');
	}

});

</script>

<script type='text/javascript'>
/* Free + urgent */

jQuery(document).ready(function($) {

	// Shared callback handler for processing output
	var outputHandlerFunc = function(imgObj) {

		var sizeInKB = function(bytes) {return (typeof bytes == 'number') ? (bytes/1024).toFixed(2) + 'Kb' : bytes;};

		var getThumbnail = function(original, maxWidth, maxHeight, upscale) {
			var canvas = document.createElement("canvas"), width, height;
			if (original.width<maxWidth && original.height<maxHeight && upscale == undefined) {
				width = original.width;
				height = original.height;
			}
			else {
				width = maxWidth;
				height = parseInt(original.height*(maxWidth/original.width));
				if (height>maxHeight) {
					height = maxHeight;
					width = parseInt(original.width*(maxHeight/original.height));
				}
			}
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(original, 0, 0, width, height);
			$(canvas).attr('title','Original size: ' + original.width + 'x' + original.height);
			return canvas;
		}



		$(new Image()).on('load', function(e) {
console.log('imgobj',e)
			var $wrapper = $('<li class="new-item"><div class="list-content"><span class="preview"></span><span class="options"><span class="imagedelete" title="Remove image"></span></span></div></li>').appendTo('#output_free_urgent ul');
			$('.imagedelete',$wrapper).one('click',function(e) {

				var f_count = document.getElementById('image_count').value;
			document.getElementById('image_count').value = parseInt(f_count) - 1;

				$wrapper.toggleClass('new-item').addClass('removed-item');
				$wrapper.one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(e) {
			   		$wrapper.remove();
			   	});
			});

			var thumb = getThumbnail(e.target,50,50);
			var $link = $('<a rel="fancybox">').attr({
				target:"_blank",
				href: imgObj.imgSrc
			}).append(thumb).appendTo($('.preview', $wrapper));

		}).attr('src',imgObj.imgSrc);

	}

	$("a[rel=fancybox]").fancybox();

    var fileReaderAvailable = (typeof FileReader !== "undefined");
    var clipBoardAvailable = (window.clipboardData !== false);
    var pasteAvailable = Boolean(clipBoardAvailable & fileReaderAvailable & !eval('/*@cc_on !@*/false'));

	if (fileReaderAvailable) {

		// Enable drop area upload
		$('#dropzone_free_urgent').imageUpload({
			errorContainer: $('span','#errormessages_free_urgent'),
			trigger: 'dblclick',
			enableCliboardCapture: pasteAvailable,
			onBeforeUpload: function() {$('body').css('background-color','green');console.log('start',Date.now());},
			onAfterUpload: function() {$('body').css('background-color','#eee');console.log('end',Date.now());},
			outputHandler:outputHandlerFunc
		})

		$('#dropzone_free_urgent').prev('#free_urgent_wrapper').find('#textbox_free_urgent').append('<p class="large">Drag and Drop<br>Image File here</p><p class="small">Doubleclick<br>for file requester</p>');
	}
	else {
		$('body').addClass('nofilereader');
	}

	if (!pasteAvailable) {
		$('body').addClass('nopaste');
	}

});

</script>

<script type='text/javascript'>
/* Gold */

jQuery(document).ready(function($) {

	// Shared callback handler for processing output
	var outputHandlerFunc = function(imgObj) {

		var sizeInKB = function(bytes) {return (typeof bytes == 'number') ? (bytes/1024).toFixed(2) + 'Kb' : bytes;};

		var getThumbnail = function(original, maxWidth, maxHeight, upscale) {
			var canvas = document.createElement("canvas"), width, height;
			if (original.width<maxWidth && original.height<maxHeight && upscale == undefined) {
				width = original.width;
				height = original.height;
			}
			else {
				width = maxWidth;
				height = parseInt(original.height*(maxWidth/original.width));
				if (height>maxHeight) {
					height = maxHeight;
					width = parseInt(original.width*(maxHeight/original.height));
				}
			}
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(original, 0, 0, width, height);
			$(canvas).attr('title','Original size: ' + original.width + 'x' + original.height);
			return canvas;
		}



		$(new Image()).on('load', function(e) {
console.log('imgobj',e)
			var $wrapper = $('<li class="new-item"><div class="list-content"><span class="preview"></span><span class="options"><span class="imagedelete" title="Remove image"></span></span></div></li>').appendTo('#output_gold ul');
			$('.imagedelete',$wrapper).one('click',function(e) {

				var f_count = document.getElementById('image_count').value;
				document.getElementById('image_count').value = parseInt(f_count) - 1;

				$wrapper.toggleClass('new-item').addClass('removed-item');
				$wrapper.one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(e) {
			   		$wrapper.remove();
			   	});
			});

			var thumb = getThumbnail(e.target,50,50);
			var $link = $('<a rel="fancybox">').attr({
				target:"_blank",
				href: imgObj.imgSrc
			}).append(thumb).appendTo($('.preview', $wrapper));

		}).attr('src',imgObj.imgSrc);

	}

	$("a[rel=fancybox]").fancybox();

    var fileReaderAvailable = (typeof FileReader !== "undefined");
    var clipBoardAvailable = (window.clipboardData !== false);
    var pasteAvailable = Boolean(clipBoardAvailable & fileReaderAvailable & !eval('/*@cc_on !@*/false'));

	if (fileReaderAvailable) {

		// Enable drop area upload
		$('#dropzone_gold').imageUpload({
			errorContainer: $('span','#errormessages_gold'),
			trigger: 'dblclick',
			enableCliboardCapture: pasteAvailable,
			onBeforeUpload: function() {$('body').css('background-color','green');console.log('start',Date.now());},
			onAfterUpload: function() {$('body').css('background-color','#eee');console.log('end',Date.now());},
			outputHandler:outputHandlerFunc
		})

		$('#dropzone_gold').prev('#gold_wrapper').find('#textbox_gold').append('<p class="large">Drag and Drop<br>Image File here</p><p class="small">Doubleclick<br>for file requester</p>');
	}
	else {
		$('body').addClass('nofilereader');
	}

	if (!pasteAvailable) {
		$('body').addClass('nopaste');
	}

});

</script>

<script type='text/javascript'>
/* Gold + urgent */

jQuery(document).ready(function($) {

	// Shared callback handler for processing output
	var outputHandlerFunc = function(imgObj) {

		var sizeInKB = function(bytes) {return (typeof bytes == 'number') ? (bytes/1024).toFixed(2) + 'Kb' : bytes;};

		var getThumbnail = function(original, maxWidth, maxHeight, upscale) {
			var canvas = document.createElement("canvas"), width, height;
			if (original.width<maxWidth && original.height<maxHeight && upscale == undefined) {
				width = original.width;
				height = original.height;
			}
			else {
				width = maxWidth;
				height = parseInt(original.height*(maxWidth/original.width));
				if (height>maxHeight) {
					height = maxHeight;
					width = parseInt(original.width*(maxHeight/original.height));
				}
			}
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(original, 0, 0, width, height);
			$(canvas).attr('title','Original size: ' + original.width + 'x' + original.height);
			return canvas;
		}



		$(new Image()).on('load', function(e) {
console.log('imgobj',e)
			var $wrapper = $('<li class="new-item"><div class="list-content"><span class="preview"></span><span class="options"><span class="imagedelete" title="Remove image"></span></span></div></li>').appendTo('#output_gold_urgent ul');
			$('.imagedelete',$wrapper).one('click',function(e) {

				var f_count = document.getElementById('image_count').value;
			document.getElementById('image_count').value = parseInt(f_count) - 1;

				$wrapper.toggleClass('new-item').addClass('removed-item');
				$wrapper.one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(e) {
			   		$wrapper.remove();
			   	});
			});

			var thumb = getThumbnail(e.target,50,50);
			var $link = $('<a rel="fancybox">').attr({
				target:"_blank",
				href: imgObj.imgSrc
			}).append(thumb).appendTo($('.preview', $wrapper));

		}).attr('src',imgObj.imgSrc);

	}

	$("a[rel=fancybox]").fancybox();

    var fileReaderAvailable = (typeof FileReader !== "undefined");
    var clipBoardAvailable = (window.clipboardData !== false);
    var pasteAvailable = Boolean(clipBoardAvailable & fileReaderAvailable & !eval('/*@cc_on !@*/false'));

	if (fileReaderAvailable) {

		// Enable drop area upload
		$('#dropzone_gold_urgent').imageUpload({
			errorContainer: $('span','#errormessages_gold_urgent'),
			trigger: 'dblclick',
			enableCliboardCapture: pasteAvailable,
			onBeforeUpload: function() {$('body').css('background-color','green');console.log('start',Date.now());},
			onAfterUpload: function() {$('body').css('background-color','#eee');console.log('end',Date.now());},
			outputHandler:outputHandlerFunc
		})

		$('#dropzone_gold_urgent').prev('#gold_urgent_wrapper').find('#textbox_gold_urgent').append('<p class="large">Drag and Drop<br>Image File here</p><p class="small">Doubleclick<br>for file requester</p>');
	}
	else {
		$('body').addClass('nofilereader');
	}

	if (!pasteAvailable) {
		$('body').addClass('nopaste');
	}

});

</script>

<script type='text/javascript'>

jQuery(document).ready(function($) {

	// Shared callback handler for processing output
	var outputHandlerFunc = function(imgObj) {

		var sizeInKB = function(bytes) {return (typeof bytes == 'number') ? (bytes/1024).toFixed(2) + 'Kb' : bytes;};

		var getThumbnail = function(original, maxWidth, maxHeight, upscale) {
			var canvas = document.createElement("canvas"), width, height;
			if (original.width<maxWidth && original.height<maxHeight && upscale == undefined) {
				width = original.width;
				height = original.height;
			}
			else {
				width = maxWidth;
				height = parseInt(original.height*(maxWidth/original.width));
				if (height>maxHeight) {
					height = maxHeight;
					width = parseInt(original.width*(maxHeight/original.height));
				}
			}
			canvas.width = width;
			canvas.height = height;
			canvas.getContext("2d").drawImage(original, 0, 0, width, height);
			$(canvas).attr('title','Original size: ' + original.width + 'x' + original.height);
			return canvas;
		}



		$(new Image()).on('load', function(e) {
		console.log('imgobj',e)
			var $wrapper = $('<li class="new-item"><div class="list-content"><span class="preview"></span><span class="options"><span class="imagedelete" title="Remove image"></span></span></div></li>').appendTo('#output_platinum ul');
			$('.imagedelete',$wrapper).one('click',function(e) {

				var f_count = document.getElementById('image_count').value;
			document.getElementById('image_count').value = parseInt(f_count) - 1;

				$wrapper.toggleClass('new-item').addClass('removed-item');
				$wrapper.one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(e) {
			   		$wrapper.remove();
			   	});
			});

			var thumb = getThumbnail(e.target,50,50);
			var $link = $('<a rel="fancybox">').attr({
				target:"_blank",
				href: imgObj.imgSrc
			}).append(thumb).appendTo($('.preview', $wrapper));

		}).attr('src',imgObj.imgSrc);

	}

	$("a[rel=fancybox]").fancybox();

    var fileReaderAvailable = (typeof FileReader !== "undefined");
    var clipBoardAvailable = (window.clipboardData !== false);
    var pasteAvailable = Boolean(clipBoardAvailable & fileReaderAvailable & !eval('/*@cc_on !@*/false'));

	if (fileReaderAvailable) {

		// Enable drop area upload
		$('#dropzone_platinum').imageUpload({
			errorContainer: $('span','#errormessages_platinum'),
			trigger: 'dblclick',
			enableCliboardCapture: pasteAvailable,
			onBeforeUpload: function() {$('body').css('background-color','green');console.log('start',Date.now());},
			onAfterUpload: function() {$('body').css('background-color','#eee');console.log('end',Date.now());},
			outputHandler:outputHandlerFunc
		})

		$('#dropzone_platinum').prev('#platinum_wrapper').find('#textbox_platinum').append('<p class="large">Drag and Drop<br>Image File here</p><p class="small">Doubleclick<br>for file requester</p>');
	}
	else {
		$('body').addClass('nofilereader');
	}

	if (!pasteAvailable) {
		$('body').addClass('nopaste');
	}

});

</script>

	<style>
		.section-title-01{
			height: 273px;
			background-color: #262626;
			text-align: center;
			position: relative;
			width: 100%;
			overflow: hidden;
		}
		ul#free,
		ul#free li {
			/* Setting a common base */
			margin: 0;
			padding: 0;
		}

		ul#free li {
			display: inline-block;
			vertical-align: top;
			margin-left: 10px;
		}
	</style>
	<link rel="stylesheet" href="j-folder/css/demo.css">
	<link rel="stylesheet" href="j-folder/css/font-awesome.min.css">
	<link rel="stylesheet" href="j-folder/css/j-forms.css">

	        <!-- tinymce editor 

        <script type="text/javascript" src="js/nicEdit.js"></script>
		<script type="text/javascript">
			bkLib.onDomLoaded(function() { new nicEditor().panelInstance('dealdescription'); });
		</script>-->

		 <link rel="stylesheet" href="js/jquery.cleditor.css" />
    
		<!-- google map by postal code -->		
		 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJPl18cl1woQc2OYOkugwisxSdaqEX3qw"></script>
		<script type="text/javascript">

          function getPosition(callback) {
            var geocoder = new google.maps.Geocoder();
            var postcode = document.getElementById("postalcode").value;

            geocoder.geocode({'address': postcode}, function(results, status) 
            {   
              if (status == google.maps.GeocoderStatus.OK) 
              {
                callback({
                  latt: results[0].geometry.location.lat(),
                  long: results[0].geometry.location.lng()
                });
              }
            });
          }

          function setup_map(latitude, longitude) { 
            var _position = { lat: latitude, lng: longitude};
            
            var mapOptions = {
              zoom: 12,
              center: _position
            }

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var marker = new google.maps.Marker({
              position: mapOptions.center,
              map: map
            });
          }
		  
		  function address(latt, long1){
		  $.ajax({ url:'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latt+','+long1+'&sensor=true',
         success: function(data){
			 $('#location').val(data.results[0].formatted_address);
			 
             /*or you could iterate the components for only the city and state*/
         }
			});
			}

          window.onload = function() {
            setup_map(51.5073509, -0.12775829999998223);

            document.getElementById("postalcode").onchange = function() {
              getPosition(function(position){
                setup_map(position.latt, position.long);
				address(position.latt, position.long);
              });
            }
          }
      </script> 

 	<!-- Section Title-->    
            <div class="section-title-01">
                <!-- Parallax Background -->
                <div class="bg_parallax image_02_parallax"></div>
                <!-- Parallax Background -->
			</div>   
            <!-- End Section Title-->
    <section class="content-central">
		<!-- Shadow Semiboxed -->
		<div class="semiboxshadow text-center">
			<img src="img/img-theme/shp.png" class="img-responsive" alt="">
		</div>
		
		<!-- content info - Blog-->
		<div class="content_info">
			<div class="paddings-mini">
				<!-- content-->
				<div class="container">
					<div class="row">
						<div class="wrapper wrapper-640" style="padding-top: 0px;">

							<form action="http://lazy-coding.com/j-forms-advanced/forms/order_multistep_with_steps/j-folder/php/demo.php" method="post" class="j-forms j-multistep tooltip-hover" id="j-forms" enctype="multipart/form-data" novalidate>

								<div class="header">
									<a href="postad" class="pull-left post_ad_back"><i class="fa fa-mail-reply-all fa-3x"></i></a><p>Post a Deal</p>
								</div>
								 <!--end /.header-->

								<div class="content">
									<div class="top-head">
										<div class="j-row">
											<div class="col-sm-8 pad_bottm">
												<ul class="social-team pull-left">
													<li><b><?php echo ucfirst(@$cat); ?></b> /</li>
													<li><b><?php echo ucfirst(@$sub_name); ?></b> /</li>
													<li><b><?php echo ucfirst(@$sub_sub_name); ?></b></li>
												</ul>                 
											</div>
											<div class="col-sm-4 pad_bottm">
												<ul class="social-team pull-left">
													<li><a href="" data-toggle="modal" data-target="#Services" ><b>Change Category</b></a></li>
												</ul>                 
											</div>
										</div> 
									</div>

									<!-- start steps -->
									<div class="j-row">
										<div class="span4 step">
											<div class="steps">
												<span>Step 1:</span>
												<p>1st Screen</p>
											</div>
										</div>
										<div class="span4 step">
											<div class="steps">
												<span>Step 2:</span>
												<p>Packages</p>
											</div>
										</div>
										
										<div class="span4 step">
											<div class="steps">
												<span>Step 3:</span>
												<p>Terms & Conditions</p>
											</div>
										</div>
									</div>
									<!-- end steps -->

									<fieldset>

										<div class="divider gap-bottom-25"></div>

										<div class="post_deal_bor">	
											
											<div class="j-row">
												<div class="span5 unit">
													<div class="unit check logic-block-radio">
														<div class="inline-group">
															<label class="radio">
																<input type="radio" name="checkbox_toggle" id="next-step-radio" class='bus_consumer' value="Yes">
																<i></i>Business 
																<sup data-toggle="tooltip" title="" data-original-title="Business">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<label class="radio">
																<input type="radio" name="checkbox_toggle" class='bus_consumer'  value="No">
																<i></i>Consumer 
																<sup data-toggle="tooltip" title="" data-original-title="Postal Code">
																	<img src="img/icons/i.png">
																</sup>
															</label>
														</div>
													</div>
												</div>
											</div>
											<!-- start Postal Code -->
											<div class="j-row">
												<div class="span6 unit">
													<label class="label">Postal Code 
														<sup data-toggle="tooltip" title="" data-original-title="Postal Code">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="email">
															<i class="fa fa-bookmark-o"></i>
														</label>
														<input type="text" id="postalcode" name="postalcode" placeholder="(e.g. EH14 4AB)" >
													</div>
												</div>
												<div class="span6 unit">
													<label class="label">Location 
														<sup data-toggle="tooltip" title="" data-original-title="Location">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="phone">
															<i class="fa fa-building-o"></i>
														</label>
														<!-- <input type="text" id="area" name="area" placeholder="Enter Area"> -->
														<input id="location" name='location' readonly type="text" placeholder="Type in an address" size="90" />
													</div>
												</div>
											</div>
											<!-- end  Area -->
											<div class="j-row">
												<div class="span2 unit">
													
												</div>
												<div class="span8 unit">
													<!--  Map here -->
													 <!-- <div class="map_canvas"></div> -->
													 <div id="map"></div>
												</div>
												<div class="span2 unit">
													
												</div>
											</div>
										
										</div>
										
										<div class="post_deal_bor top_10" id='bus_logo' style='display:none;margin-top: 20px;'>	
											<div class="j-row"  > 
												<div class="span6 unit">
													<label class="label">Business Logo 
														<sup data-toggle="tooltip" title="" data-original-title="Business Logo ">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="unit">
														<label id='file_remove' class="input append-big-btn">
															<div class="file-button">
																Browse
																<input type="file" name="file" id='file' onchange="document.getElementById('file_input').value = this.value; fileupload(this);">
															</div>
															<input type="text" id="file_input" readonly="" placeholder="no file selected">
															<span class="hint">Only: jpg / png  Size: less 1 Mb</span>
														</label>
													</div>
												</div>
												<div class="span4 unit" class='img_hide'>
													<img id="blah" src="#" alt=''/>
												</div>
												<div class="span2 unit" class='del_img'>
												<a href='javascript:void(0);' id="del_img" style='display:none;'><img src="ad_images/delete.png" alt=''/></a>
												</div>
											</div>
										</div>
										<!-- end Business Logo -->
										
										<div class="post_deal_bor top_10" style='margin-top: 20px;'>	
											<div class="j-row">
												<div class="span12 unit">
													<div class="unit check logic-block-radio">
														<div class="inline-group">
															<label class="radio">
																<input type="radio" name="checkbox_services" id="next-step-radio" value="Yes">
																<i></i>Service Provider
																	<sup data-toggle="tooltip" title="" data-original-title="Service Provider">
																		<img src="img/icons/i.png">
																	</sup>
															</label>
															<label class="radio">
																<input type="radio" name="checkbox_services"  value="No">
																<i></i>Service needed
																<sup data-toggle="tooltip" title="" data-original-title="Service needed">
																	<img src="img/icons/i.png">
																</sup>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="j-row">
												<div class="span6 unit"><!-- start Deal Tag -->
													<label class="label">Deal Tag / Caption 
														<sup data-toggle="tooltip" title="" data-original-title="Postal">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="dealtag">
															<img src="j-folder/img/dealtag.png">
														</label>
														<input type="text" id="dealtag" name="dealtag" placeholder="Enter Deal Tag">
													</div>
												</div><!-- end Deal Tag -->
												<div class="span6 unit"><!-- start Deal Tag -->
													<label class="label">Type of Service 
														<sup data-toggle="tooltip" title="" data-original-title="Type of Service">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="dealtag">
															<img src="j-folder/img/type.png">
														</label>
														<input type="text" id="typeservice" name="typeservice" placeholder="Type of Service">
													</div>
												</div>
											</div>
											
											<div class="j-row">
												<div class="span12 unit"><!-- start Deal Description -->
													<label class="label">Deal Description 
														<sup data-toggle="tooltip" title="" data-original-title="Deal Description ">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<textarea type="text" id="dealdescription" name="dealdescription" cols="40" placeholder="Enter Deal Description"></textarea>
														<input type='hidden' name='text_hide' id='text_hide' value='' />
													</div>
												</div><!-- end Deal Description -->
											</div>
											
											<div class="j-row">
												<div class="span6 unit">
													<label class="label">Price 
														<sup data-toggle="tooltip" title="" data-original-title="Price">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="unit check logic-block-radio">
														<div class="inline-group">
															<label class="radio">
																<input type="radio" name="checkbox_toggle1" id="next-step-radio" value="Yes">
																<i></i>£ (Pound) 
															</label>
															<label class="radio">
																<input type="radio" name="checkbox_toggle1"  value="No">
																<i></i> € (Euro)
															</label>
														</div>
													</div>
												</div>
												<div class="span6 unit">
													<div class="j-row">
														<div class="span6 unit top_20">
															<div class="input">
																<label class="icon-right" for="dealtag">
																	<img src="j-folder/img/price.png">
																</label>
																<input type="text" id="priceamount" name="priceamount" placeholder="Enter Amount" onkeypress="return isNumber(event)">
															</div>
														</div>
														<div class="span6 unit"><!-- start Deal Tag -->
															<label class="label">Price Type 
																<sup data-toggle="tooltip" title="" data-original-title="Price Type ">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<label class="input select">
																<select name="price_type">
																	<option value="none" selected disabled="">Select type</option>
																	<option value="Negotiable">Negotiable</option>
																	<option value="Fixed">Fixed</option>
																</select>
																<i></i>
															</label>
														</div>
													</div><!-- end service -->
												
												</div>
											</div>
										</div>
									</fieldset>

									<fieldset>

										<div class="divider gap-bottom-25"></div>

										<!-- start name -->
										<div class="j-row">
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-2">
														<div class="prince">
															Free
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Recent Ads Section 
																	<a href="img/free.png" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Low on Views</li>
															<li><i class="fa fa-arrow-right"></i> Only 3-5 photos</li>
															<li>
																<center>
																	<div class="free_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0</h3>
																	</div>
																</center>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" name="candles" id='free_package' value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														
														<div class="free_hide" style="display:none;padding-bottom:20px;">
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Auto Boosted Once in 3 days (till 30 days) - £0.99
															</label>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Marked as URGENT – £0.99 for 7 days (for e.g.) 
															</label>
															<ul class="list-styles top_10">
																<li><i class="fa fa-arrow-right"></i> Visibility 
																	<ul>
																		<li><i class="fa fa-arrow-right"></i> (Free + Urgent) – Also labelled as  CRUCIAL AD
																		<a href="" class="fancybox">Example</a></li>
																		<li><i class="fa fa-arrow-right"></i> Displayed under Categories to Sub Category  
																		<a href="" class="fancybox">Example</a></li>
																	</ul>
																</li>
															</ul>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Top Banner Ad –  £0.99 for 30 days (for e.g.)
															</label>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Vertical Banner Ad – £5.99 for 30 days (for e.g.)

															</label>
														</div>
														
														
														<a href="#four" data-toggle="tab" id='free_urgent' class=" btn btn-primary multi-next-btn">Select Package</a>
													</div>
													
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>

											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-1">
														<div class="prince">
															Gold
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Top Banner Ad(MOST VALUED Ad) 
																	<a href="img/gold.png" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under Categories & Sub Category 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 3x more Views</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" id='gold_package' name="candles" value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														<ul class="list-styles gold_hide" style="display:none;">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Marked as URGENT
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Top Banner Ad (MOST VALUED Ad)
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under Categories & Sub Categories
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Also Displayed under other Sub Categories under Main Category 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 7x more Views</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£1.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														<a href="#four" data-toggle="tab" id='gold_urgent' class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
											
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-3">
														<div class="prince">
															Platinum
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Marked as SIGNIFICANT Ad on every page under selected category
																	<a href="img/platinum.png" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Vertical Banner Ad with full description of Selling Product with Reply option
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed as Marquee (breaking news) on the home page
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under all categories in right columns 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 10x Views + High on Returns</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="platinum_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£5.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														
														
														<a href="#four" data-toggle="tab" id='platinum_urgent' class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
										</div>
										
										<!--Business to Customer Start-->
										<div class="j-row top_20" style="display:none">
											<h3 >Business to Customer</h3>
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-2">
														<div class="prince">
															Free
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info" style="min-height: 378px;">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> N.A.</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>

											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-1">
														<div class="prince">
															Gold
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0.99</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £11.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £20.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" id="bu_cust_urpackage" name="candles" value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														<ul class="list-styles b_customer_hide" style="display:none">
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£1.98</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £20.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £39.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
											
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-3">
														<div class="prince">
															Platinum
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info" style="min-height: 378px;">
														<ul class="list-styles">
															<li>
																<center>
																	<div class="platinum_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£5.99</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £59.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £99.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
											</div>
										</div>
										<!-- Business to Customer End-->
										
										<!-- Pets, E-Zone, Clothing & Lifestyle Start-->
										<div class="j-row top_20" style="display:none">
											<h3>Pets, E-Zone, Clothing & Lifestyle Start</h3>
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-2">
														<div class="prince">
															Free
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Recent Ads Section 
																	<a href="img/free.png" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Low on Views</li>
															<li><i class="fa fa-arrow-right"></i> Only 2 photos</li>
															<li>
																<center>
																	<div class="free_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0</h3>
																	</div>
																</center>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" name="candles" id='pec_free_package' value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														<div class="pec_free_hide" style="display:none;padding-bottom:20px;">
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Auto Boosted Once in 3 days (till 30 days) - £0.99
															</label>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Marked as URGENT – £0.99 for 7 days (for e.g.) 
															</label>
															<ul class="list-styles top_10">
																<li><i class="fa fa-arrow-right"></i> Visibility 
																	<ul>
																		<li><i class="fa fa-arrow-right"></i> (Free + Urgent) – Also labelled as  CRUCIAL AD
																		<a href="" class="fancybox">Example</a></li>
																		<li><i class="fa fa-arrow-right"></i> Displayed under Categories to Sub Category  
																		<a href="" class="fancybox">Example</a></li>
																	</ul>
																</li>
															</ul>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Top Banner Ad –  £0.99 for 30 days (for e.g.)
															</label>
															<label class="radio line_height">
																<input type="radio" name="radio" checked="">
																<i></i>
																Vertical Banner Ad – £5.99 for 30 days (for e.g.)

															</label>
														</div>
														<a href="#four" data-toggle="tab" id='free_urgent' class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>

											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-1">
														<div class="prince">
															Gold
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Top Banner Ad(MOST VALUED Ad) 
																	<a href="img/gold.png" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under Categories & Sub Category 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 3x more Views</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" id='pec_goldur_package' name="candles" value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														<ul class="list-styles pec_goldur_hide" style="display:none">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Marked as URGENT
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Top Banner Ad (MOST VALUED Ad)
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under Categories & Sub Categories
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Also Displayed under other Sub Categories under Main Category 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 7x more Views</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£1.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														<a href="#four" data-toggle="tab" id='gold_urgent' class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
											
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-3">
														<div class="prince">
															Platinum
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Validity : 30 days</li>
															<li><i class="fa fa-arrow-right"></i> Visibility 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> Marked as SIGNIFICANT Ad on every page under selected category
																	<a href="img/platinum.png" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Vertical Banner Ad with full description of Selling Product with Reply option
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed as Marquee (breaking news) on the home page
																	<a href="" class="fancybox">Example</a></li>
																	<li><i class="fa fa-arrow-right"></i> Displayed under all categories in right columns 
																	<a href="" class="fancybox">Example</a></li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> 10x Views + High on Returns</li>
															<li><i class="fa fa-arrow-right"></i> Auto Boosted once in 3 days</li>
															<li><i class="fa fa-arrow-right"></i> Unlimited Photos</li>
															<li>
																<center>
																	<div class="platinum_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£5.99</h3>
																	</div>
																</center>
															</li>
														</ul>
														<a href="#four" data-toggle="tab" id='platinum_urgent' class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
										</div>
										<!-- Pets, E-Zone, Clothing & Lifestyle End-->
										
										<!--Business to Customer Start-->
										<div class="j-row top_20" style="display:none">
											<h3 >Business to Customer</h3>
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-2">
														<div class="prince">
															Free
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info" style="min-height: 378px;">
														<ul class="list-styles">
															<li><i class="fa fa-arrow-right"></i> Same Features as Customer to Customer free ads</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>

											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-1">
														<div class="prince">
															Gold
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info">
														<ul class="list-styles">
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£0.99</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £11.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £20.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<label class="checkbox">
															<input type="checkbox" id="bu_cust_urpackage1" name="candles" value="candles-5$" data-price="5">
															<i></i>
															is Urgent
														</label>
														<ul class="list-styles b_customer_hide1" style="display:none">
															<li>
																<center>
																	<div class="gold_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£1.98</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £20.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £39.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
												<!-- End promotion-box-->
											</div>
											
											<div class="span4">
												<!-- promotion-box-->
												<div class="promotion-box">
													<div class="promotion-box-center color-3">
														<div class="prince">
															Platinum
														</div>
													</div>
													<!-- End promotion-box-center-->

													<!-- promotion-box-info-->
													<div class="promotion-box-info" style="min-height: 378px;">
														<ul class="list-styles">
															<li>
																<center>
																	<div class="platinum_bg text_center">
																		<h3 style="color:white;padding-top:10px;">£5.99</h3>
																	</div>
																</center>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 1 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	15 ad - £59.99(Validity: 3 months) </li>
																</ul>
															</li>
															<li><i class="fa fa-arrow-right"></i> Option 2 : 
																<ul>
																	<li><i class="fa fa-arrow-right"></i> 
																	30 ad - £99.99 (Validity: 6 months)</li>
																</ul>
															</li>
														</ul>
														<a href="#four" data-toggle="tab"  class="btn btn-primary multi-next-btn">Select Package</a>
													</div>
													<!-- End promotion-box-info-->
												</div>
											</div>
										</div>
										<!-- Business to Customer End-->
										
										
									</fieldset>
									
									<fieldset>

										<div class="divider gap-bottom-25"></div>

											<!-- free__pck Start -->
											<div class="j-row free_pck" style='display: none;'>
												<div class="span4 unit">
													<div style="width:240px;">
														<div id="dropzone-wrapper">
															<div id="free_wrapper"><div id=textbox_free></div></div>
															<div id="dropzone_free"></div>
														</div>
														<div id="errormessages_free"><span style="display: none;"></span></div>

														<div id="overlay_free"></div>
													</div>
												</div>
												<div class="span8 unit">
													<div class="j-row">
														<div class="span12">
															<div>
																<h3>Upload Images ( 3-5 images ) :</h3>
																<div id="output_free"><ul id="free"></ul></div>
															</div>
															<div style="clear:both;"></div>
														</div>
													</div>
												</div>
											</div>
											<!-- free_pck End -->
												
											<!-- free_urgent_pck Start -->
											<div class="j-row free_urgent_pck" style='display: none;'>
												<div class="span4 unit">
													<div style="width:240px;">
														<div id="dropzone-wrapper">
															<div id="free_urgent_wrapper"><div id=textbox_free_urgent></div></div>
															<div id="dropzone_free_urgent"></div>
														</div>
														<div id="errormessages_free_urgent"><span style="display: none;"></span></div>

														<div id="overlay_free_urgent"></div>
													</div>
												</div>
												<div class="span8 unit">
													<div class="j-row">
														<div class="span12">
															<div>
																<h3>Upload Images ( 9 images ) :</h3>
																<div id="output_free_urgent"><ul id="free"></ul></div>
															</div>
															<div style="clear:both;"></div>
														</div>
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Video Link 
														<sup data-toggle="tooltip" title="" data-original-title="Video Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="freeurgent_video" name="freeurgent_video" placeholder="">
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Website Link 
														<sup data-toggle="tooltip" title="" data-original-title="Website Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="freeurgent_weblink" name="freeurgent_weblink" placeholder="">
													</div>
												</div>
											</div>
											<!-- free_urgent_pck End -->
												
											<!-- Gold package Start -->
											<div class="j-row gold_pck" style='display: none;'>
												<div class="span4 unit">
													<div style="width:240px;">
														<div id="dropzone-wrapper">
															<div id="gold_wrapper"><div id=textbox_gold></div></div>
															<div id="dropzone_gold"></div>
														</div>
														<div id="errormessages_gold"><span style="display: none;"></span></div>

														<div id="overlay_gold"></div>
													</div>
												</div>
												<div class="span8 unit">
													<div class="j-row">
														<div class="span12">
															<div>
																<h3>Upload Images ( 9 images ) :</h3>
																<div id="output_gold"><ul id="free"></ul></div>
															</div>
															<div style="clear:both;"></div>
														</div>
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Video Link 
														<sup data-toggle="tooltip" title="" data-original-title="Video Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="gold_videolink" name="gold_videolink" placeholder="">
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Website Link 
														<sup data-toggle="tooltip" title="" data-original-title="Website Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="gold_weblink" name="gold_weblink" placeholder="">
													</div>
												</div>
											</div>
											<!--Gold package End -->
												
											<!-- gold_urgent_pck Start -->
											<div class="j-row gold_urgent_pck" style='display: none;'>
												<div class="span4 unit">
													<div style="width:240px;">
														<div id="dropzone-wrapper">
															<div id="gold_urgent_wrapper"><div id=textbox_gold_urgent></div></div>
															<div id="dropzone_gold_urgent"></div>
														</div>
														<div id="errormessages_gold_urgent"><span style="display: none;"></span></div>

														<div id="overlay_gold_urgent"></div>
													</div>
												</div>
												<div class="span8 unit">
													<div class="j-row">
														<div class="span12">
															<div>
																<h3>Upload Images ( 12 images ) :</h3>
																<div id="output_gold_urgent"><ul id="free"></ul></div>
															</div>
															<div style="clear:both;"></div>
														</div>
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Video Link 
														<sup data-toggle="tooltip" title="" data-original-title="Video Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="goldurgent_video" name="goldurgent_video" placeholder="">
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Website Link 
														<sup data-toggle="tooltip" title="" data-original-title="Website Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="goldurgent_weblink" name="goldurgent_weblink" placeholder="">
													</div>
												</div>
											</div>
											<!-- gold_urgent_pck End -->	
													
											<!-- platinum package Start -->
											<div class="j-row platinum_pck" style='display: none;'>
												<div class="span4 unit">
													<div style="width:240px;">
														<div id="dropzone-wrapper">
															<div id="platinum_wrapper"><div id=textbox_platinum></div></div>
															<div id="dropzone_platinum"></div>
														</div>
														<div id="errormessages_platinum"><span style="display: none;"></span></div>

														<div id="overlay_platinum"></div>
													</div>
												</div>
												<div class="span8 unit">
													<div class="j-row">
														<div class="span12">
															<div>
																<h3>Upload Images ( 15 images ) :</h3>
																<div id="output_platinum"><ul id="free"></ul></div>
															</div>
															<div style="clear:both;"></div>
														</div>
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Video Upload 
														<sup data-toggle="tooltip" title="" data-original-title="Video Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<input type="file">
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Website Link 
														<sup data-toggle="tooltip" title="" data-original-title="Website Link">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="platinum_weblink" name="platinum_weblink" placeholder="">
													</div>
												</div>
												<div class="span12 unit">
													<label class="label">Marquee Title 
														<sup data-toggle="tooltip" title="" data-original-title="Marquee Title">
															<img src="img/icons/i.png">
														</sup>
													</label>
													<div class="input">
														<label class="icon-right" for="Video">
															<i class="fa fa-briefcase"></i>
														</label>
														<input type="text" id="marquee_title" name="marquee_title" placeholder="">
													</div>
												</div>
											</div>
											<!-- platinum package End -->

										
											<!-- Contact Information -->
											<div class="j-row">
												<div class="span12 unit">
													<input type='hidden' id='package_type' name='package_type' value='' />
													<input type='hidden' id='image_count' name='image_count' value='0' />
													<b>Contact Information</b>
												</div>
											</div>
											<div class="j-row">
												<div class="span12" id='business_form'>
													<div class="j-row">
														<div class="span6 unit">
															<label class="label">Business Name 
																<sup data-toggle="tooltip" title="" data-original-title="Business Name">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="company">
																	<i class="fa fa-briefcase"></i>
																</label>
																<input type="text" id="busname" name="busname" placeholder="Enter  Name ">
															</div>
														</div>
														<div class="span6 unit">
															<label class="label">Contact Person Name 
																<sup data-toggle="tooltip" title="" data-original-title="Contact Person Name ">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="name">
																	<i class="fa fa-user"></i>
																</label>
																<input type="text" id="buscontname" name="buscontname" placeholder="Enter Contact Person Name ">
															</div>
														</div>
													</div>
													<div class="j-row">
														<div class="span6 unit">
															<label class="label">Mobile Number 
																<sup data-toggle="tooltip" title="" data-original-title="Mobile Number">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="phone">
																	<i class="fa fa-phone"></i>
																</label>
																<input type="text" id="bussmblno" name="bussmblno" placeholder="Enter Your Mobile Number ">
															</div>
														</div>
														<div class="span6 unit">
															<label class="label">Email 
																<sup data-toggle="tooltip" title="" data-original-title="Email">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="email">
																	<i class="fa fa-envelope-o"></i>
																</label>
																<input type="email" id="busemail" name="busemail" placeholder="Enter Your Email">
															</div>
														</div>
													</div>
												</div>
												
												<div class="span12" id='consumer_form'>
													<div class="j-row">
														<div class="span6 unit">
															<label class="label">Contact Name 
																<sup data-toggle="tooltip" title="" data-original-title="Contact Name">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="name">
																	<i class="fa fa-user"></i>
																</label>
																<input type="text" id="conscontname" name="conscontname" placeholder="Enter Contact Person Name ">
															</div>
														</div>
														<div class="span6 unit">
															<label class="label">Mobile Number 
																<sup data-toggle="tooltip" title="" data-original-title="Mobile Number ">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="phone">
																	<i class="fa fa-phone"></i>
																</label>
																<input type="text" id="conssmblno" name="conssmblno" placeholder="Enter Your Mobile Number ">
															</div>
														</div>
													</div>
													<div class="j-row">
														<div class="span6 unit">
															<label class="label">Email 
																<sup data-toggle="tooltip" title="" data-original-title="Email">
																	<img src="img/icons/i.png">
																</sup>
															</label>
															<div class="input">
																<label class="icon-right" for="email">
																	<i class="fa fa-envelope-o"></i>
																</label>
																<input type="email" id="consemail" name="consemail" placeholder="Enter Your Email">
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- end name -->
										
										<!-- start response from server -->
										<div id="response"></div>
										<!-- end response from server -->

									</fieldset>

								</div>
								<!-- end /.content -->

								<div class="footer">
									<button type="submit" class="primary-btn multi-submit-btn">Order</button>
									<button type="button" class="primary-btn multi-next-btn" >Next</button>
									<button type="button" class="secondary-btn multi-prev-btn">Back</button>
								</div>
								<!-- end /.footer -->

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.js"></script> 
	<script src="j-folder/js/jquery.maskedinput.min.js"></script>
	<script src="j-folder/js/jquery.validate.min.js"></script>
	<script src="j-folder/js/additional-methods.min.js"></script>
	<script src="j-folder/js/jquery.form.min.js"></script>
	<script src="j-folder/js/j-forms.min.js"></script>
		
	<script src="js/jquery.cleditor.min.js"></script>
	<script src="js/jquery.cleditor.js"></script>
    
	<script>
        $(document).ready(function () { 
		$("#dealdescription").cleditor({ controls: "bold italic underline | bullets numbering | font size style | color highlight" })[0].focus(); 
		});
    </script>

    <script type="text/javascript">
   
    		/*function len(){
    			
    	 var len = document.getElementById('text_hide').value;
    	 
	    	 if(len == '' || len == '<br>'){
	    	 	document.getElementById("deal_desc_error").style.display = "block";
	    	 	return false;
	    	 }
	   	 	else{
		   	 		if(len.length < 200 ){
		   	 		document.getElementById("deal_desc_error").style.display = "block";
		   	 		return false;
		   	 				}
		   	 				else{
		   	 		document.getElementById("deal_desc_error").style.display = "none";			
		   	 				}
				}
    	 
    	}*/
    	
    </script>
	
	
	<!-- Modal -->
	<form method='post' action="<?php echo base_url(); ?>postad_create_services" id='edit_service_cat'>
	<div class="modal fade" id="Services" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2>Services <span>Category </span></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-10 col-md-offset-1 post_deal_bor">
							<div class="row">
								<div class="col-md-6 clearfix">
									<h3>Professional
										<input type='hidden' name='services_cat' id='services_cat' value='services' />
										<input type='hidden' name='services_sub' id='services_sub' value='' />
										<input type='hidden' name='services_sub_sub' id='services_sub_sub' value='' />
									</h3>
							<?php foreach ($services_sub_prof as $serv_prof) { ?>
									<h4><a href="javascript:void(0)" id="<?php echo  $serv_prof['sub_category_id'].','.$serv_prof['sub_subcategory_id']; ?>" class='edit_service_prof'><?php echo ucfirst($serv_prof['sub_subcategory_name']); ?></a></h4>
							<?php	} ?>
								</div>
								<div class="col-md-6 clearfix">
									<h3>Popular</h3>
									<?php foreach ($services_sub_pop as $serv_pop) { ?>
										<h4><a href="javascript:void(0)" id="<?php echo  $serv_pop['sub_category_id'].','.$serv_pop['sub_subcategory_id']; ?>" class='edit_service_pop'><?php echo ucfirst($serv_pop['sub_subcategory_name']); ?></a></h4>
									<?php	} ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
	<!-- Services content End-->

