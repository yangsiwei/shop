<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/layer.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pk.css";

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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/layer.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/layer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duobao.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/pk.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/pk.js";
?>
<?php if ($this->_var['root']['item_data']['is_number_choose']): ?>
 <?php
    $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/number_choose.css";
    $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/number_choose.js";
    $this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/number_choose.js";
 ?>
<?php endif; ?>
<?php echo $this->fetch('inc/header.html'); ?>
<script type="text/javascript">
var main_url='<?php
echo parse_url_tag("u:index|index|"."".""); 
?>';
var totalbuy_cart_url='<?php
echo parse_url_tag("u:index|totalbuy|"."".""); 
?>';
</script>
<script>
var min_buy=<?php echo $this->_var['root']['item_data']['min_buy']; ?>;
var duobao_item_id=<?php echo $this->_var['root']['item_data']['id']; ?>;
</script>
<div class="wrap_full_w clearfix" style="margin-bottom: 29px">
<!-- 站内地图 -->
	<div class="dir"></div>
	<!-- 主体内容 -->
	<div class="goods-detail f_l">
		<div class="goods-pic-info">
			<!-- 十夺宝币专区logo -->
			<?php if ($this->_var['root']['item_data']['min_buy'] == 10): ?>
			<div class="ico logo-box ten-logo-box"></div>
			<?php endif; ?>
			<?php if ($this->_var['root']['item_data']['unit_price'] == 100): ?>
			<div class="ico logo-box hundred-logo-box"></div>
			<?php endif; ?>
			<!-- 大图预览 -->
			<div class="goods-pic-wrap">
				<div class="goods-pic">
					<img src="<?php echo $this->_var['root']['item_data']['icon']; ?>" width="418" height="418">
				</div>
			</div>
			<!-- 红色箭头 -->
			<i class="ico ico-arrow-red-up ico-arrow-red"></i>
			<!-- 小图片选择 -->
			<div class="small-pic-wrap">
				<ul class="small-pic-list">
					<?php if ($this->_var['root']['item_data']['icon']): ?>
					<li class="small-pic-item small-pic-current small-pic-odd">
 						<img src="<?php echo $this->_var['root']['item_data']['icon']; ?>">
					</li>
					<?php endif; ?>
					<?php $_from = $this->_var['root']['item_data']['deal_gallery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'deal_gallery_unit');$this->_foreach['deal_gallery'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['deal_gallery']['total'] > 0):
    foreach ($_from AS $this->_var['deal_gallery_unit']):
        $this->_foreach['deal_gallery']['iteration']++;
?>
					<li class="small-pic-item <?php if (($this->_foreach['deal_gallery']['iteration'] == $this->_foreach['deal_gallery']['total'])): ?>small-pic-last<?php endif; ?> <?php if (($this->_foreach['deal_gallery']['iteration'] - 1) == 1): ?> small-pic-odd<?php endif; ?>">
						<img src="<?php echo $this->_var['deal_gallery_unit']; ?>">
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

				</ul>
			</div>
			<!-- 承诺信息 -->
			<div class="promise-box clearfix">
				<div class="promise-item">
					<i class="goods-detail-ico ico-promise promise-open"></i><span class="promise-txt">公正公开</span>
				</div>
				<div class="promise-item">
					<i class="goods-detail-ico ico-promise promise-real"></i><span class="promise-txt">正品保证</span>
				</div>
				<div class="promise-item">
					<i class="goods-detail-ico ico-promise promise-protect"></i><span class="promise-txt">权益保障</span>
				</div>
				<div class="promise-item promise-item-last">
					<i class="goods-detail-ico ico-promise promise-free"></i><span class="promise-txt">免费配送</span>
				</div>
			</div>
			<!-- 分享到 -->
			<div class="share-to">
						<!-- JiaThis Button BEGIN -->
						<div class="jiathis_style">
							<span class="jiathis_txt">分享到：</span>
							<a class="jiathis_button_tsina"><i class="icon iconfont"></i></a>
							<a class="jiathis_button_weixin"><i class="icon iconfont"></i></a>
							<a class="jiathis_button_cqq"><i class="icon iconfont"></i></a>
							<a class="jiathis_button_qzone"><i class="icon iconfont"></i></a>
							<a class="jiathis_button_douban"><i class="icon iconfont"></i></a>
							<a class="jiathis_button_copy"><i class="icon iconfont"></i></a>
							<a class="jiathis_counter_style"></a>
						</div>
						<script type="text/javascript">
							 var jiathis_config = {
								sm:"tsina,weixin,cqq,qzone,douban,copy",
								siteNum:6,
							 	title:"<?php echo $this->_var['share_title']; ?>",
							    url:"<?php echo $this->_var['share_url']; ?>",

							};
						</script>
						<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>

						<!-- JiaThis Button END -->


			</div>
		</div>
	</div>
	<!-- 购买商品 -->
	<div class="goods-detail-buy f_r">
		<!-- 商品标题 -->
		<div class="goods-detail-title">
		<!-- 主标题 -->
			<div class="detail-title-main">
				<h1><?php if ($this->_var['root']['item_data']['is_topspeed']): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                    <?php endif; ?>
                    <?php if ($this->_var['root']['item_data']['is_number_choose'] == 1): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">选号</em>
                    <?php endif; ?>
                    <?php if ($this->_var['root']['item_data']['is_pk'] == 1): ?>
                    <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">PK</em>
                        <?php if ($this->_var['root']['item_data']['has_password'] == 1): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">密</em>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php echo $this->_var['root']['item_data']['name']; ?></h1>
			</div>
			<!-- 副标题 -->
			<p class="detail-title-desc"><?php echo $this->_var['root']['item_data']['brief']; ?></p>
		</div>
		<div class="detail-buy-info" style="display: block;">
		
			<?php if ($this->_var['root']['item_data']['is_total_buy'] == 1 && $this->_var['root']['item_data']['total_buy_stock'] > 0 && $this->_var['root']['item_data']['progress'] < 100 && $this->_var['root']['item_data']['success_time'] == 0): ?> 
			<div class="zg-area">
				<div class="zg-hd clearfix">
					<i class="goods-detail-ico buy-type buy-type-1"></i>
					<h2 class="info-header-title">全价购买</h2>
					<h3 class="info-header-desc">无需等待，直接购买获得该商品</h3>
				</div>
				<div class="zg-price clearfix">
					<div class="zg-main-price f_l">
						<p>促销价<strong class="txt-red"><?php echo $this->_var['root']['item_data']['total_buy_price']; ?></strong></p>
					</div>
				</div>
				<div class="zg-btn clearfix">
					<a data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" href="javascript:;" onclick="add_total_buy_cart_item(this)" class="zg-buy">直接购买</a>
				</div>
			</div>
			<?php endif; ?>
			
			<?php if ($this->_var['root']['item_data']['progress'] < 100 && $this->_var['root']['item_data']['success_time'] == 0): ?>
			<!-- 进行中 -->
			<!-- 购买流程 -->
			<div class="buy-info-header clearfix">
				<?php if ($this->_var['root']['item_data']['is_total_buy'] == 1 && $this->_var['root']['item_data']['total_buy_stock'] > 0): ?> <i class="goods-detail-ico buy-type buy-type-2"></i><?php endif; ?>
				<h2 class="info-header-title"><?php if ($this->_var['root']['item_data']['unit_price'] == 100): ?>百<?php elseif ($this->_var['root']['item_data']['min_buy'] == 10): ?>十<?php else: ?>一<?php endif; ?>夺宝币夺宝</h2>
				<h3 class="info-header-desc"><span class="code">期号：<?php echo $this->_var['root']['item_data']['id']; ?></span>每满总需人次，即抽取1人获得该商品</h3>
				<div class="buy-explain">
					?
					<div class="explain-box">
						<i class="goods-detail-ico anchor-left"></i>
						<div class="explain-content">
							<p>“一夺宝币夺宝”指用户花费1夺宝币兑换一个夺宝币，随后可凭夺宝币使用一夺宝币夺宝的服务。</p>
							<p>每件商品的全部夺宝号码分配完毕后，一夺宝币夺宝根据夺宝规则计算出的一个号码。<strong>持有该幸运号码的用户可直接获得该商品。</strong></p>
						</div>
					</div>
				</div>
			</div>
			<!-- 进度条 -->
			<div class="progress-wrap clearfix">
				<div class="progress f_l">
					<div class="progress-bar" style="width:<?php echo $this->_var['root']['item_data']['progress']; ?>%"></div>
				</div>
				<div class="progress-txt f_l">已完成<?php echo $this->_var['root']['item_data']['progress']; ?>%</div>
			</div>
			<!-- 价格信息 -->
			<div class="price-info clearfix">
				<div class="all-price f_l">
					总需人次<?php echo $this->_var['root']['item_data']['max_buy']; ?>
				</div>
				<div data="<?php echo $this->_var['root']['item_data']['surplus_count']; ?>" class="rest-price f_r">
					剩余人次<?php echo $this->_var['root']['item_data']['surplus_count']; ?>
				</div>
			</div>
			<!-- 加减商品 -->
          <?php if ($this->_var['root']['item_data']['is_pk'] != 1 && $this->_var['root']['item_data']['is_number_choose'] != 1): ?>
			<div class="buy-count">
				<span>参与</span>
				<div class="buy-num-count">
					<div class="buy-num">
						<a class="buy-num-btn num-btn-min">-</a>
						<input name="num" type="text" class="num-input" value="<?php echo $this->_var['root']['item_data']['min_buy']; ?>" min_buy="<?php echo $this->_var['root']['item_data']['min_buy']; ?>" init_num="0">
						<a class="buy-num-btn num-btn-plus">+</a>
					</div>
				</div>&nbsp;<span>人次</span>
				<span class="buy-hint">
					<i class="ico ico-arrow-graysmall"></i>
					<?php if ($this->_var['root']['item_data']['user_max_buy'] > 0): ?>
					<span style="color:#dd344f;">1人次=<?php echo $this->_var['root']['item_data']['unit_price']; ?>夺宝币&nbsp;&nbsp; <?php if ($this->_var['root']['item_data']['user_max_buy'] > 0): ?>每人限购 <?php echo $this->_var['root']['item_data']['user_max_buy']; ?> 次<?php endif; ?></span>
					<?php else: ?>
					<span>多参与1人次，获取幸运号码机会翻倍！</span>
					<?php endif; ?>
				</span>
			</div>
			<ul class="buy-list clearfix">
				<li><a class="price-btn" href="javascript:void(0);">5</a></li>
				<li><a class="price-btn" href="javascript:void(0);">20</a></li>
				<li><a class="price-btn" href="javascript:void(0);">50</a></li>
				<li><a class="price-btn" href="javascript:void(0);">100</a></li>
				<li><a class="price-btn" href="javascript:void(0);">200</a></li>
				<li><a class="price-btn" href="javascript:void(0);">我包了！</a></li>
			</ul>
            <?php endif; ?>
			<!-- 按钮 -->
			<?php if ($this->_var['root']['item_data']['is_coupons']): ?>
			<div class="buy-btn-box">
				<!-- <a buy_num="<?php echo $this->_var['root']['item_data']['min_buy']; ?>" data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" class="buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?>" <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" onclick="add_cart(this,1)"<?php endif; ?>>免费云购</a> -->
				<a href="javascript:void(0);" class="buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?>" <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" onclick="add_cart_item(this,1)"<?php endif; ?>>免费云购</a>
			</div>
			<?php elseif ($this->_var['root']['item_data']['is_pk']): ?>
                <?php if ($this->_var['root']['duobao_recode_count'] > 0): ?>
                    <div class="buy-btn-box">
                        <a href="javascript:void(0);" class="buy-btn out_sold">已参与过PK</a>
                    </div>
                <?php elseif ($this->_var['root']['item_data']['has_password']): ?>
                    <div class="buy-btn-box">
                         <a href="javascript:void(0);" class="buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?> j-pk" goodName="<?php echo $this->_var['root']['item_data']['name']; ?>" goodNum="<?php echo $this->_var['root']['item_data']['min_buy']; ?>" url='<?php
echo parse_url_tag("u:index|pk#check_password|"."data_id=".$this->_var['root']['item_data']['id']."".""); 
?>' <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" <?php endif; ?>>立即PK</a>
                    </div>
                <?php else: ?>
                    <div class="buy-btn-box">
                        <a href="javascript:void(0);" class="buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?> submit"  url='<?php
echo parse_url_tag("u:index|pk#check_password|"."data_id=".$this->_var['root']['item_data']['id']."".""); 
?>' <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" <?php endif; ?>>立即PK</a>
                    </div>
                <?php endif; ?>
            <?php elseif ($this->_var['root']['item_data']['is_number_choose'] == 1): ?>
                    <div class="buy-btn-box">
                          <a class="duobao-now buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?>"  href="javascript:void(0);" duobao-item-id="<?php echo $this->_var['root']['item_data']['id']; ?>" url='<?php
echo parse_url_tag("u:index|number_choose#select|"."".""); 
?>'>立即选号</a>
                    </div>
            <?php else: ?>
			<div class="buy-btn-box">
				<a href="javascript:void(0);" class="buy-btn <?php if ($this->_var['root']['item_data']['is_effect']): ?>quick-buy<?php else: ?>out_sold<?php endif; ?>" <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" onclick="add_cart_item(this,1)"<?php endif; ?>>立即夺宝</a>
				<a href="javascript:void(0);" class="buy-btn add-to-list <?php if ($this->_var['root']['item_data']['is_effect'] == 0): ?> out_sold<?php endif; ?>" <?php if ($this->_var['root']['item_data']['is_effect']): ?> data_id="<?php echo $this->_var['root']['item_data']['id']; ?>" onclick="add_cart_item(this,0)"<?php endif; ?>>
				<i class="goods-detail-ico detail-cart"></i>
				<span>加入清单</span>
				</a>
			</div>
			<?php endif; ?>
			<!-- 参与信息 -->
			<!-- 未参与 -->
			<?php if ($this->_var['root']['item_data']['is_effect']): ?>

			<div class="user-info">
				<?php if (! $this->_var['user']): ?>
					<!-- 未登录 -->
					<a href="javascript:void(0);" onclick="ajax_login(function(){location.reload();});">请登录</a>
					查看你的夺宝号码
				<?php else: ?>
					<?php if (! $this->_var['root']['duobao_recode_list']): ?>
					<!-- 已登录 -->
					你还没参与本期商品哦~
					<?php else: ?>
					<!-- 已参与 -->
					<table>
						<thead>
							<tr>
								<th>您已参与：</th>
								<td><b class="txt-red"><?php echo $this->_var['root']['duobao_recode_count']; ?></b>人次</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>夺宝号码：</th>
								<td>
								<div class="my-code">
									<?php $_from = $this->_var['root']['duobao_recode_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_recode');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_recode']):
?>

										<?php $_from = $this->_var['duobao_recode']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_sn');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_sn']):
