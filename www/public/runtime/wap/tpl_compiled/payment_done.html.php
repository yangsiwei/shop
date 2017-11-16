<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/payment.css";
 

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";



?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<script>
$(document).ready(function(){
	$("#cfm_new_pwd").click(function(){
		$("input[name='cfm_new_pwd']:text").select();
		document.execCommand("Copy");
 	});
});
</script>

<div class="pay_order_index">
<?php if ($this->_var['data']['pay_type'] == 3): ?>
	<div class="pay_info">
		<div class="h font-fc8">订单编号：<?php echo $this->_var['data']['order_sn']; ?></div>
		<div style="line-height:20px;" class="h font-fc8">已购商品：<?php echo $this->_var['data']['pay_info']; ?></div>
	</div>
	</div>
	<?php if ($this->_var['data']['split_red_money']): ?>
		<div class="linlin flex-box" style="width:95%;">
		<input  class="testing flex-1" type="text" name="cfm_new_pwd" id="cfm_new_pwd" value="<?php echo $this->_var['data']['url']; ?>" placeholder="分享链接" readonly="readonly">
		</div>
		<p class="testing-tip">复制链接分享给好友领红包</p>
	<?php endif; ?>
	<?php if ($this->_var['data']['pay_status'] == 1): ?>
	 	<div class="btn_login" style="text-align: center;">
	    	<a href="<?php
echo parse_url_tag("u:index|uc_totalbuy#index|"."".""); 
?>" p="&pay_status=1">
	    		<input style="width:90%;"type="submit" value="购买记录">
			</a>
		</div>
	<?php else: ?>
	<form id="form" onsubmit="return check_form();" method="post" action="<?php
echo parse_url_tag("u:index|totalbuy#pay_check|"."id=".$this->_var['data']['order_id']."".""); 
?>">
	    <div class="btn_login">
	    	<input id="input_submit" type="submit" value="<?php echo $this->_var['data']['pay_info']; ?>，继续付款">
		</div>
	</form>
	<?php endif; ?>
<?php else: ?>
	<div class="pay_info">
		<div class="h font-fc8">订单编号：<?php echo $this->_var['data']['order_sn']; ?></div>
	
	</div>
	<?php if ($this->_var['data']['split_red_money']): ?>
		<div class="linlin flex-box" >
		<input  class="testing flex-1" type="text" name="cfm_new_pwd"  id="cfm_new_pwd" value="<?php echo $this->_var['data']['url']; ?>" placeholder="分享链接" readonly="readonly">
		</div>
		<p class="testing-tip">复制链接分享给好友领红包</p>
	<?php endif; ?>
    <?php if ($this->_var['data']['pay_status'] == 1): ?>
    <div class="btn_login">
        <a href="<?php
echo parse_url_tag("u:index|uc_duobao_record#index|"."".""); 
?>" p="&pay_status=1">
        <input type="submit" value="<?php echo $this->_var['data']['pay_info']; ?>">
        </a>
    </div>
	<?php else: ?>
	<form id="form" onsubmit="return check_form();" method="post" action="<?php
echo parse_url_tag("u:index|cart#order|"."id=".$this->_var['data']['order_id']."".""); 
?>">
	    <div class="btn_login">
	    	<input id="input_submit" type="submit" value="<?php echo $this->_var['data']['pay_info']; ?>，继续付款">
		</div>
	    </form>
	<?php endif; ?>
<?php endif; ?>
</div>

<?php echo $this->fetch('inc/footer_index.html'); ?>
