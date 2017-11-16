$(document).ready(function(){
	init_count_down();
	
});

function init_count_down()
{
	var timespan = parseInt($(".countdown-nums:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".ui-list .result-goods").each(function(i,o){
		var endtime = parseInt($(o).find(".countdown-nums").attr("endtime")+"000");
		if(endtime > 0){
		$(o).find(".countdown-nums").count_down({endtime:endtime,timespan:timespan,interval:10,format:"<b>%BM</b><b>%SM</b>:<b>%BS</b><b>%SS</b>:<b>%BMS</b><b>%SMS</b>",callback:function(){
			var url = AJAX_URL;
			var query = new Object();
			query.act = "get_lottery_info_anno";
			query.id =$(o).find(".countdown-nums").attr("id");	
			var b=$(o);
			$.ajax({
				url: url,
				type: "POST",
				data:query,
				dataType: "json",
				success: function(data){
					$(".ui-list .result-goods").find(".record").eq(i).html(data.html);
				},
				 error:function(o){
				 
				}
			});
		}});
	}

	});
}
