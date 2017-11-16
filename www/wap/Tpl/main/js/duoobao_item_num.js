// 预选购买数量
$(document).ready(function () {
	 init_change_cart_num();
	 init_get_buy_num();
});

function init_get_buy_num(){
	$(".right-box").live("click", function(){
		// 禁止触摸
		$("body").bind("touchmove",function(event){
			event.preventDefault();
		});

	 	$("#duobao_item_id_set").val( $(this).attr('data-id') );
        // 默认数量为最小投单量

        var data_id = $("#duobao_item_id_set").val();

		var query = new Object();
		query.data_id = data_id;
        query.act="get_duobao_item_num";
        $.ajax({
                url: AJAX_URL,
                data: query,
                type: "POST",
                dataType: "json",
                success: function (dataObj) {
                	
                	if (dataObj.user_login_status != 1) {
                    	$.showErr(dataObj.info,function(){
                    		if (dataObj.jump)
                            {
                    			location.href = dataObj.jump;
                            }
                        });
                        return false;
                    }
                	
                	/* 显示预选包尾窗口 */
                	$(".add-bg").show();
         	        $(".add-to-list").addClass('add-to-list-up');
         	        // 清除所有选中状态
         	        $(".price-btn").removeClass("all-in");
         	      
         	       /* 调整显示的样式，比如可选择，不可选择  */
                	var data = dataObj.duobao_number;
                	var img_obj = $('.add-to-list .info .title .imgbox img');
                	var a_href  = img_obj.attr('data')+'&data_id='+data_id;
                	img_obj.attr('src', data.icon);
                	img_obj.parent().attr('href', a_href); 
                	
                	// 设置数量参数
                	$("input[name='choose_item_number']").attr('max-buy', data.max_buy);
                	$("input[name='choose_item_number']").attr('min-buy', data.min_buy);
                	$("input[name='choose_item_number']").attr('cur-buy', data.current_buy);
                    $("input[name='choose_item_number']").attr("unit-price",data.unit_price);

                	var can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
                	var btn_num;
                	$(".price-btn").each(function(){
                		btn_num = parseInt($(this).text());
                		if(btn_num >  can_buy){
                			$(this).addClass("disable");
                		}else{
                			$(this).removeClass("disable");
                		}
                	});
                	 
                	// 10夺宝币区，或者可购买数小于5的，5就不显示
                	if(data.min_buy >= 10 || can_buy < 5){
                		$('.price-btn').eq(0).addClass("disable");
                	}else{
                		$('.price-btn').eq(0).removeClass("disable");
                	} 
                	
                	if(data.min_buy == 1){
                		$("input[name='choose_item_number']").val(1);
                		$('.money-info span').text(parseInt($("input[name='choose_item_number']").attr("unit-price")));
                	}else{
                		$("input[name='choose_item_number']").val(data.min_buy);
                		$('.money-info span').text(data.min_buy*parseInt($("input[name='choose_item_number']").attr("unit-price")));
                	}
                	
                	
                }
        });
       
        
    });
	// 关闭预选包尾窗口
    $(".btn-close").click(function() {
    	// 恢复触摸
		$("body").unbind("touchmove");
		
        $(".add-bg").hide();
        $(".add-to-list").removeClass('add-to-list-up');
    });
    
    $(".add-bg").click(function() {
    	// 恢复触摸
		$("body").unbind("touchmove");
    	
        $(".add-bg").hide();
        $(".add-to-list").removeClass('add-to-list-up');
    });
}

