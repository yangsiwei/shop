$(document).ready(function(){
	
	
	$("#register_box").bind("submit",function(){
		

		var mobile = $.trim($(this).find("input[name='mobile']").val());
		var user_pwd = $.trim($(this).find("input[name='user_pwd']").val());
                var sms_verify = $.trim($(this).find("input[name='sms_verify']").val());
                if(mobile=="")
		{
			$.showErr("请输入手机号");
			return false;
		}
		if(mobile.length<11)
		{
			$.showErr("手机号格式错误");
			return false;
		}
                if(sms_verify=="")
		{
			$.showErr("请输入短信验证码");
			return false;
		}
		if(user_pwd=="")
		{
			$.showErr("请输入密码");
			return false;
		}
		if(user_pwd.length<4)
		{
			$.showErr("密码长度不符");
			return false;
		}
		var query = $(this).serialize();
		var ajax_url = $(this).attr("action");
		if(!is_lock){
			is_lock=true;
			change_btn_class();
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
						$.showErr(obj.info,function(obj){
							is_lock=false;
							change_btn_class();
							});
					}
				}
			});
			return false;
		}
	});
	
	
});
var is_lock=false;
function change_btn_class(){
	if(!is_lock){
		$(".login-btn-box .login-btn").addClass("login-btn-red");
	}else{
		$(".login-btn-box .login-btn").removeClass("login-btn-red");
	}
}