?>
											<?php echo $this->_var['duobao_sn']['lottery_sn']; ?>&nbsp;
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</div>
								</td>
							</tr>
						</tbody>
					</table>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<!-- 缺货 -->
			<!--
			<div class="no-goods clearfix">
				<i class="goods-detail-ico no-goods-ico"></i>
				<div class="no-goods-content">
					<p>此商品暂时缺货。我们会尽快重新上架，</p>
					<p>敬请期待！</p>
					<a href="">去逛逛其它商品</a>
				</div>
			</div>
			 -->

			<!-- 商品下架 -->
			<?php else: ?>
			<div class="goods-soldout">
				商品已下架
			</div>
			<?php endif; ?>
		</div>
		<?php else: ?>
			<div id="countdownnum_flush" >
			<?php if (! $this->_var['root']['item_data']['has_lottery']): ?>
			<!-- 揭晓中 -->
			<!-- 倒计时 -->
			<div class="detail-countdown clearfix" >
				<i class="goods-detail-ico hourglass f_l"></i>
				<div class="countdown-box f_l">
					<div class="countdown-box-hd">
						<span>期号：<?php echo $this->_var['root']['item_data']['id']; ?></span>
						<span class="split">|</span>
						<span>揭晓倒计时</span>
					</div>
					<time class="countdown-num" nowtime="<?php echo $this->_var['root']['item_data']['now_time']; ?>" endtime="<?php echo $this->_var['root']['item_data']['lottery_time']; ?>" item_data_id="<?php echo $this->_var['root']['item_data']['id']; ?>">
						<b>0</b><b>0</b>:<b>0</b><b>0</b>:<b>0</b><b>0</b>
					</time>
				</div>
			</div>

			<?php else: ?>
			<!-- 已揭晓 -->
			<div class="winner">
				<!-- 获奖者幸运号码 -->
				<div class="winner-luckycode clearfix">
					<div class="hd f_l">
						<span class="period">期号<span class="period-num"><?php echo $this->_var['root']['item_data']['luck_lottery']['duobao_item_id']; ?></span></span>
						<span class="title">幸运号码</span>
					</div>
					
						<!-- 五倍开奖-->
						<?php if ($this->_var['lott']): ?>
						<span style="line-height:40px;font-weight: bold;color: #ffffff;font-family: Arial;"><?php $_from = $this->_var['lott']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
					<?php echo $this->_var['value']['lottery_sn']; ?>&nbsp;&nbsp;
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></span>
						<?php else: ?>
						<div class="code f_l">
						<?php echo $this->_var['root']['item_data']['luck_lottery']['lottery_sn']; ?></div>
						<?php endif; ?>
				</div>
				<!-- 获奖者信息 -->
				<div class="winner-detail clearfix">
					<i class="goods-detail-ico winner-ico"></i>
					<div class="clear"></div>
					<img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['root']['item_data']['luck_lottery']['user_id'],
  'type' => 'big',
);
echo $k['name']($k['uid'],$k['type']);
?>" alt="" class="user-pic f_l" width="90" height="90">
					<div class="f_l winner-user-info">
						<div class="info-item user-name">
							<?php if ($this->_var['lott']): ?>
								<?php $_from = $this->_var['lott']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
								<span><?php echo $this->_var['value']['user_id']; ?>,</span>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							<?php else: ?>
							<span class="hd">用户昵称</span>：<span class="bd"><a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['root']['item_data']['luck_lottery']['user_id']."".""); 
