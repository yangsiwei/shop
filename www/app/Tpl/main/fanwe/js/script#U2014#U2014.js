$(document).ready(function(){
	init_ui_button();
	init_ui_textbox();
	init_ui_select();
	init_ui_checkbox();
	init_ui_radiobox();
	init_ui_lazy();
	init_ui_starbar();

	init_ui_list();
	init_cart_tip();
	init_drop_nav();
	init_gotop();

	init_drop_user();
});


function init_cart_tip()
{
	load_cart_count();
	var top_cart_list_width = $("#top_cart_list").width();
	$("#top_cart_list").hide();
	$(".cart_tip").hover(function(){

		var dom = this;
		$(dom).stopTime();
		$(dom).oneTime(300, function(){
			$(dom).append($("#top_cart_list"));
			if($.trim($("#top_cart_list").html())=="")
			load_cart_list();
			$("#top_cart_list").show();
		});
	},function(){
		var dom = this;
		$(dom).stopTime();
		$(dom).oneTime(300, function(){
			$("#top_cart_list").hide();
		});

	});
}

function load_cart_count()
{
//	$.ajax({
//		url:AJAX_URL,
//		data:{"act":"load_cart_count"},
//		dataType:"json",
//		type:"POST",
//		success:function(obj){
//			//$(".cart_tip").find(".cart_count").html(obj.cart_count);
//		}
//	});
}
function load_cart_list()
{
	$("#top_cart_list").html("<div class='loading'></div>");
	$.ajax({
		url:AJAX_URL,
		data:{"act":"load_cart_list"},
		dataType:"json",
		type:"POST",
		success:function(obj){
			$("#top_cart_list").html(obj.html);
			//$("#top_cart_list").show();
			//alert($("#top_cart_list").find(".cart_tip_result_item").length>3);
			if($("#top_cart_list").find(".cart_tip_result_item").length>3)
			{
				$("#top_cart_list").find(".cart_tip_result_list").css("height",300);
			}
			//$("#top_cart_list").hide();
		}
	});
}
function del_cart(id,callback)
{
	$.ajax({
		url:AJAX_URL,
		data:{"act":"del_cart","id":id},
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status)
			{
				$(".cart_tip").find(".cart_count").html(obj.data.cart_count);
				$("#top_cart_list").html(obj.data.html);
				if($("#top_cart_list").find(".cart_tip_result_list").height()>300)
				{
					$("#top_cart_list").find(".cart_tip_result_list").css("height",300);
				}
			}
			if(callback&&typeof(callback)=="function")
				callback.call(null);
		}
	});
}

//初始化ui-list的位置
function init_ui_list()
{
	$(".ui-list").each(function(i,ui_list){

		var ui_item_array = new Array();
		$(ui_list).find(".ui-item").each(function(k,ui_item){
			ui_item_array.push(ui_item);
		});



		var width = $(ui_list).attr("width");
		width=isNaN(width)?0:parseInt(width);
		var pin_col_init_width = $(ui_list).attr("pin_col_init_width");
		pin_col_init_width=isNaN(pin_col_init_width)?0:parseInt(pin_col_init_width);
		var wSpan = $(ui_list).attr("wSpan");
		wSpan=isNaN(wSpan)?0:parseInt(wSpan);
		var hSpan = $(ui_list).attr("hSpan");
		hSpan=isNaN(hSpan)?0:parseInt(hSpan);


		$(ui_list).init_pin({pin_col_init_width:pin_col_init_width,width:width,hSpan:hSpan,wSpan:wSpan,isAnimate:false,speed:300});


		$(ui_item_array).each(function(k,ui_item){
			$(ui_list).pin(ui_item);
		});

	});
}

//以下是处理UI的公共函数
function init_ui_lazy()
{
	$.refresh_image = function(){
		$("img[lazy][!isload]").ui_lazy({placeholder:LOADER_IMG});
	};
	$.refresh_image();
	$(window).bind("scroll", function(e){
		$.refresh_image();
	});

}

function init_ui_starbar()
{
	$("input.ui-starbar[init!='init']").each(function(i,ipt){
		$(ipt).attr("init","init");  //为了防止重复初始化
		$(ipt).ui_starbar();
	});
}

function init_ui_checkbox()
{
	$("label.ui-checkbox[init!='init']").each(function(i,ImgCbo){
		$(ImgCbo).attr("init","init");  //为了防止重复初始化
		$(ImgCbo).ui_checkbox();
	});
}

function init_ui_radiobox()
{
	$("label.ui-radiobox[init!='init']").each(function(i,ImgCbo){
		$(ImgCbo).attr("init","init");  //为了防止重复初始化
		$(ImgCbo).ui_radiobox();
	});
}

