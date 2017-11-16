<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_account.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/plupload.full.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_account.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_account.js";
?>
<?php echo $this->fetch('inc/header.html'); ?>

<script type="text/javascript" src="<?php echo $this->_var['APP_ROOT']; ?>/system/region.js"></script>	
<script>
var UPLOAD_URL = '<?php
echo parse_url_tag("u:index|file#upload_avatar|"."".""); 
?>';
</script>
<div class="blank20"></div>

<div class="<?php 
$k = array (
  'name' => 'load_wrap',
  't' => $this->_var['wrap_type'],
);
echo $k['name']($k['t']);
?> clearfix">
	<div class="side_nav f_l ">
		<?php echo $this->fetch('inc/uc_nav_list.html'); ?>
	</div>
	<div class="right_box">
		<ul class="web-map clearfix">
			<li>当前位置：</li>
			<li><a href="<?php
echo parse_url_tag("u:index|uc_center|"."".""); 
?>">个人中心</a> ></li>
			<li class="txt-red">账户设置</li>
		</ul>
		<div class="setting_user_info">
			
			<form name="setting_user_info" action="<?php
echo parse_url_tag("u:index|uc_account#save|"."".""); 
?>" method="post" bindsubmit="true">
			<div class="content">

				<?php if ($this->_var['user_info']['is_tmp'] == 1): ?>
				<div class="confirm_login_tip">
				为确保账户安全，请完善会员资料以及会员密码
				</div>
				<?php endif; ?>
				<div class="blank20"></div>
				<div class="content_item clearfix">
					
					<div class="upimg_box clearfix">

						<h3>没头像，没礼貌</h3>
						<div class="avatar_box">
							<img class="avatar" src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['user_info']['id'],
  'type' => 'big',
);
echo $k['name']($k['uid'],$k['type']);
?>"/>
						</div>
						<div class="loading hide"></div>
						<div class="up_btn"><button class="ui-button upload_avatar_btn" rel="light" id="upload_avatar_btn" type="button">选择文件</button></div>
						<div class="img_tip">支持<?php 
$k = array (
  'name' => 'app_conf',
  'p' => 'ALLOW_IMAGE_EXT',
);
echo $k['name']($k['p']);
?></div>
						<div class="blank0"></div>
					</div>
					<div class="info_box">
						<div class="blank20"></div>
						<div class="field_group clearfix">
							<label class="f_label">Email</label>
							<div class="f_text">
								<?php if ($this->_var['user_info']['email']): ?>
									<input type="text" id="settings_email" name="email" value="<?php echo $this->_var['user_info']['email']; ?>"  holder="" readonly="readonly"/>
								<?php else: ?>
									<input type="text" id="settings_email" name="email" value="<?php echo $this->_var['user_info']['email']; ?>" class="ui-textbox " holder=""/>
								<?php endif; ?>
							</div>
						</div>
						<div class="field_group clearfix">
							<label class="f_label">用户名</label>
							<div class="f_text">
								<?php if ($this->_var['user_info']['is_tmp']): ?>
									<input type="text" id="settings_user_name" name="user_name" value="<?php echo $this->_var['user_info']['user_name']; ?>" class="ui-textbox " holder=""/>
								<?php else: ?>
									<input type="text" id="settings_user_name" name="user_name" value="<?php echo $this->_var['user_info']['user_name']; ?>"  holder=""  />
								<?php endif; ?>
							</div>
						</div>
						<div class="field_group clearfix">
							<label class="f_label">当前密码</label>
							<div class="f_text">
								<input type="password" id="current_password" name="current_password" class="ui-textbox" holder="请输入当前密码"/>
							</div>
						</div>
						<div class="field_group clearfix">
							<label class="f_label">新密码</label>
							<div class="f_text">
								<input type="password" id="settings_password" name="user_pwd" class="ui-textbox" holder="如果不想修改密码，请保持空白"/>
							</div>
						</div>
						<div class="field_group clearfix">
							<label class="f_label">确认密码</label>
							<div class="f_text">
								<input type="password" id="settings_password_confirm" name="user_pwd_confirm" class="ui-textbox" holder=""/>
							</div>
						</div>	
						<div class="field_group clearfix">
							<label class="f_label">手机号</label>
							<div class="f_text">
								<input type="text" id="settings_mobile" name="mobile" value="<?php echo $this->_var['user_info']['mobile']; ?>" data="<?php echo $this->_var['user_info']['mobile']; ?>" class="ui-textbox f_text" holder=""/>
							</div>
							<input type="hidden" class="is_check_mobile" name="is_check_mobile" value="0"/>
						</div>	
						<!--短信验证手机号-->
						<?php if (app_conf ( "SMS_ON" ) == 1): ?>
						<div class="field_group clearfix ph_sms_verify">
							<label class="f_label">验证手机号</label> 
							<div class="sms_verify_box">
								<div class="f_text">
									<input class="ui-textbox f_l ph_verify" id="sms_verify" name="sms_verify" holder="请输入验证码" />
								</div>
								<button class="ui-button f_l light ph_verify_btn" rel="light" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" type="button">发送验证码</button>
							</div>
							<div class="status_icon hide"> <i class=""></i></div>
							<div class="clear"></div>
						</div>
						<div class="ph_img_verify field_group clearfix ph_sms_verify" <?php if ($this->_var['sms_ipcount'] > 1): ?>style="display:block"<?php endif; ?>>
							<label class="f_label">图形验证码</label> 
							<div class="sms_verify_box">
								<div class="f_text">
									<input type="text" name="verify_code" class="ui-textbox img_verify f_l" style="width:150px;" holder="请输入图片文字" />
									<img src="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" class="verify f_l" style="padding:8px 0 0 5px; cursor:pointer;" rel="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" />
									<a href="javascript:void(0);" class="refresh_verify f_l" style="padding:10px 0 0 5px;">看不清楚？换一张！</a>
								</div>
								
							</div>
							<div class="status_icon hide"> <i class=""></i></div>
							<div class="clear"></div>
						</div>
						<?php endif; ?>
						<div class="content_item clearfix" style="border:none;">
							<div class="blank20"></div>
							<div class="field_group clearfix" style="margin-left:120px">
								<input type="hidden" value="<?php echo $this->_var['user_info']['id']; ?>" name="id" />
								<input type="hidden" name="is_ajax" value="1" />
								<button class="ui-button " rel="orange">保存修改</button>
							</div>
					</div>
					</div>
				
				
					
				</div>
			</div>
			
			<div class="blank20"></div>
			

			</form>

			<div class="blank20"></div>
		</div>
	</div>	
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>