?>" target="_blank"><?php echo $this->_var['root']['item_data']['luck_lottery']['user_name']; ?></a></span>
							<?php if ($this->_var['root']['item_data']['luck_lottery']['province_name'] || $this->_var['root']['item_data']['luck_lottery']['city_name']): ?>（<?php if ($this->_var['root']['item_data']['luck_lottery']['province_name']): ?><?php echo $this->_var['root']['item_data']['luck_lottery']['province_name']; ?>省<?php endif; ?><?php if ($this->_var['root']['item_data']['luck_lottery']['city_name']): ?><?php echo $this->_var['root']['item_data']['luck_lottery']['city_name']; ?>市<?php endif; ?>）<?php endif; ?>
							<?php endif; ?>
						</div>
						<div class="info-item user-id">
							<span class="hd">用户 I D</span>：<span class="bd"><?php if ($this->_var['lott']): ?><?php echo $this->_var['root']['item_data']['luck_user_id']; ?><?php else: ?><?php echo $this->_var['root']['item_data']['luck_lottery']['user_id']; ?><?php endif; ?>（ID为用户唯一不变标识）</span>
						</div>
						<div class="info-item user-buy-time">
							<span class="hd">本期参与</span>：<span><b class="txt-red">
							<?php if ($this->_var['lott']): ?>
                        		<?php echo $this->_var['duobao1']['max_buy']; ?>
                        	<?php else: ?>
                          		<?php echo $this->_var['root']['item_data']['luck_lottery']['user_duobao_count']; ?>
                        	<?php endif; ?>人次</b></span>
						</div>
					</div>
					<div class="f_l winner-record">
						<div class="info-item published-time">
							<span class="hd">揭晓时间</span>：<span class="bd"><?php echo $this->_var['root']['item_data']['lottery_time_format']; ?></span>
						</div>
						<div class="info-item buy-time">
							<span class="hd">夺宝时间</span>：<span class="bd"><?php echo $this->_var['root']['item_data']['luck_lottery']['create_time']; ?></span>
						</div>
						<div class="info-item codes">
							<a class="btn-winner-code-s">查看<?php if ($this->_var['root']['is_login']): ?>我<?php else: ?>TA<?php endif; ?>的号码>></a>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_var['lott']): ?><?php else: ?>
			<!-- 计算规则 -->
			<div class="calcu-rule clearfix">
				<!-- 如何计算已经最终取值 -->
				<div class="calcu-main info f_l clearfix">
					<div class="calcu-title">如何计算？</div>
					<!-- 幸运号码 -->
					<div class="calcu-luckycode f_l calcu-item">
						<p class="num"><?php if ($this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?><?php echo $this->_var['root']['item_data']['luck_lottery']['lottery_sn']; ?><?php else: ?>?<?php endif; ?></p>
						<p class="tip">本期幸运号码</p>
					</div>
					<!-- 等于号 -->
					<div class="equal f_l">=</div>
					<!-- 固定数值 -->
					<div class="calcu-item f_l">
						<p class="num">100000001</p>
						<p class="tip">固定数值</p>
					</div>
					<!-- 加号 -->
					<div class="add f_l">+</div>
					<!-- 变化数值 -->
					<div class="calcu-num-x f_l calcu-item">
						<p class="num"><?php if ($this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?><?php echo $this->_var['root']['item_data']['luck_lottery']['fixed_value']; ?><?php else: ?>?<?php endif; ?></p>
						<p class="tip">变化数值</p>
					</div>
				</div>
				<!-- 变化数值取值方式 -->
                <?php if ($this->_var['root']['item_data']['is_topspeed'] == 0): ?>
				<div class="calcu-sec info f_l clearfix">
					<div class="calcu-title"><strong>变化数值</strong>是取下面公式的余数</div>
					<div class="f_l left-bk">(</div>
					<div class="f_l calcu-item calcu-sum">
						<p class="num"><?php echo $this->_var['root']['item_data']['fair_sn_local']; ?></p>
						<div class="tip">50个时间求和
							<div class="hover-more more-1">
								<i class="goods-detail-ico tip-box"></i>
								<div class="more-content">
									商品的最后一个号码分配完毕，公示该分配时间点前本站全部商品的<span class="txt-red">最后50个参与时间</span>，并求和。
								</div>
							</div>
						</div>
					</div>
					<div class="f_l add">+</div>
					<div class="f_l calcu-item calcu-lottery">
						<p class="num"><?php if ($this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?><?php echo $this->_var['root']['item_data']['fair_sn']; ?><?php else: ?>?<?php endif; ?></p>
						<?php if ($this->_var['root']['item_data']['fair_sn'] != '111111'): ?>
						<div class="tip">
							<?php if ($this->_var['root']['item_data']['fair_type'] == 'wy'): ?>“老时时彩”<?php endif; ?>幸运号码
							<?php if ($this->_var['root']['item_data']['fair_type'] == 'wy'): ?>
							<div class="hover-more more-2">
								<i class="goods-detail-ico tip-box"></i>
								<div class="more-content">
									取最近一期“老时时彩”<?php if ($this->_var['root']['item_data']['fair_period']): ?> (第<?php echo $this->_var['root']['item_data']['fair_period']; ?>期) <?php endif; ?>揭晓结果。
								</div>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="f_l right-bk">)</div>
					<div class="f_l calcu-divide">÷</div>
					<div class="f_l calcu-price calcu-item">
						<p class="num"><?php echo $this->_var['root']['item_data']['max_buy']; ?></p>
						<div class="tip">总需人次</div>
					</div>
				</div>
                <?php endif; ?>
                <?php if ($this->_var['root']['item_data']['is_topspeed'] == 1): ?>
                <div class="calcu-sec info f_l clearfix">
                    <div class="calcu-title"><strong>变化数值</strong>是取下面公式的余数</div>
                    <!--<div class="f_l left-bk">(</div>-->
                    <div class="f_l calcu-item calcu-sum">
                        <p class="num"><?php echo $this->_var['root']['item_data']['fair_sn_local']; ?></p>
                        <div class="tip">50个时间求和
                            <div class="hover-more more-1">
                                <i class="goods-detail-ico tip-box"></i>
                                <div class="more-content">
                                    商品的最后一个号码分配完毕，公示该分配时间点前本站全部商品的<span class="txt-red">最后50个参与时间</span>，并求和。
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="f_l calcu-divide">÷</div>
                    <div class="f_l calcu-price calcu-item">
                        <p class="num"><?php echo $this->_var['root']['item_data']['max_buy']; ?></p>
                        <div class="tip">总需人次</div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
			</div>
			</div>
			<!-- 我的号码 -->
			<div class="my-code">
				<?php if (! $this->_var['user']): ?>
					<!-- 未参与 -->
					<a href="javascript:void(0);" onclick="ajax_login(function(){location.reload();});">请登录</a>
					查看你的夺宝号码
				<?php else: ?>
					<?php if (! $this->_var['root']['duobao_recode_list']): ?>
					<!-- 未参与 -->
					您还没有参与本次夺宝哦！
					<?php else: ?>
					<!-- 已参与 -->
					您已拥有<?php echo $this->_var['root']['duobao_recode_count']; ?>个夺宝号码
					<a class="btn-winner-code">查看号码>></a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<!-- 最新一期 -->

			<?php if ($this->_var['root']['new_item_data']): ?>
			<div class="detail-newest">
				<!-- 标题 -->
				<div class="detail-newest-title">
					<strong>【最新一期】</strong>正在火热进行中...
				</div>
				<!-- 进度条 -->
				<div class="newest-progress f_l">
					<div class="progress-wrap clearfix">
						<div class="progress f_l">
						<!-- 进度条内层 -->
							<div class="progress-bar" style="width: <?php echo $this->_var['root']['new_item_data']['progress']; ?>%"></div>
						</div>
						<div class="newest-progress-txt f_l">已完成<?php echo $this->_var['root']['new_item_data']['progress']; ?>%，剩余<?php echo $this->_var['root']['new_item_data']['surplus_count']; ?></div>
					</div>
				</div>
				<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['root']['new_item_data']['id']."".""); 
?>" class="f_l go-newest">立刻前往</a>
			</div>
			<?php else: ?>
			<div class="detail-newest">
				<!-- 标题 -->
				<div class="detail-newest-title">
					暂无最新一期……
				</div>
				<!-- 进度条 -->
				<div class="newest-progress f_l">
					<div class="progress-wrap clearfix">
						<div class="progress f_l">
						<!-- 进度条内层 -->
							<div class="progress-bar" style="width: 0%"></div>
						</div>
						<div class="f_l" style="color: #999999;">已完成0%</div>
					</div>
				</div>
				<a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>" class="f_l" style="height: 45px;line-height: 45px;margin-top: 5px;">去查看更多商品</a>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
</div>
<div class="wrap_full_w">
<!-- 选项卡 -->
	<div class="detail-tab">
		<?php if ($this->_var['root']['item_data']['progress'] < 100 && $this->_var['root']['item_data']['success_time'] == 0): ?>
		<div id="intro-tab" class="tab-item tab-item-selected">商品详情</div>
		<?php else: ?>
		<div id="result-tab" class="tab-item tab-item-selected">计算结果</div>
		<?php endif; ?>
		<div id="record-tab" class="tab-item">夺宝参与记录</div>
		<div id="share-tab" class="tab-item">晒单分享</div>
		<div id="history-tab" class="tab-item">往期夺宝</div>
	</div>
<!-- 选项卡对应内容 -->
	<div class="tab-info">
		<?php if ($this->_var['root']['item_data']['progress'] < 100 && $this->_var['root']['item_data']['success_time'] == 0): ?>
		<!-- 商品详情 -->
		<div id="intro-info"  class="tab-info-item" style="padding: 20px 0px;">
			<?php echo $this->_var['root']['item_data']['description']; ?>
		</div>
		<?php else: ?>
		<!-- 计算结果 -->
		
		<div id="result-info" class="tab-info-item">
			<div class="calcuRule">
				<div class="calcuRule-hd">
					<div class="calcuRule-hd-wrap">
						<i class="ico txt-ico"></i>
						<span class="txt">幸运号码计算规则</span>
					</div>
				</div>
                <?php if (! $this->_var['root']['item_data']['is_topspeed']): ?>
				<div class="rule-wrap">
					<ol class="rule-list">
						<li><span class="index">1</span>商品的最后一个号码分配完毕后，将公示该分配时间点前本站全部商品的最后50个参与时间；</li>
						<li><span class="index">2</span>将这50个时间的数值进行求和（得出数值A）（每个时间按时、分、秒、毫秒的顺序组合，如20:15:25.362则为201525362）；</li>
						<li><span class="index">3</span>为保证公平公正公开，系统还会等待一小段时间，取最近下一期中国福利彩票“老时时彩”的揭晓结果（一个五位数值B）；</li>
						<li><span class="index">4</span>（数值A+数值B）除以该商品总需人次得到的余数<i class="ico ico-question" style="margin-top: -3px">
							<div class="tips-layer">
								<i class="ico small-tip-ico"></i>
								<b class="txt-red">余数：</b>指整数除法中，被除数未被除尽部分。“例如27除以6”，商数为4，余数为3。
							</div>
						</i> + 原始数 10000001，得到最终幸运号码，拥有该幸运号码者，直接获得该商品。</li>
						<li class="txt-red">注：如遇福彩中心通讯故障，无法获取上述期数的中国福利彩票“老时时彩”揭晓结果，且24小时内该期“老时时彩”揭晓结果仍未公布，则默认“老时时彩”揭晓结果为<?php echo $this->_var['default_lottery']; ?>。</li>
					</ol>
				</div>
                <?php else: ?>
                <div class="rule-wrap">
                    <ol class="rule-list">
                        <li><span class="index">1</span>商品的最后一个号码分配完毕后，将公示该分配时间点前本站全部商品的最后50个参与时间；</li>
                        <li><span class="index">2</span>将这50个时间的数值进行求和（得出数值A）（每个时间按时、分、秒、毫秒的顺序组合，如20:15:25.362则为201525362）；</li>
                        <li><span class="index">3</span>数值A除以该商品总需人次得到的余数<i class="ico ico-question" style="margin-top: -3px">
                            <div class="tips-layer">
                                <i class="ico small-tip-ico"></i>
                                <b class="txt-red">余数：</b>指整数除法中，被除数未被除尽部分。“例如27除以6”，商数为4，余数为3。
                            </div>
                        </i> + 原始数 10000001，得到最终幸运号码，拥有该幸运号码者，直接获得该商品。</li>
                        <!--<li class="txt-red">注：如遇福彩中心通讯故障，无法获取上述期数的中国福利彩票“老时时彩”揭晓结果，且24小时内该期“老时时彩”揭晓结果仍未公布，则默认“老时时彩”揭晓结果为<?php echo $this->_var['default_lottery']; ?>。</li>-->
                    </ol>
                </div>
                <?php endif; ?>
			</div>
			<table class="tab-result-list" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="time" colspan="2">夺宝时间</th>
						<th>会员帐号</th>
						<th>商品名称</th>
						<th width="70">商品期号</th>
						<th width="70">参与人次</th>
					</tr>
				</thead>
				<tbody>
					<tr class="start-row">
						<td colspan="6">截止该商品最后夺宝时间【<?php echo $this->_var['root']['item_data']['f_success_time_50']; ?>】最后50条全站参与记录</td>
					</tr>
					<?php if ($this->_var['root']['duobao_order_log']): ?>
					<?php $_from = $this->_var['root']['duobao_order_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_order_log');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_order_log']):
?>
					<tr class="calcu-row">
						<td class="day"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['duobao_order_log']['create_time'],
  'g' => 'y-m-d',
);
echo $k['name']($k['v'],$k['g']);
?></td>
						<td class="time">
							<?php echo $this->_var['duobao_order_log']['create_time_format']; ?><i class="ico ico-arrow-right"></i><b class="txt-red"><?php echo $this->_var['duobao_order_log']['fair_sn_local']; ?></b>
						</td>
						<td class="user-name">
							<div class="txt-over">
								<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['duobao_order_log']['user_id']."".""); 
?>" target="_blank"><?php echo $this->_var['duobao_order_log']['user_name']; ?></a>
							</div>
						</td>
						<td class="g-name">
							<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['duobao_order_log']['duobao_item_id']."".""); 
