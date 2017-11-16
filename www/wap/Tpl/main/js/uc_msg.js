$(document).ready(function () {
	$(".del_msg").bind("click",function(){
		var url=$(this).attr("action");
		$.showConfirm("确认要删除？",function(){
			del_msg(url);
		});
		
	});
	
 });  

function del_msg(url){
	var ajaxurl = url;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "GET",
		success: function(obj){
			if(obj.status==1){
					$.showSuccess("删除成功",function(){
						location.href = obj.url;	
					});	
							
			}else{
				$.showErr("删除失败");	
			}
		},
		error:function(ajaxobj)
		{
			
		}
	});	
}

