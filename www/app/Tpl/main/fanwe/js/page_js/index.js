$(document).ready(function(){

	init_count_down();
	
});



function init_count_down()
{
	var timespan = parseInt($(".w-countdown-nums:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".w-countdown-nums").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		var index_time_set = 'FW-XS-Y-160725071';
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"<b>%BM</b><b>%SM</b>:<b>%BS</b><b>%SS</b>:<b>%BMS</b><b>%SMS</b>",callback:function(){
			var url = AJAX_URL;
			var query = new Object();
			query.act = "get_lottery_info";
			query.id =$(o).attr("id");	
			var b=$(o);
			$.ajax({
				url: url,
				type: "POST",
				data:query,
				dataType: "json",
				success: function(data){
					$(".infoList li").eq(i).find(".show_content").html(data.html);
				},
				 error:function(o){
					 
				}
			});
		}});

	});
}


