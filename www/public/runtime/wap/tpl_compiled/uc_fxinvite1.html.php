<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_fxinvite.css";
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


?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>

<style>
	.fx_table{
		width:100%;
	}
	.fx_user{
		width: 90%;
		margin-left: 5%;
		text-align: center;
	}
</style>
<div class="wrap">
	<div class="content">
		<ul class="invit-info" style="height:4.9em;">
			<li>
				<div class="info-border-box">
					<h1>今日获得夺宝币</h1>
					<p class="num-info">
						<?php echo $this->_var['data']['today_total_brokerage_money']; ?>
					</p>
				</div>
				<!-- 				<p class="other-info">
                    我的<?php echo $this->_var['data']['fx_name']; ?>创收：<?php echo $this->_var['data']['today_first_money']; ?>
                </p> -->
			</li>
			<li>
				<h1>邀请好友累计收入</h1>
				<p class="num-info">
					<?php echo $this->_var['data']['total_brokerage_money']; ?>
				</p>
				<!-- 				<p class="other-info">
                    我<?php echo $this->_var['data']['fx_name']; ?>的<?php echo $this->_var['data']['fx_name']; ?>创收：<?php echo $this->_var['data']['today_second_money']; ?>
                </p> -->
			</li>
		</ul>
		<?php if ($this->_var['dealers'] == 2): ?>

		<?php if ($this->_var['data']['first_user_count']): ?>
		<div class="farmer-info split-line split-line-top">
			<div class="fx_count">
				<i class="iconfont main-icon" style="float: left;color:#ff7849;font-size:20px;margin-left:5%;margin-right:2%;;">&#xe6ec;</i>
				<p class="myfarmer">一级邀请用户 <span style="color:#ff296d;"><?php echo $this->_var['data']['first_user_count']; ?>人</span> &nbsp;<span>今日充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['first_money']): ?><?php echo $this->_var['data']['first_money']; ?><?php else: ?>0<?php endif; ?></i></span>  <span>累计充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['first_total_money']): ?><?php echo $this->_var['data']['first_total_money']; ?><?php else: ?>0<?php endif; ?></i></span></p>
			</div>
			<div class="fx_user">
				<table class="fx_table" border="1" cellspacing="0" cellpadding="0">
					<tr>
						<th>会员名</th>
						<th>邀请人</th>
						<th>今日充值</th>
						<th>累计充值</th>
					</tr>
					<?php $_from = $this->_var['data']['first_fx_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'u');if (count($_from)):
    foreach ($_from AS $this->_var['u']):
?>
					<tr>
						<td><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."lucky_user_id=".$this->_var['u']['id']."".""); 
?>"><?php echo $this->_var['u']['user_name']; ?></a></td>
						<td><?php echo $this->_var['u']['pid']; ?></td>
						<td><?php echo $this->_var['u']['today_amount']; ?></td>
						<td><?php echo $this->_var['u']['amount_money']; ?></td>
					</tr>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_var['data']['second_user_count']): ?>
		<div class="farmer-info split-line split-line-top">
			<div class="fx_count">
				<i class="iconfont main-icon" style="float: left;color:#ff7849;font-size:20px;margin-left:5%;margin-right:2%;;">&#xe702;</i>
				<p class="myfarmer">二级邀请用户 <span style="color:#ff296d;"><?php echo $this->_var['data']['second_user_count']; ?>人</span> &nbsp;<span>今日充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['second_money']): ?><?php echo $this->_var['data']['second_money']; ?><?php else: ?>0<?php endif; ?></i></span>  <span>累计充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['second_total_money']): ?><?php echo $this->_var['data']['second_total_money']; ?><?php else: ?>0<?php endif; ?></i></span></p>
			</div>
			<div class="fx_user">
				<table class="fx_table" border="1" cellspacing="0" cellpadding="0">
					<tr>
						<th>会员名</th>
						<th>邀请人</th>
						<th>今日充值</th>
						<th>累计充值</th>
					</tr>
					<?php $_from = $this->_var['data']['second_fx_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'u');if (count($_from)):
    foreach ($_from AS $this->_var['u']):
?>
					<tr>
						<td><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."lucky_user_id=".$this->_var['u']['id']."".""); 
?>"><?php echo $this->_var['u']['user_name']; ?></a></td>
						<td><?php echo $this->_var['u']['pid']; ?></td>
						<td><?php echo $this->_var['u']['today_amount']; ?></td>
						<td><?php echo $this->_var['u']['amount_money']; ?></td>
					</tr>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_var['data']['three_user_count']): ?>
		<div class="farmer-info split-line split-line-top">
			<div class="fx_count">
				<i class="iconfont main-icon" style="float: left;color:#ff7849;font-size:20px;margin-left:5%;margin-right:2%;;">&#xe702;</i>
				<p class="myfarmer">三级邀请用户 <span style="color:#ff296d;"><?php echo $this->_var['data']['three_user_count']; ?>人</span> &nbsp;<span>今日充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['third_money']): ?><?php echo $this->_var['data']['third_money']; ?><?php else: ?>0<?php endif; ?></i></span>  <span>累计充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['third_total_money']): ?><?php echo $this->_var['data']['third_total_money']; ?><?php else: ?>0<?php endif; ?></i></span></p>
			</div>
			<div class="fx_user">
				<table class="fx_table" border="1" cellspacing="0" cellpadding="0">
					<tr>
						<th>会员名</th>
						<th>邀请人</th>
						<th>今日充值</th>
						<th>累计充值</th>
					</tr>
					<?php $_from = $this->_var['data']['three_fx_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'u');if (count($_from)):
    foreach ($_from AS $this->_var['u']):
?>
					<tr>
						<td><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."lucky_user_id=".$this->_var['u']['id']."".""); 
