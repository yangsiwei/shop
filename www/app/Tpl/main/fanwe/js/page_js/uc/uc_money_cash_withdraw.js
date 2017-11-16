$(document).ready(function(){
	
	$("#withdraw .refresh_verify").live("click",function(){
		var img = $(this).parent().find("img.verify");
		$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});
	$("#withdraw img.verify").live("click",function(){
		$(this).attr("src",$(this).attr("rel")+"?"+Math.random());
	});
	
	$("input[name='money']").blur(function(){
		var money = $.trim($("#withdraw form").find("input[name='money']").val());
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
	
	$("#withdraw form").bind("submit",function(){
		var bank_name = $.trim($("#withdraw form").find("input[name='bank_name']").val());
		var bank_account = $.trim($("#withdraw form").find("input[name='bank_account']").val());
		var bank_user = $.trim($("#withdraw form").find("input[name='bank_user']").val());
		var money = $.trim($("#withdraw form").find("input[name='money']").val());
		if($.trim(bank_name)=="")
		{
			$.showErr("请输入开户行名称");
			return false;
		}else{
			if(!bank_name.match(/^[\u4e00-\u9fa5]+$/)){
				$.showErr("开户行名称格式为全中文");
				return false;
			}
		}
		if($.trim(bank_account)=="")
		{
			$.showErr("请输入开户行账号");
			return false;
		}else{
			 
			var readonly = $("#withdraw form").find("input[name='bank_account']").attr('readonly');
			if(isNaN(bank_account)&&readonly==false){
				$.showErr("开户行账号格式为全数字");
				return false;
			}
		}
		
		if($.trim(bank_user)=="")
		{
			$.showErr("请输入开户人真实姓名");
			return false;
		}
		if($.trim(money)==""||isNaN(money)||parseFloat(money)<=0)
		{
			$.showErr("请输入正确的提现金额");
			return false;
		}
		
		var ajax_url = $("#withdraw form").attr("action");
		var query = $("#withdraw form").serialize();
		$.ajax({
			url:ajax_url,
			data:query,
			dataType:"json",
			type:"POST",
			success:function(obj){
				if(obj.status==1000)
				{
					ajax_login();
				}
				else if(obj.status)
				{
					$.showSuccess(obj.info,function(){
						location.reload();
					});
				}
				else
				{
					$.showErr(obj.info,function(){
						if(obj.jump)
						{
							location.href = obj.jump;
						}
					});
				}
			}
		});
		
		
		return false;
	});
	
	
	
	//关于手机号的验证码绑定
	init_bind_sms_btn();
	//绑定按钮事件
	init_sms_btn();
	//初始化倒计时
	function init_sms_btn() {
		$("#withdraw").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i, o) {
			$(o).attr("init_sms", "init_sms");
			var lesstime = $(o).attr("lesstime");
			var divbtn = $(o).next();
			divbtn.attr("lesstime", lesstime);
			if(parseInt(lesstime) > 0)
				init_sms_code_btn($(divbtn), lesstime);
		});
	}
	function init_bind_sms_btn() {
		if(!$("#withdraw").find("div.ph_verify_btn").attr("bindclick")) {
			$("#withdraw").find("div.ph_verify_btn").attr("bindclick", true);
			$("#withdraw").find("div.ph_verify_btn").bind("click", function() {
				if($(this).attr("rel") == "disabled")
					return false;
				var btn = $(this);
				var query = new Object();
				query.act = "send_sms_code";
				query.account = 1;
				query.no_verify = 1;
				query.verify_code = $.trim($("#withdraw").find("input[name='verify_code']").val());
				//发送手机验证登录的验证码
				$.ajax({
					url : AJAX_URL,
					dataType : "json",
					data : query,
					type : "POST",
					global : false,
					success : function(data) {
						if(data.status) {
							init_sms_code_btn(btn, data.lesstime);
							IS_RUN_CRON = true;		
							$("#withdraw").find("img.verify").click();
		    		    	if(data.sms_ipcount>1)
		    		    	{
		    		    		$("#withdraw").find(".ph_img_verify").show();
		    		    	}
		    		    	else
		    		    	{
		    		    		$("#withdraw").find(".ph_img_verify").hide();
		    		    	}
						} else {
							$.showErr(data.info,function(){
								if(data.jump)
								{
									location.href = data.jump;
								}
							});
						}
					}
				});
			});
		}

	}
        
        
        //已有银行卡操作
        $(".bank_item_btn").bind("click",function(){
            var id = $(this).attr("rel");
            var bank_name = $(this).attr("data-bank-name");
            var bank_user = $(this).attr("data-bank-user");
            var bank_account = $(this).attr("data-bank-account");
            $("input[name='bank_name']").val(bank_name);
            $("input[name='bank_user']").val(bank_user);
            $("input[name='bank_account']").val(bank_account);
            $("input[name='user_bank_id']").val(id);
            
            $("input[name='bank_name']").attr("readonly","readonly");
            $("input[name='bank_user']").attr("readonly","readonly");
            $("input[name='bank_account']").attr("readonly","readonly");
            init_ui_textbox();
            
            $("#is_bind_box").hide();
        });
        
        $(".new_bank_btn").bind("click",function(){
            clear_bank_input();
            $("#is_bind_box").show();
        });
        
        $(".bank_list_btn").hover(function(){		
		$(".bank_list").stopTime();
		$(".bank_list").oneTime(300,function(){
			$(".bank_list").slideDown("fast");	
		});						
	},function(){
		$(".bank_list").stopTime();
		$(".bank_list").oneTime(300,function(){
			$(".bank_list").slideUp("fast");
		});
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
                               }
                        }
                });
            });
        });
});

function clear_bank_input(){
    $("input[name='bank_name']").val("");
    $("input[name='bank_account']").val("");
    $("input[name='bank_user']").val($("input[name='bank_user']").attr("rel"));
    $("input[name='user_bank_id']").val("");
    $("input[name='bank_name']").attr("readonly","");
    $("input[name='bank_account']").attr("readonly","");
    $("input[name='bank_user']").attr("readonly","");
    init_ui_textbox();
}