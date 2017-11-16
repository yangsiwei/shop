$(document).ready(function(){
	init_buy_button();
});

function init_buy_button()
{
	$(".paybutton").bind("click",function(){
		$.weeboxs.open(PAY_TIP, {boxid:'pay_tip',contentType:'ajax',showButton:false, showCancel:false, showOk:false,title:'支付提示',width:450,type:'wee',onopen:function(){
			init_ui_button();
			$("#pay_done").bind("click",function(){					
				location.href = $(this).attr("url");
			});
			$("#pay_retry").bind("click",function(){					
				location.href = $(this).attr("url");
			});
			
		}});
	});
}