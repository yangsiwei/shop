<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/share.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.SuperSlide.2.1.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/lazyload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/share.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/share.js";




?>
<?php echo $this->fetch('inc/header.html'); ?>

<div class="banner">
        <div class="wrap_full_w share-banner">
        	<div class="share-footer">
        		<img src="<?php echo $this->_var['TMPL']; ?>/images/share-footer.png">
        	</div>
        </div>
        <div class="ico share-bolan"></div>
</div>
<div class="blank15"></div>
<div class="wrap_full_w">
	<div class="main-title"><h1>共 <b class="txt-red"><?php echo $this->_var['count']; ?></b> 条晒单</h1></div>
	<?php if ($this->_var['count'] == 0): ?>
			<div class="null-data">
				<p class="txt">暂时还没有晒单记录哦~</p>
				<div class="blank20"></div>

			</div>
			<?php endif; ?>
	<div class="pin-layout" id="share-pin-box">
		
	</div>
		<input type="hidden" name="page" id="hd_page" value="<?php echo $this->_var['page']; ?>" />
		<input type="hidden" name="step_size" id="hd_step_size" value="<?php echo $this->_var['step_size']; ?>" />
		<input type="hidden" name="step" id="hd_step" /> 
		<input type="hidden" name="ajax_wait" id="ajax_wait" /> 
	<div id="pin-loading" style="height:50px;text-align:center;">加载中.....</div>
	<div class="pages"><?php echo $this->_var['pages']; ?></div>
</div>
<?php echo $this->fetch('inc/footer.html'); ?>