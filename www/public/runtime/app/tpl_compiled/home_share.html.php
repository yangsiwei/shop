<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/home_luck.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/home.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_share.css";
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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/home_share.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/uc/home_share.js";

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

	<div class="f_r luck_main">
	<?php echo $this->fetch('inc/home_info.html'); ?>
	<div class="blank"></div>
	<div class="m-user-frame-colMain">
		
		<div class="share-box">
		<?php if ($this->_var['count'] == 0): ?>
			<div class="null-data">
				<p class="txt">Ta暂时还没有晒单记录哦~ </p>
				<div class="blank20"></div>
			</div>
		<?php else: ?>
			<div class="share-record">
				共有<b><?php echo $this->_var['count']; ?></b>条晒单记录
			</div>
			<div class="blank20"></div>
			
			<ul class="pin-layout" id="home-share-pin-box">
		
			</ul>
			<input type="hidden" name="page" id="hd_page" value="<?php echo $this->_var['page']; ?>" />
			<input type="hidden" name="step_size" id="hd_step_size" value="<?php echo $this->_var['step_size']; ?>" />
			<input type="hidden" name="step" id="hd_step" /> 
			<input type="hidden" name="ajax_wait" id="ajax_wait" /> 
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_var['home_user']['id']; ?>" /> 
			
			<div id="pin-loading" style="height:50px;text-align:center;">加载中.....</div>
			<div class="pages"><?php echo $this->_var['pages']; ?></div>
			<?php endif; ?>
		</div>
	</div>
	</div>

</div>
<div class="blank20"></div>
<?php echo $this->fetch('inc/footer.html'); ?>