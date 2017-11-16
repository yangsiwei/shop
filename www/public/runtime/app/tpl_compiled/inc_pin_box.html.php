<div class="pin-box">
			<div class="pic-box">
				<table>
					<tbody>
						<tr>
							<td valign="middle" align="center"><a href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['row']['id']."".""); 
?>" target="_blank" title="<?php echo $this->_var['row']['title']; ?>">
							<img  src="<?php 
$k = array (
  'name' => 'get_spec_image',
  'v' => $this->_var['row']['img']['o_path'],
  'w' => '255',
  'h' => '255',
);
echo $k['name']($k['v'],$k['w'],$k['h']);
?>"   width="270" height="<?php echo intval(270/$this->_var['row']['img']['width']*$this->_var['row']['img']['height']); ?>" ></a></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="duobao-info">
				<div class="name"><a target="_blank" href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['row']['id']."".""); 
?>"><?php echo $this->_var['row']['duobao_item']['name']; ?></a></div>
				<div class="code">幸运号码：<strong class="txt-impt"><?php echo $this->_var['row']['duobao_item']['lottery_sn']; ?></strong></div>
			</div>
			<div class="lottery-info">
				<div class="title">
					<a target="_blank" href="<?php
echo parse_url_tag("u:index|share#detail|"."id=".$this->_var['row']['id']."".""); 
?>">
						<strong><?php echo $this->_var['row']['title']; ?></strong>
					</a>
				</div>
				<div class="author">
					<a target="_blank" href="<?php
echo parse_url_tag("u:index|home#index|"."id=".$this->_var['row']['user_id']."".""); 
?>" title="<?php echo $this->_var['row']['user_name']; ?>(ID:<?php echo $this->_var['row']['user_id']; ?>)"><?php echo $this->_var['row']['user_name']; ?></a>
					<span class="time">
					 <?php 
		               if(isset($_SESSION["saitime"])){
		                 echo $_SESSION["saitime"];
		               }else{ ?>
		                <?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['share_info']['create_time'],
);
echo $k['name']($k['v']);
?>
		               
		               <?php } ?>
					</span>
				</div>
				<div class="abbr">
                	<?php echo $this->_var['row']['content']; ?>
            	</div>
			</div>
		</div>
		