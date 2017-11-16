<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/deal_cate.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
</script>

<?php endif; ?>

<div class="wrap loading_container" id="loading_container">
	<div class="content">
		<div class="search">
			<a class="search-wrap" href="<?php
echo parse_url_tag("u:index|search|"."".""); 
?>">
				<i class="iconfont">&#xe662;</i>
				<div class="search-bar">搜索商品</div>
			</a>
		</div>
		<!-- 搜索 -->
		<div class="allgoods">
                    <a href="<?php
echo parse_url_tag("u:index|duobaos#index|"."".""); 
?>"><i class="iconfont">&#xe62e;</i><h1>全部商品</h1></a>
			<p>分类浏览</p>
		</div>
		<ul class="goods-list">
			<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate');if (count($_from)):
    foreach ($_from AS $this->_var['cate']):
?>
			<li>
			<a href="<?php
echo parse_url_tag("u:index|duobaos#index|"."data_id=".$this->_var['cate']['id']."".""); 
?>"><i class="diyfont" style="color: <?php echo $this->_var['cate']['iconcolor']; ?>"><?php echo $this->_var['cate']['iconfont']; ?></i><h2><?php echo $this->_var['cate']['name']; ?></h1></a>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
		</ul>
	</div>
</div>
<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>
