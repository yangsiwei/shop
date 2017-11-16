var lesstime = 0;
$(document).ready(function () {
	init_bank();
	
	$(".bank").bind("click",function(){
		$(".bank span").removeClass("checked");
		$(this).find("span").addClass("checked");
		init_bank();		
	});
	
	init_sms_btn($("#sms_btn"));
	$("#sms_btn").bind("click",function(){
		do_send($("#sms_btn"));
	});
	

//    $("#verify_image_box").find(".verify_close_btn").bind("click",function(){
//        $("#verify_image_box").hide();
//    });
	
	$("form[name='add_card']").bind("submit",function(){		
		var bank_name = $.trim($("form[name='add_card']").find("input[name='bank_name']").val());
		var bank_account = $.trim($("form[name='add_card']").find("input[name='bank_account']").val());
		var bank_user = $.trim($("form[name='add_card']").find("input[name='bank_user']").val());
		var sms_verify = $.trim($("form[name='add_card']").find("input[name='sms_verify']").val());
		if($.trim(bank_account)=="")
		{
			$.showErr("请输入开户行账号");
			$("#user-register-sms_verify-code").find("#verify_image").val('');
			return false;
		}else{
			if(isNaN(bank_account)){
				$.showErr("卡号格式为全数字");
				$("#user-register-sms_verify-code").find("#verify_image").val('');
				return false;
			}
		}
		if($.trim(bank_name)=="")
		{
			$.showErr("请输入开户行名称");
			$("#user-register-sms_verify-code").find("#verify_image").val('');
			return false;
		}else{
			if(!bank_name.match(/^[\u4e00-\u9fa5]+$/)){
				$.showErr("开户行名称格式为全中文");
				$("#user-register-sms_verify-code").find("#verify_image").val('');
				return false;
			}
		}
		if($.trim(bank_user)=="")
		{
			$.showErr("请输入开户人真实姓名");
			$("#user-register-sms_verify-code").find("#verify_image").val('');
			return false;
		}
		// if($.trim(sms_verify)=="")
		// {
		// 	$.showErr("请输入短信验证码");
		// 	$("#user-register-sms_verify-code").find("#verify_image").val('');
		// 	return false;
		// }
		
		var ajax_url = $("form[name='add_card']").attr("action");
		var query = $("form[name='add_card']").serialize();
		$.ajax({
			url:ajax_url,
			data:query,
			dataType:"json",
			type:"POST",
			success:function(obj){
				if(obj.status==1){
					$.showSuccess("保存成功",function(){
						location.href = obj.url;
					});					
				}else if(obj.status==0){
					if(obj.info)
					{
						$.showErr(obj.info,function(){
							if(obj.url) location.href = obj.url;
						});
					}
					else
					{
						if(obj.url)location.href = obj.url;
					}
					
				}else{
					
				}
			}
		});		
		return false;
	});	
	
	$("input[name='money']").blur(function(){
		var money = $("form[name='withdraw']").find("input[name='money']").val();
		if($.trim(money)==""||isNaN(money)||parseFloat(money)<=0)
		{
			$("input[name='money']").val('');
			return false;
		}else{
			a = parseInt(money * 100);
			b = a/100;
			$("input[name='money']").val(b);
		}
	});

	
	$("form[name='withdraw']").bind("submit",function(){		
		var bank_id = $("form[name='withdraw']").find("input[name='bank_id']").val();
		var money = $("form[name='withdraw']").find("input[name='money']").val();
		var pwd = $("form[name='withdraw']").find("input[name='pwd']").val();
		if($.trim(pwd)=="")
		{
			$.showErr("请输入登录密码");
			return false;
		}

		if($.trim(bank_id)==""||isNaN(bank_id)||parseFloat(bank_id)<=0)
		{
			$.showErr("请选择提现账户");
			return false;
		}
		if($.trim(money)==""||isNaN(money)||parseFloat(money)<=0)
		{
			$.showErr("请输入正确的提现金额");
			return false;
		}
		
		var ajax_url = $("form[name='withdraw']").attr("action");
		var query = $("form[name='withdraw']").serialize();
		$.ajax({
			url:ajax_url,
			data:query,
			dataType:"json",
			type:"POST",
			success:function(obj){
				if(obj.status==1){
					$.showSuccess("提现申请成功，请等待管理员审核",function(){
						if(obj.url)location.href = obj.url;
					});					
				}else if(obj.status==0){
					if(obj.info)
					{
						$.showErr(obj.info,function(){
							if(obj.url) location.href = obj.url;
						});
					}
					else
					{
						if(obj.url)location.href = obj.url;
					}
					
				}else{
					
				}
			}
		});		
		return false;
	});	
	
	
	$(".del_bank_btn").bind("click",function(){
        var obj = $(this);
        $.showConfirm("是否确定删除",function(){
            var query = new Object();
                query.act = "del_user_bank";
                query.user_bank_id = obj.attr("rel");
           $.ajax({
                    url : withdraw_ajax_url,
                    dataType : "json",
                    data : query,
                    type : "POST",
                    global : false,
                    success : function(data) {
                           if(data.status){
                               obj.parent().remove();
                               clear_bank_input();
                           }else{
                               $.showErr(data.info);
						   }
                    }
            });
        });
    });
	
 });

