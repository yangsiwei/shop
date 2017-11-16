<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_index.css";

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
<?php echo $this->fetch('inc/header_title_balance.html'); ?>
<div style="height:30px;"></div>
<div class="uc_money_body">
	<div class="null_data" style="height:200px;weight:160px;text-align:justify;font-size:12px;margin-left:35%;">
		<p class="icon" style="margin-left:8%"><img src="http://122.114.94.153/wap/Tpl/main/images/ico_withdrawals.png" height="80" width="80"></p>
		<div></div>
		<p class="message" style="margin-top:20px;"><img src="http://122.114.94.153/wap/Tpl/main/images/yellow.png" height="14px" width="14px">&nbsp;<span style="vertical-align:middle;">&nbsp;账 户 余 额 ￥<?php echo $this->_var['data']['user_money']; ?></span></p>
		<p class="message"><img src="http://122.114.94.153/wap/Tpl/main/images/pik.png" height="14px" width="14px">&nbsp;<span style="vertical-align:middle;">&nbsp;赠 送 金 额 ￥<?php echo $this->_var['data']['give_money']; ?></span></p>
		<p class="message"><img src="http://122.114.94.153/wap/Tpl/main/images/lan.png" height="14px" width="14px">&nbsp;&nbsp;<span style="vertical-align:middle;">推 广 奖  励 ￥<?php echo $this->_var['data']['fx_money']; ?></span></p>
		<p class="message"><img src="http://122.114.94.153/wap/Tpl/main/images/blue.png" height="14px" width="14px">&nbsp;&nbsp;<span style="vertical-align:middle;">管 理 奖 励 ￥<?php echo $this->_var['data']['admin_money']; ?></span></p>
		<!--<p class="money"></p>-->
		<!--<div style="padding:2px;"></div>-->
		<!--<p class="message" style="font-size:13px;color:red;">-->
			<!--<img src="http://122.114.94.153/wap/Tpl/main/images/yellow.png" height="14px" width="14px">&nbsp;&nbsp;<span style="font-size:13px;color:red;">提 现 余 额：<?php echo $this->_var['data']['money']; ?>夺宝币</span> </br>-->
			<!--<div style="padding:2px;"></div>-->
			<!--<img src="http://122.114.94.153/wap/Tpl/main/images/pik.png" height="14px" width="14px">&nbsp;&nbsp;<span style="font-size:13px;color:red;">赠 送 金 额：<?php echo $this->_var['data']['can_use_give_money']; ?>夺宝币</span></br>-->
		<!--<div style="padding:2px;"></div>-->
			<!--<img src="http://122.114.94.153/wap/Tpl/main/images/lan.png" height="14px" width="14px">&nbsp;&nbsp;<span style="font-size:13px;color:red;">推 广 奖 励：<?php echo $this->_var['data']['fx_money']; ?>夺宝币</span></br>-->
		<!--<div style="padding:2px;"></div>-->
			<!--<img src="http://122.114.94.153/wap/Tpl/main/images/blue.png" height="14px" width="14px">&nbsp;&nbsp;<span style="font-size:13px;color:red;">管 理 奖 励：<?php echo $this->_var['data']['admin_money']; ?>夺宝币</span>-->
		<!--</p>-->
	</div>
	<!--<div style="float：left;height:200px;weight:80px;margin-top:;text-align:center;"><img src="http://122.114.94.153/wap/Tpl/main/images/ico_withdrawals.png" height="100" width="100"></div>-->
	<div class="subbox" style="margin-top:68px;width:68%;margin-left:16%;">
		<!--<a href="/wap/index.php?ctl=uc_money_cash&act=withdraw_bank_list&show_prog=1" class="buy-btn" style="color:#fff;">提现</a>-->
		<a href="<?php
echo parse_url_tag("u:index|uc_charge|"."".""); 
?>" class="buy-btn" style="color:#fff;">充值</a>
	</div>
</div>
<div class="explain_items" id="coupons_explain" style="text-align: justify;border: 3px solid red;width:90% ;font-size:12px;margin:auto;padding:10px;margin-top:30px;" >
	<ul>
		<li class="ui_clr"><p style="color:red;font-size:16px;font-weight: 900;">充值赠送：</p>首冲100送20现金红包 充1000送200奖励 活动期再次充值,LV1-LV4会员奖励6%  LV5-LV7奖励7%  LV8-LV10奖励8% 。<!-- <p style="color:red;font-size:16px;font-weight: 900;">推广奖励：</p>白银推广商直推会员百分之五奖励，黄金推广商直推会员5%充值奖励，团队二级会员3%充值奖励，钻石推广商直推会员5%充值奖励，团队二级会员3%充值奖励，三级会员5‰充值奖励。<p style="color:red;font-size:16px;font-weight: 900;">管理奖励：</p>钻石推广商除了三级奖励外，可拿三级之后所有会员充值金额的5‰充值奖励。</li> -->
		<br/>
		<div style="height:15px;"></div>
		<li><p style="text-align:center;">本网站拥有最终解释权,详情请咨询客服</p></li>
	</ul>
</div>
<?php echo $this->fetch('inc/no_footer.html'); ?>