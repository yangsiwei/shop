$(function(){
	//图片滚动
	init_pic_move();
	function init_pic_move(){
		$(".small-pic-wrap li").eq(0).mouseover(function() {
			$(".small-pic-item").removeClass('small-pic-current');
			$(this).addClass('small-pic-current');
			$(".ico-arrow-red").css('left', '31px');
			$(".goods-pic img").attr("src",$(this).children().attr('src'));
		});
		$(".small-pic-wrap li").eq(1).mouseover(function() {
			$(".small-pic-item").removeClass('small-pic-current');
			$(this).addClass('small-pic-current');
			$(".ico-arrow-red").css('left', '117px');
			$(".goods-pic img").attr("src",$(this).children().attr('src'));
		});
		$(".small-pic-wrap li").eq(2).mouseover(function() {
			$(".small-pic-item").removeClass('small-pic-current');
			$(this).addClass('small-pic-current');
			$(".ico-arrow-red").css('left', '204px');
			$(".goods-pic img").attr("src",$(this).children().attr('src'));
		});
		$(".small-pic-wrap li").eq(3).mouseover(function() {
			$(".small-pic-item").removeClass('small-pic-current');
			$(this).addClass('small-pic-current');
			$(".ico-arrow-red").css('left', '290px');
			$(".goods-pic img").attr("src",$(this).children().attr('src'));
		});
		$(".small-pic-wrap li").eq(4).mouseover(function() {
			$(".small-pic-item").removeClass('small-pic-current');
			$(this).addClass('small-pic-current');
			$(".ico-arrow-red").css('left', '377px');
			$(".goods-pic img").attr("src",$(this).children().attr('src'));
		});
	}
	
	$(".num-btn-min").bind("click",function(){
		
		init_num_change($(this),-1);
	});
	$(".num-btn-plus").bind("click",function(){
		init_num_change($(this),1);
	});
	
});
$(function(){
	//购买数量选择
	init_buy_list();
	function init_buy_list(){
		$(".buy-list li a").click(function() {
			$(".buy-list li a").removeClass('active');
			$(this).addClass('active');
		});
	}
});
$(function(){
	//鼠标悬停在input上，自动focus并选中其中内容。
	init_num_input();
	init_num_blur();
	function init_num_input(){
		$(".num-input").mouseover(function() {
			$(this).focus().select();
		});
	}
	function init_num_blur()
	{
		$(".num-input").blur(function() {
			var min_buy = $(this).attr("min_buy");
			var current_buy = $(this).val();
			if(isNaN(current_buy)||current_buy<min_buy)
			{
				current_buy = min_buy;
			}
			else
			{
				current_buy =  Math.ceil(current_buy/min_buy)*min_buy;
			}
			$(this).val(current_buy);
		});
	}
});
$(function(){
	//选项卡切换
	init_tab_item();
	function init_tab_item(){
		$(".tab-item").click(function() {
			$(".tab-item").removeClass('tab-item-selected');
			$(this).addClass('tab-item-selected');
			$(".tab-info-item").hide();
		});
		$("#intro-tab").click(function() {
			$("#intro-info").show();
		});
		$("#result-tab").click(function() {
			$("#result-info").show();
		});
		$("#record-tab").click(function() {
			if($.trim($("#record-info").html())=="" ){
				duobao_record_page(duobao_item_id,1,2);
			}
			$("#record-info").show();
		});
		$("#share-tab").click(function() {
			if($.trim($("#share-info").html())=="" ){
				duobao_share_page(duobao_item_id,1,2);
			}
			$("#share-info").show();
		});
		$("#history-tab").click(function() {
			if($.trim($("#history-info").html())=="" ){
				used_item_data_page(duobao_item_id,1,2);
			}
			$("#history-info").show();
		});
	}
});
$(function(){
	//点击显示所有号码相关脚本
	init_check_codes();
	
});
function init_check_codes(){
	//鼠标移入显示展开号码，移出消失
	$(".record-user-info").hover(function() {
		$(this).find('.btn-checkcode').addClass('btn-checkcoded');
	}, function() {
		$(this).find('.btn-checkcode').removeClass('btn-checkcoded');
	});
	//点击展开号码
	$(".btn-checkcode").click(function() {
		$(this).removeClass('btn-checkcoded').parents('.record-user-info').addClass('record-user-info-detail');
		//如果号码超过一定数量，隐藏多出的号码
		if ($(this).parents().find('.codes').height()>185){
				$(this).parents(".inner").find('.codes').css('height', '185px');
				$(this).parents(".inner").find('.folder-btn').show();
				$(this).parents(".inner").find('.btn-unfold').show();
		};
	});
	//点击展开所有号码
	$(".btn-unfold").click(function() {
		$(this).parents(".inner").find('.codes').css('height', 'auto');
		$(this).parents(".inner").find('.btn-unfold').hide();
		$(this).parents(".inner").find('.btn-fold').show();
	});
	//点击关闭所有号码
	$(".btn-fold").click(function() {
		$(this).parents(".inner").find('.codes').css('height', '185px');
		$(this).parents(".inner").find('.btn-fold').hide();
		$(this).parents(".inner").find('.btn-unfold').show();
	});
	//关闭展开号码
	$(".btn-close").click(function() {
		$(this).parents(".record-user-info-detail").removeClass('record-user-info-detail');
		$(this).parents(".inner").find('.codes').css('height', 'auto');
		$(this).parents(".inner").find('.folder-btn').hide();
	});
}
$(function(){
	init_count_dow();
function init_count_dow()
{
	var timespan = parseInt($(".countdown-num:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".countdown-num").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"<b>%BM</b><b>%SM</b>:<b>%BS</b><b>%SS</b>:<b>%BMS</b><b>%SMS</b>",callback:function(){
			var url = main_url;
			var query = new Object();
			query.ctl = "duobao";
			query.act = "init_count_dow";
			query.id =$(o).attr("item_data_id");
			$.ajax({
				url: url,
				type: "POST",
				data:query,
				dataType: "json",
				success: function(data){
					$("#countdownnum_flush").html(data.html);
					//init_other_layer();
					$(".tab-result-list .result-row").html(data.countdown_tip);
					$("#layer").html(data.layer);
					
				},
				 error:function(o){
				 
				}
			});
		}});

	});
}
});
$(function(){
	countdown_init_count_dows();

});


