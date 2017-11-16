$(document).ready(function(){

	//init_countdown();
	init_count_down();
});

function init_count_down()
{
	var timespan = parseInt($(".w-countdown-nums:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".w-countdown-nums").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"%H:%M:%S:%MS",callback:function(){
			 var parent_obj = $(o).parent();
			 $(parent_obj).find(".w-countdown-nums").remove();
             $(parent_obj).find(".w-countdown").remove();
             $(parent_obj).find(".w-countwaiting").show();
		}});

	});
}


