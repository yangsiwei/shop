<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/relate_goods.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/layer.css";

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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/layer.m/layer.m.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/layer.m/layer.m.js";





?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<style type="text/css">
	/**iphone6**/
	@media screen and (min-width: 321px) and (max-width: 375px){
		.page_detail{
			transform-origin: 0px 0px 0px;
			transform: scale(0.350988);
			-moz-transform: scale(0.400988);width:980px;height:2000px;
			/*width:980px;*/
			height:1000px;

		}

		.desc{text-align:center;margin-left:61px;}
		.desc img{width:90%;}
	}
	/**iphone5**/
	@media screen and (max-width: 320px) {
		.page_detail { 
			transform-origin: 0px 0px 0px;
			transform: scale(0.300988);
			-moz-transform: scale(0.400988);
			width:980px;
			height:1000px;
		} 
		.desc{text-align:center;margin-left:30px;}

	}
	/**iphone6plue**/
	@media screen and  (min-width: 376px) and (max-width: 414px)  {
		.page_detail { 
			transform-origin: 0px 0px 0px;
			transform: scale(0.40988);
			-moz-transform: scale(0.400988);
			
			height:2000px;
			width:980px;
		} 
		.desc{text-align:center;margin-left:17px;}
	}
	@media screen and  (min-width:768px)  {
		.page_detail { 
			transform-origin: 0px 0px 0px;
			transform: scale(0.75988);
			-moz-transform: scale(0.400988);
			
			height:2000px;
			width:980px;
		} 
		.desc{text-align:center;margin-left:11px;}
	}
    /*transform: scale(0.380988)*/
</style>
<div class="wrap page_detail">

<div class="content desc">
   <?php echo $this->_var['data']['desc']; ?>
</div>
</div>
<?php echo $this->fetch('inc/no_footer.html'); ?>
