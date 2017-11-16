<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/user_login.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_withdraw.css";

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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_money_withdraw.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_money_withdraw.js";

?>
<style type="text/css">
	.withd_money{
		font-size:12px;
	}
	.span{
		margin-left:60px;
	}
	.i_span{
		margin-left:42px;
		width:20px;
		height:20px;
		border:1px solid #D01E41;
		text-align:center;
		border-radius:10px;
		background:#D01E41;
		color:#fff;
	}
	.tishi{
		width:92%;
		margin:auto;
	}
	.close{
		position:absolute;
		left:10%;
	}
</style>
<?php echo $this->fetch('inc/header_getpassword.html'); ?>
<script>
    var withdraw_ajax_url = "<?php
echo parse_url_tag("u:index|uc_money_cash|"."del_user_bank".""); 
?>";
</script>
<style>

</style>
<div class="wrap">
	<div class="content">
		<div id="withdraw">
			<ul class="address-input">
				<?php if ($this->_var['data']['step'] == 1): ?>
				<form action="<?php
echo parse_url_tag("u:index|uc_money_cash#do_withdraw|"."".""); 
?>" method="post" name="withdraw">
				<li class="split-line">
					<span>提现至</span>
					<input name="bank_name" class="ui-textbox" readonly="readonly" value=""  />
					<input type="hidden"  value="" name="bank_id" />
				</li>
				<li class="split-line">
					<span>提现类</span>
					<select style="margin-left:5%;" name="withdraw_method" id="withdraw_method_ss">
						<option value="">请选择提现款项</option>
						<option value="money">余额</option>
						<option value="give_money">充值赠送</option>
						<option value="fx_money">推广奖</option>
						<option value="admin_money">管理奖</option>
					</select>


					<!--<input class="ui-textbox" value="" type="number" name="money" placeholder="当前可提现的余额<?php echo $this->_var['data']['money']; ?>夺宝币"  />-->
				</li>
				<li class="split-line" id="hint_i" style="display:none;">
					<div class="span">
						<!--<span class="withd_money">可用金额&nbsp;<b id="money_method"     style="width:60px;height:30px;">0</b>&nbsp;&nbsp;夺宝币 </span><br />-->
						<!--<span class="withd_money">冻结金额&nbsp;<b id="money_method_no"  style="width:60px;height:30px;">0</b>&nbsp;&nbsp;夺宝币 </span>-->
						<table style="width:160px;border:1px;font-size:12px;">
							<tr>
								<td>可用金额</td>
								<td class="withd_money " id="money_method" style="width:60px;text-align:center;">0</td>
								<td>夺宝币</td>
							</tr>
							<tr>
								<td>冻结金额</td>
								<td class="withd_money" id="money_method_no" style="width:60px;text-align:center;">0</td>
								<td>夺宝币</td>
							</tr>
						</table>
					</div>
					<div class="i_span">
						<i>?</i>
					</div>
					<!--<div class="close">-->
						<!--<i style="font-size:24px;color:#666;">X</i>-->
					<!--</div>-->
				</li>
				<li class="split-line" style="height:180px;font-size:14px;display:none;" id="hint_t">
					<div class="tishi">
						<p class="money_tishi" style="font-size:12px;">1.余额提现：<br />
							<span>a.非花呗、信用卡充值资金消费余额， 7日后申请，24小时内到账，每日限提RMB5000，每月限提RMB10万；</span>
							<br>
							<span>b.花呗、信用卡充值资金，7日后申请提现，第一次提现金额低于2000则免费，第二次收取1%手续费，第三次开始收取百分之5%手续费，24小时内到账；</span>
						</p>
						<p class="give_money_tishi" style="font-size:12px;">2.充值赠送：首冲100送20现金红包，联系在线客服领取。非推广商以及花呗、信用卡充值赠送只能消费不能提现；白银推广商：月提现总额5000；黄金推广商月提现总额1万；钻石推广商月提现总额5万；七日后可申请提现；<br />
						</p>
						<p class="fx_money_tishi" style="font-size:12px;">3.奖励满七天后的第一个工作日可申请提现，500起提，收取2%手续费。24小时内到账；
						</p>
						<p class="admin_money_tishi" style="font-size:12px;">4.管理奖：<span>奖励满七天后的第一个工作日可申请提现，2000起提，收取2%手续费，24小时内到账。</span><br/><span>注：若发现违反相关金融规定或法律，经核实，本公司有权冻结账户（如：信用卡、花呗套现；洗黑钱；大额资金非正常急进急出），其他未尽事宜以国家法律及相关规定为依据。</span></p>
					</div>
				</li>
				<li class="split-line">
					<span>金<em></em>额</span>
					<input class="ui-textbox" type="number" name="money" placeholder="请输入提现金额" id="Withdrawals" />
				</li>
				<li class="split-line">
					<span>密<em></em>码</span>
					<input class="ui-textbox" value="" name="pwd" type="password"  placeholder="请再次输入登录密码" />
				</li>
				<div class="blank"></div>

				<?php $_from = $this->_var['data']['bank_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
				<li class="split-line bank" >
					<label><?php echo $this->_var['item']['bank_name']; ?><span  bank_name="<?php echo $this->_var['item']['bank_name']; ?>" rel="<?php echo $this->_var['item']['id']; ?>"  <?php if ($this->_var['key'] == 0): ?>class="checked"<?php endif; ?>></span></label>
					<a class="iconfont del_bank_btn" rel="<?php echo $this->_var['item']['id']; ?>">&#xe64f;</a>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<div class="blank"></div>
				<li class="split-line add_card" >
					<a href="<?php
echo parse_url_tag("u:index|uc_money_cash#add_card|"."".""); 
?>">添加银行卡</a>
				</li>
				<div class="blank"></div>
				<div class="subbox">
					<input type="submit" class="sub goahead" value="确认提现">
				</div>
				</form>
				<?php elseif ($this->_var['data']['step'] == 2): ?>
				<form action="<?php
echo parse_url_tag("u:index|uc_money_cash#do_bind_bank|"."".""); 
?>" method="post" name="add_card">
				<li class="split-line">
					<span>卡<em></em>号</span>
					<input  name="bank_account" value="" class="ui-textbox" placeholder="请输入银行卡卡号" />
				</li>
				<li class="split-line">
					<span>开户行</span>
					<input  name="bank_name" value="" class="ui-textbox" placeholder="具体到支行名称-可填写地级市" />
				</li>
				<li class="split-line">
					<span>户<em></em>名</span>
					<input  name="bank_user" value="" class="ui-textbox" holder="请输入开户银行真实姓名" />
				</li>
				<!--<li>-->
					<!--<span>验证码</span>-->
					<!--<input class="ui-textbox ph_verify" id="sms_verify" name="sms_verify" holder="请输入验证码" />-->
					<!--<button class="btn_phone" type="Button"  id="sms_btn"  lesstime="<?php echo $this->_var['sms_lesstime']; ?>" account="1" >获取验证码</button>-->
				<!--</li>-->
				<div class="subbox">
					<input type="submit" class="sub" value="提交">
				</div>
				</form>

				<?php endif; ?>


			</ul>
		</div>
	</div>
</div>
<!-- <script src="https://cdn.bootcss.com/jquery/2.2.2/jquery.min.js"></script> -->
<script>
    $(function(){
        //在切换提现款项时给提示
        $("#withdraw_method_ss").change(function(){
            var method = $("#withdraw_method_ss").val();
            $("#hint_i").show();
            $.ajax({
                type: "POST",
                url: '<?php
echo parse_url_tag("u:index|uc_money_cash#method_ajax|"."".""); 
?>',
                data: "method="+method,
                dataType: "json",
                success: function(data){
                $("#money_method").html(data.money);
                $("#money_method_no").html(data.money_no);
     //            	var sum = $("#Withdrawals").val();
     //            	var money = $("#Withdrawals").val()/50;
					// var ex = /^\d+$/;
					// if(sum < data.money){
					// 	alert("余额不足");
					
					// 	if(!ex.test(money) ) {
					// 		$("#Withdrawals").val("");
					// 		alert("提现要是五十的倍数");
					// 		$("#Withdrawals").focus();
					// 	}
					// }
            	}
        	});


            if(method == 'money'){
                $(".money_tishi").show().nextAll().hide();
            }
            if(method == 'give_money'){
                $(".give_money_tishi").show().nextAll().hide();
                $(".give_money_tishi").show().prevAll().hide();
            }
            if(method == 'fx_money'){
                $(".fx_money_tishi").show().nextAll().hide();
                $(".fx_money_tishi").show().prevAll().hide();
            }

            if(method == 'admin_money'){
                $(".admin_money_tishi").show().nextAll().hide();
                $(".admin_money_tishi").show().prevAll().hide();
            }

        });

        //对提现规则进行解释
        $(".i_span").click(function(){
            if($("#hint_t").is(':hidden')){//如果当前隐藏
                $("#hint_t").show('1');//那么就显示div
            }else{//否则
                $("#hint_t").hide('1');//就隐藏div
            }
        });



        //点击X隐藏提示和规则
        $(".close").click(function(){
            $("#hint_i").hide();
            $('#hint_t').hide('1');
        });
		//提现要是五十的倍数
		// $("#Withdrawals").blur(function(){
		// var money = $("#Withdrawals").val()/50;
		// var ex = /^\d+$/;
		// 	if(!ex.test(money) ) {
		// 		alert("提现要是五十的倍数");
		// 		$("#Withdrawals").val("");
				
		// 		$("#Withdrawals").focus();
		// 	}
		// });
    });
</script>
<?php echo $this->fetch('inc/sms_verify_code.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>