?>" target="_blank"><?php echo $this->_var['duobao_order_log']['name']; ?></a>
						</td>
						<td><?php echo $this->_var['duobao_order_log']['duobao_item_id']; ?></td>
						<td><?php echo $this->_var['duobao_order_log']['number']; ?>人次</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php endif; ?>
					<?php if ($this->_var['lott']): ?><?php else: ?>
					<tr class="result-row">
						<td colspan="6">
							<h4>计算结果</h4>
                            <?php if (! $this->_var['root']['item_data']['is_topspeed']): ?>
							<ol>
								<li><span class="index">1、</span>求和：<?php echo $this->_var['root']['item_data']['fair_sn_local']; ?> (上面50条参与记录的时间取值相加)</li>
								<li><span class="index">2、</span><?php if ($this->_var['root']['item_data']['fair_sn'] != '111111'): ?><?php if ($this->_var['root']['item_data']['fair_type'] == 'wy'): ?>第 <?php echo $this->_var['root']['item_data']['fair_period']; ?> 期“老时时彩”<?php endif; ?><?php endif; ?>幸运号码：<?php $_from = $this->_var['root']['item_data']['fair_sn_s']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fair_sn');if (count($_from)):
    foreach ($_from AS $this->_var['fair_sn']):
?><b class="ico ball"><?php echo $this->_var['fair_sn']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></li>
								<li><span class="index">3、</span>求余：(<?php echo $this->_var['root']['item_data']['fair_sn_local']; ?> + <?php $_from = $this->_var['root']['item_data']['fair_sn_s']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fair_sn');if (count($_from)):
    foreach ($_from AS $this->_var['fair_sn']):
?><b class="ico ball"><?php echo $this->_var['fair_sn']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								) % <?php echo $this->_var['root']['item_data']['max_buy']; ?> (商品所需人次) =
								<?php $_from = $this->_var['root']['item_data']['luck_lottery']['fixed_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fixed_values');if (count($_from)):
    foreach ($_from AS $this->_var['fixed_values']):
