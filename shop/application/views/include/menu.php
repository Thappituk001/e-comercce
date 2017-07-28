<!-- <ul class="nav navbar-nav">
    <li class="dropdown megamenu-fullwidth"><a data-toggle="dropdown" class="dropdown-toggle" href="#"> New
        Products <b class="caret"> </b> </a>
        <ul class="dropdown-menu">
            <li class="megamenu-content ">
                <ul class="col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl">
                    <li class="no-border">
                        <p class="promo-1"><strong> NEW COLLECTION </strong></p>
                    </li>
                    <li><a href="<?php echo base_url(); ?>shop/new_product">  ALL NEW PRODUCTS </a></li>
                    <li><a href="<?php echo base_url(); ?>shop/new_product">  NEW PRODUCT GROUP 1 </a></li>
                    <li><a href="<?php echo base_url(); ?>shop/new_product">  NEW PRODUCT GROUP 2 </a></li>
                    <li><a href="<?php echo base_url(); ?>shop/new_product">  NEW PRODUCT GROUP 3  </a></li>
                    <li><a href="<?php echo base_url(); ?>shop/new_product">  NEW PRODUCT GROUP 4  </a></li>
                </ul>
                <ul class="col-lg-3  col-sm-3 col-md-3  col-xs-4">
                    <li><a class="newProductMenuBlock" href="<?php echo base_url(); ?>shop/product"> <img
                        class="img-responsive" src="<?php echo base_url(); ?>shop/images/site/warrix-1.png" alt="product"> <span
                        class="ProductMenuCaption"> <i class="fa fa-caret-right"> </i> Category 1 </span>
                    </a></li>
                </ul>
                <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-4">
                    <li><a class="newProductMenuBlock" href="<?php echo base_url(); ?>shop/product"> <img
                        class="img-responsive" src="<?php echo base_url(); ?>shop/images/site/warrix-2.jpg" alt="product"> <span
                        class="ProductMenuCaption"> <i
                        class="fa fa-caret-right"> </i> Category 2 </span> </a></li>
                    </ul>
                    <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-4">
                        <li><a class="newProductMenuBlock" href="<?php echo base_url(); ?>shop/product"> <img
                            class="img-responsive" src="<?php echo base_url(); ?>shop/images/site/warrix-6.jpg" alt="product"> <span
                            class="ProductMenuCaption"> <i class="fa fa-caret-right"> </i> Category 3 </span>
                        </a></li>
                    </ul> 
                </li>
            </ul>
        </li>

       
        <li class="dropdown megamenu-80width ">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#"> PRODUCT
            <b class="caret"> </b> </a>
            <ul class="dropdown-menu">
                <li class="megamenu-content">
                  
                    <ul class="col-lg-2  col-sm-2 col-md-2  unstyled noMarginLeft"> 
                        <li class="no-border">
                            <p class="promo-1"><strong> Group 1</strong></p>
                        </li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 1 </a></li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 2 </a></li>
                    </ul>
                    <ul class="col-lg-2  col-sm-2 col-md-2  unstyled">
                        <li class="no-border">
                            <p class="promo-1"><strong> Group 2</strong></p>
                        </li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 1 </a></li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 2 </a></li>
                    </ul>
                    <ul class="col-lg-2  col-sm-2 col-md-2  unstyled">
                        <li class="no-border">
                            <p class="promo-1"><strong> Group 3</strong></p>
                        </li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 1 </a></li>
                        <li><a href="<?php echo base_url(); ?>shop/product"> PRODUCTS 2 </a></li>
                    </ul>
                    <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-6">
                        <li class="no-margin productPopItem ">
                            <a href="<?php echo base_url(); ?>shop/product"> 
                                <img class="img-responsive" src="<?= base_url() ?>shop/images/site/warrix-4.jpg" ></a> 
                                <a class="text-center productInfo alpha90" href="<?php echo base_url(); ?>shop/product">WA-3318 <br>
                                    <span> $399 </span></a>
                                </li>
                            </ul>
                            <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-6">
                                <li class="no-margin productPopItem relative"><a href="<?php echo base_url(); ?>shop/product"> <img
                                    class="img-responsive" src="<?= base_url() ?>shop/images/site/warrix-5.jpg" alt="img"> </a> <a
                                    class="text-center productInfo alpha90" href="<?php echo base_url(); ?>shop/product"> WA-3315 <br>
                                    <span> $379 </span> </a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </ul> -->


            <div class="navbar navbar-tshop navbar-fixed-top" role="navigation">
                <?php $this->load->view("include/navbar_top.php"); ?>
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand " href="<?php echo base_url() ?>shop"> <img src="<?php echo base_url(); ?>shop/images/logo.png" alt="WARRIX"> </a>
                        <!--  <a href="<?php echo base_url(); ?>shop/cart/cart/<?php print_r(@$this->id_cart); ?>" > </a> -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" onclick="viewCart(<?= @$this->id_cart; ?>)">
                         <i class="fa fa-shopping-basket fa-lg colorWhite"> </i> 
                         <span id="cartMobileLabel" class="label labelRounded label-danger" style="position: relative; margin-left:-10px; top:-10px;"><?php echo $this->cart_qty ?> </span>
                     </button>

                 </div>
                 <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                       <?php foreach ($menus as $menu): ?>
                        <li>
                       <!--  <pre>
                            <?php print_r($menu) ?>
                        </pre> -->
                        <?php if(empty($menu->child)): ?>

                        <a href="<?= base_url()?>shop/product/item/<?= $menu->parent_id ?>" ><i class="fa <?= $menu->icon ?>" aria-hidden="true"></i> &nbsp; <?= $menu->name ?></a>
                        <?php else: ?>
                       
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa <?= $menu->icon ?>" aria-hidden="true"></i> &nbsp; <?= $menu->name ?><span class="caret">
                            </a>
                            <ul class="dropdown-menu" >
                                <?php foreach ($menu->child as $child): ?>
                                    <?php if ($child->sub_child!==''): ?>
                                        <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa <?= $child->icon ?>" aria-hidden="true"></i> &nbsp; <?= $child->name ?><b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($child->sub_child as $sub): ?>
                                                    <li><a href="<?= base_url()?>shop/product/item/<?= $sub->parent_id ?>/<?= $sub->child_id ?>/<?= $sub->id_subchild ?>"><i class="fa <?= $sub->icon ?>" aria-hidden="true"></i> &nbsp;<?= $sub->name ?></a></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                    <?php else: ?>
                                    <li><a href="<?= base_url()?>shop/product/item/<?= $child->parent_id ?>/<?= $child->id_child ?>"><i class="fa <?= $child->icon ?>" aria-hidden="true"></i> &nbsp; <?= $child->name ?></a></li>
                                   <?php endif ?>
                               <?php endforeach ?>
                           </ul>
                           <?php endif ?>
                       </li>
                   <?php endforeach ?>
               </ul>
               <div class="navbar-right hidden-xs">
                <div class="dropdown cartMenu">
                    <a href="<?php echo base_url(); ?>shop/cart/cart/<?php print_r(@$this->id_cart); ?>" style="color:#fff;position: relative;
                        float: right;padding: 5px 5px;margin-top: 5px;margin-right: 10px;margin-bottom: 8px;background-color: transparent;background-image: none; border: 1px solid transparent; border-radius: 4px;"  > 
                        <i class="fa fa-shopping-basket fa-lg"> </i>
                        <span id="cartLabel" class="label labelRounded label-danger" style="position: relative; margin-left:-10px;top:-10px;"><?php print_r(@$cart_qty); ?></span>
                    </a>
                </div>
            </div>

        </div><!--/.nav-collapse -->
    </div>
</div>


<style>
    @media (min-width: 767px) {
        .navbar-nav .dropdown-menu .caret {
            transform: rotate(-90deg);
        }
    }
    .cartMenu:hover { font-size:16px}

</style>

<script>
    $(document).ready(function() {
        $('.navbar a.dropdown-toggle').on('click', function(e) {
            var $el = $(this);

            var $parent = $(this).offsetParent(".dropdown-menu");
            $(this).parent("li").toggleClass('open');


            if(!$parent.parent().hasClass('nav')) {
                $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 3});
            }
            $('.nav li.open').not($(this).parents("li")).removeClass("open");
            return false;
        });


    });

    function viewCart(id_cart){
        var l = window.location;
        var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1];
        // alert(base_url+"/shop/cart/cart/"+id_cart);

        document.location.href = base_url+"/shop/cart/cart/"+id_cart;
    }
</script>







