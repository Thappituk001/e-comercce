
<link href="<?php echo base_url(); ?>shop/assets/css/product_detail.css" rel="stylesheet">
<?php if( $product !== FALSE ) : ?>
	<?php $pd = $product[0] ?>
	<section class="section-product-info" style="background-color:white;">
		<div class="container-1000 container   main-container product-details-container">
			<div class="row">

				<!-- left column -->
				<?php if( isset( $images ) && $images !== FALSE ) : ?>            

					<!--/ left column end -->
					<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12" style="margin-bottom:20px">

						<section class="gallery">
							<div class="carousel">
								<?php $i = 1; ?>
								<?php foreach ($images as $img): ?>
									<input type="radio" id="image<?= $i ?>" name="gallery-control" >
									<?php $i++; ?>
								<?php endforeach ?>
								
								<input type="checkbox" id="fullscreen" name="gallery-fullscreen-control"/>

								<div class="thumbnails">
									<div class="slider">
										<div class="indicator"></div>
									</div>
									<?php $j = 1; ?>
									<?php foreach ($images as $img): ?>
										<label for="image<?= $j ?>" class="thumb" style="background-image: url('<?php echo get_image_path(@$img->id, 4); ?>">
										</label>
										<?php $j++; ?>
									<?php endforeach ?>
								</div>

								<div class="wrap">
									<?php $o = 1; ?>
									<?php foreach ($images as $img): ?>
										<figure>
											<label for="fullscreen">
												<img src="<?php echo get_image_path(@$img->id, 4); ?>" alt="image<?= $o ?>"/>
											</label>
										</figure>
										<?php $o++; ?>
									<?php endforeach ?>
								</div> <!-- warp -->
							</div> <!-- carousel -->
						</section>
					</div>

				<?php endif; ?>            
				<!-- right column -->
				<!-- end part of image   -->
				

				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
					<div class="product-details-info-wrapper">
						<h2 class="product-details-product-title"> <?php echo $pd->style_code; ?></h2>
						<span><strong><?php echo $pd->style_name; ?></strong></span>
						
						<div class="price">
							<?php if(  $pd->discount_percent > 0 || $pd->discount_amount > 0  ) : ?>
								<span class="old-price"><?php echo number_format($pd->product_price,2,'.',''); ?>  <?php echo getCurrency(); ?></span>
							<?php endif; ?><br/>
							<span><?php echo sell_price($pd->product_price, $pd->discount_amount,$pd->discount_percent); ?><?php echo getCurrency(); ?></span> 
						</div>

						<!--  *************************************************************  -->
						<!--  *********************** show on web *************************  -->

						<!-- part  of color-->
						<?php 	$colors 	= get_product_colors($pd->style_id); ?>
						<div class="product-details-product-color hidden-xs">
							<span class="selected-color">

								<strong>Color </strong> 
								<?php if( $colors !== FALSE ) : ?> 

									<?php 
									$id 	= [];
									$color  = [];	
									foreach ($colors as $c) {
										if (!in_array($c->id_color,$id)) {

											array_push($id,$c->id_color);
											array_push($color,["color_name"=>$c->color_name,"code_color"=>$c->code_color,"id_color"=>$c->id_color]);
											}//if
										}//foreach

										?>

										<?php $i = count($color);?>   
										<?php 	foreach( $color as $c ) : ?>
											<span class="color-value" style="padding-right:10px; display:inline-block;color:<?php print_r($c['code_color'])?>"><?php print_r($c['color_name']); ?></span>
											<?php if( $i >1 ) : ?> |  <?php endif; ?>
											<?php $i--; ?> 
										<?php 	endforeach; ?>                   
									<?php endif; ?>                                
								</span>
							</div>

							<!-- part  of size-->

							<?php 	$sizes	= get_product_sizes($pd->style_id); ?>
							<div class="product-details-product-color hidden-xs">
								<span class="selected-color">
									<strong>Size </strong> 
									<?php if( $sizes !== FALSE ) : ?>  
										<?php 	$i = count($sizes) ; ?>                  
										<?php 	foreach( $sizes as $size ) : ?>
											<span class="color-value" style="padding-right:10px; display:inline-block"><?php echo $size->size_name; ?></span><?php if( $i >1 ) : ?>|  <?php endif; ?>
											<?php $i--; ?>                                
										<?php 	endforeach; ?>                                
									<?php endif; ?>                                
								</span>
							</div>

	
							<!--  ***************************************************************  -->
							<!--  ********************** show on mobile *************************  -->

							<?php 	$colors 	= get_product_colors($pd->style_id); ?>
							<div class="product-details-product-color row-cart-actions clearfix hidden-lg hidden-md hidden-sm">
								<form>
									<strong>Select Color</strong> 
									<?php if( $colors !== FALSE ) : ?> 
										<?php 
										$id 	= [];
										$color  = [];	
										foreach ($colors as $c) {
											if (!in_array($c->id_color,$id)) {

												array_push($id,$c->id_color);
												array_push($color,["color_name"=>$c->color_name,"code_color"=>$c->code_color,"id_color"=>$c->id_color]);
											}//if
										}//foreach
										?>  <div class="form-group">
										<select class="form-control" id="color_select">
											<?php 	foreach( $color as $c ) : ?>
												<option value="<?php print_r($c['id_color']); ?>"><?php print_r($c['color_name']); ?></option>
												
											<?php 	endforeach; ?>  
										</select>
									</div>                 
								<?php endif; ?>                                

								<!-- part  of size-->
								
								<div class="product-details-product-color hidden-lg hidden-md hidden-sm">
					
									<strong>Select Size </strong>
									<div class="form-group">
										<select class="form-control" name="size_select" id="size_select">
											
										</select>
									</div>                      
								</div>
							</form>
						</div>



			<!--  **************************** END ******************************  -->
			<!--  ***************************************************************  -->
						<input type="text" class="hidden" id="style_id" value="<?= $pd->style_id; ?>">


						<div class="row row-cart-actions clearfix hidden-xs" style="margin-top: 20px">
							<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" onClick="getOrderGrid(<?php echo $pd->style_id; ?>)">ต้องการสั่งซื้อ</button></div>
						</div>


						<div class="row row-cart-actions clearfix hidden-md hidden-lg hidden-sm" style="margin-top: 20px">
							<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" >ต้องการสั่งซื้อ</button></div>
						</div>

						<!-- <div class="clear"></div> -->

						<div class="product-share clearfix">
							<p> SHARE </p>
							<div class="socialIcon">
								<a name="fb_share" data-href="#"> <i class="fa fa-facebook"></i></a>
								<a class="twitter-share-button"  href="๒"><i class="fa fa-twitter"></i></a>
								<a href="#"> <i class="fa fa-google-plus"></i></a>
								<a href="#"> <i class="fa fa-pinterest"></i></a></div>
								<br>
							</div>
						</div>
					</div>
					<!--/ right column end -->

				</div>
				<!--/.row-->

