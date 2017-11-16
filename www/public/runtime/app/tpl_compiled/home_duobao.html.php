<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/home.css";
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


?>
<?php echo $this->fetch('inc/header.html'); ?>

<div class="blank20"></div>

<div class="<?php 
$k = array (
  'name' => 'load_wrap',
  't' => $this->_var['wrap_type'],
);
echo $k['name']($k['t']);
?> clearfix">
	<div class="side_nav f_l ">
		<?php echo $this->fetch('inc/home_nav_list.html'); ?>
	</div>
	<div class="m-user-frame-colMain">
		<?php echo $this->fetch('inc/home_info.html'); ?>

		<div class="m-user-frame-content" pro="userFrameWraper">
			<div class="m-user-duobao">
				<div class="m-user-comm-wraper">
					<div class="m-user-comm-cont">
						<div class="m-user-comm-title">
							<div class="m-user-comm-navLandscape">
								<span class="title">购买记录</span>
							</div>

						</div>



						<div>
							<?php if ($this->_var['list']): ?>
							<div class="listCont">
								<div id="pro-view-18">
									<table class="m-user-comm-table">
										<thead style="background:#f2f2f2;">
										<tr>
											<th class="col-info-th">商品信息</th>
											<th class="col-period-th">期号</th>
											<th class="col-joinNum-th">参与人次</th>
											<th class="col-status-th">购买状态</th>
											<th class="col-opt-th">操作</th>
										</tr>
										</thead>
									</table>
									<div class="duobaoList">
									<table class="m-user-comm-table">
										<tbody>
										<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
										<tr>
											<td class="col-info">
												<div class="w-goods-l">
													<div class="w-goods-pic">
														<a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>" style="text-decoration:none;color:#3399ff;">
															<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['value']['duobao_item']['icon'],
  'w' => '74',
  'h' => '74',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" alt="<?php echo $this->_var['value']['name']; ?>" width="74" height="74" style="border:0px;" />
														</a>
													</div>
													<p class="w-goods-title">
														<?php if ($this->_var['value']['duobao_item']['min_buy'] == 10): ?>
														<span class="type-ten">十夺宝币专区</span>&nbsp;<?php endif; ?>
														<?php if ($this->_var['value']['duobao_item']['unit_price'] == 100): ?>
														<span class="type-ten" style="background-color:red">百夺宝币专区</span>&nbsp;<?php endif; ?>
														<a title="<?php echo $this->_var['value']['name']; ?>" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>" style="text-decoration:none;color:#333333;"><?php echo $this->_var['value']['name']; ?></a>
													</p>
													<p class="w-goods-price">总需：<?php echo $this->_var['value']['duobao_item']['max_buy']; ?>人次</p>
													<div class="winner">
														<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
														<div class="name">获得者：<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['value']['duobao_item']['luck_user_id']."".""); 
?>" title="<?php echo $this->_var['value']['duobao_item']['luck_user_name']; ?>" style="text-decoration:none;color:#3399ff;"><?php echo $this->_var['value']['duobao_item']['luck_user_name']; ?></a>（本期参与<strong class="txt-dark" style="color:#333333;"><?php echo $this->_var['value']['duobao_item']['luck_user_buy_count']; ?></strong>人次）
														</div>
														<div class="code">幸运代码：<strong class="txt-impt" style="color:#db3652;"><?php echo $this->_var['value']['duobao_item']['lottery_sn']; ?></strong></div>
														<div class="time">揭晓时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['value']['duobao_item']['lottery_time'],
);
echo $k['name']($k['v']);
?></div>
														<?php else: ?>
														<div class="progress">
                       									<div class="progress-bar" style="width: <?php echo $this->_var['value']['duobao_item']['progress']; ?>%"></div>
                    									</div>
                    									已完成<?php echo $this->_var['value']['duobao_item']['progress']; ?>%，剩余<?php echo $this->_var['value']['duobao_item']['less']; ?>
														<?php endif; ?>
													</div>
												</div>
											</td>
											<td class="col-period"><?php echo $this->_var['value']['duobao_item']['id']; ?></td>
											<td class="col-joinNum"><?php echo $this->_var['value']['number']; ?>人次</td>
											<td class="col-status">
												<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
												已揭晓
												<?php else: ?>
												正在进行
												<?php endif; ?>
											</td>
											<td class="col-opt" style="">
												<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
													<?php if ($this->_var['value']['duobao_item']['new_duobao_item_id']): ?>
														<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."dbid=".$this->_var['value']['duobao_item']['duobao_id']."".""); 
?>'>参与最新</a>
													<?php else: ?>
														<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>查看详情</a>
													<?php endif; ?>
												<?php else: ?>
													<?php if ($this->_var['value']['duobao_item']['progress'] == 100): ?>
														<?php if ($this->_var['value']['duobao_item']['new_duobao_item_id']): ?>
															<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."dbid=".$this->_var['value']['duobao_item']['duobao_id']."".""); 
?>'>参与最新</a>
														<?php else: ?>
															<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>查看详情</a>
														<?php endif; ?>
													<?php else: ?>
													<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>跟买</a>
													<?php endif; ?>
												<?php endif; ?>
												<p class="w-goods-price">
												Ta参与<?php echo $this->_var['value']['number']; ?>次<br />
													<a href="javascript:my_no_all(<?php echo $this->_var['value']['duobao_item']['id']; ?>,<?php echo $this->_var['home_user']['id']; ?>);"  style="color:#3399ff;">查看云号码</a>
												</p>
											</td>
										</tr>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
										</tbody>
									</table>
									</div>
									<div class="pages"><?php echo $this->_var['pages']; ?></div>
								</div>
							</div>

							<?php else: ?>
							<div class="listCont">
							<div >
									<div class="duobaoList">
										<div class="m-user-comm-empty">
											<div class="i-desc">Ta没有相应的购买记录哦~</div>
											<div class="i-opt">
												<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>" style="color:#ffffff;border:none;white-space:nowrap;font-size:18px;display:inline-block;vertical-align:middle;padding:0px 35px;height:45px;line-height:45px;border-radius:4px;cursor:pointer;font-family:'microsoft yahei', simhei;outline:none;text-decoration:none !important;background:#dd344f;">马上去逛逛</a>
											</div>
										</div>
									</div>
									<div pro="pager" class="pager" style="text-align:right;"></div>
								</div>
							</div>
							<?php endif; ?>



						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>
