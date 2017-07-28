<a onclick="openNav()" id="btn_filter" class="btn_filter hidden-lg hidden-md hidden-sm">open</a>
<script type="text/javascript" src="<?php echo base_url(); ?>shop/assets/plugins/icheck-1.x/icheck.min.js"></script> 


<script src="<?php echo base_url(); ?>shop/assets/js/nouislider.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>shop/assets/css/nouislider.min.css" />

<div class="container main-container" style="margin-left:5%">
  <div class="morePost row featuredPostContainer style2 globalPaddingTop ">
    <h3 class="section-title style2 text-center header-main"><span>NEW PRODUCT</span></h3>
    <div class="col-md-2 col-sm-2 hidden-xs">

     <?php echo form_open('shop/new_product'); ?>
     <label for="">COLOR</label>
     <div class="input-group">
      <?php foreach ($color as $c): ?>
        <input type="checkbox" name="color[]" value="<?= $c->id_color ?>"> <?php print_r($c->color_group) ?><br>
      <?php endforeach ?>
    </div>
    <legend></legend>
    <label for="">SIZE</label>
    <div class="input-group scroll">
      <?php foreach ($size as $s): ?>
        <input type="checkbox" name="size[]" value="<?= $s->id_size ?>"> <?php print_r($s->size_name) ?><br>
      <?php endforeach ?>
    </div>
    <legend></legend>

    <label for="price-min">Price:</label>
    <div id="slider"></div><br>

    MIX: <span id="slider-snap-value-lower"></span><br>
    MAX:<span id="slider-snap-value-upper"></span><br>
    <button type="submit" class="btn btn-block btn-primary">ตกลง</button>
    <?php echo form_close(); ?>




  </div>
  <div class="col-md-10 col-sm-10 col-xs-12" id="draggable">
    <div class="row xsResponse" id="feature-box">

      <?php foreach( $product_query as $item ) : ?>

        <?php 	$link	= 'main/productDetail/'.$item->id_product; ?>
        <div class="item col-lg-3 col-md-3 col-sm-4 col-xs-6 features">
          <div class="product">                
            <div class="image">
             <a href="<?php echo $link; ?>"><img src="<?php echo get_image_path(get_id_cover_image($item->id_product), 3); ?>" alt="img" class="img-responsive"></a>
             <?php if( $item->discount != 0 OR is_new_product($item->id_product)) : ?>
              <div class="promotion">
               <?php if( is_new_product($item->id_product) ) : ?>
                <span class="new-product"> NEW</span> 
              <?php endif; ?>
              <?php if( $item->discount != 0 ) : ?>
               <span class="discount"><?php echo discount_label($item->discount, $item->discount_type); ?> OFF</span>
             <?php endif; ?>
           </div>
         <?php endif; ?>
       </div>
       <div class="description">
        <h4><a href="<?php echo $link; ?>"><?php echo $item->product_code; ?></a></h4>
        <p><?php echo $item->product_name; ?></p>
      </div>
      <div class="price">
       <span><?php echo sell_price($item->product_price, $item->discount, $item->discount_type); ?>  <?php echo getCurrency(); ?></span><br>
       <?php if( $item->discount != 0 ) : ?>
         <span class="old-price"><?php echo $item->product_price; ?>  <?php echo getCurrency(); ?></span>
       <?php endif; ?>
     </div>
     <div class="action-control"><a class="btn btn-primary" onclick="addToCart(<?= $item->id_product ?>,<?= $id_customer ?>)" > <span class="add2cart"><i
      class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
    </div>
  </div>
  <!--/.item-->
<?php endforeach; ?>               
</div>
<!-- /.row -->
<div class="row">
  <div class="load-more-block text-center">
   <a class="btn btn-thin header-main" href="javascript:void(0)" onClick="loadMoreFeatures()"> 
     <i class="fa fa-plus-sign">+</i> load more products
   </a>
 </div>
</div>
</div>
<!--/.container-->
</div>
<!--/.featuredPostContainer-->
</div>  

