	$(document).ready(function(){
		load_exchange_row();
		$("select[name='send_type']").bind("change",function(){load_exchange_row();});
		
		
		load_ecv_preview();
		$("select[name='tpl']").bind("change",function(){
			load_ecv_preview();
		});
		
		load_money_type();
		$("select[name='sm_way']").bind("change",function(){
			load_money_type();
		});
		
		$("#is_all").bind("change",function(){
			var is_all = $(this).find("input[name='is_all']").attr('checked');
			if(is_all==true){
				for(i=0;i<$("#use_range").find("input").length;i++){
					$("#use_range").find("input")[i].checked=true
				} 
			}else{
				for(i=0;i<$("#use_range").find("input").length;i++){
					$("#use_range").find("input")[i].checked=false
				}
			}
			   
		});
		$(".not_all").bind("change",function(){
			var not_all = $(this).find("input").attr('checked');
			if(not_all==false){
				$("#use_range").find("input[name='is_all']").get(0).checked=false; 
			}
				
		});
		
	});
	
	
	function load_money_type(){
		var sm_way = $("select[name='sm_way']").val();
		
		if(sm_way == 0){
			// 固额
			$('#defaule_money').find('.item_title').text('代金券面额');
			$('#defaule_money').find('.tip_span').text('请填写整数');
			$('#rand_money').find('input').removeClass('require');
			$("input[name='money']").addClass('require');
			$('#defaule_money').show();
			$('#rand_money').hide();
		}else if(sm_way == 1){
			// 随机
			$('#defaule_money').hide();
			$("input[name='money']").removeClass('require');
			$('#rand_money').show();
			$('#rand_money').find('input').addClass('require');
		}else if(sm_way == 2){
			// 充值金额百分比
			$('#defaule_money').find('.item_title').text('充值金额百分比');
			$('#rand_money').find('input').removeClass('require');
			$("input[name='money']").addClass('require');
			$('#defaule_money').find('.tip_span').text('%');
			$('#rand_money').hide();
			$('#defaule_money').show();
		}else{
			$('#rand_money').find('input').removeClass('require');
			$('#rand_money').hide();
		}
	}
	
	function load_exchange_row()
	{
		var send_type = $("select[name='send_type']").val();
		
		$("input[name='use_limit']").removeAttr("readonly");
		
		if(send_type==1)
		{
			$("input[name='exchange_sn']").val("");
			$("input[name='exchange_limit_bonus']").val("");
			$("#minchange_money").hide();
			$("#bonus_row").hide();
			$("#exchange_row").show();
			$("#share_url_row").hide();
			$("#tpl_row").hide();
			$("#total_limit").show();
			$("#sm_way").hide();
			$('#rand_money').hide();
			$('#defaule_money').find('.item_title').text('代金券面额');
			$('#defaule_money').find('.tip_span').text('请填写整数');
			
		}
		else if(send_type==2)
		{
			$("input[name='exchange_score']").val("");
			$("input[name='exchange_limit_score']").val("");
			$("#minchange_money").hide();
			$("#exchange_row").hide();
			$("#bonus_row").show();
			$("#share_url_row").show();
			$("#tpl_row").show();
			$("#total_limit").show();
			$("#sm_way").hide();
			$('#rand_money').hide();
			$('#defaule_money').find('.item_title').text('代金券面额');
			$('#defaule_money').find('.tip_span').text('请填写整数');
		}
		else if( send_type==4 || send_type==5 || send_type==6 || send_type==7)
		{
				if(send_type==5){
					$('.sm_way_2').show();
				}else{
					
					$('.sm_way_2').hide(); 
					var sm_way = $("select[name='sm_way']").val();
					if(sm_way == 2){
						sm_way = 0;
					}
					if(sm_way){
						$("select[name='sm_way']").val(sm_way);
					}else{
						$("select[name='sm_way']").val('0');
						$('#defaule_money').find('.item_title').text('代金券面额');
						$('#defaule_money').find('.tip_span').text('请填写整数');
					}
				}
				if(send_type==6){
					$("#minchange_money").show();
				}else{
					$("#minchange_money").hide();
				}
			
			$("input[name='use_limit']").val("1");
			$("input[name='use_limit']").attr('readonly', 'readonly');
			
			$("#sm_way").show();
		 
			$("input[name='exchange_score']").val("");
			$("input[name='exchange_limit_score']").val("");	
			$("input[name='exchange_sn']").val("");
			$("input[name='exchange_limit_bonus']").val("");	
			$("#exchange_row").hide();
			$("#bonus_row").hide();
			$("#share_url_row").hide();
			$("#tpl_row").hide();
			$("#total_limit").hide();
			 
		}
		else
		{			
			$("input[name='exchange_score']").val("");
			$("input[name='exchange_limit_score']").val("");	
			$("input[name='exchange_sn']").val("");
			$("input[name='exchange_limit_bonus']").val("");	
			$("#minchange_money").hide();
			$("#exchange_row").hide();
			$("#bonus_row").hide();
			$("#share_url_row").hide();
			$("#tpl_row").hide();
			$("#total_limit").hide();
			$("#sm_way").hide();
			$('#rand_money').hide();
			$('#defaule_money').find('.item_title').text('代金券面额');
			$('#defaule_money').find('.tip_span').text('请填写整数');
		}
	}
	

	function load_ecv_preview()
	{
		var tpl_file = $("select[name='tpl']").val();
		var t = tpl_file.split(".");
		var ecv_key = t[0];
		$("#preview").html("<img src='"+APP_ROOT+"/system/ecv_tpl/preview/"+ecv_key+".jpg' />");
	}