function htmlEncode(s) {
    var r = "", c;
    for (var i = 0; i < s.length; i++) {
        c = s.charCodeAt(i);
        r += (c < 32 || c == 38 || c > 127) ? ("&#" + c + ";") : s.charAt(i);
    }
    return r;
};
var keids = [];
var isrefresh = true;
$(document).ready(function(){
	
	
	//绑定ui_upload
	
	$(".ui_upload").each(function(i,o){
		$(o).ui_upload({FilesAdded:function(files){
			return confirm("上传新图标库，将替换原图标库，可能需要重新设置所有的分类图标。确定吗？");
		},FileUploaded:function(ajaxobj){
			if(ajaxobj.status)
			{
				alert("图标库更新成功");
				location.reload();
			}
			else
			{
				alert(ajaxobj.info);
			}
		},UploadComplete:function(files){
			
		},Error:function(errObject){
			alert(errObject.message);
		}});
	});
	
	//绑定图标选择
	if($("#ui_iconfont_select").length>0)
	{
		var box = $("#ui_iconfont");
		$.ajax({
			url:ICON_FETCH_URL,
			dataType:"json",
			type:"POST",
			success:function(obj){
				
				$("#ui_iconfont_select").html(obj.html);
				$(".pickfont").bind("click",function(){
					var code = $.trim($(this).attr("rel"));
					$(box).find(".diyfont").html(code);
					$(box).find("input[name='iconfont']").val(htmlEncode(code));
				});
				
				if($("#ui_iconfont_select").height()>200)
				{
					$("#ui_iconfont_select").css("height",200);
				}
			}
		});
	}

	
	init_word_box();
	$("#info").ajaxStart(function(){
		 $(this).html(LANG['AJAX_RUNNING']);
		 $(this).show();
	});
	$("#info").ajaxStop(function(){
		
		$("#info").oneTime(2000, function() {				    
			$(this).fadeOut(2,function(){
				$("#info").html("");				
			});			    	
		});	
	});
	/*
	$(window).bind('beforeunload',function(){
		if($("form[name='edit'],form.ajax_form").length>0&&isrefresh)
		return '您输入的内容尚未保存，确定离开此页面吗？';
	});
        */
	//绑定ajax提交
	$("form[name='edit'],form.ajax_form").bind("submit",function(){
		var on_submit = $(this).attr("on_submit");
		if(on_submit)
		{
			var fn = window[on_submit];
			var result = fn();
			if(!result)
			{
				return result;
			}
		}
		if(check_require())
		{
			if(confirm("确认要保存吗？"))
			{
				for(var idx=0;idx<keids.length;idx++)
				{
					KE.util.setData(keids[idx])
				}
				var url = $(this).attr("action");
				var query = $(this).serialize()+"&ajax=1";
				$.ajax({
					url:url,
					data:query,
					dataType:"json",
					type:"POST",
					success:function(obj){
						if(obj.status)
						{
							isrefresh = false;
							alert(obj.info);
							location.href = obj.jumpUrl;
						}
						else
						{
							alert(obj.info);
						}
					}
				});
			}
			
		}		
		return false;
	});
	
	$("tr.row").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
});


function check_require()
{
	var doms = $(".require");
	var check_ok = true;
	$.each(doms,function(i, dom){
		if( $.trim( $(dom).val() ) =='' || ( $(dom).val()=='0' &&  !$(dom).hasClass('except_zero') ) )
		{						
				var title = $(dom).parent().parent().find(".item_title").html();
				if(!title)
				{
					title = '';
				}
				if(title.substr(title.length-1,title.length)==':')
				{
					title = title.substr(0,title.length-1);
				}
				if($(dom).val()=='')
				TIP = LANG['PLEASE_FILL'];
				if($(dom).val()=='0')
				TIP = LANG['PLEASE_SELECT'];						
				alert(TIP+title);
				$(dom).focus();
				check_ok = false;
				return false;						
		}
	});
	if(!check_ok)
	return false;
	else
		return true;
}

//排序
function sortBy(field,sortType,module_name,action_name)
{
	location.href = CURRENT_URL+"&_sort="+sortType+"&_order="+field+"&";
}
//添加跳转
function add()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=add";
}

//编辑跳转
function edit(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id;
}
//添加跳转
function add_goods()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=shop_add";
}
//编辑跳转
function edit_goods(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=shop_edit&id="+id;
}

//添加跳转
function add_deal_youhui()
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=youhui_add";
}
//编辑跳转
function edit_deal_youhui(id)
{
	location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=youhui_edit&id="+id;
}

//全选
function CheckAll(tableID)
{
	$("#"+tableID).find(".key").attr("checked",$("#check").attr("checked"));
}

function toogle_status(id,domobj,field)
{
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=toogle_status&field="+field+"&id="+id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){

			if(obj.data=='1')
			{
				$(domobj).html(LANG['YES']);
			}
			else if(obj.data=='0')
			{
				$(domobj).html(LANG['NO']);
			}
			else if(obj.data=='')
			{
				
			}
			$("#info").html(obj.info);
		}
	});
}

//改变状态
function set_effect(id,domobj)
{
		$.ajax({ 
				url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_effect&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){

					if(obj.data=='1')
					{
						$(domobj).html(LANG['IS_EFFECT_1']);
					}
					else if(obj.data=='0')
					{
						$(domobj).html(LANG['IS_EFFECT_0']);
					}
					else if(obj.data=='')
					{
						
					}
					$("#info").html(obj.info);
				}
		});
}

