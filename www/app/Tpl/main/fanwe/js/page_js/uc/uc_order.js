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
						$.showConfirm("您已成功收货，立即去点评吗？",function(){
							location.href = obj.dp_url;
						},function(){
							location.reload();
						});
					}
					else
					{
						$.showErr("收货失败");
					}
				}
			});
		});
		return false;
	});
	
	
	/**
	 * 没收到货
	 */
	$(".refuse_delivery").bind("click",function(){
		var dom = $(this);
		$.showConfirm("没收到货吗？确定提交维权订单吗？",function(){
			$.weeboxs.open(refuse_delivery_form_html, {boxid:'refuse_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'没收到货',width:250,type:'wee',onopen:function(){
				init_ui_button();
				init_ui_textbox();
			},onok:function(){
				var content = $("#refuse_box").find("textarea[name='content']").val();
				var query = new Object();
				query.content = content;
				$.ajax({
					url:$(dom).attr("action"),
					data:query,
					type:"POST",
					dataType:"json",
					success:function(obj){
						$.weeboxs.close("refuse_box");
						if(obj.status==1000)
						{
							ajax_login();
						}
						else if(obj.status==1)
						{							
							$.showSuccess("维权订单已提交，请等待管理员审核",function(){
								location.reload();
							});
						}
						else
						{
							$.showErr(obj.info);
						}
					}
				});
					
			}});
			return false;
			
			
			
		});
		return false;
	});
	
	
	$(".del_order").bind("click",function(){
		var dom = $(this);
		$.showConfirm("确定要删除本订单吗？",function(){
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
						$.showSuccess("订单删除成功！",function(){
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
	
	
	$(".refund").bind("click",function(){
		var dom = $(this);
		$.showConfirm("确定要申请退款吗？",function(){
			$.ajax({
				url:$(dom).attr("action"),
				type:"POST",
				dataType:"json",
				success:function(obj){
					if(obj.status==1000)
					{
						ajax_login();
					}
					else if(obj.status)
					{
						$.weeboxs.open(obj.html, {boxid:'refund_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'退款申请',width:250,type:'wee',onopen:function(){
							init_ui_button();
							init_ui_textbox();
						},onok:function(){
							var form = $("form[name='refund_form']");
							var query = $(form).serialize();
							$.weeboxs.close("refund_box");
							$.ajax({
								url:$(form).attr("action"),
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
										$.showSuccess(obj.info,function(){
											location.reload();
										});
									}
									else
									{
										$.showErr(obj.info);
									}
								}
							});
						}});
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
	
	
	$(".send_coupon").bind("click",function(){
		var dom = $(this);
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
					IS_RUN_CRON = 1;
					$.showSuccess(obj.info,function(){
						location.reload();
					});
				}
				else
				{
					$.showErr(obj.info,function(){
						if(obj.jump)
						{
							location.href = obj.jump;
						}
					});
				}
			}
		});
	});
});