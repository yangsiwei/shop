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

<?php function get_handle($id,$order_item)
	{
		$str = "<a href='".u("DealOrder/view_order",array("id"=>$order_item['order_id']))."'>查看详情</a>";
		
		return $str;
	}
	function get_delivery_status($status)
	{
		if($status==0)
		return "未发货";
		else
		return "已发货";
	} ?>
<script type="text/javascript" src="__TMPL__Common/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.weebox.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/weebox.css" />
<div class="main">
<div class="main_title"><?php echo L("DEAL_ORDER");?></div>
<div class="blank5"></div>
<form name="search" action="__APP__" method="get">	

<div class="search_row">

		<?php echo L("ORDER_SN");?>：<input type="text" class="textbox" name="order_sn" value="<?php echo strim($_REQUEST['order_sn']);?>" style="width:100px;" />		
		夺宝活动期号：<input type="text" class="textbox" name="duobao_item_id" value="<?php echo strim($_REQUEST['duobao_item_id']);?>" style="width:100px;" />
		会员名称：<input type="text" class="textbox" name="user_name" value="<?php echo strim($_REQUEST['user_name']);?>" style="width:100px;" />
		发货情况：
		<select name="delivery_status_item">
			<option value="-1" <?php if(intval($_REQUEST['delivery_status_item']) == '-1'): ?>selected="selected"<?php endif; ?>>全部</option>
			<option value="0" <?php if(intval($_REQUEST['delivery_status_item']) == '0'): ?>selected="selected"<?php endif; ?>>未发货</option>
			<option value="1" <?php if(intval($_REQUEST['delivery_status_item']) == 1): ?>selected="selected"<?php endif; ?>>已发货</option>
		</select>
        是否填写地址:
        <select name="is_set_consignee">
            <option value="-1" <?php if(intval($_REQUEST['is_set_consignee']) == '-1'): ?>selected="selected"<?php endif; ?>>全部</option>
            <option value="0" <?php if(intval($_REQUEST['is_set_consignee']) == '0'): ?>selected="selected"<?php endif; ?>>未填写</option>
            <option value="1" <?php if(intval($_REQUEST['is_set_consignee']) == 1): ?>selected="selected"<?php endif; ?>>已填写</option>
        </select>
		<input type="hidden" value="DealOrder" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />

</div>
</form>
	<!--start 五倍开奖-->

<div class="blank5"></div>
	<table id="dataTable" class="dataTable" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
			<td colspan="9" class="topTd">&nbsp; </td>
		</tr>
		<tr class="row">
			<th width="50px"><a href="javascript:sortBy('id','1','DealOrder','index')" title="按照编号升序排列 ">编号</a></th>
			<th><a href="javascript:sortBy('name','1','DealOrder','index')" title="按照活动名称升序排列 ">五倍开奖活动名称</a></th>
			<th><a href="javascript:sortBy('order_sn','1','DealOrder','index')" title="按照订单编号升序排列 ">订单编号</a></th>
			<th><a href="javascript:sortBy('duobao_item_id','1','DealOrder','index')" title="按照期号升序排列 ">期号</a></th>
			<th><a href="javascript:sortBy('user_id','1','DealOrder','index')" title="按照会员名称升序排列 ">会员名称<img src="/blog--fang-duobaoShop-master/duobaoShop/admin/Tpl/default/Common/images/desc.gif" width="12" height="17" border="0" align="absmiddle" /></a></th>
			<th><a href="javascript:sortBy('create_time','1','DealOrder','index')" title="按照下单时间升序排列 ">下单时间</a></th>
			<th><a href="javascript:sortBy('delivery_status','1','DealOrder','index')" title="按照发货升序排列 ">发货</a></th>
			<th><a href="javascript:sortBy('lottery_sn','1','DealOrder','index')" title="按照中奖幸运号升序排列 ">中奖幸运号</a></th>
			<th>操作</th>
		</tr>
		<?php if(!empty($five)): ?><?php if(is_array($five)): foreach($five as $key=>$vo): ?><tr class="row">
					<td>&nbsp;<?php echo ($vo["duobao_id"]); ?></td>
					<td>&nbsp;<?php echo ($vo["name"]); ?></td>
					<td>&nbsp;<?php echo ($vo["success"]); ?></td>
					<td>&nbsp;<?php echo ($vo["id"]); ?></td>
					<td>&nbsp;<?php echo ($vo["luck_user_id"]); ?></td>
					<td>&nbsp;<?php echo ($vo["create_time"]); ?></td>
					<td>
					<?php if($vo["take_effect"] == 0 ): ?>未发货
					<?php else: ?>
						已发货<?php endif; ?>
					</td>
					<td>&nbsp;<?php echo ($vo["lottery_sn"]); ?></td>
					<td> <a href="/porche.php?m=DealOrder&amp;a=view_order&amp;id=<?php echo ($vo["id"]); ?>&amp;zq=is_five">查看详情</a>&nbsp;</td>
				</tr><?php endforeach; endif; ?><?php endif; ?>
		<tr>
			<td colspan="9" class="bottomTd"> &nbsp;</td>
		</tr>
		</tbody>
	</table>
	<!--end 五倍开奖-->
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="9" class="topTd" >&nbsp; </td></tr><tr class="row" ><th width="50px"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','DealOrder','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('name','<?php echo ($sort); ?>','DealOrder','index')" title="按照活动名称<?php echo ($sortType); ?> ">活动名称<?php if(($order)  ==  "name"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('order_sn','<?php echo ($sort); ?>','DealOrder','index')" title="按照订单编号<?php echo ($sortType); ?> ">订单编号<?php if(($order)  ==  "order_sn"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('duobao_item_id','<?php echo ($sort); ?>','DealOrder','index')" title="按照期号<?php echo ($sortType); ?> ">期号<?php if(($order)  ==  "duobao_item_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('user_id','<?php echo ($sort); ?>','DealOrder','index')" title="按照<?php echo L("USER_NAME");?><?php echo ($sortType); ?> "><?php echo L("USER_NAME");?><?php if(($order)  ==  "user_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','DealOrder','index')" title="按照<?php echo L("ORDER_CREATE_TIME");?><?php echo ($sortType); ?> "><?php echo L("ORDER_CREATE_TIME");?><?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('delivery_status','<?php echo ($sort); ?>','DealOrder','index')" title="按照发货<?php echo ($sortType); ?> ">发货<?php if(($order)  ==  "delivery_status"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('lottery_sn','<?php echo ($sort); ?>','DealOrder','index')" title="按照中奖幸运号<?php echo ($sortType); ?> ">中奖幸运号<?php if(($order)  ==  "lottery_sn"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th >操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$deal_order_item): ++$i;$mod = ($i % 2 )?><tr class="row" ><td>&nbsp;<?php echo ($deal_order_item["id"]); ?></td><td>&nbsp;<?php echo ($deal_order_item["name"]); ?></td><td>&nbsp;<?php echo ($deal_order_item["order_sn"]); ?></td><td>&nbsp;<?php echo ($deal_order_item["duobao_item_id"]); ?></td><td>&nbsp;<?php echo (get_user_name($deal_order_item["user_id"])); ?></td><td>&nbsp;<?php echo (to_date($deal_order_item["create_time"])); ?></td><td>&nbsp;<?php echo (get_delivery_status($deal_order_item["delivery_status"])); ?></td><td>&nbsp;<?php echo ($deal_order_item["lottery_sn"]); ?></td><td> <?php echo (get_handle($deal_order_item["id"],$deal_order_item)); ?>&nbsp;</td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="9" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 

<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>