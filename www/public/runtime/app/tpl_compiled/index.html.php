<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/goods_item.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/zone.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index_down.css";

$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/bottom.css";

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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/index.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/index.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/index.js";

?>
<?php echo $this->fetch('inc/header.html'); ?>

<?php if ($this->_var['is_send_reg_ecv'] == 1): ?>
		<?php echo $this->fetch('inc/log_ecv.html'); ?>
<?php endif; ?>

<style>
	.drop_title i{display:none;}
</style>
<script>
$(function(){
	var send_money='<?php echo $this->_var['send_money']; ?>';
	if(send_money=='1'){
		setTimeout(function (){
			$(".ecv_log_suc").addClass('active');
		},250);
		setTimeout(function () {
		    $(".ecv_log_suc").removeClass('active');
		},2000);
	}
});
</script>
<!--S 底层悬浮 -->
	<div style="" class="index_float_bottom">
		<div class="index_float_bottoms">
		</div>
		<div class="index_float_bottom_in">
			<div class="index_float_close">
				<a onClick="floatClose()" href="javascript:void(0);"></a>
			</div>
			<div class="index_float_img">
			</div>
			<div class="index_float_wx">
				<p>扫描二维码<br>下载夺宝APP</p>
				<img src="./app/1507341800.png" style="margin-left:10px">
			</div>
		</div>
	</div>
 	<!--E 底层悬浮 -->	
<div class="wrap_full_w clearfix" style="position: relative;z-index: 50;">
	<div class="fix_cate_tree f_l">
		<?php 
$k = array (
  'name' => 'load_cate_tree',
  'c' => '8',
);
echo $k['name']($k['c']);
?>
	</div>
	<div class="left-side f_l">
	
		<div class="index-news">
		<?php if ($this->_var['agreement']['agreement']): ?>
			<h1><?php echo $this->_var['agreement']['agreement_name']; ?></h1>
			<div class="agreement_content">
			<?php echo $this->_var['agreement']['agreement']; ?>
			</div>
			<div class="check-info">
				<a href="<?php
echo parse_url_tag("u:index|news|"."t=notice&id=".$this->_var['agreement']['id']."".""); 
?>">查看详情>></a>
			</div>
		<?php endif; ?>	
		</div>

	</div>
	<div class="main-banner f_l">
		<div id="slideBox" class="slideBox">
			<div class="bd">
				<div class="tempWrap">
					<ul>
						<?php echo show_adv("slide","<li>__ADV_CODE__</li>","730","350");  ?>

					</ul>
				</div>
			</div>
			
			<div class="hd">
				<ul>

				</ul>
			</div>
			<a class="prev iconfont control-btn slideBox-control-btn" href="javascript:void(0)"><i class="ico arrow-large arrow-large-l"></i></a>
			<a class="next iconfont control-btn slideBox-control-btn" href="javascript:void(0)"><i class="ico arrow-large arrow-large-r"></i></a>
		</div>
		<div id="txtScroll-left">
			<div class="txtScroll-left">
			<?php if ($this->_var['lastet_list']): ?>
				<div class="bd">
					<div class="tempWrap">
						<ul class="infoList">
                            <?php $_from = $this->_var['lastet_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lastet_two');if (count($_from)):
    foreach ($_from AS $this->_var['lastet_two']):
?>
                            <?php $_from = $this->_var['lastet_two']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lastet');if (count($_from)):
    foreach ($_from AS $this->_var['lastet']):
?>
							<li>
								<div class="new-goods">
								<!-- 正在揭晓 -->
								    <?php if ($this->_var['lastet']['has_lottery'] == 0): ?>
									<i class="ico new-goods-logo goods-revealing-logo"></i>
								<!-- 最新揭晓 -->
								    <?php else: ?>
									<i class="ico new-goods-logo"></i>
									<?php endif; ?>
								<!-- 商品名称 -->
									<h1 class="new-goods-title">
										<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['lastet']['id']."".""); 
