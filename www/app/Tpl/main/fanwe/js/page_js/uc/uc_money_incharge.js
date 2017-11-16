$(document).ready(function(){	
	
	$("form[name='incharge_form']").bind("submit",function(){
		var money = $("input[name='money']").val();
		var payment_id = $("input[name='payment']").val();

		if($.trim(payment_id)==""||isNaN(payment_id)||parseFloat(payment_id)<=0)
		{
			$.showErr("请选择支付方式");
			return false;
		}
		
		if($.trim(money)==""||isNaN(money)||parseFloat(payment_id)<=0)
		{
			$.showErr("请选择正确的充值金额");
			return false;
		}
		
		return true;

	});
	
	
	$(".select_num").bind("click",function(){
		$(".select_num").removeClass("selected");
		$(this).addClass("selected");
		$("input.select_num").attr('name','xxx');
        $(this).attr('name','money');
	});
	
	$(".select_num1").focus(function(){
		$(".select_num").removeClass("selected");
		$(this).addClass('selected');
	});
	$(".select_num1").blur(function(){
		$(".select_num1").removeClass("selected");
		// $("input[name='money']").val($(this).val());
	});

});
