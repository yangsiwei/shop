<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_center.css";
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
<style type="text/css">
	.dealers:hover{
		text-decoration:underline;
	}
	.alertxx{
		position:relative;
	}
	.form-item{
		margin-top:20px;
	}
	#up{
		margin-left:85px;
	}
	#qu{
		margin-left:100px;
	}
	.merit>li{
		font-size:16px;
		color:red;
	}
	#close{
		margin-left:165px;
	}
	/*<?php if ($this->_var['dealers']): ?>
	.dealers{
		display:none;
	}
	<?php endif; ?>*/
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
	<div class="m-user-frame-colMain">
		<div class="m-user-frame-content-m" pro="userFrameWraper">
			<div class="m-user-center">
				<div class="m-user-center-hd clearfix">
				<!-- 头像 -->
					<div class="f_l avatar">
						<img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['user_info']['id'],
  'type' => 'big',
);
echo $k['name']($k['uid'],$k['type']);
?>" data-id="<?php echo $this->_var['user_info']['id']; ?>" width="160" height="160">
					</div>
					<div class="user-info f_l">
						<ul>
							<li class="user-name"><?php echo $this->_var['user_info']['user_name']; ?>  &nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#43D3B6;">LV<?php echo $this->_var['user_info']['level_id']; ?></span>
								<?php if ($this->_var['dealers'] != 2): ?>
							<span class="dealers" style="font-size:16px;color:#6E99FF;cursor:pointer;">经销商养成计划</span>
								<?php endif; ?>
							</li>
							<?php if ($this->_var['dealers']): ?>
							<li>ID：<b><?php echo $this->_var['user_info']['id']; ?></b> &nbsp;&nbsp;&nbsp;我的等级：<b style="color:gold;"><?php echo $this->_var['level']; ?></b> &nbsp;&nbsp;&nbsp;我的推广奖<b><?php echo $this->_var['user_info']['fx_money']; ?></b>
							&nbsp;&nbsp;&nbsp;我的管理奖<b><?php echo $this->_var['user_info']['admin_money']; ?></b>
							</li>
							<?php endif; ?>
							<li>账户余额：<b class="txt-red"><?php 
$k = array (
  'name' => 'round',
  'v' => $this->_var['user_info']['money'],
  'e' => '2',
);
echo $k['name']($k['v'],$k['e']);
?></b>
								&nbsp;&nbsp;&nbsp;
								赠送金额：<b class="txt-red"><?php echo $this->_var['user_info']['give_money']; ?></b>
								<a href="<?php
echo parse_url_tag("u:index|uc_money|"."".""); 
?>" class="btn btn-s">充值</a>
								<a href="<?php
echo parse_url_tag("u:index|uc_money_cash#withdraw|"."".""); 
?>" class="btn btn-s">提现</a>
							</li>
							<!--<li>可用红包：<b class="txt-red"><?php echo $this->_var['user_info']['voucher_count']; ?></b>-->
								<!--个-->
								<!--<a href="<?php
echo parse_url_tag("u:index|uc_voucher|"."".""); 
?>">查看</a>-->
								<!--&lt;!&ndash; <a href="<?php
echo parse_url_tag("u:index|uc_money_remove#index|"."".""); 
?>" class="btn btn-s">资产迁移</a> &ndash;&gt;-->
							<!--</li>-->
						</ul>
					</div>
				</div>
				<div class="blank10"></div>
				<div class="m-user-comm-wraper">
					<div class="m-user-comm-cont">
						<div class="m-user-comm-title">
							<a href="<?php
echo parse_url_tag("u:index|uc_duobao|"."".""); 
?>" class="f_r">查看更多记录</a>
							<h3>我最近的夺宝</h3>
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
														<div class="ico <?php if ($this->_var['value']['duobao_item']['luck_user_id'] == $this->_var['user_info']['id']): ?> winner-ico<?php endif; ?>"></div>
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
											<td class="col-opt" style="">
												<p class="w-goods-price">
													我参与<?php echo $this->_var['value']['number']; ?>人次
													<a href="javascript:my_no_all(<?php echo $this->_var['value']['duobao_item']['id']; ?>);"  style="color:#3399ff;">查看</a>
												</p>
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
													<?php if ($this->_var['value']['duobao_item']['progress'] == '100'): ?>
														<?php if ($this->_var['value']['duobao_item']['new_duobao_item_id']): ?>
														<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."dbid=".$this->_var['value']['duobao_item']['duobao_id']."".""); 
?>'>参与最新</a>
														<?php else: ?>
															<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>查看详情</a>
														<?php endif; ?>
													<?php elseif ($this->_var['value']['duobao_item']['is_pk'] == 1): ?>
                                                        <a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>查看详情</a>
                                                    <?php else: ?>
														<a class="w-button-main" href='<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['duobao_item']['id']."".""); 
