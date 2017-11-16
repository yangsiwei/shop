
//S 2015 12 1
 /*首页底部悬浮框  点击关闭*/
 function floatClose(){
     $(".index_float_close a").click(function(){
         $(".index_float_bottom").hide();
     })
     $.cookies.set('index_float_bottom', '1', { expires:365}); 
 }
 function floatOpen(){
	 if(!$.cookies.get('index_float_bottom')){
		 $(".index_float_bottom").show();
	 }
 }
 //E 2015 12 1