?><b class="square"><?php echo $this->_var['fixed_values']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								(余数)
									<i class="ico ico-question" style="margin-top: -2px">
										<div class="tips-layer">
											<i class="ico small-tip-ico"></i>
												<b class="txt-red">余数：</b>指整数除法中，被除数未被除尽部分。“例如27除以6”，商数为4，余数为3。
										</div>
									</i>
								</li>
								<li><span class="index">4、</span><?php $_from = $this->_var['root']['item_data']['luck_lottery']['fixed_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fixed_values');if (count($_from)):
    foreach ($_from AS $this->_var['fixed_values']):
?><b class="square"><?php echo $this->_var['fixed_values']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								(余数) + 100000001 =
								<?php $_from = $this->_var['root']['item_data']['luck_lottery']['lottery_sns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lottery_sns');if (count($_from)):
    foreach ($_from AS $this->_var['lottery_sns']):
?><b class="square"><?php echo $this->_var['lottery_sns']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</li>
							</ol>
                            <?php else: ?>
                            <ol>
                                <li><span class="index">1、</span>求和：<?php echo $this->_var['root']['item_data']['fair_sn_local']; ?> (上面50条参与记录的时间取值相加)</li>
                                <li><span class="index">2、</span>求余：<?php echo $this->_var['root']['item_data']['fair_sn_local']; ?>
                                     % <?php echo $this->_var['root']['item_data']['max_buy']; ?> (商品所需人次) =
                                    <?php $_from = $this->_var['root']['item_data']['luck_lottery']['fixed_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fixed_values');if (count($_from)):
    foreach ($_from AS $this->_var['fixed_values']):
