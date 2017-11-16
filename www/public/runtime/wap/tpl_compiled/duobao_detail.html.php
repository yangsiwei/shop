<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao_detail.css";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duobao_detail.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duobao_detail.js";


?>

<?php echo $this->fetch('inc/header_title_home.html'); ?>

<script type="text/javascript">
	//减少移动端触发"Click"事件时300毫秒的时间差
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);
var fair_check_link = "<?php echo $this->_var['data']['fair_check_link']; ?>";
</script>
<div class="wrap">
    <div class="content">
    	<div class="formula-wrap split-line">
		<div class="formula">
			<h1>计算公式</h1>
            <?php if (! $this->_var['data']['is_topspeed']): ?>
			<p>[(数值A+数值B)&divide;商品所需人次]取余数+100000001</p>
            <?php endif; ?>
            <?php if ($this->_var['data']['is_topspeed']): ?>
            <p>[数值A&divide;商品所需人次]取余数+100000001</p>
            <?php endif; ?>
		</div>
		</div>
		<ul class="info-list">
			<li class="split-line">
				<h1>数值A</h1>
				<p>= 截止该商品开奖时间点前最后50条全站参与记录</p>
				<p class="open-box fr">展开<i class="iconfont">&#xe6c3;</i></p>
				<p class="close-box fr">收起<i class="iconfont">&#xe6c4;</i></p>
				<p>= <span><?php echo $this->_var['data']['value_a']; ?></span></p>	
			</li>
			<dl class="user-list">
				<dt class="split-line">				
				<p class="fl">夺宝时间</p>
				用户账号
				</dt>
				<?php $_from = $this->_var['data']['duobao_item_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'log');if (count($_from)):
    foreach ($_from AS $this->_var['log']):
?>
				<dd class="split-line">
					<div class="txtbox">
					<time><?php echo $this->_var['log']['create_time_format']; ?></time> 
					 <span><i class="iconfont">&#xe6d4;</i><?php echo $this->_var['log']['fair_sn_local']; ?></span>
					 </div>
					 <p class="user-name"><?php echo $this->_var['log']['user_name']; ?></p>
				</dd>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</dl>
			<li class="split-line">
                <?php if (! $this->_var['data']['is_topspeed']): ?>
				<h1>数值B</h1>
				<?php if ($this->_var['data']['fair_name']): ?>
				<p>= 最近一期<?php echo $this->_var['data']['fair_name']; ?>的开奖结果</p>
				<p>= 
				
				<?php if ($this->_var['data']['value_b']): ?>
				<span><?php echo $this->_var['data']['value_b']; ?></span>		
				<?php else: ?>
				<em>正在等待开奖...</em>
				<?php endif; ?>
				<?php if ($this->_var['data']['value_b'] == $this->_var['data']['default_value_b']): ?>
				开奖通讯失败，使用默认值<?php echo $this->_var['data']['default_value_b']; ?>
				<?php elseif ($this->_var['data']['fair_period'] == 000000): ?>
				未获取到期号，使用默认值<?php echo $this->_var['data']['default_value_b']; ?>
				<?php else: ?>
				(第<?php echo $this->_var['data']['fair_period']; ?>期)
				<?php endif; ?>
				</p>
				<?php else: ?>
					<?php if ($this->_var['data']['value_b']): ?>
					<span><?php echo $this->_var['data']['value_b']; ?></span>		
					<?php else: ?>
					<em>正在等待开奖...</em>
					<?php endif; ?>	
				<?php endif; ?>
                <?php endif; ?>
			</li>
			<div class="blank-line split-line"></div>
			<li class="split-line">
				<h1>计算结果</h1>
				<?php if ($this->_var['data']['lottery_sn']): ?>
				<h2>幸运号码: <span><?php echo $this->_var['data']['lottery_sn']; ?></span></h2>
				<?php else: ?>
				<h2>幸运号码: <em>等待揭晓...</em>
				<?php endif; ?>
			</li>
		</ul>
    </div>
</div>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>

