<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao.css";
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_duobao.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_duobao.js";

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
		<?php echo $this->fetch('inc/uc_nav_list.html'); ?>
	</div>
	<div class="m-user-frame-colMain">
		<ul class="web-map clearfix">
			<li>当前位置：</li>
			<li><a href="<?php
echo parse_url_tag("u:index|uc_center|"."".""); 
?>">个人中心</a> ></li>
			<li class="txt-red">夺宝记录</li>
		</ul>
		<div class="m-user-frame-content" pro="userFrameWraper">
			<div class="m-user-duobao">
				<div class="m-user-comm-wraper">
					<div class="m-user-comm-cont">
						<div class="m-user-comm-title">
							<div class="m-user-comm-navLandscape">
								<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_time_type=".$this->_var['data']['log_time_type']."".""); 
?>" class="i-item <?php if (! $this->_var['data']['log_type']): ?>i-item-active<?php endif; ?>" >参与成功&nbsp;<span class="txt-impt"><?php echo $this->_var['data']['success_count']; ?></span></a>&nbsp;
								<span class="i-sptln">|</span>&nbsp;
								<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=3&log_time_type=".$this->_var['data']['log_time_type']."".""); 
?>" class="i-item <?php if ($this->_var['data']['log_type'] == 3): ?>i-item-active<?php endif; ?>">即将揭晓&nbsp;<span class="txt-impt"><?php echo $this->_var['data']['soon_count']; ?></span></a>&nbsp;
								<span class="i-sptln">|</span>&nbsp;
								<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=1&log_time_type=".$this->_var['data']['log_time_type']."".""); 
?>" class="i-item <?php if ($this->_var['data']['log_type'] == 1): ?>i-item-active<?php endif; ?>">正在进行&nbsp;<span class="txt-impt"><?php echo $this->_var['data']['in_count']; ?></span></a>&nbsp;
								<span class="i-sptln">|</span>&nbsp;
								<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=2&log_time_type=".$this->_var['data']['log_time_type']."".""); 
?>" class="i-item <?php if ($this->_var['data']['log_type'] == 2): ?>i-item-active<?php endif; ?>">已揭晓&nbsp;<span class="txt-impt"><?php echo $this->_var['data']['complete_count']; ?></span></a>
							</div>
							<div class="m-user-comm-selectTitle" >
								<span pro="text">
									<?php if ($this->_var['data']['log_time_type'] == 1): ?>今天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 2): ?>最近7天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 3): ?>最近30天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 4): ?>最近3个月<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 5): ?>1年内<?php endif; ?></span><span class="w-select-arr">▼</span>
									<div class="w-menu" tabindex="0" id="w_menu" style="display: none;">
										<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=".$this->_var['data']['log_type']."&log_time_type=1".""); 
?>">
										<div class="w-menu-item <?php if ($this->_var['data']['log_time_type'] == 1): ?>w-menu-item-hover<?php endif; ?>" tabindex="0" data-index="0" data-value="" id="pro-view-73">今天</div>
										</a>
										<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=".$this->_var['data']['log_type']."&log_time_type=2".""); 
?>">
										<div class="w-menu-item <?php if ($this->_var['data']['log_time_type'] == 2): ?>w-menu-item-hover<?php endif; ?>" tabindex="0" data-index="1" data-value="" id="pro-view-74" >最近7天</div>
										</a>
										<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=".$this->_var['data']['log_type']."&log_time_type=3".""); 
?>">
										<div class="w-menu-item <?php if ($this->_var['data']['log_time_type'] == 3): ?>w-menu-item-hover<?php endif; ?>" tabindex="0" data-index="2" data-value="" id="pro-view-75" >最近30天</div>
										</a>
										<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=".$this->_var['data']['log_type']."&log_time_type=4".""); 
?>">
										<div class="w-menu-item <?php if ($this->_var['data']['log_time_type'] == 4): ?>w-menu-item-hover<?php endif; ?>" tabindex="0" data-index="3" data-value="" id="pro-view-76" >最近3个月</div>
										</a>
										<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."log_type=".$this->_var['data']['log_type']."&log_time_type=5".""); 