<!-- 
	<div style="clear:both"></div> -->


</div>
<!-- /.product-details-container -->
</section>

<form id="orderForm">
	<div class="modal fade" id="orderGrid">
		<div class="modal-dialog" id="mainGrid">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#585858">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 class="modal-title text-center" id="productCode" style="size:26px;margin-top:10px;font-weight: bold;"><?php echo $pd->style_code; ?> |  <?php echo $pd->style_name; ?></h5>
				</div>
				<div class="modal-body" id="orderContent">
					<form class="form-horizontal">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-highlight text-center" >
								<thead style="text-align: center;">
									<th></th>
									<?php 
									$color = [];
									$size= [];

									?>
									<?php foreach ($grid as $g): ?>
										<?php 
										if(!in_array($g->color_name,$color))
										{
											array_push($color, $g->color_name);
										}
										if(!in_array($g->size_name,$size)){
											array_push($size, array($g->size_name,"color"=>$g->color_name));
										}
										?>
									<?php endforeach ?>
									<?php foreach ($color as $c): ?>
										<th><?= $c ?></th>
									<?php endforeach ?>

								</thead>
								<tbody >									
									<?php foreach ($size as $s): ?>
										<tr><td><?php  print_r($s[0]); ?></td>
											<?php foreach ($color as $c): ?>
												<?php if ($s['color'] == $c): ?>
													<td><input type="text"></td>
												<?php else: ?>
													<td></td>
												<?php endif ?>
											<?php endforeach ?>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id_product" id="id_product" value="<?php echo $pd->product_id; ?>" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onClick="addToCart()">Add to cart</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</form>

