$(document).ready(function(){
	
	$(".select-bar .minus").live("click",function(){
		add_total_cart($(this), -1);
		
	});
	
	$(".select-bar .plus").live("click",function(){
		add_total_cart($(this), 1);
	});
	
	$(".select-bar .cart_input").live("blur",function(){
		var id = $(this).attr("data-id");
		add_total_cart($(this), 0);
	});
	 
	// 设置默认收货地址
	$("input[name='consignee_id']").val( $('.address-list .current').attr('data-id') );
	
	// 添加收货地址
	$('.add-address').click(function(){
		load_consignee();
		if( $("#cart_consignee").attr("rel") == '' ){
			var addr_obj = $('.set-add-consignee');
			if( addr_obj.hasClass('display-none') ){
				addr_obj.removeClass('display-none').addClass('display-block');
			}else{
				addr_obj.removeClass('display-block').addClass('display-none');
			}
		}else{
			$("#cart_consignee").attr("rel", '');
		}
	});
	
	// 选中
	$(".address-list li").live("click",function(){
		$(".address-list li").removeClass('current');
		$(this).addClass('current');
		$("input[name='consignee_id']").val($(this).attr('data-id'));
	});
	
	
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
	
	var add_num = 1;
	var id = parseInt( $(obj).attr("duobao_item_id") );
	
	// 设置num
	if(num == 0){
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
       			ajax_login();
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
	$("input[name='num["+data.id+"]']").val(data.number); 
}
