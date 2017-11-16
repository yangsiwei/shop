<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_index.css";
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/cart_index.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/cart_index.js";
?>
<?php echo $this->fetch('inc/header_cart.html'); ?>
<script type="text/javascript">
var main_url='<?php
echo parse_url_tag("u:index|index|"."".""); 
?>';
var jsondata=<?php echo $this->_var['jsondata']; ?>;
</script>
<div class="<?php 
$k = array (
  'name' => 'load_wrap',
  't' => $this->_var['wrap_type'],
);
echo $k['name']($k['t']);
?> cate_row">
<div class="blank20"></div>
	<form name="buy_form" id="buy_form" action="<?php
echo parse_url_tag("u:index|cart#check|"."".""); 
?>" method="post">
		<input type="hidden" value="<?php echo $this->_var['type']; ?>" name="type"/>
		<div class="wrap">
		<?php if ($this->_var['type'] == 'free'): ?>
			<div id="cart_form">
				<?php echo $this->fetch('inc/cart_list_free.html'); ?>
			</div>
		<?php else: ?>
			<div id="cart_form">
				<?php echo $this->fetch('inc/cart_list.html'); ?>
			</div>
		<?php endif; ?>
		
			<div class="blank5"></div>
			<?php if ($this->_var['is_cart_agreement'] == 0): ?>
				<div class="f_r">
					<input type="checkbox" name="check_agreement" <?php if ($this->_var['is_new_member'] == 0 && $this->_var['type'] == 'free'): ?>disabled<?php endif; ?> /> 我已阅读并同意《服务协议》
				</div>
				<div class="blank5"></div>
				<div id="agreement" style="padding: 10px 20px;height: 330px;border: 2px solid #ddd;overflow-y: auto;" >
				<?php echo $this->_var['agreement']['agreement']; ?>
				</div>
			<?php endif; ?>
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
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend']['id']."".""); 
?>">
										<img src="<?php echo $this->_var['recomend']['icon']; ?>" width="200" height="200" lazy="true" />
									</a>
								</div>
								<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend']['id']."".""); 
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
	</form>
</div>

<?php echo $this->fetch('inc/footer.html'); ?>