/*
 * user_center优惠币说明
 */
$(document).ready(function () {
	$(".uc_explain").click(function(){
		 $("#coupons_explain").slideToggle();
	});
});


$(function(){
	var upfile_data = new Array();
	 /*图片上传*/
	 var img_index = 0;
	 $("#file-btn").live("change",function(){
		 if(this.files[0].type=='image/png'||this.files[0].type=='image/jpeg'||this.files[0].type=='image/gif'){ 
	        // 也可以传入图片路径：lrz('../demo.jpg', ...
	     	var is_err = 0;
	        lrz(this.files[0], {
				width:1200,
				height:900,
	            before: function() {
	            	//压缩开始
	            },
	            fail: function(err) {
	                //console.error(err);
	            	is_err = 1;
	            	alert(err);
	            },
	            always: function() {
	            	//压缩结束
	            },
	            done: function (results) {
	            // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
	            	if(is_err !=1){
	            		var data = new Array();
	            		var data = {
	                            base64: results.base64,
	                            size: results.base64.length // 校验用，防止未完整接收
	                        };
	            		upfile_data = JSON.stringify(data);
	            		var query = new Object();
						query.img_data = upfile_data;
				        $.ajax({
								url:suce_url,
								data:query,
								type:"post",
								dataType:"json",
								success:function(data){
                                    if(data.status){
                                        $("#fileimg").attr("src",data.big_url+"?r="+Math.random());
                                        $.showSuccess(data.info);
                                    }else{
                                        $.showErr(data.info);
                                    }
								}
								,error:function(){
									$.showErr("服务器提交错误");
								}
							});	
	            	}
	            }
	        });

	 	}else{
	 		$.showErr("上传的文件格式有误");
	 	}
	 });
});