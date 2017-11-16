/*加入购车事件*/
function add_cart_duoabos(obj,type){
    var btn_item = $(obj);

        var buy_num = $(obj).parent().siblings(".goods-number").find("input[name='num']").val();;
        //请求服务端加入购物车表
        var query = new Object();
        query.act = "addcart";
        query.buy_num = buy_num;
        query.data_id = parseInt($(obj).attr('data_id'));
        $.ajax({
            url: AJAX_URL,
            data: query,
            type: "POST",
            dataType: "json",
            success: function (obj) {

                if(type==1){
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
                		location.href=cart_url;
	                }else{	                	
	                    $.showErr(obj.info);
	                }	
                }else{
                    if (obj.status == -1) {
                		ajax_login();
                    }else if (obj.status == 1) {
	                	var img_obj=$(btn_item).parents(".goods-wrap").find(".imgbox img");
	                   	var left=$(img_obj).offset().left;
	                	var top=$(img_obj).offset().top;
	                	var cart_left=$(".cart_tip .cart_count").offset().left;
	                	var cart_top=$(".cart_tip .cart_count").offset().top;
	                	var float_nav_top=$(".float_nav_bar").offset().top;
	                	var img_clone=img_obj.clone();
	                	$('body').append(img_clone);
	                	
	                	img_clone.css({'position':'absolute','left':left,'top':top,'z-index':10000});
	                	
	                	if($(".float_nav_bar").is(":hidden")){
	                     	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:cart_top+10},500,function(){
	                    		$(img_clone).remove();	
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    		load_cart_list();
	                    	});
	                	}else{
	         
	                    	$(img_clone).animate({"height":"0px","width":"0px",left:cart_left+13,top:float_nav_top+25},500,function(){
	                    		$(img_clone).remove();	
	                    		$(".cart_tip .cart_count").html(obj.cart_item_num);
	                    		load_cart_list();
	                    	});
	                	}
	                	
	
	                	
	                } else {
	
	                    $.showErr(obj.info);
	   
	                }
                }
            }
        });
}