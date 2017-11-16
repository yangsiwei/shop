<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/goods_item.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobaos_cate.css";
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duobaos_1.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duobaos_1.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duobaos.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duobaos.js";

?>
<?php echo $this->fetch('inc/header.html'); ?>

<div class="wrap_full_w clearfix sub_nav">
<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>">首页</a> > <a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>">全部商品</a><?php if ($this->_var['cate_info']): ?> > <a href="<?php
echo parse_url_tag("u:index|duobaos|"."id=".$this->_var['cate_info']['id']."".""); 
?>"><?php echo $this->_var['cate_info']['name']; ?></a><?php endif; ?>
</div>

<?php if ($this->_var['cate_list']): ?>

<div class="wrap_full_w clearfix">
    <div class="cate-title">
        <h1>
        <a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>">所有商品</a><span class="subtitle" >（共 <b class="standard" ><?php echo $this->_var['data']['count']; ?></b> 件相关商品）</span>
        </h1>
    </div>
    <div class="cate-list-wrap" style="background-color: rgba(245, 245, 245, 0.58);">
         <ul class="cate-list">
        <?php $_from = $this->_var['cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['v']):
?>
            <li class="cate-item <?php if ($this->_var['key'] % 8 == 7): ?>last<?php endif; ?>">
                <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=".$this->_var['data']['order']."&d=".$this->_var['data']['dir']."&id=".$this->_var['v']['id']."".""); 
?>">
                <span class="dxys">
                <i class="diyfont dxys1 txt-red" ><?php echo $this->_var['v']['iconfont']; ?></i>
                <i class="diyfont dxys2 txt-red" ><?php echo $this->_var['v']['iconfont']; ?></i>
                </span>
                <span class="name <?php if ($this->_var['v']['id'] == $this->_var['data']['id']): ?>current<?php endif; ?>"><?php echo $this->_var['v']['name']; ?></span>
                </a>
            </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['data']['keyword']): ?>
<div class="wrap_full_w keyword_row">
商品搜索 - <span>“<?php echo $this->_var['data']['keyword']; ?>”</span>
</div>
<?php else: ?>
<div class="wrap_full_w">
    <div class="order-list-wrap">
        <h3>排序：</h3>
        <ul class="ordered_List">
        <li class="<?php if ('hot' == $this->_var['data']['order'] || ! $this->_var['data']['order']): ?>scurrent<?php endif; ?>">
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=hot&id=".$this->_var['data']['id']."".""); 
?>">
            人气搜索
            </a>
        </li>
        <li class="<?php if ('sort' == $this->_var['data']['order']): ?>scurrent<?php endif; ?>">
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=sort&id=".$this->_var['data']['id']."".""); 
?>">
            平台推荐
            </a>
        </li>
        <li class="<?php if ('less' == $this->_var['data']['order']): ?>scurrent<?php endif; ?>"   >
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=less&id=".$this->_var['data']['id']."".""); 
?>">
            剩余次数
            </a>
        </li>
        <li class="<?php if ('new' == $this->_var['data']['order']): ?>scurrent<?php endif; ?>"  >
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=new&id=".$this->_var['data']['id']."".""); 
?>">
            最新商品
        </a>
        </li>
        <li class="<?php if (! $this->_var['data']['dir'] && ( $this->_var['data']['order'] == 'max_buy' )): ?>scurrent<?php endif; ?>"  >
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=max_buy&id=".$this->_var['data']['id']."".""); 
?>">
            总需人数 <i class="ico ico-arrow-sort ico-arrow-sort-gray-down"></i>
            </a>
        </li>
        <li class="<?php if ($this->_var['data']['dir']): ?>scurrent<?php endif; ?>"  >
            <a href="<?php
echo parse_url_tag("u:index|duobaos|"."t=max_buy&d=1&id=".$this->_var['data']['id']."".""); 
?>" >
            总需人数 <i class="ico ico-arrow-sort ico-arrow-sort-gray-up"></i>
            </a>
        </li>
        </ul>
    </div>
</div>
<?php endif; ?>
<div class="wrap_full_w">
    <div class="cate-goods-list-wrap">
        <?php if ($this->_var['list']): ?>
        <ul class="cate-goods-list ui-list" width="294" pin_col_init_width="0" wSpan="8" hSpan="10">
            <?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'cart_item_group');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['cart_item_group']):
