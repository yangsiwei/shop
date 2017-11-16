$(document).ready(function(){
	load_zt_adv_id();
	$("#advposition").bind("change",function(){
		load_zt_adv_id();
	});
	
	load_zt_id_layout();
	$("#zt_id").bind("change",function(){
		load_zt_id_layout();
	});
});

function load_zt_adv_id()
{
	var advposition = $("#advposition").val();
	if(advposition==2)
	{
		$("#zt_tr").show();
		$("#zt_layout").show();
	}
	else
	{
		$("#zt_tr").hide();
		$("#zt_layout").hide();
	}
}


function load_zt_id_layout()
{
	var zt_id = $("#zt_id").val();
	var adv_id = $("input[name='id']").val();
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=load_zt_id&zt_id="+zt_id+"&adv_id="+adv_id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				$("#zt_layout").show();
				var layout_list = obj.data;
				var html = "";
				for(i=0;i<layout_list.length;i++)
				{
					html += "<option value='"+layout_list[i]['key']+"'";
					if(layout_list[i]['selected'])
						html+=" selected='selected' ";
					html += ">"+layout_list[i]['key']+"</option>";
				}
				$("select[name='zt_position']").html(html);	
				$("#preview").html("<img src='"+obj.preview+"' />");
			}
			else
			{
				$("#preview").find("img").remove();
				$("#zt_layout").hide();
			}
			
		}
	});
}