?><b class="square"><?php echo $this->_var['fixed_values']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    (余数)
                                    <i class="ico ico-question" style="margin-top: -2px">
                                        <div class="tips-layer">
                                            <i class="ico small-tip-ico"></i>
                                            <b class="txt-red">余数：</b>指整数除法中，被除数未被除尽部分。“例如27除以6”，商数为4，余数为3。
                                        </div>
                                    </i>
                                </li>
                                <li><span class="index">4、</span><?php $_from = $this->_var['root']['item_data']['luck_lottery']['fixed_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fixed_values');if (count($_from)):
    foreach ($_from AS $this->_var['fixed_values']):
?><b class="square"><?php echo $this->_var['fixed_values']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    (余数) + 100000001 =
                                    <?php $_from = $this->_var['root']['item_data']['luck_lottery']['lottery_sns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lottery_sns');if (count($_from)):
    foreach ($_from AS $this->_var['lottery_sns']):
?><b class="square"><?php echo $this->_var['lottery_sns']; ?></b><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </li>
                            </ol>
                            <?php endif; ?>
							<span class="result-code">幸运号码： <?php echo $this->_var['root']['item_data']['luck_lottery']['lottery_sn']; ?></span>
						</td>
					<?php endif; ?>
					</tr>
					<?php if ($this->_var['root']['duobao_order_logs']): ?>
					<?php $_from = $this->_var['root']['duobao_order_logs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_order_logs');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_order_logs']):
