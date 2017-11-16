//切换地区
$(document).ready(function(){	
	//验证码刷新
	$(".setting_user_info img.verify").live("click",function(){
		$(this).attr("src",$(this).attr("rel")+"?"+Math.random());
	});
	$(".setting_user_info .refresh_verify").live("click",function(){
		var img = $(this).parent().find("img.verify");
		$(img).attr("src",$(img).attr("rel")+"?"+Math.random());
	});
	//绑定微博同步的ajax
	function syn_weibo(class_name)
	{		
			var query = new Object();
			query.class_name = class_name;
			query.act = "set_syn_weibo";
			$.ajax({
				url:AJAX_URL,
				data:query,
				dataType:"json",
				type:"POST",
				success:function(obj){
					if(obj.status==-1)
					{
						ajax_login();
					}
					else if(obj.status==1)
					{
						$.showSuccess(obj.info,function(){
							location.reload();
						});
					}
					else
					{
						$.showErr(obj.info,function(){
							location.reload();
						});
					}
				}
			});
	
	}
	$(".syn_weibo").bind("checkon",function(){
		syn_weibo($(this).attr("data"));
	});
	$(".syn_weibo").bind("checkoff",function(){
		syn_weibo($(this).attr("data"));
	});
	
	
	//天数计算
	$("#settings_bmonth,#settings_byear").bind("change",function(){
		var m = parseInt($("#settings_bmonth").val());
		var y = parseInt($("#settings_byear").val());
		if(m>0&&y>0)
		{
			var d = parseInt($("#settings_bday").val());			
			day = load_month_day(y,m);
			var html = "<option value='0'>日</option>";
			for(i=1;i<=day;i++)
			{
				html += "<option value='"+i+"'>"+i+"</option>";
			}
			$("#settings_bday").html(html);
			$("#settings_bday").val(d);
			$("#settings_bday").ui_select({refresh:true});
		}
	});
	
	
	init_bind_sms_btn();
	//绑定按钮事件
	init_sms_btn();
	//初始化倒计时
	function init_sms_btn() {
		$(".setting_user_info").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i, o) {
			$(o).attr("init_sms", "init_sms");
			var lesstime = $(o).attr("lesstime");
			var divbtn = $(o).next();
			divbtn.attr("lesstime", lesstime);
			if(parseInt(lesstime) > 0)
				init_sms_code_btn($(divbtn), lesstime);
		});
	}
	function init_bind_sms_btn() {
		if(!$(".setting_user_info").find("div.ph_verify_btn").attr("bindclick")) {
			$(".setting_user_info").find("div.ph_verify_btn").attr("bindclick", true);
			$(".setting_user_info").find("div.ph_verify_btn").bind("click", function() {
				if($(this).attr("rel") == "disabled")
					return false;
				var is_error = 0;
				var error_msg = '';
				var form = $("form[name='setting_user_info']");
				var btn = $(this);
				var query = new Object();
				query.act = "send_sms_code";
				var mobile = $(form).find("input[name='mobile']").val();
				if($.trim($("#current_password").val()) =='')
				{
					$("#current_password").focus();
					$.showErr("修改手机号，必须输入当前密码进行验证!");	
					return false;
				}
				if($.trim(mobile) == "") {
					$("#account_mobile").focus();
					is_error = 1;
					error_msg = "请输入手机号";
				}

				if(!$.checkMobilePhone(mobile)) {
					$("#settings_mobile").focus();
					is_error = 1;
					error_msg = "手机号格式不正确";
				}
				if(is_error) {
					$.showErr(error_msg);
					//$.Show_error_tip(error_msg);
					return false;
				}
				query.mobile = $.trim(mobile);
				query.verify_code = $.trim($(form).find("input[name='verify_code']").val());
				query.unique = 1;
				query.no_verify = 1;
				//是否验证手机是否被注册过
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
							$(form).find("img.verify").click();
							if(data.sms_ipcount > 1) {
								$(form).find(".ph_img_verify").show();
							} else {
								$(form).find(".ph_img_verify").hide();
							}
						} else {
							$.showErr(data.info);
							if(data.field=="verify_code"){
								$('.verify').attr("src",$('.verify').attr("rel")+"?"+Math.random());
							}
						}
					}
				});
			});
		}

	}
	
	/**
	 * 是否显示验证行
	 */
	function init_ph_sms_verify_row()
	{
		var default_mobile = $("#settings_mobile").attr("data");
		var cur_mobile = $("#settings_mobile").val();
		if(cur_mobile !='' && default_mobile != cur_mobile){
			$(".ph_sms_verify").css("visibility","inherit");
			$(".is_check_mobile").val("1");
		}else{
			$(".ph_sms_verify").css("visibility","hidden");
			$(".is_check_mobile").val("0");
		}
	}
	
	$("#settings_mobile").blur(function(){
		init_ph_sms_verify_row();
	});
	init_ph_sms_verify_row();
	
	$("select[name='province_id']").bind("change",function(){
		load_city();
	});
	
	$("form[name='setting_user_info']").submit(function(){
		var is_check_mobile =$(".is_check_mobile").val();
		var form = $("form[name='setting_user_info']");
		
		var user_name = $.trim($("#settings_user_name").val());
		var name_rul = /^[\w\u4e00-\u9fa5]+$/;
		if(user_name==""){
			$("#settings_user_name").focus();
			$.showErr("请输入用户名！");
			
			return false;
		}
		else if(! name_rul.test(user_name)){
			$("#settings_user_name").focus();			
			$.showErr("您输入的用户名不合法！");
			
        	return false;
        }
		
		if($.trim($("#settings_email").val()).length == 0)
		{
			$("#settings_email").focus();
			$.showErr("请输入邮件地址！");
			
			return false;
		}
		
		if(!$.checkEmail($("#settings_email").val()))
		{
			$("#settings_email").focus();			
			$.showErr("请输入正确的邮件地址！");
			return false;
		}
		
		if($.trim($("#settings_password").val())!=''&&!$.minLength($("#settings_password").val(),4,false))
		{
			$("#settings_password").focus();
			$.showErr("请输入不小于4个字符的密码！");	
			return false;
		}
		
//		if($.trim($("#settings_password").val())!='' && $.trim($("#current_password").val()) =='')
//		{
//			$("#current_password").focus();
//			$.showErr("修改密码，必须输入当前密码进行验证！");	
//			return false;
//		}
		
		if($("#settings_password_confirm").val() != $("#settings_password").val())
		{
			$("#settings_password_confirm").focus();
			$.showErr("请确认两次输入的密码一致！");			
			return false;
		}

		if(!$.checkMobilePhone($("#settings_mobile").val()))
		{
			$("#settings_mobile").focus();			
			$.showErr("请真确输入手机号！");	
			return false;
		}		
		

		var url = $(form).attr("action");
		var query = $(form).serialize();
		$.ajax({
			url : url,
			type : "POST",
			data : query,
			dataType : "json",
			success : function(data) {
				if(data.error == 1000) {
					ajax_login();
				}else if(data.error == 0) {
					$.showSuccess("修改成功",function(){window.location=data.jump;});
				}else{
					$.showErr(data.info);
				}
				return false;
			}
		});	
		
		return false;
	});
	
	//上传控件
	$(".up_btn div.upload_avatar_btn").ui_upload({multi:false,FilesAdded:function(files){
		$(".upimg_box .avatar_box").css("visibility","hidden");
		$(".upimg_box .loading").show();
		return true;
	},FileUploaded:function(responseObject){
		if(responseObject.error==1000)
		{
			ajax_login();
		}
		else if(responseObject.error==0)
		{
			$(".upimg_box .loading").hide();
			$(".upimg_box .avatar_box img").attr("src",responseObject.big_url+"?r="+Math.random());
			$(".upimg_box .avatar_box").css({"visibility":"initial"});
			$(".user_info_box .avatar_box").find("img").attr("src",responseObject.small_url+"?r="+Math.random());
			
		}
		else
		{
			$.showErr(responseObject.message);
		}
	},UploadComplete:function(files){
	},Error:function(errObject){
		
	}});
	
	/*微博同步绑定*/
	$(".syn_weibo").bind("change",function(){
		var class_name = $(this).attr("data");
		if(!class_name){
			$.showErr("参数错误");
			return false;
		}
		var query = new Object();
		query.act = "set_syn_weibo";
		query.class_name = class_name;
		$.ajax({
			url : AJAX_URL,
			type : "POST",
			data : query,
			dataType : "json",
			success : function(data) {
				if(data.status == -1) {
					ajax_login();
				}else if(data.status == 1) {
					$.showSuccess(data.info,function(){window.location.reload();});
				}else{
					$.showErr(data.info);
				}
				return false;
			}
		});
		
	});
});
	
