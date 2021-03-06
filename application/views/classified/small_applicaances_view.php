<!DOCTYPE html>
<html>
<head>

<title>New & Used Small Home & Kitchen Appliances For Sale UK | 99 Right Deals</title>

<meta name="description" content="Browse the used and new home and kitchen small appliances for sale like Microwave Ovens, Cookers, Grinder Juicers, Coffee Tea Makers in United Kingdom" />

<!-- xxx Head Content xxx -->
<?php echo $this->load->view('common/head');?> 
<!-- xxx End xxx -->

<link rel="stylesheet" href="<?php echo base_url(); ?>j-folder/css/j-forms.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/innerpagestyles.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/filter.css"> 
<link rel="stylesheet" href="<?php echo base_url(); ?>libs/slider.css">
<script type="text/javascript" src="<?php echo base_url(); ?>js/jssor.slider.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
$('.cd-filter-content').niceScroll({
autohidemode: 'false',     
cursorborderradius: '0px', 
background: '#f4f4f4',     
cursorwidth: '8px',       
cursorcolor: '#E95413'     
});
});
</script>

<script type="text/javascript">
$(function(){
$(".loc_map").click(function(){
var val = $(".loc_map").attr("id");
var val1 = val.split(",");
$(".map_show").html('<iframe src = "https://maps.google.com/maps?q='+val1[0]+','+val1[1]+'&hl=es;z=5&amp;output=embed" width="950px" height="300px"></iframe>');
});
});
</script>

<script type="text/javascript">
$(document).ready(
function()
{
$("input:checkbox").change(
function()
{
$("form.jforms").submit();
}
)
$('input:radio').click(function() {
$("form.jforms").submit();
}
)
$('.dealtitle_sort').change(function() {
$("form.jforms").submit();
}
)
$('.price_sort').change(function() {
$("form.jforms").submit();
}
)
$('.recentdays_sort').change(function() {
$("form.jforms").submit();
}
)
$(".clear_location").click(function(){
$('#latt').val('');
$('#longg').val('');
$('#find_loc').val('');
$("form.jforms").submit();
});
}
);
</script>
<?php foreach ($busconcount as $countval) {
$allbustype = $countval->allbustype;
$business = $countval->business;
$consumer = $countval->consumer;
}
foreach ($deals_pck as $pckval) {
$urgentcnt = $pckval->urgentcount;
$platinumcnt = $pckval->platinumcount;
$goldcnt = $pckval->goldcount;
$freecnt = $pckval->freecount;
}
foreach ($public_adview as $publicview) {
$left_ad1 = $publicview->sidead_one;
$topad = $publicview->topad;
$mid_ad = $publicview->mid_ad;
}
foreach ($sellerneededcount as $sncnt) {
$seller = $sncnt->seller;
$needed = $sncnt->needed;
$forhire = $sncnt->forhire;
}
$smalls_sub12 = $this->session->userdata('smalls_sub');
$seller_deals = $this->session->userdata('seller_deals');
$dealurgent = $this->session->userdata('dealurgent');
$dealtitle = $this->session->userdata('dealtitle');
$dealprice = $this->session->userdata('dealprice');
$recentdays = $this->session->userdata('recentdays');
$search_bustype = $this->session->userdata('search_bustype');
$location = $this->session->userdata('location');
$latt = $this->session->userdata('latt');
$longg = $this->session->userdata('longg');
?>
</head>

<body id="home">

<!--Preloader-->
<div class="preloader">
<div class="status">&nbsp;</div>
</div> 

<!-- Start Entire Wrap-->
<div id="layout">

<!-- xxx tophead Content xxx -->
<?php echo $this->load->view('common/tophead'); ?> 
<!-- xxx End tophead xxx -->

<!-- Inner Page Content Start-->
<div class="section-title-01">
<div class="bg_parallax image_01_parallax"></div>
</div>   

<section class="content-central">
<div class="semiboxshadow text-center">
<img src="<?php echo base_url(); ?>img/img-theme/shp.png" class="img-responsive" alt="Shadow" title="Shadow view">
</div>
<form id="j-forms2" action="<?php echo base_url(); ?>small_applicaances_view/search_filters" method='post' class="j-forms jforms" style="background-color: rgb(255, 255, 255) !important;">
<div class="content_info">
<div class="paddings">
<div class="container pad_bott_50">
<div class="row">
<div class="col-md-10 col-sm-8 col-md-offset-1 add_top">
<?php echo $topad; ?>
</div>
</div>
</div>
<div class="container">
<div class="row">
<!-- Item Table-->
<div class="col-md-3 col-sm-3">
<div class="container-by-widget-filter bg-dark color-white">
<!-- Widget Filter -->
<a href="<?php echo base_url(); ?>e-zone-phones-tablets-sale"><h3 class="title-widget">Ezone Filter</h3></a>

