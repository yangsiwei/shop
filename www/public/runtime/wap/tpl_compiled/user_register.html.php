<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/user_register.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/user_register.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";

?>
<?php echo $this->fetch('inc/header_register.html'); ?>

<div class="wrap" id="user-register-loginform" >
	<form action="<?php
echo parse_url_tag("u:index|user#dophregister|"."".""); 
?>" id="register_box">
	<section class="register">
		<ul class="input-list split-line-top split-line">
			<li>
				<i class="iconfont">&#xe6eb;</i>
				<div class="input-box split-line">
				<label class="com-input">
					<input class="phone-num"  type="tel" maxlength="11" class="mobile" name="mobile" id="mobile"  placeholder="请输入您的手机号码">
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
                     <input class="testing third" maxlength="6" type="tel" id = "sms_verify" name="sms_verify"  placeholder="请输入手机验证码" style="width:80%">
				</label>
				</div>
			</li>
			<li>
				<i class="iconfont">&#xe6ef;</i>
				<div class="input-box split-line">
				<label class="com-input">
					<input type="password" name="user_pwd" placeholder="请输入您的登录密码" class="password">
				</label>
				</div>
			</li>
			<li>
				<input type="checkbox" checked="checked" id="read" style="margin-left:20px;background:#D93A55;">我已阅读并同意<a href="<?php
echo parse_url_tag("u:index|user#register_agree|"."".""); 
?>" style="color:#d93a55">《注册协议》</a>及<a href="<?php
echo parse_url_tag("u:index|user#register_privacy|"."".""); 
?>" style="color:#d93a55">《隐私条款》</a>
			</li>

		</ul>
		<div class="login-btn-box">
		<input type="submit" value="下一步" class="login-btn login-btn-red">
		</div>
		<div class="a-box">
		<a href="<?php
echo parse_url_tag("u:index|user#getpassword|"."".""); 
?>" class="forget fr">忘记密码？</a>
		<div class="clear"></div>
		</div>
	</section>
</form>
</div>
<?php echo $this->fetch('inc/sms_verify_code.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
