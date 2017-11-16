<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_check.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";

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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/cart_check.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/cart_check.js";


?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>

<script type="text/javascript">
	var ajaxing = false; //ajax计算中，true是不允许提交订单
	var order_id = '<?php echo $this->_var['data']['order_id']; ?>';  //订单号
    var is_number_choose='<?php echo $this->_var['data']['is_number_choose']; ?>';
    var is_pk='<?php echo $this->_var['data']['is_pk']; ?>';
</script>
<div class="wrap">
	<input type="hidden" value="<?php echo $this->_var['payment_id']; ?>" name="payment_id"/>
	<input type="hidden" value="<?php echo $this->_var['account_id']; ?>" name="account_id"/>
    <?php if ($this->_var['data']['is_number_choose']): ?>
    <form  action="<?php
echo parse_url_tag("u:index|number_choose#done|"."".""); 
?>" method="POST"  id="pay-form" class="pay-form">
    <?php elseif ($this->_var['data']['is_pk']): ?>
    <form  action="<?php
echo parse_url_tag("u:index|pk#done|"."".""); 
?>" method="POST"  id="pay-form" class="pay-form">
    <?php elseif ($this->_var['data']['order_id']): ?>
        <form  action="<?php
echo parse_url_tag("u:index|cart#order_done|"."".""); 
?>" method="POST"  id="pay-form" class="pay-form">
        <input name="order_id" type="hidden" value="<?php echo $this->_var['data']['order_id']; ?>" />
    <?php else: ?>
        <form  action="<?php
