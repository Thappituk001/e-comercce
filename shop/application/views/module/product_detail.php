
<link href="<?php echo base_url(); ?>shop/assets/css/product_detail.css" rel="stylesheet">
<?php if( $pd !== FALSE ) : ?>

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
										<label for="image<?= $j ?>" class="thumb" style="background-image: url('<?php echo get_image_path(@$img->id_image, 4); ?>">
										</label>
										<?php $j++; ?>
									<?php endforeach ?>
								</div>

								<div class="wrap">
									<?php $o = 1; ?>
									<?php foreach ($images as $img): ?>
										<figure>
											<label for="fullscreen">
												<img src="<?php echo get_image_path(@$img->id_image, 4); ?>" alt="image<?= $o ?>"/>
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
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
					<div class="product-details-info-wrapper">
						<h2 class="product-details-product-title"> <?php echo $pd->style_code; ?></h2>
						<span><strong><?php echo $pd->style_name; ?></strong></span>
						
						<div class="price">
							<?php if( $pd->discount_percent != 0 ) : ?>
								<span class="old-price">
								<?php echo sell_price($pd->product_price, $pd->discount_amount,$pd->discount_percent); ?><?php echo getCurrency(); ?>
								</span>
							<?php endif; ?><br/>
								<span>
								<?php echo number_format($pd->product_price, 2, '.', ''); ?>  <?php echo getCurrency(); ?>
								</span> 
						</div>

						<?php if( has_attribute($pd->product_id, 'color') ) : ?>
							<?php 	$colors 	= get_product_colors($pd->product_id); ?>
							<div class="product-details-product-color">
								<span class="selected-color">
									<strong>Color </strong> 
									<?php if( $colors !== FALSE ) : ?>    
										<?php $i = count($colors); ?>                
										<?php 	foreach( $colors as $color ) : ?>
											<span class="color-value" style="padding-right:10px; display:inline-block"><?php echo $color->color_name; ?></span><?php if( $i >1 ) : ?>|  <?php endif; ?>
											<?php $i--; ?> 
										<?php 	endforeach; ?>                                
									<?php endif; ?>                                
								</span>
							</div>
						<?php  endif; ?>

						<?php if( has_attribute($pd->product_id, 'size') ) : ?>
							<?php 	$sizes	= get_product_sizes($pd->product_id); ?>
							<div class="product-details-product-color">
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
						<?php  endif; ?> 

						<?php if( has_attribute($pd->product_id, 'attribute') ) : ?>
							<?php 	$attrs	 = get_product_attributes($pd->product_id); ?>    
							<div class="product-details-product-color">
								<span class="selected-color">
									<strong>Attribute </strong> 
									<?php if( $attrs !== FALSE ) : ?>  
										<?php 	$i = count($attrs) ; ?>                  
										<?php 	foreach( $attrs as $attr ) : ?>
											<span class="color-value" style="padding-right:10px; display:inline-block"><?php echo $attr->attribute_name; ?></span><?php if( $i >1 ) : ?>|  <?php endif; ?>
											<?php $i--; ?>                                
										<?php 	endforeach; ?>                               
									<?php endif; ?>                                
								</span>
							</div>
						<?php  endif; ?> 

						<div class="row row-filter clearfix hide visible-xs">
							<?php if( $count_attrs['length'] == 2 && $count_attrs['horizontal'] == 'color') : ?>
								<?php 	$colors = get_product_colors($pd->product_id); ?>
								<?php 	if( $colors !== FALSE ) : ?>
									<div class="col-xs-6">
										<select class="form-control" id="colorFilter">
											<option value="" selected>Color</option>
											<?php	foreach( $colors as $color ) : ?>                                
												<option value="<?php echo $color->id_color; ?>"><?php echo $color->color_code.' | '.$color->color_name; ?></option>
											<?php 	endforeach; 	?>                                
										</select>
									</div>
								<?php endif; ?>
							<?php endif; ?>

							<?php if( $count_attrs['length'] == 2 && $count_attrs['horizontal'] == 'attribute') : ?>
								<?php 	$attrs = get_product_attributes($pd->product_id); ?>
								<?php 	if( $attrs !== FALSE ) : ?>
									<div class="col-xs-6">
										<select class="form-control" id="attrFilter">
											<option value="" selected>Attribute</option>
											<?php	foreach( $attrs as $attr ) : ?>                                
												<option value="<?php echo $attr->id_attribute; ?>"><?php echo $color->attribute_name; ?></option>
											<?php 	endforeach; 	?>                                
										</select>
									</div>
								<?php endif; ?>
							<?php endif; ?>

							<?php if( $count_attrs['length'] == 3) : ?>
								<?php 	$colors = get_product_colors($pd->product_id); ?>
								<?php 	if( $colors !== FALSE ) : ?>
									<div class="col-xs-6">
										<select class="form-control" id="colorFilter">
											<option value="" selected>Color</option>
											<?php	foreach( $colors as $color ) : ?>                                
												<option value="<?php echo $color->id_color; ?>"><?php echo $color->color_code.' | '.$color->color_name; ?></option>
											<?php 	endforeach; 	?>                                
										</select>
									</div>
								<?php endif; ?>

								<?php 	$attrs = get_product_attributes($pd->product_id); ?>
								<?php 	if( $attrs !== FALSE ) : ?>
									<div class="col-xs-6">
										<select class="form-control" id="attrFilter">
											<option value="" selected>Attribute</option>
											<?php	foreach( $attrs as $attr ) : ?>                                
												<option value="<?php echo $attr->id_attribute; ?>"><?php echo $attr->attribute_name; ?></option>
											<?php 	endforeach; 	?>                                
										</select>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>


						<!-- productFilter -->

						<div class="row row-cart-actions clearfix hide visible-xs">
							<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" onClick="getOrderGridWithFilter(<?php echo $pd->product_id; ?>)">แสดงรายการ</button></div>
						</div>

						<div class="row row-cart-actions clearfix hidden-xs">
							<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" onClick="getOrderGrid(<?php echo $pd->product_id; ?>)">ต้องการสั่งซื้อ</button></div>
						</div>

						<div class="clear"></div>

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
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 class="modal-title text-center" id="productCode"><?php echo $pd->style_code; ?> |  <?php echo $pd->style_name; ?></h5>
				</div>
				<div class="modal-body" id="orderContent">

				</div>
				<div class="modal-footer">
					<input type="hidden" name="id_product" id="id_product" value="<?php echo $pd->id_product; ?>" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onClick="addToCart()">Add to cart</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</form>

