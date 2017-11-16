<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_exchange.js";

?>
<?php echo $this->fetch('inc/header.html'); ?>

<script type="text/javascript">
var ALLOW_EXCHANGE = 0;
<?php if ($this->_var['ACTION_NAME'] == 'exchange'): ?>
	ALLOW_EXCHANGE = '<?php echo $this->_var['allow_exchange']; ?>';
	var EXCHANGE_JSON_DATA = <?php echo $this->_var['exchange_json_data']; ?>;
<?php endif; ?>
</script>
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
		<div class="main_box uc_info_box">
			<!--<div class="info_nav" >-->
				<!--<ul class="clearfix">-->
					<!--<li <?php if ($this->_var['ACTION_NAME'] == 'money'): ?>class="cur"<?php endif; ?>><a href="<?php
echo parse_url_tag("u:index|uc_log#money|"."".""); 
?>">我的资金</a></li>-->
					<!--<li <?php if ($this->_var['ACTION_NAME'] == 'score'): ?> class="cur"<?php endif; ?>><a href="<?php
echo parse_url_tag("u:index|uc_log#score|"."".""); 
?>">我的积分</a></li>-->
					<!--<?php if ($this->_var['allow_exchange']): ?><li <?php if ($this->_var['ACTION_NAME'] == 'exchange'): ?> class="cur"<?php endif; ?>><a href="<?php
