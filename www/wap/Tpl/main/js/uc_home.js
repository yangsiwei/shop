$(function(){
	load ();
//	$(".reply-btn").bind("click",function(){
//		var act_item_box = $(this).parent().find(".act-item-box");
//			
//		if(act_item_box.css("right")=="36px"){
//			act_item_box.animate({'right':'-160'},300);
//		}else{
//			act_item_box.animate({'right':36},300);
//		}
//	});
//	Touche(document.querySelector('.reply-btn')).on('click', function(e){
//		var act_item_box = $(this).parent().find(".act-item-box");
//			
//		if(act_item_box.css("right")=="36px"){
//			act_item_box.animate({'right':'-160'},300);
//		}else{
//			act_item_box.animate({'right':36},300);
//		}
//	});

//	Touche(document.querySelector("body")).on('click', function(e){
//		var class_name = $(e.target).attr("class");
//		alert(class_name);
//	});
//	$(document.body).click(function(e) {
//		var class_name = $(e.target).attr("class");
//		alert(class_name);
//		if($(e.target).parent().attr("class")!='reply-btn')
//    	{
//			$(".act-item-box").animate({'right':'-160'},300);
//    	}
//	});
//	document.addEventListener('touchstart',touchstart, false);
//	
//	function touchstart (event){
//        var event = event || window.event;
//        console.log($(event.target).parent().attr("class"));
//        if($(event.target).parent().attr("class")!='reply-btn')
//        	$(".act-item-box").animate({'right':'-160'},300);
//         
//    }
	
/*图片预览展示效果*/

//定义图片展示插件变量
var container = document.querySelector( 'div.wrap' ), //--最外层的DIV
//triggerBttn = document.getElementById( 'trigger-overlay' ),//--激活事件的按钮
overlay = document.querySelector( 'div.overlay' ),//--遮罩的层
closeBttn = overlay.querySelector( 'button.overlay-close' );//--监听的关闭按钮
transEndEventNames = {	//--动画的类型
	'WebkitTransition': 'webkitTransitionEnd',
	'MozTransition': 'transitionend',
	'OTransition': 'oTransitionEnd',
	'msTransition': 'MSTransitionEnd',
	'transition': 'transitionend'
},
transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],//--要执行的动画
support = { transitions : Modernizr.csstransitions };

//打开遮罩事件
function toggleOverlay() {
	if( classie.has( overlay, 'open' ) ) {
		classie.remove( overlay, 'open' );
		classie.remove( container, 'overlay-open' );
		classie.add( overlay, 'close' );
		var onEndTransitionFn = function( ev ) {
			if( support.transitions ) {
				if( ev.propertyName !== 'visibility' ) return;
				this.removeEventListener( transEndEventName, onEndTransitionFn );
			}
			classie.remove( overlay, 'close' );
		};
		if( support.transitions ) {
			overlay.addEventListener( transEndEventName, onEndTransitionFn );
		}
		else {
			onEndTransitionFn();
		}
	}
	else if( !classie.has( overlay, 'close' ) ) {
		classie.add( overlay, 'open' );
		classie.add( container, 'overlay-open' );
	}
}
//图片展示事件
$(".img_o_btn").bind("click",function(){
	var p_Width = document.body.clientWidth-20;
	$("#gallery2 .list").html('');
	toggleOverlay();
	//获取所有图片进行填充
	var img_box_html = '';
	var data_index = $(this).attr("data-index");
	img_box_html = '<div class="item"><div class="ibox"><img src="'+$(this).attr("o_path")+'" alt="" /></div></div>';
	$(this).parent().parent().find(".img_o_btn").each(function(){
		if($(this).attr("o_path")){
			if($(this).attr("data-index") !=data_index){
				img_box_html+='<div class="item"><div class="ibox"><img src="'+$(this).attr("o_path")+'" alt="" /></div></div>';
			}
				
		}
	});
	
	$("#gallery2 .list").html(img_box_html);
	/*触屏轮播事件*/
	$('#gallery2').touchSlider({
		mode: 'auto',
		single: true,
		lockScroll:false,
		 center: true,
		onChange: function(prev, curr) {
			$(".overlay").animate({scrollTop:"0px"},200);
		},
		onStart:function(){
			$("#gallery2 .ibox").css('width',p_Width+"px");
			$("#gallery2 .ibox img").css('width',"90%");
			
			
		}
	});
	
});
closeBttn.addEventListener( 'click', toggleOverlay );



$("form[name='reply_form']").bind("submit",function(){
	
	var r_id = $("input[name='reply_tid']").val();
	if($.trim($("input[name='reply_txt']").val())){
		var url = $("form[name='reply_form']").attr('action');

		var query = $("form[name='reply_form']").serialize();
		$.ajax({
			url:url,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status)
				{
					cancel_reply();
					$(".r_data_"+r_id).append(obj.reply_html);
					if($(".r_data_"+r_id).find(".r-item").length>0)
						$(".r_data_"+r_id).parent().show();


					 $(window).scrollTop($(".r_sub_data_id_"+obj.reply_data.reply_id).offset().top-($(window).height()/2));
				}
				else
				{
					$.showErr(obj.info,function(){
						if(obj.jump)
						{
							location.href = location.href;
						}else{
							location.reload();
						}
					});
				}
			}
		});
	}
	return false;
});

