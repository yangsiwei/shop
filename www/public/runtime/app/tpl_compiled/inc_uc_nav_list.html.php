
<div class="nav_item_title">
	<a href="<?php
echo parse_url_tag("u:index|uc_center|"."".""); 
?>">个人中心</a>
</div>
<hr>
<div class="side_nav">
<?php if ($this->_var['uc_nav_list']): ?>
<?php $_from = $this->_var['uc_nav_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('nav_key', 'nav_item_0_58031100_1510721594');if (count($_from)):
    foreach ($_from AS $this->_var['nav_key'] => $this->_var['nav_item_0_58031100_1510721594']):
?>
	<dl class="nav_item">
		<?php $_from = $this->_var['nav_item_0_58031100_1510721594']['node']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('s_nav_key', 's_nav_item');if (count($_from)):
    foreach ($_from AS $this->_var['s_nav_key'] => $this->_var['s_nav_item']):
?>
			<dd><a class="<?php if ($this->_var['s_nav_item']['current'] == 1): ?>current<?php endif; ?>" href="<?php echo $this->_var['s_nav_item']['url']; ?>"><?php echo $this->_var['s_nav_item']['name']; ?></a></dd>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</dl>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<div class="blank20"></div>
</div>