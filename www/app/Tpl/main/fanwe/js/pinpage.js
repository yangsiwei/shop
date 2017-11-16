$(document).ready(function(){
	
	var first_height = 222;
	$("#pin_test").init_pin({pin_col_init_height:[first_height],width:225,hSpan:25,wSpan:10,isAnimate:false,speed:300});
	init_demobtn();
	
	
	if($(window).width()<1050)
	{
		$("#pin_test").reposition();
	}
	if($(window).width()>1200)
	{
		$("#pin_test").reposition();
	}
	
	$(window).resize(function(){
		if($(window).width()<1050)
		{
			$("#pin_test").reposition();
		}
		if($(window).width()>1200)
		{
			$("#pin_test").reposition();
		}
	});
});



function init_demobtn()
{
	$("#pinbtn").css("top",$(document).scrollTop()+$(window).height()-80);
	$(window).scroll(function(){
		$("#pinbtn").css("top",$(document).scrollTop()+$(window).height()-80);
	});	
	
	$("#pinbtn").bind("click",function(){
		var h = GetRandomNum(100,350);
		$("#pin_test").pin("<div style='width:225px; height:"+h+"px; border:solid 1px #ccc;'></div>");
	});
}

function GetRandomNum(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
} 