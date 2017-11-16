<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_index.css";

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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>

<ul class="pay-record scroll_bottom_list">
	<?php if ($this->_var['data']['list']): ?>
		<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
		<li>
			<span class="fr"><?php echo $this->_var['row']['money']; ?></span>
			<p class="pay-way">资金记录</p>
			<time><?php echo $this->_var['row']['log_info']; ?></time>
			<p class="payed"><?php echo $this->_var['row']['log_time']; ?></p>
			<div class="clear"></div>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
	<?php else: ?>
		<div class="null_data">
		<p class="icon"><i class="iconfont">&#xe6e8;</i></p>
		<p class="message">暂无数据</p>
		</div>
	<?php endif; ?>	
</ul>	
<?php if ($this->_var['pages']): ?>
	<div class="fy scroll_bottom_page">
		<?php echo $this->_var['pages']; ?>
	</div>
<?php endif; ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>