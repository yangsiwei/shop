<?php $_from = $this->_var['data']['feeinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'feeinfo');$this->_foreach['feeinfo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['feeinfo']['total'] > 0):
    foreach ($_from AS $this->_var['feeinfo']):
        $this->_foreach['feeinfo']['iteration']++;
?>
<div class="item-common list-li" <?php if (($this->_foreach['feeinfo']['iteration'] == $this->_foreach['feeinfo']['total'])): ?>style="border-bottom:none;"<?php endif; ?>>
    <span class="item-label fl"><?php echo $this->_var['feeinfo']['name']; ?></span>
    <div class="item-content red fr"><?php echo $this->_var['feeinfo']['value']; ?></div>
	<div class="clear"></div>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>