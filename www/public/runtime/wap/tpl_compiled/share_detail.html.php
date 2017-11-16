<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_share_detail.css";
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

<div class="share-detail-wrap loading_container" id="loading_container">
	<div class="share-detail">
		<div class="detail-hd clearfix">
			<h1 class="title"><?php echo $this->_var['data']['share_info']['title']; ?></h1>
			<a class="user-name"><?php echo $this->_var['data']['share_info']['user_name']; ?></a>
			<p class="time"><?php echo $this->_var['data']['share_info']['create_time']; ?></p>
		</div>
		<div class="goods-info">
			<p>商品信息：<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['data']['share_info']['duobao_item']['id']."".""); 
?>" class="goods-name"><?php echo $this->_var['data']['share_info']['duobao_item']['name']; ?></a></p>
			<p>商品期号：<?php echo $this->_var['data']['share_info']['duobao_item']['id']; ?></p>
			<p>本期参与：<em class="txt-red"><?php echo $this->_var['data']['share_info']['duobao_item']['luck_user_buy_count']; ?></em>人次</p>
			<p>幸运号码：<em class="txt-red"><?php echo $this->_var['data']['share_info']['duobao_item']['lottery_sn']; ?></em></p>
			<p>揭晓时间：<?php echo $this->_var['data']['share_info']['duobao_item']['lottery_time']; ?></p>
		</div>
		<p class="share-txt"><?php echo $this->_var['data']['share_info']['content']; ?></p>
		<?php if ($this->_var['data']['share_info']['image_list']): ?>
		<ul class="share-pic">
			<?php $_from = $this->_var['data']['share_info']['image_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'image');if (count($_from)):
    foreach ($_from AS $this->_var['image']):
?>
			<li><img src="<?php echo $this->_var['image']['o_path']; ?>"></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>