?>'>追加人次</a>
													<?php endif; ?>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
										</tbody>
									</table>
									</div>
								</div>
							</div>
							<?php else: ?>
						<div class="listCont">
							<div >
								<div class="duobaoList">
									<div class="m-user-comm-empty">
										<div class="i-desc">
											您没有相应的夺宝记录哦~</div>
										<div class="i-opt">
											<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>" style="color:#ffffff;border:none;white-space:nowrap;font-size:18px;display:inline-block;vertical-align:middle;padding:0px 35px;height:45px;line-height:45px;border-radius:4px;cursor:pointer;font-family:'microsoft yahei', simhei;outline:none;text-decoration:none !important;background:#dd344f;">马上去逛逛</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="m-user-frame-content-r f_r">
		<!-- 广告 -->
			<div class="extension">
				<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>"></a>
			</div>
			<div class="blank10"></div>
			<div class="log-history">
				<h3 class="title">我最近浏览的商品</h3>
				<div class="history-info">
					<ul class="clearfix">
						<?php $_from = $this->_var['history_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'history_item');if (count($_from)):
    foreach ($_from AS $this->_var['history_item']):
?>
						<li><a href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['history_item']['id']."".""); 
?>" title="<?php echo $this->_var['history_item']['name']; ?>"><img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['history_item']['icon'],
  'w' => '72',
  'h' => '72',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" width="72" height="72"></a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 调用弹框 -->
   <div class="alertxx" style="display:none">
        <div class="form-item cf">
            <div class="controls" style="margin-top:10px; margin-left:25px;">
            	<?php if ($this->_var['no_pass']): ?>
            	<label class="no_pass">
            	<p style="font-size:20px;">您暂时未完成经销商养成计划,再充值<span style="color:gold;"><?php echo $this->_var['no_pass']; ?></span>夺宝币，可以成为经销商。</p>
            	<?php endif; ?>

            	<?php if ($this->_var['pass']): ?>
            	<label class="pass">
            	<p style="font-size:20px;">恭喜您已完成经销商养成计划，点击确定即刻成为我们的经销商</p>
                <?php endif; ?>
                </label>
                
            	<span style="font-size:18px;color:#DD344F;">成为经销商的优点：</span><br/>
            	<ul class="merit">
            		<li>成为经销商坐享推广奖啦！！！</li>
            		<li>成为经销商坐享推广奖啦！！！</li>
            		<li>成为经销商坐享推广奖啦！！！</li>
            	</ul>
                </label>
                
            </div>
        </div>
        
        <div class="form-item">
			<?php if ($this->_var['no_pass']): ?>
				<input type="submit" id="qu" style="width:90px; height:40px; background:#0C8EF1; font-size:15px; color:#F2F3FF;margin-left:180px;" value="确定" >
        	<?php endif; ?>
        	<?php if ($this->_var['pass']): ?>
            <input type="submit" id="up" style="width:90px; height:40px; background:#CC3900; font-size:15px; color:#F2F3FF;" value="成为经销商" >
            <input type="submit" id="qu" style="width:90px; height:40px; background:#0C8EF1; font-size:15px; color:#F2F3FF;" value="取消" >
            <?php endif; ?>
        </div>
    </div>
<!-- 调用弹框结束 -->
	<div class="success" style="display:none;">
		<div class="form-item cf">
            <div class="controls" style="margin-top:10px; margin-left:25px;">
				<p style="font-size:20px;color:gold;">恭喜，您已经成为经销商！！！</p>
			</div>
        </div>	
        <div class="form-item">
            <input type="submit" id="close" style="width:90px; height:40px; background:#CC3900; font-size:15px; color:#F2F3FF;" value="确定" >
        </div>
	</div>
<div class="blank20"></div>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<!-- <script src="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.5/js/bootstrap.js"></script> -->
<script type="text/javascript" src="./public/layer/layer.js"></script>
<script>
	$(document).ready(function(){
        var index;
        $(".dealers").click(function(){
        	// var need_money = <?php echo $this->_var['need_money']; ?>;
        	// if(need_money == null){
        	// 	$(".no_pass").addCss("display:none;");
        	// 	$(".pass").addCss("idsplay:block");
        	// }
            index = layer.open({
                shade :0,
                type: 1,
                tipsMore: true,
                title: '经销商养成计划',
                area: ['450px', '43%'],
                content: $(".alertxx") //iframe的url
            });
            return false;
        });

        $("#up").click(function(){
        	//发送ajax把这个用户变更成为经销商
        	$.ajax({
			   type: "POST",
			   url: "<?php
echo parse_url_tag("u:index|uc_money_cash#tobe_dealers|"."".""); 
?>",
			   data: "tobe=2",
			   success: function(data){
			   		index = layer.open({
	                shade :0,
	                type: 1,
	                tipsMore: true,
	                title: '恭喜！！',
	                area: ['400px', '25%'],
	                content: $(".success") //iframe的url
			   });
			   }
			});
            layer.close(index);
        });

        $("#qu").click(function(){
            layer.close(index);
        });

        $("#close").click(function(){
            layer.close(index);
        });
      })

</script>
<?php echo $this->fetch('inc/footer.html'); ?>
