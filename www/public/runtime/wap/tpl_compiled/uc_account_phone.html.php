<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/user_login.css";

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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/user_login.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/user_login.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>

<div class="wrap">

	<div class="content">

		<form action="<?php
echo parse_url_tag("u:index|user#dophbind|"."".""); 
?>" id="ph_login_box">
			<section class="login-phone">
			<ul class="input-list split-line-top split-line">
				<li>
					<i class="iconfont">&#xe6eb;</i>
					<div class="input-box split-line">
					<label class="com-input">
						<input type="tel" class="phone" id="mobile" name="mobile" placeholder="请输入新手机号" value="<?php echo $this->_var['user_info']['mobile']; ?>">
					</label>
					<label>
						<input class="btn_phone" type="Button"  id="sms_btn" unique="1" value="点击获取验证码" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" />
					</label>
					</div>
				</li>
				<li>
					<i class="iconfont">&#xe6f0;</i>
					<div class="input-box split-line">
					<label class="com-input">
						<input class="testing third" type="tel" maxlength="6" id = "sms_verify" name="sms_verify"  placeholder="请输入手机短信中的验证码">
					</label>
					</div>
				</li>
			</ul>
			<div class="login-btn-box">
			<input type="submit" value="立即绑定" class="login-btn">
			</div>
			<!-- <div class="a-box">
			<a href="<?php
echo parse_url_tag("u:index|user#getpassword|"."".""); 
?>">取回密码</a>
			<div class="clear"></div>
			</div> -->
		</section>
	</form>


	</div>
</div>
<?php echo $this->fetch('inc/sms_verify_code.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
