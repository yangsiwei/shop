<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobaos.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/TouchSlide.1.1.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/onload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/swipe.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>

<?php echo $this->fetch('inc/header_title_home.html'); ?>

<script type="text/javascript">
  //减少移动端触发"Click"事件时300毫秒的时间差
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);

</script>

<?php endif; ?>

<div class="wrap  loading_container" id="loading_container">
    <div class="content">
    <?php if ($this->_var['list']): ?>
      <ul class="goods-list scroll_bottom_list">
      
      	<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
        <li>
          <!--<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">-->
		  <div>
		  	
          <div class="imgbox">   
		      <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">   	
            <img src="<?php echo $this->_var['item']['icon']; ?>" alt="">		
			    </a>
		  <?php if ($this->_var['item']['min_buy'] == 10 || $this->_var['item']['unit_price'] == 10): ?>
          <div class="tenyen"></div>
		  <?php endif; ?>	
		   <?php if ($this->_var['item']['unit_price'] == 100): ?>
          <div class="hundredyen"></div>
		  <?php endif; ?>	
          </div>
		  
          <div class="info">
          	 <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
            <header><?php if ($this->_var['item']['is_topspeed']): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                <?php endif; ?><?php echo $this->_var['item']['name']; ?></header>
            <progress max="<?php echo $this->_var['item']['max_buy']; ?>" value="<?php echo $this->_var['item']['current_buy']; ?>"></progress>
            <p class="fl">总需<span class="all"><?php echo $this->_var['item']['max_buy']; ?></span></p>
            <p class="fr">剩余<span class="rest"><?php echo $this->_var['item']['surplus_buy']; ?></span></p>
			</a>
          </div>
		 
		  <a class="right-box add_cart_item" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data-id="<?php echo $this->_var['item']['id']; ?>">参与</a>
		  </div>
		  
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </ul>
	  <?php if ($this->_var['pages']): ?>
	<div class="fy scroll_bottom_page">
		<?php echo $this->_var['pages']; ?>
	</div>
	<?php endif; ?>
	<?php else: ?>
	<!-- 无数据↓ -->
    <div class="null_data">
      <p class="icon"><i class="iconfont">&#xe6e8;</i></p>
      <p class="message">暂无数据</p>
    </div>
	<?php endif; ?>
    </div>
</div>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/add_to_list.html'); ?>
<?php endif; ?>
