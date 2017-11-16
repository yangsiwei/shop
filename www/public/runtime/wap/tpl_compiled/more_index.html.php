<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/more.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/TouchSlide.1.1.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.fly.min.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/onload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/swipe.js";
?>
<?php echo $this->fetch('inc/header_title_only.html'); ?>

<?php $_from = $this->_var['data']['indexs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
<ul class="m-more-list split-line-top">
	<li>
		<a href="<?php echo $this->_var['item']['url']; ?>" class="m-more-item flex-box split-line">
			<div class="u-more-img"><img src="<?php echo $this->_var['item']['img']; ?>" alt="<?php echo $this->_var['item']['name']; ?>"></div>
			<div class="u-more-info flex-1">
				<h1 class="u-more-tit"><?php echo $this->_var['item']['name']; ?></h1>
				<p class="u-more-tip"><?php echo $this->_var['item']['desc']; ?></p>
			</div>
			<i class="iconfont">&#xe704;</i>
		</a>
	</li>
</ul>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

<div class="blank10"></div>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>