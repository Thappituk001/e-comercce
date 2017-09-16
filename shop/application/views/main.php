
<style>
.owl-carousel {
    display: none;
    position: relative;
    width: 100%;
    -ms-touch-action: pan-y;
}
.owl-carousel .owl-item {
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    -webkit-transform: translate3d(0, 0, 0);
    -moz-transform: translate3d(0, 0, 0);
    -ms-transform: translate3d(0, 0, 0);
}
.owl-carousel .owl-wrapper-outer {
    overflow: hidden;
    position: relative;
    width: 100%;
}
#productslider .item {
    margin: 0 15px 5px;
}

.item {
    display: block;
    height: auto;
    transition: all 0.3s ease 0s;
    -moz-transition: all 0.3s ease 0s;
    -webkit-transition: all 0.3s ease 0s;
    -o-transition: all 0.3s ease 0s;
    -ms-transition: all 0.3s ease 0s;
    margin-bottom: 15px;
    height: 450px;
}
.product {
    display: block;
    height: auto;
    transition: all 0.3s ease 0s;
    -moz-transition: all 0.3s ease 0s;
    -webkit-transition: all 0.3s ease 0s;
    -o-transition: all 0.3s ease 0s;
    -ms-transition: all 0.3s ease 0s;
    border: 1px solid #DDDDDD;
    border-bottom: 1px solid #DDDDDD;
    text-align: center;
}

.modal-dialog { /* Width */
    max-width: 100%;
    width: auto !important;
    display: inline-block;
}
.modal-body { 
    max-height: 300px; 
    padding: 10px; 
    overflow-y: auto; 
    -webkit-overflow-scrolling: touch; 
}
.modal{
    text-align: center;
}

th,td  {
    max-width: 100px;
    word-wrap: break-word;
}

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
<?php $this->load->view('module/new_arrivals'); ?>
<?php $this->load->view('module/features'); ?>

<form id="orderForm">
	<div class="modal fade" id="orderGrid" >
		<div class="modal-dialog" id="mainGrid">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#585858">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 class="modal-title text-center" id="productCode" style="size:26px;margin-top:10px;font-weight: bold;"></h5>
				</div>
				<div class="modal-body" id="orderContent">
					<form class="form-horizontal">
						<!-- <div class="table-responsive" style="width:auto;"> -->
                          <table class="table table-bordered table-striped table-highlight"  id="tableOrder">
                             <thead >
                                <tr id="tableOrder_th">
                                   <th></th>
                               </tr>
                           </thead>
                           <tbody id="tableOrder_bd"> 

                           </tbody>
                       </table>
                       <!-- </div> -->
                   </form>


               </div>
               <div class="modal-footer">
                   <!-- <input type="hidden" name="id_product" id="id_product" value="<?php echo $pd->product_id; ?>" /> -->
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary" onClick="addToCart()">Add to cart</button>
               </div>
           </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
   </div><!-- /.modal -->
</form>
