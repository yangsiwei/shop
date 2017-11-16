<div class="m-user-frame-content" pro="userFrameWraper">
			<div class="m-user-duobao">
				<div class="m-user-comm-wraper clearfix home_user_info">
				<div class="home_avatar f_l">
					<img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['home_user']['id'],
  'type' => 'big',
);
echo $k['name']($k['uid'],$k['type']);
?>" />
				</div>
				<div class="home_info f_l">
					<span class="home_user_name"><?php echo $this->_var['home_user']['user_name']; ?></span>
					
					<span class="home_user_id">ID：<b><?php echo $this->_var['home_user']['id']; ?></b></span>
				</div>
				</div>
				
			</div>
</div>
<div class="blank"></div>