function countdown_init_count_dows()
{
	var timespan = parseInt($(".countdown-nums:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".countdown-nums").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"<b>%BM</b><b>%SM</b>:<b>%BS</b><b>%SS</b>:<b>%BMS</b><b>%SMS</b>",callback:function(){
			var url = main_url;
			var query = new Object();
			query.ctl = "duobao";
			query.act = "used_item_data_countdown";
			query.data_id =$(o).attr("id");
			query.page =$(o).attr("page");
			query.type =$(o).attr("type");
			$.ajax({
				url: url,
				type: "POST",
				data:query,
				dataType: "json",
				success: function(data){
					$("#"+$(o).attr("id")).parents("li").html(data.html);

				},
				error:function(o){
						 
				}
			});
		}});

	});
}

/*加入购车事件*/
function add_cart_item(obj,type){
    var btn_item = $(obj);

        var buy_num = parseInt($(".buy-num-count input[name='num']").val());
        //请求服务端加入购物车表
        var query = new Object();
        query.act = "addcart";
        query.buy_num = buy_num;
        query.data_id = parseInt($(obj).attr('data_id'));
        $.ajax({
            url: AJAX_URL,
            data: query,
            type: "POST",
            dataType: "json",
            success: function (obj) {

                if(type==1){
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
                		location.href=cart_url;
	                }else{	                	
	                    $.showErr(obj.info);
	                }	
                }else{
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
	                	var img_obj=$(".goods-detail .goods-pic-info .goods-pic img");
	                   	var left=$(img_obj).offset().left;
	                	var top=$(img_obj).offset().top;
	                	var cart_left=$(".cart_tip .cart_count").offset().left;
	                	var cart_top=$(".cart_tip .cart_count").offset().top;
	                	var float_nav_top=$(".float_nav_bar").offset().top;
	                	var img_clone=img_obj.clone();
	                	$('body').append(img_clone);
	                	
	                	img_clone.css({'position':'absolute','left':left,'top':top,'z-index':10000});
	                	
	                	if($(".float_nav_bar").is(":hidden")){
	                     	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:cart_top+10},500,function(){
	                    		$(img_clone).remove();	
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    	});
	                	}else{
	         
	                    	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:float_nav_top+25},500,function(){
	                    		$(img_clone).remove();	
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    	});
	                	}
	                	
	
	                	
	                } else {
	
	                    $.showErr(obj.info);
	   
	                }
                }
            }
        });
}


