$(document).ready(function(){

		$(".jump_tip span").everyTime(1000,function(){
			var t = parseInt($(this).html());
			if(t-1<=0)
			{
				t = 0;
				$(this).stopTime();
				var jump = $(".jump_tip a").attr("href");
				location.href = jump;
			}
			else
			{
				t = t-1;
			}			
			$(this).html(t);
		});

});