<script type="text/javascript">
var jsondata=<?php echo $this->_var['jsondata']; ?>;
</script>
	<table id="tab" >
	<tr>
	<td width="46"><input type="checkbox" name="whole" <?php if ($this->_var['is_whole'] == 1): ?> checked="checked"<?php endif; ?>/></td>
	<td width="80">商品名称</td>
	<td width="480"></td>
	<td width="120">价值</td>
	<td width="140">夺宝单价(夺宝币/人次)</td>
	<td width="160">参与人次</td>
	<td width="120">小计</td>
	<td width="60">操作</td>
	</tr>
    <?php if ($this->_var['cart_result']['cart_list']): ?>
		
	<?php $_from = $this->_var['cart_result']['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	<tr class="cart-list">
		    <td><input type="checkbox" name="selected[]" value="<?php echo $this->_var['item']['id']; ?>" <?php if ($this->_var['item']['is_effect'] == 1): ?> checked="checked"<?php endif; ?>/></td>
			<td class="split-line text_left" data-id="<?php echo $this->_var['item']['id']; ?>">
				<div class="goods-img">
					<a href="<?php echo $this->_var['item']['url']; ?>">
						<img src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['item']['deal_icon'],
  'w' => '80',
  'h' => '80',
  'g' => '1',
);
echo $k['name']($k['v'],$k['w'],$k['h'],$k['g']);
?>" alt="<?php echo $this->_var['item']['deal_icon']; ?>">
					</a>
				</div>
			</td>
			<td class="text_left">
				<div class="flex-box">
					<p>
					<?php if ($this->_var['item']['min_buy'] == 10 || $this->_var['item']['unit_price'] == 10): ?>
						<span class="type-ten">十夺宝币专区</span>&nbsp;<?php endif; ?>
					<?php if ($this->_var['item']['unit_price'] == 100): ?>
						<span class="type-ten" style="background-color:red">百夺宝币专区</span>&nbsp;<?php endif; ?>
                        <?php if ($this->_var['item']['is_topspeed']): ?>
                        <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速专区</em>
                        <?php endif; ?>
						<a href="<?php echo $this->_var['item']['url']; ?>" ><?php echo $this->_var['item']['name']; ?></a> 
					</p>
					<p>总需<span style="color:#39f"><?php echo $this->_var['item']['max_buy']; ?></span>人次参与，还剩：<span class="rest"><?php echo $this->_var['item']['residue_count']; ?></span>人次</p>
				</div>
			</td>
			<td>  
					<p><span><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['item']['value_price'],
);
echo $k['name']($k['v']);
?></span></p>
			</td>
			<td>
			<?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['item']['unit_price'],
);
echo $k['name']($k['v']);
?>
			</td>	
			<td class="select-bar_td">
				<div class="select-bar">
				  	<!--<p>参与人次</p>-->
				  	<div class="select">
				  		<a class="num-btn minus" duobao_item_id="<?php echo $this->_var['item']['duobao_item_id']; ?>" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>">-</a>
				  		<input class="cart_input" name="num[<?php echo $this->_var['item']['id']; ?>]" class="buy_number buy-num-<?php echo $this->_var['item']['id']; ?>" type="text" value="<?php echo $this->_var['item']['number']; ?>" duobao_item_id="<?php echo $this->_var['item']['duobao_item_id']; ?>" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>" />
				  		<a class="num-btn plus" duobao_item_id="<?php echo $this->_var['item']['duobao_item_id']; ?>" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>">+</a>
				  		<!--<em>参与人次需是<?php echo $this->_var['item']['min_buy']; ?>的倍数</em>-->
				  	</div>
				  	<!--<i class="iconfont del-item-btn" data-id="<?php echo $this->_var['item']['id']; ?>">&#xe68d;</i>-->
				    </div>
			</td> 
			<td class="select-bar_total">
			<p><span class="txt-red"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['item']['total_price'],
);
echo $k['name']($k['v']);
?></span></p>
			</td> 
			<td>
			    <a href="javascript:;" onclick="del_carts(this,<?php echo $this->_var['item']['id']; ?>)" style="color:#808080">删除</a>
			</td>
	</tr>		
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr style="background:#f2f2f2">
	<td><input type="checkbox" name="whole_2" <?php if ($this->_var['is_whole'] == 1): ?> checked="checked"<?php endif; ?> /></td>
	<td><a href="javascript:void(0);" id="del_cart_whole" style="text-decoration: none;">删除选中</a></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td colspan="2" style="text-align:right;line-height: 35px;padding-right: 15px" >
		<div class="cart-list-footer-total">总计：<span class="txt-red"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['cart_result']['total_data']['total_price'],
);
echo $k['name']($k['v']);
?></span></div>
		
	</td>
	</tr>
    <?php else: ?>
    <tr class="null_data">
    	<td colspan="8" >
		<div>
		<p class="message">购物车暂无数据,<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>">马上去逛逛~</a></p>
		</div>
	    </td>
    </tr>
    <?php endif; ?>
    </table>
    <div style="width:100%;height:30px;text-align:right;">账户余额：<span class="txt-red"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['user_money'],
);
echo $k['name']($k['v']);
?></span>&nbsp;&nbsp;
    <?php if ($this->_var['fx_money']): ?>
	推广奖余额：<span class="txt-red"><?php echo $this->_var['fx_money']; ?>夺宝币</span>&nbsp;&nbsp;
	<?php endif; ?>
	<?php if ($this->_var['give_money']): ?>
	充值赠送可用：<span class="txt-red"><?php echo $this->_var['give_money']; ?>夺宝币</span>&nbsp;&nbsp;
	<?php endif; ?>
	<?php if ($this->_var['admin_money']): ?>
	管理奖余额：<span class="txt-red"><?php echo $this->_var['admin_money']; ?>夺宝币</span>
	<?php endif; ?>
	</div>
	<div class="blank20"></div>
    <div style="float:right;width:800px" >
	<button id="button_check" class="btn f_r <?php if ($this->_var['cart_result']['total_data']['total_price'] > 0): ?>go_check<?php else: ?>no_go_check<?php endif; ?>" rel="orange">去结算</button>
	<div class="blank15"></div>
	<p class="text_right" style="font-size: 13px;color:#808080;">夺宝有风险，参与需谨慎</p>
	</div>
	<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>" class="btn btn-aside">返回首页</a>