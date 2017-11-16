$(document).ready(function(){

	$(".do_verify").bind("click",function(){	
		if(confirm("确认该项操作吗？"))
		{
			var action = $(this).attr("action");
			var query = new Object();
			query.ajax = 1;
			$.ajax({
				url:action,
				type:"POST",
				data:query,
				dataType:"json",
				success:function(obj){
					if(obj.status)
					{
						alert(obj.info);
						location.reload();
					}
					else
					{
						alert(obj.info);
					}
					
				}
			});
			
		}
		
	});
	
});