var droped_select = null; //已经下拉的对象
var uiselect_idx = 0;
function init_ui_select()
{
	$("select.ui-select[init!='init']").each(function(i,o){
		uiselect_idx++;
		var id = "uiselect_"+Math.round(Math.random()*10000000)+""+uiselect_idx;
		var op = {id:id};
		$(o).attr("init","init");  //为了防止重复初始化
		$(o).ui_select(op);
	});

	//追加hover的ui-select
	$("select.ui-drop[init!='init']").each(function(i,o){
		uiselect_idx++;
		var id = "uiselect_"+Math.round(Math.random()*10000000)+""+uiselect_idx;
		var op = {id:id,event:"hover"};
		$(o).attr("init","init");  //为了防止重复初始化
		$(o).ui_select(op);
	});

	$(document.body).click(function(e) {
		if($(e.target).attr("class")!='ui-select-selected'&&$(e.target).parent().attr("class")!='ui-select-selected')
    	{
			$(".ui-select-drop").fadeOut("fast");
			$(".ui-select").removeClass("dropdown");
			droped_select = null;
    	}
		else
		{
			if(droped_select!=null&&droped_select.attr("id")!=$(e.target).parent().attr("id"))
			{
				$(droped_select).find(".ui-select-drop").fadeOut("fast");
				$(droped_select).removeClass("dropdown");
			}
			droped_select = $(e.target).parent();
		}
	});

}

function init_ui_button()
{

	$("button.ui-button[init!='init']").each(function(i,o){
		$(o).attr("init","init");  //为了防止重复初始化
		$(o).ui_button();
	});

}

function init_ui_textbox()
{

	$(".ui-textbox[init!='init'],.ui-textarea[init!='init']").each(function(i,o){
		$(o).attr("init","init");  //为了防止重复初始化
		$(o).ui_textbox();
	});

}
//ui初始化结束


function init_sms_btn()
{
	$(".login-panel").find("button.ph_verify_btn[init_sms!='init_sms']").each(function(i,o){
		$(o).attr("init_sms","init_sms");
		var lesstime = $(o).attr("lesstime");
		var divbtn = $(o).next();
		divbtn.attr("form_prefix",$(o).attr("form_prefix"));
		divbtn.attr("lesstime",lesstime);
		if(parseInt(lesstime)>0)
		init_sms_code_btn($(divbtn),lesstime);
	});
}
//关于短信验证码倒计时
function init_sms_code_btn(btn,lesstime)
{

	$(btn).stopTime();
	$(btn).removeClass($(btn).attr("rel"));
	$(btn).removeClass($(btn).attr("rel")+"_hover");
	$(btn).removeClass($(btn).attr("rel")+"_active");
	$(btn).attr("rel","disabled");
	$(btn).addClass("disabled");
	$(btn).find("span").html("重新获取("+lesstime+")");
	$(btn).attr("lesstime",lesstime);
	$(btn).everyTime(1000,function(){
		var lt = parseInt($(btn).attr("lesstime"));
		lt--;
		$(btn).find("span").html("重新获取("+lt+")");
		$(btn).attr("lesstime",lt);
		if(lt==0)
		{
			$(btn).stopTime();
			$(btn).removeClass($(btn).attr("rel"));
			$(btn).removeClass($(btn).attr("rel")+"_hover");
			$(btn).removeClass($(btn).attr("rel")+"_active");
			$(btn).attr("rel","light");
			$(btn).addClass("light");
			$(btn).find("span").html("发送验证码");
		}
	});
}


function form_err(ipt,txt){
	$(ipt).parent().parent().find(".form_tip").html("<span class='error'>"+txt+"</span>");
}
function form_success(ipt,txt){
	if(txt!="")
	$(ipt).parent().parent().find(".form_tip").html("<span class='success'>"+txt+"</span>");
	else
		$(ipt).parent().parent().find(".form_tip").html("<span class='success'>&nbsp;</span>");
}
function form_tip(ipt,txt){
	$(ipt).parent().parent().find(".form_tip").html("<span class='tip'>"+txt+"</span>");
}
function form_tip_clear(ipt)
{
	$(ipt).parent().parent().find(".form_tip").html("");
}

