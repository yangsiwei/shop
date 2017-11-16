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
		if(!$(getpassword_form).find("input[name='user_mobile']").attr("bindfocus"))
		{
			$(getpassword_form).find("input[name='user_mobile']").attr("bindfocus",true);		
			$(getpassword_form).find("input[name='user_mobile']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$("#getpassword_form").each(function(k,getpassword_form){
		if(!$(getpassword_form).find("input[name='user_mobile']").attr("bindblur"))
		{
			$(getpassword_form).find("input[name='user_mobile']").attr("bindblur",true);
			$(getpassword_form).find("input[name='user_mobile']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入手机号码");
				}
				else if(!$.checkMobilePhone($.trim(txt)))
				{
					form_err($(this),"手机号码格式不正确");
				}
				else
				{
					//验证邮箱是否注册过
					ajax_check_field("getpassword_mobile",txt,0,ipt);
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
	
	
	
	//发短信的按钮事件
	$("#getpassword_form").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i,o){
		$(o).attr("init_sms","init_sms");
		var lesstime = $(o).attr("lesstime");
		var divbtn = $(o).next();
		divbtn.attr("form_prefix",$(o).attr("form_prefix"));
		divbtn.attr("lesstime",lesstime);
		if(parseInt(lesstime)>0)
		init_sms_code_btn($(divbtn),lesstime);	
	});
	$("#getpassword_form").each(function(k,pwd_pannel){
		if(!$(pwd_pannel).find("div.ph_verify_btn").attr("bindclick"))
		{

			$(pwd_pannel).find("div.ph_verify_btn").attr("bindclick",true);
			$(pwd_pannel).find("div.ph_verify_btn").bind("click",function(){	
				if($(this).attr("rel")=="disabled")return false;
				var form = $("#getpassword_form").find("form");				
				var btn = $(this);
				var query = new Object();
				query.act = "send_sms_code";
				query.get_password = 1;  //是否验证手机有效性
				var mobile = $(form).find("input[name='user_mobile']").val();
				if($.trim(mobile)=="")
				{
					form_tip($(form).find("input[name='user_mobile']"),"请输入手机号码");
					return false;
				}
				if(!$.checkMobilePhone(mobile))
				{
					form_err($(form).find("input[name='user_mobile']"),"手机号格式不正确");
					return false;
				}
				query.mobile = $.trim(mobile);
				query.verify_code = $.trim($(form).find("input[name='verify_code']").val());
				//发送手机验证登录的验证码
				$.ajax({
		    		url:AJAX_URL,
		    		dataType: "json",
		    		data:query,
		            type:"POST",
		            global:false,
		    		success:function(data)
		    		{
		    		    if(data.status)
		    		    {
		    		    	init_sms_code_btn(btn,data.lesstime);
		    		    	IS_RUN_CRON = true;
		    		    	$(form).find("img.verify").click();
		    		    	if(data.sms_ipcount>1)
		    		    	{
		    		    		$(form).find(".ph_img_verify").show();
		    		    	}
		    		    	else
		    		    	{
		    		    		$(form).find(".ph_img_verify").hide();
		    		    	}
		    		    }
		    		    else
		    		    {
		    		    	if(data.field)
		    		    	{
		    		    		form_err($(form).find("input[name='"+data.field+"']"),data.info);
		    		    	}
		    		    	else
		    		    	$.showErr(data.info);
		    		    }
		    		}
		    	});
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
				var mobile = $(form).find("input[name='user_mobile']");
				if($.trim(mobile.val())=="")
				{
					form_tip(mobile,"请输入手机号码");
					return false;
				}
				if(!$.checkMobilePhone(mobile.val()))
				{
					form_err(email,"手机号码格式不正确");
					return false;
				}
				
								
				var verify_code = $(form).find("input[name='sms_verify']");
				if($.trim(verify_code.val())=="")
				{
					form_tip(verify_code,"请输入验证码");
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