$(function(){
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
			query.act = "load_ecv_list";
			query.n_valid = n_valid;
			$.ajax({
				url:ajax_url,
				data:query,
				type:"POST",
				dataType:"json",
				success:function(obj){
					if(obj.status==1)
					{
						$(".ecv_box_c").append(obj.html);
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