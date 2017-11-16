$(document).ready(function(){
	 
	init_addd_cart();
});

/*加入购车事件*/
var cur_num = 0;
function init_addd_cart(){

  $(".add_cart_item").bind("click",function(){
	 
    var buy_num = parseInt($(this).attr('buy_num'));
    //请求服务端加入购物车表
    var obj_e=$(this);
    var obj_e_width=$(this).width();
    var obj_e_height=$(this).height();
    var query = new Object();
    query.act="add_cart";
    query.buy_num = buy_num;
    query.data_id = parseInt($(this).attr('data_id'));

    $.ajax({
      url:AJAX_URL,
      data:query,
      type:"POST",
      dataType:"json",
      success:function(obj){
        if (obj.status == -1) {
                 $.showErr(obj.info,function(){
                	 if (obj.jump)
                     {
                         location.href = obj.jump;
                     }
                 });
                 
                 return false;
             }
        if (obj.status==1) {
          //增加购物车里面商品数量
          if(obj.cart_item_num>0){
//        	 var b_top=$(obj_e).offset().top; 
//        	 var b_left=$(obj_e).offset().left; 
//
//        	 var e_top=$(".nav_cart_num").offset().top; 
//        	 var e_left=$(".nav_cart_num").offset().left; 
//        	 
// 			var ccopy=$(".nav_cart_num").clone();
// 			
// 			ccopy.css({'position':'absolute','left':b_left+obj_e_width/2,'top':b_top+obj_e_height/2,'z-index':10000});
// 			$('body').append(ccopy);
// 			
// 			ccopy.animate({left:e_left,top:e_top},'show',function(){
// 			ccopy.remove();
//        	  $(".nav_cart_num").html(obj.cart_item_num);
//            $(".nav_cart_num").show();
//           
// 			});
            $(".nav_cart_num").html(obj.cart_item_num);
            $(".nav_cart_num").fadeIn(1000);
                         if(obj.cart_item_num>cur_num){
                            $(".nav_cart_num").addClass("nav_cart_num_zoom");
                            setTimeout(function(){
                                $(".nav_cart_num").removeClass("nav_cart_num_zoom");
                            }, 200 );
                        }
                        cur_num = obj.cart_item_num;
          }else{
            $(".nav_cart_num").hide();
          }


        }else{
        
         $.showErr(obj.info);
        }
      }
    });
    //ajax end

  });

}