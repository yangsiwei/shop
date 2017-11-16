
<div class="nav_item_title" style="width:320px;">
	<a href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['home_user']['id']."".""); 
?>">	Ta的夺宝联盟</a>
</div>
<hr>
<div class="side_nav">
	<dl class="nav_item">

			<dd><a class="<?php if ($this->_var['MODULE_NAME'] == 'home'): ?>current<?php endif; ?>" href="<?php
echo parse_url_tag("u:index|home|"."id=".$this->_var['home_user']['id']."".""); 
?>">购买记录</a></dd>
			<dd><a class="<?php if ($this->_var['MODULE_NAME'] == 'home_luck'): ?>current<?php endif; ?>" href="<?php
echo parse_url_tag("u:index|home_luck|"."id=".$this->_var['home_user']['id']."".""); 
?>">幸运记录</a></dd>
			<dd><a class="<?php if ($this->_var['MODULE_NAME'] == 'home_share'): ?>current<?php endif; ?>" href="<?php
echo parse_url_tag("u:index|home_share|"."id=".$this->_var['home_user']['id']."".""); 
?>">Ta的晒单</a></dd>

	</dl>

<div class="blank20"></div>
</div>