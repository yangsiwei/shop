$(function(){

	//切换配送地址
	$(".address-list .alt").click(function() {
		$(".alt").removeClass('checked');
		$(this).addClass('checked');
	});
	
	//确定配送地址
	$("#confirm_address").bind("click",function(){
		if($(".address-list .checked").attr("data-id")==null){
			$.showErr("请选择一个配送地址");
			return false;
		}


		$(".address-weebox-content .aw-info .addr-item:nth-child(1)").find("span").html($(".address-list .checked td:nth-child(1)").html());
		$(".address-weebox-content .aw-info .addr-item:nth-child(2)").find("span").html($(".address-list .checked td:nth-child(3)").html());
		$(".address-weebox-content .aw-info .addr-item:nth-child(3)").find("span").html($(".address-list .checked td:nth-child(2)").html());

		$.weeboxs.open($(".confirm-address-weebox").html(), {boxid:'confirm-address-weebox',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'确认收货地址',width:350,type:'wee',onopen:function(){
			init_ui_button();
			init_ui_textbox();
		},onok:function(){
			 var query = new Object();
			 query.consignee_id = $(".address-list .checked").attr("data-id");
			 query.order_item_id = $("input[name='order_item_id']").val();
			 query.act="uc_luck_confirm_address";
			 $.weeboxs.close("confirm-address-weebox");
			 $.ajax({
			 	url:AJAX_URL,
			 	data:query,
			 	dataType:"json",
			 	type:"POST",
			 	success:function(obj){
			 		if(obj.status==1000)
			 		{
			 			ajax_login();
			 		}
			 		else if(obj.status)
			 		{
			 			location.reload();
			 		}
			 		else
			 		{
			 			$.showErr(obj.info);
			 		}
			 	}
			 });
		}});

	});
	
	//确认收货
	/**
	 * 确认收货
	 */
	$(".verify_delivery").bind("click",function(){
		var dom = $(this);
		$.showConfirm("确定已经收到快递发来的货物了吗？",function(){
			$.ajax({
				url:$(dom).attr("action"),
				type:"POST",
				dataType:"json",
				success:function(obj){
					if(obj.status==1000)
					{
						ajax_login();
					}
					else if(obj.status==1)
					{
						$.showConfirm("您已成功收货，立即去晒单吗？",function(){
							location.href = obj.share_url;
						},function(){
							location.reload();
						});
					}
					else
					{
						$.showErr(obj.info);
					}
				}
			});
		});
		return false;
	});
	$(".detail").bind("click",function(){
		var dom = $(this);
		$.showConfirm("虚拟商品确认后无法更改哦！",function(){
			$.ajax({
				url:$(dom).attr("action"),
				type:"POST",
				dataType:"json",
				success:function(obj){
					if(obj.status==1000)
					{
						ajax_login();
					}
					else if(obj.status==1)
					{
						$.showConfirm("您已成功收货，立即去晒单吗？",function(){
							location.href = obj.share_url;
						},function(){
							location.reload();
						});
					}
					else
					{
						$.showErr(obj.info);
					}
				}
			});
		});
		return false;
	});
});


function luck_add_consignee(){
	$(".uc_luck_add_consignee").show();
}