function load_city()
{
	var id = $("select[name='province_id']").val();
	
	var evalStr="regionConf.r"+id+".c";

	if(id==0)
	{
		var html = "<option value='0'>所在城市</option>";
	}
	else
	{
		var regionConfs=eval(evalStr);
		evalStr+=".";
		var html = "<option value='0'>所在城市</option>";
		for(var key in regionConfs)
		{
			html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
		}
	}
	$("select[name='city_id']").html(html);
	$("select[name='city_id']").ui_select({refresh:true});

}
/*解除第三方登录绑定*/
function unset_bind_api(class_name){
	var class_name = $.trim(class_name);
	if(!class_name){
		$.showErr("参数错误");
		return false;
	}
	$.showConfirm("确定要解除绑定吗？解除后微博也将无法同步。",function(){
		var query = new Object();
		query.act = "unset_bind_api";
		query.class_name = class_name;
		$.ajax({
			url : AJAX_URL,
			type : "POST",
			data : query,
			dataType : "json",
			success : function(data) {
				if(data.status == -1) {
					ajax_login();
				}else if(data.status == 1) {
					$.showSuccess("解除绑定成功，想同步微博可以再次进行绑定^_^",function(){window.location.reload();});
				}else{
					$.showErr(data.info);
				}
				return false;
			}
		});
	});
	
	
}

/**
 *  加载月份中的天数
 * @param $year  年份
 * @param $month 月份 1-12
 */
function load_month_day(year,month)
{
	var monthday = [31,28,31,30,31,30,31,31,30,31,30,31];  //12个月份的默认天数
	if((year % 4 == 0 && year % 100 != 0) || (year % 400 == 0)) //闰年
	{
		monthday[1] = 29;
	}
	return monthday[month-1];
}

			