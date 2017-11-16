<div class="login_box clearfix">
	
	<?php if (app_conf ( "SMS_ON" ) == 1): ?>
	<div class="login-tab" rel="<?php echo $this->_var['form_prefix']; ?>">
		<a class="current" href="javascript:void(0);" rel="other" lk="<?php echo $this->_var['form_prefix']; ?>"><span class="iconfont">&#xe617;</span>&nbsp;普通方式登录</a>
		<a href="javascript:void(0);" rel="ph" lk="<?php echo $this->_var['form_prefix']; ?>"><span class="iconfont">&#xe61a;</span>&nbsp;手机动态码登录</a>
	</div>
	<div class="blank"></div>
	<div class="blank"></div>
	<?php endif; ?>
	
	<div class="login-panel form_panel" rel="<?php echo $this->_var['form_prefix']; ?>">
		<div class="panel" rel="other" style="display:block;">
		<form name="<?php echo $this->_var['form_prefix']; ?>_login_form" class="login" method="post" action="<?php
echo parse_url_tag("u:index|user#dologin|"."".""); 
?>">
			<input type="hidden" name="form_prefix" value="<?php echo $this->_var['form_prefix']; ?>" />
			<dl>
				<dt>用户名</dt>
				<dd>
					<?php if ($this->_var['user_info']): ?>				
					<input class="ui-textbox" disabled="true" holder="请输入用户名/邮箱/手机号" value="<?php echo $this->_var['user_info']['user_name']; ?>" />
					<input type="hidden" value="<?php echo $this->_var['user_info']['user_name']; ?>" name="user_key" />
					<?php else: ?>
					<input class="ui-textbox" name="user_key" holder="请输入用户名/邮箱/手机号" />
					<?php endif; ?>
					<span class="form_tip"></span>
				</dd>
			</dl>
			<dl>
				<dt>密码</dt>
				<dd>
					<input type="password" name="user_pwd" class="ui-textbox" holder="请输入密码" />
					<a href="<?php
echo parse_url_tag("u:index|user#getpassword|"."".""); 
?>">忘记密码？</a>
					<span class="form_tip"></span>
				</dd>
			</dl>
			<dl>
				<dt>图片验证码</dt>
				<dd>
					<input type="text" name="verify_code" class="ui-textbox img_verify" holder="请输入图片文字" />
					<img src="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" class="verify" rel="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" />
					<a href="javascript:void(0);" class="refresh_verify">看不清楚？换一张！</a>
					<span class="form_tip"></span>
				</dd>
			</dl>
			<dl class="cbo_line">
				<dt></dt>
				<dd>
					<label class="ui-checkbox" rel="common_cbo"><input type="checkbox" name="auto_login" value="1" />自动登录</label>
				</dd>
			</dl>
			<dl>
				<dt></dt>
				<dd>

					<button class="ui-button f_l" rel="orange" type="submit">登录</button>
					<?php if ($this->_var['form_prefix'] == 'ajax'): ?>
					<a href="<?php
echo parse_url_tag("u:index|user#register|"."".""); 
?>" class="regist_btn">
					<button class="ui-button f_l" rel="blue" type="button">立即注册</button>
					</a>
					<?php endif; ?>
				</dd>
			</dl>
		</form>
		</div>
		
		<div class="panel" rel="ph">
		<form name="<?php echo $this->_var['form_prefix']; ?>_ph_login_form" class="ph_login" method="post" action="<?php
echo parse_url_tag("u:index|user#dophlogin|"."".""); 
?>">
			<input type="hidden" name="form_prefix" value="<?php echo $this->_var['form_prefix']; ?>" />
			<dl>
				<dt>手机号</dt>
				<dd>
					<input class="ui-textbox" name="user_mobile" <?php if ($this->_var['user_info']['mobile']): ?>value="<?php echo $this->_var['user_info']['mobile']; ?>"<?php else: ?>value="<?php echo $this->_var['fanwe_mobile']; ?>"<?php endif; ?> holder="请输入手机号" />
					<span class="form_tip"></span>
				</dd>
			</dl>
			
			<dl class="ph_img_verify" <?php if ($this->_var['sms_ipcount'] > 1): ?>style="display:block"<?php endif; ?>>
				<dt>图片验证码</dt>
				<dd>
					<input type="text" name="verify_code" class="ui-textbox img_verify" holder="请输入图片文字" />
					<img src="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" class="verify" rel="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" />
					<a href="javascript:void(0);" class="refresh_verify">看不清楚？换一张！</a>
					<span class="form_tip"></span>
				</dd>
			</dl>	
					
			<dl>
				<dt>验证码</dt>
				<dd>
					<input class="ui-textbox ph_verify" name="sms_verify" holder="请输入验证码" />
					<button class="ui-button light ph_verify_btn f_l" rel="light" form_prefix="<?php echo $this->_var['form_prefix']; ?>" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" type="button">发送验证码</button>
					<span class="form_tip"></span>
				</dd>
			</dl>
			<dl class="cbo_line">
				<dt></dt>
				<dd>
					<label class="ui-checkbox" rel="common_cbo"><input type="checkbox" name="save_mobile" value="1" />记住手机号</label>
				</dd>
			</dl>
			<dl>
				<dt></dt>
				<dd>
					<button class="ui-button f_l" rel="orange" type="submit">登录</button>					
				</dd>
			</dl>
		</form>
		</div>
	</div>
	<?php if ($this->_var['user_info']): ?>	
	<div class="cop_site">
	<div class="confirm_login_tip">
	 <?php if ($this->_var['user_info']['is_tmp'] == 1): ?>
	 <a href="<?php
echo parse_url_tag("u:index|uc_account|"."".""); 
?>">请先去完善您的个人资料</a>
	 <?php else: ?>
	 为保证账号安全，请输入密码再次确认您的账号身份
	 <?php endif; ?>
	</div>
	</div>
	<?php else: ?>
	<!-- <div class="cop_site">		
		<div class="wx_login" >
			<div id="login_box_css" style="width:154px; height:50px;">
				<a href="javascript:void(0);" class="iconfont" id="wx_login" rel="<?php
echo parse_url_tag("u:index|user#wx_login|"."".""); 
?>">&#xe632;</a>
	
				<?php if ($this->_var['WB_APP_KEY'] != '' && $this->_var['WB_APP_SECRET'] != ''): ?>
				 <a class="iconfont" id="wb_login" href="<?php
echo parse_url_tag("u:index|user#wb_login|"."".""); 
?>">&#xe633;</a>
				<?php endif; ?>
	
				<?php if ($this->_var['QQ_HL_APPID'] != '' && $this->_var['QQ_HL_APPKEY'] != ''): ?>
				<a class="iconfont" id="qq_login" href="<?php
echo parse_url_tag("u:index|user#qq_login|"."".""); 
?>">&#xe64a;</a>
				<?php endif; ?>
			</div>
		</div>
	</div> --><!--合作站点-->
	<?php endif; ?>
</div>
<script>
$(document).ready(function(){
	// 设置登录按钮宽度
	var al = $('#login_box_css').find('a').length;
	$('#login_box_css').css('width', 50*al);
});

</script>
