$(document).ready(function () {
    init_notice();
    //init_order_by();
    init_auto_load_data();
    init_count_down();
    init_slide_top();
    init_fenxiang();
});

function init_slide_top(){
	
	//获取菜单距离顶部的高度
	var nav_top_height = $(".slider-nav").offset().top;
	nav_top_height = parseInt(nav_top_height)-50;
	slideNavGoTop(nav_top_height);
}
//向上按钮显示隐藏
function slideNavGoTop(min_height){
	var slide_key_down = 'FW-XS-Y-160725071';
	//获取页面的最小高度，无传入值则默认为600像素
    min_height ? min_height = min_height : min_height = 50;
    //为窗口的scroll事件绑定处理函数
    $(window).scroll(function(){
        //获取窗口的滚动条的垂直位置
        var s = $(window).scrollTop();
        //当窗口的滚动条的垂直位置大于页面的最小高度时，让返回顶部夺宝币素渐现，否则渐隐
        if( s > min_height){
        	$(".slider-nav").addClass("slider-nav-top");
        }else{
        	$(".slider-nav").removeClass("slider-nav-top");
        };
    });
};
function share_complete(share_key){
    $.showSuccess("分享成功");
}
function init_fenxiang(){
   var share_data={};
    share_data["share_content"]=share_url;
    share_data["share_url"]=share_url;
    share_data["key"]='';
    share_data['sina_app_api']=1;
    share_data['qq_app_api']=1;
    share_data["share_imageUrl"]=$(".tempWrap").find('img').attr('src');
    share_data['share_title'] = title;
    share_data=JSON.stringify(share_data);
   $("#fenxiang").click(function(){
       try{
           App.sdk_share(share_data);
       }catch(e){
           $.showErr(e);
       }
   });
}
function get_brower_info(){
    var w=window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth;
    var h=window.innerHeight
        || document.documentElement.clientHeight
        || document.body.clientHeight;
    var rJson={screen_width:w,screen_height:h};
    var rTmp=IsPC();
    for(var i in rTmp){
        rJson[i]=rTmp[i];
    }
    return rJson;

}
function IsPC() {
    var userAgent = navigator.userAgent;
    var rJson={};
    if(userAgent.match(/Android/i)){
        var tmp=userAgent.match(/Android.*/i);
            rJson["sdk_type"]="dev_type=Android";
            rJson["sdk_version_name"]=tmp.match(/([\d.]+)/);
    }else if(userAgent.match(/iPhone/i)){
        var tmp=userAgent.match(/iPhone.*/i);
        rJson["sdk_type"]="dev_type=ios";
        rJson["sdk_version_name"]=tmp.match(/([\d.]+)/);
    }
    return rJson;
}
function init_adv_slider(){
	$('#marquee').bxSlider({
        mode:'vertical', //默认的是水平
        displaySlideQty:1,//显示li的个数
        moveSlideQty: 1,//移动li的个数  
        captions: true,//自动控制
        auto: true,
        controls: false//隐藏左右按钮
  });
}

function init_count_down()
{
	var timespan = parseInt($(".w-countdown-nums:first").attr("nowtime")+"000") - new Date().getTime(); 
	$(".w-countdown-nums").each(function(i,o){
		var endtime = parseInt($(o).attr("endtime")+"000");
		$(o).count_down({endtime:endtime,timespan:timespan,interval:10,format:"%H:%M:%S:%MS",callback:function(){
			$(o).html("计算中");
		}});

	});
}




function setCookie(name, value, iDay) {

    /* iDay 表示过期时间
     
     cookie中 = 号表示添加，不是赋值 */

    var oDate = new Date();

    oDate.setDate(oDate.getDate() + iDay);

    document.cookie = name + '=' + value + ';expires=' + oDate;

}

function getCookie(name) {

    /* 获取浏览器所有cookie将其拆分成数组 */

    var arr = document.cookie.split('; ');



    for (var i = 0; i < arr.length; i++) {

        /* 将cookie名称和值拆分进行判断 */

        var arr2 = arr[i].split('=');

        if (arr2[0] == name) {

            return arr2[1];

        }

    }

    return '';

}

