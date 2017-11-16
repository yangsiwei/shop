function toogle_merchant_status()
{
	var is_merchant = $("select[name='is_merchant']").val();
	if(is_merchant==1)
	{
		$("#merchant_name").show();
	}
	else
	{
		$("#merchant_name").find("input[name='merchant_name']").val("");
		$("#merchant_name").hide();
	}
}
function toogle_daren_status()
{
	var is_daren = $("select[name='is_daren']").val();
	if(is_daren==1)
	{
		$("#daren_title").show();
		$("#daren_cate").show();
	}
	else
	{
		$("#daren_title").find("input[name='daren_title']").val("");
		$("#daren_cate").find("input[name='cate_id[]']").attr("checked",false);
		$("#daren_cate").hide();
		$("#daren_title").hide();
	}
}
function check_merchant_name()
{
	var merchant_name = $("input[name='merchant_name']").val();
	if(merchant_name!='')
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=check_merchant_name", 
		data: "merchant_name="+merchant_name+"&ajax=1",
		type:"post",
		dataType: "json",
		success: function(obj){
			if(obj.status==0)
			{
				alert(obj.info);
				$("input[name='merchant_name']").val("");
			}
		}
	});
}

function init_is_robot()
{
	var is_robot = $("select[name='is_robot']").val();
	if(is_robot==1)
	{
		$(".is_robot").hide();
                $(".robot").show();
	}
	else
	{
		$(".is_robot").show();
                $(".robot").hide();
	}
}

$(document).ready(function(){
	$("select[name='is_merchant']").bind("change",function(){
		toogle_merchant_status();
	});
	toogle_merchant_status();
	$("select[name='is_daren']").bind("change",function(){
		toogle_daren_status();
	});
	toogle_daren_status();
	$("input[name='merchant_name']").bind("blur",function(){
		check_merchant_name();
	});
	
	$("select[name='is_robot']").bind("change",function(){
		init_is_robot();
	});
	init_is_robot();
});
