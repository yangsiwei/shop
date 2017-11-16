$(document).ready(function(){
	
	$("#group").hide();
	$("select[name='page_module_ins']").removeClass("require");
	init_page_module();
	$("select[name='page_module']").bind("change",function(){
		init_page_module();
		if($("select[name='page_module']  option:selected").val()!='index|index#index')
		{
			$("#group").hide();
			$("select[name='page_module_ins']").removeClass("require");
		}
	});
	if($("select[name='group']  option:selected").val()=='cate_banner')
	{
		$("#group").show();
		$("select[name='page_module_ins']").addClass("require");
	}
	$("select[name='group']").bind("change",function(){
		
		if($("select[name='group']  option:selected").val()=='cate_banner')
			{
				$("#group").show();
				$("select[name='page_module_ins']").addClass("require");
			}
		if($("select[name='group']  option:selected").val()!='cate_banner')
		{
			$("#group").hide();
			$("select[name='page_module_ins']").removeClass("require");
		}
	});
});

function init_page_module()
{
	var page_module = $("select[name='page_module']").val();
	var page_group = webadv_cfg_json[page_module];
	var html = "<option value='0'>未选择分组</option>";
	if(page_group)
	{
		var groups = page_group['groups'];
		for(var i=0;i<groups.length;i++)
		{
			html+="<option value='"+groups[i]['group']+"'>"+groups[i]['name']+"</option>";
		}
	}
	$("select[name='group']").html(html);
	if(group_selected)
	{
		$("select[name='group']").val(group_selected);
	}
}
function lll()
{
	
}