//绑定主菜单的相关操作
function init_drop_nav()
{

	if($(".drop_nav").length > 0){


	$(".drop_nav").find(".drop_box").hide();
	$(".drop_nav").hover(function(){
		var drop_nav = $(this);
		if($(drop_nav).attr("ref")!="no_drop")
		{
			$(drop_nav).stopTime();
			$(drop_nav).oneTime(50, function(){
				$(drop_nav).find(".drop_box").slideDown("fast");
				$(drop_nav).find(".drop_title i").addClass("up");
			});
		}
	},function(){
		var drop_nav = $(this);
		$(drop_nav).stopTime();
		$(drop_nav).find(".drop_box").fadeOut("fast");
		$(drop_nav).find(".drop_title i").removeClass("up");
	});


	var max_height = $(".fix_cate_tree").height()+$(".fix_nav_bar").height()+$(".fix_nav_bar").offset().top;
	$(window).scroll(function(){
		if($(document).scrollTop()>max_height)
		{
			$(".float_nav_bar").show();
			$(".float_nav_bar").find(".drop_nav").attr("ref","");
		}
		else
		{
			$(".float_nav_bar").hide();
			$(".float_nav_bar").find(".drop_nav").attr("ref","no_drop");
		}
	});
	if($(document).scrollTop()>0)
	{
		$(".float_nav_bar").show();
		$(".float_nav_bar").find(".drop_nav").attr("ref","");
		$(".float_nav_bar").css("top",$(document).scrollTop());
	}
	else
	{
		$(".float_nav_bar").hide();
		$(".float_nav_bar").find(".drop_nav").attr("ref","no_drop");
	}
	}
}





function init_gotop()
{

	$(window).scroll(function(){

		if($.browser.msie && $.browser.version =="6.0")
		{
			$("#go_top").css("top",$(document).scrollTop()+$(window).height()-80);
		}

		if($(document).scrollTop()>0)
			$("#go_top").fadeIn();
		else
			$("#go_top").fadeOut();
	});

	if($.browser.msie && $.browser.version =="6.0")
	$("#go_top").css("top",$(document).scrollTop()+$(window).height()-80);
	if($(document).scrollTop()>0)
		$("#go_top").fadeIn();
	else
		$("#go_top").fadeOut();

	$("#go_top").bind("click",function(){
		$("html,body").animate({scrollTop:0},"fast","swing",function(){
		});
	});

}



/*验证*/
$.minLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);

	return strLength >= length;
};

$.maxLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);

	return strLength <= length;
};
$.getStringLength=function(str)
{
	str = $.trim(str);

	if(str=="")
		return 0;

	var length=0;
	for(var i=0;i <str.length;i++)
	{
		if(str.charCodeAt(i)>255)
			length+=2;
		else
			length++;
	}

	return length;
};

$.checkMobilePhone = function(value){
	if($.trim(value)!='')
	{
		var reg = /^(1[34578]\d{9})$/;
		return reg.test($.trim(value));
	}
	else
		return true;
};
$.checkEmail = function(val){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	return reg.test(val);
};


/**
 * 检测密码的复杂度
 * @param pwd
 * 分数 1-2:弱 3-4:中 5-6:强
 * 返回 0:弱 1:中 2:强 -1:无
 */
function checkPwdFormat(pwd)
{
	var regex0 = /[a-z]+/;
	var regex1 = /[A-Z]+/;
	var regex2 = /[0-9]+/;
	var regex3 = /\W+/;   //符号
	var regex4 = /\S{6,8}/;
	var regex5 = /\S{9,}/;


	var result = 0;

	if(regex0.test(pwd))result++;
	if(regex1.test(pwd))result++;
	if(regex2.test(pwd))result++;
	if(regex3.test(pwd))result++;
	if(regex4.test(pwd))result++;
	if(regex5.test(pwd))result++;

	if(result>=1&&result<=2)
		result=0;
	else if(result>=3&&result<=4)
		result=1;
	else if(result>=5&&result<=6)
		result=2;
	else
		result=-1;

	return result;
}

/**
 * 验证用户字段
 * @param field 字段名称
 * @param value 值
 * @param user_id	会员ID
 * @param ipt	输入框
 */
var allow_ajax_check = true;
function ajax_check_field(field,value,user_id,ipt)
{
	if(!allow_ajax_check)return;
	var query = new Object();
	query.act = "check_field";
	query.field = field;
	query.value = value;
	query.user_id = user_id;
	$.ajax({
		url:AJAX_URL,
		dataType: "json",
		data:query,
        type:"POST",
        global:false,
		success:function(data)
		{
		    if(!data.status)
		    {
		    	if(data.field)
		    	{
		    		form_err(ipt,data.info);
		    	}
		    	else
		    	$.showErr(data.info);
		    }
		    else
		    {
		    	form_success(ipt,data.info);
		    }
		}
	});
}



function show_signin_message(signin_result)
{
	if(signin_result.status)
	{
		var msg = "<span class='signin_msg'>"+signin_result.info+"</span>";
		if(signin_result.point||signin_result.score||signin_result.money)
		{
			msg+="<span class='signin_price'>";
			if(signin_result.money)
				msg+=signin_result.money+"&nbsp;";
			if(signin_result.score)
				msg+=signin_result.score+"&nbsp;";
			if(signin_result.point)
				msg+=signin_result.point+"&nbsp;";
			msg+="</span>";
		}
		$.showSuccess(msg);
	}

}

