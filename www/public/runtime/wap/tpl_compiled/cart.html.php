<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/cart_index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/tb/iconfont.css";

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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/sms_verify.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/cart_index.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/cart_index.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";


?>

<?php echo $this->fetch('inc/header_title_only.html'); ?>

<script type="text/javascript">
	//减少移动端触发"Click"事件时300毫秒的时间差
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);
var jsondata = <?php echo $this->_var['jsondata']; ?>;
var cart_index = 1;
</script>

<?php endif; ?>


<div class="loading_container" id="loading_container" style="background:#fff;">
	<form name="buy_form" id="buy_form" action="<?php
echo parse_url_tag("u:index|cart#check_cart|"."".""); 
?>" method="post">
	<input type="hidden" value="<?php echo $this->_var['type']; ?>" name="type"/>
	<div class="wrap">
	    <?php if ($this->_var['data']['cart_list']): ?>
			<ul class="cart-list">
		<?php $_from = $this->_var['data']['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
				<li class="split-line" data-id="<?php echo $this->_var['item']['id']; ?>">
				  <div class="goods-img">
	          <?php if ($this->_var['item']['min_buy'] == 10 || $this->_var['item']['unit_price'] == 10): ?>
	            <div class="tenyen"></div>
	          <?php elseif ($this->_var['item']['unit_price'] == 100): ?>
	            <div class="hundredyen"></div>
	          <?php endif; ?>
				    <img src="<?php echo $this->_var['item']['deal_icon']; ?>" alt="<?php echo $this->_var['item']['deal_icon']; ?>">
				  </div>
				  <div class="txtbox">
						<div class="flex-box">
							<h2> <?php if ($this->_var['item']['is_topspeed']): ?>
                                <em style="background-color:#ff2300;padding: 1px 3px;color: #fff;font-size: 12px;">极速</em>
                                <?php endif; ?><?php echo $this->_var['item']['name']; ?></h2>
						</div>
						<p>总需:<span><?php echo $this->_var['item']['max_buy']; ?></span>人次，剩余<span class="rest"><?php echo $this->_var['item']['residue_count']; ?></span>人次</p>
					    <div class="select-bar">
					  	<p>参与人次</p>
						  	<div class="select-wrap">
							  	<div class="select">
                                    <?php if ($this->_var['item']['is_number_choose']): ?>
                                    <a href="javascript:void(0);" class="iconfont split-line-right" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe6d3;</a>
                                    <input name="num[<?php echo $this->_var['item']['id']; ?>]" readonly="readonly" class="buy_number buy-num-<?php echo $this->_var['item']['id']; ?> buy_number_js" type="tel" value="<?php echo $this->_var['item']['number']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>" />
                                    <a href="javascript:void(0);" class="iconfont split-line-left" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe6d1;</a>
                                    <em>选号区的物品无法在购物区改变数量</em>
                                    <?php else: ?>
                                    <a href="javascript:void(0);" class="iconfont split-line-right minus" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe6d3;</a>
                                    <input name="num[<?php echo $this->_var['item']['id']; ?>]" class="buy_number buy-num-<?php echo $this->_var['item']['id']; ?> buy_number_js" type="tel" value="<?php echo $this->_var['item']['number']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>" />
                                    <a href="javascript:void(0);" class="iconfont split-line-left plus" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe6d1;</a>
                                    <em>参与人次需是<?php echo $this->_var['item']['min_buy']; ?>的倍数</em>
                                    <?php endif; ?>
							  	</div>
						  	</div>
					  	<i class="iconfont del-item-btn" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe68d;</i>
					    </div>
				  </div>
				  <div class="clear"></div>
				</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	
			</ul>
	    <?php else: ?>
			<div class="null_data">
			<!--<p class="icon"><i class="iconfont">&#xe6e8;</i></p>-->
				<div class="img" style="width: 40%;margin-left: 30%;margin-top: 5%;">
					<img src="Tpl/main/images/gwc.jpg" alt="" style="width: 100%;height: 100%;">
				</div>
			<p class="message" style="color:#333;font-size:18px;margin-top: 5%;">购物车竟然是空的</p>
				<p style="margin-top:5px">再忙，也要记得买点什么犒赏自己(^_^)</p>
			</div>	
	    <?php endif; ?>
	
	    </div>
	<?php if ($this->_var['data']['cart_list']): ?>
	<div class="cart-floot">
	    <div class="cart-count-box split-line-top">
	    <?php if ($this->_var['type'] == 'free'): ?>
	        <div  class="cart-item-number">共<span class="cart-num-set"><?php echo $this->_var['data']['total_data']['cart_item_number']; ?></span>商品，总计: <span><?php echo $this->_var['data']['total_data']['total_price']; ?></span>优惠币</div>
	        <?php if ($this->_var['is_new_member'] == 0): ?>
		    	<input class="check-btn" style="background-color: #ddd;color:#fff; width:7rem" type="button" value="新用户才能参与免费购" />
		    <?php else: ?>
	    		<input class="check-btn" type="submit" value="结算" />
		    <?php endif; ?>
	    <?php endif; ?>
	    <?php if ($this->_var['type'] == ''): ?>
	        <div  class="cart-item-number">共<span class="cart-num-set"><?php echo $this->_var['data']['total_data']['cart_item_number']; ?></span>商品，总计: <span><?php echo $this->_var['data']['total_data']['total_price']; ?></span>夺宝币</div>

			<?php if ($this->_var['data']['total_data']['cart_item_number'] > 0): ?>
	        <input class="check-btn" type="submit" value="结算" />
			<?php endif; ?>
	    <?php endif; ?>
	    </div>
	</div>
	<?php endif; ?>
	</form>
</div>

<div class="like" style="background:#fff;">
	<div class="nav-gwc" style="margin-bottom:15px">
		<!-- <span>------</span> -->
		<p style="width:33%;height:1px;background: #FF7B22;float:left;margin-top:3%"></p>
		<span style="line-height:5px;">购物车帮你挑</span>
		<!-- <span>------</span> -->
		<p style="width:33%;height:1px;background: #FF7B22;float:right;margin-top:3%"></p>
	</div>	
	<div class="content">
		<?php if ($this->_var['data']['index_duobao_list']): ?>
		<!--在此处改变类名改变样式 tuan-ul-list  tuan-ul-img-->
		<ul class="tuan-ul tuan-ul-img split-line-top">
			<?php $_from = $this->_var['data']['index_duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
			<li class="tuan_li split-line">
				<a class="blw" href="<?php
echo parse_url_tag("u:index|duobao#index|"."data_id=".$this->_var['item']['id']."".""); 
?>">
				<div class="pic">
					<?php if ($this->_var['item']['min_buy'] == 10 || $this->_var['item']['unit_price'] == 10): ?>
					<div class="tenyen"></div>
					<?php endif; ?>
					<?php if ($this->_var['item']['unit_price'] == 100): ?>
					<div class="hundredyen"></div>
					<?php endif; ?>
					<img src="<?php echo $this->_var['item']['icon']; ?>" lazy="true" />
				</div>
				</a>
				<div class="info">
					<div class="info-tit">
						<?php if ($this->_var['item']['is_topspeed']): ?>
						<em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
						<?php endif; ?>
						<?php echo $this->_var['item']['name']; ?>
					</div>

					<div class="progress-box">
						<div class="left-box">
							<progress max="<?php echo $this->_var['item']['max_buy']; ?>" value="<?php echo $this->_var['item']['current_buy']; ?>"></progress>
							<div class="fl">
								<p class="txt-red"><?php echo $this->_var['item']['current_buy']; ?></p>
								<p>已参与人次</p>
							</div>
							<div class="fr">
								<p class="txt-red"><?php echo $this->_var['item']['surplus_buy']; ?></p>
								<p>剩余人次</p>
							</div>
						</div>
						<!--<a data-id="<?php echo $this->_var['item']['id']; ?>" class="right-box add_cart_item" unit_price="<?php echo $this->_var['item']['unit_price']; ?>" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data_id="<?php echo $this->_var['item']['id']; ?>" rel="<?php echo $this->_var['item']['icon']; ?>"><i class="iconfont">&#xe658;</i></a>-->
					</div>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>

		<?php if ($this->_var['pages']): ?>
		<a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>" class="more_duobao page-load">查看更多</a>
		<?php endif; ?>
		<?php else: ?>
		<div class="null_data">
			<p class="icon"><i class="iconfont">&#xe6e8;</i></p>
			<p class="message">暂无数据</p>
		</div>
		<?php endif; ?>
	</div>

</div>

<style>
	.nav-gwc{
		text-align: center;
		color: #FF5722;
		font-size: 18px;
	}
	.goods{
		width:100%;
		background:#fff;
	}
</style>

<script>
	$(function(){
		$('.menu_box>li').eq(2).children('a').children('p').children('img').attr('src','./wap/Tpl/main/images/menu/gour.png ');
	    $(".check-btn").click(function(){
	        var balance = <?php echo $this->_var['balance']; ?>;
	        if(balance<1){
	            alert('余额不足，请先充值');
                location.href = "<?php
echo parse_url_tag("u:index\|uc_charge\|"."".""); 
?>";
			}
		});
	});
</script>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/app_input_num.html'); ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>
