$(function(){
	var is_lock = true;
	init_btn_status();
	$("#ph_password_box").bind("submit",function(){

		var mobile = $.trim($(this).find("input[name='mobile']").val());
		var sms_verify = $.trim($(this).find("input[name='sms_verify']").val());
		var new_pwd = $.trim($(this).find("input[name='new_pwd']").val());
		var cfm_new_pwd = $.trim($(this).find("input[name='cfm_new_pwd']").val());
		if(new_pwd=="")
		{
			$.showErr("请输入新密码");
			return false;
		}
		if(new_pwd!=cfm_new_pwd)
		{
			$.showErr("新密码输入不匹配，请确认");
			return false;
		}
		if(mobile=="")
		{
			$.showErr("请输入手机号");
			return false;
		}
		if(sms_verify=="")
		{
			$.showErr("请输入收到的验证码");
			return false;
		}

		var query = $(this).serialize();
		var ajax_url = $(this).attr("action");
		$.ajax({
			url:ajax_url,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status)
				{
					$.showSuccess(obj.info,function(){
						location.href = obj.jump;
					});
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

function init_btn_status(){
	$("input[name='cfm_new_pwd']").keyup(function(){
			if($(this).val().length>0 && $("input[name='new_pwd']").val().length>0&& $("input[name='sms_verify']").val().length>0){
				$(".login-btn-box .login-btn").addClass("login-btn-red");
				is_lock = false;
			}else{
				$(".login-btn-box .login-btn").removeClass("login-btn-red");
				is_lock = true;
			}
	});

}