?>" title="<?php echo $this->_var['lastet']['duobaoitem_name']; ?>" target="_blank"><?php echo $this->_var['lastet']['duobaoitem_name']; ?></a>
									</h1>
								<!-- 商品图片 -->
									<div class="new-goods-pic f_r">
										<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['lastet']['icon'],
  'w' => '120',
  'h' => '120',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" width="120" width="120">
									</div>
								<!-- 商品信息 -->
									<p class="goods-price">总需：<?php echo $this->_var['lastet']['max_buy']; ?>人次</p>
									<p class="code">期号：<?php echo $this->_var['lastet']['id']; ?></p>
									
									<div class="show_content">
								<!-- 倒计时 -->
								    <?php if ($this->_var['lastet']['has_lottery'] == 0): ?>
									<div class="goods-counting">
										<div class="goods-countdown">
											揭晓倒计时：
											<div class="countdown">
												<span class="countdown-nums">
													<time class="w-countdown-nums" nowtime="<?php echo $this->_var['now_time']; ?>" endtime="<?php echo $this->_var['lastet']['lottery_time']; ?>" id="<?php echo $this->_var['lastet']['id']; ?>">
													<b>0</b><b>0</b>:<b>0</b><b>0</b>:<b>0</b><b>0</b>
												    </time>

												</span>
											</div>
										</div>
									</div>
									<?php endif; ?>
									<?php if ($this->_var['lastet']['has_lottery'] == 1): ?>
								<!-- 获奖信息 -->
									<div class="goods-record">
										<?php if ($this->_var['duobao']): ?>
										<p class="owner">获得者：<?php echo $this->_var['lastet']['user_name']; ?>...</p>
										<?php else: ?>
										<p class="owner">获得者：<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['lastet']['luck_user_id']."".""); 
?>"><?php echo $this->_var['lastet']['user_name']; ?></a></p>
										<?php endif; ?>
										<p>本期参与：
										<?php if ($this->_var['duobao']): ?><?php echo $this->_var['lastet']['max_buy']; ?><?php else: ?><?php echo $this->_var['lastet']['luck_user_buy_count']; ?>
										<?php endif; ?>人次</p>
										<p style="word-wrap: break-word;word-break: break-all;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">幸运号码：<?php echo $this->_var['lastet']['lottery_sn']; ?></p>
									</div>

								<!-- 错误 -->
									<div class="goods-error">
										<p>
											非常抱歉~
											<br>
											服务器开小差了，请稍后再试...
										</p>
									</div>
									<?php endif; ?>
									</div>
									
								</div>
							</li>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
					</div>
				</div>
				<div class="hd">
					<ul class="num">
						<li></li>
					</ul>
				</div>
				<a class="prev iconfont txtScroll-left-control-btn" href="javascript:void(0)"><i class="ico arrow-large arrow-large-l"></i></a>
				<a class="next iconfont txtScroll-left-control-btn" href="javascript:void(0)"><i class="ico arrow-large arrow-large-r"></i></a>
				<?php endif; ?>			
			</div>
		</div>
	</div>
	<div class="right-side f_r">
	<!-- 推荐夺宝 -->
	    <?php if ($this->_var['recomend_one']): ?>
		<div class="goods-recommend">
			<i class="ico goods-recommend-logo" title="推荐夺宝"></i>
			<div class="recommend">
				<div class="goods-pic">
					<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend_one']['id']."".""); 
?>" title="<?php echo $this->_var['recomend_one']['duobaoitem_name']; ?>">
						<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['recomend_one']['icon'],
  'w' => '180',
  'h' => '180',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" width="180" height="180">
					</a>
				</div>
				<a  class="goods-title" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend_one']['id']."".""); 