<?php endif; ?>


<script>

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
	function getOrderGrid(id_pd)
	{
		load_in();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/orderGrid",
			type:"POST", 
			cache: "false", 
			data: { "id_pd" : id_pd },
			success: function(rs){
				load_out();
				// var rs 	= $.trim(rs);
				// if( rs != 'fail' && rs != '')
				// {
				// 	var arr = rs.split(' || ');
				// 	var wid = arr[0] + 'px';
				// 	$('#mainGrid').css('width', wid);
				// 	$('#orderContent').html(arr[1]);
				// 	$("#orderGrid").modal('show');
				// }	
				console.log(rs);			
			},error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	}

	function getOrderGridWithFilter(id_pd)
	{
		var c = 0;
		var filter;
		var idc = '';
		var ida = '';

		if( $('#colorFilter').length && !$("#attrFilter").length){ 
			filter = { attr : 'color', id : $("#colorFilter").val() };
			c += 1; 
			idc	= $('#colorFilter').val();
		}

		if( $('#attrFilter').length && !$("#colorFilter").length ){ 
			filter = { attr : 'attribute', id : $("#attrFilter").val() };
			c += 1; 
			ida = $('#attrFilter').val();	
		}
		if( $("#colorFilter").length && $("#attrFilter").length ){
			c += 2;
			ida = $("#attrFilter").val();
			idc = $("#colorFilter").val();
			filter = { 'color' : idc, 'attribute' : ida };
		}
		if( $('#colorFilter').length && idc == '' ){ swal('กรุณาระบุสี'); return false; }
		if( $('#attrFilter').length && ida == ''){ swal('กรุณาระบุคุณลักษณะ'); return false; }

		if( c == 0 ){
			getOrderGrid(id_pd);	
		}else{
			var ds = JSON.stringify(filter);
			console.log(ds);
			OrderGridWithFilter(id_pd, ds, c);
		}
	}

	function OrderGridWithFilter(id_pd, data, count)
	{
		load_in();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/orderGridWithFilter",
			type:"POST", cache: "false", data: { "filter" : data, "id_pd" : id_pd, "count" : count },
			success: function(rs){
				load_out();
				var rs = $.trim(rs);
				if( rs != 'fail' && rs != '' )
				{
					//console.log(rs);
					var arr = rs.split(' || ');
					var wid = arr[0] + 'px';
					$('#mainGrid').css('width', wid);
					$('#orderContent').html(arr[1]);
					$("#orderGrid").modal('show');
				}
			}
		});				
	}

</script>
<style>
	

</style>