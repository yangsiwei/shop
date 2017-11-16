var lesstime = 0;
$(document).ready(function(){
	init_sms_btn($("#sms_btn"));
	$("#sms_btn").bind("click",function(){
		do_send($("#sms_btn"));
	});
//        $("#verify_image_box").find(".verify_close_btn").bind("click",function(){
//            $("#verify_image_box").hide();
//        });
});

function do_send(btn)
{
	if($.trim($("#mobile").val())=="")
	{
		$.showErr("请输入手机号码");
		return false;
	}

	if(lesstime>0)return;
	var query = new Object();
	query.mobile = $("#mobile").val();
	query.act = "send_sms_code";
	query.unique = $(btn).attr("unique");
	query.verify_code = (btn).attr("verify_code");
	$.ajax({
		url:AJAX_URL,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status==1)
			{
                $("#user-register-sms_verify-code").hide();
                $(".wrap").show();
                $(".third-login").show();
				$(btn).attr("lesstime",obj.lesstime);
				init_sms_btn(btn);
				$.showSuccess(obj.info);
                $(btn).attr("verify_code","");

			}
			else
			{
				if(obj.status==-1)
				{
                    $(".wrap").hide();
                    $(".third-login").hide();
                    $("#user-register-sms_verify-code").show();
                    $("#user-register-sms_verify-code").find("img").attr("src",obj.verify_image+"&r="+Math.random());
//					$("#user-register-sms_verify-code").html("");
//                                        var html_str = '<div class="wrap"><label class="com-input"><input type="text" class="v_txt" placeholder="图形码" id="verify_image"/></label><img src="'+obj.verify_image+"&r="+Math.random()+'"  />'+
//                                                        '<div class="blank"></div><div class="blank"></div>'+
//                                                        '<div class="v_btn_box"><input type="button" class="v_btn" name="confirm_btn" value="确认"/></div></div>';
//                                        $("#user-register-sms_verify-code").html(html_str);


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
//                            $("#user-register-sms_verify-code").html("");
//                            $("#user-register-sms_verify-code").hide();

                            do_send(btn);
						}
					});
					if($(btn).attr("verify_code")&&$(btn).attr("verify_code")!="")
					{
						$.showErr(obj.info,function(){
							$(btn).attr("verify_code","");
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
		$(btn).attr("lesstime",lesstime);
		if(lesstime<=0)
		{
			$(btn).stopTime();
			$(btn).val("点击获取验证码");
		}
	});
}
