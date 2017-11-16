$(document).ready(function(){
	init_getpassword_panel();
});


/*初始化取回密码表单*/
function init_getpassword_panel()
{	
	
	//验证码刷新
	$("#getpassword_form img.verify").live("click",function(){
		$(this).attr("src",$(this).attr("rel")+"?"+Math.random());
	});
	$("#getpassword_form .refresh_verify").live("click",function(){
		var img = $(this).parent().find("img.verify");
		$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});
	
	//未提交前的验证
	$("#getpassword_form").each(function(k,getpassword_form){
		if(!$(getpassword_form).find("input[name='getpassword_email']").attr("bindfocus"))
		{
			$(getpassword_form).find("input[name='getpassword_email']").attr("bindfocus",true);		
			$(getpassword_form).find("input[name='getpassword_email']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$("#getpassword_form").each(function(k,getpassword_form){
		if(!$(getpassword_form).find("input[name='getpassword_email']").attr("bindblur"))
		{
			$(getpassword_form).find("input[name='getpassword_email']").attr("bindblur",true);
			$(getpassword_form).find("input[name='getpassword_email']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入邮箱账号");
				}
				else if(!$.checkEmail($.trim(txt)))
				{
					form_err($(this),"邮箱格式不正确");
				}
				else
				{
					//验证邮箱是否注册过
					ajax_check_field("getpassword_email",txt,0,ipt);
				}
			});
		}
	});
	
	
	
	$("#getpassword_form").each(function(k,getpassword_form){
		if(!$(getpassword_form).find("input[name='verify_code']").attr("bindfocus"))
		{
			$(getpassword_form).find("input[name='verify_code']").attr("bindfocus",true);
			$(getpassword_form).find("input[name='verify_code']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$("#getpassword_form").each(function(k,getpassword_form){
		if(!$(getpassword_form).find("input[name='verify_code']").attr("bindblur"))
		{
			$(getpassword_form).find("input[name='verify_code']").attr("bindblur",true);
			$(getpassword_form).find("input[name='verify_code']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入图片文字");
				}
				else
				{
					//验证图片验证码
					ajax_check_field("verify_code",txt,0,ipt);
				}
			});
		}
	});
	
	
		
	//对表单的提交ajax绑定
	$("#getpassword_form").find(".getpassword").each(function(i,form){
		if(!$(form).attr("bindsubmit"))
		{
			$(form).attr("bindsubmit",true);
			$(form).bind("submit",function(){
				
				//验证字段
				var email = $(form).find("input[name='getpassword_email']");
				if($.trim(email.val())=="")
				{
					form_tip(email,"请输入邮箱账号");
					return false;
				}
				if(!$.checkEmail(email.val()))
				{
					form_err(email,"邮箱格式不正确");
					return false;
				}
				
								
				var verify_code = $(form).find("input[name='verify_code']");
				if($.trim(verify_code.val())=="")
				{
					form_tip(verify_code,"请输入图片文字");
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