/*加载更多操作*/
var load_page = 2;
$(".load-move").bind("click",function(){
	var id = $(this).attr("data-id");
	var query = new Object();
	query.id = id;
	query.page = load_page;
	query.act = "load_move_reply";
	$.ajax({
		url:ajax_url,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status==1)
			{
				$(".r_data_"+id).append(obj.reply_html);
				if($(".r_data_"+id).find(".r-item").length>0)
					$(".r_data_"+id).parent().show();
				
				if(obj.is_lock==1){
					$(".load-move").unbind();
					$(".load-move").css("background-color","#A6A6A6");
				}
				load_page++;
			}
			else if(obj.status==-1)
			{
				$.showErr(obj.info,function(){
					if(obj.jump)
						window.location=obj.jump;
				});
			}
		}
	});
});

	/*发现页面用到的JS*/
	$(".tags_box .tag_item").bind("click",function(){
		var obj = $(this).addClass("curr");
		
	});	
});

function do_fav_topic(id){
	var query = new Object();
	query.id = id;
	query.act = "do_fav_topic";
	$.ajax({
		url:ajax_url,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status)
			{
				$.showSuccess(obj.info,function(){
					if(obj.jump)
					location.href = obj.jump;
				});	
				
			}
			else
			{
				$.showErr(obj.info,function(){
					if(obj.jump)
					location.href = obj.jump;
				});
				cancel_act();
			}
			$('.act-item-box').animate({'right':'-160'},300);
		}
	});
	
}
/**
 * id:主题ID
 * rid:要发送的回复人ID
 * */
function submit_reply(id,reply_id){
	$('.act-item-box').animate({'right':'-160'},300);
	if(reply_id>0){
		$(".r_sub_data_id_"+reply_id).addClass("curr");
		setTimeout(function(){$(".r_sub_data_id_"+reply_id).removeClass("curr");}, 1000);
		var query = new Object();
		query.reply_id = reply_id;
		query.act = "check_reply_user";
		$.ajax({
			url:ajax_url,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(obj){
				if(obj.status==1)
				{
					$(".del_r_data").unbind();
					$(".del_r_data").bind("click",function(){
						del_reply(id,reply_id);
					});
					show_reply_act_box();			
				}else if(obj.status==-1){
					$.showErr(obj.info,function(){
						window.location=obj.jump;
					});
				}else{
					var r_data = obj.r_data;
					$("input[name='reply_txt']").val("回复@"+r_data.user_name+":");
					$("input[name='reply_rid']").val(reply_id);
					
					show_reply_box();
				}
			}
		});
	}else{
		//清除数据
		$("input[name='reply_txt']").val('');
		$("input[name='reply_tid']").val(''); 
		$("input[name='reply_rid']").val('');
		show_reply_box();
	}
	
	
	
	
	$("input[name='reply_tid']").val(id); 
	
}

function show_reply_box(){
	cancel_act();
	$(".reply-input-box").slideDown();
}

function show_reply_act_box(){
	cancel_reply();
	$(".reply-act-box").slideDown();
}
function cancel_reply(){
	$(".reply-input-box").fadeOut();
	//清除数据
	$("input[name='reply_txt']").val('');
	$("input[name='reply_tid']").val(''); 
}
function cancel_act(){
	$(".reply-act-box").fadeOut();
}
function del_reply(id,reply_id){
	var query = new Object();
	query.act="del_reply";
	query.reply_id = reply_id;
	$.ajax({
		url:ajax_url,
		data:query,
		type:"POST",
		dataType:"json",
		success:function(obj){
			if(obj.status==1)
			{
				cancel_act();
				$(".r_sub_data_id_"+reply_id).fadeOut();
				$(".r_sub_data_id_"+reply_id).remove();
				if($(".r_data_"+id).find(".r-item").length==0){
					$(".r_data_"+id).parent().hide();
				}
					
			}else if(obj.status==-1){
				$.showErr(obj.info,function(){
					window.location=obj.jump;
				});
			}else{
				$.showErr(obj.info,function(){
					location.reload();
				});
			}
		}
	});
}

function load (){
 
    document.addEventListener('touchstart',touch, false);
    document.addEventListener('touchmove',touch, false);
    document.addEventListener('touchend',touch, false);
     
    function touch (event){
        var event = event || window.event;
 
        switch(event.type){
            case "touchstart":
            	if( $(event.target).parent().attr("class") != 'reply-btn' 
            		&&($(event.target).parent().attr("class") !='act-zan' 
            		  &&$(event.target).parent().attr("class") !='act-table')
            		  )
            		$(".act-item-box").animate({'right':'-160'},300);
            	else{
            		var obj = $(event.target).parent().parent().find(".act-item-box");
            		show_reply_act(obj);
            		
            	}
            	
                break;
            case "touchend":
                
                break;
            case "touchmove":
                break;
        }
         
    }
}

function show_reply_act(obj){
	$(obj).animate({'right':'36px'},300);
}
function hide_reply_act_box(){
	$(".act-item-box").animate({'right':'-160'},300);
}




