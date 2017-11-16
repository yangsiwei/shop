<?php if (! $this->_var['is_app']): ?>
<?php if ($this->_var['is_show_down']): ?>
<!--<div class="Client">-->

	<!--<div class="Client_de">-->
		<!--<a href="javascript:void(0);" class="close_but">-->
			<!--<i class="iconfont">&#xe608;</i>-->
		<!--</a>&lt;!&ndash;关闭扭&ndash;&gt;-->
		<!--<div class="transcript" style="float:left;">-->
			<!--<div class="index_footer_logo"></div>-->
			<!--<div style="float:left; margin-left:10px;">-->
			<!--立即下载APP<br />-->
			<!--随时购买</div>-->
		<!--</div>-->
		<!--<a href="<?php echo $this->_var['data']['mobile_btns_download']; ?>" class="go_download" style="float:right;">-->
			<!--立即下载-->
		<!--</a>-->
	<!--</div>-->
	<!--<div class="Client_bg">-->
	<!--</div>-->

<!--</div>-->
<?php endif; ?>
<?php endif; ?>
           <div class="gotop" data-com="gotop">
				<a href="#">
					<i class="iconfont"></i>
				</a>
			</div>
			<?php if ($this->_var['is_lottery'] == 1 && $this->_var['MODULE_NAME'] != 'uc_winlog'): ?>
				<?php echo $this->_var['lottery_html']; ?>
			<?php endif; ?>
			
	</body>
<html>
