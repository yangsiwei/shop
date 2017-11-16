$(document).ready(function(){
	
	// 加载表单
	load_consignee();
	
	// 提交表单
	$("#sub_address").bind("click",function(){
		submit_address();
	});
	
	// 删除地址
	$(".del-consignee").live("click",function(){
		var url=$(this).attr("url");
		$.showConfirm("确定要删除吗？",function(){		
			$('.weedialog').hide();
			$('.dialog-mask').hide();
			del_address(url);
		});			
		return false;  // 防止冒泡事件
	});
	
	// 修改地址
	$(".save-consignee").live("click",function(){
		// 如果已经打开且修改的是同一个地址，第二次点击则隐藏
		var id = $(this).attr('url');
		if( $('.set-add-consignee').hasClass('display-block') && $("input[name='consignee_id']").val() == id ){
			$('.set-add-consignee').removeClass('display-block').addClass('display-none');
			$("#cart_consignee").attr("rel", '');
		}else{
			$("#cart_consignee").attr("rel", id);
			$('.set-add-consignee').removeClass('display-none').addClass('display-block');
		}
		load_consignee();
		return false;  // 防止冒泡事件
	});
	
	// 设置默认
	$(".set-default").live("click",function(){
		var ajaxurl = $(this).attr("url");
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "GET",
			success: function(obj){
				if(obj.status==2){
					ajax_login();
				}else if(obj.status==1){
					$.showSuccess("设置成功",function(){
						;
						//location.reload();
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
	
});


function del_address(url){
		var ajaxurl = url;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			type: "GET",
			success: function(obj){
				if(obj.status==2){
					ajax_login();
				}else if(obj.status==1){
					 
					location.reload();
					 		
				}else{
					$.showErr("删除失败");
				}
			},
			error:function(ajaxobj)
			{
				
			}
		});	
}


//装载配送地区
function load_consignee()
{	
		var consignee_id = $("#cart_consignee").attr("rel");
		var query = new Object();
		query.act = "load_consignee";
		query.id = consignee_id;
		$.ajax({ 
			url: AJAX_URL,
			data:query,
			dataType:"json",
			type:"POST",
			success: function(data){
				$("#cart_consignee").html(data.html);				
				init_region_ui_change();
				init_ui_select();
				init_ui_textbox();	
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
			var html = "<option value='0'>="+LANG['SELECT_PLEASE']+"=</option>";
		}
		else
		{
			var regionConfs=eval(evalStr);
			evalStr+=".";
			var html = "<option value='0'>="+LANG['SELECT_PLEASE']+"=</option>";
			for(var key in regionConfs)
			{
				html+="<option value='"+eval(evalStr+key+".i")+"'>"+eval(evalStr+key+".n")+"</option>";
			}
		}
		$("select[name='"+next_name+"']").html(html);
		$("select[name='"+next_name+"']").ui_select({refresh:true});
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


function submit_address()
{
	var query = $("form[name='my_address']").serialize();
	var ajaxurl = $("form[name='my_address']").attr("action");
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(obj){
			if(obj.status==2){
				ajax_login();
			}else if(obj.status==3){
				$.showErr("配送地址最多5个");
			}else if(obj.status==1){
				$.showSuccess("地址保存成功",function(){
					$('.set-add-consignee').removeClass('display-block').addClass('display-none');
					$('.address-list').html(obj.consignee_li);
					// 设置默认收货地址
					$("input[name='consignee_id']").val( $('.address-list').find('.current').attr('data-id') );
				});				
			}else{
				$.showErr(obj.info);
			}
		},
		error:function(ajaxobj)
		{
			
		}
	});	
}


 


