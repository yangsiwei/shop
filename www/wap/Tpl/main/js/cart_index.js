$(document).ready(function(){
	init_cartnum_btn();
	init_del_cart_item();
	init_buy_form();
});

function init_del_cart_item(){
   $(".del-item-btn").bind("click",function(){
       var data_id = $(this).attr("data-id");
       del_item(data_id);
   });

}
function del_item(data_id){
    $(".cart-list li[data-id='"+data_id+"']").fadeOut("slow");
       $(".cart-list li[data-id='"+data_id+"']").remove();
       jsondata[data_id]["number"]=0;
       call_total_show();
       var query = new Object();
        query.id = data_id;
        query.act = "del_cart";
        $.ajax({
            url:AJAX_URL,
            data:query,
            type:"POST",
            dataType:"JSON",
            success:function(obj){
                if(obj.cart_info.cart_item_num>0){
                     $(".nav_cart_num").html(obj.cart_info.cart_item_num);
                     $(".nav_cart_num").show();
                     $(".cart-num-set").text(obj.cart_info.cart_item_num);
                }
                else
                    $(".nav_cart_num").hide();
            }
        });
}

function init_buy_form()
{
	$("#buy_form").bind("submit",function(){

		var query = $(this).serialize();
		var action = $(this).attr("action");

		$.ajax({
			url:action,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status==1)
				{
					location.href = obj.jump;
				}
				else if(obj.status==-1)
				{
					location.href = obj.jump;
				}
				else
				{
                    if(obj.expire_ids.length){
                        $.showErr(obj.info,function(){
                        	$.each( obj.expire_ids, function(i, o)
                            { 
                                del_item(o);
                            });
                        });
                        
                        return false;
                    }
                    else if(obj.info)
                    {
                    	$.showErr(obj.info,function(){
                    		if(obj.jump)
                            {
                                    location.href = obj.jump;
                            }
                    	});
                        
                    }
                    else
                    {
                            if(obj.jump)
                            {
                                    location.href = obj.jump;
                            }
                    }

				}
			}
		});

		return false;
	});
}

function init_cartnum_btn()
{
	$(".minus").bind("click",function(){
		var data_id = $(this).attr("data-id");
		var number = parseInt(jsondata[data_id]["number"]);
                var min_buy = parseFloat(jsondata[data_id]["min_buy"]);
		jsondata[data_id]["number"] = number-min_buy<=0?min_buy:number-min_buy;
                $(".buy-num-"+data_id).val(jsondata[data_id]["number"]);

		call_total_show();
	});

	$(".plus").bind("click",function(){
		var data_id = $(this).attr("data-id");
		var number = parseInt(jsondata[data_id]["number"]);
		var unit_price = parseFloat(jsondata[data_id]["unit_price"]);
                var min_buy = parseFloat(jsondata[data_id]["min_buy"]);
                var residue_count = parseFloat(jsondata[data_id]["residue_count"]);
		jsondata[data_id]["number"] = number+min_buy>residue_count?residue_count:number+min_buy;
                $(".buy-num-"+data_id).val(jsondata[data_id]["number"]);

		call_total_show();
	});

	$(".buy_number").blur(function(){
		var data_id = $(this).attr("data-id");
		var number = parseInt(jsondata[data_id]["number"]);
		var unit_price = parseInt(jsondata[data_id]["unit_price"]);
                var min_buy = parseInt(jsondata[data_id]["min_buy"]);
                var residue_count = parseFloat(jsondata[data_id]["residue_count"]);

                var cart_num =$(this).val();

		if(cart_num==''){
			cart_num = min_buy;
		}
		if(cart_num>=residue_count){
			cart_num = residue_count;
		}else{
			 var multiple = parseInt(parseInt($(this).val())/min_buy);
			  if(multiple>0){
				  cart_num = min_buy*multiple;
			  }else{
				  cart_num = min_buy;
			  }
		}
		jsondata[data_id]["number"] =cart_num;
                $(this).val(jsondata[data_id]["number"]);
		call_total_show();
		button_buy_box_show();

	});
	$(".buy_number").focus(function(){
		button_buy_box_hide();
	});

}

function call_total_show()
{
    var total_price = 0;
    $.each( jsondata, function(i, o){
        total_price+= parseInt(o.number*o.unit_price);
    });
    //$(".cart-item-number span").html(total_price+"夺宝币");
    $(".cart-item-number span").html(total_price);
}

function button_buy_box_show(){
	$(".cart-floot").show("fast");
	$(".footer-menu-box").show("fast");
}
function button_buy_box_hide(){

		$(".cart-floot").hide();
		$(".footer-menu-box").hide();

}
