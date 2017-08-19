
<?php if( $features !== FALSE ) : ?>
<div class="container main-container">
    <div class="morePost row featuredPostContainer style2 globalPaddingTop ">
        <h3 class="section-title style2 text-center"><span>FEATURES PRODUCT</span></h3>

        <div class="container" id="draggable">
            <div class="row xsResponse" id="feature-box">
            <?php foreach( $features as $item ) : ?>
            <?php 	$link	= 'main/productDetail/'.$item->product_id; ?>
                <div class="item col-lg-3 col-md-3 col-sm-4 col-xs-6 features">
                    <div class="product">
                        <div class="image">
                            <a href="<?php echo $link; ?>"><img src="<?php echo get_image_path(get_id_cover_image($item->product_id), 3); ?>" alt="img" class="img-responsive"></a>
							<?php if( $item->discount_amount > 0 ) : ?>
                            <div class="promotion">
                                <?php if( $item->discount_percent > 0 || $item->discount_amount > 0  ) : ?>
                                	<span class="discount"><?php echo discount_label($item->discount_amount,$item->discount_percent); ?> OFF</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="description">
                            <h4>
                            <a href="<?php echo $link; ?>"><?php echo $item->style_code; ?></a></h4>
                            <p><?php echo $item->style_name; ?></p>
                         </div>
                        <div class="price">
                            <?php if(  $item->discount_percent > 0 || $item->discount_amount > 0  ) : ?>
                        	<span class="old-price"><?php echo number_format($item->product_price,2,'.',''); ?>  <?php echo getCurrency(); ?></span>
                            <?php endif; ?><br/>
                            <span><?php echo sell_price($item->product_price, $item->discount_amount,$item->discount_percent); ?><?php echo getCurrency(); ?></span> 
                        </div>
                        <div class="action-control"><a class="btn btn-primary" onclick="addToCart(<?= $item->product_id ?>,<?= $id_customer ?>)"> <span class="add2cart"><i
                                class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
                    </div>
                </div>
                <!--/.item-->
			<?php endforeach; ?>               
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="load-more-block text-center">
                	<a class="btn btn-thin" href="javascript:void(0)" onClick="loadMoreFeatures()"> 
                    	<i class="fa fa-plus-sign">+</i> load more products
                    </a>
               </div>
            </div>
        </div>
        <!--/.container-->
    </div>
    <!--/.featuredPostContainer-->
</div>   

<script id="item_template" type="text/x-handlebars-template">
{{#each this}}
<div class="item col-lg-3 col-md-3 col-sm-4 col-xs-6 features">
	<div class="product">
		<div class="image">
			<a href="{{ link }}"><img src="{{ image_path }}" alt="img" class="img-responsive"></a>
			{{#if promotion}}
				<div class="promotion">
				{{#if new_product}}
					<span class="new-product"> NEW</span> 
				{{/if}}
				{{#if discount}}
					<span class="discount">{{ discount_label }} OFF</span>
				{{/if}}
			</div>
			{{/if}}
		</div>
		<div class="description">
			<h4><a href="{{ link }}">{{ product_code }}</a></h4>
			<p>{{ product_name }}</p>
		</div>
		<div class="price">
			{{#if discount}}
			<span class="old-price">{{ price }} บาท</span>
			{{/if}}
            <br><span>{{ sell_price }} บาท</span> 
		</div>
		<div class="action-control"><a class="btn btn-primary"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
	</div>
</div>
{{/each}}
</script> 
<script>
function loadMoreFeatures()
{
	var offset = $('.features').length;
    console.log(offset);
	load_in();
	setTimeout(function(){
	$.ajax({
		url:"main/loadMoreFeatures",
		type:"POST", cache:false, data:{ "offset" : offset },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs != 'none' )
			{
                // console.log(rs);
				var source = $('#item_template').html();
				var data    = $.parseJSON(rs);
				var output	= $('#feature-box');
				render_append(source, data, output);
			}
		}
	});	
	}, 1000);
}

</script>

<?php endif; ?>