?>" title="<?php echo $this->_var['recomend_one']['duobaoitem_name']; ?>" target="_blank"><?php echo $this->_var['recomend_one']['duobaoitem_name']; ?></a>
				<p class="goods-price">总需：<?php echo $this->_var['recomend_one']['max_buy']; ?>人次</p>
				<!-- 进度条 -->
				<div class="progressBar">
				<!-- 进度条外层 -->
					<div class="progress">
					<!-- 进度条内层 -->
						<div class="progress-bar" style="width:<?php echo $this->_var['recomend_one']['progress']; ?>%"></div>
					</div>
					<!-- 进度信息 -->
					<div class="progress-txt">
						已完成<?php echo $this->_var['recomend_one']['progress']; ?>%，剩余<span><?php echo $this->_var['recomend_one']['surplus_buy']; ?></span>
					</div>
				</div>
				<!-- 按钮 -->
				<div class="btn-box">
					<a class="btn duobao-now" buy_num="<?php echo $this->_var['recomend_one']['min_buy']; ?>" data_id="<?php echo $this->_var['recomend_one']['id']; ?>" onclick="add_cart(this,1)">立即夺宝</a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	<!-- 新品推荐 -->
	    <?php if ($this->_var['recomend_list']): ?>
		<div class="new-goods-recommend">
		<!-- 图标 -->
			<i class="ico new-goods-recommend-logo" title="新品推荐"></i>
			<!-- 轮播 -->
			<div class="txtScroll-left-2">
				<div class="bd">
					<div class="tempWrap">
					<ul>
					<?php $_from = $this->_var['recomend_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'recomend');if (count($_from)):
    foreach ($_from AS $this->_var['recomend']):
?>
					<li>
						<div class="goods-pic">
							<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend']['id']."".""); 
?>" target="_blank">
								<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['recomend']['icon'],
  'w' => '120',
  'h' => '120',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" width="120" height="120">
							</a>
						</div>
						<div class="goods-info">
							<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['recomend']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['recomend']['duobaoitem_name']; ?>"><?php echo $this->_var['recomend']['duobaoitem_name']; ?></a>
							<p title="<?php echo $this->_var['recomend']['duobaoitem_brief']; ?>" style="text-align:center;color: #808080;    overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?php echo $this->_var['recomend']['duobaoitem_brief']; ?></p>
						</div>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
					</div>
				</div>
				<a class="prev iconfont txtScroll-left-2-control-btn" href="javascript:void(0)"><i class="ico arrow-small arrow-small-l"></i></a>
				<a class="next iconfont txtScroll-left-2-control-btn" href="javascript:void(0)"><i class="ico arrow-small arrow-small-r"></i></a>
			</div>
		</div>
        <?php endif; ?>
	</div>
</div>
<div class="wrap_full_w clearfix index-goods-list">
	<?php if ($this->_var['hot_duobao_list']): ?>
		<div class="goods-list-wrap f_l">
			<h1 class="main-title">最热商品
				<a href="<?php
echo parse_url_tag("u:index|duobaos|"."r=hot".""); 
?>" class="title-more">更多商品，点击查看>></a>
			</h1>
			<ul class="goods-list ui-list" width="241" pin_col_init_width="0" wSpan="-1" hSpan="1" style="width: 961px">
			<?php $_from = $this->_var['hot_duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hot_duobao');if (count($_from)):
    foreach ($_from AS $this->_var['hot_duobao']):
?>
				<li class="goods ui-item">
				<div class="hover_line">
					<?php if ($this->_var['hot_duobao']['min_buy'] == 10 || $this->_var['hot_duobao']['unit_price'] == 10): ?>
					<div class="ico logo-box ten-logo-box"></div>
					<?php endif; ?>
					<?php if ($this->_var['hot_duobao']['unit_price'] == 100): ?>
					<div class="ico logo-box hundred-logo-box"></div>
					<?php endif; ?>
					<div class="goods-wrap">
					<div class="imgbox">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['hot_duobao']['id']."".""); 
