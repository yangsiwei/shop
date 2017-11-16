<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
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
<?php echo $this->fetch('inc/header_login.html'); ?>
<?php echo $this->fetch('inc/sms_verify_code.html'); ?>
<div class="wrap">

	<?php if ($this->_var['user_info']['is_tmp'] == 1 && $this->_var['user_info']['mobile'] == ''): ?>
	<!--请绑定手机-->
	<form action="<?php
echo parse_url_tag("u:index|user#dophbind|"."".""); 
?>" id="ph_login_box">
	<section class="login-phone ">
		<ul class="input-list split-line-top split-line">
			<li>
				<i class="iconfont">&#xe6eb;</i>
				<div class="input-box split-line">
				<label class="com-input">
					 <input type="tel" maxlength="11" id="mobile" class="mobile" name="mobile" placeholder="请输入手机号">
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
                                    <input class="testing third" maxlength="6" type="tel" id = "sms_verify" name="sms_verify"  placeholder="请输入手机短信中的验证码" style="width: 60%;">
				</label>
				</div>
			</li>
		</ul>
		<div class="login-btn-box">
		<input id="ph_login_box" type="submit" value="绑定手机" class="login-btn">
		</div>
	</section>
	</form>
	<?php else: ?>
		 <?php if ($this->_var['user_info']): ?>
		 
		 <?php if ($this->_var['user_info']['mobile']): ?>
		 <!--手机验证登录-->
		 <form action="<?php
echo parse_url_tag("u:index|user#dologin|"."".""); 
?>" id="com_login_box">
		 <section class="login-phone">
	 		<ul class="input-list split-line-top split-line">
	 			<li>
	 				<i class="iconfont">&#xe6eb;</i>
	 				<div class="input-box split-line">
	 				<label class="com-input">
	 					 <input type="tel" maxlength="11" id="mobile" class="mobile" name="mobile" placeholder="请输入手机号">
	 				</label>
	 				<label>
	 					<input class="btn_phone" type="Button"  id="sms_btn" unique="0" value="点击获取验证码" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" />
	 				</label>
	 				</div>
	 			</li>
	 			<li>
	 				<i class="iconfont">&#xe6f0;</i>
	 				<div class="input-box split-line">
	 				<label class="com-input">
	 					<input class="testing third" type="tel" maxlength="6" id = "sms_verify" name="sms_verify"  placeholder="请输入手机短信中的验证码"  style="width: 60%;">
	 				</label>
	 				</div>
	 			</li>
	 		</ul>
	 		<div class="login-btn-box">
	 		<input  id="ph_login_box" type="submit" value="登录" class="login-btn">
	 		</div>
			<div class="a-box">
			<a href="javascript:void(0);" class="swich fl">手机登录</a>
			<div class="clear"></div>
			</div>
	 	</section>
	</form>
		 <?php endif; ?>
		 <?php else: ?>
		 <!--手机账户邮箱登录-->
<form action="<?php
echo parse_url_tag("u:index|user#dologin|"."".""); 
?>" id="com_login_box">
		<section class="login-normal con-1">
		<ul class="input-list split-line-top split-line">
			<li>
				<i class="iconfont">&#xe6f1;</i>
				<div class="input-box split-line">
				<label class="com-input">
					<input type="text" name="user_key" class="mobile" placeholder="请输入手机号 / 邮箱 / 用户名" >
				</label>
				</div>
			</li>
			<li>
				<i class="iconfont">&#xe6ef;</i>
				<div class="input-box split-line">
				<label class="com-input">
					<input type="password" placeholder="请输入密码" class="password" name="user_pwd">
				</label>
				</div>
			</li>
		</ul>
                    <div class="login-btn-box" >
                            <a  id="com_login_box" type="button" class="login-btn" onclick="$(this).submit();">登录</a>
			</div>
			<div class="a-box">
			<a href="#" class="swich fl">手机账号登录</a>
			<a href="<?php
echo parse_url_tag("u:index|user#getpassword|"."".""); 
?>" class="forget fr">忘记密码？</a>
			<div class="clear"></div>
			</div>
		</section>
	</form>

	<!--手机验证登录-->
	<form action="<?php
