function receive_ecv(sn){
	var query = new Object();
	query.act = "do_snexchange";
	query.sn = sn;
	$.ajax({
		url:ajax_url,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status==1)
			{
				$.showSuccess(obj.info,function(){
					if(obj.jump){
						location.href = obj.jump;
					}else{
						$(".wrap").html(obj.html);
					}
					
				});
			}
			else
			{
				if(obj.info)
				{
					$.showErr(obj.info,function(){
							if(obj.status == -1){
								if(PAGE_TYPE=='app'){
									App.login();
								}else{
									location.href = obj.jump;
								}
							}else{
								$(".wrap").html(obj.html);
							}
						});
				}
				else
				{
					location.reload;
				}
				
			}
		}			
	});
}