function init_change_cart_num(){
	var num_input 		= $("input[name='choose_item_number']");
	// 输入变
	num_input.bind("focusout",function(){ 
		get_last_num($(this));
	});
	
	num_input.bind("focusin",function(){ 
		get_last_num($(this));
	});

 
	num_input.bind('input propertychange', function() {  
		get_last_num($(this));
	});
	
 
	
	$('.price-btn').bind("click", function () {
		// 判断是否可以选择
		if($(this).hasClass('disable')){
			return false;
		}
		
		$(this).addClass("all-in").siblings().removeClass("all-in");
		
		var number_item;
		var choose_number   = $(this).text();
		var data_id 		= $("#duobao_item_id_set").val(); 
        var data			= new Object;
		
		
		data.max_buy 		= num_input.attr('max-buy');
		data.min_buy 		= num_input.attr('min-buy');
		data.current_buy 	= num_input.attr('cur-buy');

    	var can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
    	
    	if( isNaN(choose_number) || choose_number > can_buy ){  // 如果不是数字，则是“我包了”，显示所有剩余量
    		number_item = can_buy;
    	}else{
    		number_item = choose_number;
    	}
    	
    	

    	$('.money-info span').text(number_item*parseInt(num_input.attr("unit-price")));
    	
    	// 显示效果
    	num_input.val( number_item );
    	 
    	num_input.animate({ 
		    fontSize: "0.9rem", 
		}, 200 );
         
    	num_input.animate({ 
		    fontSize: "0.5rem", 
		}, 200 );
		
	});
	
	
	// +
	$('.index-add-cart-num').bind("click", function () {
		$('.price-btn').removeClass("all-in");
		
		var choose_number = $(this).text();
		var can_buy;
		
		// 原先的数值
		var org_number =  num_input.val();
		var data_id = $("#duobao_item_id_set").val();
         
		var number_item;
        var data			= new Object;
		data.max_buy 		= num_input.attr('max-buy');
		data.min_buy 		= num_input.attr('min-buy');
		data.current_buy 	= num_input.attr('cur-buy');
    	
    	can_buy 	= parseFloat(data.max_buy) - parseFloat(data.current_buy);
    	number_item = parseFloat(org_number) + parseFloat(data.min_buy);
    	
    	if(number_item > can_buy){
    		number_item = can_buy;
    	}

    	$('.money-info span').text(number_item*parseInt(num_input.attr("unit-price")));
    	num_input.val(number_item);
    	 
    	
    	num_input.css("font-size", '0.9rem');
        setTimeout(function(){
        	num_input.css("font-size", '0.5rem');
        }, 200 );
         
	});
	
	// -
	$('.index-subtract-cart-num').bind("click", function () {
		$('.price-btn').removeClass("all-in");
		
		 
		var choose_number = $(this).text();
		var can_buy;
		
		// 原先的数值
		var org_number =  num_input.val();
		var data_id = $("#duobao_item_id_set").val();
         
		var number_item;
        var data			= new Object;
		data.max_buy 		= num_input.attr('max-buy');
		data.min_buy 		= num_input.attr('min-buy');
		data.current_buy 	= num_input.attr('cur-buy');
		
		
		can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
    	number_item = parseFloat(org_number) - parseFloat(data.min_buy);
    	if(number_item > can_buy){
    		number_item = can_buy;
    	}
    	
    	if(number_item < data.min_buy){
    		number_item = data.min_buy;
    	}
    	
    	$('.money-info span').text(number_item*parseInt(num_input.attr("unit-price")));
    	
    	num_input.val(number_item);
    	 
    	
    	num_input.css("font-size", '0.9rem');
        setTimeout(function(){
        	num_input.css("font-size", '0.5rem');
        }, 200 );
        
	});
	
	// 添加到购物车
	$('.index-comfirm-addto_cart').bind("click", function () {
		// 购买的数量
		var data_id = $("#duobao_item_id_set").val();
		//var pay_url = APP_ROOT + "/wap/index.php?ctl=cart&act=check&data_id=" + data_id;
		var pay_url = APP_ROOT + "/wap/index.php?ctl=cart&show_prog=1";
		var add_number =  $("input[name='choose_item_number']").val();
		var query = new Object();
		query.data_id = data_id;
		query.buy_num = add_number;
        query.act="add_cart";
        $.ajax({
                url: AJAX_URL,
                data: query,
                type: "POST",
                dataType: "json",
                success: function (data) {
                	if(data.status == 1){
                		if(data.jump){
                			location.href = data.jump;
                		}else{
                			location.href = pay_url;
                		}
                	}else{
                		if(data.status == -1){
                			$(".add-bg").hide();
                	        $(".add-to-list").removeClass('add-to-list-up');
                			$.showErr(data.info, function(){
                				location.href = data.jump;
                			});
                		}else{
                			$(".add-bg").hide();
                	        $(".add-to-list").removeClass('add-to-list-up');
                			$.showErr(data.info);
                		}
                		// 恢复触摸
                		$("body").unbind("touchmove");
                	}
                }
        });
	});
}