?>"><?php echo $this->_var['u']['user_name']; ?></a></td>
						<td><?php echo $this->_var['u']['pid']; ?></td>
						<td><?php echo $this->_var['u']['today_amount']; ?></td>
						<td><?php echo $this->_var['u']['amount_money']; ?></td>
					</tr>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_var['data']['four_fx']): ?>
		<div class="farmer-info split-line split-line-top">
			<div class="fx_count">
				<i class="iconfont main-icon" style="float: left;color:#ff7849;font-size:20px;margin-left:5%;margin-right:2%;;">&#xe702;</i>
				<p class="myfarmer">四级邀请用户 <span style="color:#ff296d;"><?php echo $this->_var['data']['four_fx']['user_count']; ?>人</span> &nbsp;<span>今日充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['four_fx']['today_money']): ?><?php echo $this->_var['data']['four_fx']['today_money']; ?><?php else: ?>0<?php endif; ?></i></span>  <span>累计充值：<i style="color:#ff296d;"><?php if ($this->_var['data']['four_fx']['total_money']): ?><?php echo $this->_var['data']['four_fx']['total_money']; ?><?php else: ?>0<?php endif; ?></i></span></p>
			</div>
		</div>
		<?php endif; ?>

		<div class="rule split-line-top split-line">
			<a class="split-line title-bar">
				<i class="iconfont main-icon">&#xe705;</i>

				<p class="myfarmer">邀请推广奖规则  </p>
			</a>
			<div class="rule-info">
				<p class="rule-title">一级邀请用户：</p>
				<p>指我直接邀请的用户。</p>
				<p class="rule-title">二级邀请用户：</p>
				<p>指我的一级邀请用户邀请的用户。</p>
				<p class="rule-title">三级邀请用户：</p>
				<p>指我的二级邀请用户邀请的用户。</p>
				<!--<p class="rule-title last-rule-title">-->
				<!--比如，我邀请A，A邀请B,B邀请C，C邀请D。A是我的一级邀请用户，B是我的二级邀请用户，C是我的三级邀请用户。<br />-->
				<!--如果A消费100夺宝币，我将获得3%的推广奖即3夺宝币。<br />-->
				<!--如果B消费100夺宝币，A将获得3%的推广奖即3夺宝币，我将获得2%的推广奖即2夺宝币。<br />-->
				<!--如果C消费100夺宝币，B将获得3%的推广奖即3夺宝币，A将获得2%的推广奖即2夺宝币，我将获得1%的推广奖即1夺宝币。<br />-->
				<!--如果D消费100夺宝币，C将获得3%的推广奖即3夺宝币，B将获得2%的推广奖即2夺宝币，A将获得1%的推广奖即1夺宝币，我不获得推广奖。<br />-->
				<!--</p>-->
			</div>
		</div>
		<div class="htg-farmer split-line-top">
			<!--<a class="split-line title-bar">-->
			<!--<i class="iconfont main-icon">&#xe706;</i>-->
			<!--<p class="myfarmer">如何获得邀请用户</p>-->
			<!--</a>-->
			<div class="htg-farmer-info">
				<!--<p>-->
				<!--在任意页面点击屏幕右上角，点击“发送给朋友”或“分享到朋友圈”即可。想要获取更多用户，就要经常“发送给朋友”或“分享到朋友圈”，然后给朋友留言，让朋友一起来玩！-->
				<!--</p>-->
				<!--<div class="img-info">-->
				<!--<div class="imgbox imgbox-1"></div>-->
				<!--<div class="imgbox imgbox-2"></div>-->
				<!--</div>-->
			</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_var['dealers'] == 0): ?>
		<div style="width:80%;background:#C1C3DE;text-align:center;border-radius:10px;margin:40px; auto;height:200px;">
			<span style="line-height:80px;font-size:18px;">在消费<?php echo $this->_var['need_money']; ?>元就可成为经销商啦！！</span>
			<ul style="color:#D84450;margin-top:10px;">
				<li>成为经销商坐享推广奖啦！</li>
				<li>成为经销商坐享推广奖啦！</li>
				<li>成为经销商坐享推广奖啦！</li>
			</ul>
		</div>
		<?php endif; ?>
		<?php if ($this->_var['dealers'] == 1): ?>
		<div style="width:80%;background:#C1C3DE;text-align:center;border-radius:10px;margin:40px; auto;height:200px;">
			<span style="font-size:18px;line-height:80px;">恭喜，您已经可以成为经销商啦！</span><br>
			<button style="width:35%;height:40px;border-radius:5px;text-align:center;line-height:40px;font-size:18px;border:none;margin-top:-20px;background:#D84450;"><a href="<?php
echo parse_url_tag("u:index|uc_fxinvite#to_be_dealers|"."".""); 
?>" style="color:#fff;">成为经销商</a></button>
			<ul style="color:#D84450;margin-top:10px;">
				<li>成为经销商坐享推广奖啦！</li>
				<li>成为经销商坐享推广奖啦！</li>
				<li>成为经销商坐享推广奖啦！</li>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php echo $this->fetch('inc/footer_index.html'); ?>