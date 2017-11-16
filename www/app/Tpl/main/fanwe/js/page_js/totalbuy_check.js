$(document).ready(function(){
	count_buy_totalbuy();
	init_payment_change();
	init_voucher_verify();
	init_sms_event();
});

function init_payment_change()
{
	var payment_id=$("input[name='payment_id']").val();
	var account_payment=$(".account_payment").val();
	$("input[name='payment']").parent().each(function(i,o){
		if($(o).children().val()==payment_id){
			$(o).click();
		}
	});
	
	
	$("input[name='account_money'],input[name='ecvsn'],input[name='ecvpassword']").bind("blur",function(){
		count_buy_totalbuy();
	});
	$("*[name='ecvsn']").bind("change",function(){
		count_buy_totalbuy();
	});
	$("input[name='payment']").bind("checked",function(){
		count_buy_totalbuy();

	});
	$("#check-all-money").bind("checkon",function(){

		count_buy_totalbuy();
	});
	$("#check-all-money").bind("checkoff",function(){

		$("#account_money").val("0");
		count_buy_totalbuy();

	});
}

function init_voucher_verify()
{
	$('#verify_ecv').bind("click",function(){
		var query = new Object();
		query.ecvsn = $(this).parent().find("input[name='ecvsn']").val();
		query.ecvpassword = $(this).parent().find("input[name='ecvpassword']").val();
		query.act = "verify_ecv";
		$.ajax({
			url: AJAX_URL,
			data:query,
			dataType:"json",
			type:"POST",
			success: function(obj){
				$.showSuccess(obj.info);
			},
			error:function(ajaxobj)
			{
//				if(ajaxobj.responseText!='')
//				alert(ajaxobj.responseText);
			}
		});
	});
}

function count_buy_totalbuy()
{

	set_buy_btn_status(false);
	var query = new Object();

	query.id = $("input[name='id']").val();

	//余额支付
	var account_money = $("input[name='account_money']").val();
	if(!account_money||$.trim(account_money)=='')
	{
		account_money = 0;
	}
	query.account_money = account_money;

	//全额支付
	if($("#check-all-money").attr("checked"))
	{
		query.all_account_money = 1;
		$("#check-all-money").val(1);
	}
	else
	{
		query.all_account_money = 0;
	}

	//代金券
	var ecvsn = $("*[name='ecvsn']").val();
	if(!ecvsn)
	{
		ecvsn = '';
	}
	var ecvpassword = $("*[name='ecvpassword']").val();
	if(!ecvpassword)
	{
		ecvpassword = '';
	}
	query.ecvsn = ecvsn;
	query.ecvpassword = ecvpassword;

	//支付方式
	var payment = $("input[name='payment']:checked").val();
	if(!payment)
	{
		payment = 0;
	}
	query.payment = payment;
	query.bank_id = $("input[name='payment']:checked").attr("rel");
	query.act = "count_buy_totalbuy";
	$.ajax({
		url: AJAX_URL,
		data:query,
		type: "POST",
		dataType: "json",
		success: function(data){
			$("#cart_total").html(data.html);

			$("input[name='account_money']").val(data.account_money);
			if(data.pay_price == 0)
			{
				$("input[name='payment']").attr("checked",false);
				$("input[name='payment']").parent().each(function(i,o){
					$(o).ui_radiobox({refresh:true});
				});
			}

			set_buy_btn_status(true);
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(LANG['REFRESH_TOO_FAST']);
		}
	});
}

/**
 * 设置购物提交按钮状态
 */
function set_buy_btn_status(status,refresh_ui)
{
	if(!refresh_ui)
	{
		refresh_ui = false;
	}

	var buy_btn = $("#order_done");
	var buy_btn_ui = buy_btn.next();

	if(status)
	{
		if(refresh_ui)
		{
			buy_btn_ui.attr("rel","blue");
			buy_btn_ui.removeClass("disabled");
			buy_btn_ui.addClass("blue");
		}


		buy_btn.unbind("click");
		buy_btn.bind("click",function(){
			submit_buy();
		});
	}
	else
	{
		if(refresh_ui)
		{
			buy_btn_ui.attr("rel","disabled");
			buy_btn_ui.removeClass("blue");
			buy_btn_ui.addClass("disabled");
		}

		buy_btn.unbind("click");
	}

}