function init_num_change(o,type){
	
	  var input_ele=$(o).siblings("input[name='num']");
	  var buy_num = parseInt($(input_ele).val());
	  var min_buy = parseInt($(input_ele).attr("min_buy"));
	  var init_num = parseInt($(input_ele).attr("init_num"));
	  var surplus_buy = o.attr('surplus-buy');
	  
	  
      buy_num=buy_num+min_buy*type;
      
      if(buy_num > surplus_buy){
    	  buy_num = surplus_buy;
      }
      
      if(buy_num>0){
    	  $(input_ele).val(buy_num);
    	  if(init_num==1){
    		 $(input_ele).parents(".goods-number").siblings(".btn-box").find(".cate-duobao-now").attr("buy_num",buy_num);
    		 $(input_ele).parents(".goods-number").siblings(".btn-box").find(".add-to-list").attr("buy_num",buy_num);
    	  }
      }
}

function used_item_data_pages(){
	$('.pages a').click(function(){
		if( isNaN( $(this).html() ) ){
			if($.trim($(this).html())=='下一页'){
				used_item_data_page(duobao_item_id,parseFloat(new_p)+1,1);
			}
			if($.trim($(this).html())=='上一页'){
				used_item_data_page(duobao_item_id,parseFloat(new_p)-1,1);
			}
			if($.trim($(this).html())=='下5页'){
				if(parseFloat(new_p)+5>max_page){
					used_item_data_page(duobao_item_id,max_page,1);
				}else if(parseFloat(new_p)+5<=max_page){
					used_item_data_page(duobao_item_id,parseFloat(new_p)+5,1);
				}
			}
			if($.trim($(this).html())=='上5页'){
				if(parseFloat(new_p)-5<1){
					used_item_data_page(duobao_item_id,1,1);
				}else if(parseFloat(new_p)-5>=1){
					used_item_data_page(duobao_item_id,parseFloat(new_p)-5,1);
				}
			}
			if($.trim($(this).html())=='第一页'){
				used_item_data_page(duobao_item_id,1,1);
			}
			if($.trim($(this).html())=='最后一页'){
				used_item_data_page(duobao_item_id,max_page,1);
			}
		}else{
			used_item_data_page(duobao_item_id,$(this).html(),1);
		}
		return false;
	});
}
function used_item_data_page(data_id,page,type){
	var url = main_url;
	var query = new Object();
	query.ctl = "duobao";
	query.act = "used_item_data_page";
	query.data_id =data_id;
	query.p =page;
	$.ajax({
		url: url,
		type: "POST",
		data:query,
		dataType: "json",
		success: function(data){
			$("#history-info").html(data.html);
			countdown_init_count_dows();
			used_item_data_pages();
			if(type==1){
				$("html,body").animate({scrollTop:$(".share-to").offset().top},0);
			}
		},
		error:function(o){
				 
		}
	});
}
function duobao_record_pages(){
	$('.pages a').click(function(){
		if( isNaN( $(this).html() ) ){
			if($.trim($(this).html())=='下一页'){
				duobao_record_page(duobao_item_id,parseFloat(new_p)+1,1);
			}
			if($.trim($(this).html())=='上一页'){
				duobao_record_page(duobao_item_id,parseFloat(new_p)-1,1);
			}
			if($.trim($(this).html())=='下5页'){
				if(parseFloat(new_p)+5>max_page){
					duobao_record_page(duobao_item_id,max_page,1);
				}else if(parseFloat(new_p)+5<=max_page){
					duobao_record_page(duobao_item_id,parseFloat(new_p)+5,1);
				}
			}
			if($.trim($(this).html())=='上5页'){
				if(parseFloat(new_p)-5<1){
					duobao_record_page(duobao_item_id,1,1);
				}else if(parseFloat(new_p)-5>=1){
					duobao_record_page(duobao_item_id,parseFloat(new_p)-5,1);
				}
			}
			if($.trim($(this).html())=='第一页'){
				duobao_record_page(duobao_item_id,1,1);
			}
			if($.trim($(this).html())=='最后一页'){
				duobao_record_page(duobao_item_id,max_page,1);
			}
		}else{
			duobao_record_page(duobao_item_id,$(this).html(),1);
		}
		return false;
	});
}
function duobao_record_page(data_id,page,type){
	var url = main_url;
	var query = new Object();
	query.ctl = "duobao";
	query.act = "duobao_record_page";
	query.data_id =data_id;
	query.p =page;
	$.ajax({
		url: url,
		type: "POST",
		data:query,
		dataType: "json",
		success: function(data){
			$("#record-info").html(data.html);
			init_check_codes();
			duobao_record_pages();
			if(type==1){
				$("html,body").animate({scrollTop:$(".share-to").offset().top},0);
			}
		},
		error:function(o){
				 
		}
	});
}
function duobao_share_pages(){
	$('.pages a').click(function(){
		if( isNaN( $(this).html() ) ){
			if($.trim($(this).html())=='下一页'){
				duobao_share_page(duobao_item_id,parseFloat(new_p)+1,1);
			}
			if($.trim($(this).html())=='上一页'){
				duobao_share_page(duobao_item_id,parseFloat(new_p)-1,1);
			}
			if($.trim($(this).html())=='下5页'){
				if(parseFloat(new_p)+5>max_page){
					duobao_share_page(duobao_item_id,max_page,1);
				}else if(parseFloat(new_p)+5<=max_page){
					duobao_share_page(duobao_item_id,parseFloat(new_p)+5,1);
				}
			}
			if($.trim($(this).html())=='上5页'){
				if(parseFloat(new_p)-5<1){
					duobao_share_page(duobao_item_id,1,1);
				}else if(parseFloat(new_p)-5>=1){
					duobao_share_page(duobao_item_id,parseFloat(new_p)-5,1);
				}
			}
			if($.trim($(this).html())=='第一页'){
				duobao_share_page(duobao_item_id,1,1);
			}
			if($.trim($(this).html())=='最后一页'){
				duobao_share_page(duobao_item_id,max_page,1);
			}
		}else{
			duobao_share_page(duobao_item_id,$(this).html(),1);
		}
		return false;
	});
}
function duobao_share_page(data_id,page,type){
	var url = main_url;
	var query = new Object();
	query.ctl = "duobao";
	query.act = "duobao_share_page";
	query.data_id =data_id;
	query.p =page;
	$.ajax({
		url: url,
		type: "POST",
		data:query,
		dataType: "json",
		success: function(data){
			$("#share-info").html(data.html);
			init_check_codes();
			duobao_share_pages();
			if(type==1){
				$("html,body").animate({scrollTop:$(".share-to").offset().top},0);
			}
		},
		error:function(o){
				 
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
        		ajax_login();
            }else if (obj.status == 1) {
        		location.href=totalbuy_cart_url;
            }else{	                	
                $.showErr(obj.info);
            }	
        }
    });
}