<div id="myNav" class="overlay">

  <!-- Button to close the overlay navigation -->
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" style="margin:0px;padding:0px">&times;</a>

  <!-- Overlay content -->
  <div class="overlay-content" style="margin-bottom:50px;color:red;margin-top:0px;padding-top:0px">

    <?php echo form_open('shop/new_product'); ?>
    <div class="container">
      <div class="row">
        <label style="font-weight:bold;color:#DA631D">COLOR</label>
      </div>
      <?php foreach ($color as $c): ?>
        <div class="col-xs-6">
          <div class="input-group">
            <input type="checkbox" name="color[]" value="<?= $c->id_color ?>"> 
            <?php print_r($c->color_group) ?><br>
          </div>
        </div>

      <?php endforeach ?>

      <legend></legend>

      <div class="row">
        <label style="font-weight:bold;color:#DA631D">SIZE</label>
      </div>
      <?php foreach ($size as $s): ?>
        <div class="col-xs-6">
          <div class="input-group">
            <input type="checkbox" name="size[]" value="<?= $s->id_size ?>"> 
            <?php print_r($s->size_name) ?><br>
          </div>
        </div>
      <?php endforeach ?>
      
      <div class="row" style="padding-right:10px;padding-left: 10px">
        <legend></legend>
        <label style="font-weight:bold;color:#DA631D">Price:</label>
        <CENTER>
          <div id="slider_modal" style="width:200px"></div><br>

          MIX: <span id="slider-snap-value-lower-modal"></span><br>
          MAX:<span id="slider-snap-value-upper-modal"></span><br>
          <button type="submit" class="btn btn-block btn-primary">ตกลง</button>
        </CENTER>
      </div>


      <?php echo form_close(); ?>

    </div>

  </div>

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
   <span>{{ sell_price }}</span> 
   {{#if discount}}
   <span class="old-price">{{ price }}</span>
   {{/if}}
 </div>
 <div class="action-control"><a class="btn btn-primary"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
</div>
</div>
<!--/.item--> 
{{/each}}
</script>
<script>


  var slider = document.getElementById('slider');

  noUiSlider.create(slider, {
    start: [0, 10000],
    connect: true,
    range: {
      'min': 0,
      'max': 10000
    }
  });

  var snapValues = [
  document.getElementById('slider-snap-value-lower'),
  document.getElementById('slider-snap-value-upper')
  ];

  slider.noUiSlider.on('update', function( values, handle ) {
    snapValues[handle].innerHTML = values[handle];
  });

  var slider_modal = document.getElementById('slider_modal');

  noUiSlider.create(slider_modal, {
    start: [0, 10000],
    connect: true,
    range: {
      'min': 0,
      'max': 10000
    }
  });

  var snapValues = [
  document.getElementById('slider-snap-value-lower-modal'),
  document.getElementById('slider-snap-value-upper-modal')
  ];

  slider_modal.noUiSlider.on('update', function( values, handle ) {
    snapValues[handle].innerHTML = values[handle];
  });

  function loadMoreFeatures()
  {

   var offset = $('.features').length;
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
        var source = $('#item_template').html();
        var data        = $.parseJSON(rs);
        var output  = $('#feature-box');
        render_append(source, data, output);
      }
    }
  }); 
   }, 1000);
 }



 $(window).scroll(function() {
  if($(window).scrollTop() >=  $(document).height()-$(window).height()) {
    loadMoreFeatures();
  }
});

 function openNav() {
  $("#myNav").css('width', '100%');
  $(".header-main").hide();
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav() {
  $("#myNav").css('width', '0%');
  $(".header-main").show("slow");
}


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


      <style>
        div.scroll {
          width: 100px;
          height: 100px;
          overflow: scroll;
        }

        .btn_filter{
          position:absolute;
          width:30px;
          height:30px;
          text-align: center;
          background-color:#F5ECCE;
          padding-top:5px;
          box-shadow: 2px 2px 2px #888;
          border-radius:20px;
          margin-top:5%;
          z-index: 999;
        }


        .overlay {
          height: 100%;
          width: 0;
          position: fixed; 
          z-index: 1; 
          left: 0;
          top: 0;
          background-color: rgb(0,0,0); 
          background-color: rgba(0,0,0, 0.9);
          overflow-x: hidden; 
          transition: 0.5s; 
        }

        .overlay-content {
          position: relative;
          top: 20%; /* 25% from the top */
          width: 100%; /* 100% width */
          text-align: center; /* Centered text/links */
          margin-top: 30px; 
        }

        .overlay a {
          padding: 8px;
          text-decoration: none;
          font-size: 16px;
          color: #818181;
          display: block; /* Display block instead of inline */
          transition: 0.3s; /* Transition effects on hover (color) */
        }

        .overlay a:hover, .overlay a:focus {
          color: #f1f1f1;
        }

        .overlay .closebtn {
          position: absolute;
          top: 17%;
          right:3%;
          font-size: 24px;
        }

        @media screen and (max-height: 450px) {
          .overlay a {font-size: 20px}
          .overlay .closebtn {
            font-size: 40px;
            top: 15px;
            right: 35px;
          }
        }
      </style>
