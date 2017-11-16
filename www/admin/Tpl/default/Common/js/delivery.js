$(document).ready(function(){
	$("select[name='allow_default']").bind("change",function(){
		set_default_row();
	});
	$("#add_region_conf").bind("click",function(){
		add_region_row();
	});
	
	$("#add_region_conf_lbs").bind("click",function(){
		add_region_row_lbs();
	});
	set_default_row();
});

function set_default_row()
{
	var allow_default = $("select[name='allow_default']").val();
	if(allow_default == 0)
	{
		$("input[name='first_weight']").attr("disabled",true);
		$("input[name='first_fee']").attr("disabled",true);
		$("input[name='continue_weight']").attr("disabled",true);
		$("input[name='continue_fee']").attr("disabled",true);
	}
	else
	{
		$("input[name='first_weight']").attr("disabled",false);
		$("input[name='first_fee']").attr("disabled",false);
		$("input[name='continue_weight']").attr("disabled",false);
		$("input[name='continue_fee']").attr("disabled",false);		
	}
}

function add_region_row()
{
	var row_html = "<div>"+ 
					LANG['FIRST_WEIGHT'] + ":<input type='text' class='textbox' name='region_first_weight[]' style='width:40px;' />&nbsp;" +
					LANG['FIRST_FEE'] + ":<input type='text' class='textbox' name='region_first_fee[]' style='width:40px;' />&nbsp;" +
					LANG['CONTINUE_WEIGHT'] + ":<input type='text' class='textbox' name='region_continue_weight[]' style='width:40px;' />&nbsp;" +
					LANG['CONTINUE_FEE'] + ":<input type='text' class='textbox' name='region_continue_fee[]' style='width:40px;' />&nbsp;" +
					LANG['SUPPORT_REGION'] + ":<input type='text' class='textbox' name='region_support_region_name[]' onfocus='select_delivery_regions(this);' />&nbsp;" +
					"<input type='hidden' name='region_support_region[]' />"+
					" [ <a href='javascript:void(0);' onclick='$(this.parentNode).remove();' style='text-decoration:none;' title='"+LANG['DELETE']+"'>-</a> ] </div>";
	$("#region_conf").append(row_html);
}

function select_delivery_regions(o)
{
	var region_conf_id = $(o.parentNode).find("input[name='region_conf_id[]']").val();
	$.weeboxs.open(ROOT+'?m=Delivery&a=selectRegions&region_conf_id='+region_conf_id, {contentType:'ajax',showButton:true,title:LANG['SELECT_SUPPORT_REGION'],width:300,height:250,onok:function(ob){
		if (confirm(LANG['CONFIRM_SELECT_REGION'])) {
			select_region_ok(o);
			$.weeboxs.close();
		}
	}});
}

function switch_region(o)
{
	var delivery_fee_id = $("input[name='delivery_fee_id']").val();
	var region_id = $(o.parentNode).find("input[name='region_id[]']").val();
	if($.trim($(o).html())=='+')
	{
		//打开
		if($(".region_level_"+region_id).length>0)
		{
			$(".region_level_"+region_id).find("input[name='region_id[]']").attr("checked",$(o.parentNode).find("input[name='region_id[]']").attr("checked"));
			$(".region_level_"+region_id).show();
			$(o).html('-');
		}
		else
		{
			$.ajax({ 
				url: ROOT+"?"+VAR_MODULE+"=Delivery&"+VAR_ACTION+"=getSubRegion&id="+region_id+"&delivery_fee_id="+delivery_fee_id, 
				data: "ajax=1",
				success: function(html){
					$(o.parentNode).append(html);
					if($(o.parentNode).find("input[name='region_id[]']").attr("checked"))
					$(".region_level_"+region_id).find("input[name='region_id[]']").attr("checked",$(o.parentNode).find("input[name='region_id[]']").attr("checked"));
					$(o).html('-');				
				}
			});
		}
		
	}
	else
	{		
		$(".region_level_"+region_id).hide();
		$(o).html('+');
	}
}
function select_region_ok(o)
{
	var cbo = $("input[name='region_id[]']:checked");
	var ids = '';
	var names = '';
	for(i=0;i<cbo.length;i++)
	{
		ids += $(cbo[i]).val()+",";
		names += $(cbo[i].parentNode).find("span").html()+",";
	}
	ids = ids.substr(0,ids.length-1);
	names = names.substr(0,names.length-1);
	$(o.parentNode).find("input[name='region_support_region[]']").val(ids);
	$(o).val(names);

}

function check_sub(o)
{
	var region_id = $(o).val();
	$(".region_level_"+region_id).find("input[name='region_id[]']").attr("checked",$(o).attr("checked"));
}


function add_region_row_lbs()
{
	var row_html = "<div>"+ 
					LANG['FIRST_WEIGHT'] + ":<input type='text' class='textbox' name='region_first_weight[]' style='width:40px;' />&nbsp;" +
					LANG['FIRST_FEE'] + ":<input type='text' class='textbox' name='region_first_fee[]' style='width:40px;' />&nbsp;" +
					LANG['CONTINUE_WEIGHT'] + ":<input type='text' class='textbox' name='region_continue_weight[]' style='width:40px;' />&nbsp;" +
					LANG['CONTINUE_FEE'] + ":<input type='text' class='textbox' name='region_continue_fee[]' style='width:40px;' />&nbsp;" +
					LANG['MIN_SCALE'] + ":<input type='text' class='textbox' name='min_scale[]' style='width:40px;' />&nbsp;" +
					LANG['MAX_SCALE'] + ":<input type='text' class='textbox' name='max_scale[]' style='width:40px;' />&nbsp;" +
					" [ <a href='javascript:void(0);' onclick='$(this.parentNode).remove();' style='text-decoration:none;' title='"+LANG['DELETE']+"'>-</a> ] </div>";
	$("#region_conf_lbs").append(row_html);
}






