function init_bank(){
	var bank_name=$(".bank ").find(".checked").attr("bank_name");
	var bank_id=$(".bank ").find(".checked").attr("rel");
	$("input[name='bank_name']").val(bank_name);
	$("input[name='bank_id']").val(bank_id);
}



function do_send(btn)
{
        var account = $(btn).attr("account");
	if($.trim($("#mobile").val())=="" && account!=1)
	{
		$.showErr("请输入手机号码");
		return false;
	}
	
	if(lesstime>0)return;
	var query = new Object();
	query.mobile = $("#mobile").val();
	query.act = "send_sms_code";
	query.unique = $(btn).attr("unique");
        query.account = account;
	query.verify_code = (btn).attr("verify_code");
	$.ajax({
		url:AJAX_URL,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			//console.log(obj);
			if(obj.status==1)
			{
				$(btn).attr("lesstime",obj.lesstime);
				init_sms_btn(btn);
				$.showSuccess(obj.info);
				
			}
			else
			{
				if(obj.status==-1)
				{
							$(".wrap").hide();
							$("#user-register-sms_verify-code").show();
		                    $("#user-register-sms_verify-code").find("img").attr("src",obj.verify_image+"&r="+Math.random());
//							$("#verify_image_box .verify_form_box .verify_content").html("");
//		                    var html_str = '<div class="v_input_box"><input type="text" class="v_txt" placeholder="图形码" id="verify_image"/><img src="'+obj.verify_image+"&r="+Math.random()+'"  /></div>'+
//		                                    '<div class="blank"></div><div class="blank"></div>'+
//		                                    '<div class="v_btn_box"><input type="button" class="v_btn" name="confirm_btn" value="确认"/></div>';
//		                    $("#verify_image_box .verify_form_box .verify_content").html(html_str);
//		                    $("#verify_image_box").show();
							
							$("#user-register-sms_verify-code").find("img").bind("click",function(){
								$(this).attr("src",obj.verify_image+"&r="+Math.random());
							});
							$("#user-register-sms_verify-code").find("input[name='confirm_btn']").bind("click",function(){
								var verify_code = $.trim($("#user-register-sms_verify-code").find("#verify_image").val());
								if(verify_code=="")
								{
									$.showErr("请输入图形验证码");
								}
								else
								{
									$(btn).attr("verify_code",verify_code);
//									$("#verify_image_box .verify_form_box .verify_content").html("");
							                                    $("#user-register-sms_verify-code").hide();
							                                    $(".wrap").show();
							                                    do_send(btn);
							                                    
								}
							});
							if($(btn).attr("verify_code")&&$(btn).attr("verify_code")!="")
							{
								$.showErr(obj.info,function(){
									$(btn).attr("verify_code","")
								});
							}
				}
				else
				{
					$.showErr(obj.info);
				}
				
			}
		}
	});
}


//关于短信验证码倒计时
function init_sms_btn(btn)
{
	$(btn).stopTime();
	$(btn).everyTime(1000,function(){
		lesstime = parseInt($(btn).attr("lesstime"));
		lesstime--;
		$(btn).val("重新获取("+lesstime+")");
		$(btn).html("重新获取("+lesstime+")");
		$(btn).attr("lesstime",lesstime);
		if(lesstime<=0)
		{
			$(btn).stopTime();
			$(btn).val("获取验证码");
			$(btn).html("获取验证码");
		}
	});
}

function clear_bank_input(){
    $("input[name='bank_name']").val("");
    $("input[name='bank_account']").val("");
    $("input[name='bank_user']").val($("input[name='bank_user']").attr("rel"));
    $("input[name='user_bank_id']").val("");
    $("input[name='bank_name']").attr("readonly","");
    $("input[name='bank_account']").attr("readonly","");
    init_ui_textbox();
}