echo parse_url_tag("u:index|cart#done|"."".""); 
?>" method="POST"  id="pay-form" class="pay-form">
    <?php endif; ?>
	<div class="back-white">
	<div class="goodsum list-li">
	<header>
		<i class="iconfont up-btn order-info-btn fr">&#xe6c4;</i>
		<i class="iconfont down-btn order-info-btn fr">&#xe6c3;</i>

		<p>订单明细</p>
		<div class="clear"></div>
	</header>
	</div>
	<section class="goodsum-info split-line">
		<div class="info">
			<h2>注：请确认如下订单明细</h2>
			<div class="txtbox">
      <?php $_from = $this->_var['data']['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
      		<div class="txtbox-info">
			<p class="fl txt-over"><?php echo $this->_var['item']['name']; ?></p>
			<p class="fr"><span><?php echo $this->_var['item']['number']; ?></span>人次</p>
			<div class="clear"></div>
			</div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>

		</div>
	</section>
	<div class="blank-bar"></div>
	<!--留言-->
        <!--
	<div class="normal-fieldset">
		<h4 class="mj-title m-t10 list-li">留言信息</h4>
		<div class="textarea-box split-line">
		<textarea name="content" placeholder="请填写附加要求"><?php echo $this->_var['data']['order_memo']; ?></textarea>
		</div>
    </div>
        ==>
	<!--end 留言-->


	<!--支付方式-->
	<!--<?php if ($this->_var['data']['show_payment']): ?>-->
    <!--<div class="normal-fieldset">-->
		<!--<h4 class="mj-title m-t10 list-li">选择支付方式</h4>-->
        <!--<section class="items-common common-radio-box">-->
            <!--<?php $_from = $this->_var['data']['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');$this->_foreach['payment'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['payment']['total'] > 0):
    foreach ($_from AS $this->_var['payment']):
        $this->_foreach['payment']['iteration']++;
?>-->
            <!--<div class="item-common list-li" <?php if (($this->_foreach['payment']['iteration'] == $this->_foreach['payment']['total'])): ?>style="border-bottom:none;"<?php endif; ?>>-->
                    <!--<label class="pay-ways" rel="payment"><?php echo $this->_var['item']['bank_name']; ?><span class="payment-name"  bank_name="<?php echo $this->_var['item']['bank_name']; ?>" rel="<?php echo $this->_var['item']['id']; ?>" ></span><?php if ($this->_var['payment']['logo'] != ''): ?><img style="padding: .4rem ; width:1.8rem;" src="<?php echo $this->_var['payment']['logo']; ?>" ><?php echo $this->_var['payment']['name']; ?><?php else: ?><?php echo $this->_var['payment']['name']; ?><?php endif; ?></label>-->
                    <!--<input class="payment-item" type="radio"  value="<?php echo $this->_var['payment']['id']; ?>" name="payment" style="display:none;" />-->
            <!--</div>-->
			<!--<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
        <!--</section>-->
    <!--</div>-->
	<!--<?php endif; ?>-->
	<!--end 支付方式-->
	<div class="blank-line" style="color: #000"></div>

  <!--余额-->
	<?php if ($this->_var['data']['has_account']): ?>
    <div class="normal-fieldset">
        <section class="items-common common-radio-box">
            <div class="item-common list-li" style="border-bottom:none;width:80%;margin:auto;">
                <label class="pay-ways" rel="all_account_money"><?php echo $this->_var['item']['bank_name']; ?><span class="payment-name"></span>	确认支付：我的余额<?php echo $this->_var['account_amount']; ?>夺宝币</label>
                  <input class="payment-item"  type="checkbox" value="1" name="all_account_money" style="display:none;"/>

            </div>
        </section>
    </div>
	<?php endif; ?>
	<!--end 余额-->

	<!--代金券-->
	<!--<div class="blank-line"></div>-->
        <!--<?php if ($this->_var['data']['has_ecv'] == 1 && $this->_var['data']['voucher_list']): ?>-->
		<!--<div class="normal-fieldset" style="background: #fff">-->
			<!--<h4 class="mj-title m-t10 list-li">请先择红包</h4>-->
	            <!--<div class="item-common list-li">-->
	            <!--<i class="iconfont down-arrow">&#xe6c3;</i>-->
	               <!--<select name="ecvsn" class="red-bag">-->
	               		<!--<option value="">不使用红包</option>-->
						<!--<?php $_from = $this->_var['data']['voucher_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'voucher');if (count($_from)):
    foreach ($_from AS $this->_var['voucher']):
?>-->
						<!--<option value="<?php echo $this->_var['voucher']['sn']; ?>"><?php echo $this->_var['voucher']['name']; ?></option>-->
						<!--<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
	               <!--</select>-->
	            <!--</div>-->
		<!--</div>-->
        <!--<?php endif; ?>-->

	<!--end代金券-->

	<div class="blank-line"></div>
	<!--总计-->
	<div id="cart_total"></div>
	<!--end总计-->


	<div class="btn_order">
			<input class="sub" type="submit" value="去结算">
	  </div>
	</div>
	</form>
<div class="blank50"></div>
</div>

	<div class="am-layer ">
	    <div class="am-layer-title"><span class="title-txt">支付提示</span><a href="<?php echo $this->_var['data']['cencel_url']; ?>"><div class="cencel-btn iconfont"></div></a></div>
	  <div class="am-layer-con">
			<div style=" padding:0.2rem; text-align:center;" id="payment_tip">
				<p style="font-size:0.6rem;">
					请您在新打开的页面完成付款。				
				</p>
				<div class="blank10"></div>
				<div class="blank10"></div>
				<div class="notice" style="font-size: 0.54rem;color:#808080">
					<p class="txt-red">付款完成前请不要关闭此窗口。</p>
					完成付款后请根据您的情况点击下面的按钮：				</div>
				<div class="blank10"></div>
				<div class="blank10"></div>
				<div class="tip_btn">
					<a class="btn btn-blue reload-btn" href="">重新发起支付</a>
					<a class="btn btn-red success-btn" href="">已完成付款</a>
				</div>
			</div>
	  </div>


</div>
<?php echo $this->fetch('inc/no_footer.html'); ?>