echo parse_url_tag("u:index|user#dophlogin|"."".""); 
?>" id="ph_login_box">
	<section class="login-phone con-2">
	 <ul class="input-list split-line-top split-line">
		 <li>
			 <i class="iconfont">&#xe6eb;</i>
			 <div class="input-box split-line">
			 <label class="com-input">
				 <input type="tel" maxlength="11" id="mobile" class="mobile" name="mobile" placeholder="请输入手机号">
			 </label>
			 <label>
				 <input class="btn_phone" type="Button"  id="sms_btn" unique="0" value="点击获取验证码" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" />
			 </label>
			 </div>
		 </li>
		 <li>
			 <i class="iconfont">&#xe6f0;</i>
			 <div class="input-box split-line">
			 <label class="com-input">
				 <input class="testing third" type="tel" maxlength="6" id = "sms_verify" name="sms_verify"  placeholder="请输入手机短信中的验证码"  style="width: 60%;">
			 </label>
			 </div>
		 </li>
	 </ul>
	 <div class="login-btn-box">
	 <input id="ph_login_box" type="submit" value="登录" class="login-btn">
	 </div>
	 <div class="a-box">
	 <a href="javascript:void(0);" class="swich fl">手机登录</a>
	 <a href="<?php
echo parse_url_tag("u:index|user#getpassword|"."".""); 
?>" class="forget fr">忘记密码？</a>
	 <div class="clear"></div>
	 </div>
 </section>
</form>
		 <?php endif; ?>
	<?php endif; ?>
</div>
<!-- <div class="third-login">
	<p><span>第三方登录</span></p>
	<div class="third-login-info">
		<?php if ($this->_var['is_weixin']): ?>
		<a href="javascript:void(0);" onclick="weixin_login();" class="weixin-login">
			<i class="iconfont">&#xe6f7;</i>
			<span>微信</span>
		</a>
		<?php endif; ?>
		<?php if ($this->_var['is_app'] && $this->_var['m_config']['wx_mappid'] != '' && $this->_var['m_config']['wx_mappsecret'] != ''): ?>
		<a href="javascript:void(0);" onclick="weixin_login_app();" class="weixin-login">
			<i class="iconfont">&#xe6f7;</i>
			<span>微信</span>
		</a>
		<?php endif; ?>


		<?php if (! $this->_var['is_app'] && $this->_var['WB_APP_KEY'] != '' && $this->_var['WB_APP_SECRET'] != ''): ?>
		<a href="<?php
echo parse_url_tag("u:index|user#wb_login|"."".""); 
?>" class="weixin-login">
			<i style="background: #F5101B;" class="iconfont">&#xe6a2;</i>
			<span>微博</span>
		</a>
		<?php endif; ?>

		<?php if ($this->_var['is_app'] && $this->_var['m_config']['sina_app_key'] != '' && $this->_var['m_config']['sina_app_secret'] != ''): ?>
		<a href="javascript:void(0);" onclick="weibo_login_app();" class="weixin-login">
			<i style="background: #F5101B;" class="iconfont">&#xe6a2;</i>
			<span>微博</span>
		</a>
		<?php endif; ?>

		<?php if (! $this->_var['is_app'] && $this->_var['QQ_HL_APPID'] != '' && $this->_var['QQ_HL_APPKEY'] != ''): ?>
		<a href="<?php
echo parse_url_tag("u:index|user#qq_login|"."".""); 
?>" class="weixin-login">
		<i style="background: #21c11e;" class="iconfont">&#xe6a0;</i>
		<span>QQ</span>
		</a>
		<?php endif; ?>

		<?php if ($this->_var['is_app'] && $this->_var['m_config']['qq_app_secret'] != '' && $this->_var['m_config']['qq_app_key'] != ''): ?>
		<a href="javascript:void(0);" onclick="qq_login_app();" class="weixin-login">
			<i style="background: #21c11e;" class="iconfont">&#xe6a0;</i>
			<span>QQ</span>
		</a>
		<?php endif; ?>


	</div>
</div> -->


<?php echo $this->fetch('inc/footer_index.html'); ?>
