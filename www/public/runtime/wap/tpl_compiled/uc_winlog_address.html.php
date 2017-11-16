<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_winlog_address.css";	
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_winlog_address.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_winlog_address.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<script type="text/javascript">
var main_url='<?php
echo parse_url_tag("u:index|uc_winlog|"."".""); 
?>';
var next_url='<?php
echo parse_url_tag("u:index|uc_winlog|"."".""); 
?>';
</script>
<div class="wrap">
	<div class="content">
		<div class="add"><a href="<?php
echo parse_url_tag("u:index|uc_address#add|"."order_item_id=".$this->_var['order_item_id']."".""); 
?>" class="add_address" >新增地址</a>
		</div>
	 	<ul class="address_list">
	 		<?php $_from = $this->_var['data']['consignee_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	 		<li class="split-line address-item <?php if ($this->_var['item']['is_default'] == 1): ?>address-item-checked<?php endif; ?>" onclick="confirm_address(this,<?php echo $this->_var['item']['id']; ?>,<?php echo $this->_var['order_item_id']; ?>)">
	 			<div class="address-item-hd clearfix">
		 			<p class="user-name">收货人：<?php echo $this->_var['item']['consignee']; ?></p>
		 			<p class="mobile"><?php echo $this->_var['item']['mobile']; ?></p>
	 			</div>
	 			<p class="address-info">收货地址：<?php echo $this->_var['item']['region_lv2_name']; ?> <?php echo $this->_var['item']['region_lv3_name']; ?> <?php echo $this->_var['item']['region_lv4_name']; ?> <?php echo $this->_var['item']['address']; ?></p>
	 			<i class="checked-ico iconfont">&#xe70a;</i>
	 		</li>
	 		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	 	</ul>
	</div>
</div>
<script>
	$(function(){
		$(".address-item").click(function() {
			$(".address-item").removeClass('address-item-checked');
			$(this).addClass('address-item-checked');
		});
	});
</script>
<?php echo $this->fetch('inc/footer_index.html'); ?>