$(function(){
  init_duobao_cart();
  init_count_down();
  init_info_list();
});

function init_info_list(){
	if(cart_conf_json.min_buy!=10){
		  $('.tenyen').hide();
	}
	
	if( $("#banner_box").hasClass('has_img') ){
		  init_adv_list();
	}
	
	if($("#duobao_sn_list").find("dd").length>20){
		  $("#duobao_sn_list").attr("status","close");
		  $("#duobao_sn_list").css({"height":"140","overflow-y":"hidden"});
		  $("#func").bind("click",function(){
			  if($("#duobao_sn_list").attr("status")=="close")
			  {
				  $("#duobao_sn_list").css({"height":"auto","overflow-y":"hidden"});
				  $("#func").html("收起<i class='iconfont'>&#xe6c4;</i>");
				  $("#duobao_sn_list").attr("status","open");
			  }
			  else
			  {
				  $("#duobao_sn_list").css({"height":"140","overflow-y":"hidden"});
				  $("#func").html("展开<i class='iconfont'>&#xe6c3;</i>");
				  $("#duobao_sn_list").attr("status","close");
			  }
		  });
	}
	else
	{
		  $("#func").hide();
	}

}

function init_adv_list(){
	TouchSlide({
		slideCell:"#banner_box",
		titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航夺宝币素包裹层
		mainCell:".bd ul",
		effect:"leftLoop",
		autoPage:true,//自动分页
		autoPlay:true, //自动播放
		delayTime:750
	});
}
function init_count_down()
{
	var timespan = parseInt($(".w-countdown-nums:first").attr("nowtime")+"000") - new Date().getTime();
	$(".w-countdown-nums").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"%H:%M:%S:%MS",callback:function(){
			$(o).html("开奖中");
			$(o).everyTime(5000,function(){
				var duobao_item_id = $(o).attr("duobao_item_id");
				$.ajax({
					url: AJAX_URL,
		            data: {"act":"duobao_status","duobao_item_id":duobao_item_id},
		            type: "POST",
		            dataType: "json",
		            success: function (obj) {
		            	if(obj.status==1)
		            	{
		            		location.reload();
		            	}
		            }
				});
			});
		}});

	});
}



 
/*
*初始化购物车
*/
function init_duobao_cart(){
  if(cart_conf_json.residue_count){//没有库存了
      $(".joinin").show();
      $(".gotonew").hide();
  }else{

      $(".joinin").hide();
      $(".gotonew").show();
  }
 
  /*购物车显示的数值*/
  load_cart_data();

}


/*查询购物车内容*/
function load_cart_data(){
  if(cart_data_json){
    //增加购物车里面商品数量
    if(cart_data_json.cart_item_num>0){
      $(".goods-in-list").html(cart_data_json.cart_item_num);
      $(".goods-in-list").show();
    }else{
      $(".goods-in-list").hide();
    }
  }
}
