<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_msg.css";	
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_msg.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_msg.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<?php endif; ?>

<div class="loading_container" id="loading_container">
	<div class="wrap">
		<div class="content">
			<?php if ($this->_var['data']['list']): ?>
			<div class="msg_box">
					<div class="blank"></div>
					<div class="scroll_bottom_list">
					<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'msg');if (count($_from)):
    foreach ($_from AS $this->_var['msg']):
?>
					<div class="msg">
						<div class="msg_ico">
							<?php if ($this->_var['msg']['icon']): ?>
								<?php if ($this->_var['msg']['link']): ?><a href="<?php echo $this->_var['msg']['link']; ?>"  target="_blank"><?php endif; ?>					
								<img src="<?php echo $this->_var['msg']['icon']; ?>" />
								<?php if ($this->_var['msg']['link']): ?></a><?php endif; ?>	
							<?php else: ?>
							<i class="iconfont">&#xe619;</i>
							<?php endif; ?>						
						</div>
						<div class="msg_main">
							<div class="msg_title">
							<?php if ($this->_var['msg']['short_title']): ?>
								<?php if ($this->_var['msg']['link']): ?><a href="<?php echo $this->_var['msg']['link']; ?>" target="_blank"><?php endif; ?>
									<?php echo $this->_var['msg']['short_title']; ?>
								<?php if ($this->_var['msg']['link']): ?></a><?php endif; ?>	
							<?php endif; ?>
							</div>
							<div class="msg_content">
								<?php echo $this->_var['msg']['content']; ?>
							</div>
						</div>
						<div class="clear"></div>
						<div class="msg_info"><?php echo $this->_var['msg']['create_time']; ?><a href="javascript:void(0);" class="del_msg" action="<?php
echo parse_url_tag("u:index|uc_msg#remove_msg|"."id=".$this->_var['msg']['id']."".""); 
?>"><i class="iconfont">&#xe68d;</i></a></div>
					</div>
					
					<div class="blank"></div>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
			</div>
			<?php else: ?>	
	
			<div class="null_data">
			<p class="icon"><i class="iconfont">&#xe6e8;</i></p> 
			<p class="message">暂无数据</p>
			</div>		
	
			<?php endif; ?>
	
		</div>
	</div>
	<?php if ($this->_var['pages']): ?>
		<div class="fy scroll_bottom_page">
			<?php echo $this->_var['pages']; ?>
		</div>
	<?php endif; ?>
	<div class="blank50"></div>
</div>
<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>	
<?php endif; ?>