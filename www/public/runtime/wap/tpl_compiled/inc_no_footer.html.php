<?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>
<?php else: ?>

<?php endif; ?>
	
		<?php if ($this->_var['is_lottery'] == 1 && $this->_var['MODULE_NAME'] != 'uc_winlog'): ?>
			<?php echo $this->_var['lottery_html']; ?>
		<?php endif; ?>
	</body>
<html>