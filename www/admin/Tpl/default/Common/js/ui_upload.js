(function($) {   
	

	
	/**
	 * 基于plupload的上传控件
	 */
	$.fn.ui_upload = function(options){
		var op = {url:UPLOAD_URL,multi:false,FilesAdded:null,FileUploaded:null,UploadComplete:null,Error:null,extensions:ALLOW_IMAGE_EXT}; 
		options = $.extend({},op, options);		
		var btn = $(this); 

		var uploader = new plupload.Uploader({
			browse_button : btn[0], 
			url : options.url,
			flash_swf_url : UPLOAD_SWF,
			silverlight_xap_url : UPLOAD_XAP,
			multi_selection:options.multi,
			filters : {
				max_file_size : MAX_IMAGE_SIZE,
				mime_types: [
					{title : "Image files", extensions : options.extensions}
				]
			}
		});

		uploader.init();
		

		/**
		 * 当文件添加到上传队列后触发
		 * 监听函数参数：(uploader,files)
		 * uploader为当前的plupload实例对象，files为一个数组，里面的元素为本次添加到上传队列里的文件对象
		 */
		uploader.bind('FilesAdded',function(uploader,files){
			if(options.FilesAdded!=null)
			{
				if(options.FilesAdded.call(null,files)!=false)
				{
					uploader.start();
				}
			}
			else
			{
				//添加完直接上传
				uploader.start();
			}	
			
		});
		
		

		/**
		 * 当队列中的某一个文件上传完成后触发
		 * 监听函数参数：(uploader,file,responseObject)
		 * uploader为当前的plupload实例对象，file为触发此事件的文件对象，responseObject为服务器返回的信息对象，它有以下3个属性：response
		 * responseHeaders：服务器返回的头信息
		 * status：服务器返回的http状态码，比如200
		 * 
		 * 返回到外部的为ajaxobj数据，status为false中止上传
		 */
		uploader.bind('FileUploaded',function(uploader,file,responseObject){
			if(options.FileUploaded!=null)
			{
				var ajaxobj = $.parseJSON(responseObject.response);
				options.FileUploaded.call(null,ajaxobj);
				if(ajaxobj.error!=0)
				{
					uploader.stop();
				}
			}
				
		});
		

		/**
		 * 当上传队列中所有文件都上传完成后触发
		 * 监听函数参数：(uploader,files)
		 * uploader为当前的plupload实例对象，files为一个数组，里面的元素为本次已完成上传的所有文件对象
		 */
		uploader.bind('UploadComplete',function(uploader,files){
			if(options.UploadComplete!=null)
				options.UploadComplete.call(null,files);
		});
		
		
		/**
		 * 当发生触发时触发
		 * 监听函数参数：(uploader,errObject)
		 * uploader为当前的plupload实例对象，errObject为错误对象，它至少包含以下3个属性(因为不同类型的错误，属性可能会不同)：
		 * code：错误代码，具体请参考plupload上定义的表示错误代码的常量属性
		 * file：与该错误相关的文件对象
		 * message：错误信息
		 */
		uploader.bind('Error',function(uploader,errObject){
			if(options.Error!=null)
				options.Error.call(null,errObject);
		});
				
	}

	
})(jQuery); 

