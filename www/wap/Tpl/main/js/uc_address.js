$(document).ready(function () { 
	init_region_ui_change();
	
	$("#sub_address").bind("click",function(){
		submit_address();
	});

	set_default();

	$(".del").bind("click",function(){
		var url=$(this).attr("url");
		$.showConfirm("确认要删除？",function(){
			del_address(url);
		});
		
	});
	
	
	$("#map_select").click(function(){
		map_show();
	});
	
 });  

function del_address(url){
	var ajaxurl = url;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "GET",
		success: function(obj){
			if(obj.status==1){
					$.showSuccess("删除成功",function(){
						location.href = obj.url;	
					});	
							
			}else{
				$.showErr("删除失败");	
			}
		},
		error:function(ajaxobj)
		{
			
		}
	});	
}

function submit_address()
{
	var query = $("form[name='my_address']").serialize();
	var ajaxurl = $("form[name='my_address']").attr("action");
	var consignee =  $.trim( $("input[name='consignee']").val() );
	var zip =  $.trim( $("input[name='zip']").val() );
	var zip_rul = /^[1-9][0-9]{5}$/;
	
	if(consignee.length > 8){
		alert('收件人名字要在8个字以内哦~');
		return false;
	}
	if(zip !=""){
		if (zip.length != 6 || ! zip_rul.test(zip)){
			alert('请正确填写6个数字的邮编哦~');
			return false;
		}
	}
	
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(obj){
			if(obj.status==1){
				$.showSuccess("保存成功",function(){
					location.href = obj.url;
				});
				
			}else if(obj.status==0){
				if(obj.info)
				{
					$.showErr(obj.info,function(){
						if(obj.url)
							location.href = obj.url;
					});
				}
				else
				{
					if(obj.url)
						location.href = obj.url;
				}
				
			}else{
				
			}
		},
		error:function(ajaxobj)
		{
			
		}
	});	
}


/**
 * 初始化地区切换事件
 */
function init_region_ui_change(){	

	$.load_select = function(lv)
	{
		var name = "region_lv"+lv;
		var next_name = "region_lv"+(parseInt(lv)+1);
		var id = $("select[name='"+name+"']").val();
		
		if(lv==1)
		var evalStr="regionConf.r"+id+".c";
		if(lv==2)
		var evalStr="regionConf.r"+$("select[name='region_lv1']").val()+".c.r"+id+".c";
		if(lv==3)
		var evalStr="regionConf.r"+$("select[name='region_lv1']").val()+".c.r"+$("select[name='region_lv2']").val()+".c.r"+id+".c";
		
		if(id==0)
		{
			var html = "<option value='0'>=请选择=</option>";
		}
		else
		{
			var regionConfs=eval(evalStr);
			evalStr+=".";
			var html = "<option value='0'>=请选择=</option>";
			for(var key in regionConfs)
			{
				html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
			}
		}
		$("select[name='"+next_name+"']").html(html);		
		if(lv == 4)
		{
			//load_delivery();
		}
		else
		{
			$.load_select(parseInt(lv)+1);
		}	
	};
	
	$("select[name='region_lv1']").bind("change",function(){
		$.load_select("1");
	});
	$("select[name='region_lv2']").bind("change",function(){
		$.load_select("2");
	});
	$("select[name='region_lv3']").bind("change",function(){
		$.load_select("3");
	});	
	$("select[name='region_lv4']").bind("change",function(){
		$.load_select("4");
	});	
}



function set_default(){
	$(".set_default").bind("click",function(){
		var ajaxurl = $(this).attr("dfurl");
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "GET",
			success: function(obj){
				if(obj.status==1){
					$.showSuccess("设置成功",function(){
						location.href = obj.url;
					});	
					
				}else{
					$.showErr("设置失败");
				}
			},
			error:function(ajaxobj)
			{
				
			}
		});		
	});
}

/**
 * 显示地图
*/
function map_show(){
	//修改header里的后退数值
	try{
		$('#header_back_btn').attr('backurl',window.location.href);
	}catch(e){};
	
	$('.map_select_box').show();
}