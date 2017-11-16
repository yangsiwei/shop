$(document).ready(function() {
	$(".record-tab li").click(function() {
		$(".record-tab li").removeClass('active');
		$(this).addClass('active');
	});
	$(".show-code").click(function() {
		$(this).hide();
		$(".hide-code").show();
		$(".my-code-list").show();
	});
	$(".hide-code").click(function() {
		$(this).hide();
		$(".show-code").show();
		$(".my-code-list").hide();
	});
});