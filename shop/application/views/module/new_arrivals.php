<?php if( @$new_arrivals !== true ) : ?>

    <div class="container main-container head-offset">
        <!-- Main component call to action -->

        <div class="row featuredPostContainer globalPadding style2">
            <h3 class="section-title style2 text-center header-main"><span>NEW ARRIVALS</span></h3>

            <div id="productslider" class="owl-carousel owl-theme">
                <!-- <?php echo "id customer :".$id_customer; ?>
                <?php echo "id cart :"; print_r($id_cart); ?>
                <?php echo "item :"; print_r($cart_items) ?>
                <?php echo "qty :" ; print_r($cart_qty); ?> -->
                <!-- Items -->
                <?php foreach( @$new_arrivals as $item ) : ?>
                    <?php 	@$link = 'main/productDetail/'.$item->id_product; ?>
                    <div class="item">
                        <div class="product">
                           <!--  Wishlist  <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left"><i class="glyphicon glyphicon-heart"></i></a>  -->
                           <div class="image">
                            <a href="<?php echo $link; ?>">
                             <img src="<?php echo get_image_path(get_id_cover_image($item->id_product), 3); ?>" alt="img" class="img-responsive">
                         </a>
                         <div class="promotion">
                             <span class="new-product"> NEW </span>
                             <?php if( $item->discount != 0 ) : ?>
                                 <span class="discount"><?php echo discount_label($item->discount, $item->discount_type); ?> OFF </span>
                             <?php endif; ?>
                         </div>
                     </div>
                     <div class="description">
                        <h4><a href="<?php echo $link; ?>"><?php echo $item->product_code; ?></a></h4>

                        <p><?php echo $item->product_name; ?></p>
                        <!--   <span class="size">XL / XXL / S </span>  -->
                    </div>
                    <div class="price">
                            <?php if( $item->discount != 0 ) : ?>
                            <span class="old-price"><?php echo $item->product_price; ?>  <?php echo getCurrency(); ?></span>
                            <?php endif; ?><br/>
                            <span>
                            <?php echo sell_price($item->product_price, $item->discount, $item->discount_type); ?>  <?php echo getCurrency(); ?>
                            </span> 
                    </div>
                    <div class="action-control">
                        <button class="btn btn-primary" onclick="addToCart(<?= $item->id_product ?>,<?= $id_customer ?>)"> 
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