?>
					<tr class="calcu-row">
						<td class="day"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['duobao_order_logs']['create_time'],
  'g' => 'y-m-d',
);
echo $k['name']($k['v'],$k['g']);
?></td>
						<td class="time">
							<?php echo $this->_var['duobao_order_logs']['create_time_format']; ?>
						</td>
						<td class="user-name">
							<div class="txt-over">
								<a href="" target="_blank"><?php echo $this->_var['duobao_order_logs']['user_name']; ?></a>
							</div>
						</td>
						<td class="g-name">
							<a href="" target="_blank"><?php echo $this->_var['duobao_order_logs']['name']; ?></a>
						</td>
						<td><?php echo $this->_var['duobao_order_logs']['duobao_item_id']; ?></td>
						<td><?php echo $this->_var['duobao_order_logs']['number']; ?>人次</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
		<!-- 夺宝参与记录 -->
		<div id="record-info" class="tab-info-item" style="min-height:300px;">
		</div>
		<!-- 晒单分享 -->
		<div id="share-info" class="tab-info-item"  style="min-height:300px;">
		</div>
		<!-- 往期夺宝 -->
		<?php if ($this->_var['lott']): ?><?php else: ?>
		<div id="history-info" class="tab-info-item" style="min-height:300px;">
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($this->_var['root']['item_data']['is_pk']): ?>
<div class="pk-box">
    <div class="mask"></div>
    <div class="start-pk">
        <div class="hd">参与PK</div>
        <div class="close-pk"></div>
        <h2 class="pk-tit" id=if"pk-title"></h2>

        <p class="pk-num">单次参与人次：<span id="span-pk-num"></span></p>

        <form autocomplete="off">
            <div class="pk-set">
                <p>本商品PK密码：</p>
                <input type="password" id="pk_password" check-login='<?php
