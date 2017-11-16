<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" />

<?php 
$k = array (
  'name' => 'load_compatible',
);
echo $k['name']();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->_var['page_title']): ?><?php echo $this->_var['page_title']; ?> - <?php endif; ?><?php echo $this->_var['site_seo']['title']; ?></title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php endif; ?> <?php echo $this->_var['site_seo']['keyword']; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php endif; ?> <?php echo $this->_var['site_seo']['description']; ?>" />
<script type="text/javascript">
var APP_ROOT = '<?php echo $this->_var['APP_ROOT']; ?>';
var AJAX_LOGIN_URL	= '<?php
echo parse_url_tag("u:index|user#ajax_login|"."".""); 
?>';
var AJAX_URL	= '<?php
echo parse_url_tag("u:index|ajax|"."".""); 
?>';

//关于图片上传的定义
var LOADER_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif';
var UPLOAD_SWF = '<?php echo $this->_var['TMPL']; ?>/js/utils/Moxie.swf';
var UPLOAD_XAP = '<?php echo $this->_var['TMPL']; ?>/js/utils/Moxie.xap';
var MAX_IMAGE_SIZE = '<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'MAX_IMAGE_SIZE',
);
echo $k['name']($k['v']);
?>';
var ALLOW_IMAGE_EXT = '<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'ALLOW_IMAGE_EXT',
);
echo $k['name']($k['v']);
?>';
var UPLOAD_URL = '<?php
echo parse_url_tag("u:index|file#upload|"."".""); 
?>';
var QRCODE_ON = '<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'QRCODE_ON',
);
echo $k['name']($k['v']);
?>';
var cart_url='<?php
echo parse_url_tag("u:index|cart|"."".""); 
?>';
</script>
<script type="text/javascript" src="<?php echo $this->_var['APP_ROOT']; ?>/public/runtime/app/lang.js"></script>
    <link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/bottom.css">
    <link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/online.css">
    <link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/sidebar.css">
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

</head>
<body>
<img src="<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif" style="display:none;" /><!--延时加载的替代图片生成-->
<div class="top_nav">
	<div class="wrap_full_w">
		<span class="f_l">欢迎来到<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SHOP_TITLE',
);
echo $k['name']($k['v']);
?></span>	
		<span class="f_r">
			<ul class="head_tip">
				<li class="user_tip" id="head_user_tip"><?php 
$k = array (
  'name' => 'load_user_tip',
);
echo $this->_hash . $k['name'] . '|' . base64_encode(serialize($k)) . $this->_hash;
?></li>
			</ul>
		</span>
	</div>
</div><!--顶部横栏-->
<div class="blank25"></div>
<div class="wrap_full_w clearfix">
	<div class="logo f_l">
	<a class="link" href="<?php echo $this->_var['APP_ROOT']; ?>/">
		<?php
			$this->_var['logo_image'] = app_conf("SHOP_LOGO")?app_conf("SHOP_LOGO"):$this->_var['TMPL']."/images/default_logo.png";
		?>
		<?php 
$k = array (
  'name' => 'load_page_png',
  'v' => $this->_var['logo_image'],
);
echo $k['name']($k['v']);
?>
	</a>
	</div>

<div class="process_head f_r">
</div>
</div><!--logo与头部搜索-->



