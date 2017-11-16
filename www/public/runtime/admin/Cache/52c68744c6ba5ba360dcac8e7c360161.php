<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<style type="text/css">
	
/**
 * 自定义的font-face
 */
@font-face {font-family: "diyfont";
  src: url('<?php echo APP_ROOT; ?>/public/iconfont/iconfont.eot?r=<?php echo time(); ?>'); /* IE9*/
  src: url('<?php echo APP_ROOT; ?>/public/iconfont/iconfont.eot?#iefix&r=<?php echo time(); ?>') format('embedded-opentype'), /* IE6-IE8 */
  url('<?php echo APP_ROOT; ?>/public/iconfont/iconfont.woff?r=<?php echo time(); ?>') format('woff'), /* chrome、firefox */
  url('<?php echo APP_ROOT; ?>/public/iconfont/iconfont.ttf?r=<?php echo time(); ?>') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
  url('<?php echo APP_ROOT; ?>/public/iconfont/iconfont.svg#iconfont&r=<?php echo time(); ?>') format('svg'); /* iOS 4.1- */}
.diyfont {
  font-family:"diyfont" !important;
  font-size:20px;
  font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}

</style>
<script type="text/javascript">
 	var VAR_MODULE = "<?php echo conf("VAR_MODULE");?>";
	var VAR_ACTION = "<?php echo conf("VAR_ACTION");?>";
	var MODULE_NAME	=	'<?php echo MODULE_NAME; ?>';
	var ACTION_NAME	=	'<?php echo ACTION_NAME; ?>';
	var ROOT = '__APP__';
	var ROOT_PATH = '<?php echo APP_ROOT; ?>';
	var CURRENT_URL = '<?php echo btrim($_SERVER['REQUEST_URI']);?>';
	var INPUT_KEY_PLEASE = "<?php echo L("INPUT_KEY_PLEASE");?>";
	var TMPL = '__TMPL__';
	var APP_ROOT = '<?php echo APP_ROOT; ?>';
	
	//关于图片上传的定义
	var UPLOAD_SWF = '__TMPL__Common/js/Moxie.swf';
	var UPLOAD_XAP = '__TMPL__Common/js/Moxie.xap';
	var MAX_IMAGE_SIZE = '1000000';
	var ALLOW_IMAGE_EXT = 'zip';
	var UPLOAD_URL = '<?php echo u("File/do_upload_icon");?>';
	var ICON_FETCH_URL = '<?php echo u("File/fetch_icon");?>';
	var ofc_swf = '__TMPL__Common/js/open-flash-chart.swf';
</script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.timer.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.ui.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/jquery.ui.css" />
<script type="text/javascript" src="__TMPL__Common/js/plupload.full.min.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/ui_upload.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.weebox.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<script type="text/javascript" src="__TMPL__Common/js/swfobject.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/script.js"></script>

<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
<script type='text/javascript'  src='__ROOT__/admin/public/kindeditor/kindeditor.js'></script>
</head>
<body>
<div id="info"></div>

<script type="text/javascript">
	var sale_line_data_url = '<?php echo urlencode(u("Ofc/sale_month_line",array("year"=>$cyear,"month"=>$cmonth)));?>';
</script>
<script type="text/javascript" src="__TMPL__Common/js/balance.js"></script>
<div class="main">
<div class="main_title"> <?php echo ($balance_title); ?>报表</div>

<div class="blank5"></div>
<div class="search_row">
	<form name="search" id="balance_index_search_form" action="__APP__" method="get">
		<?php echo L("SEARCH_REFERER_TIME");?>：
		<select name="year">
			<?php if(is_array($year_list)): foreach($year_list as $key=>$year): ?><option value="<?php echo ($year["year"]); ?>" <?php if($year['current']): ?>selected="selected"<?php endif; ?>><?php echo ($year["year"]); ?>年</option><?php endforeach; endif; ?>
		</select>
		<select name="month">
			<?php if(is_array($month_list)): foreach($month_list as $key=>$month): ?><option value="<?php echo ($month["month"]); ?>" <?php if($month['current']): ?>selected="selected"<?php endif; ?>><?php echo ($month["month"]); ?>月</option><?php endforeach; endif; ?>
		</select>
		<select name="type">					
			<option value="2" <?php if($type == 2): ?>selected="selected"<?php endif; ?>>会员充值明细</option>
			<option value="4" <?php if($type == 4): ?>selected="selected"<?php endif; ?>>会员提现明细</option>	
			<option value="1" <?php if($type == 1): ?>selected="selected"<?php endif; ?>>销售明细</option>		
		</select>
		<input type="hidden" value="Balance" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
		<input type="button" class="button" value="清空当月报表" onclick="clear_balance('<?php echo u("Balance/foreverdelete",array("month"=>$cmonth,"year"=>$cyear));?>');" />
        <input type="button" class="button" value="导出Excel" id="export_excel" url="<?php echo U("Balance/export_excel");?>"/>
	</form>
</div>
<div class="blank5"></div>

<table class="form" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=5 class="topTd"></td>
	</tr>
	<tr>
		<th colspan=5><?php echo ($month_title); ?> 运营数据<br />本月账面结余：总收入<?php echo (format_price($stat_result["income_money"])); ?> - 总支出<?php echo (format_price($stat_result["out_money"])); ?> = <?php echo (format_price($accout_money)); ?></th>
	</tr>
	
	<tr>
		<td>
			<div id="sale_line_data_chart"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan=5 class="bottomTd"></td>
	</tr>
</table>

<div class="blank5"></div>
<div class="search_row" style="text-align:right;">
	【<?php echo ($balance_title); ?>总计：<?php echo (format_price($sum_money)); ?>】 【<?php echo ($balance_title); ?>本页总计：<?php echo (format_price($page_sum_money)); ?>】
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="4" class="topTd" >&nbsp; </td></tr><tr class="row" ><th width="50px"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','Balance','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('log_info','<?php echo ($sort); ?>','Balance','index')" title="按照日志<?php echo ($sortType); ?> ">日志<?php if(($order)  ==  "log_info"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="100px"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','Balance','index')" title="按照金额<?php echo ($sortType); ?> ">金额<?php if(($order)  ==  "money"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th width="200px"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','Balance','index')" title="按照发生日期<?php echo ($sortType); ?> ">发生日期<?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): ++$i;$mod = ($i % 2 )?><tr class="row" ><td>&nbsp;<?php echo ($log["id"]); ?></td><td>&nbsp;<?php echo ($log["log_info"]); ?></td><td>&nbsp;<?php echo (format_price($log["money"])); ?></td><td>&nbsp;<?php echo (to_date($log["create_time"])); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="4" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->

<div class="blank5"></div>
<div class="search_row" style="text-align:right;">
	【<?php echo ($balance_title); ?>总计：<?php echo (format_price($sum_money)); ?>】 【<?php echo ($balance_title); ?>本页总计：<?php echo (format_price($page_sum_money)); ?>】
</div>
<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>