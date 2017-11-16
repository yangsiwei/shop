$(document).ready(function(){
	init_ajax_user();
	init_wx_user();
	init_login_panel();
});


function init_ajax_user()
{
	$("#pop_login").bind("click",function(){
		if(typeof(login_callback)=="function")
		{
			ajax_login(login_callback);
		}
		else
		{
			ajax_login();
		}
		return false;
	});
}
function ajax_login(func)
{	
	$.weeboxs.open(AJAX_LOGIN_URL, {boxid:"wee_login_box",contentType:'ajax',showButton:false,title:"会员登录",width:580,type:'wee',onopen:function(){init_ui_button(); init_ui_textbox(); init_login_panel(); init_ui_checkbox();},onclose:func});	
}

function init_wx_user()
{
	$("#wx_login").live("click",function(){
		var ajax_url = $(this).attr("rel");
		if(typeof(login_callback)=="function")
		{
			wx_login(ajax_url,login_callback);
		}
		else
		{
			wx_login(ajax_url);
		}
		return false;
	});
}

function wx_login(ajax_url,func)
{	
	$.weeboxs.open(ajax_url, {boxid:"wee_wx_login_box",contentType:'ajax',showButton:false,title:"微信登录",width:350,height:300,type:'wee',onopen:function(){
		$("#wx_login").everyTime(2000,function(){

			var query = new Object();
			query.act = "check_login_status";
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
				    	$("#wx_login").stopTime();
				    	location.reload();
				    }
				}
			});
			
		});
		
	},onclose:function(){		
		$("#wx_login").stopTime();
		if(typeof(func)=="function")
		func.call(null);
	}});	
}

