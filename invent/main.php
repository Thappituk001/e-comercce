<?php
	$pop_on = "back";
	$sql = dbQuery("SELECT delay, start, end, content, width, height FROM tbl_popup WHERE pop_on = '$pop_on' AND active =1");
	$row = dbNumRows($sql);
	if($row>0){
		list($delay, $start, $end, $content, $width, $height ) = dbFetchArray($sql);
		$popup_content ="<div class='row'><div class='col-lg-12'>$content</div></div>";
		include "../library/popup.php";
		$today = date('Y-m-d H:i:s');
		if(isset($_COOKIE['pop_back'])&&$_COOKIE['pop_back'] !=$delay){ setcookie('pop_back','',time()-3600); }
		if($start<=$today &&$end>=$today){  
			if(!isset($_COOKIE['pop_back'])){
				setcookie("pop_back", $delay, time()+$delay);
				echo" <script> $(document).ready(function(e) {  $('#modal_popup').modal('show'); }); </script>";
			}
		}
	}
		
?>
<div class='container'>
	<div class='row'><div class='col-xs-12'>&nbsp;</div></div>
	<div class='row'>
    	<div class='col-xs-6 col-xs-offset-3'>
    		<div class='input-group'>
            	<span class='input-group-addon'>ค้นหาสินค้า</span>
            	<input type='text' name='search-text' id='search-text' class='form-control' placeholder="พิมพ์รหัสสินค้า หรือ ขื่อสินค้า ที่ต้องการค้นหา" />
                <span class='input-group-btn'>
                  <button type='button' class='btn btn-default' id='search-btn'>&nbsp;&nbsp;<span id='load'><span class='glyphicon glyphicon-search'></span></span>&nbsp;&nbsp;</button>
                </span>
            </div>
        </div>
    </div>
    <div class='row'><div class='col-xs-12'><hr/></div></div>
    <div class='row'>
    <div class='col-xs-12' id='result'>
    </div>
    </div>
</div>
<script id="template" type="text/x-handlebars-template">
<table class='table table-bordered'>
<thead>
<th style='width:15%; text-align:center;'>รูปภาพ</th><th style='width:45%; text-align:center;'>สินค้า</th><th style='width:15%; text-align:center;'>จำนวน</th><th style='width:25%; text-align:center;'>สถานที่</th>
</thead>
{{#each this}}
{{#if nodata}}
<tr>
	<td colspan="4" align='center'><h4>----- ไม่พบข้อมูล  -----</h4></td>
</tr>
{{else}}
<tr>
	<td align='center'>{{{ img }}}</td>
	<td style='vertical-align:middle;'> {{ product }}</td>
	<td align='center' style='vertical-align:middle;'>{{ total_qty }}</td>
	<td align='center' style='vertical-align:middle;'>
	<button type='button' id='{{ id }}' class='btn btn-default' data-container='body' data-toggle='popover' data-html='true' data-placement='right' data-content='{{{ in_zone }}}' onmouseover="popin($(this))" onmouseout="popout($(this))">แสดงที่เก็บ</button>
	</td>
</tr>
{{/if}}
{{/each}}
</table>
</script>
<script>
function popin(el)
{
	el.popover('show');	
}
function popout(el)
{
	el.popover('hide');	
}

$("#search-text").keyup(function(e){
    if(e.keyCode == 13)
    {
       get_search();
    }
});

function get_search()
{
	var txt = $("#search-text").val();
	if( txt != "")
	{
		load_in();
		$.ajax({
			url:"controller/searchController.php?find_product",
			type:"POST", cache:false, data:{ "search_text" : txt },
			success: function(rs)
			{
				load_out();
				var source = $("#template").html();
				var data = $.parseJSON(rs);
				var output = $("#result");
				render(source, data, output);
			}
		});
	}
}
	
</script>
