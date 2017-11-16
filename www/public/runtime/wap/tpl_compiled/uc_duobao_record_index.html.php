<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao_record.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";

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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>
<?php echo $this->fetch('inc/header_title_only.html'); ?>
<script type="text/javascript">
	$(function(){
		$('.menu_box>li').eq(4).children('a').children('p').children('img').attr('src','./wap/Tpl/main/images/wd4.png');
		$('.item_txt').eq(4).css('color','#dd354e');
	})
</script>
<div style="z-index:10;" class="slider-nav split-line">
  <ul>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|uc_duobao_record#index|"."".""); 
?>" <?php if ($this->_var['log_type'] == 0): ?>class="cur"<?php endif; ?>>全部</a></li>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|uc_duobao_record#index|"."log_type=1".""); 
?>" <?php if ($this->_var['log_type'] == 1): ?>class="cur"<?php endif; ?>>进行中</a></li>
    <li class="nav-item"><a href="<?php
echo parse_url_tag("u:index|uc_duobao_record#index|"."log_type=2".""); 
?>"  <?php if ($this->_var['log_type'] == 2): ?>class="cur"<?php endif; ?>>已揭晓</a></li>
  </ul>
</div>
<?php endif; ?>

<div class="loading_container" id="loading_container">
<?php if ($this->_var['list']): ?>
<div class="m-content">
<ul class="scroll_bottom_list">
	<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	<li class="dblist <?php if ($this->_var['item']['success_time'] == 0): ?>inprogress<?php else: ?>finish<?php endif; ?>">
        <?php if ($this->_var['item']['unit_price'] == 100): ?><div class="hundredyen"></div><?php endif; ?>
        <?php if ($this->_var['item']['unit_price'] == 10 || $this->_var['item']['min_buy'] == 10): ?> <div class="tenyen"></div><?php endif; ?>
		<div class="dblistimg">
		<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>"><img alt="" src="<?php echo $this->_var['item']['icon']; ?>"/></a>
		</div>
		<div class="dblistmain">
			<a class="tit" href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
			<?php if ($this->_var['item']['is_five']): ?>
			<em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">五倍</em>
			<?php endif; ?>
            <?php if ($this->_var['item']['is_topspeed']): ?>
            <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
            <?php endif; ?>
            <?php if ($this->_var['item']['is_number_choose'] == 1): ?>
            <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">选号</em>
            <?php endif; ?>
            <?php if ($this->_var['item']['is_pk'] == 1): ?>
            <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">PK</em>
            <?php endif; ?>
            <?php echo $this->_var['item']['name']; ?></a>
			<div class="prl">
				<div class="lable">期号&nbsp;:&nbsp;<?php echo $this->_var['item']['id']; ?></div>
				<div class="progressBar">
                    <span class="bar" style="width:<?php echo $this->_var['item']['progress']; ?>%"><i class="color"></i></span>
                </div>
				<div class="lable fl">总需&nbsp;:&nbsp;<?php echo $this->_var['item']['max_buy']; ?></div>
				<div class="lable fl surplus">剩余&nbsp;:&nbsp;<em><?php echo $this->_var['item']['less']; ?></em></div>
				<a class="tacked" href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>"><?php if ($this->_var['item']['is_pk'] == 1): ?>查看详情<?php else: ?>追加<?php endif; ?></a>
			</div>
			<div class="prl">
				<div class="partake fl">本期参与&nbsp;:&nbsp;<em><?php echo $this->_var['item']['number']; ?></em>人次</div>
				<a class="lookno fr" href="<?php
echo parse_url_tag("u:index|uc_duobao_record#my_no|"."id=".$this->_var['item']['id']."".""); 
?>">查看我的号码</a>
			</div>
			<?php if ($this->_var['item']['has_lottery'] == 1): ?>
			<div class="announce">
				<div class="mline" style="width: 264px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">获得者&nbsp;:&nbsp;<span><a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['item']['luck_user_id']."".""); 
?>"><?php echo $this->_var['item']['luck_user_name']; ?></a></span></div>
				<div class="mline">本期参与&nbsp;:&nbsp;<em><?php echo $this->_var['item']['max_buy']; ?></em>人次</div>
				<div class="mline" style="width: 264px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">幸运号码&nbsp;:&nbsp;<em><?php echo $this->_var['item']['lottery_sn']; ?></em> </div>
				<div class="mline">揭晓时间&nbsp;:&nbsp;<?php echo $this->_var['item']['lottery_time']; ?></div>
			</div>
			<?php else: ?>
			<div class="announce">
				<div class="mline">等待开奖</div>
			</div>
			<?php endif; ?>
		</div>
	</li>

	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
</div>
<?php else: ?>
<div class="content">

<div class="null_data">
		<p class="icon"><i class="iconfont">&#xe6e8;</i></p>
		<p class="message"> 暂无数据</p>
</div>
</div>
<?php endif; ?>
<?php if ($this->_var['pages']): ?>
	<div class="fy scroll_bottom_page">
		<?php echo $this->_var['pages']; ?>
	</div>
<?php endif; ?>
</div>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>
