<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";		
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_address.css";	
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
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_address.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_address.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<script type="text/javascript" src="<?php echo $this->_var['APP_ROOT']; ?>/public/runtime/region.js"></script>	
<div class="wrap">
	<div class="content">
					<form name="my_address" method="post" action='<?php
echo parse_url_tag("u:index|uc_address#save|"."".""); 
?>'>
						<input type="hidden" value="<?php echo $this->_var['order_item_id']; ?>" name="order_item_id"  />	
						<input type="hidden" value="<?php echo $this->_var['data']['consignee_info']['id']; ?>" name="region_id"  />	
                        <input type="hidden" name="post_xpoint" id="post_xpoint" value="<?php echo $this->_var['data']['consignee_info']['xpoint']; ?>"/>
                        <input type="hidden" name="post_ypoint" id="post_ypoint" value="<?php echo $this->_var['data']['consignee_info']['ypoint']; ?>"/>
						<dl class="address_input">
							<dt>收<em></em>件<em></em>人</dt>
							<dd>
								<input class="ui-textbox" value="<?php echo $this->_var['data']['consignee_info']['consignee']; ?>" name="consignee" holder="请输入收货人姓名" />								
							</dd>

							<dt>省<em></em>市<em></em>区</dt>
							<dd class="select-address">
								
								<div class="p_c_r" style="display:none;">	
								<select name="region_lv1" class="region_select">
                                	<option value="1">中国</option>
<!--								    <option value="0">=请选择=</option>
									<?php $_from = $this->_var['data']['region_lv1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv1');if (count($_from)):
    foreach ($_from AS $this->_var['lv1']):
?>
									<option <?php if ($this->_var['lv1']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv1']['id']; ?>"><?php echo $this->_var['lv1']['name']; ?></option>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
							  	</select>
							  	</div>								
								
								<div class="p_c_r">				
									<select name="region_lv2" class="region_select" >
										<option value="0">=请选择=</option>
										<?php $_from = $this->_var['data']['region_lv2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv2');if (count($_from)):
    foreach ($_from AS $this->_var['lv2']):
?>
										<option <?php if ($this->_var['lv2']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv2']['id']; ?>"><?php echo $this->_var['lv2']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									  </select>
								</div>
								<div class="p_c_r">					
									<select name="region_lv3" class="region_select" >
										<option value="0">=请选择=</option>		
										<?php $_from = $this->_var['data']['region_lv3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv3');if (count($_from)):
    foreach ($_from AS $this->_var['lv3']):
?>
										<option <?php if ($this->_var['lv3']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv3']['id']; ?>"><?php echo $this->_var['lv3']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									 </select>
								 </div>
								<div class="p_c_r">				
									<select name="region_lv4" class="region_select" >
										<option value="0">=请选择=</option>
										<?php $_from = $this->_var['data']['region_lv4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lv4');if (count($_from)):
    foreach ($_from AS $this->_var['lv4']):
?>
										<option <?php if ($this->_var['lv4']['selected'] == 1): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_var['lv4']['id']; ?>"><?php echo $this->_var['lv4']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									  </select>
								  </div>

							</dd>

							<dt>详细地址</dt>
							<dd>
								<input  name="address" value="<?php echo $this->_var['data']['consignee_info']['address']; ?>" class="ui-textbox" holder="请输入收货地址" />
								
							</dd>							
							
							<dt>邮<em></em><em></em><em></em><em></em>编</dt>
							<dd>
								<input  name="zip" value="<?php echo $this->_var['data']['consignee_info']['zip']; ?>" class="ui-textbox" holder="请输入邮编" />
								
							</dd>

							<dt>手<em></em><em></em><em></em><em></em>机</dt>
							<dd>
								<input  name="mobile" value="<?php echo $this->_var['data']['consignee_info']['mobile']; ?>" class="ui-textbox" holder="请输入收货人手机" />
								
							</dd>
							<dd>
							
							</dd>
						</dl>	
							
                        <div style="padding: 0 10px;"><button type="button" value="确定"   name="commit"  id="sub_address" class="sub_address"  >确定</button></div>					



				</form>
	</div>
</div>
<?php echo $this->fetch('inc/footer_index.html'); ?>	