//购物提交
function submit_buy()
{
	set_buy_btn_status(false,true);
	
	//提交订单
	var ajaxurl = $("#paydone_form").attr("action");
	var query = $("#paydone_form").serialize();
	$.ajax({
		url:ajaxurl,
		data:query,
		dataType:"json",
		type:"POST",
		success:function(obj){
			set_buy_btn_status(true,true);
			if(obj.status)
			{
				if(obj.info!="")
				{
					$.showSuccess(obj.info,function(){
						if(obj.jump!="")
							location.href = obj.jump;
					});
				}
				else
				{
					if(obj.jump!="")
						location.href = obj.jump;
				}
			}
			else
			{
				if(obj.info!="")
				{
					$.showErr(obj.info,function(){
						if(obj.jump!="")
							location.href = obj.jump;
					});
				}
				else
				{
					if(obj.jump!="")
						location.href = obj.jump;
				}
			}
		}
	});
}


/**
 * 初始化会员手机绑定的操作
 */
function init_sms_event()
{

	//验证码刷新
	$("#user_mobile img.verify").live("click",function(){
		$(this).attr("src",$(this).attr("rel")+"?"+Math.random());
	});
	$("#user_mobile .refresh_verify").live("click",function(){
		var img = $(this).parent().find("img.verify");
		$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});

	//验证验证码
	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("input[name='verify_code']").attr("bindfocus"))
		{
			$(mobile_panel).find("input[name='verify_code']").attr("bindfocus",true);
			$(mobile_panel).find("input[name='verify_code']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});


	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("input[name='verify_code']").attr("bindblur"))
		{
			$(mobile_panel).find("input[name='verify_code']").attr("bindblur",true);
			$(mobile_panel).find("input[name='verify_code']").bind("blur",function(){
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

	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("input[name='user_mobile']").attr("bindblur"))
		{
			$(mobile_panel).find("input[name='user_mobile']").attr("bindblur",true);
			$(mobile_panel).find("input[name='user_mobile']").bind("blur",function(){
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


	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("input[name='sms_verify']").attr("bindfocus"))
		{
			$(mobile_panel).find("input[name='sms_verify']").attr("bindfocus",true);
			$(mobile_panel).find("input[name='sms_verify']").bind("focus",function(){
				form_tip_clear($(this));
			});
		}
	});


	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("input[name='sms_verify']").attr("bindblur"))
		{
			$(mobile_panel).find("input[name='sms_verify']").attr("bindblur",true);
			$(mobile_panel).find("input[name='sms_verify']").bind("blur",function(){
				var txt = $(this).val();
				var ipt = $(this);
				if($.trim(txt)=="")
				{
					form_tip($(this),"请输入收到的验证码");
				}
			});
		}
	});

	$.init_cart_sms_btn = function()
	{
		$("#user_mobile").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i,o){
			$(o).attr("init_sms","init_sms");
			var lesstime = $(o).attr("lesstime");
			var divbtn = $(o).next();
			divbtn.attr("lesstime",lesstime);
			if(parseInt(lesstime)>0)
			init_sms_code_btn($(divbtn),lesstime);
		});
	};



	//发短信的按钮事件
	$.init_cart_sms_btn();
	$("#user_mobile").each(function(k,mobile_panel){
		if(!$(mobile_panel).find("div.ph_verify_btn").attr("bindclick"))
		{
			$(mobile_panel).find("div.ph_verify_btn").attr("bindclick",true);
			$(mobile_panel).find("div.ph_verify_btn").bind("click",function(){

				if($(this).attr("rel")=="disabled")return false;
				var btn = $(this);
				var query = new Object();
				query.act = "send_sms_code";
				var mobile = $(mobile_panel).find("input[name='user_mobile']").val();
				if($.trim(mobile)=="")
				{
					form_tip($(mobile_panel).find("input[name='user_mobile']"),"请输入手机号");
					return false;
				}
				if(!$.checkMobilePhone(mobile))
				{
					form_err($(mobile_panel).find("input[name='user_mobile']"),"手机号格式不正确");
					return false;
				}
				query.mobile = $.trim(mobile);
				query.verify_code = $.trim($(mobile_panel).find("input[name='verify_code']").val());
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
		    		    	$(mobile_panel).find("img.verify").click();
		    		    	if(data.sms_ipcount>1)
		    		    	{
		    		    		$(mobile_panel).find(".ph_img_verify").show();
		    		    	}
		    		    	else
		    		    	{
		    		    		$(mobile_panel).find(".ph_img_verify").hide();
		    		    	}
		    		    }
		    		    else
		    		    {
		    		    	if(data.field)
		    		    	{
		    		    		form_err($(mobile_panel).find("input[name='"+data.field+"']"),data.info);
		    		    	}
		    		    	else
		    		    	$.showErr(data.info);
		    		    }
		    		}
		    	});
			});
		}
	});

}