// 计算输入的值
function get_last_num(num_obj){
	$("input[name='org_num']").val('');
	$('.price-btn').removeClass("all-in");
	// 修改的值
	var mod_val = 0;
	var num_input 		= $("input[name='choose_item_number']");
	var app_num_input 	= $("input[name='app_choose_item_number']");
	
	var change_num 		= num_obj.val();
    var data			= new Object;

	data.max_buy 		= num_input.attr('max-buy');
	data.min_buy 		= num_input.attr('min-buy');
	data.current_buy 	= num_input.attr('cur-buy');
	
	 
	if(parseInt(change_num) > parseInt(data.min_buy)){
   		var set_num = '';
   		var cur_max_num = data.max_buy - data.current_buy;
   		if(change_num < data.min_buy){
   			//set_num = data.min_buy;
   		}else if(change_num > cur_max_num){
   			set_num = cur_max_num;
   		}else{
   			mod_val = change_num % data.min_buy;
       		if(mod_val > 0){
           		set_num = Math.floor(change_num / data.min_buy) * data.min_buy;
           	}else{
           		set_num = change_num;
           	}
   		}
   		
   		if(set_num > 0){
   			num_input.val(set_num);
   			app_num_input.val( set_num );
   			$('.money-info span').text(set_num*parseInt(num_input.attr("unit-price")));
   		}
   		
   	}else{
   		//num_input.val(data.min_buy);
   		//app_num_input.val( data.min_buy );
   		//$('.money-info span').text(data.min_buy*parseInt(num_input.attr("unit-price")));
   	}
 
   	$('.money-info span').text( num_input.val()*parseInt(num_input.attr("unit-price")));

}


var cur_num = 0;
/*加入购车事件*/
function add_cart(obj){
    var btn_item = $(obj);

        var buy_num = parseInt($("input[name='choose_item_number']").val());
        //请求服务端加入购物车表
        var query = new Object();
        query.act = "add_cart";
        query.buy_num = buy_num;
        query.data_id = parseInt($("#duobao_item_id_set").val());

        $.ajax({
            url: AJAX_URL,
            data: query,
            type: "POST",
            dataType: "json",
            success: function (obj) {
                if (obj.status == -1) {
                	$.showErr(obj.info,function(){
                		if (obj.jump)
                        {
                            location.href = obj.jump;
                        }
                    });
                    return false;
                }
                if (obj.status == 1) {
                	// 添加成功，隐藏预选窗口	
                	$(".add-bg").hide();
         	        $(".add-to-list").removeClass('add-to-list-up');
                	
         	        // 恢复触摸
            		$("body").unbind("touchmove");
         	        
                    //增加购物车里面商品数量
                    if (obj.cart_item_num > 0) {
                    	// duobao页面的
                    	$(".goods-in-list").html(obj.cart_item_num);
                        $(".goods-in-list").show();
                    	
                        //首页的，填充购物车数值
                        $(".nav_cart_num").html(obj.cart_item_num);
                        $(".nav_cart_num").fadeIn(1000);
                        if(obj.cart_item_num>cur_num){
                            $(".nav_cart_num").addClass("nav_cart_num_zoom");
                            
                            setTimeout(function(){
                                $(".nav_cart_num").removeClass("nav_cart_num_zoom");
                            }, 200 );
                        }
                        
                        cur_num = obj.cart_item_num;
                        return false;
                    } else {
                    	// 首页
                        $(".nav_cart_num").hide();
                        
                        // 夺宝页
                        $(".goods-in-list").hide();
                    }
                    return false

                } else {

                    $.showErr(obj.info);
                    return false
                }
            }
        });
}

/*直购，加入购车事件*/
function add_total_buy_cart_item(obj){
    //请求服务端加入购物车表
    var query = new Object();
    query.act = "add_total_cart";
    query.buy_num = 1;
    query.data_id = parseInt( $(obj).attr('data_id') );
    $.ajax({
        url: AJAX_URL,
        data: query,
        type: "POST",
        dataType: "json",
        success: function (obj) {
            if (obj.status == -1) {
        		location.href=obj.jump;
            }else if (obj.status == 1) {
        		location.href=totalbuy_cart_url;
            }else{	                	
                $.showErr(obj.info);
            }	
        }
    });
}
