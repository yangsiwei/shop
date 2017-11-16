<!DOCTYPE html>
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

/* 	//后退
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
	}); */

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
<?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>

<?php else: ?>
<div class="header">
	<div class="c-hd split-line">
        <section class="cut_city">
              <a class="back_bt" href="javascript:window.history.go(-1);">取消</a>
	    </section>
        <section class="logo_img"><?php echo $this->_var['data']['page_title']; ?></section>
        <section class="cut_city hd-right">
                      <a id="header_back_btn" href="<?php
echo parse_url_tag("u:index|user#register|"."".""); 
?>">注册</a>
        </section>
	 </div>
</div>
<?php endif; ?>
<?php echo $this->fetch('inc/wx_share.html'); ?>
