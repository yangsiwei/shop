$(function(){
	init_show_btn();
	
});

function init_show_btn(){
	$(".open-box").show();
	$(".close-box").hide();
	
	$(".open-box").bind("click",function(){
		$(this).hide();
		$(".close-box").show();
		$(".info-list .user-list").slideDown("slow");
	});
	$(".close-box").bind("click",function(){
		$(this).hide();
		$(".open-box").show();
		$(".info-list .user-list").slideUp("slow");
	});
}
function app_open_url(){
	var json = '{"url":"'+fair_check_link+'","open_url_type":"3"}';
	
	App.open_type(json);
}