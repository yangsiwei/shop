
// 预选购买数量
$(document).ready(function () {
	 init_change_cart_num();
	 init_get_buy_num();
});

function init_get_buy_num(){
	$(".right-box").live("click", function(){
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
                	
                	if(data.min_buy >= 10){
                		$('.price-btn').eq(0).addClass("disable");
                	}else{
                		$('.price-btn').eq(0).removeClass("disable");
                	}
                	
                	if(data.min_buy == 1){
                		$("input[name='choose_item_number']").val(1);
                		$('.money-info span').text(1);
                	}else{
                		$("input[name='choose_item_number']").val(data.min_buy);
                		$('.money-info span').text(data.min_buy);
                	}
                	
                	
                }
        });
       
        
    });
	// 关闭预选包尾窗口
    $(".btn-close").click(function() {
        $(".add-bg").hide();
        $(".add-to-list").removeClass('add-to-list-up');
    });
    
    $(".add-bg").click(function() {
        $(".add-bg").hide();
        $(".add-to-list").removeClass('add-to-list-up');
    });
}

function init_change_cart_num(){
	// 输入变
	$("input[name='choose_item_number']").bind("focusout",function(){ 
		get_last_num($(this));
	});
	
	$("input[name='choose_item_number']").bind("focusin",function(){ 
		get_last_num($(this));
	});
	
	$('.price-btn').bind("click", function () {
		
		// 判断是否可以选择
		if($(this).hasClass('disable')){
			return false;
		}
		
		var number_item;
		var choose_number = $(this).text();
		$(this).addClass("all-in").siblings().removeClass("all-in");
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
                	var data = dataObj.duobao_number;
                	
                	var can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
                	if(isNaN(choose_number)){  // 如果不是数字，则是“我包了”，显示所有剩余量
                		number_item = can_buy;
                	}else if(choose_number > can_buy){
                		number_item = can_buy;
                	}else{
                		number_item = choose_number;
                	}
                	$('.money-info span').text(number_item);
                	$("input[name='choose_item_number']").val(number_item);
                	
                	
                	$("input[name='choose_item_number']").css("font-size", '0.8rem');
                     
                     setTimeout(function(){
                    	 $("input[name='choose_item_number']").css("font-size", '0.5rem');
                     }, 200 );
                	
                }
        });
		
	});
	
	
	// +
	$('.index-add-cart-num').bind("click", function () {
		var number_item;
		var choose_number = $(this).text();
		var can_buy;
		
		$('.price-btn').removeClass("all-in");
		
		// 原先的数值
		var org_number =  $("input[name='choose_item_number']").val();
		
		
		var query = new Object();
		query.data_id = $("#duobao_item_id_set").val();
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
                	var data = dataObj.duobao_number;
                	
                	can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
                	number_item = parseFloat(org_number) + parseFloat(data.min_buy);
                	if(number_item > can_buy){
                		number_item = can_buy;
                	} 
                	$('.money-info span').text(number_item);
                	$("input[name='choose_item_number']").val(number_item);
                	
                	$("input[name='choose_item_number']").css("font-size", '0.8rem');
                    setTimeout(function(){
                   	 $("input[name='choose_item_number']").css("font-size", '0.5rem');
                    }, 200 );
                }
        });
	});
	
	// -
	$('.index-subtract-cart-num').bind("click", function () {
		var number_item;
		var choose_number = $(this).text();
		var can_buy;
		
		$('.price-btn').removeClass("all-in");
		
		// 原先的数值
		var org_number =  $("input[name='choose_item_number']").val();
		
		
		var query = new Object();
		query.data_id = $("#duobao_item_id_set").val();
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
                	var data = dataObj.duobao_number;
                	
                	can_buy = parseFloat(data.max_buy) - parseFloat(data.current_buy);
                	number_item = parseFloat(org_number) - parseFloat(data.min_buy);
                	if(number_item > can_buy){
                		number_item = can_buy;
                	}
                	if(number_item < 1){
                		number_item = data.min_buy;
                	}
                	$('.money-info span').text(number_item);
                	$("input[name='choose_item_number']").val(number_item);
                	
                	$("input[name='choose_item_number']").css("font-size", '0.8rem');
                    setTimeout(function(){
                   	 $("input[name='choose_item_number']").css("font-size", '0.5rem');
                    }, 200 );
                }
        });
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
                		location.href = pay_url;
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
                	}
                }
        });
	});
}

// 计算输入的值
function get_last_num(num_obj){
	$('.price-btn').removeClass("all-in");
	// 修改的值
	var mod_val = 0;
	var change_num = num_obj.val();
	var query = new Object();
	query.data_id = $("#duobao_item_id_set").val();
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
           	  var data = dataObj.duobao_number;
	    	   
		       	if(parseInt(change_num) > parseInt(data.min_buy)){
		       		var set_num = '';
		       		var cur_max_num = data.max_buy - data.current_buy;
		       		if(change_num < data.min_buy){
		       			set_num = data.min_buy;
		       		}else if(change_num > cur_max_num){
		       			set_num = cur_max_num;
		       		}else{
		       			mod_val = change_num % data.min_buy;
		           		if(mod_val > 0){
		               		set_num = Math.floor(change_num / data.min_buy) * data.min_buy;
		               	}
		       		}
		       		
		       		if(set_num > 0){
		       			$("input[name='choose_item_number']").val(set_num);
		       			$('.money-info span').text(set_num);
		       		}
		       		
		       	}else{
		       		$("input[name='choose_item_number']").val(data.min_buy);
		       		$('.money-info span').text(data.min_buy);
		       	}
		       	
		       	$('.money-info span').text($("input[name='choose_item_number']").val());
	       	 
	       }
	});

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