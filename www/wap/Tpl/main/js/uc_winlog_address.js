function confirm_address(obj,consignee_id,order_item_id){
	 var query = new Object();
	 query.consignee_id = consignee_id;
	 query.order_item_id = order_item_id;
	 query.act="uc_luck_confirm_address";
	 $.showConfirm("确定选择这个地址吗？",function(){
		$.ajax({
		url:main_url,
		data:query,
		dataType:"json",
		type:"POST",
		success:function(data){
			if(data.user_login_status)
			{
				location.href = $data.jump;
			}
			else if(data.status)
			{
				location.href = next_url;
			}
			else
			{
				$.showErr(data.info);
			}
		}
		});
	});
	return false;
}