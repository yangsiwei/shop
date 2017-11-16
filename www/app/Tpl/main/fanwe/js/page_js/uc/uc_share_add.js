$(function(){
	
	//上传控件
	$(".share_imgs_upbtn").ui_upload({multi:true,FilesAdded:function(files){
		//选择文件后判断
		if($(".share_imgs_upload_box").find(".img_item").length+files.length>10)
		{
			$.showErr("最多只能传10张图片");
			return false;
		}
		else
		{
			for(i=0;i<files.length;i++)
			{
				var html = '<div class="img_item"><div class="loader"></div></div>';
				var dom = $(html);		
				$(".share_imgs_upload_box").append(dom);	
			}
			uploading = true;
			return true;
		}
	},FileUploaded:function(responseObject){
		if(responseObject.error==0)
		{
			var first_loader = $(".share_imgs_upload_box").find(".img_item div.loader:first");
			var box = first_loader.parent();
			first_loader.remove();
			var html = '<a href="javascript:void(0);" alt="删除" title="删除" data-id="'+responseObject.id+'"></a>'+
			'<table><tr><td valign="middle" align="center"><img src="'+APP_ROOT+'/'+responseObject.web_140+'" /></td></tr></table>'+
			'<input type="hidden" name="share_imgs[]" value="'+responseObject.url+'" /><input type="hidden" name="share_image_ids[]" value="'+responseObject.id+'" />';
			$(box).html(html);
			$(box).find("a").bind("click",function(){
				$(this).parent().remove();
			});
		}
		else
		{
			$.showErr(responseObject.msg);
		}
	},UploadComplete:function(files){
		//全部上传完成
		uploading = false;
	},Error:function(errObject){
		$.showErr(errObject.message);
	}});
	
	//页面初始化函数
	$(".share-add-btn").click(function() {
		$(".add-share-box").hide();
		$(".share-add-form").show();
	});
	$(".share-textarea").focus(function() {
		$(".textarea-holder").hide();
	});
	$(".share-textarea").blur(function() {
		if($(this).val().length==0){
			$(".textarea-holder").show();
		}
		
	});
	
	var is_ajax = 1;
	$("form[name='share_form']").submit(function(){
		
		var title = $("input[name='title']").val();
		var content = $("textarea[name='content']").val();
		
		if(title==''||title.trim().getlength()<6){
			$.showErr("请留下一个最少6个字的晒单主题吧~");
			return false;
		}
		if(content==''||content.trim().getlength()<30){
			$.showErr("“幸运感言”，字不在多最少30个~");
			return false;
		}
		if($("input[name='share_image_ids[]']").length<3){
			$.showErr("T_T...至少给我个3张无死角的靓照吧~");
			return false;
		}
		

		if(is_ajax){
			is_ajax = 0;
			var query =  $(this).serialize();
			$.ajax({
				url: $(this).attr("action"),
				type: "POST",
				data:query,
				dataType: "json",
				success: function(data){
					is_ajax=1;
					if(data.status==1000)
			 		{
			 			ajax_login();
			 		}
			 		else if(data.status)
			 		{
			 			$.showSuccess(data.info,function(){
			 				window.location = data.jump;
			 			});
			 			
			 		}
			 		else
			 		{
			 			$.showErr(data.info);
			 		}
					return false;
				}
			});
		}else{
			$.showErr("客官别急~我在晒单的路上...");
		}
		return false;
	});
	
});

String.prototype.getlength = function() 
{ 
	return this.replace('/[^\x00-\xff]/ig', "aa").length; 
}