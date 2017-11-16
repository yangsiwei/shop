<!--用户信息部分-->
<script src="./wap/Tpl/main/js/tbb/iconfont.js"></script>
<style type="text/css">
	.icon {
		width: 1em; height: 1em;
		vertical-align: -0.15em;
		fill: currentColor;
		overflow: hidden;
	}
	.uc-money-box {
		position: relative;
		width: 100%;
		height:2rem;
		background: linear-gradient(to right, #ff5c40 0%, #ff755b 30%,#ff127a 100%);
		border-radius:0 0 50% 50%;
	}
	.goods_abbr{
		background: #fff;
	}
</style>
<div class="goods_abbr">
	<div class="person_account" style="background: linear-gradient(to right, #ff5c40 0%, #ff755b 30%,#ff127a 100%);">
		<div class="account_info" style="margin-top:3em;width:100%;">
			<div class="file_img">
				<div class="item-add" style="width:100%;height:100%;border: 1px solid #ddd;float:left">
					<img id="fileimg" src="<?php echo $this->_var['data']['user_logo']; ?>">
					<input class="file-btn" id="file-btn" type="file" capture="camera">
				</div>
			</div>
			<div class="name" style="width:83%;">
				<a href="<?php
echo parse_url_tag("u:index|uc_account#index|"."".""); 
?>" style="float:left;">
				<h1 style="color:#ffffff;">
					<span style="float:left;font-size: 22px;"><?php echo $this->_var['data']['user_name']; ?></span>
					<div class="fx_level" style="width: 2em;height: 1em;float: left;background: #f24759;margin-right: 1em;border-radius: 30px;margin-left: .2em;margin-top: .1em;text-align: center;line-height:19px;">
						<span style="font-size:10px;"><?php echo $this->_var['fx_level']; ?></span>
					</div>
				</h1>
				</a>
				<!--<?php if ($this->_var['jingxiaoshang']): ?>-->
				<!--<span style="position:absolute;color:#fff;top:23%;left:50%;font-size:20px;" class="tobeDealers">成为经销商</span>-->
				<!--<?php endif; ?>-->
				<br>
				<p> <i style="border: 1px solid #ffd496;border-radius: 100px;text-align: center;">
					<span style="margin-left:2%;color:#fff;">ID：</span>
					<span><?php echo $this->_var['data']['uid']; ?></span>
				</i>&nbsp;&nbsp;

					<i style="border: 1px solid #cc617b;border-radius: 100px;text-align: center;">
						<span style="margin-left:2%;color:#fff;">等级：</span>
						<span>LV<?php echo $this->_var['data']['level_id']; ?></span>
					</i>&nbsp;&nbsp;
					<i style="border: 1px solid #e20d41;border-radius: 100px;text-align: center;">
						<span style="margin-left:2%;color:#fff;">经验值：</span>
						<span><?php echo $this->_var['data']['total_use_money']; ?></span>
					</i>
					<!--<a class="uc_explain">说明</a>-->
				</p>
			</div>
			<div class="shezhi" style="position: absolute;top: 19%;right: 10%;">
				<a href="<?php
echo parse_url_tag("u:index|uc_setting|"."".""); 
?>">
				<span style="font-size: 26px;">
						<svg class="icon" >
						  <use xlink:href="#icon-shezhi1"></use>
						</svg>
					</span>
				</a>
				<a href="<?php
echo parse_url_tag("u:index|uc_msg|"."".""); 
?>">
				<span style="font-size: 26px;position: relative;">
						<svg class="icon" >
						  <use xlink:href="#icon-xiaoxi3"></use>
						</svg>
						<?php if ($this->_var['data']['msg_count']): ?>
						<span style="display: block;
									background: #ed2c3f;
									width: 16px;
									height: 16px;
									font-size: 14px;
									text-align: center;
									color: #fff;
									border-radius: 20px;
									position: absolute;
									top: -2px;
	    							left: 12px;"><i><?php echo $this->_var['data']['msg_count']; ?></i>
						</span>
						<?php endif; ?>
					</span>
				</a>
			</div>
		</div>
	</div>
	<div class="uc-money-box"></div>
	<!--<div class="explain_items" id="coupons_explain" style="display:none;">-->
	<!--<ul>-->
	<!--<li class="ui_clr">1.余额为本金加可用充值赠送加推广奖加管理奖；</li>-->
	<!--<li class="ui_clr">2.可用充值赠送<?php echo $this->_var['user_info']['can_use_give_money']; ?>夺宝币；</li>-->
	<!--<li class="ui_clr">2.推广奖<?php echo $this->_var['user_info']['fx_money']; ?>夺宝币；</li>-->
	<!--<li class="ui_clr">2.管理奖<?php echo $this->_var['user_info']['admin_money']; ?>夺宝币；</li>-->
	<!--</ul>-->
	<!--</div>-->

</div>
<script>

</script>
<!--end 用户信息部分-->