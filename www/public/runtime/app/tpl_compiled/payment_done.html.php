<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/payment_done.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/goods_item.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/zone.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/lazyload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";

?>
<?php echo $this->fetch('inc/header_cart.html'); ?>
<script>
$(document).ready(function(){
	$(".copy").click(function(){
		$("input[name='cfm_new_pwd']:text").select();
		document.execCommand("Copy");
		alert("已复制");
	});
});
</script>
<!-- 支付成功 -->
<div class="blank20"></div>
<div class="wrap_full_w">
<!-- 充值成功 -->
	<?php if ($this->_var['order_info']['type'] == 1): ?>
	<style>
		.process_head{display:none;}
	</style>
	
	<div class="message">
		<div class="suc-ico ico"></div>
		<h1 class="title">恭喜您，充值成功，充值金额为<?php echo $this->_var['order_info']['total_price']; ?>！
		</h1>
		<div class="tips">
			<p>您现在可以
			<a class="btn btn-main" href="<?php echo $this->_var['APP_ROOT']; ?>/">返回首页</a>
			</p>
		</div>
	</div>
	<?php else: ?>
	<div class="message">
		<div class="suc-ico ico"></div>
		<h1 class="title">恭喜您，参与成功！请等待系统为您揭晓！<a style="font-size: 13px;font-weight: normal;" href="<?php
echo parse_url_tag("u:index|uc_duobao|"."".""); 
?>">查看夺宝记录</a>
		</h1>
		<div class="tips">
			<p>您现在可以
			<a href="<?php echo $this->_var['APP_ROOT']; ?>/">返回首页</a>
			或
			<a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>" class="btn btn-main">查看更多商品</a>
			</p>
			<p style="margin-top: 8px">您成功参与了<?php echo $this->_var['total_number']; ?>人次夺宝，信息如下：</p>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th width="219">夺宝时间</th>
				<th width="270">商品名称</th>
				<th width="100" style="text-align: center;">商品期号</th>
				<th width="100" style="text-align: center;">参与人次</th>
				<th width="361">当期号码</th>
			</tr>
		</thead>
		<?php if ($this->_var['order_item']): ?>
		<tbody>
		<?php $_from = $this->_var['order_item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
			<tr>
				<td><?php echo $this->_var['item']['create_time_format']; ?></td>
				<td><a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['item']['duobao_item_id']."".""); 
?>"><?php echo $this->_var['item']['name']; ?></a></td>
				<td style="text-align: center;"><?php echo $this->_var['item']['duobao_item_id']; ?></td>
				<td style="text-align: center;"><?php echo $this->_var['item']['number']; ?></td>
				<td>
					<ul>
					<?php $_from = $this->_var['item']['lottery_sn_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lottery_item');$this->_foreach['lottery_sn_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['lottery_sn_list']['total'] > 0):
    foreach ($_from AS $this->_var['lottery_item']):
        $this->_foreach['lottery_sn_list']['iteration']++;
?>
						<?php if (($this->_foreach['lottery_sn_list']['iteration'] - 1) < 7): ?>
						<li><?php echo $this->_var['lottery_item']; ?></li>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php if (count ( $this->_var['item']['lottery_sn_list'] ) > 7): ?>
						<li><a href="javascript:my_no_all(<?php echo $this->_var['item']['duobao_item_id']; ?>,<?php echo $this->_var['order_info']['user_id']; ?>,<?php echo $this->_var['item']['id']; ?>);">查看更多>></a></li>
						<?php endif; ?>
					</ul>
				</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</tbody>
		<?php endif; ?>
	</table>
	<?php if ($this->_var['sendmoney']['split_red_money']): ?>
	<div class="linlin" >
	<input  class="testing" type="text" name="cfm_new_pwd" value="<?php echo $this->_var['sendmoney']['url']; ?>" placeholder="分享链接" readonly="readonly">
	<span class="copy">复制</span>
	<div class="blank"></div>
	<p class="testing-tip">复制链接分享给好友领红包</p>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</div>
<?php echo $this->fetch('inc/footer.html'); ?>