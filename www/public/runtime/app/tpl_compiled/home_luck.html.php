<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/home_luck.css";
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_msg.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/uc_msg.js";

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
	<?php if ($this->_var['list']): ?>
	<div class="f_r luck_main">
	<?php echo $this->fetch('inc/home_info.html'); ?>
		<div  class="m-win-user">
			
			<div class="m-user-comm-wrapers">
				<div class="m-user-comm-titleHasBdr">
					<h2 class="title">幸运记录
						<span class="txt-gray">共有&nbsp;<strong pro="total"><?php echo $this->_var['total']; ?></strong>&nbsp;条幸运记录~</span>
					</h2>
				</div>
				<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
				<div class="w-goods">
					<div class="w-goods-pics">
					    <a title="<?php echo $this->_var['value']['name']; ?>" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['id']."".""); 
?>" style="text-decoration:none;color:#3399ff;">
						<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['value']['icon'],
  'w' => '200',
  'h' => '200',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" alt="<?php echo $this->_var['value']['name']; ?>" style="border:0px;width:200px;" />
						</a>
					</div>
					<div class="w-goods-content">
						<p class="w-goods-title">
							<a title="<?php echo $this->_var['value']['name']; ?>" href="<?php
echo parse_url_tag("u:index|duobao|"."id=".$this->_var['value']['id']."".""); 
?>" style="text-decoration:none;color:#333333;font-size:14px;font-weight:bold;"><?php echo $this->_var['value']['name']; ?></a>
						</p>
						<p class="w-goods-price" >期号：<?php echo $this->_var['value']['id']; ?></p>
						<p class="w-goods-price" >总需：<?php echo $this->_var['value']['max_buy']; ?>人次</p>
						<p class="w-goods-price" >幸运号码：<strong class="txt-impt"><?php echo $this->_var['value']['lottery_sn']; ?></strong></p>
						<p class="w-goods-price" >总共参与：<strong class="txt-dark"><?php echo $this->_var['value']['luck_user_buy_count']; ?></strong>人次</p>
						<p class="w-goods-price" >揭晓时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['value']['lottery_time'],
);
echo $k['name']($k['v']);
?></p>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				
				
			</div>
			<div class="pages"><?php echo $this->_var['pages']; ?></div>
		</div>
	</div>
	<?php else: ?>
	<div class="f_r luck_main">
	<?php echo $this->fetch('inc/home_info.html'); ?>
	<div class="blank"></div>
	<div class="m-user-frame-colMain">
		
		<div class="m-user-frame-content" pro="userFrameWraper">
			<div class="m-user-duobao">
				<div class="m-user-comm-wraper">
					<div class="m-user-comm-cont">
						<div>
						
						<div class="listCont">
							<div >
								<div class="duobaoList">
									<div class="m-user-comm-empty" id="pro-view-11" style="text-align:center;padding:100px 0px 128px;">
										<div class="i-desc">Ta还没有此记录哦~</div>
										<div class="i-opt">
											<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>" class="w-button w-button-main w-button-xl" style="color:#ffffff;border:none;white-space:nowrap;font-size:18px;display:inline-block;vertical-align:middle;padding:0px 35px;height:45px;line-height:45px;border-radius:4px;cursor:pointer;font-family:'microsoft yahei', simhei;outline:none;text-decoration:none !important;background:#dd344f;">马上试试运气</a>
										</div>
									</div>
								</div>
								<div pro="pager" class="pager" style="text-align:right;"></div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php endif; ?>
</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>