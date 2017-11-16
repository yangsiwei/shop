$(function(){
/*图片上传*/
	 var img_index = 0;
	 $("#file-btn").live("change",function(){
		 if(this.files[0].type=='image/png'||this.files[0].type=='image/jpeg'||this.files[0].type=='image/gif'){



		 	var demo_box = $(".img-show-box");
			//<li class="pic-item"><img src="http://ossweb-img.qq.com/images/lol/img/champion/Annie.png"></li>
				var item_box = '<li class="pic-item img_load img-item img-index-'+img_index+'" data-index="'+img_index+'"><img src="'+LOADING_IMG+'"></li>';
	     	//var item_box = '<div class="img_load img-item img-index-'+img_index+'" data-index="'+img_index+'"><img src="'+LOADING_IMG+'"></div>';
	     	if($(".img-show-box .img-item").length >0){
	     		$(".img-show-box .img-item").last().after(item_box);
	     		if($(".img-show-box .img-item").length==3){
	     			$(".img-show-box .item-add").remove();
	     		}
	     	}else{
	     		demo_box.html(item_box);
	     		$(".img-show-box").append('<li class=" item-add"><a href="" class="add-pic" >+</a><input class="file-btn" id="file-btn" type="file" capture="camera"></li>');
	     	}
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

	            		upfile_data[img_index] = JSON.stringify(data);
	            		demo_report(results.base64, results.origin.size);
	            		img_index++;
	            	}

	            }
	        });
	 	}else{
	 		$.showErr("上传的文件格式有误");
	 	}
	 });

	 var is_lock = 0;
	 /*表单提交事件*/
	 $(".share-btn").bind("click",function(){

				 var form = $("form[name='add_form']");
				 var title = $("input[name='title']").val();
				 var content = $("textarea[name='content']").val();
				 var id = $("input[name='id']").val();
				 	if(title==''||title.trim().getlength()<6){
				 			$.showErr("请留下一个最少6个字的晒单主题吧~");
				 			return false;
				 		}
				 		if(content==''||content.trim().getlength()<30){
				 			$.showErr("“幸运感言”，字不在多最少30个~");
				 			return false;
				 		}
				 		if($(".img-show-box .img-item").length<3){
				 			$.showErr("T_T...至少给我个3张无死角的靓照吧~");
				 			return false;
				 		}


					 $(".share-btn").css("background-color","#6D6D6D");
					 var url = $(form).attr("action");
					 var query = new Object();
					 query.id = id;
					 query.title = title;
					 query.content = content;
					 query.img_data = upfile_data;

					 if(is_lock == 0 ||is_lock ==1){
						 is_lock=1;

					 $.ajax({
							url:url,
							data:query,
							type:"post",
							dataType:"json",
							success:function(data){
								is_lock = 0;
								if(data.status == 1){
									$.showSuccess(data.info,function(){
										if(data.jump)
											window.location=data.jump;
									});
								}else{
									$.showErr(data.info,function(){
										if(data.jump)
											window.location=data.jump;
									});
								}

							}
							,error:function(){
								$.showErr("服务器提交错误");
							}
						});
					 
					 
					 return false;
				 }else{
					 $.showErr("请勿重复提交");
				 }
				 return false;


	 });

});
/*图片base64 数组*/
var upfile_data = new Array();
function demo_report(base64,size) {
    var img = new Image();

    if(size === 'NaNKB') size = '';
    if(size>0){
    	var span_html = '<span class="item_span" style="background-image: url('+base64+');background-size: cover;background-position: 50% 20%;background-repeat: no-repeat;"></span><a class="close-btn" href="javascript:void(0);" onclick="del_img_box(this)"><i class="iconfont">&#xe608;</i></a>';
    	$(".img_load").html(span_html);
    	$(".img_load").removeClass('img_load');

    }
}




function del_img_box(obj){
	var index = $(obj).parent().attr("data-index");
	upfile_data["i_"+index] = '';
	upfile_data[index]='';
	//upfile_data.splice(index,1);

	$(".img-index-"+index).fadeOut("slow");
	setTimeout(function(){
		$(".img-index-"+index).remove();
		if($(".img-show-box .img-item").length<3 && $(".img-show-box .item-add").length==0){
			$(".img-show-box").append('<li class=" item-add"><a href="" class="add-pic" >+</a><input class="file-btn" id="file-btn" type="file" capture="camera"></li>');
		}
	},500);
}

String.prototype.getlength = function()
{
	return this.replace('/[^\x00-\xff]/ig', "aa").length;
}
