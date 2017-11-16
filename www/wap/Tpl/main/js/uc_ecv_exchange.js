$(function(){

	$("input[name='sn']").focus(function(){
		$(this).val('');
		setTimeout(function () {//由于键盘弹出是有动画效果的，要获取完全弹出的窗口高度，使用了计时器
           $(".tip-txt").show();
        }, 500);
		
	}).blur(function(){
		$(".tip-txt").hide();
		$("form[name='exchange_form']").submit();
	});
	$("form[name='exchange_form']").bind('submit',function(){
		var sn = $("input[name='sn']").val();
		if(sn==''){
			//$.showErr("口令不能为空");
			return false;
		}else{
			var form = $("form[name='exchange_form']");
			var url=$(form).attr('action');
			var query = new Object();
			query.sn = sn;
			$.ajax({
				url:url,
				data:query,
				type:'post',
				dataType:'json',
				success:function(obj){
					if(obj.status==1){
						$.showErr(obj.info,function(){
		                	 if (obj.jump)
		                     {
		                         location.href = obj.jump;
		                     }
		                 });
						
					}else{
						$.showErr(obj.info,function(){
							location.reload();
		                 });
					}
					return false;
				}
			});
		}
		return false;
	});
	
	
	//分页参数
	var cur_page = 1;
	var total_page = $(".load-data").attr("page-data");
	$(".load-data").bind("click",function(){
		if(cur_page < total_page){
			$(".load-img").show();
			$(".load-data").hide();
			load_page = cur_page+1;
			var query = new Object();
			query.page = load_page;
			query.act = "load_ecv_exchange_list";
			$.ajax({
				url:ajax_url,
				data:query,
				type:"POST",
				dataType:"json",
				success:function(obj){
					if(obj.status==1)
					{
						$(".items-box").append(obj.html);
						if(obj.is_lock==1){
							$(".load-data").unbind();
							$(".load-data").hide();
						}else{
							$(".load-data").show();
						}
						$(".load-img").hide();
						cur_page++;
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
		}
	});
	
});
function form_submit_ev(){
	var sn = $("input[name='sn']").val();
	if(sn==''){
		$.showErr("口令不能为空");
		return false;
	}else{
		var form = $("form[name='exchange_form']");
		var url=$(form).attr('action');
		var query = new Object();
		query.sn = sn;
		$.ajax({
			url:url,
			data:query,
			type:'post',
			dataType:'json',
			success:function(obj){
				if(obj.status==1){
					$.showSuccess(obj.info,function(){
						if(obj.jump){
							window.location = obj.jump;
						}
					});
					
				}else{
					$.showErr(obj.info,function(){
						if(obj.jump){
							window.location = obj.jump;
						}
					});
				}
				return false;
			}
		});
	}
	return false;
}
function do_exchange(id,obj){
	var score = $(obj).attr('score-data');
	$.showConfirm("兑换将扣除："+score+"积分,确定兑换吗？",function(){
		
		var query = new Object();
		query.id = id;
		query.act = 'do_exchange';
		$.ajax({
			url:ajax_url,
			data:query,
			type:'post',
			dataType:'json',
			success:function(data){
				if(data.status==1){
                                    $.showErr(data.info,function(){
                                    	location.reload();
                                    });
					
				}else{
					$.showErr(data.info,function(){
						location.reload();
	                 });
				}
				
				return false;
			}
		});
	});
}


