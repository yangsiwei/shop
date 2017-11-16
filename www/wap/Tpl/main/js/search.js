$(document).ready(function () { 
    $(".search_criteria li").click(function(){
	   	  $(".search_criteria li").removeClass("this");
		  $(this).addClass("this");  
		  $("input[name='search_type']").val($(this).attr("data"));
	});
    
    $(".hot_list .hot_item .hot-link").bind("click",function(){
    	$("#keyword").val($(this).html());
    	search_submit();
    });
	
	$('#j_more').click(function(){
		reload_search_result();
	});
	
	
 });  

function search_submit(){
	$("form[name='search_form']").submit();
}
