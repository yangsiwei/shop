$(document).ready(function(){
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

});