echo parse_url_tag("u:index|uc_log#exchange|"."".""); 
?>">uc兑换</a></li><?php endif; ?>-->
				<!--</ul>-->
			<!--</div>-->
			<!-- 资产 -->
			<?php if ($this->_var['ACTION_NAME'] == 'money'): ?>
			<!-- 资产标题 -->
			<div class="info_box">
				<div class="blank20"></div>
				<h3>我的资产信息</h3>
				<div class="blank10"></div>
				<div class="bg_box growth_content">
					
					<div class="info_items">
						<ul>
							<li><label>我当前的余额是：</label><span class="main_color"><?php echo $this->_var['user_info']['money']; ?>夺宝币</span></li>
							<li><label> 可用的赠送金额：</label><span class="main_color"><?php echo $this->_var['user_info']['can_use_give_money']; ?>夺宝币</span></li>
							<li><label> 我的赠送总金额：</label><span class="main_color"><?php echo $this->_var['user_info']['give_money']; ?>夺宝币</span></li>
                            <?php if ($this->_var['user_info']['dealers'] == 2): ?>
							<li><label>我当前的推广奖：</label><span class="main_color"><?php echo $this->_var['user_info']['fx_money']; ?>夺宝币</span></li>
                            <?php endif; ?>
                            <?php if ($this->_var['user_info']['fx_level'] > 3): ?>
							<li><label> 我当前的管理奖：</label><span class="main_color"><?php echo $this->_var['user_info']['admin_money']; ?>夺宝币</span></li>
                            <?php endif; ?>
							<li><label>	充值到<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SHOP_TITLE',
);
echo $k['name']($k['v']);
?>帐户，方便抢购！：</label><span><a class="main_color" href="<?php
echo parse_url_tag("u:index|uc_money#incharge|"."".""); 
?>" target="_blank">[会员充值]</a></span></li>
						</ul>
					</div>
				</div>
			</div>
			
			<!-- 资产内容 -->
			<div class="blank20"></div>
			<div class="info_box">
				<h3>我的资产记录</h3>
				<div class="blank10"></div>
				<div class="info_table">
					<table>
						<tbody>
							<tr>
								<th width="120">时间</th>
								<th width="700">详情</th>
								<th width="176">金额</th>
							</tr>
							<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
							<tr class="alt">
                                <td><?php echo $this->_var['row']['flog_time']; ?></td>
                                <td class="detail"><?php echo $this->_var['row']['log_info']; ?></td>
                                <td class="value increase" ><span class="growth">&yen;<?php if ($this->_var['row']['money'] > 0): ?><?php endif; ?><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['row']['money'],
  'v2' => '2',
);
echo $k['name']($k['v'],$k['v2']);
?></span></td>
                            </tr>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <tr >
                            	<?php if ($this->_var['data']['count'] > 0): ?>
                                <td colspan="3"><div class="pages"><?php echo $this->_var['pages']; ?></div></td>
                                <?php else: ?>
                                <td colspan="3"><span>暂时没有成资金日志</span></td>
                                <?php endif; ?>

                            </tr>
						</tbody>
					</table>
				</div>
				
			</div>
			
			<?php endif; ?>
			
			<!--经验-->
			<?php if ($this->_var['ACTION_NAME'] == 'point'): ?> 
			<div class="info_box">
				<div class="blank20"></div>
				<h3>我的成长信息</h3>
				<div class="blank10"></div>
				<div class="bg_box growth_content">
					
					<div class="info_items">
						<ul>
							<li><label>我当前的等级是：</label><span class="level_bg level_<?php echo $this->_var['uc_query_data']['cur_level']; ?>" title="<?php echo $this->_var['uc_query_data']['cur_level_name']; ?>"></span></li>
							<li><label>我当前的经验值是：</label><span class="main_color"><?php echo $this->_var['uc_query_data']['cur_point']; ?></span></li>
							<?php if ($this->_var['uc_query_data']['next_level'] > 0): ?>
								<li><label>我再增加：</label><span><em class="main_color"><?php echo $this->_var['uc_query_data']['next_point']; ?></em> 经验值，就可以升级为：<em class="lv_name"><?php echo $this->_var['uc_query_data']['next_level_name']; ?></em></span></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="blank20"></div>
			<div class="info_box">
				<h3>我的成长记录</h3>
				<div class="blank10"></div>
				<div class="info_table">
					<table>
						<tbody>
							<tr>
								<th width="120">时间</th>
								<th width="auto">详情</th>
								<th width="70">经验值</th>
							</tr>
							<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
							<tr class="alt">
                                <td><?php echo $this->_var['row']['flog_time']; ?></td>
                                <td class="detail"><?php echo $this->_var['row']['log_info']; ?></td>
                                <td class="value increase" ><span class="growth"><?php if ($this->_var['row']['point'] > 0): ?>+<?php endif; ?><?php echo $this->_var['row']['point']; ?></span></td>
                            </tr>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <tr >
                            	<?php if ($this->_var['data']['count'] > 0): ?>
                                <td colspan="3"><div class="pages"><?php echo $this->_var['pages']; ?></div></td>
                                <?php else: ?>
                                <td colspan="3"><span>暂时没有成长记录，^_^ 去发发文章或者图片，累计经验你就成长了~</span></td>
                                <?php endif; ?>

                            </tr>
						</tbody>
					</table>
				</div>
				
			</div>
			<?php endif; ?>
			
			
			<!--积分-->
			<?php if ($this->_var['ACTION_NAME'] == 'score'): ?> 
			<div class="info_box">
				<div class="blank20"></div>
					<h3>我的积分信息</h3>
				<div class="blank10"></div>
				<div class="bg_box ">
					<div class="info_items">
						<ul>
							<li><label>我当前的积分是：</label><span class="main_color"><?php echo $this->_var['uc_query_data']['cur_score']; ?></span></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="blank20"></div>
			<div class="info_box">
				<h3>我的积分记录</h3>
				<div class="blank10"></div>
				<div class="info_table">
					<table>
						<tbody>
							<tr>
								<th width="120">时间</th>
								<th width="auto">详情</th>
								<th width="70">积分值</th>
							</tr>
							<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
							<tr class="alt">
                                <td><?php echo $this->_var['row']['flog_time']; ?></td>
                                <td class="detail"><?php echo $this->_var['row']['log_info']; ?></td>
                                <td class="value increase" ><span class="growth"><?php if ($this->_var['row']['score'] > 0): ?>+<?php endif; ?><?php echo $this->_var['row']['score']; ?></span></td>
                            </tr>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <tr >
                            	<?php if ($this->_var['data']['count'] > 0): ?>
                                <td colspan="3"><div class="pages"><?php echo $this->_var['pages']; ?></div></td>
                                <?php else: ?>
                                <td colspan="3"><span>暂时没有积分记录，^_^ </span></td>
                                <?php endif; ?>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
			<?php endif; ?>
			
			
			<!-- 兑换 -->
			<?php if ($this->_var['ACTION_NAME'] == 'exchange' && $this->_var['allow_exchange']): ?>
			<!-- 资产标题 -->
			<div class="info_box">
				<div class="blank20"></div>
				<h3>我的资产预览</h3>
				<div class="blank10"></div>
				<div class="bg_box growth_content">
					
					<div class="info_items">
						<ul>
							<li><label>我当前的余额是：</label><span class="main_color"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_info']['money'],
);
echo $k['name']($k['v']);
?></span></li>
							<li><label>我累计的积分是：</label><span class="main_color"><?php echo $this->_var['user_info']['score']; ?></span></li>
							<li><label>我当前的经验是：</label><span class="main_color"><?php echo $this->_var['user_info']['point']; ?></span></li>
						</ul>
					</div>
				</div>
			</div>
			
			<!-- 资产内容 -->
			<div class="blank20"></div>
			<div class="info_box">
				<h3>我的兑换操作</h3>
				<div class="blank10"></div>
				<div class="info_table cnt_tf_left">
					<table>
						<tbody>
							<tr>
								<th width="80">兑换数量</th>
								<th width="auto">详情</th>
								<th width="150">消耗</th>
							</tr>
							<tr class="alt">
                                <td>
                                	<input type="text" class="ui-textbox field_text" name="amountdesc" id="amountdesc" size="4"  />
                                </td>
                                <td class="detail">
                                		<div class="field_select w430">
			                                <select name="key" id="key" class="ui-select ">
												<?php $_from = $this->_var['exchange_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'exchange_desc');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['exchange_desc']):
?>
												<option value="<?php echo $this->_var['key']; ?>" rel="<?php echo $this->_var['exchange_desc']['title']; ?>"><?php echo $this->_var['exchange_desc']['title']; ?>(<?php echo $this->_var['exchange_desc']['ratiodesc']; ?> <?php echo $this->_var['exchange_desc']['title']; ?>:<?php echo $this->_var['exchange_desc']['ratiosrc']; ?> <?php echo $this->_var['exchange_desc']['srctitle']; ?>)</option>
												<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
											</select>
										</div>
                                </td>
                                <td class="value increase" >
                                	<input type="text" class="ui-textbox field_text" name="amountsrc" id="amountsrc"   size="4" readonly="true" />
                                	<span id="titlesrc"></span>
                                </td>
                            </tr>
							<tr >
                                <td colspan="3">
	                                <span>登录密码：&nbsp;&nbsp;</span>
									<input type="password" name="user_pwd" id="user_pwd" class="ui-textbox field_text" />
								</td>
                            </tr>
                            <tr >
                                <td colspan="3">
                                <button id="doexchange" rel="orange" type="button" class="formbutton ui-button">兑换</button>
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
				
			</div>
			
			<?php endif; ?>
		</div>
	</div>	
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>