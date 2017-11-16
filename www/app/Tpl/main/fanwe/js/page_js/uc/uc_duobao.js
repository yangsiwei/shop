$(document).ready(function(){
	w_menu();
});

function w_menu()
{
	$(".m-user-comm-selectTitle").hover(function() {
		$("#w_menu").css("display","block");
	}, function() {
		$("#w_menu").css("display","none");
	});
};