//改变状态
function set_fictitious(id,domobj)
{	
	$.ajax({ 
				url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_fictitious&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){

					if(obj.data=='1')
					{
						$(domobj).html(LANG['IS_FICTITIOUS_1']);
					}
					else if(obj.data=='0')
					{
						$(domobj).html(LANG['IS_FICTITIOUS_0']);
					}
					else if(obj.data=='')
					{
						
					}
					$("#info").html(obj.info);
				}
		});
}

//改变状态
function set_verify(id,domobj)
{
		$.ajax({ 
				url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_verify&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){

					if(obj.data=='1')
					{
						$(domobj).html(LANG['IS_EFFECT_1']);
					}
					else if(obj.data=='0')
					{
						$(domobj).html(LANG['IS_EFFECT_0']);
					}
					else if(obj.data=='')
					{
						
					}
					$("#info").html(obj.info);
				}
		});
}

function set_sort(id,sort,domobj)
{
	$(domobj).html("<input type='text' value='"+sort+"' id='set_sort' class='require'  />");
	$("#set_sort").select();
	$("#set_sort").focus();
	$("#set_sort").bind("blur",function(){
		var newsort = $(this).val();
		
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=set_sort&id="+id+"&sort="+newsort, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				if(obj.status)
				{
					$(domobj).parent().html('<span class="sort_span" onclick="set_sort('+id+','+newsort+',this);">'+newsort+'</span>');
					
				}
				else
				{
					$(domobj).html(sort);
				}
				$("#info").html(obj.info);

			}
	});
});
}

//普通删除
function del(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=delete&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}
//完全删除
function foreverdel(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=foreverdelete&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}

//完全删除
function biz_submit_del(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['DELETE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_DELETE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=biz_submit_del&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href=location.href;
			}
	});
}

//恢复
function restore(id)
{
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['RESTORE_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");
	}
	if(confirm(LANG['CONFIRM_RESTORE']))
	$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=restore&id="+id, 
			data: "ajax=1",
			dataType: "json",
			success: function(obj){
				$("#info").html(obj.info);
				if(obj.status==1)
				location.href = location.href;
			}
	});
}

//节点全选
function check_node(obj)
{
	$(obj.parentNode.parentNode.parentNode).find(".node_item").attr("checked",$(obj).attr("checked"));
}
function check_is_all(obj)
{
	if($(obj.parentNode.parentNode.parentNode).find(".node_item:checked").length!=$(obj.parentNode.parentNode.parentNode).find(".node_item").length)
	{
		$(obj.parentNode.parentNode.parentNode).find(".check_all").attr("checked",false);
	}
	else
		$(obj.parentNode.parentNode.parentNode).find(".check_all").attr("checked",true);
}
function check_module(obj)
{
	if($(obj).attr("checked"))
	{
		$(obj).parent().parent().find(".check_all").attr("disabled",true);
		$(obj).parent().parent().find(".node_item").attr("disabled",true);
	}
	else
	{
		$(obj).parent().parent().find(".check_all").attr("disabled",false);
		$(obj).parent().parent().find(".node_item").attr("disabled",false);	
	}
}


function export_csv()
{
	var inputs = $(".search_row").find("input");
	var selects = $(".search_row").find("select");
	var param = '';
	for(i=0;i<inputs.length;i++)
	{
		if(inputs[i].name!='m'&&inputs[i].name!='a')
		param += "&"+inputs[i].name+"="+$(inputs[i]).val();
	}
	for(i=0;i<selects.length;i++)
	{
		param += "&"+selects[i].name+"="+$(selects[i]).val();
	}
	var url= ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=export_csv";
	location.href = url+param;
}

function init_word_box()
{
	$(".word-only").bind("keydown",function(e){
		if(e.keyCode<65||e.keyCode>90)
		{
			if(e.keyCode != 8)
			return false;
		}
	});
}

function reset_sending(field)
{
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"=Index&"+VAR_ACTION+"=reset_sending&field="+field, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			$("#info").html(obj.info);			
		}
	});
}

function search_supplier()
{
	var key = $("input[name='supplier_key']").val();
	if($.trim(key)=='')
	{
		alert(INPUT_KEY_PLEASE);
	}
	else
	{
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"=SupplierLocation&"+VAR_ACTION+"=search_supplier", 
			data: "ajax=1&key="+key,
			type: "POST",
			success: function(obj){
				$("#supplier_list").html(obj);
			}
		});
	}
}


function search_supplier_location()
{
	var key = $("input[name='location_key']").val();
	if($.trim(key)=='')
	{
		alert(INPUT_KEY_PLEASE);
	}
	else
	{
		$.ajax({ 
			url: ROOT+"?"+VAR_MODULE+"=SupplierLocation&"+VAR_ACTION+"=search_supplier_location", 
			data: "ajax=1&key="+key,
			type: "POST",
			success: function(obj){
				$("#location_list").html(obj);
			}
		});
	}
}





userCard=(function(){	
	return {
		load : function(e,id){
	
				
			}
	  	};
})();


function load_balance(id)
{
	deal_id = $("input[name='hd_deal_id']").val();
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert(LANG['CHECK_EMPTY_WARNING']);
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");		
	}	
	
	$.ajax({ 
		url: ROOT+"?"+VAR_MODULE+"=Balance&"+VAR_ACTION+"=check_balance&deal_id="+deal_id+"&id="+id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				$.weeboxs.open(ROOT+'?m=Balance&a=load_balance&id='+id+"&deal_id="+deal_id, {contentType:'ajax',showButton:false,title:LANG['DO_BALANCE'],width:600,height:200});
			}
			else
			{
				alert(obj.info);
			}
		}
	});
	
	
}


function load_ofc(id,dataurl,w,h)
{
	swfobject.embedSWF(
			ofc_swf, id,
			w, h, "9.0.0", "expressInstall.swf",
			{"data-file":dataurl} ,{"wmode":"transparent"});
}



