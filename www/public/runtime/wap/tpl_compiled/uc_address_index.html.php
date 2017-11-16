<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_address.css";	
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_address.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_address.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>


<div class="wrap">
	<div class="content">
		<div class="add"><a href="<?php
echo parse_url_tag("u:index|uc_address#add|"."".""); 
?>" class="add_address" >新增地址</a></div>
	 	<div class="blank"></div>
	 	<div class="address_list">
	 		<?php $_from = $this->_var['data']['consignee_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	 		<ul>
						<li>收件人：<?php echo $this->_var['item']['consignee']; ?></li>
						<li>手机：<?php echo $this->_var['item']['mobile']; ?></li>
						<li><?php echo $this->_var['item']['region_lv2_name']; ?> <?php echo $this->_var['item']['region_lv3_name']; ?> <?php echo $this->_var['item']['region_lv4_name']; ?></li>
						<li>详细地址：<?php echo $this->_var['item']['address']; ?></li>	 			
	 		</ul>
	 		<div class="add clearfix">
	 			<a href="<?php echo $this->_var['item']['url']; ?>" class="operate" >修改</a>
	 			<a href="javascript:;" class="operate del"  url="<?php echo $this->_var['item']['del_url']; ?>" >删除</a>
	 			<a href="javascript:;" <?php if ($this->_var['item']['is_default'] == 0): ?>dfurl="<?php echo $this->_var['item']['dfurl']; ?>"<?php endif; ?> class="operate  <?php if ($this->_var['item']['is_default'] == 1): ?>defaulted<?php else: ?>set_default<?php endif; ?>" ><?php if ($this->_var['item']['is_default'] == 1): ?>默认地址<?php else: ?>设为默认<?php endif; ?></a>
	 		</div>
	 		<div class="blank"></div>
	 		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	 	</div>
	</div>
</div>

<?php echo $this->fetch('inc/footer_index.html'); ?>	