?>" title="<?php echo $this->_var['hot_duobao']['name']; ?>">
							<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['hot_duobao']['icon'],
  'w' => '200',
  'h' => '200',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" lazy="true" width="200" height="200" />
						</a>
					</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['hot_duobao']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['hot_duobao']['name']; ?>">
                        <?php if ($this->_var['hot_duobao']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                        <?php endif; ?><?php echo $this->_var['hot_duobao']['name']; ?>
						</a>
						<div class="p-price f_l">
							总需：<?php echo $this->_var['hot_duobao']['max_buy']; ?>人次

						</div>
						<div class="clear"></div>
						<!-- 进度条 -->
						<div class="progressBar" title="<?php echo $this->_var['hot_duobao']['progress']; ?>%">
						<!-- 进度条外层 -->
							<div class="progress">
							<!-- 进度条内层 -->
								<div class="progress-bar" style="width:<?php echo $this->_var['hot_duobao']['progress']; ?>%"></div>
							</div>
						</div>
						<ul class="person-info">
							<li class="f_l">
								<p class="num"><?php echo $this->_var['hot_duobao']['current_buy']; ?></p>
								<p class="info">已参与人次</p>
							</li>
							<li class="f_r tar">
								<p class="num"><?php echo $this->_var['hot_duobao']['less_buy']; ?></p>
								<p class="info">剩余人次</p>
							</li>
							<div class="clear"></div>
						</ul>
						<div class="btn-box clearfix">
							<a class="duobao-now btn" buy_num="<?php echo $this->_var['hot_duobao']['min_buy']; ?>" data_id="<?php echo $this->_var['hot_duobao']['id']; ?>" title="立即夺宝" onclick="add_cart(this,1)">立即夺宝</a>
							<a class="cart-btn" buy_num="<?php echo $this->_var['hot_duobao']['min_buy']; ?>" data_id="<?php echo $this->_var['hot_duobao']['id']; ?>" title="添加到购物车" onclick="add_cart(this,0)"></a>
						</div>
					</div>
				</div>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	<?php endif; ?>
	<div class="lottery_list f_r">
			<h1 class="lottery-title">中奖记录</h1>
			<div class="txtScroll-top">
				<div class="bd">
					<div class="tempWrap">
						<ul class="lottery_box infoList">
						<?php $_from = $this->_var['newest_lottery_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'newest_lottery');$this->_foreach['newest_lottery_foreach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['newest_lottery_foreach']['total'] > 0):
    foreach ($_from AS $this->_var['newest_lottery']):
        $this->_foreach['newest_lottery_foreach']['iteration']++;
?>
							<li class="clearfix lottery_info <?php if (($this->_foreach['newest_lottery_foreach']['iteration'] - 1) % 2 == 0): ?>gray-bac<?php endif; ?>" >
									<div class="user-pic f_l"><img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['newest_lottery']['luck_user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>" width="40" height="40"></div>
									<div class="f_l bonus-info">
									<div class="user-time clearfix">
										<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['newest_lottery']['luck_user_id']."".""); 
?>" class="user_name f_l" title="<?php echo $this->_var['newest_lottery']['user_name']; ?>(ID:<?php echo $this->_var['newest_lottery']['luck_user_id']; ?>)"><?php echo $this->_var['newest_lottery']['user_name']; ?></a>
										<!--//中奖纪录的时间-->
										<!--<sapn class="duobao-time f_r">于<?php echo $this->_var['newest_lottery']['span_time']; ?></sapn>-->
									</div>
									<p class="info">夺得<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['newest_lottery']['id']."".""); 
?>" class="bonus-goods"><?php echo $this->_var['newest_lottery']['name']; ?></a></p>
									<div class="p-price">
										总需：<?php echo $this->_var['newest_lottery']['max_buy']; ?>人次
									</div>
									</div>
							</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
					</div>
				</div>
			</div>
			<h2>看看谁的手气最好！</h2>
	</div>
</div>
<div class="clear"></div>
<div class="wrap_full_w">
	<?php if ($this->_var['cate_list_product']): ?>
		<?php $_from = $this->_var['cate_list_product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate_list_0_85033500_1510715083');if (count($_from)):
    foreach ($_from AS $this->_var['cate_list_0_85033500_1510715083']):
?>
		 <?php if ($this->_var['cate_list_0_85033500_1510715083']['duobao_list']): ?>
		<div class="index-goods-list" style="width: 1201px">
			<h1 class="main-title"><?php echo $this->_var['cate_list_0_85033500_1510715083']['name']; ?>
			<a href="<?php
echo parse_url_tag("u:index|duobaos|"."id=".$this->_var['cate_list']['id']."".""); 
?>" class="title-more">更多商品，点击查看>></a>
			</h1>
			<ul class="ui-list" width="241" pin_col_init_width="240" wSpan="-1" hSpan="1">
			<li class="cate_list">
			<a href="<?php
echo parse_url_tag("u:index|duobaos|"."id=".$this->_var['cate_list']['id']."".""); 
?>" class="title-more"><img src="<?php echo $this->_var['cate_list_0_85033500_1510715083']['image']; ?>" width="238" height="400"  ></a>
			<!-- <?php echo show_adv("cate_banner","","238","400");  ?> -->
			</li>
			<?php $_from = $this->_var['cate_list_0_85033500_1510715083']['duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hot_duobao');if (count($_from)):
    foreach ($_from AS $this->_var['hot_duobao']):
?>
				<li class="goods ui-item">
				<div class="hover_line">
					<?php if ($this->_var['hot_duobao']['min_buy'] == 10 || $this->_var['hot_duobao']['unit_price'] == 10): ?>
					<div class="ico logo-box ten-logo-box"></div>
					<?php endif; ?>
					<?php if ($this->_var['hot_duobao']['unit_price'] == 100): ?>
					<div class="ico logo-box hundred-logo-box"></div>
					<?php endif; ?>
					<div class="goods-wrap">
						<div class="imgbox">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['hot_duobao']['id']."".""); 