<div class="cd-filter-block">
<h4 class="title-widget">Small Appliances</h4>
<div class="cd-filter-content">
<div class="filters_categories">	
<ul class="list-styles">
<?php foreach ($smallapp as $smallapp1) {
$oven = $smallapp1->oven;
$fprocess = $smallapp1->fprocess;
$mixer = $smallapp1->mixer;
$cooker = $smallapp1->cooker;
$sandwich = $smallapp1->sandwich;
$chopper = $smallapp1->chopper;
$grill = $smallapp1->grill;
$kettle = $smallapp1->kettle;
$fryer = $smallapp1->fryer;
$purifier = $smallapp1->purifier;
$dishwash = $smallapp1->dishwash;
$flour = $smallapp1->flour;
$stabilizer = $smallapp1->stabilizer;
$others = $smallapp1->others;
} ?>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>microwave-ovens-otg-sale">Microwave Ovens & OTG (<?php echo $oven; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>food-processors-sale-london">Food Processors (<?php echo $fprocess; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>used-grinder-juicers-sale">Mixer Grinder Juicers (<?php echo $mixer; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>cookers-steamers-for-sale">Cookers & Steamers (<?php echo $cooker; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>used-toasters-sandwich-makers">Toasters & Sandwich Makers (<?php echo $sandwich; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>blenders-choppers-sale-london">Blenders & Choppers (<?php echo $chopper; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>grills-tandooris-sale-london">Grills & Tandooris (<?php echo $grill; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>coffee-makers-kettles-sale">Coffee Tea Makers & Kettles (<?php echo $kettle; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>fryers-snack-makers-sale">Fryers & Snack makers (<?php echo $fryer; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>used-water-purifiers-sale">Water Purifiers (<?php echo $purifier; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>used-dishwashers-for-sale">Dishwashers (<?php echo $dishwash; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>secondhand-flour-mill-sale">Flour Mill (<?php echo $flour; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>new-used-stablizers-sale">Stablizers (<?php echo $stabilizer; ?>)</a></li>
<li><i class="fa fa-arrow-circle-o-right"></i> <a href="<?php echo base_url(); ?>other-small-appliances-for-sale">Others (<?php echo $others; ?>)</a></li>
</ul>
</div>
</div>
</div> 

<div class="cd-filter-block">
<h4 class="title-widget">Seller Type</h4>

<div class="cd-filter-content">
<div>
<label class="checkbox">
<input type="checkbox" name="seller_deals[]" class='seller_deals' <?php if(isset($seller_deals) && in_array('Seller',$seller_deals)) echo 'checked = checked';?> value="Seller" >
<i></i> Seller Deals (<?php echo $seller; ?>)
</label>
<label class="checkbox">
<input type="checkbox" name="seller_deals[]" class='seller_deals' <?php if(isset($seller_deals) && in_array('Needed',$seller_deals)) echo 'checked = checked';?> value="Needed" >
<i></i> Needed Deals (<?php echo $needed; ?>)
</label>
<label class="checkbox">
<input type="checkbox" name="seller_deals[]" class='seller_deals' <?php if(isset($seller_deals) && in_array('ForHire',$seller_deals)) echo 'checked = checked';?> value="ForHire" >
<i></i> ForHire Deals (<?php echo $forhire; ?>)
</label>
</div>
</div> 
</div>

<div class="cd-filter-block">
<h4 class="title-widget">Deal Type</h4>

<div class="cd-filter-content">
<div>
<label class="radio">
<input type="radio" name="search_bustype" class="search_bustype" value="all" <?php if($search_bustype == 'all') echo 'checked = checked';?> checked >
<i></i> All (<?php echo $allbustype; ?>)
</label>
<label class="radio">
<input type="radio" name="search_bustype" class="search_bustype" value="business" <?php if($search_bustype == 'business') echo 'checked = checked';?> >
<i></i> Business (<?php echo $business; ?>)
</label>
<label class="radio">
<input type="radio" name="search_bustype" class="search_bustype" value="consumer" <?php if($search_bustype == 'consumer') echo 'checked = checked';?> >
<i></i> Consumer (<?php echo $consumer; ?>)
</label>
</div>
</div>
</div>

<div class="cd-filter-block">
<h4 class="title-widget">Location</h4>

<div class="cd-filter-content">
<div class="input">
<input type="text" placeholder="Enter Location" id="find_loc" class="find_loc_search" value="<?php echo $location; ?>" name="find_loc">
<input type='hidden' name='latt' id='latt' value='' >
<input type='hidden' name='longg' id='longg' value='' >
<button class="btn btn-primary sm-btn pull-right find_location" id='find_location' >Find</button>
<button class="btn btn-primary sm-btn pull-right clear_location" id='clear_location' >Clear</button>
</div>
</div>
</div> 

<div class="cd-filter-block">
<h4 class="title-widget">Search Only</h4>

<div class="cd-filter-content">
<div>
<label class="checkbox">
<input type="checkbox" name="dealurgent[]" class="dealurgent"  value="0" <?php if(isset($dealurgent) && in_array('0',$dealurgent)){ echo 'checked = checked';}?> >
<i></i> Urgent Deals (<?php echo $urgentcnt; ?>)
</label>
<label class="checkbox">
<input type="checkbox" name="dealurgent[]" class="dealurgent" value="3" <?php if(isset($dealurgent) && in_array('3',$dealurgent)){ echo 'checked = checked';}?> >
<i></i> Significant Deals (<?php echo $platinumcnt; ?>)
</label>
<label class="checkbox">
<input type="checkbox" name="dealurgent[]" class="dealurgent" value="2" <?php if(isset($dealurgent) && in_array('2',$dealurgent)){ echo 'checked = checked';}?> >
<i></i> Most Valued Deals (<?php echo $goldcnt; ?>)
</label>
<label class="checkbox">
<input type="checkbox" name="dealurgent[]" class="dealurgent" value="1" <?php if(isset($dealurgent) && in_array('1',$dealurgent)){ echo 'checked = checked';}?> >
<i></i> Recent Deals (<?php echo $freecnt; ?>)
</label>
</div>
</div>
</div> 
</div>
<div class="row top_20">
<div class="col-sm-12 add_left">
<?php echo $left_ad1; ?>
</div>
</div>
</div>
<!-- End Item Table-->

<!-- Item Table-->
<div class="col-md-9 col-sm-9">
<div class="sort-by-container tooltip-hover">
<div class="row">
<div class="col-md-12">
<strong>Sort by:</strong>
<ul>                            
<li>
<div class="top_bar_top">
<label class="input select">
<select name="dealtitle_sort" class="dealtitle_sort">
<option value="Any" <?php if($dealtitle == 'Any') echo 'selected = selected';?> >Any</option>
<option value="atoz" <?php if($dealtitle == 'atoz') echo 'selected = selected';?> >A to Z</option>
<option value="ztoa" <?php if($dealtitle == 'ztoa') echo 'selected = selected';?> >Z to A</option>
</select>
<i></i>
</label>
</div>
</li>
<li>
<div class="top_bar_top">
<label class="input select">
<select name="price_sort" class="price_sort">
<option value="Any" <?php if($dealprice == 'Any') echo 'selected = selected';?> >Any(Pricing)</option>
<option value="lowtohigh" <?php if($dealprice == 'lowtohigh') echo 'selected = selected';?> >Low to High</option>
<option value="hightolow" <?php if($dealprice == 'hightolow') echo 'selected = selected';?> >High to Low</option>
</select>
<i></i>
</label>
</div>
</li>
<li>
<div class="top_bar_top">
<label class="input select">
<select name="recentdays_sort" class="recentdays_sort">
<option value="Any" <?php if($recentdays == 'Any') echo 'selected = selected';?> >Any(posted on)</option>
<option value="last24hours" <?php if($recentdays == 'last24hours') echo 'selected = selected';?> >Last 24 Hours</option>
<option value="last3days" <?php if($recentdays == 'last3days') echo 'selected = selected';?> >Last 3 Days</option>
<option value="last7days" <?php if($recentdays == 'last7days') echo 'selected = selected';?> >Last 7 Days</option>
<option value="last14days" <?php if($recentdays == 'last14days') echo 'selected = selected';?> >Last 14 Days</option>
<option value="last1month" <?php if($recentdays == 'last1month') echo 'selected = selected';?> >Last 1 month</option>
</select>
<i></i>
</label>
</div>
</li>
</ul>
</div>
</div>
</div>
<!-- sort-by-container-->

<div class="row list_view_searches motor_result">
<?php echo $this->load->view("classified/small_applicaances_view_search"); ?>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
</section>
<!-- Inner Page Content End-->

<!-- xxx footer Content xxx -->
<?php echo $this->load->view('common/footer');?> 
<!-- xxx footer End xxx -->

</div>
<!-- End Entire Wrap -->

<div class="modal fade" id="map_location" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h2>Map Location</h2>
</div>
<div class="modal-body map_show">

</div>
</div>
</div>
</div>

<script src="<?php echo base_url(); ?>js/jquery.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery.xuSlider.js"></script>
<script src="<?php echo base_url();?>j-folder/js/jquery.validate.min.js"></script>

<script>
$('.xuSlider').xuSlider();
</script>

<script>
$(document).ready(function(){
$('#find_loc').autocomplete({
source: '<?php echo base_url(); ?>classified/search_autocomplete',
minLength: 1,
messages: {
noResults:'No Data Found'
}
});
});
</script>

<script src="<?php echo base_url(); ?>js/jquery.nicescroll.js"></script> 

<script src="<?php echo base_url();?>libs/jquery.mixitup.min.js"></script>
<script src="<?php echo base_url();?>libs/main.js"></script>

<!-- xxx footerscript Content xxx -->
<?php echo $this->load->view('common/footerscript');?> 
<!-- xxx footerscript End xxx -->

</body>
</html>
