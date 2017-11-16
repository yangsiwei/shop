
function init_fx_level(is_init)
{
	var fx_salary_type = '';
	var condition = '';
	var fx_set_type = $("input[name='fx_set_type']").val();
	if($("select[name='fx_salary_type']").size()>0){
		fx_salary_type = $("select[name='fx_salary_type']").val();
		condition += "&fx_salary_type="+fx_salary_type;
	}
	if($("input[name='level_id']").size()>0){
		condition += "&level_id="+$("input[name='level_id']").val();
	}

	if($("input[name='check_ids']").size()>0){
		if($("input[name='check_ids']").val()>0){
			condition += "&deal_id="+$("input[name='check_ids']").val();
		}
		
	}
	
	if(is_init == 1){
		condition +="&is_init=1";
	}
	
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=load_fx_level&fx_set_type="+fx_set_type+condition, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			$(".fx_is_open").html(obj.fx_is_open_html);
			$(".fx_default_status").html(obj.default_html);
			$(".fx_salary_type_qrcode").html(obj.qrcode_html+'&nbsp;<span class="tip_span">永久二维码，是无过期时间的，但数量较少（目前为最多10万个）</span>');
			$(".fx_salary_type_box").html(obj.type_html);
			$(".fx_level_box").html(obj.level_html);
		}
	});	
}


