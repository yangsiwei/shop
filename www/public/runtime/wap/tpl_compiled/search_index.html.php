<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";	
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/search_new.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/search.js";


?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<div class="wrap">
				<div class="content">

				<form name="search_form" action="<?php
echo parse_url_tag("u:index|search#do_search|"."".""); 
?>" method="post" >
				<div class="search_box">
					
					  <div class="search_text">
					  	    <div class="ico"><i class="iconfont">&#xe662;</i></div>
							<div class="box"><input type="text" name = "keyword" id = "keyword" value="<?php echo $this->_var['search_keyword']; ?>" placeholder="请输入搜索关键词"></div>
					  </div>
					  <div class="search_bottom">
					  	  <input type="hidden" name="search_type" value="<?php echo $this->_var['search_type']; ?>"/>
					  	  <input type="button" onclick = "search_submit()"  value="搜索">
					  </div>
				
				</div>	 
				</form>
               
                  
				<?php if ($this->_var['hot_kw']): ?>
				<div class="hot_tag_box">
					 <ul class="hot_list">
					   	     <li><a class="hot-link title" href="javascript:void(0);">热门搜索</a></li>
					   	     <?php $_from = $this->_var['hot_kw']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
					   	     	 <li class="hot_item"><a class="hot-link " href="javascript:void(0);"><?php echo $this->_var['row']; ?></a></li>
					   	     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					   </ul>
				</div>
				<?php endif; ?>
				</div>
			
</div>				

<?php echo $this->fetch('inc/footer_index.html'); ?>
