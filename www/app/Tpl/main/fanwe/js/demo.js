//首页的部份演示脚本
$(document).ready(function(){
				
	//空表单的submit点击
	$("button.noform").bind("click",function(){
		$.showErr("橙色提交空表单按钮被点击");
	});
	
	//以下代码testformbtn的点击事件，不会被执行，因为有表单事件，已被绑定为表单的submit
	$("button.testformbtn").bind("click",function(){
		$.showErr("该事件不会被执行");
	});
	
	$("form[name='testform']").bind("submit",function(){
		if($("#test1").val()=="")
		{
			$.showConfirm("确认为空",function(){
				$.showSuccess("确认成功");
			});
			return false;
		}
	});
	
	//绑定上传
	$("#uploader").ui_upload({FilesAdded:function(files){
		//alert("FilesAdded:"+files.length);
		return true;
	},FileUploaded:function(responseObject){
		alert("FileUploaded:"+responseObject.file.url);
	},UploadComplete:function(files){
		//alert("UploadComplete:"+files.length);
	},Error:function(errObject){
		
	}});
	
	

	//评分星级的测试
	$("#demostar_ipt").bind("onchange",function(){		
		alert("当前"+$(this).val()+"星");
	});
	$("#demostar_ipt").bind("uichange",function(){		
		$("#starcontent").html("当前"+$(this).attr("sector")+"星");
	});
	$("#demostar").click(function(){
		$("#demostar_ipt").val(Math.random()*5);
		$("#demostar_ipt").ui_starbar({refresh:true});
	});
});