function app_download(func){
	$(".android,.ios").bind("click",function(){

		var down_url=$(this).find("a").attr('down_url');
		$.weeboxs.open(down_url, {boxid:"app_box",contentType:'ajax',showButton:false,title:"手机APP下载",width:650, type:'wee',onopen:function(){init_ui_button(); init_ui_textbox();  init_login_panel();init_ui_checkbox();},onclose:func});

	})

}


//关于二维码扫描UI显示
function show_scan_box(dom)
{
	if(QRCODE_ON==1)
	{
		$(dom).find("*[rel='qrcode']").css("opacity","1");
		$(dom).find("*[rel='qrcode']").animate({
			opacity: 'show'
		  },  { duration: 100,queue:false });
	}

}
function hide_scan_box(dom)
{
	if(QRCODE_ON==1)
	{
		$(dom).find("*[rel='qrcode']").css("opacity","1");
		$(dom).find("*[rel='qrcode']").animate({
			opacity: 'hide'
		  },  { duration: 100,queue:false });
	}

}


function init_drop_user()
{
	$("#user_drop_box").hide();
	if($("#user_drop").length>0)
	{
		$("#user_drop").hover(function(){
			$("#user_drop_box").stopTime();
			$("#user_drop_box").oneTime(300,function(){
				var left = $("#user_drop").position().left - ($("#user_drop_box").width()-$("#user_drop").width());
				$("#user_drop_box").css("left",left);
				$("#user_drop_box").css("top",31);
				$("#user_drop_box").slideDown("fast");
			});
		},function(){
			$("#user_drop_box").stopTime();
			$("#user_drop_box").oneTime(300,function(){
				$("#user_drop_box").slideUp("fast");
			});
		});

		$("#user_drop_box").hover(function(){

			$("#user_drop_box").stopTime();
			$(this).show();
		},function(){
			$("#user_drop_box").stopTime();
			$("#user_drop_box").oneTime(300,function(){
				$("#user_drop_box").slideUp("fast");
			});
		});
	}
}


/*加入购车事件*/
function add_cart(obj,type){
    var btn_item = $(obj);

        var buy_num = parseInt($(obj).attr('buy_num'));
        //请求服务端加入购物车表
        var query = new Object();
        query.act = "addcart";
        query.buy_num = buy_num;
        query.data_id = parseInt($(obj).attr('data_id'));
        $.ajax({
            url: AJAX_URL,
            data: query,
            type: "POST",
            dataType: "json",
            success: function (obj) {

                if(type==1){
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
                		location.href=cart_url;
	                }else{
	                    $.showErr(obj.info);
	                }
                }else{
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
	                	var img_obj=$(btn_item).parents(".goods-wrap").find(".imgbox img");
	                   	var left=$(img_obj).offset().left;
	                	var top=$(img_obj).offset().top;
	                	var cart_left=$(".cart_tip .cart_count").offset().left;
	                	var cart_top=$(".cart_tip .cart_count").offset().top;
	                	var float_nav_top=$(".float_nav_bar").offset().top;
	                	var img_clone=img_obj.clone();
	                	$('body').append(img_clone);

	                	img_clone.css({'position':'absolute','left':left,'top':top,'z-index':10000});

	                	if($(".float_nav_bar").is(":hidden")){
	                     	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:cart_top+10},500,function(){
	                    		$(img_clone).remove();
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    		load_cart_list();
	                    	});
	                	}else{

	                    	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:float_nav_top+25},500,function(){
	                    		$(img_clone).remove();
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    		load_cart_list();
	                    	});
	                	}



	                } else {

	                    $.showErr(obj.info);

	                }
                }
            }
        });
}


var duobao_no_html = new Object();

function my_no_all(id,user_id,order_item_id)
{

		if(duobao_no_html['sn'+id])
		{
			 $.weeboxs.open(duobao_no_html['sn'+id], {title:'全部云号码', contentType:'', width:600,showOk:false,showCancel:false});
		}
		else
		{
			$.ajax({
		        url:AJAX_URL,
		        data:{"act":"my_no_all","id":id,"user_id":user_id,"order_item_id":order_item_id},
		        type:"POST",
		        dataType:"json",
		        success:function(obj){
	                $.weeboxs.open(obj.html, {title:'全部云号码', contentType:'', width:600,showOk:false,showCancel:false});
	                duobao_no_html['sn'+id]= obj.html;

	          	}
	    	});
		}
};