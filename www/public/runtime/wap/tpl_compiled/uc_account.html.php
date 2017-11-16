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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_account.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_account.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>

<div class="wrap">

	<div class="content">
	<div class="comment_list_txt1">

		 <div class="Contentbox">

		   <div class="hover">

			<?php if ($this->_var['user_info']['is_tmp'] == 1): ?>
			<form action="<?php
echo parse_url_tag("u:index|uc_account#save|"."".""); 
?>" name="account_form">
					<section class="login-normal con-1">
						<ul class="input-list split-line-top split-line">
							<li>
								<i class="iconfont">&#xe6f1;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="text" placeholder="请输入用户名,仅能修改一次" name="user_name" value="<?php echo $this->_var['user_info']['user_name']; ?>">
								</label>
								</div>
							</li>
							<li>
								<i class="iconfont">&#xe69e;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="text" placeholder="请输入邮箱地址" name="user_email" value="<?php echo $this->_var['user_info']['email']; ?>">
								</label>
								</div>
							</li>
                                                        <?php if ($this->_var['user_info']['is_phone_register'] == 1): ?>
                                                        <?php else: ?>
							<li>
								<i class="iconfont">&#xe6ef;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="password"  placeholder="请设置登录密码" name="user_pwd">
								</label>
								</div>
							</li>
							<li>
								<i class="iconfont">&#xe6ef;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="password"  placeholder="请再次输入登录密码" name="cfm_user_pwd" >
								</label>
								</div>
							</li>
                                                        <?php endif; ?>
						</ul>
							<div class="login-btn-box">
                                                            <input type="hidden" value="<?php echo $this->_var['user_info']['is_phone_register']; ?>" name="is_phone_register" />
                                                            <input type="hidden" value="<?php echo $this->_var['user_info']['is_tmp']; ?>" name="is_tmp" />
							<input type="submit" value="保存" class="login-btn login-btn-red">
							</div>
						</section>
		</form>
			<?php else: ?>
				<div class="list-view">
				      <section class="login-normal con-1">
						<ul class="input-list split-line-top split-line">
				       	    <li>
								<i class="iconfont">&#xe6f1;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="text" placeholder="请输入用户名" name="user_name" value="<?php echo $this->_var['user_info']['user_name']; ?>" readonly="readonly">
								</label>
								</div>
							</li>
							<li>
								<i class="iconfont">&#xe69e;</i>
								<div class="input-box split-line">
								<label class="com-input">
									<input type="text" placeholder="请输入邮箱地址" name="user_email" value="<?php echo $this->_var['user_info']['email']; ?>"  readonly="readonly">
								</label>
								</div>
							</li>
				       </ul>

						</section>
				</div>
			<?php endif; ?>
				<section>
			<div class="a-box">
				<a href="<?php
echo parse_url_tag("u:index|uc_account#phone|"."".""); 
?>">
				<?php if ($this->_var['user_info']['mobile'] == ''): ?>
				绑定手机号
				<?php else: ?>
				更换手机号
				<?php endif; ?>
					</a>
			<div class="clear"></div>
				<!--<div class="dealers" style="width:80%;margin: auto;background:#43D3B6;">-->
					<!--<p style="text-align:center;">你的经销商等级 <span style="color:#DC4962;"><?php echo $this->_var['level']; ?></span></p>-->
				<!--</div>-->
			</div>

	</section>

		   </div>


		 </div>

	 </div>
	</div>
</div>

<?php echo $this->fetch('inc/footer_index.html'); ?>
