$(document).ready(function(){
	 
	$(".exchange").bind("click",function(){
		var url=$(this).attr("url");
		$.showConfirm("确定要兑换吗？",function(){				
			exchange(url);
		});			
	});
	
	$("#sn_exchange").bind("submit",function(){
		if($.trim($(this).find("input[name='sn']").val())=="")
		{
			$.showErr("请输入序列号");
			return false;
		}
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		$.ajax({ 
			url: ajaxurl,
			data:query,
			dataType: "json",
			type: "POST",
			success: function(obj){
				if(obj.status==2){
					ajax_login();
				}else if(obj.status==1){
					$.showSuccess("兑换成功",function(){
						location.href = obj.jump;
					});				
				}else{
					$.showErr(obj.info,function(){
						location.reload();
					});
				}
			},
			error:function(ajaxobj)
			{
				
			}
		});		
		return false;
	});
	
});



function exchange(url){	
		var ajaxurl = url;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "GET",
			success: function(obj){
				if(obj.status==2){
					ajax_login();
				}else if(obj.status==1){
					$.showSuccess("兑换成功",function(){
						location.href = obj.jump;
					});				
				}else{
					$.showErr(obj.info,function(){
						location.reload();
					});
				}
			},
			error:function(ajaxobj)
			{
				
			}
		});		

}



