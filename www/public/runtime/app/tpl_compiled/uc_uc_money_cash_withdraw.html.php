<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_cash_withdraw.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/plupload.full.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_order.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_order.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_money_cash_withdraw.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_money_cash_withdraw.js";

?>
<?php echo $this->fetch('inc/header.html'); ?>

<script>
    var withdraw_ajax_url = "<?php
echo parse_url_tag("u:index|uc_money_cash|"."del_user_bank".""); 
?>";
</script>

<style>
	.tishi{
		width:340px;
		height:335px;
		position:absolute;
		top:255px;
		right:20px;
		border:1px solid #F6E0AF;
		background:#FFFFEB;
	}
</style>
<div class="blank20"></div>

<div class="<?php 
$k = array (
  'name' => 'load_wrap',
  't' => $this->_var['wrap_type'],
);
echo $k['name']($k['t']);
?> clearfix">
	<div class="side_nav f_l ">
		<?php echo $this->fetch('inc/uc_nav_list.html'); ?>
	</div>
	<div class="right_box">
		<ul class="web-map clearfix">
			<li>当前位置：</li>
			<li><a href="<?php
echo parse_url_tag("u:index|uc_center|"."".""); 
?>">个人中心</a> ></li>
			<li class="txt-red">我的资产</li>
		</ul>
		<div class="main_box uc_info_box" style="position:relative;">
			<div class="main_roll f_l" id="main_roll">

			<ul class="roll">
			<?php echo show_adv("slide","<li>__ADV_CODE__</li>","1000","96");  ?>	
			</ul>
		
			</div>

			<!-- 资产标题 -->
			<div class="info_box">
				<div class="blank20"></div>
				<h3>我的资产信息</h3>
				<div class="blank10"></div>
				<div class="bg_box growth_content">
					
					<div class="info_items">
						<ul>
							<li>
							<label>当前的余额：</label><span class="main_color" ><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_info']['money'],
);
echo $k['name']($k['v']);
?>&emsp;</span>
							&nbsp;&nbsp;
							<label>当前的管理奖：</label><span class="main_color" ><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_info']['admin_money'],
);
echo $k['name']($k['v']);
?>&emsp;</span>
							&nbsp;&nbsp;
							<label>当前的推广奖：</label><span class="main_color" ><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_info']['fx_money'],
);
echo $k['name']($k['v']);
?>&emsp;</span>
							&nbsp;&nbsp;
							<label>当前可提现充值赠送：</label><span class="main_color" ><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_info']['can_use_give_money'],
);
echo $k['name']($k['v']);
?>&emsp;</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			
			<div id="withdraw">
		
				<form name="withdraw_form" action="<?php
