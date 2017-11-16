<?php
//000000003600s:9912:"<?php exit;?>a:3:{s:8:"template";a:6:{i:0;s:37:"/phpstudy/www/wap/Tpl/main/helps.html";i:1;s:53:"/phpstudy/www/wap/Tpl/main/inc/header_title_home.html";i:2;s:44:"/phpstudy/www/wap/Tpl/main/inc/wx_share.html";i:3;s:48:"/phpstudy/www/wap/Tpl/main/inc/footer_index.html";i:4;s:49:"/phpstudy/www/wap/Tpl/main/inc/app_input_num.html";i:5;s:47:"/phpstudy/www/wap/Tpl/main/inc/footer_menu.html";}s:7:"expires";i:1510832062;s:8:"maketime";i:1510828462;}<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Mobile Devices Support @begin -->
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="false" name="twcClient" id="twcClient">
<meta name="wap-font-scale" content="no">
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
<meta content="no-cache" http-equiv="pragma">
<meta content="0" http-equiv="expires">
<!--允许全屏模式-->
<meta content="yes" name="apple-mobile-web-app-capable" />
<!--指定sari的样式-->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta content="telephone=no" name="format-detection" />
<!-- Mobile Devices Support @end -->
<title>帮助</title>
<script type="text/javascript">
	var APP_ROOT = '';
	var LOADER_IMG = 'http://www.gagoods.cn/wap/Tpl/main/images/loader_img.gif';
	var LOADING_IMG = 'http://www.gagoods.cn/wap/Tpl/main/images/loading.gif';
	var AJAX_URL = '/wap/index.php?ctl=ajax&show_prog=1';
	var PAGE_TYPE = '';
	var DOMAIN_URL = '/wap/index.php?show_prog=1';
</script>
<link rel="stylesheet" type="text/css" href="http://www.gagoods.cn/public/runtime/statics/wap/b9ef23b4c70e116eca35dae926d7f092.css?v=2.0.2800" />
<script type="text/javascript" src="http://www.gagoods.cn/public/runtime/statics/wap/cb8a5f3951ae6e9d11ec477c1fc5e132.js?v=2.0.2800"></script>
<script>
/*app 请求时候用到*/
$(function(){
	
	//后退
	$('#header_back_btn').click(function(){
		var Expression=/http(s)?:\/\/?/;
		var objExp=new RegExp(Expression);
		var backurl = $(this).attr('backurl');
		$(this).attr('backurl','-1');
		if(objExp.test(backurl)==true){
			location.href = backurl;
		}else{
			window.history.go(-1);
		}
	});
    function share_compleate(share_key){
        $.showSuccess("分享成功");
    }
    var share_data={};
    var share_title=$(".good-countdown > p").html();
    var share_imageUrl=$(".content").find("img").attr("src")
    share_data["share_content"]=window.location.href;
    share_data["share_url"]=window.location.href;
    share_data["key"]='';
    share_data['sina_app_api']=1;
    share_data['qq_app_api']=1;
    share_data["share_imageUrl"]=share_imageUrl?share_imageUrl:"/yydb/wap/Tpl/main/images/default_logo.png";
    share_data['share_title'] = share_title?share_title:"快快加入一夺宝币夺宝";
    share_data=JSON.stringify(share_data);
    $("#fenxiang").click(function(){
        try{
            App.sdk_share(share_data);
        }catch(e){
            $.showErr(e);
        }
    });
});
</script>
<script type="text/javascript">
	//减少移动端触发"Click"事件时300毫秒的时间差
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);
</script>
</head>
<body>
<div class="header">
	<div class="header-wrap">
	<div class="c-hd split-line">
        <section class="cut_city">
              <a id="header_back_btn" backurl='-1'><i class="iconfont">&#xe701;</i></a>
	    </section>
        <section class="logo_img">帮助</section>
        <section style="width:2rem !important;" class="cut_city hd-right">
                      <a id="hd-drop" href="javascript:void(0)"><i class="iconfont home">&#xe657;</i></a>
        </section>
        <div class="hd-drop-mask"></div>
        <ul class="hd-drop">
        	<li>
        		<a class="flex-box split-line" href="/wap/index.php?show_prog=1">
        			<i class="iconfont">&#xe6ee;</i>
        			<p>返回首页</p>
        		</a>
        	</li>
            <li>
                <a class="flex-box split-line" href="/wap/index.php?ctl=user_center&show_prog=1">
                <i class="iconfont">&#xe6f1;</i>
                <p>用户中心</p>
                </a>
            </li>
        	<li>
        		<a class="flex-box split-line" href="/wap/index.php?ctl=uc_duobao_record&show_prog=1">
        			<i class="iconfont">&#xe6ff;</i>
        			<p>夺宝记录</p>
        		</a>
        	</li>
                    </ul>
     </div>
	 </div>
