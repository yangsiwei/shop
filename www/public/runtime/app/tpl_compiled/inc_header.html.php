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

<METAHTTP-EQUIV="Pragma"CONTENT="no-cache">

<METAHTTP-EQUIV="Cache-Control"CONTENT="no-cache">

<METAHTTP-EQUIV="Expires"CONTENT="0">
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
<link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/bottom.css">
<link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/online.css">
<link rel="stylesheet" type="text/css" href="http://122.114.94.153/app/Tpl/main/fanwe/css/sidebar.css">
<script type="text/javascript" src="<?php echo $this->_var['APP_ROOT']; ?>/public/runtime/app/lang.js"></script>
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
<style type="text/css">
	#jd{
		display: inline-block;
		line-height: 31px;
		margin-top: 5px;

	}
	.cate_tree{background: rgb(219, 54, 82);}
	.nav_bar .main_nav {background: rgb(219, 54, 82); width: 960px;}
	/*.nav_bar {border-bottom: 3px solid #2b71c2;}*/
	.nav_bar .main_nav ul li.current{background-color: rgb(219, 54, 82);}
	.nav_bar .main_nav ul li.current a {color: #fff;}
	.nav_bar .main_nav ul li a {color:#fff;}
	.nav_bar .drop_nav .drop_title {border-bottom: 1px solid rgb(219, 54, 82);background:rgb(219, 54, 82);}
	.nav_bar .main_nav ul li .discover {color: #fff;}
	/*.cate_tree ul li {border-bottom: 1px solid rgb(219, 54, 82);border-top: 1px solid rgb(219, 54, 82);}*/
</style>
</head>
<body>
<img src="<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif" style="display:none;" /><!--延时加载的替代图片生成-->
<div class="top_nav">
	<div class="wrap_full_w">
		<!-- <span class="f_l">欢迎来到<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SHOP_TITLE',
);
echo $k['name']($k['v']);
?></span> -->
		<span class="f_l" style="color:blue; ">夺宝联盟战略合作伙伴</span>&nbsp;&nbsp;
		<img src="app/Tpl/main/fanwe/images/jd.png" id="jd">
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
<div class="wrap_full_w clearfix" style="height: 80px;position: relative;z-index:51;">
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

	<div class="cart_tip_margin f_r">
		<a href="javascript:void(0);" class="cart_tip">
		<i class="ico ico-mini-cart"></i>
		<span class="cart_title">购物车</span>
		<div class="cart-count-wrap" style="float:right;">
		<i class="ico ico-white-solid ico-white-solid-l"></i>
		<b class="cart_count">
		<?php echo $this->_var['cart_info']['cart_item_num']; ?>
		</b>
		</div>
		</a>
	</div>
	<div class="f_r" style="position:relative;">
		<div class="top_search search key_words_search_display">
			<form action="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>" name="search_form" method=get class="key_words_search_display" >
			<input type="hidden" name="ctl"  value="duobaos" />

			<input type="text" name="keyword" autocomplete="off" class="ui-textbox search_keyword f_l key_words_search_display" holder="请输入您要搜索的关键词" <?php if ($this->_var['data']['keyword']): ?>value="<?php echo $this->_var['data']['keyword']; ?>"<?php endif; ?> />

			<button class="search_btn f_l" rel="search_btn" type="submit"><i class="search-ico iconfont">&#xe63a;</i></button>
			</form>
		</div>
	</div>
	<div class="top_cart_list " id="top_cart_list">
	</div>
</div><!--logo与头部搜索-->
<div class="nav_bar fix_nav_bar">
	<div class="wrap_full_w clearfix">

		<div class="drop_nav" ref="<?php echo $this->_var['drop_nav']; ?>">
			<span class="drop_title" style="background: rgb(219, 54, 82);">全部分类<i class="ico"></i></span>
			<div class="drop_box">
				<?php 
$k = array (
  'name' => 'load_cate_tree',
  'c' => '0',
);
echo $k['name']($k['c']);
?>
			</div>
		</div><!--下拉菜单-->

		<div class="main_nav" style="background:rgb(219, 54, 82);">
			<ul>
				<?php $_from = $this->_var['nav_list']['one']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['nav_item']):
?>
				<li <?php if ($this->_var['nav_item']['current'] == 1): ?>class="current"<?php endif; ?>><a href="<?php echo $this->_var['nav_item']['url']; ?>" <?php if ($this->_var['nav_item']['blank']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_item']['name']; ?></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<li><a href="http://122.114.94.153/">超级抽奖</a></li>
				<!-- <?php if ($this->_var['nav_list']['two']): ?>
				<li class="discover-wrap">
					<a href="javascript:;" class="discover">发现 <i class="iconfont">&#xe647;</i></a>
					<ul class="discover-box">
						<?php $_from = $this->_var['nav_list']['two']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['nav_item']):
?>
							<li <?php if ($this->_var['nav_item']['current'] == 1): ?>class="current"<?php endif; ?>><a href="<?php echo $this->_var['nav_item']['url']; ?>" <?php if ($this->_var['nav_item']['blank']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_item']['name']; ?></a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</li>
				<?php endif; ?> -->
			</ul>
		</div>


	</div>
</div>

<div class="nav_bar float_nav_bar">
	<div class="wrap_full_w clearfix">
		<?php if (! $this->_var['no_nav']): ?>
		<div class="drop_nav" ref="<?php echo $this->_var['drop_nav']; ?>">
			<span class="drop_title">全部分类<i class="ico"></i></span>
			<div class="drop_box">
				<?php 
$k = array (
  'name' => 'load_cate_tree',
  'c' => '0',
);
echo $k['name']($k['c']);
?>
			</div>
		</div><!--下拉菜单-->
		<?php endif; ?>
		<div class="main_nav">
			<ul>
				<?php $_from = $this->_var['nav_list']['one']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['nav_item']):
?>
				<li <?php if ($this->_var['nav_item']['current'] == 1): ?>class="current"<?php endif; ?>><a href="<?php echo $this->_var['nav_item']['url']; ?>" <?php if ($this->_var['nav_item']['blank']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_item']['name']; ?></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<li><a href="http://122.114.94.153/">超级抽奖</a></li>
			<!-- 	<?php if ($this->_var['nav_list']['two']): ?>
				<li class="discover-wrap">
					<a href="javascript:;" class="discover">发现 <i class="iconfont">&#xe647;</i></a>
					<ul class="discover-box">
						<?php $_from = $this->_var['nav_list']['two']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['nav_item']):
?>
							<li <?php if ($this->_var['nav_item']['current'] == 1): ?>class="current"<?php endif; ?>><a href="<?php echo $this->_var['nav_item']['url']; ?>" <?php if ($this->_var['nav_item']['blank']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_item']['name']; ?></a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</li>
				<?php endif; ?> -->
			</ul>
		</div>

		<a href="javascript:void(0);" class="cart_tip f_r">
			<i class="ico ico-mini-cart"></i>
			<span class="cart_title">购物车</span>
			<div class="cart-count-wrap" style="float:right;">
			<i class="ico ico-white-solid ico-white-solid-l"></i>
				
				<b class="cart_count">
					<?php echo $this->_var['cart_info']['cart_item_num']; ?>
				</b>
			</div>
		</a>

	</div>
</div>


