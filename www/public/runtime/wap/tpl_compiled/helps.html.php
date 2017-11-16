<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/article.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<div class="wrap">
	<div class="content">
		<ul class="list">
			<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
			<li>
				<header><?php echo $this->_var['item']['title']; ?></header>
				<section>
					<ul>
					 	<?php $_from = $this->_var['item']['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'it');if (count($_from)):
    foreach ($_from AS $this->_var['it']):
?>
						<li><a href="<?php echo $this->_var['it']['url']; ?>"><?php echo $this->_var['it']['title']; ?><i class="iconfont">&#xe6fa;</i></a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</section>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('.menu_box>li').eq(3).children('a').children('p').children('img').attr('src','./wap/Tpl/main/images/menu/helpr.png');
	})
</script>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/app_input_num.html'); ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>