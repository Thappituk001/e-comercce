
<?php if( @$new_arrivals !== true ) : ?>

    <div class="container main-container head-offset">
        <!-- Main component call to action -->

        <div class="row featuredPostContainer globalPadding style2">
            <h3 class="section-title style2 text-center header-main"><span>NEW ARRIVALS</span></h3>

            
            <div id="productslider" class="owl-carousel owl-theme ">
                <?php foreach( @$new_arrivals as $item ) : ?>
                    <?php 	@$link = 'main/productDetail/'.$item->product_id; ?>
                    <div class="item">
                        <div class="product">
                            <div class="image">
                                <a href="<?php echo $link; ?>">
                                 <img src="<?php echo get_image_path(get_id_cover_image($item->product_id), 4); ?>" class="img-responsive">
                             </a> 
                             <div class="promotion">
                               <span class="new-product" > NEW </span>
                               <?php if ($item->discount_amount > 0 && $item->discount_percent <= 0): ?>
                                <span class="discount">
                                    <?php echo number_format($item->discount_amount, 2, '.', '');?> บาท
                                    <span style="color:yellow" >OFF</span>
                                </span>
                            <?php elseif ($item->discount_amount <= 0 && $item->discount_percent > 0): ?>
                                <span class="discount">
                                    <?php echo number_format($item->discount_percent, 2, '.', '');?> %
                                    <span style="color:yellow" >OFF</span>
                                </span>
                            <?php elseif($item->discount_amount > 0 && $item->discount_percent > 0): ?>
                                <span class="discount">
                                    <?php echo number_format($item->discount_amount, 2, '.', '');?> บาท 
                                    <?php echo number_format($item->discount_percent, 2, '.', '');?> %
                                    <span style="color:yellow" >OFF</span>
                                </span>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="description">
                        <h4><a href="<?php echo $link; ?>"><?php echo $item->style_code; ?></a></h4>
                        <p><?php echo $item->style_name; ?></p>
                    </div>
                    <div class="price">
                        <?php if( $item->discount_percent > 0 || $item->discount_amount > 0) : ?>
                            <span class="old-price"><?php echo number_format($item->product_price, 2, '.', '') ?>  <?php echo getCurrency(); ?></span>
                        <?php endif; ?>
                        <span><br>
                            <?php echo sell_price($item->product_price, $item->discount_amount,$item->discount_percent); ?> <?php echo getCurrency(); ?>
                        </span> 
                    </div>
                    <div class="action-control">
                        <button class="btn btn-primary" onclick="addToCart(<?= $item->product_id ?>,<?= $id_customer ?>)"> 
                            <span class="add2cart">
                                <i class="glyphicon glyphicon-shopping-cart"></i> Add to cart </span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?> 
            <!-- End items -->
        </div>
        <!--/.productslider-->

    </div>
    <!--/.featuredPostContainer-->
</div>    
<?php endif; ?>

<script>

    function addToCart(id_product,id_customer){
            // console.log(id_product);
            // console.log(id_customer);
            
            $.ajax({
                url:"cart/addToCart",
                type:"POST",
                cache:false, 
                data:{"id_product":id_product,"id_customer":id_customer},
                success: function(res){
                    console.log(res);
                    if(res=='success'){
                        location.reload();
                    }
                    
                },error:function(e){
                    console.log("error");
                }
            }); 
        }//function


    </script>