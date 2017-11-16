$(document).ready(function(){
	
	$(".goods-num .minus").live("click",function(){
		add_total_cart($(this), -1);
		
	});
	
	$(".goods-num .plus").live("click",function(){
		add_total_cart($(this), 1);
	});
	
	 
	
	$(".goods-num .cart_input").bind('input',function(e){  
		add_total_cart($(this));
	});
	 
	 
	 
	$("#submit_buy_form").bind("click",function(){
		 
		var form_obj 	= $('#buy_form');
		var query 		= form_obj.serialize();
		var action 		= form_obj.attr("action");
		
	  
		$.ajax({
			url:action,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status==1)
				{
					location.href = obj.jump;
				}else{
					$.showErr(obj.info,function(){
						if(obj.jump)
						{
							location.href = obj.jump;
						}
					});
				}
			 
			}
		});
	 
		return false;
	});
	 
});
 

function add_total_cart(obj, num){
	
	var add_num;
	var id = parseInt( $(obj).attr("duobao_item_id") );
	
	// 设置num
	if( obj.hasClass( 'cart_input' ) ){
		add_num = parseInt( obj.val() );
	}else{
		add_num = parseInt( obj.siblings("input").val() ) + num;
	}
	
	 
	 //请求服务端加入购物车表
	var query     = new Object();
	query.act     = "add_total_cart";
    query.update  = 1;
    query.buy_num = add_num;
    query.data_id = id;
    $.ajax({
       url: AJAX_URL,
       data: query,
       type: "POST",
       dataType: "json",
       success: function (obj) {
           if (obj.status == -1) {
        	   location.href=obj.jump;
           }else if (obj.status == 1) {
           		recount_total(obj.cart_item);
           }else{	                	
               $.showErr(obj.info);
           }	
       }
    });
}

function recount_total(data)
{
	var total_price = parseFloat(data.total_price);
	total_price = total_price.toFixed(2);
	
	$('.set-total-price').text(total_price);
	$(".goods-num .cart_input").val(data.number); 
	
}