echo parse_url_tag("u:index|user#check_login|"."".""); 
?>' placeholder="请输入密码(选填)">
            </div>
            <a href="javascript:void(0);" class="btn start-pk-btn" id="pk_submit">参与pk</a>
        </form>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['root']['item_data']['is_number_choose']): ?>
<div class="number-choose-box">
    <div class="mask"></div>
    <div class="choose-number">
        <div class="close-choose"></div>
        <div class="hd">
            <a class="can-choose-only" href="javascript:void(0);">只显示可选号码</a>
            <a class="all-choose" href="javascript:void(0);">全选</a>
            <a class="cancel-all-choose active" href="javascript:void(0);">取消全选</a>
            <a href="javascript:void(0)" class="choose-btn btn" id="confirm" check-login='<?php
echo parse_url_tag("u:index|user#check_login|"."".""); 
?>' url='<?php
echo parse_url_tag("u:index|number_choose#add_cart|"."".""); 
?>'>确定</a>
        </div>
        <ul class="clearfix number-list" id="number-list">

        </ul>
        <ul class="choose-tip">
            <li><div class="tip-ico tip-ico-can"></div><p>可选</p></li>
            <li><div class="tip-ico tip-ico-cant"></div><p>不可选</p></li>
            <li><div class="tip-ico tip-ico-ready"></div><p>已选</p></li>
        </ul>
    </div>
</div>
<?php endif; ?>
<div id="layer"><?php echo $this->fetch('inc/layer.html'); ?></div>
<?php echo $this->fetch('inc/footer.html'); ?>