?>">
										<div class="w-menu-item <?php if ($this->_var['data']['log_time_type'] == 5): ?>w-menu-item-hover<?php endif; ?>" tabindex="0" data-index="4" data-value="" id="pro-view-77" >1年内</div>
										</a>
									</div>
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
											<th class="col-status-th">夺宝状态</th>
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
										<tr <?php if ($this->_var['value']['duobao_item']['luck_user_id'] == $this->_var['user_info']['id']): ?>class="get-win"<?php endif; ?>>
											<td class="col-info">
												<div class="w-goods-l">
													<div class="w-goods-pic">
														<div class="<?php if ($this->_var['value']['duobao_item']['luck_user_id'] == $this->_var['user_info']['id']): ?>ico winner-ico<?php endif; ?>"></div>
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
                                                        <?php if ($this->_var['value']['duobao_item']['is_number_choose'] == 1): ?>
                                                        <span class="type-ten" style="background-color:red">选号</span>&nbsp;<?php endif; ?>
                                                        <?php if ($this->_var['value']['duobao_item']['is_pk'] == 1): ?>
                                                        <span class="type-ten" style="background-color:red">PK</span>&nbsp;<?php endif; ?>
														<a title="<?php echo $this->_var['value']['duobao_item']['name']; ?>" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>" style="text-decoration:none;color:#333333;"><?php echo $this->_var['value']['duobao_item']['name']; ?></a>
													</p>
													<p class="w-goods-price">总需：<?php echo $this->_var['value']['duobao_item']['max_buy']; ?>人次</p>
													<div class="winner">
														<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
														<div class="name">获得者：<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['value']['duobao_item']['luck_user_id']."".""); 
?>" title="<?php echo $this->_var['value']['duobao_item']['luck_user_name']; ?>" style="text-decoration:none;color:#3399ff;"><?php echo $this->_var['value']['duobao_item']['luck_user_name']; ?></a>（本期参与<strong class="txt-dark" style="color:#333333;"><?php echo $this->_var['value']['number']; ?></strong>人次）
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
														<div class="progress f_l">
                       									<div class="progress-bar f_l" style="width: <?php echo $this->_var['value']['duobao_item']['progress']; ?>%"></div>
                    									</div>
                    									<div class="blank" style="height:1px;"></div>
                    									已完成<?php echo $this->_var['value']['duobao_item']['progress']; ?>%<?php if ($this->_var['value']['duobao_item']['less'] > 0): ?>，剩余<?php echo $this->_var['value']['duobao_item']['less']; ?><?php endif; ?>
														<?php endif; ?>
													</div>
												</div>
											</td>
											<td class="col-period"><?php echo $this->_var['value']['duobao_item']['id']; ?></td>
											<td class="col-joinNum"><?php echo $this->_var['value']['number']; ?>人次</td>
											<td class="col-status">
												<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
												已揭晓
												<?php elseif ($this->_var['value']['duobao_item']['progress'] == 100): ?>
												即将揭晓
												<?php else: ?>
												正在进行
												<?php endif; ?>
											</td>
											<td class="col-opt" style="">
												<?php if ($this->_var['value']['duobao_item']['has_lottery'] == 1): ?>
												<a class="w-button-main" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>">查看详情</a>
												<?php else: ?>
												<a class="w-button-main" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>"><?php if ($this->_var['value']['duobao_item']['progress'] == 100): ?>查看详情<?php elseif ($this->_var['value']['duobao_item']['is_pk'] == 1): ?>查看详情<?php else: ?>追加人次<?php endif; ?></a>
												<?php endif; ?>
												<p class="w-goods-price">
													<a href="javascript:my_no_all(<?php echo $this->_var['value']['duobao_item']['id']; ?>);"  style="color:#3399ff;">查看夺宝号码</a>
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
										<div class="i-desc">
											您<?php if ($this->_var['data']['log_time_type'] == 1): ?>今天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 2): ?>最近7天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 3): ?>最近30天<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 4): ?>最近3个月<?php endif; ?><?php if ($this->_var['data']['log_time_type'] == 5): ?>1年内<?php endif; ?>没有相应的夺宝记录哦~</div>
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