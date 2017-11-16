$(document).ready(function(){
	
	init_switch_hide_list();
	
});

/**
 * 顶部可能存在的菜单切换
 */
function init_switch_hide_list()
{
	var x=$(document).height();
	   $(".hide_list").height(x-93);
	   /*--隐藏菜单透明背景高度--*/
	   $(".mall-cate li").click(function(){
		   if($(this).hasClass("not_do")){
			   return false;
		   }
		  	if($(this).hasClass("this")){
				$(this).removeClass("this");
				$(this).find("i").removeClass("fa-caret-up").addClass("fa-sort-desc");
				$(".hide_list").hide();
				$(".list-mask").show();
			}
			else{
				$(".hide_list").show();
				var y=$(this).index();
				$(".abbr").hide();
				$(".abbr").eq(y).show();
				$(".list-mask").show();
				/*-----------------------------------*/
				//alert(y);
				//$(".abbr").eq(y).find(".second_list ul").eq(0).show();
				/*-----------------------------------*/
				$(".mall-cate li").removeClass("this");
				$(".hide_list").show();
				$(".mall-cate li i").removeClass("fa-caret-up").addClass("fa-sort-desc");
				$(this).addClass("this");
				$(this).find("i").addClass("fa-caret-up").removeClass("fa-sort-desc");
			}	   
		  });
		  /*隐藏菜单的事件操作*/
		 $(".list-mask").click(function(){

		  	if($(this).hasClass("this")){
				$(this).removeClass("this");
				$(this).find("i").removeClass("fa-caret-up").addClass("fa-sort-desc");
				$(".hide_list").hide();
			}
			else{
				$(".hide_list").hide();
				var y=$(this).index();
				$(".abbr").hide();
				$(".abbr").eq(y).show();
				/*-----------------------------------*/
				//alert(y);
				//$(".abbr").eq(y).find(".second_list ul").eq(0).show();
				/*-----------------------------------*/
				$(".mall-cate li").removeClass("this");
				$(".mall-cate li i").removeClass("fa-caret-up").addClass("fa-sort-desc");
				$(this).hide();
			}
		  });
		 
		 
		/*--------------------------------------20140905-----------*/  
		 $(".second_list ul").hide();
		 $(".directory").click(function(){
		  	            $(".directory").removeClass("select");
		  	            $(this).addClass("select");
						var z=$(this).index();
						var a=$(this).parent().parent().parent().index();
						
						$(".second_list ul").hide();
						if( !$(this).hasClass("all_kind") ){
							$(".abbr").eq(a).find(".second_list").find("ul").eq(z).show();
						}
		 });
}