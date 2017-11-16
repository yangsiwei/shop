$(document).ready(function(){
	$("select[name='deal_cate_id']").bind("change",function(){
		init_deal_cate_type();
	});
	init_deal_cate_type();
});

function init_deal_cate_type()
{
	var cate_id = $("select[name='deal_cate_id']").val();
	var id = $("input[name='id']").val();

	if(cate_id>0)
	{
		var query = new Object();
		query.ajax = 1;
		query.cate_id = cate_id;
		query.id = id;
		
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=load_sub_cate", 
			data: query,
			dataType: "json",
			success: function(obj){
				if(obj.status)
				{
					$("#sub_cate_box").show();
					$("#sub_cate_box").find(".item_input").html(obj.html);
				}
				else
				{
					$("#sub_cate_box").hide();
					$("#sub_cate_box").find(".item_input").html("");
				}
				
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		
		});
	}
	else
	{
		$("#sub_cate_box").hide();
		$("#sub_cate_box").find(".item_input").html("");
	}
}
