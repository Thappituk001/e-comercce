<?php
$this->load->view("include/header"); 
$this->load->view($view);
$this->load->view("include/footer");
?>
<div class="promo_field" id="promo">
	Promotion Field
</div>
<script>
	$(window).scroll(function() {
	    if ($(this).scrollTop()) {
	       $('#promo').show('4000', function() {
		
			});
	    } else {
	        $('#promo').hide('400', function() {
		
			});
	    }
	});
</script>
<style>
	.promo_field{
		position: absolute;
	    bottom:50px;
	    font-size: 18px;
	    width:100%;
	    height:100px;
		opacity:0.4;
	    background-color:#2E2E2E;
	}
</style>