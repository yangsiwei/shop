$(document).ready(function(){

	$("#uc-share-pin-box").init_pin({pin_col_init_height:[0],pin_col_init_width:0,width:292,hSpan:12,wSpan:10,isAnimate:false,speed:300});
	$(".pages").hide();
	$("#hd_step").val(1);
	$("#ajax_wait").val(0);

	load_pin_page();


	$(window).bind("scroll", function(e){
		var scrolltop = $(window).scrollTop();
		var loadheight = $("#pin-loading").offset().top;
		var windheight = $(window).height();

		var page = $("#hd_page").val();
		var step = $("#hd_step").val();
		var ajax_wait = $("#ajax_wait").val();
		var step_size = $("#hd_step_size").val();

//		$("#text").html(windheight+" "+scrolltop+" "+loadheight);
//		var step_size = $("#hd_step_size").val();
		//滚动到位置+分段加载未结束+ajax未在运行
	    if(windheight+scrolltop>=loadheight+50&&parseInt(step)>0&&ajax_wait==0)
	    {
	    	load_pin_page();
	    }
	});

});

function load_pin_page()
{
	$("#ajax_wait").val(1);  //表示开始加载
	var page = $("#hd_page").val();
	var step = $("#hd_step").val();
	var ajax_wait = $("#ajax_wait").val();
	var step_size = $("#hd_step_size").val();

	var query = new Object();
	query.act = "uc_share";
	query.page = page;
	query.step = step;
	query.step_size = step_size;

	$("#pin-loading").css("visibility","visible");

	$.ajax({
		url: AJAX_URL,
		data:query,
		type: "POST",
		dataType: "json",
		success: function(data){
			if(data.status == 1000)
				ajax_login();
			$("#pin-loading").css("visibility","hidden");
//			$("body").append(data.sql+"<br />");
			$.each(data.doms, function(i,dom){
				$("#uc-share-pin-box").pin(dom);
			});
			if(data.status)  //继续加载
			{
    			$("#hd_step").val(data.step);
				$("#ajax_wait").val(0);
   			}
			else //加载结束
			{
				$("#ajax_wait").val(0);
				$("#hd_step").val(0);
				$(".pages").show();
			}

			$(".dialog-mask").css("height",$(document).height());

		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}
