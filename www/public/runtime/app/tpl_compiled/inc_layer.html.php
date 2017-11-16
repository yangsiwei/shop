<div id="<?php if ($this->_var['root']['is_login']): ?> my-layer<?php else: ?>others-layer<?php endif; ?>">
	<div class="box_container others-code">
		<span id="my_duobao" style="display: none;">
		<a class="box_container-close">×</a>
		<div class="msgbox-hd">我的云号码</div>
		<div class="msgbox-bd">
			<div class="msgbox-info">
				<div class="msgbox-info-hd">我本期共参与了<?php echo $this->_var['root']['duobao_recode_count']; ?>人次</div>
				<div class="code-list">
					<dl class="items">
					<?php $_from = $this->_var['root']['duobao_recode_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_recode');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_recode']):
?>
						<dt class="items-time"><?php echo $this->_var['duobao_recode']['create_time']; ?></dt>
						<dd>
							<?php $_from = $this->_var['duobao_recode']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_recode_row');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_recode_row']):
?>
								<span class="itemcode <?php if ($this->_var['duobao_recode_row']['lottery_sn'] == $this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?>txt-impt<?php endif; ?>"><?php echo $this->_var['duobao_recode_row']['lottery_sn']; ?></span>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</dd>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</dl>
				</div>
			</div>
		</div>
		</span>
		<span id="ta_duobao" style="display: none;">
			<?php if ($this->_var['root']['is_login'] != 1): ?>
		<a class="box_container-close">×</a>
		<div class="msgbox-hd">Ta的云号码</div>
		<div class="msgbox-bd">
			<div class="msgbox-info">
				<div class="msgbox-info-hd">Ta本期共参与了<?php echo $this->_var['root']['ta_duobao_recode_count']; ?>人次</div>
				<div class="code-list">
					<dl class="items">
					<?php $_from = $this->_var['root']['ta_duobao_recode_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ta_duobao_recode');if (count($_from)):
    foreach ($_from AS $this->_var['ta_duobao_recode']):
?>
						<dt class="items-time"><?php echo $this->_var['ta_duobao_recode']['create_time']; ?></dt>
						<dd><?php $_from = $this->_var['ta_duobao_recode']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ta_duobao_recode_row');if (count($_from)):
    foreach ($_from AS $this->_var['ta_duobao_recode_row']):
?>
							<span class="itemcode <?php if ($this->_var['ta_duobao_recode_row']['lottery_sn'] == $this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?>txt-impt<?php endif; ?>"><?php echo $this->_var['ta_duobao_recode_row']['lottery_sn']; ?></span>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</dd>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</dl>
				</div>
			</div>
		</div>
			<?php else: ?>
			<a class="box_container-close">×</a>
		<div class="msgbox-hd">我的云号码</div>
		<div class="msgbox-bd">
			<div class="msgbox-info">
				<div class="msgbox-info-hd">我本期共参与了<?php echo $this->_var['root']['duobao_recode_count']; ?>人次</div>
				<div class="code-list">
					<dl class="items">
					<?php $_from = $this->_var['root']['duobao_recode_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_recode');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_recode']):
?>
						<dt class="items-time"><?php echo $this->_var['duobao_recode']['create_time']; ?></dt>
						<dd>
							<?php $_from = $this->_var['duobao_recode']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_recode_row');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_recode_row']):
?>
								<span class="itemcode <?php if ($this->_var['duobao_recode_row']['lottery_sn'] == $this->_var['root']['item_data']['luck_lottery']['lottery_sn']): ?>txt-impt<?php endif; ?>"><?php echo $this->_var['duobao_recode_row']['lottery_sn']; ?></span>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</dd>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</dl>
				</div>
			</div>
		</div>
			<?php endif; ?>
		</span>
	</div>
</div>