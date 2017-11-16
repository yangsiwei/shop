$(document).ready(function(){
	init_register_panel();
});

/*初始化注册表单*/
function init_register_panel()
{	
	$(".register-tab a").live("click",function(){
		var form_prefix = $(this).attr("lk");
		var rel = $(this).attr("rel");
		
		$(".register-panel[rel='"+form_prefix+"']").find(".panel").hide();
		$(".register-panel[rel='"+form_prefix+"']").find(".panel[rel='"+rel+"']").show();
		
		$(".register-tab[rel='"+form_prefix+"']").find("a").removeClass("current");
		$(this).addClass("current");
		
	});
	
	//验证码刷新
	$(".register-panel img.verify").live("click",function(){
		$(this).attr("src",$(this).attr("rel")+"?"+Math.random());
	});
	$(".register-panel .refresh_verify").live("click",function(){
		var img = $(this).parent().find("img.verify");
		$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});
	
	//未提交前的验证
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='email']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='email']").attr("bindfocus",true);		
			$(register_panel).find("input[name='email']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='email']").attr("bindblur"))
		{
			$(register_panel).find("input[name='email']").attr("bindblur",true);
			$(register_panel).find("input[name='email']").bind("blur",function(){
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
					//验证邮箱唯一性
					ajax_check_field("email",txt,0,ipt);
				}
			});
		}
	});
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_name']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='user_name']").attr("bindfocus",true);		
			$(register_panel).find("input[name='user_name']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_name']").attr("bindblur"))
		{
			$(register_panel).find("input[name='user_name']").attr("bindblur",true);
			$(register_panel).find("input[name='user_name']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入用户名");
				}
				else
				{
					//验证用户名唯一性
					ajax_check_field("user_name",txt,0,ipt);
				}
			});
		}
	});
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_pwd']").attr("bindkeyup"))
		{
			$(register_panel).find("input[name='user_pwd']").attr("bindkeyup",true);
			$(register_panel).find("input[name='user_pwd']").bind("keyup",function(){
				var rel = checkPwdFormat($(this).val());
				var relId = $(this).attr("rel");
				$(register_panel).find(".pwd_chk_line[rel='"+relId+"'] li").removeClass("act");
				$(register_panel).find(".pwd_chk_line[rel='"+relId+"'] li[rel='"+rel+"']").addClass("act");
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_pwd']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='user_pwd']").attr("bindfocus",true);		
			$(register_panel).find("input[name='user_pwd']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_pwd']").attr("bindblur"))
		{
			$(register_panel).find("input[name='user_pwd']").attr("bindblur",true);
			$(register_panel).find("input[name='user_pwd']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入密码");
				}
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_pwd_confirm']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='user_pwd_confirm']").attr("bindfocus",true);		
			$(register_panel).find("input[name='user_pwd_confirm']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_pwd_confirm']").attr("bindblur"))
		{
			$(register_panel).find("input[name='user_pwd_confirm']").attr("bindblur",true);
			$(register_panel).find("input[name='user_pwd_confirm']").bind("blur",function(){
				var txt = $(this).val();
				var rel = $(this).attr("rel");
				var pwd = $(register_panel).find("input[name='user_pwd'][rel='"+rel+"']").val();
				
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
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='verify_code']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='verify_code']").attr("bindfocus",true);
			$(register_panel).find("input[name='verify_code']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='verify_code']").attr("bindblur"))
		{
			$(register_panel).find("input[name='verify_code']").attr("bindblur",true);
			$(register_panel).find("input[name='verify_code']").bind("blur",function(){
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
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_mobile']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='user_mobile']").attr("bindfocus",true);
			$(register_panel).find("input[name='user_mobile']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='user_mobile']").attr("bindblur"))
		{
			$(register_panel).find("input[name='user_mobile']").attr("bindblur",true);
			$(register_panel).find("input[name='user_mobile']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入手机号");
				}
				else if(!$.checkMobilePhone(txt))
				{
					form_err($(this),"手机号格式不正确");
				}
				else
				{
					//验证手机唯一性
					ajax_check_field("mobile",txt,0,ipt);
				}
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='sms_verify']").attr("bindfocus"))
		{
			$(register_panel).find("input[name='sms_verify']").attr("bindfocus",true);
			$(register_panel).find("input[name='sms_verify']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("input[name='sms_verify']").attr("bindblur"))
		{
			$(register_panel).find("input[name='sms_verify']").attr("bindblur",true);	
			$(register_panel).find("input[name='sms_verify']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入收到的验证码");
				}
			});
		}
	});
	
	
	
	
	
	//发短信的按钮事件
	init_register_sms_btn();
	$(".register-panel").each(function(k,register_panel){
		if(!$(register_panel).find("div.ph_verify_btn").attr("bindclick"))
		{
			$(register_panel).find("div.ph_verify_btn").attr("bindclick",true);
			$(register_panel).find("div.ph_verify_btn").bind("click",function(){		
				
				if($(this).attr("rel")=="disabled")return false;
				var formname = $(this).attr("form_prefix")+"_ph_register_form";
				var form = $("form[name='"+formname+"']");
				var btn = $(this);
				var query = new Object();
				query.act = "send_sms_code";
				var mobile = $(form).find("input[name='user_mobile']").val();
				if($.trim(mobile)=="")
				{
					form_tip($(form).find("input[name='user_mobile']"),"请输入手机号");
					return false;
				}
				if(!$.checkMobilePhone(mobile))
				{
					form_err($(form).find("input[name='user_mobile']"),"手机号格式不正确");
					return false;
				}
				query.mobile = $.trim(mobile);
				query.verify_code = $.trim($(form).find("input[name='verify_code']").val());
				query.unique = 1; //是否验证手机是否被注册过
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
	$(".register-panel").find(".register").each(function(i,form){
		if(!$(form).attr("bindsubmit"))
		{
			$(form).attr("bindsubmit",true);
			$(form).bind("submit",function(){
				
				//验证字段
				var email = $(form).find("input[name='email']");
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
				
				var user_name = $(form).find("input[name='user_name']");
				if($.trim(user_name.val())=="")
				{
					form_tip(user_name,"请输入用户名");
					return false;
				}
				
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
								window.location.reload();		
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
	
	
	
	$(".register-panel").find(".ph_register").each(function(i,form){
		if(!$(form).attr("bindsubmit"))
		{
			$(form).attr("bindsubmit",true);
			$(form).bind("submit",function(){
				
				//验证字段
				var user_mobile = $(form).find("input[name='user_mobile']");
				if($.trim(user_mobile.val())=="")
				{
					form_tip(user_mobile,"请输入手机号");
					return false;
				}
				if(!$.checkMobilePhone(user_mobile.val()))
				{
					form_err(user_mobile,"手机号格式不正确");
				}
				var sms_verify = $(form).find("input[name='sms_verify']");
				if($.trim(sms_verify.val())=="")
				{
					form_tip(sms_verify,"请输入收到的验证码");
					return false;
				}
				
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
								window.location.reload();	
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

function init_register_sms_btn()
{
	$(".register-panel").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i,o){
		$(o).attr("init_sms","init_sms");
		var lesstime = $(o).attr("lesstime");
		var divbtn = $(o).next();
		divbtn.attr("form_prefix",$(o).attr("form_prefix"));
		divbtn.attr("lesstime",lesstime);
		if(parseInt(lesstime)>0)
		init_sms_code_btn($(divbtn),lesstime);
	});
}