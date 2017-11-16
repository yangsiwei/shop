<script type="text/javascript">
	<?php if ($this->_var['is_weixin'] && $this->_var['signPackage']['appId']): ?>
	var appId = '<?php echo $this->_var['signPackage']['appId']; ?>';
	var timestamp = '<?php echo $this->_var['signPackage']['timestamp']; ?>';
	var nonceStr = '<?php echo $this->_var['signPackage']['nonceStr']; ?>';
	var signature = '<?php echo $this->_var['signPackage']['signature']; ?>';
	
	
	<?php if ($this->_var['item_data']['name']): ?>
	var page_title = '<?php echo $this->_var['item_data']['name']; ?>';
	<?php else: ?>
	var page_title = '<?php echo $this->_var['data']['page_title']; ?>';
	<?php endif; ?>
	
	var shar_url = '<?php echo $this->_var['wx_share_url']; ?>';
	
		<?php if ($this->_var['data']['advs']['0']['img']): ?>
			var imgUrl = '<?php echo $this->_var['data']['advs']['0']['img']; ?>';
		<?php elseif ($this->_var['item_data']['deal_gallery']['0']): ?>
			var imgUrl = '<?php echo $this->_var['item_data']['deal_gallery']['0']; ?>';
		<?php elseif ($this->_var['data']['share_register_qrcode']): ?>
			var imgUrl = '<?php echo $this->_var['data']['share_register_qrcode']; ?>';
		<?php else: ?>
			var imgUrl = '';
		<?php endif; ?>
		
	<?php endif; ?>

</script>

<?php if ($this->_var['is_weixin'] && $this->_var['signPackage']['appId']): ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php endif; ?>