?>
			<?php if ($this->_var['cart_item_group']): ?>
				<li class="goods ui-item">
				<?php if ($this->_var['cart_item_group']['min_buy'] == 10): ?>
				<div class="ico logo-box ten-logo-box"></div>
				<?php endif; ?>
				<?php if ($this->_var['cart_item_group']['unit_price'] == 100): ?>
				<div class="ico logo-box hundred-logo-box"></div>
				<?php endif; ?>
				<div class="blank0"></div>
					<div class="goods-wrap">
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['cart_item_group']['id']."".""); 
?>" class="imgbox" title="<?php echo $this->_var['cart_item_group']['name']; ?>">
							<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['cart_item_group']['icon'],
  'w' => '200',
  'h' => '200',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>">
						</a>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['cart_item_group']['id']."".""); 
?>" class="goods-title" title="<?php echo $this->_var['cart_item_group']['name']; ?>">
                        <?php if ($this->_var['cart_item_group']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                        <?php endif; ?><?php echo $this->_var['cart_item_group']['name']; ?>
						</a>
						<div class="p-price">
							总需：<?php echo $this->_var['cart_item_group']['max_buy']; ?>人次
						</div>
						<!-- 进度条 -->
						<div class="progressBar" title="<?php echo $this->_var['cart_item_group']['progress']; ?>%">
						<!-- 进度条外层 -->
							<div class="progress">
							<!-- 进度条内层 -->
								<div class="progress-bar" style="width:<?php echo $this->_var['cart_item_group']['progress']; ?>%"></div>
							</div>
						</div>
						<ul class="person-info clearfix">
							<li class="f_l">
								<p class="num"><?php echo $this->_var['cart_item_group']['current_buy']; ?></p>
								<p class="info">已参与人次</p>
							</li>
							<li class="f_r tar">
								<p class="num"><?php echo $this->_var['cart_item_group']['surplus_buy']; ?></p>
								<p class="info">剩余人次</p>
							</li>
						</ul>
						<div class="buy-info">
							我要参与：<div class="goods-number">
							<!-- 加减商品数 -->
								<div class="number-box buy-num-count">
								<!-- 减号 -->
									<a href="javascript:void(0);" class="num-btn num-btn-min">-</a>
								<!-- 输入框 -->
									<input type="text" value="<?php echo $this->_var['cart_item_group']['min_buy']; ?>" class="num-input" min_buy="<?php echo $this->_var['cart_item_group']['min_buy']; ?>" init_num="1" name="num">
								<!-- 加号 -->
									<a href="javascript:void(0);" surplus-buy="<?php echo $this->_var['cart_item_group']['surplus_buy']; ?>" class="num-btn num-btn-plus">+</a>
								</div>
							</div>人次
							<div class="btn-box">
								<div class="btn cate-duobao-now" buy_num="<?php echo $this->_var['cart_item_group']['min_buy']; ?>" data_id="<?php echo $this->_var['cart_item_group']['id']; ?>" onclick="add_cart_duoabos(this,1)">立即夺宝</div>
								<div class="btn add-to-list" buy_num="<?php echo $this->_var['cart_item_group']['min_buy']; ?>" data_id="<?php echo $this->_var['cart_item_group']['id']; ?>" onclick="add_cart_duoabos(this,0)">加入清单</div>
							</div>
						</div>
					</div>
				</li>
			<?php else: ?>
				<li class="goods ui-item not">
				</li>
			<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <div class="pages"><?php echo $this->_var['pages']; ?></div>
    </div>
    <?php else: ?>
    <div class="empty" style="padding-top:20px;">
        <p class="status-empty" style="margin-top:0px;margin-bottom:20px;padding:110px;height:75px;line-height:75px;text-align:center;color:@shadow_color;font-size:17px;border:1px solid #ddd;">
            <i class="littleU littleU-cry"></i>
            &nbsp;&nbsp;没有找到有关<?php if ($this->_var['data']['keyword']): ?>“<span class="txt-red" style="color:@main_color;"><?php echo $this->_var['data']['keyword']; ?></span>”的<?php endif; ?>商品哦~
        </p>
    </div>
    <?php endif; ?>
</div>
<script>
    $(".cate-item").hover(function() {
        $(this).find('.dxys1').addClass('cate-hovered');
        $(this).find('.name').addClass('txt-red')
    }, function() {
        $(this).find('.dxys1').removeClass('cate-hovered');
        $(this).find('.name').removeClass('txt-red')
    });
</script>
<?php echo $this->fetch('inc/footer.html'); ?>