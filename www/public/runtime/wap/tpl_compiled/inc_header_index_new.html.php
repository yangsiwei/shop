<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Mobile Devices Support @begin -->
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="false" name="twcClient" id="twcClient">
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
<meta content="no-cache" http-equiv="pragma">
<meta content="0" http-equiv="expires">
<!--允许全屏模式-->
<meta content="yes" name="apple-mobile-web-app-capable" />
<!--指定sari的样式-->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta content="telephone=no" name="format-detection" />
<!-- Mobile Devices Support @end -->
<title><?php echo $this->_var['data']['page_title']; ?></title>
<script type="text/javascript">
	var APP_ROOT = '<?php echo $this->_var['APP_ROOT']; ?>';
	var LOADER_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif';
	var LOADING_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loading.gif';
	var AJAX_URL = '<?php
echo parse_url_tag("u:index|ajax|"."".""); 
?>';
	var PAGE_TYPE = '<?php echo $this->_var['PAGE_TYPE']; ?>';

</script>
<link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['pagecss'],
);
echo $k['name']($k['v']);
?>" />
<script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['pagejs'],
  'c' => $this->_var['cpagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
<script>
/*app 请求时候用到*/
$(function(){
	<?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>
	App.page_title('<?php echo $this->_var['data']['page_title']; ?>');

	if($(".hide_list")){
		$(".hide_list").addClass("page_type_app");
	}
	<?php endif; ?>

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
});
</script>
<style type="text/css">
@media screen and (min-width: 320px){
#pop_win{
      top: 291px;left: 39px;
}}
@media screen and (min-width: 360px){
#pop_win{
    top: 281px;left:53px;
}}
@media screen and (min-width: 375px){
#pop_win{
    top: 281px;left:60px;
}}

@media screen and (min-width: 414px){
#pop_win{
    left: 80px; top: 319px;
}}
@media screen and (min-width: 412px){
#pop_win{
    top: 331px;left: 79px;
}}
@media screen and (min-width: 768px){
#pop_win{
       left: 252px;top: 459px;
}}

</style>
</head>
<body>
<?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>

<?php else: ?>
<header class="header-wrapper close">
	<div class="header-wrap" style="background: linear-gradient(to right, #fa5203 0%,#C91B1D 50%,#fa5203 100%);">
	<!-- <span style="font-size:24px;color:#fff;margin-left:20px;" class="iconfont" id="menu">&#xe639;</span> -->
	<div id="qid" style="width: 35px;height: 35px;border-radius: 35px;border-right-style: none;text-align: center;line-height: 35px;margin-left: 15px;border: 1px solid #fff;color: #fff;" >签</div>
		<div class="title" style="margin-left:15%;">
			<img src="./wap/Tpl/main/images/logo-1.png" style="width:70%"/>
		</div>
	<div class="qd qidao" >
		<div id='calendar_month_span1' style='color:#e0fd02 ;text-align: center;margin-top:22px;'></div>
		<img src="./wap/Tpl/main/images/guanbi.png" style="width:8%;position: fixed;top:14%;right:3%;z-index:1000;" class="guanb">
		<div class="sgin-1">
			<div class="yuan">
				<p style="float: left"></p>
				<p style="float: right"></p>
			</div>
			<div id="calendar"></div>
		</div>
	</div>
	<a class="user-center-link z-nav-down hd-right"></a>
	<a class="search-btn" href="<?php
echo parse_url_tag("u:index|search#index|"."".""); 
?>">
	<i class="iconfont">&#xe6e7;</i>
	</a>
    <?php if ($this->_var['is_app'] == true): ?>
        <a class="search-btn" id="fenxiang">
        <i class="iconfont">&#xe6bb;</i>
        </a>
    <?php endif; ?>
	</div>
</header>
<div id="msg_dom1" style="display:none;">
	<div id="pop_win" style="text-align: center; position: fixed; z-index: 1999; background:#fff; width: 250px; border-radius: 10px;">
		<span style="font-size: 16px;font-weight: 100;"><b>签到提示</b></span>
		<span style="padding:10px;display:block; border-bottom:1px solid #ccc;">
			<span class="signin_msg"></span>
			<span class="signin_price">&nbsp;</span>
		</span><div style="padding:10px; display:-moz-box; display:-webkit-box;display:box; width:100%;">
		<div style="-moz-box-flex:1.0;-webkit-box-flex:1.0;box-flex:1.0;display:block;" id="yes">确定</div>
	</div>
	</div>
	<div id="bg_mask" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgb(0, 0, 0); z-index: 1998; opacity: 0.2;"></div>
</div>
<script type="text/javascript">
    $(function(){
        var lock=false;
        $("#qid").click(function(){
            if(!lock) {
                lock=true;
                $.ajax({
                    type: "POST",
                    url: "<?php
echo parse_url_tag("u:index\|index#qid\|"."".""); 
?>",
                    data: "do=sign",
                    dataType: "json",
                    success: function(datas){
                        if(datas.status = 'success') {
                            var msg = datas.msg;
                            $('.qd').show(500);
                            $('.sgin-1').show(500);    
							$("#calendar_month_span1").html(msg);
                            lock=false;
                        }
                    }
                });
            }
            //ajax获取日历json数据
            setTimeout( function(){
                var myDate = new Date;
                var year = myDate.getFullYear();//获取当前年
                var yue = myDate.getMonth()+1;//获取当前月
                $.ajax({
                    type: "POST",
                    url: "<?php
echo parse_url_tag("u:index\|index#monthd\|"."".""); 
?>",
                    data: "showMonth="+yue+"&"+"showYear="+year,
                    success: function(datas){
                        var signList = eval('(' + datas + ')');
                        calUtil.init(signList);
                        $('.zise').html("<img src='./wap/Tpl/main/images/liwu.png' >");
                    }
                });

            }, 500 );
        });
        $("#yes").click(function () {
            $("#msg_dom1").css("display","none");
        });
        $('.guanb').click(function(){
            $('.qd').hide(500);
            $('.sgin-1').hide(500);
        });
    });
</script>
</script>
<?php endif; ?>
<?php echo $this->fetch('inc/wx_share.html'); ?>