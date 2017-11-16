<?php if ($this->_var['user_info']): ?>
	<span class="user_name">
		欢迎您，<a href="<?php
echo parse_url_tag("u:index|uc_center|"."".""); 
?>" ><?php echo $this->_var['user_info']['user_name']; ?></a>
		<?php if ($this->_var['user_info']['level'] > 0): ?>
		<span title="<?php echo $this->_var['user_info']['level_name']; ?>" class="level_bg level_<?php echo $this->_var['user_info']['level']; ?>"></span>
		<?php endif; ?>
		[ <a href="<?php
echo parse_url_tag("u:index|user#loginout|"."".""); 
?>">退出</a> ]
	</span>
	<?php if ($this->_var['user_info']['msg_count'] > 0): ?>
	<a href="<?php
echo parse_url_tag("u:index|uc_msg|"."".""); 
?>" class="msg_count" title="您共有<?php echo $this->_var['user_info']['msg_count']; ?>条新信息"><span><i class="iconfont">&#xe62c;</i> 消息 <em><?php echo $this->_var['user_info']['msg_count']; ?></em></span></a>
	<?php endif; ?>
	<script type="text/javascript">
			init_drop_user();
			<?php if ($this->_var['signin_result']): ?>
			show_signin_message(<?php echo $this->_var['signin_result']; ?>);
			<?php endif; ?>
	</script>
<?php else: ?>
	<span class="login_tip">请先 [<a href="<?php
echo parse_url_tag("u:index|user#login|"."".""); 
?>" title="登录" id="pop_login">登录</a>]<?php if ($this->_var['wx_login']): ?> / [<a href="javascript:void(0);" rel="<?php
echo parse_url_tag("u:index|user#wx_login|"."".""); 
?>" title="微信登录" id="wx_login">微信登录</a>]<?php endif; ?> 或 [<a href="<?php
echo parse_url_tag("u:index|user#register|"."".""); 
?>" title="注册">注册</a>]</span>
<?php endif; ?>
<span id="user_drop">
	<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."".""); 
?>">我的夺宝</a>
	<i class="iconfont">&#xe610;</i>
</span>
<div id="user_drop_box">
	<dl>
		<dd><a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."".""); 
?>">夺宝记录</a></dd>
		<dd class="group"><a href="<?php
echo parse_url_tag("u:index|uc_luck|"."".""); 
?>">幸运记录</a></dd>
		<dd><a href="<?php
echo parse_url_tag("u:index|uc_share|"."".""); 
?>">我的晒单</a></dd>
		<dd><a href="<?php
echo parse_url_tag("u:index|uc_money|"."".""); 
?>">帐户充值</a></dd>
	</dl>
</div>
 <a href="<?php
echo parse_url_tag("u:index|help|"."".""); 
?>">帮助</a>