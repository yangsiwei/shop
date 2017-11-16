$(function(){
	$(".add_expression").addClass("curr");
	bind_publish_item_textarea_set_expression();
	/*触屏事件*/
	 $(".expression-show-box").touchSlider({
		 	mode: 'index',
			center: true,
			single: true,
			item: '.emotion',
			holder: 'div.expression-show-box-viewport',
			box: 'div.expression-list',
			onChange: function(prev, curr) {
				$('#expression-nav a.tablink').removeClass('active');
				$('#expression-nav a.tablink').filter(function(i){return i == curr}).addClass('active');
			},
			onStart: function() {
				var count = $('.expression-show-box').get(0).getCount();
				$('#expression-nav').html('');
				for (var i = 0; i < count; i++) {
					var el = $('<a href="#" class="tablink">'+(i+1)+'</a>');
					el.attr('index', i);
				
					$('#expression-nav').append(el);

					el.bind('click', function(){
						$('.expression-show-box').get(0).moveTo($(this).attr('index'));
						return false;
					});
				}
			}
		});
	 
	 
	 /*图片上传*/
	 var img_index = 0;
	 $("#file-btn").live("change",function(){
		 if(this.files[0].type=='image/png'||this.files[0].type=='image/jpeg'||this.files[0].type=='image/gif'){
			 
		 	img_box_show();
		 	
		 	var demo_box = $(".img-show-box");
	     	var item_box = '<div class="img_load img-item img-index-'+img_index+'" data-index="'+img_index+'"><img src="'+LOADING_IMG+'"></div>';
	     	if($(".img-show-box .img-item").length >0){
	     		$(".img-show-box .img-item").last().after(item_box);
	     		if($(".img-show-box .img-item").length==3){
	     			$(".img-show-box .item-add").remove();
	     		}
	     	}else{
	     		demo_box.html(item_box);
	     		$(".add_img .file-btn").remove();
	     		$(".img-show-box").append('<div class="item-add"><img src="'+add_img_icon+'"/><input class="file-btn" id="file-btn" type="file" capture="camera" /></div>');
	     		$(".add_img").bind("click",function(){img_box_show();});
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
	            	$.showErr(err);
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
	            	//console.log(upfile_data);
		            
	            }
	        });
	 	}else{
	 		$.showErr("上传的文件格式有误");
	 	}
	 });
	 
	 /*表单提交事件*/
	 $("form[name='publish_form']").submit(function(){
		 
		 var form = $("form[name='publish_form']");
		 var content = $("#publish_item_textarea").val();
		 if(content.length>0){
			 $(".publish_btn").css("background-color","#6D6D6D");
			 $(".publish_btn").attr("disabled","disabled");
			 var url = $(form).attr("action");
			 var query = new Object();
			 query.content = content;
			 query.img_data = upfile_data;
			 $.ajax({
					url:url,
					data:query,
					type:"post",
					dataType:"json",
					success:function(data){
						if(data.status == 1){
							$.showSuccess(data.info,function(){
								if(data.jump)
									window.location=data.jump;
							});
						}else if(data.status == -1){
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
			 $.showErr("发表的内容不能为空");
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

function add_img(){
	
	img_box_show();
	
	if(type==1 && $(".img-show-box .img-item").length<3){
		return $("#file-btn").click();
	}
	if($(".img-show-box .img-item").length == 0){
 		return $("#file-btn").click();
 	}
}

function add_expression(){
	expression_show();
}
function expression_show(){
	$(".add_expression").addClass("curr");
	$(".expression").show();
	$(".add_img").removeClass("curr");
	$(".img-show-box").hide();
}
function img_box_show(){
	$(".add_img").addClass("curr");
	$(".img-show-box").show();
	$(".add_expression").removeClass("curr");
	$(".expression").hide();
}

function del_img_box(obj){
	var index = $(obj).parent().attr("data-index");
	upfile_data["i_"+index] = '';
	
	$(".img-index-"+index).fadeOut("slow");
	setTimeout(function(){
		$(".img-index-"+index).remove();
		if($(".img-show-box .img-item").length<3 && $(".img-show-box .item-add").length==0){
			$(".img-show-box").append('<div class="item-add"><img src="'+add_img_icon+'"/><input class="file-btn" id="file-btn" type="file" capture="camera" /></div>');
		}
	},500);
}


/*表情事件*/
function bind_publish_item_textarea_set_expression()
{
	$(".emotion_publish_item_textarea").find("a").bind("click",function(){
		var o = $(this);
		insert_publish_item_textarea_cnt("["+$(o).attr("rel")+"]");	
	});
	
}

function insert_publish_item_textarea_cnt(cnt)
{
	var val = $("#publish_item_textarea").val();
//	var pos = $("#publish_item_textarea").attr("position");
//	var bpart = val.substr(0,pos);
//	var epart = val.substr(pos,val.length);
//	$("#publish_item_textarea").val(bpart+cnt+epart);
	$("#publish_item_textarea").val(val+cnt);
	$.weeboxs.close("form_pop_box");
	
}