//公告滚动
function init_notice()
{
    $(".notice-box").everyTime(3000, function () {
        roll_news();
    });
    $(".notice-box").hover(function () {
        $(".notice-box").stopTime();
    }, function () {
        $(".notice-box").everyTime(3000, function () {
            roll_news();
        });
    });

}
function roll_news()
{
    $(".notice-box ul").find("li:first").animate({marginTop: "-" + $(".notice_board ul").find("li:first").height() + "px"}, 300, function () {
        var li = $(this);
        $(".notice-box ul").append('<li class="n-item">' + $(li).html() + '</li>');
        $(li).remove();
    });
}

/**
 * 排序初始化
 */

/*function init_order_by() {
    $(".slider-nav .nav-item a").bind("click", function () {
        if ($(this).is('.last')) {
            if ($(this).is('.f-down')) {
                $(this).removeClass('f-down').addClass('f-up');
                location.href = $(this).find('.i-up').attr('data_url');
            } else if ($(this).is('.f-up')) {
                $(this).removeClass('f-up').addClass('f-down');
                location.href = $(this).find('.i-down').attr('data_url');
            } else {
                $(this).removeClass('f-up').addClass('f-down');
                location.href = $(this).find('.i-down').attr('data_url');
            }
        }
    });
}*/






var page=2;
var stop=true;
var page_total = 0;
function init_auto_load_data(){
    $(window).scroll(function(){ 
        if(page_total>0 && page>page_total){
            stop=false;
            $(".page-load").html("没有更多夺宝活动了~");
        }else{
            $(".page-load").html("努力加载中...~");
        }
        var order=$(".slider-nav .nav-item a.cur").attr("value");
        var order_dir=$(".slider-nav .nav-item a.cur span.xz").attr("order_dir");
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop())+100; 
        if($(document).height() <= totalheight){ 
            if(stop==true){ 
                stop=false;
                var query = new Object();
                query.page = page;
                query.order = order;
                query.order_dir = order_dir;
                query.act="load_index_list_data";
                $.ajax({
                        url: AJAX_URL,
                        data: query,
                        type: "POST",
                        dataType: "json",
                        success: function (obj) {
                            $(".tuan-ul").append(obj.html);    
                            stop=true;
                            page++;
                            page_total = obj.page.page_total;
                        }
                });
            } 
        }
    });
}

/**
 * 点击排序按钮进行排序
 * @param obj
 * @returns
 */
function order_sort(obj){

    page=2;
    stop=true;
    page_total = 0;
	 
	if($(obj).hasClass('cur') && !$(obj).hasClass('last') ){
		return false;
	}
	
	$(obj).addClass('cur').parent().siblings().children('a').removeClass('cur');
	$("a[sort='max_buy']").removeClass('f-up').removeClass('f-down');
	var loading_list = $('.loading-list');
	loading_list.css('z-index', 100);
	// 点击切换排序后，要跳到前面的位置
	$("html,body").animate({
		 scrollTop: $("#set-top-height").offset().top - $(".nav-item").height() - 3
	}, 300);
	
	
	
	// 总需排序设置
	var order_dir;
	if ($(obj).hasClass('last')) {
		order_dir = $(obj).attr('order_dir');
		var set_order_dir = order_dir == 1 ? 0:1;
		$(obj).attr('order_dir', set_order_dir);
		if(set_order_dir == 1){
			$(obj).addClass('f-up').removeClass('f-down');
		}else{
			$(obj).addClass('f-down').removeClass('f-up');
		}
    }
	
	 
    var order=$(obj).attr("sort");
    var query = new Object();
    query.page = 1;
    query.order = order;
    query.order_dir = order_dir;
    query.act="load_index_list_data";
    $.ajax({
            url: AJAX_URL,
            data: query,
            type: "POST",
            dataType: "json",
            success: function (data) {
                $(".tuan-ul").html(data.html);
                
                loading_list.css('z-index', -1);

                page=2;
                stop=true;
                page_total = 0;
                init_auto_load_data();

                $.refresh_image();
            }
    });
         
    
}
