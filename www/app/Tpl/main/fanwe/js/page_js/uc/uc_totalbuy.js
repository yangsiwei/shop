$(document).ready(function(){
	
	$(".check_delivery[ajax='true']").bind("click",function(){
		return false;
	});
	$(".check_delivery[ajax='true']").hover(function(){
		var id = "delivery_box_"+$(this).attr("rel");
		$("#"+id).stopTime();
		var dom = $(this);
		if($("#"+id).length>0)
		{
			$("#"+id).show();
		}
		else
		{
			var kuaidi_type = $(this).attr("kuaidi_type");
			if(kuaidi_type == ''){
				var html = "<div id='"+id+"' class='check_delivery_pop'><div class='loading'></div></div>";
				var box = $(html);
				$("body").append(box);
				$(box).css({"position":"absolute","left":$(dom).position().left-80,"top":$(dom).position().top+20,"z-index":10});

				$.ajax({
					url:$(dom).attr("action"),
					type:"POST",
					dataType:"json",
					success:function(obj){
						if(obj.status)
						{
							$(box).html(obj.html);
						}
						else
						{
							$(box).remove();
						}
					}
				});
			}else if(kuaidi_type == 2){
				var html = "<div id='"+id+"' class='check_delivery_pop'><div class='loading'></div><div class='iframe'></iframe></div>";
				var box = $(html);
				$("body").append(box);
				$(box).css({"position":"absolute","left":$(dom).position().left-80,"top":$(dom).position().top+20,"z-index":10});
				

				$.ajax({
					url:$(dom).attr("action"),
					type:"POST",
					dataType:"json",
					success:function(obj){
						if(obj.status)
						{
							$(box).find(".iframe").hide();
							$(box).find(".iframe").html(obj.html);
							$("iframe[name='kuaidi100']").load(function(){
								$(box).find(".loading").hide();
								$(box).css({"position":"absolute","left":$(dom).position().left-394,"top":$(dom).position().top+16,"z-index":10});
								$(box).find(".iframe").show();
								$(box).removeClass("check_delivery_pop");
							});
						}
						else
						{
							$(box).remove();
						}
					}
				});
			}
			
		}
		$("#"+id).hover(function(){
			$("#"+id).stopTime();
			$("#"+id).show();
		},function(){
			$("#"+id).oneTime(300,function(){
				$("#"+id).remove();
			});
		});
	},function(){
		var id = "delivery_box_"+$(this).attr("rel");
		if($("#"+id).length>0)
		{
			$("#"+id).oneTime(300,function(){
				$("#"+id).remove();
			});
		}
		
	});
	
	w_menu();
	/**
	 * 确认收货
	 */
	$(".verify_delivery").bind("click",function(){
		var dom = $(this);
		$.showConfirm("确认进行收货操作吗？",function(){
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
						$.showConfirm("您已成功收货",function(){
							location.reload();
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

function w_menu()
{
	$(".m-user-comm-selectTitle").hover(function() {
		$("#w_menu").css("display","block");
	}, function() {
		$("#w_menu").css("display","none");
	});
};


function close_order(obj, order_id){
    var query = new Object();
	query.order_id = order_id;
	$.showConfirm("确定要关闭订单吗？",function(){
		$.ajax({
			url:url,
			data:query,
			dataType:"json",
			type:"POST",
			success:function(data){
				if(data.status==1){
					$(obj).parent().parent().html('订单已关闭');
				}
			}
		});
	}); 
	/*  if(window.confirm('你确定要取消交易吗？')){
		$.post(url, { "order_id": order_id }, function(data){
			alert(data);
			$(obj).parent().parent().html('订单已关闭');
		}, "json");
		
    }  */ 
	
}
