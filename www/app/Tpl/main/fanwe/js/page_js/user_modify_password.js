$(document).ready(function(){
	init_modify_password_panel();
});


/*初始化重置密码表单*/
function init_modify_password_panel()
{	
	
	$("#modify_password_form").each(function(k,modify_password_form){
		if(!$(modify_password_form).find("input[name='user_pwd']").attr("bindkeyup"))
		{
			$(modify_password_form).find("input[name='user_pwd']").attr("bindkeyup",true);
			$(modify_password_form).find("input[name='user_pwd']").bind("keyup",function(){
				var rel = checkPwdFormat($(this).val());
				$(modify_password_form).find(".pwd_chk_line li").removeClass("act");
				$(modify_password_form).find(".pwd_chk_line li[rel='"+rel+"']").addClass("act");
			});
		}
	});
	
	
	$("#modify_password_form").each(function(k,modify_password_form){
		if(!$(modify_password_form).find("input[name='user_pwd']").attr("bindfocus"))
		{
			$(modify_password_form).find("input[name='user_pwd']").attr("bindfocus",true);		
			$(modify_password_form).find("input[name='user_pwd']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	$("#modify_password_form").each(function(k,modify_password_form){
		if(!$(modify_password_form).find("input[name='user_pwd']").attr("bindblur"))
		{
			$(modify_password_form).find("input[name='user_pwd']").attr("bindblur",true);
			$(modify_password_form).find("input[name='user_pwd']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入密码");
				}
			});
		}
	});
	
	
	$("#modify_password_form").each(function(k,modify_password_form){
		if(!$(modify_password_form).find("input[name='user_pwd_confirm']").attr("bindfocus"))
		{
			$(modify_password_form).find("input[name='user_pwd_confirm']").attr("bindfocus",true);		
			$(modify_password_form).find("input[name='user_pwd_confirm']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	$("#modify_password_form").each(function(k,modify_password_form){
		if(!$(modify_password_form).find("input[name='user_pwd_confirm']").attr("bindblur"))
		{
			$(modify_password_form).find("input[name='user_pwd_confirm']").attr("bindblur",true);
			$(modify_password_form).find("input[name='user_pwd_confirm']").bind("blur",function(){
				var txt = $(this).val();
				var pwd = $(modify_password_form).find("input[name='user_pwd']").val();
				
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入确认密码");
				}
				else if($.trim(txt)!=$.trim(pwd))
				{
					form_err($(this),"您两次输入的密码不匹配");
				}
				else
				{
					form_success($(this),"");
				}
			});
		}
	});
	
		
	//对表单的提交ajax绑定
	$("#modify_password_form").find(".modify_password").each(function(i,form){
		if(!$(form).attr("bindsubmit"))
		{
			$(form).attr("bindsubmit",true);
			$(form).bind("submit",function(){
				
				//验证字段
				var user_pwd = $(form).find("input[name='user_pwd']");
				if($.trim(user_pwd.val())=="")
				{
					form_tip(user_pwd,"请输入密码");
					return false;
				}
				
				var user_pwd_confirm = $(form).find("input[name='user_pwd_confirm']");
				if($.trim(user_pwd_confirm.val())=="")
				{
					form_tip(user_pwd_confirm,"请输入确认密码");
					return false;
				}
				if($.trim(user_pwd_confirm.val())!=$.trim(user_pwd.val()))
				{
					form_err(user_pwd_confirm,"您两次输入的密码不匹配");
					return false;
				}
				
				var url = $(form).attr("action");
				var query = $(form).serialize();				
				$.ajax({
					url: url,
					type: "POST",
					data:query,
					dataType: "json",
					success: function(data){
						if(data.status)
		    		    {
							$.showSuccess(data.info,function(){
								location.href = data.jump;		
							});							    		    	
		    		    }
		    		    else
		    		    {
		    		    	$(form).find(".verify").click();
		    		    	if(data.field)
		    		    	{
		    		    		form_err($(form).find("input[name='"+data.field+"']"),data.info);
		    		    	}
		    		    	else
		    		    	$.showErr(data.info);
		    		    }
					}
				});
				
				return false;
			});
		}
	});
		
}