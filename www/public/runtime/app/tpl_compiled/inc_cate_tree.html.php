<div class="cate_tree">
<!-- <?php $_from = $this->_var['cate_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['cate_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate_loop']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['cate_loop']['iteration']++;
?>
<?php if (( $this->_var['count'] > 0 && $this->_foreach['cate_loop']['iteration'] <= $this->_var['count'] ) || $this->_var['count'] == 0): ?>
<dl class="<?php if (($this->_foreach['cate_loop']['iteration'] == $this->_foreach['cate_loop']['total']) || $this->_var['count'] == $this->_foreach['cate_loop']['iteration']): ?>no_border<?php endif; ?>">
	<dt><a href="<?php echo $this->_var['nav']['url']; ?>"><?php if ($this->_var['nav']['iconfont']): ?><i class="diyfont"><?php echo $this->_var['nav']['iconfont']; ?></i>&nbsp;<?php endif; ?><?php echo $this->_var['nav']['name']; ?></a></dt>
	<?php if ($this->_var['nav']['sub_nav']): ?>
	<dd class="sub_nav <?php if (! $this->_var['nav']['pop_nav']): ?>no_arrow<?php endif; ?>">
		<ul>
			<?php $_from = $this->_var['nav']['sub_nav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub_nav');if (count($_from)):
    foreach ($_from AS $this->_var['sub_nav']):
?>
			<li><a href="<?php echo $this->_var['sub_nav']['url']; ?>" <?php if ($this->_var['sub_nav']['is_recommend'] == 1): ?>class="heavy"<?php endif; ?> ><?php echo $this->_var['sub_nav']['name']; ?></a></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</dd>
	<?php endif; ?>
	<?php if ($this->_var['nav']['pop_nav']): ?>
	<dd class="pop_nav">
		<span><a href="<?php echo $this->_var['nav']['url']; ?>"><?php echo $this->_var['nav']['name']; ?></a></span>
		<ul>
			<?php $_from = $this->_var['nav']['pop_nav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pop_nav');if (count($_from)):
    foreach ($_from AS $this->_var['pop_nav']):
?>
			<li><a href="<?php echo $this->_var['pop_nav']['url']; ?>" <?php if ($this->_var['pop_nav']['is_recommend'] == 1): ?>class="heavy"<?php endif; ?>><?php echo $this->_var['pop_nav']['name']; ?></a>&nbsp;&nbsp;|</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</dd>
	<?php endif; ?>
</dl>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> -->
<ul>
<li>
		<a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>"><?php if ($this->_var['nav']['iconfont']): ?><i class="diyfont"><?php echo $this->_var['nav']['iconfont']; ?></i>&nbsp;<?php endif; ?>全部商品</a>
</li>
<?php $_from = $this->_var['cate_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['cate_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate_loop']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['cate_loop']['iteration']++;
?>
	<li>
		<a href="<?php
echo parse_url_tag("u:index|duobaos|"."id=".$this->_var['nav']['id']."".""); 
?>"><?php if ($this->_var['nav']['iconfont']): ?><i class="diyfont"><?php echo $this->_var['nav']['iconfont']; ?></i>&nbsp;<?php endif; ?><?php echo $this->_var['nav']['name']; ?></a>
	</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
</div>
<div class="ico nav-bolan"></div>