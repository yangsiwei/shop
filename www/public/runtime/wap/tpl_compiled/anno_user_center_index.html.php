<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/anno_user_center.css";

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
<header class="uc-info-head">
    <div class="head-pic fl">
    <img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['user_info']['id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>"  width="100%" height="100%">
    </div>
    <div class="user-box fl">
	    <p class="user-name"><?php echo $this->_var['user_info']['user_name']; ?></p>
	    <p class="user-id">ID:<span><?php echo $this->_var['user_info']['id']; ?></span></p>
    </div>
</header>
<div class="slider-nav split-line">
  <ul>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."lucky_user_id=".$this->_var['user_info']['id']."".""); 
?>" <?php if ($this->_var['log_type'] == 0): ?>class="cur"<?php endif; ?>>夺宝记录</a></li>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."log_type=1&lucky_user_id=".$this->_var['user_info']['id']."".""); 
?>" <?php if ($this->_var['log_type'] == 1): ?>class="cur"<?php endif; ?>>幸运记录</a></li>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|anno_user_center#index|"."log_type=2&lucky_user_id=".$this->_var['user_info']['id']."".""); 
?>"  <?php if ($this->_var['log_type'] == 2): ?>class="cur"<?php endif; ?>>充值记录</a></li>
  </ul>
</div>

<?php if ($this->_var['list'] && $this->_var['log_type'] != 2): ?>
	<div class="m-content">
		<ul class="scroll_bottom_list">
			<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
			<li class="dblist <?php if ($this->_var['item']['success_time'] == 0): ?>inprogress<?php else: ?>finish<?php endif; ?>">
				<div class="dblistimg">
					<?php if ($this->_var['item']['min_buy'] == 10): ?>
						<div class="tenyen"></div>
					<?php endif; ?>
					<?php if ($this->_var['item']['min_buy'] == 100): ?>
						<div class="hundred"></div>
					<?php endif; ?>
					<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>"><img alt="" src="<?php echo $this->_var['item']['icon']; ?>"/></a>
				</div>
				<div class="dblistmain">
					<a class="tit" href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>"><?php if ($this->_var['item']['is_topspeed']): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                    <?php endif; ?>
                    <?php if ($this->_var['item']['is_number_choose'] == 1): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">选号</em>
                    <?php endif; ?>
                    <?php if ($this->_var['item']['is_pk'] == 1): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">PK</em>
                    <?php endif; ?><?php echo $this->_var['item']['name']; ?></a>
					<div class="prl">
						<div class="lable">期号&nbsp;:&nbsp;<?php echo $this->_var['item']['id']; ?></div>
						<div class="progressBar">
		                    <span class="bar" style="width:<?php echo $this->_var['item']['progress']; ?>%"><i class="color"></i></span>
		                </div>
						<div class="lable fl">总需&nbsp;:&nbsp;<?php echo $this->_var['item']['max_buy']; ?>人次</div>
						<div class="lable fl surplus">剩余&nbsp;:&nbsp;<em><?php echo $this->_var['item']['less']; ?></em>人次</div>
							<a class="tacked" href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">跟买</a>
						</div>
					<?php if ($this->_var['item']['has_lottery'] == 1 && $this->_var['log_type'] == 0): ?>
					<div class="prl">
						<div class="partake fl">本期参与&nbsp;:&nbsp;<em><?php echo $this->_var['item']['number']; ?></em>人次</div>
						<a class="lookno fr" href="<?php
echo parse_url_tag("u:index|anno_user_center#my_no|"."id=".$this->_var['item']['id']."&uid=".$this->_var['user_info']['id']."".""); 
?>">查看夺宝号码</a>
					</div>
					<div class="announce">
						<div class="mline" style="width: 264px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">获得者&nbsp;:&nbsp;<span><a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['item']['luck_user_id']."".""); 