?>" title="<?php echo $this->_var['hot_duobao']['name']; ?>">
							<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['hot_duobao']['icon'],
  'w' => '200',
  'h' => '200',
);
echo $k['name']($k['v'],$k['w'],$k['h']);
?>" width="200" height="200" lazy="true" />
						</a>
						</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['hot_duobao']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['hot_duobao']['name']; ?>">
                        <?php if ($this->_var['hot_duobao']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                        <?php endif; ?><?php echo $this->_var['hot_duobao']['name']; ?>
						</a>
						<div class="p-price f_l">
							总需：<?php echo $this->_var['hot_duobao']['max_buy']; ?>人次

						</div>
						<div class="clear"></div>
						<!-- 进度条 -->
						<div class="progressBar" title="<?php echo $this->_var['hot_duobao']['progress']; ?>%">
						<!-- 进度条外层 -->
							<div class="progress">
							<!-- 进度条内层 -->
								<div class="progress-bar" style="width:<?php echo $this->_var['hot_duobao']['progress']; ?>%"></div>
							</div>
						</div>
						<ul class="person-info">
							<li class="f_l">
								<p class="num"><?php echo $this->_var['hot_duobao']['current_buy']; ?></p>
								<p class="info">已参与人次</p>
							</li>
							<li class="f_r tar">
								<p class="num"><?php echo $this->_var['hot_duobao']['less_buy']; ?></p>
								<p class="info">剩余人次</p>
							</li>
							<div class="clear"></div>
						</ul>
						<div class="btn-box">
							<a class="duobao-now btn" buy_num="<?php echo $this->_var['hot_duobao']['min_buy']; ?>" data_id="<?php echo $this->_var['hot_duobao']['id']; ?>" title="立即夺宝" onclick="add_cart(this,1)">立即夺宝</a>
							<a class="cart-btn" buy_num="<?php echo $this->_var['hot_duobao']['min_buy']; ?>" data_id="<?php echo $this->_var['hot_duobao']['id']; ?>" title="添加到购物车" onclick="add_cart(this,0)"></a>
						</div>
					</div>
					</div>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endif; ?>
</div>
<div class="wrap_full_w">
	    <?php if ($this->_var['newest_duobao_list']): ?>
		<div class="index-new-goods-list" style="width: 1201px">
			<h1 class="main-title">最新上架
			<a href="<?php
echo parse_url_tag("u:index|duobaos|"."r=new".""); 
?>" class="title-more">更多商品，点击查看>></a>
			</h1>
			<ul class="ui-list" width="241" pin_col_init_width="0" wSpan="-1" hSpan="1">
				<?php $_from = $this->_var['newest_duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'newest_duobao');if (count($_from)):
    foreach ($_from AS $this->_var['newest_duobao']):
?>
				<li class="goods ui-item">
				<div class="hover_line">
				<?php if ($this->_var['newest_duobao']['min_buy'] == 10 || $this->_var['newest_duobao']['unit_price'] == 10): ?>
					<div class="ico logo-box ten-logo-box"></div>
					<?php endif; ?>
					<?php if ($this->_var['newest_duobao']['unit_price'] == 100): ?>
					<div class="ico logo-box hundred-logo-box"></div>
					<?php endif; ?>
					<div class="goods-wrap">
						<div class="imgbox">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['newest_duobao']['id']."".""); 
?>" title="<?php echo $this->_var['newest_duobao']['name']; ?>">
							<img src="<?php echo $this->_var['newest_duobao']['icon']; ?>" width="200" height="200" lazy="true" />
						</a>
						</div>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['newest_duobao']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['newest_duobao']['name']; ?>">
                        <?php if ($this->_var['newest_duobao']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                        <?php endif; ?><?php echo $this->_var['newest_duobao']['name']; ?>
						</a>
						<div class="p-price f_l">
							总需：<?php echo $this->_var['newest_duobao']['max_buy']; ?>人次
						</div>
					</div>
					</div>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
		<?php endif; ?>
</div>



<div class="wrap_full_w">
		<div class="index-share-list" style="width: 1201px;border-bottom: 1px solid #ddd">
			<h1 class="main-title" style="border-bottom: 1px solid #d2d2d2;">晒单分享
			<a href="<?php
echo parse_url_tag("u:index|share|"."".""); 
?>" class="title-more">更多分享，点击查看>></a>
			</h1>
			<div class="multipleLine">
				<div class="bd">
					<div class="ulWrap">
						<?php $_from = $this->_var['share_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'share');if (count($_from)):
    foreach ($_from AS $this->_var['share']):
?>
						<ul class="clearfix">
							<?php if ($this->_var['share']['0']): ?>
							<li class="index-share f_l left">
								<a href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['share']['0']['id']."".""); 
?>" class="share-pic" target="_blank" style="background-image:url(<?php echo $this->_var['share']['0']['image_list']['0']['path']; ?>);background-size:100% 100%;"></a>
								<div class="share-info">
									<i class="ico quote quote-b"></i>
									<i class="ico quote quote-a"></i>
									<p class="share-txt" style="cursor:pointer;" onclick="window.location.href= '<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['share']['0']['id']."".""); 
?>';return false">【<?php echo $this->_var['share']['0']['title']; ?>】<?php echo $this->_var['share']['0']['content']; ?></p>
									<p class="author">
										—— <a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['share']['0']['user_id']."".""); 
?>" title="{<?php echo $this->_var['share']['0']['user_name']; ?>}(ID:<?php echo $this->_var['share']['0']['user_id']; ?>)" target="_blank"><?php echo $this->_var['share']['0']['user_name']; ?></a>
										<!-- <?php echo $this->_var['share']['0']['create_time']; ?> -->

										<?php
										 session_start();
										 $time=time()-rand(999,99999);$saitime = date('Y-m-d',$time);
                                            echo $saitime;
										 $_SESSION["saitime"]=$saitime; ?>
									</p>
								</div>
							</li>
							<?php endif; ?>
							<?php if ($this->_var['share']['1']): ?>
							<li class="index-share f_l">
								<a href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['share']['1']['id']."".""); 
