/*******************************
 * @name Layer跨浏览器兼容插件 v1.0
 *******************************/
(function($){
    $.fn.otherLayer = function(){
        var isIE = (document.all) ? true : false;
        var isIE6 = isIE && !window.XMLHttpRequest;
        var position = !isIE6 ? "fixed" : "absolute";
        var other_code = jQuery(".others-code");
		var other_codeheight=other_code.height();
		var other_codewidth=other_code.width();
        console.log(other_codeheight+"   "+other_codewidth);
            other_code.css({"z-index":"9999","display":"block","position":position ,"top":"50%","left":"50%","margin-top": -(other_codeheight/2)+ "px","margin-left": -(other_codewidth/2) + "px"});
        var layer=jQuery("<div></div>");
            layer.css({"width":"100%","height":"100%","position":position,"top":"0px","left":"0px","background-color":"#000","z-index":"9998","opacity":"0.6"});
        jQuery("body").append(layer);
        function layer_iestyle(){
            var maxWidth = Math.max(document.documentElement.scrollWidth, document.documentElement.clientWidth) + "px";
            var maxHeight = Math.max(document.documentElement.scrollHeight, document.documentElement.clientHeight) + "px";
            layer.css({"width" : maxWidth , "height" : maxHeight });
        }
        function other_code_iestyle(){
            var marginTop = jQuery(document).scrollTop - other_codeheight/ 2 + "px";
            var marginLeft = jQuery(document).scrollLeft - other_codewidth/ 2 + "px";
            other_code.css({"margin-top" : marginTop , "margin-left" : marginLeft });
        }
        if(isIE){
            layer.css("filter","alpha(opacity=60)");
        }
        if(isIE6){
            layer_iestyle();
            other_code_iestyle();
        }
        jQuery("window").resize(function(){
            layer_iestyle();
        });
        layer.live('click',function(){
            other_code.hide();
            jQuery(this).remove();
        });
        $(".box_container-close").live('click',function(){
            other_code.hide();
            jQuery(layer).remove();
        });
    };
})(jQuery);
(function($){
    $.fn.myLayer = function(){
        var isIE = (document.all) ? true : false;
        var isIE6 = isIE && !window.XMLHttpRequest;
        var position = !isIE6 ? "fixed" : "absolute";
        var my_code_box = jQuery(".my-code-box");
        console.log(my_code_box.height()+"   "+my_code_box.width());
            my_code_box.css({"z-index":"9999","display":"block","position":position ,"top":"50%","left":"50%","margin-top": -(my_code_box.height()/2)+ "px","margin-left": -(my_code_box.width()/2) + "px"});
        var layer=jQuery("<div></div>");
            layer.css({"width":"100%","height":"100%","position":position,"top":"0px","left":"0px","background-color":"#000","z-index":"9998","opacity":"0.6"});
        jQuery("body").append(layer);
        function layer_iestyle(){
            var maxWidth = Math.max(document.documentElement.scrollWidth, document.documentElement.clientWidth) + "px";
            var maxHeight = Math.max(document.documentElement.scrollHeight, document.documentElement.clientHeight) + "px";
            layer.css({"width" : maxWidth , "height" : maxHeight });
        }
        function my_code_box_iestyle(){
            var marginTop = jQuery(document).scrollTop - my_code_box.height()/ 2 + "px";
            var marginLeft = jQuery(document).scrollLeft - my_code_box.width()/ 2 + "px";
            my_code_box.css({"margin-top" : marginTop , "margin-left" : marginLeft });
        }
        if(isIE){
            layer.css("filter","alpha(opacity=60)");
        }
        if(isIE6){
            layer_iestyle();
            my_code_box_iestyle();
        }
        jQuery("window").resize(function(){
            layer_iestyle();
        });
        layer.live('click',function(){
            my_code_box.hide();
            jQuery(this).remove();
        });
        $(".box_container-close").live('click',function(){
            my_code_box.hide();
            jQuery(layer).remove();
        });
    };
})(jQuery);
$(function(){
    //查看他人弹出遮罩层
    init_other_layer();
    function init_other_layer() {
        $(".btn-winner-code").live('click',function(){
            $("#ta_duobao").css("display","none");
            $("#my_duobao").css("display","block");
            $("#others-layer").otherLayer();
            $(".others-code").show();
        });
        $(".btn-winner-code-s").live('click',function(){
            $("#ta_duobao").css("display","block");
            $("#my_duobao").css("display","none");
            $("#others-layer").otherLayer();
            $(".others-code").show();
        });
    }
});
$(function(){
    //查看自己弹出遮罩层
    init_my_layer();
    function init_my_layer() {
        $(".btn-my-code").live('click',function(){
        $("#my-layer").myLayer();
        $(".my-code-box").show();
        });
    }
});