<?php endif; ?>


<script>
$(document).ready(function(){
	$("#color_select").change(function(){
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/fetchSize",
			type:"POST",
			cache:true, 
			data: {
				"color_select" : $(this).val(),
				"id_style": $("#style_id").val()
			}, 
			success: function(rs) { 
				var opt = "";
				var res = $.parseJSON(rs)
				$.each(res, function(key, val){
					opt +="<option value='"+ val +"'>"+val["size_name"]+"</option>"
				});
				$("#size_select").html( opt );
				console.log(res);
			},error: function(e) {
				console.log("error");
			}
		});	
	});
});//document ready

	function addToCart()
	{
		$("#orderGrid").modal('hide');
		var id_cart 	= $("#id_cart").val();
		var id_cus 	= $("#id_customer").val();
		load_in();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/addToCart/"+id_cus+"/"+id_cart,
			type:"POST", cache: "false", data: $("#orderForm").serialize(),
			success: function(rs){
				load_out();
				var rs = $.trim(rs);
				if( rs == 'success' )
				{
					swal({ title: 'Success', title : 'Add to cart successfully', timer: 1000, type: 'success' });
					updateCart();
				}
				else
				{
					swal({ title: 'ไม่สำเร็จ', title : 'เพิ่มสินค้าลงตะกร้าไม่สำเร็จ กรุณาลองใหม่อีกครั้ง', type: 'error' });	
				}

			}
		});
	}

	function updateCart()
	{
		var id_cart = $('#id_cart').val();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/cart/getCartQty",
			type: "POST", cache: "false", data:{ "id_cart" : id_cart },
			success: function(rs){
				var rs = $.trim(rs);
				var rs = parseInt(rs);
				if( !isNaN(rs) )
				{
					$("#cartLabel").text(rs);
					$('#cartLabel').css('visibility', 'visible');
					$("#cartMobileLabel").text(rs);
					$('#cartMobileLabel').css('visibility', 'visible');
				}
			}
		});
	}


	function validQty(el, qty)
	{
		var input = parseInt(el.val());
		el.val(input);
		if( el.val() !== '' && isNaN(input) ){ swal('ตัวเลขไม่ถูกต้อง', 'กรุณาป้อนเฉพาะตัวเลขเท่านั้น', 'warning'); el.val(''); return false; }
		var qty = parseInt(qty);
		if( input > qty)
		{
			swal('สินค้าไม่พอ', 'มีสินค้าในสต็อก '+qty+' เท่านั้น', 'warning');
			el.val('');
		}
	}


	function getOrderGrid(id)
	{
		// load_in();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/orderGrid",
			type:"POST", 
			cache: "false", 
			data: { "id_style" : id },
			success: function(rs){
				// load_out();

				var arr = Object.keys(JSON.parse(rs)).map(function(k) { return JSON.parse(rs)[k] });
				var color = arr[0];
				var size = arr[1];

				console.log(color[0]);

		        // $("#orderContent").html();
		        
		        $("#orderGrid").modal('show');


		    },error: function(XMLHttpRequest, textStatus, errorThrown) {
		    	console.log(errorThrown);
		    }
		});
	}

	
	

</script>
<style>
	

</style>