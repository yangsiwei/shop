$(document).ready(function(){
	$("form[name='account_form']").bind("submit",function(){

		var user_name = $.trim($(this).find("input[name='user_name']").val());
		var name_rul = /^[\w\u4e00-\u9fa5]+$/;
		var email = $.trim($(this).find("input[name='user_email']").val());
        var is_phone_register = $.trim($(this).find("input[name='is_phone_register']").val());
        if(is_phone_register == 0){
            var user_pwd = $.trim($(this).find("input[name='user_pwd']").val());
            var cfm_user_pwd = $.trim($(this).find("input[name='cfm_user_pwd']").val());
        }
        
        if(user_name==""){
        	$.showErr("用户名不能为空");
			return false;
		}
		else if(! name_rul.test(user_name)){
			$.showErr("您输入的用户名不合法！");
        	return false;
        }
        
		if(email)
		{
			var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			if(!myreg.test(email))
			{
				$.showErr("请输入正确的邮箱地址");
				return false;
			}

		}
        if(is_phone_register == 0){
            if(user_pwd=="")
            {
                    $.showErr("请输入密码");
                    return false;
            }
            if(user_pwd!=cfm_user_pwd)
            {
                    $.showErr("密码输入不匹配，请确认");
                    return false;
            }
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
						if(obj.jump)
						location.href = obj.jump;
					});
				}
				else
				{
					$.showErr(obj.info,function(){
						if(obj.jump)
						location.href = obj.jump;
					});
				}
			}
		});

		return false;
	});
	
});

// function init_btn_status(){
//     $("input[name='cfm_user_pwd']").keyup(function(){
//                     var is_phone_register = $("input[name='is_phone_register']").val();
//                     if($(this).val().length>=4 && is_phone_register==0 && $("input[name='user_name']").val().length>0 && $("input[name='user_email']").val().length>0 && $("input[name='user_pwd']").val().length>=4){
//                             $(".login-btn-box .login-btn").addClass("login-btn-red");
//
//                             is_lock = false;
//                     }else{
//                             $(".login-btn-box .login-btn").removeClass("login-btn-red");
//                             is_lock = true;
//                     }
//     });
//
//     $("input[name='user_email']").keyup(function(){
//         var is_phone_register = $("input[name='is_phone_register']").val();
//                     if($(this).val().length>0 && $("input[name='user_name']").val().length>0 && is_phone_register==1){
//                             $(".login-btn-box .login-btn").addClass("login-btn-red");
//
//                             is_lock = false;
//                     }else{
//                             $(".login-btn-box .login-btn").removeClass("login-btn-red");
//                             is_lock = true;
//                     }
//
//     });
// }
