$(document).ready(function(){	
	
	$("form[name='do_charge']").bind("submit",function(){
		
		var money = $("input[name='money']").val();
		var payment_id = $("input[name='payment_id']").val();		

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

        var money;
        $(".select_num").click(function(){
            money = String($(".selected").val());
        });

        if(payment_id == 7){
            window.location.herf="http://www.caizhi998.com/Public/php/shanpay.php?money = "+money;
        }

		var query = $(this).serialize();
		var action = $(this).attr("action");

		$.ajax({
			url:action,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status==1)
				{
					if(obj.is_app){
						var json = '{"url":"'+obj.jump+'&is_app=1","open_url_type":"1"}';
						App.open_type(json);
					}else{
						location.href = obj.jump;
					}
				}
				else if(obj.status==2)
				{
					try{
						var str = pay_sdk_json(obj['sdk_code']);
						App.pay_sdk(str);
					}catch(ex)
					{
						$.showErr(ex);
					}

				}
				else
				{
					if(obj.info)
					{
						$.showErr(obj.info,function(){
							if(obj.jump)
							{
								location.href = obj.jump;
							}
						});
					}
					else
					{
						if(obj.jump)
						{
							location.href = obj.jump;
						}
					}

				}
			}
		});

		return false;

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


	

	
	// $(".pay_select").bind("click",function(){
	// 	$(".pay_select span").removeClass("checked");
	// 	$(this).find("span").addClass("checked");
	// 	$(".pay_select").find("input[name='payment_id']").attr("checked",false);
	// 	$(this).find("input[name='payment_id']").attr("checked",true);
    //
	// });
	
});
