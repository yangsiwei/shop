<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_share.css";
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
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<?php endif; ?>

<div class="loading_container" id="loading_container">
	<?php if (! $this->_var['luck_list'] && ! $this->_var['share_list']): ?>
	<!-- 没有数据 -->
	<div class="null-data">
		<div class="blank15"></div>
		<div class="share-none"></div>
		<?php if ($this->_var['data_id'] > 0): ?>
		<p>此夺宝活动还没有人晒单</p>
		<?php else: ?>
		<p>您还没有晒单记录哦</p>
		<?php endif; ?>
		<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>">立即夺宝</a>
	</div>
	<?php else: ?>
	<!-- 有数据 -->
	<div class="luck-data">
		<div class="luck-data-hd">
			<p class="title txt-red">好运旺就要放肆晒！拿晒单红包奖上奖哦！</p>
		</div>
		<!-- 中奖未晒单 -->
		<ul class="luck-list">
			<?php $_from = $this->_var['luck_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ome');if (count($_from)):
    foreach ($_from AS $this->_var['ome']):
?>
			<li class="item split-line">
				<div class="pic" onclick="window.location.href= '<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['ome']['duobao_item_id']."".""); 
?>';return false"><img src="<?php echo $this->_var['ome']['deal_icon']; ?>"></div>
				<div class="info">
					<p class="goods-name" onclick="window.location.href= '<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['ome']['duobao_item_id']."".""); 
?>';return false"><?php echo $this->_var['ome']['name']; ?></p>
					<p class="code">期号：<?php echo $this->_var['ome']['duobao_item_id']; ?></p>
					<a href="<?php
echo parse_url_tag("u:index|uc_share#rule|"."id=".$this->_var['ome']['duobao_item_id']."".""); 
?>" class="share-now">立即晒单</a>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		
		<!-- 已晒单 -->
		<ul class="share-list scroll_bottom_list">
		<?php $_from = $this->_var['share_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ome');if (count($_from)):
    foreach ($_from AS $this->_var['ome']):
?>
			<li class="item">
				<a href="<?php
echo parse_url_tag("u:index|uc_winlog|"."".""); 
?>" class="avatar"><img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['ome']['user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>"></a>
				<a href="<?php
echo parse_url_tag("u:index|uc_winlog|"."".""); 
?>" class="user-name"><?php echo $this->_var['ome']['user_name']; ?></a>
				<p class="time"><?php echo $this->_var['ome']['create_time']; ?></p>
				<div class="clear"></div>
				<a href="<?php
echo parse_url_tag("u:index|uc_share#detail|"."id=".$this->_var['ome']['share_id']."".""); 
?>" class="share-info">
					<div class="content">
						<div class="arrow-ico"></div>
						<h2 class="share-title"><?php echo $this->_var['ome']['title']; ?></h2>
						<p class="goods-name"><?php echo $this->_var['ome']['name']; ?></p>
						<p class="code">期号：<?php echo $this->_var['ome']['duobao_item_id']; ?></p>
						<p class="status">晒单状态：
						<?php if ($this->_var['ome']['is_check'] == 0): ?>
							<span class="txt-orange">审核中</span>
						<?php else: ?>
						<?php if ($this->_var['ome']['is_effect'] == 1): ?>
							<span class="txt-green">审核通过</span>
						<?php else: ?>
							<span class="txt-red">审核不通过</span>
						<?php endif; ?>
						<?php endif; ?>
						<p class="share-txt"><?php echo $this->_var['ome']['content']; ?></p>
						<?php if ($this->_var['ome']['image_list']): ?>
						<ul class="share-pic clearfix">
							<?php $_from = $this->_var['ome']['image_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ome');if (count($_from)):
    foreach ($_from AS $this->_var['ome']):
?>
							<li>
								<img src="<?php echo $this->_var['ome']['path']; ?>">
							</li>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
						<?php endif; ?>
					</div>
				</a>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		
		<?php if ($this->_var['pages']): ?>
		<div class="fy scroll_bottom_page">
			<?php echo $this->_var['pages']; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>