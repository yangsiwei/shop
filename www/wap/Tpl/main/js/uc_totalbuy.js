$(document).ready(function(){
	$(".confirm_receipt").click(function(){
		var action = $(this).attr("action");
		$.showConfirm("您确定收货操作吗？？",function(){
			 $.ajax({
	            	url:action,
	    			data:{},
	    			dataType:"json",
	    			type:"POST",
	    			success:function(data){
	    				if(data.status == 1){
	    					window.location.reload();
	    				}else{
	    					alert(data.info);
	    				}
	    				
	    			}
	            })
		});
	});
	
	$(".fictitious_info").click(function(){
		var action = $(this).attr("action");
			$.confirm({
				'title'		: '虚拟商品信息',
				'message'	: action,
				'buttons'	: {
					'确定'	: {
						'class'	: 'gray',
						'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
			
	});
	
	
	$(".close-order").click(function(){
		var action = $(this).attr("action");
		$.showConfirm("您确定要关闭订单么？",function(){
			 $.ajax({
	            	url:action,
	    			data:{},
	    			dataType:"json",
	    			type:"POST",
	    			success:function(data){
	    				if(data.status == 1){
	    					window.location.reload();
	    				}else{
	    					alert(data.info);
	    				}
	    			}
	            })
		});
	});
	
	
	
});
 
