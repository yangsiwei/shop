$(function(){
	init_btn_status();


	$("#com_login_box").bind("submit",function(){
		if (is_lock) {
			return false;
		}
		var user_key = $.trim($(this).find("input[name='user_key']").val());
		var user_pwd = $.trim($(this).find("input[name='user_pwd']").val());
		if(user_key=="")
		{
			$.showErr("请输入登录帐号");
			return false;
		}
		if(user_pwd=="")
		{
			$.showErr("请输入密码");
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
						location.href = obj.jump;
				}
				else
				{
					$.showErr(obj.info);
				}
			}
		});

		return false;
	});



	$("#ph_login_box").bind("submit",function(){
		if (is_lock) {
			return false;
		}

		var mobile = $.trim($(this).find("input[name='mobile']").val());
		var sms_verify = $.trim($(this).find("input[name='sms_verify']").val());
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
						location.href = obj.jump;
				}
				else
				{
					$.showErr(obj.info);
				}
			}
		});

		return false;
	});

$(".swich").bind("click",function(){
	//alert($(".login-phone").is(":hidden"));
	// $(".login-normal").is(":hidden");
	if($(".login-phone").is(":hidden")){
		$(".login-phone").show();
		$(".login-normal").hide();
	}else{
		$(".login-phone").hide();
		$(".login-normal").show();
	}
});




});
var is_lock = true;

function init_btn_status(){
        if($(".login-phone").is(":hidden")){
            $(".login-btn-box .login-btn").addClass("login-btn-red");
                    is_lock = false;
        }
        
	$("input[name='user_pwd']").keyup(function(){

			if($(this).val().length>0 && $("input[name='user_key']").val().length>0){
				$(".login-btn-box .login-btn").addClass("login-btn-red");

				is_lock = false;
			}else{
				$(".login-btn-box .login-btn").removeClass("login-btn-red");
				is_lock = true;
			}
	});

	$("input[name='sms_verify']").keyup(function(){
			if($(this).val().length>0 && $("input[name='mobile']").val().length>=11){
				$(".login-btn-box .login-btn").addClass("login-btn-red");

				is_lock = false;
			}else{
				$(".login-btn-box .login-btn").removeClass("login-btn-red");
				is_lock = true;
			}

	});
}
