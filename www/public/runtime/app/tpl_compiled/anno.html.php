<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/goods_item.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/anno.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.SuperSlide.2.1.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/lazyload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/anno.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/anno.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/dc/js/page_js/slider.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/dc/js/page_js/slider.js";

?>
<?php echo $this->fetch('inc/header.html'); ?>
<div class="wrap_full_w clearfix">
	<?php if ($this->_var['lastet_list']): ?>
		<div class="result-goods-list f_l">
			<h1 class="main-title">最近三天揭晓的所有商品</h1>
			<ul class="ui-list" width="296" pin_col_init_width="0" wSpan="11" hSpan="12">
			<?php $_from = $this->_var['lastet_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lastet_duobao');if (count($_from)):
    foreach ($_from AS $this->_var['lastet_duobao']):
?>
				<li class="result-goods ui-item">
					<div class="ten-logo-box"></div>
					<div class="goods-info">
					<div class="imgbox">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['lastet_duobao']['id']."".""); 
?>">
							<img src="<?php echo $this->_var['lastet_duobao']['icon']; ?>" lazy="true" width="200" height="200" />
						</a>
					</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['lastet_duobao']['id']."".""); 
?>" class="goods-title">
                        <?php if ($this->_var['lastet_duobao']['is_topspeed'] == 1): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                        <?php endif; ?>
                        <?php if ($this->_var['lastet_duobao']['is_number_choose'] == 1): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">选号</em>
                        <?php elseif ($this->_var['lastet_duobao']['is_pk'] == 1): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">PK</em>
                        <?php endif; ?><?php echo $this->_var['lastet_duobao']['name']; ?>
						</a>
						<p class="p-price">
							总需：<?php echo $this->_var['lastet_duobao']['max_buy']; ?>人次
						</p>
						<p class="code">
							期号：<?php echo $this->_var['lastet_duobao']['id']; ?>
						</p>
					</div>
					<!-- 揭晓结果 -->
					<div class="record" >
					<?php if (! $this->_var['lastet_duobao']['luck_user_id']): ?>
					<!-- 倒计时 -->
					<div class="countdown">
						<div class="countdown-title">
							<i class="ico countdown-ico countdown-ico-gray"></i>
							揭晓倒计时
						</div>
						<time class="countdown-nums" nowtime="<?php echo $this->_var['now_time']; ?>" endtime="<?php echo $this->_var['lastet_duobao']['lottery_time']; ?>" id="<?php echo $this->_var['lastet_duobao']['id']; ?>">
							<b>0</b><b>0</b>:<b>0</b><b>0</b>:<b>0</b><b>0</b>
						</time>
					</div>
					<?php else: ?>
					<!-- 用户头像 -->
						<div class="user-pic f_l">
							<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['lastet_duobao']['luck_user_id']."".""); 
?>" target="_blank">
								<img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['lastet_duobao']['luck_user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>" width="40" height="40">
							</a>
						</div>
					<!-- 中奖信息 -->
						<div class="record-info">
							<p class="owner-info">
								恭喜<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['lastet_duobao']['luck_user_id']."".""); 
?>" target="_blank"><?php echo $this->_var['lastet_duobao']['luck_user_name']; ?></a>获得该商品
							</p>
							<p>幸运号码：<b class="txt-red"><?php echo $this->_var['lastet_duobao']['lottery_sn']; ?></b></p>
							<p>本期参与：<b class="txt-red"><?php echo $this->_var['lastet_duobao']['luck_user_buy_count']; ?></b>人次</p>
							<p>揭晓时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['lastet_duobao']['lottery_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?></p>
							<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['lastet_duobao']['id']."".""); 
?>" class="btn check-info" target="_blank">查看详情</a>
						</div>
					<?php endif; ?>
					</div>
				</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
			<div class="pages"><?php echo $this->_var['pages']; ?></div>
		</div>
	<?php else: ?>
	<div class="null-data f_l">
		<p class="txt">暂时还没有最新揭晓哦~</p>
		<div class="blank20"></div>
	</div>
	<?php endif; ?>
	<div class="result-lottery-list f_r">
		<div class="result-lottery">
			<div class="title">
				<h1>最快揭晓</h1>
			</div>
			<div class="ico result-bolan"></div>
			<ul class="new-result-list">
			<?php $_from = $this->_var['unopen_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'unopen_duobao');if (count($_from)):
    foreach ($_from AS $this->_var['unopen_duobao']):
?>
				<li class="goods">
				<div class="ten-logo-box"></div>
					<div class="goods-wrap">
					<div class="imgbox">
						<a href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['unopen_duobao']['id']."".""); 
?>' title="<?php echo $this->_var['unopen_duobao']['duobaoitem_name']; ?>">
							<img src="<?php echo $this->_var['unopen_duobao']['icon']; ?>" lazy="true" width="200" height="200" />
						</a>
					</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['unopen_duobao']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['unopen_duobao']['duobaoitem_name']; ?>">
                        <?php if ($this->_var['unopen_duobao']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速专区</em>
                        <?php endif; ?><?php echo $this->_var['unopen_duobao']['duobaoitem_name']; ?>
						</a>
						<div class="p-price f_l">
							总需：<?php echo $this->_var['unopen_duobao']['max_buy']; ?>人次
						</div>
						<div class="clear"></div>
						<!-- 进度条 -->
						<div class="progressBar">
						<!-- 进度条外层 -->
							<div class="progress" title="<?php echo $this->_var['unopen_duobao']['progress']; ?>%">
							<!-- 进度条内层 -->
								<div class="progress-bar" style="width:<?php echo $this->_var['unopen_duobao']['progress']; ?>%"></div>
							</div>
						</div>
						<ul class="person-info">
							<li class="f_l">
								<p class="num"><?php echo $this->_var['unopen_duobao']['current_buy']; ?></p>
								<p class="info">已参与人次</p>
							</li>
							<li class="f_r tar">
								<p class="num"><?php echo $this->_var['unopen_duobao']['less_buy']; ?></p>
								<p class="info">剩余人次</p>
							</li>
							<div class="clear"></div>
						</ul>
						<div class="btn-box">
							<a class="btn finished" buy_num="<?php echo $this->_var['unopen_duobao']['less_buy']; ?>" data_id="<?php echo $this->_var['unopen_duobao']['id']; ?>" onclick="add_cart(this,1)">我来包尾</a>
						</div>
					</div>
				</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	</div>
</div>


<?php echo $this->fetch('inc/footer.html'); ?>