?>" class="share-pic" target="_blank" style="background-image:url(<?php echo $this->_var['share']['1']['image_list']['0']['path']; ?>);background-size:100% 100%;"></a>
								<div class="share-info">
									<i class="ico quote quote-b"></i>
									<i class="ico quote quote-a"></i>
									<p class="share-txt" style="cursor:pointer;" onclick="window.location.href= '<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['share']['1']['id']."".""); 
?>';return false">【<?php echo $this->_var['share']['1']['title']; ?>】<?php echo $this->_var['share']['1']['content']; ?></p>
									<p class="author">
										—— <a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['share']['1']['user_id']."".""); 
?>" title="{<?php echo $this->_var['share']['1']['user_name']; ?>}(ID:<?php echo $this->_var['share']['1']['user_id']; ?>)" target="_blank"><?php echo $this->_var['share']['1']['user_name']; ?></a>
										<!-- <?php echo $this->_var['share']['1']['create_time']; ?> -->
										<?php
										 session_start();
										 echo $_SESSION["saitime"]; ?>
									</p>
								</div>
							</li>
							<?php endif; ?>
						</ul>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="ecv_log_suc" style="z-index: 9999;">
    <p>夺宝币已存入余额</p>
    <p>付款时可以直接使用</p>
</div>




<script>
/*轮播图*/
	var slide_count = $(".slideBox").find(".bd ul li").length;
	var ctl_html = "";
	for(var i=0;i<slide_count;i++)
	{
		ctl_html+="<li></li>";
	}
	 $(".slideBox").find(".hd ul").html(ctl_html);
	$(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true}).hover(function() {
		$(".slideBox-control-btn").show()
	}, function() {
		$(".slideBox-control-btn").hide()
	});
/*中奖纪录*/
	$(".txtScroll-top").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"top",autoPlay:true,vis:8});
/*最新揭晓左右轮播*/
	$(".txtScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,scroll:2,vis:2}).hover(function() {
		$(".txtScroll-left-control-btn").show()
	}, function() {
		$(".txtScroll-left-control-btn").hide()
	});
/*新品推荐左右轮播*/
$(".txtScroll-left-2").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true}).hover(function() {
		$(".txtScroll-left-2-control-btn").show()
	}, function() {
		$(".txtScroll-left-2-control-btn").hide()
	});
/*晒单分享*/
jQuery(".multipleLine").slide({titCell:".hd ul",mainCell:".bd .ulWrap",autoPage:true,effect:"top",autoPlay:true,vis:2});
</script>
<?php echo $this->fetch('inc/footer.html'); ?>