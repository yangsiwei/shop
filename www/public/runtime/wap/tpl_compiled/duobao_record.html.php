<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao_record.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/TouchSlide.1.1.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<div class="wrap">
	<div class="content">
		<?php if ($this->_var['list']): ?>
		<div class="record-wrap scroll_bottom_list">
			<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
			<?php if ($this->_var['item']['has_lottery'] == 0): ?>
			<ul class="recording">
				
				<li>
					<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">期号：<em><?php echo $this->_var['item']['id']; ?></em> 即将揭晓，正在倒计时...</a>
				</li>	
							
			</ul>
			<?php else: ?>
			<ul class="recorded ">
				
				<li>
					
					<header>
						<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">期号：<em><?php echo $this->_var['item']['id']; ?></em>(揭晓时间：<time><?php echo $this->_var['item']['date']; ?>&nbsp;<?php echo $this->_var['item']['lottery_time_show']; ?></time>)</a>
					</header>
					<section>
						<a class="head-pic" href="javascript:void(0);" style="background:url(<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['item']['luck_user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>);background-size: contain"></a>
						<ul>
							<li>获奖者：
							<a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['item']['luck_user_id']."".""); 
?>" class="user"><?php echo $this->_var['item']['user_name']; ?></a>
							</li>
							<!-- <li class="ip-ad">
								(<?php echo $this->_var['item']['duobao_area']; ?>IP：<em><?php echo $this->_var['item']['duobao_ip']; ?></em>)
							</li> -->
							<li>
								用户ID：<?php echo $this->_var['item']['luck_user_id']; ?>(唯一不变标识)
							</li>
							<li>
								幸运号码：<span><?php echo $this->_var['item']['lottery_sn']; ?></span>
							</li>
							<!--<li>
								本期参与：<span>1</span>人次
							</li>-->
						</ul>
						<div class="clear"></div>
					</section>
					
				</li>
				
			</ul>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<?php if ($this->_var['pages']): ?>
		<div class="fy scroll_bottom_page">
			<?php echo $this->_var['pages']; ?>
		</div>
		<?php endif; ?>
		<?php else: ?>
		 <div class="null_data">
	      <p class="icon"><i class="iconfont">&#xe6e8;</i></p>
	      <p class="message">暂无数据</p>
	    </div>
		<?php endif; ?>
	</div>
</div>
<?php echo $this->fetch('inc/footer_index.html'); ?>
