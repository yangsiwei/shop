<div id="cart_total_box">
	<div class="order-check-form ">
		<p style="text-align: right; line-height: 24px;">
		<?php
echo lang("DEAL_TOTAL_PRICE"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['total_price'],
);
echo $k['name']($k['value']);
?>
		<?php if ($this->_var['result']['delivery_fee'] > 0): ?>
		+ <?php
echo lang("DELIVERY_FEE"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['delivery_fee'],
);
echo $k['name']($k['value']);
?>
		<?php endif; ?>
		<?php if ($this->_var['result']['user_discount'] > 0): ?>
		- <?php
echo lang("USER_DISCOUNT"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['user_discount'],
);
echo $k['name']($k['value']);
?>
		<?php endif; ?>
		<?php if ($this->_var['result']['payment_fee'] > 0): ?>
		+ <?php
echo lang("PAYMENT_FEE"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['payment_fee'],
);
echo $k['name']($k['value']);
?>
		<?php endif; ?>
		<?php if ($this->_var['result']['paid_account_money'] > 0): ?>
		- <?php
echo lang("ACCOUNT_PAY_AMOUNT"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['paid_account_money'],
);
echo $k['name']($k['value']);
?>
		<?php endif; ?>
		<?php if ($this->_var['result']['paid_ecv_money'] > 0): ?>
		- <?php
echo lang("ECV_PAY_AMOUNT"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['paid_ecv_money'],
);
echo $k['name']($k['value']);
?>
		<?php endif; ?>
		=
		<span class="red"><?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['pay_total_price'],
);
echo $k['name']($k['value']);
?></span>
		</p>
		<p style="text-align: right; line-height: 24px;">

		<?php if ($this->_var['result']['account_money'] > 0): ?>
		- <?php
echo lang("ACCOUNT_PAY"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['account_money'],
);
echo $k['name']($k['value']);
?><br>
		<?php endif; ?>

		<?php if ($this->_var['result']['ecv_money'] > 0): ?>
		- <?php
echo lang("ECV_PAY"); 
?>：<?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['ecv_money'],
);
echo $k['name']($k['value']);
?><br>
		<?php endif; ?>

		= <?php
echo lang("PAY_TOTAL_PRICE_ORDER"); 
?>：<?php if ($this->_var['result']['payment_info']): ?> 使用<?php echo $this->_var['result']['payment_info']['name']; ?>付款<?php endif; ?>
		<span class="red"><?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['pay_price'],
);
echo $k['name']($k['value']);
?></span>
		</p>
		<p style="text-align: right; line-height: 24px;">
		<?php if ($this->_var['result']['return_total_money'] != 0): ?>
		<?php
echo lang("RETURN_TOTAL_MONEY"); 
?>： <?php 
$k = array (
  'name' => 'format_price',
  'value' => $this->_var['result']['return_total_money'],
);
echo $k['name']($k['value']);
?> <br>
		<?php endif; ?>
		<?php if ($this->_var['result']['return_total_score'] != 0): ?>
			<?php if ($this->_var['result']['buy_type'] == 1): ?>
			消耗积分：  <?php echo format_score(abs($this->_var['result']['return_total_score']));?>
			<?php else: ?>
			<?php
echo lang("RETURN_TOTAL_SCORE"); 
?>：<?php 
$k = array (
  'name' => 'format_score',
  'value' => $this->_var['result']['return_total_score'],
);
echo $k['name']($k['value']);
?>
			<?php endif; ?>
		<?php endif; ?>
		</p>
		</div>

		<?php if ($this->_var['result']['promote_description']): ?>
		<div class="f_r" style="text-align:right;">
		<div class="promote_title">参与的促销活动</div>
		<?php $_from = $this->_var['result']['promote_description']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'promote_item');if (count($_from)):
    foreach ($_from AS $this->_var['promote_item']):
?>
		<div class="promote_item"><?php echo $this->_var['promote_item']; ?></div>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<div class="blank"></div>
		<?php endif; ?>
</div>