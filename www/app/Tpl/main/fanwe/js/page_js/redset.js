$(document).ready(function(){
	var list_num = $(".redset-list ul li").length;
	if (list_num<9) {
		$(".redset-list").css('height', 81*list_num+39);
	} else {
		$(".redset-list").css('height', '687px');
	}
	$("#ph_login_box").bind("submit",function(){
		
		var query = $(this).serialize();
		var ajax_url = $(this).attr("action");
		$.ajax({
			url:ajax_url,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status==1)
				{
					location.reload();
				}else if(obj.status==2){
					$(".evc-num").text(obj.info);
					$(".not-sub").hide();
				}
				else
				{
					$.showErr(obj.info);
				}
			}
		});

		return false;
	});
});
