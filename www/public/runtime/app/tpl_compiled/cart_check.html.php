<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_order.css";
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/cart_check.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/cart_check.js";
?>
<?php echo $this->fetch('inc/header_cart.html'); ?>
<script type="text/javascript">
var main_url='<?php
echo parse_url_tag("u:index|index|"."".""); 
?>';
var APP_ROOT = '<?php echo $this->_var['APP_ROOT']; ?>';
var CART_URL = '<?php
echo parse_url_tag("u:index|cart|"."".""); 
?>';
var CART_CHECK_URL = '<?php
echo parse_url_tag("u:index|cart#check|"."".""); 
?>';
<?php if (app_conf ( "APP_MSG_SENDER_OPEN" ) == 1): ?>
var send_span = <?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SEND_SPAN',
);
echo $k['name']($k['v']);
?>000;
var IS_RUN_CRON = 1;
var DEAL_MSG_URL = '<?php
echo parse_url_tag("u:index|cron#deal_msg_list|"."".""); 
?>';
<?php endif; ?>
var AJAX_LOGIN_URL	= '<?php
echo parse_url_tag("u:index|user#ajax_login|"."".""); 
?>';
var AJAX_URL	= '<?php
echo parse_url_tag("u:index|ajax|"."".""); 
?>';
var LOADER_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif';
</script>

<div class="<?php 
$k = array (
  'name' => 'load_wrap',
  't' => $this->_var['wrap_type'],
);
echo $k['name']($k['t']);
?> cate_row">
<div class="blank15"></div>
	<form name="cart_form" id="cart_form" action="<?php if ($this->_var['order_info']): ?><?php
echo parse_url_tag("u:index|cart#order_done|"."".""); 
?><?php else: ?><?php
echo parse_url_tag("u:index|cart#done|"."".""); 
?><?php endif; ?>" method="post">
<div class="wrap clearfix" style="border: 1px solid #ddd;">
	<table id="cart_table">
	<tr>
		<td width="480" class="text_left" style="padding-left: 15px">商品名称</td>
		<td width="130">商品期号</td>
		<td width="130">价值(夺宝币)</td>
		<td width="130">夺宝单价(夺宝币/人次)</td>
		<td width="130">参与人次</td>
		<td width="170" style="padding-right: 15px">小计(夺宝币)</td>
	</tr>
    <?php if ($this->_var['cart_list']): ?>

	<?php $_from = $this->_var['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	<tr class="cart-list" style="border-bottom: 1px solid #ddd;">

			<td class="text_left" style="padding-left: 15px">
					<a href="<?php echo $this->_var['item']['url']; ?>"><?php echo $this->_var['item']['name']; ?></a>
			</td>
			<td>
					<?php echo $this->_var['item']['duobao_item_id']; ?>
			</td>
			<td>
					<?php echo $this->_var['item']['value_price']; ?>
			</td>
			<td>
					<?php echo $this->_var['item']['unit_price']; ?>
			</td>
			<td>
					<?php echo $this->_var['item']['number']; ?>
			</td>
			<td style="padding-right: 15px">
			<p class="txt-red"><span><?php echo $this->_var['item']['total_price']; ?></span></p>
			</td>

	</tr>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr>
	<td colspan="6" style="text-align:right;line-height: 56px;padding: 0 20px" >
		<div class="cart-list-footer-total f_r" style="margin-left: 15px">商品合计：<span class="txt-red" style="font-size: 22px;font-weight: bold;"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['total_price'],
);
echo $k['name']($k['v']);
?></span></div>
		<a href="<?php
echo parse_url_tag("u:index|cart|"."".""); 
?>"  class="f_r" style="line-height: 62px">返回清单修改</a>
	</td>
	</tr>
	<td colspan="6" style="border:none;background:#f5f5f5;padding: 0;">
		
	<?php if ($this->_var['show_payment']): ?>
	<div id="cart_payment">
	<div class="cart_row layout_box" style="border: 0;">
		<div class="content" style="background:#f5f5f5;">
			<?php if ($this->_var['bank_paylist']): ?>
			<?php $_from = $this->_var['bank_paylist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment_item');if (count($_from)):
    foreach ($_from AS $this->_var['payment_item']):
?>
				<?php echo $this->_var['payment_item']['display_code']; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<?php endif; ?>
			<?php if ($this->_var['icon_paylist']): ?>
			<?php $_from = $this->_var['icon_paylist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment_item');if (count($_from)):
    foreach ($_from AS $this->_var['payment_item']):
?>
				<?php echo $this->_var['payment_item']['display_code']; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<?php endif; ?>

			<div class="clear"></div>
			<div class="account_payment">
			<?php $_from = $this->_var['disp_paylist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment_item');if (count($_from)):
    foreach ($_from AS $this->_var['payment_item']):
?>
				<?php echo $this->_var['payment_item']['display_code']; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
		</div>
	</div>
	<input type="hidden" value="<?php echo $this->_var['payment_id']; ?>" name="payment_id"/>
	</div>
	
	<div id="cart_total">
	</div>
	
	<div id="cart_submit">
		<input type="hidden" value="<?php echo $this->_var['order_info']['id']; ?>" name="id" />
		<button id="order_done" class="btn btn-main f_r" rel="blue" type="button"><?php echo $this->_var['LANG']['CONFIRM_ORDER_AND_PAY']; ?></button>
	</div><!--cart_submit-->
	<div class="blank30"></div>
	<?php endif; ?>
	
	</td>


    <?php else: ?>
    <tr class="null_data">
    	<td colspan="7" >
		<div>
		<p class="message">购物车暂无数据</p>
		</div>
	    </td>
    </tr>
    <?php endif; ?>
	</table>
</div>

	</form>
    <div class="blank15"></div>
    <div class="wrap_full_w">
	    <?php if ($this->_var['recomend_list']): ?>
		<div class="index-new-goods-list" style="width: 1201px">
			<h1 class="main-title">推荐夺宝
			<a href="javascript:void(0);" id="new"  class="title-more"><i class="iconfont">&#xe624;</i>换一批</a>
			</h1>
			<ul class="ui-list" width="241" pin_col_init_width="0" wSpan="-1" hSpan="1">
				<?php $_from = $this->_var['recomend_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'recomend');if (count($_from)):
    foreach ($_from AS $this->_var['recomend']):
?>
				<li class="goods ui-item">
				<div class="hover_line">
				<div class="ten-logo-box"></div>
					<div class="goods-wrap">
						<div class="imgbox">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['recomend']['id']."".""); 
?>">
							<img src="<?php echo $this->_var['recomend']['icon']; ?>" width="200" height="200" lazy="true" />
						</a>
						</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['recomend']['id']."".""); 
?>" class="goods-title">
							<?php echo $this->_var['recomend']['duobaoitem_name']; ?>
						</a>
						<div class="p-price f_l">
							总需：<?php echo $this->_var['recomend']['max_buy']; ?>人次
						</div>
					</div>
					</div>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
		<?php endif; ?>
    </div>



</div>

<?php echo $this->fetch('inc/footer.html'); ?>