echo parse_url_tag("u:index|uc_money_cash#withdraw_done|"."".""); 
?>" method="post" />	
				<div class="info_table">
				<table>
					<tr>
						<td class="withdraw">
							开户行名称
						</td>
						<td class="withcontent">
                            <input type="text" name="bank_name" class="ui-textbox" holder="请输入开户行全称" />
                            <?php if ($this->_var['user_bank_list']): ?>
                            <div class="bank_list_btn">已有银行卡<i class="iconfont">&#xe610;</i>
                                <div class="bank_list">
                                     
                                    <?php $_from = $this->_var['user_bank_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
                                    <div class="bank_item bank_item_btn" rel="<?php echo $this->_var['row']['id']; ?>" data-bank-name="<?php echo $this->_var['row']['bank_name']; ?>" data-bank-account="<?php echo $this->_var['row']['bank_account']; ?>" data-bank-user="<?php echo $this->_var['row']['bank_user']; ?>"  title="<?php echo $this->_var['row']['bank_user']; ?> <?php echo $this->_var['row']['show_bank_name']; ?>">
                                        <?php echo $this->_var['row']['show_bank_name']; ?>
                                        <a class="iconfont del_bank_btn" rel="<?php echo $this->_var['row']['id']; ?>">&#xe619;</a>
                                    </div>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    
                                   <div class="bank_item " >
                                         <a href="javascript:void(0);" class="new_bank_btn" >使用新的银行卡</a>
                                     </div>
                                </div>
                            </div>
                            <?php endif; ?>
						</td>
					</tr>
					<tr>
						<td class="withdraw">
							开户行账号
						</td>
						<td class="withcontent">
							<input type="text" name="bank_account" class="ui-textbox" holder="请输入开户行账号" />
						</td>
					</tr>
					<tr>
						<td class="withdraw">
							真实姓名
						</td>
						<td class="withcontent">
							<input type="text" name="bank_user" class="ui-textbox" holder="请输入开户人真实姓名" rel="<?php echo $this->_var['real_name']; ?>" <?php if ($this->_var['real_name']): ?>value="<?php echo $this->_var['real_name']; ?>"  readonly="readonly"<?php endif; ?>/>
						</td>
					</tr>
					<tr>
						<td class="withdraw">
							提现款项
						</td>
						<td class="withcontent">
							<select style="width:321px;height:37px;border:1px solid #ababab" name="withdraw_method">
								<option value="">请选择提现款项</option>
								<option value="money">余额</option>
								<option value="give_money">充值赠送</option>
								<option value="fx_money">推广奖</option>
								<option value="admin_money">管理奖</option>
							</select>
							<!-- <input type="text" name="money" class="ui-textbox" holder="请输入提现金额" /> -->
						</td>
					</tr>

					<tr>
						<td class="withdraw">
							提现金额
						</td>
						<td class="withcontent">
							<input type="text" name="money" class="ui-textbox" holder="请输入提现金额" />
						</td>
					</tr>
					<?php if (app_conf ( "SMS_ON" ) == 1): ?>
					<tr <?php if ($this->_var['sms_ipcount'] > 1): ?>style="display:table-row"<?php endif; ?> class="ph_img_verify">
						<td class="withdraw">
							图片验证码
						</td>
						<td class="withcontent">
							<input type="text" name="verify_code" style="width:150px;" class="ui-textbox img_verify f_l" holder="请输入图片文字" />
							<img src="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" class="verify f_l" style="padding:8px 0 0 5px; cursor:pointer;" rel="<?php echo $this->_var['APP_ROOT']; ?>/verify.php" />
							<a href="javascript:void(0);" class="refresh_verify f_l" style="padding:10px 0 0 5px; cursor:pointer;">看不清楚？换一张！</a>
						</td>
					</tr>	
					<tr>
						<td class="withdraw">
							请输入验证码
						</td>
						<td class="withcontent">
							<input class="ui-textbox f_l ph_verify" id="sms_verify" name="sms_verify" holder="请输入验证码" />
							<button class="ui-button f_l light ph_verify_btn" rel="light" lesstime="<?php echo $this->_var['sms_lesstime']; ?>" type="button">发送验证码</button>
						</td>
					</tr>
										
					<?php endif; ?>
                                            <tr id="is_bind_box">
						<td class="withdraw">
							&nbsp;
						</td>
						<td class="withcontent">
							<label class="ui-checkbox" rel="common_cbo"><input type="checkbox" name="is_bind" value="1" />是否绑定</label>
						</td>
					</tr>
					<tr>		
						<td colspan=2>
                            <input type="hidden" name="user_bank_id" value=""/>
                            <button class="ui-button orange" rel="orange" type="submit">提交申请</button>
                        </td>
					</tr>
				</table>
				</div>
				</form>					
	
			</div><!--end form-->

			<div class="tishi">
				<p><b>1.余额提现：</b><span>(工作日9:00-17:00)一、 非花呗、信用卡充值资金消费余额， 7日后申请，2小时内到账，每日限提RMB5000，每月限提RMB10万；</span>
					<br>
				<span>二、花呗、信用卡充值资金，7日后申请提现，第一次提现金额低于2000则免费，第二次收取1%手续费，第三次开始收取百分之5%手续费，2小时内到账；</span>
				</p>
				<p><b>2.充值赠送：</b><span>（工作日9:00-17:00）首冲100送20现金红包，联系在线客服领取。非推广商以及花呗、信用卡充值赠送只能消费不能提现；白银推广商：月提现总额5000；黄金推广商月提现总额1万；钻石推广商月提现总额5万；七日后可申请提现；</span>
				</p>
				<p><b>3.推广奖：</b><span>奖励满七天后的第一个工作日可申请提现，500起提，收取2%手续费。2小时内到账；</span>
				</p>
				<p><b>4.管理奖：</b><span>奖励满七天后的第一个工作日可申请提现，2000起提，收取2%手续费，2小时内到账。</span>
				</p>
				<p><span>注：若发现违反相关金融规定或法律，经核实，本公司有权冻结账户（如：信用卡、花呗套现；洗黑钱；大额资金非正常急进急出），其他未尽事宜以国家法律及相关规定为依据。</span></p>
			</div>

			<!-- 提现内容 -->
			<div class="blank20"></div>
			<div class="info_box">
				<h3>提现记录</h3>
				<div class="blank10"></div>
				<div class="info_table order_table">
					<table>
						<tbody>
							<tr>
								<th width="120">时间</th>
								<th width="100">金额</th>
								<th width="auto">详情</th>
								<th width="100">状态</th>
								<th width="70">操作</th>
							</tr>
							
							<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
							<tr class="alt">
                                <td>
								<?php 
$k = array (
  'name' => 'to_date',
  'value' => $this->_var['row']['create_time'],
);
echo $k['name']($k['value']);
?>
								</td>
                                <td>
                                	<h1><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['row']['money'],
);
echo $k['name']($k['v']);
?></h1>
                                </td>
                                <td class="detail">
                                	开户行全称:<?php echo $this->_var['row']['bank_name']; ?><br />
									开户行账号:<?php echo $this->_var['row']['bank_account']; ?><br />
									开户人真实姓名:<?php echo $this->_var['row']['bank_user']; ?><br />
                                </td>
								<td>									
									<?php if ($this->_var['row']['is_paid'] == 0): ?>
									<h1>审核中</h1>
									<?php else: ?>
									<h1><?php 
$k = array (
  'name' => 'to_date',
  'value' => $this->_var['row']['pay_time'],
);
echo $k['name']($k['value']);
?></h1> 已支付
									<?php endif; ?>
								</td>
								<td class="op_box">
									<a href="javascript:void(0);" class="del_order" action="<?php
echo parse_url_tag("u:index|uc_money_cash#del_withdraw|"."id=".$this->_var['row']['id']."".""); 
?>">删除</a>
								</td>
                            </tr>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                           
                            <tr >
                            	<?php if ($this->_var['list']): ?>
                                <td colspan="4"><div class="pages"><?php echo $this->_var['pages']; ?></div></td>
                                <?php else: ?>
                                <td colspan="4"><span>暂时没有提现记录</span></td>
                                <?php endif; ?>
                            </tr>
						</tbody>
					</table>
				</div>
				
			</div><!--end 列表-->
			
		</div>
	</div>	
</div>

<script>
	$(function(){

	});
</script>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>