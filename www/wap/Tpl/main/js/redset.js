$(document).ready(function(){
	
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
					$(".money").text(obj.info);
					$(".login-btn-box").hide();
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
