$(document).ready(function(){
	
	init_nav_type($("#typeSelect").val());
	$("#typeSelect").bind("change",function(){
		init_nav_type($(this).val());	
	});
	init_nav_cfg();
});



function init_nav_cfg()
{
	var navs=nav_cfg['web']['nav'];

	$("#advposition").find("option[value='1']").remove();

	

	$("#typeSelect").empty();
	for(nav_key in navs)
	{
		nav_item = navs[nav_key];
		
		var select_str = "";
		if(nav_item['type']==adv_type)
		{
			select_str = "selected='selected'";
		}
		$("#typeSelect").append("<option value='"+nav_item['type']+"' "+select_str+" >"+nav_item['name']+"</option>");
	}
}

function init_nav_type(type)
{
	$("#type").hide();			

	var navs=nav_cfg['web']['nav'];
	var val = type;
	
	for(nav_key in navs)
	{
		nav_item = navs[nav_key];
		if(val==nav_item['type'])
		{
			if(nav_item['field']!="")
			{
				$("#type").show();		
				$("#type").find(".item_title").html(nav_item['fname']);
				$("#type").find(".item_input input").attr("name",nav_item['field']);
				
				
				var data_val = "";
				try{
					data_val = data_json[nav_item['field']];
				}catch(ex)
				{
					
				}
				
				if(data_val)
				{
					$("#type").find(".item_input input").val(data_val);
				}
				else
				{
					$("#type").find(".item_input input").val("");
				}
			}
			break;
		}
	}	
}
	