/*初始化登录表单*/
function init_login_panel()
{
	
	$(".login-tab a").live("click",function(){
		var form_prefix = $(this).attr("lk");
		var rel = $(this).attr("rel");
		
		$(".login-panel[rel='"+form_prefix+"']").find(".panel").hide();
		$(".login-panel[rel='"+form_prefix+"']").find(".panel[rel='"+rel+"']").show();
		
		$(".login-tab[rel='"+form_prefix+"']").find("a").removeClass("current");
		$(this).addClass("current");
		
	});
	
	//验证码刷新
	$(".login-panel img.verify").each(function(k,img){
		if(!$(img).attr("bindclick"))
		{
			$(img).attr("bindclick",true);
			$(img).bind("click",function(){
				$(img).attr("src",$(this).attr("rel")+"?"+Math.random());
			});
		}
	});
	$(".login-panel .refresh_verify").each(function(k,text){
		if(!$(text).attr("bindclick"))
		{
			$(text).attr("bindclick",true);
			$(text).bind("click",function(){				
				var img = $(text).parent().find("img.verify");
				$(img).attr("src",$(img).attr("rel")+"?"+Math.random());				
			});
		}
	});
	

	$(".login-panel .refresh_verify").live("click",function(){
		//var img = $(this).parent().find("img.verify");
		//$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});
	
	//未提交前的验证
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_key']").attr("bindfocus"))
		{
			$(login_panel).find("input[name='user_key']").attr("bindfocus",true);		
			$(login_panel).find("input[name='user_key']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_key']").attr("bindblur"))
		{
			$(login_panel).find("input[name='user_key']").attr("bindblur",true);
			$(login_panel).find("input[name='user_key']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入登录帐号");
				}
			});
		}
	});
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_pwd']").attr("bindfocus"))
		{
			$(login_panel).find("input[name='user_pwd']").attr("bindfocus",true);
			$(login_panel).find("input[name='user_pwd']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_pwd']").attr("bindblur"))
		{
			$(login_panel).find("input[name='user_pwd']").attr("bindblur",true);
			$(login_panel).find("input[name='user_pwd']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入密码");
				}
			});
		}
	});

	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='verify_code']").attr("bindfocus"))
		{
			$(login_panel).find("input[name='verify_code']").attr("bindfocus",true);
			$(login_panel).find("input[name='verify_code']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='verify_code']").attr("bindblur"))
		{
			$(login_panel).find("input[name='verify_code']").attr("bindblur",true);
			$(login_panel).find("input[name='verify_code']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入图片文字");
				}
				else
				{
					//验证手机验证码
					ajax_check_field("verify_code",txt,0,ipt);
				}
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_mobile']").attr("bindfocus"))
		{
			$(login_panel).find("input[name='user_mobile']").attr("bindfocus",true);
			$(login_panel).find("input[name='user_mobile']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='user_mobile']").attr("bindblur"))
		{
			$(login_panel).find("input[name='user_mobile']").attr("bindblur",true);
			$(login_panel).find("input[name='user_mobile']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入手机号");
				}
				if(!$.checkMobilePhone(txt))
				{
					form_err($(this),"手机号格式不正确");
				}
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='sms_verify']").attr("bindfocus"))
		{
			$(login_panel).find("input[name='sms_verify']").attr("bindfocus",true);
			$(login_panel).find("input[name='sms_verify']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});
	
	
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("input[name='sms_verify']").attr("bindblur"))
		{
			$(login_panel).find("input[name='sms_verify']").attr("bindblur",true);	
			$(login_panel).find("input[name='sms_verify']").bind("blur",function(){
				var txt = $(this).val();
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入收到的验证码");
				}
			});
		}
	});
	
	
	
	
	
	//发短信的按钮事件
	init_sms_btn();
	$(".login-panel").each(function(k,login_panel){
		if(!$(login_panel).find("div.ph_verify_btn").attr("bindclick"))
		{

			$(login_panel).find("div.ph_verify_btn").attr("bindclick",true);
			$(login_panel).find("div.ph_verify_btn").bind("click",function(){	
				if($(this).attr("rel")=="disabled")return false;
				var formname = $(this).attr("form_prefix")+"_ph_login_form";
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
	$(".login-panel").find(".login").each(function(i,form){
		if(!$(form).attr("bindsubmit"))
		{
			$(form).attr("bindsubmit",true);
			$(form).bind("submit",function(){
				
				
				//验证字段
				var user_key = $(form).find("input[name='user_key']");
				if($.trim(user_key.val())=="")
				{
					form_tip(user_key,"请输入登录帐号");
					return false;
				}
				var user_pwd = $(form).find("input[name='user_pwd']");
				if($.trim(user_pwd.val())=="")
				{
					form_tip(user_pwd,"请输入密码");
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
				allow_ajax_check = false;
				$.ajax({
					url: url,
					type: "POST",
					data:query,
					dataType: "json",
					success: function(data){
						allow_ajax_check = true;
						if(data.status)
		    		    {											
						
							$(document).append(data.data);
							
							if(data.tip!=""&&data.tip!=null)
							{
								$("#head_user_tip").html(data.tip);
								$.weeboxs.close("wee_login_box");
							}
							else
							{
								if(data.data!=""&&data.data!=null)
								{
									$.showErr(data.info,function(){
										
										location.href = data.jump;
									});
								}
								else
								{  
									location.href = data.jump;
								}
								
							}							
		    		    	
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
	
	
	
	$(".login-panel").find(".ph_login").each(function(i,form){
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

				
				var url = $(form).attr("action");
				var query = $(form).serialize();	
				allow_ajax_check = false;
				$.ajax({
					url: url,
					type: "POST",
					data:query,
					dataType: "json",
					success: function(data){
						allow_ajax_check = true;
						if(data.status)
		    		    {
							$(document).append(data.data);
							
							if(data.tip!=""&&data.tip!=null)
							{
								  
								$("#head_user_tip").html(data.tip);
								$.weeboxs.close("wee_login_box");
								if(typeof(data.str) != "undefined"){
									$.showSuccess(data.str);
								}
								 
							}
							else
							{
								if(data.data!=""&&data.data!=null)
								{
									$.showErr(data.info,function(){
										location.href = data.jump;
									});
								}
								else
								{
									location.href = data.jump;
								}
								
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
				
				return false;
			});
		}
	});
}