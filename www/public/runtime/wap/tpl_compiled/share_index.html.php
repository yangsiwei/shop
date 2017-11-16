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
<!-- 有数据 -->
<?php endif; ?>

<div class="luck-data loading_container" id="loading_container">
	<!-- 晒单列表 -->
	<?php if ($this->_var['data']['list']): ?>
	<ul class="share-list scroll_bottom_list">
		<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'share');if (count($_from)):
    foreach ($_from AS $this->_var['share']):
?>
		<li class="item">
			<a class="avatar"><img src="<?php echo $this->_var['share']['user_avatar']; ?>"></a>
			<a class="user-name"><?php echo $this->_var['share']['user_name']; ?></a>
			<p class="time"><?php echo $this->_var['share']['create_time']; ?></p>
			<div class="clear"></div>
			<a href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['share']['id']."".""); 
?>" class="share-info">
				<div class="content">
					<div class="arrow-ico"></div>
					<h2 class="share-title"><?php echo $this->_var['share']['title']; ?></h2>
					<p class="goods-name"><?php echo $this->_var['share']['duobao_item']['name']; ?></p>
					<p class="code">期号：<?php echo $this->_var['share']['duobao_item_id']; ?></p>
					<p class="share-txt"><?php echo $this->_var['share']['content']; ?></p>
					<?php if ($this->_var['share']['image_list']): ?>
					<ul class="share-pic clearfix">
						<?php $_from = $this->_var['share']['image_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'image');if (count($_from)):
    foreach ($_from AS $this->_var['image']):
?>
						<li>
							<img src="<?php echo $this->_var['image']['path']; ?>">
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
					<?php endif; ?>
				</div>
			</a>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<div class="clear"></div>
	</ul>
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

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>