?>"><?php echo $this->_var['item']['luck_user_name']; ?></a></span> </div>
						<div class="mline">本期参与&nbsp;:&nbsp;<em><?php echo $this->_var['item']['number']; ?></em>人次</div>
						<div class="mline" style="width: 264px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">幸运号码&nbsp;:&nbsp;<em><?php echo $this->_var['item']['lottery_sn']; ?></em> </div>
						<div class="mline">揭晓时间&nbsp;:&nbsp;<?php echo $this->_var['item']['lottery_time']; ?></div>
					</div>
					<?php elseif ($this->_var['log_type'] == 1): ?>
					<div class="prl">
						<div class="partake fl">本期参与&nbsp;:&nbsp;<em><?php echo $this->_var['item']['number']; ?></em>人次</div>
					</div>
					<div class="mline">幸运号码&nbsp;:&nbsp;<em><?php echo $this->_var['item']['lottery_sn']; ?></em></div>
					<div class="mline">揭晓时间&nbsp;:&nbsp;<?php echo $this->_var['item']['lottery_time']; ?></div>
					<?php else: ?>
						<div class="announce">
							<div class="mline">等待开奖</div>
						</div>
					<?php endif; ?>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
<?php elseif (empty ( $this->_var['list'] ) && $this->_var['log_type'] != 2): ?>
	<div class="content">
	<div class="null_data">
		<p class="icon"><i class="iconfont">&#xe6e8;</i></p>
		<p class="message">暂无数据</p>
	</div>
</div>
<?php endif; ?>

<?php if ($this->_var['charge_log'] && $this->_var['log_type'] == 2): ?><!-- 他人的夺宝中心，只显示审核通过的晒单 -->
	<div class="m-content">
		<table style="width:90%;margin:15px auto;font-size:14px;text-align:left;line-height:25px;">
			<tr style="background:#cbcce0;">
				<th style="width:45%;">充值订单号</th>
				<th style="width:30%;">充值时间</th>
				<th style="width:25%;">充值金额</th>
			</tr>
			<?php $_from = $this->_var['charge_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ch');if (count($_from)):
    foreach ($_from AS $this->_var['ch']):
?>
				<tr style="border-top:5px solid #fff;background:#eae7e6;">
					<td><?php echo $this->_var['ch']['order_sn']; ?></td>
					<td><?php echo $this->_var['ch']['create_time']; ?></td>
					<td> <span style="color:#eb606d;"><?php echo $this->_var['ch']['pay_amount']; ?></span> 夺宝币</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</table>
		<!--<ul class="share-list scroll_bottom_list">-->
			<!--<?php $_from = $this->_var['share_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ome');if (count($_from)):
    foreach ($_from AS $this->_var['ome']):
?>-->
			<!--<li class="item">-->
				<!--<div class="clear"></div>-->
				<!--<a href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['ome']['share_id']."".""); 
?>" class="share-info">-->
					<!--<div class="content">-->
						<!--<h2 class="share-title"><?php echo $this->_var['ome']['title']; ?></h2>-->
						<!--<p class="goods-name"><?php echo $this->_var['ome']['name']; ?></p>-->
						<!--<p class="code">期号：<?php echo $this->_var['ome']['duobao_item_id']; ?></p>-->
						<!--<p class="share-time">晒单时间：<?php echo $this->_var['ome']['create_time']; ?></p>-->
						<!--<p class="share-txt"><?php echo $this->_var['ome']['content']; ?></p>-->
						<!--<?php if ($this->_var['ome']['image_list']): ?>-->
						<!--<ul class="share-pic clearfix">-->
							<!--<?php $_from = $this->_var['ome']['image_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ome');if (count($_from)):
    foreach ($_from AS $this->_var['ome']):
?>-->
							<!--<li>-->
								<!--<img src="<?php echo $this->_var['ome']['path']; ?>">-->
							<!--</li>-->
							<!--<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
						<!--</ul>-->
						<!--<?php endif; ?>-->
					<!--</div>-->
				<!--</a>-->
			<!--</li>-->
			<!--<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
		<!--</ul>-->
	</div>
<?php elseif (empty ( $this->_var['charge_log'] ) && $this->_var['log_type'] == 2): ?>
<div class="content">
	<div class="null_data">
		<p class="icon"><i class="iconfont">&#xe6e8;</i></p>
		<p class="message">暂无数据</p>
	</div>
</div>
<?php endif; ?>

<?php if ($this->_var['pages']): ?>
	<div class="fy scroll_bottom_page">
		<?php echo $this->_var['pages']; ?>
	</div>
<?php endif; ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