</div>
<script>
	$("#hd-drop").bind('click', function() {
		$(".hd-drop").toggleClass('active');
		$(".hd-drop-mask").toggleClass('active');
	});
	$(".hd-drop-mask").bind('click', function() {
		$(".hd-drop").removeClass('active');
		$(".hd-drop-mask").removeClass('active');
	});
</script>
<script type="text/javascript">
	
</script>
<div class="wrap">
	<div class="content">
		<ul class="list">
						<li>
				<header>新手指南</header>
				<section>
					<ul>
					 							<li><a href="/wap/index.php?ctl=helps&act=show&data_id=54&show_prog=1">常见问题<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=53&show_prog=1">公司简介<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=52&show_prog=1">产品介绍<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=74&show_prog=1">经销商等级<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=73&show_prog=1">了解夺宝联盟<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=70&show_prog=1">充值奖励<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=71&show_prog=1">会员等级<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=69&show_prog=1">提现规则<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=72&show_prog=1">夺宝联盟平台服务协议<i class="iconfont">&#xe6fa;</i></a></li>
											</ul>
				</section>
			</li>
						<li>
				<header>夺宝保障</header>
				<section>
					<ul>
					 							<li><a href="/wap/index.php?ctl=helps&act=show&data_id=59&show_prog=1">账号和安全<i class="iconfont">&#xe6fa;</i></a></li>
											</ul>
				</section>
			</li>
						<li>
				<header>商品配送</header>
				<section>
					<ul>
					 							<li><a href="/wap/index.php?ctl=helps&act=show&data_id=57&show_prog=1">配送费用<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=56&show_prog=1">商品配送<i class="iconfont">&#xe6fa;</i></a></li>
											</ul>
				</section>
			</li>
						<li>
				<header>服务保障</header>
				<section>
					<ul>
					 							<li><a href="/wap/index.php?ctl=helps&act=show&data_id=63&show_prog=1">售后问题<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=64&show_prog=1">夺宝联盟保障体系<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=65&show_prog=1">正品承诺<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=66&show_prog=1">安全支付<i class="iconfont">&#xe6fa;</i></a></li>
												<li><a href="/wap/index.php?ctl=helps&act=show&data_id=60&show_prog=1">支付交易问题<i class="iconfont">&#xe6fa;</i></a></li>
											</ul>
				</section>
			</li>
					</ul>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('.menu_box>li').eq(3).children('a').children('p').children('img').attr('src','http://www.gagoods.cn/wap/Tpl/main/images/menu/helpr.png');
	})
</script>
           <div class="gotop" data-com="gotop">
				<a href="#">
					<i class="iconfont"></i>
				</a>
			</div>
						
	</body>
<html>
<div class="footer-menu-box">
    <div class="f_menu split-line-top">
      <ul class="menu_box">
        <li class="menu_item ">
          <a href="/wap/index.php?show_prog=1">
            <p style="width:2.6rem;height:2rem;"><img src="http://www.gagoods.cn/wap/Tpl/main/images/menu/homeb.png" style="width:100%"/></p>
          </a>
        </li>
          <li class="menu_item ">
            <a href="/wap/index.php?ctl=anno&show_prog=1">
                <p style="width:2.6rem;height:2rem;"><img src="http://www.gagoods.cn/wap/Tpl/main/images/menu/jieb.png" style="width:100%"/></p>
            </a>
          </li>
          <li class="menu_item ">
              <a href="/wap/index.php?ctl=cart&show_prog=1">
              <p style="width:2.6rem;height:2rem;"><img src="http://www.gagoods.cn/wap/Tpl/main/images/menu/goub.png" style="width:100%"/></p>
              </a>
          </li>
        <li class="menu_item cur" >
          <a href="/wap/index.php?ctl=helps&show_prog=1">
             <p style="width:2.6rem;height:2rem;"><img src="http://www.gagoods.cn/wap/Tpl/main/images/menu/helpb.png" style="width:100%"/></p>
          </a>
        </li>
        <li class="menu_item ">
          <a href="/wap/index.php?ctl=user_center&show_prog=1" onclick="mt_rand(this);">
            <p style="width:2.6rem;height:2rem;"><img src="http://www.gagoods.cn/wap/Tpl/main/images/menu/meb.png" style="width:100%"/></p>
          </a>
        </li>
    </ul>
    <a href="/o2onew/wap/biz.php?ctl=more"></a>
  </div>
</div>
<script type="text/javascript">
/*  */
</script>           <div class="gotop" data-com="gotop">
				<a href="#">
					<i class="iconfont"></i>
				</a>
			</div>
						
	</body>
<html>
";
?>