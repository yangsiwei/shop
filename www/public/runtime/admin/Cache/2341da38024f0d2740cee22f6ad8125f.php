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

<?php function get_list_content($content)
	{
		return  msubstr(empty_tag($content),0,10);
	}
	function get_is_robot($is)
	{
		if($is)return "是";
		else
		return "否";
	}
	function get_check_status($is)
	{
		if($is)return "是";
		else
		return "否";
	} ?>
<script type="text/javascript">

</script>
<div class="main">
<div class="main_title"><?php echo ($main_title); ?></div>
<div class="blank5"></div>
<div class="button_row">
	<input type="button" class="button" value="<?php echo L("DELETE");?>" onclick="del();" />
</div>
<div class="blank5"></div>
<div class="search_row">
	<form name="search" action="__APP__" method="get">	
		用户名：<input type="text" class="textbox" name="user_name" value="<?php echo strim($_REQUEST['user_name']);?>" />		
		关键词：<input type="text" class="textbox" name="keyword" value="<?php echo strim($_REQUEST['keyword']);?>" />	
		机器人：
		<select name="is_robot">
			<option value="-1" <?php if(intval($_REQUEST['is_robot']) == '-1'): ?>selected="selected"<?php endif; ?>>全部</option>
			<option value="0" <?php if(intval($_REQUEST['is_robot']) == '0'): ?>selected="selected"<?php endif; ?>>否</option>
			<option value="1" <?php if(intval($_REQUEST['is_robot']) == '1'): ?>selected="selected"<?php endif; ?>>是</option>
		</select>
			
		<input type="hidden" value="Share" name="m" />
		<input type="hidden" value="index" name="a" />
		<input type="submit" class="button" value="<?php echo L("SEARCH");?>" />
	</form>
</div>
<div class="blank5"></div>
<!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="15" class="topTd" >&nbsp; </td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px   "><a href="javascript:sortBy('id','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('duobao_item_id','<?php echo ($sort); ?>','Share','index')" title="按照期号  <?php echo ($sortType); ?> ">期号  <?php if(($order)  ==  "duobao_item_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('user_id','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("USER_NAME");?>  <?php echo ($sortType); ?> "><?php echo L("USER_NAME");?>  <?php if(($order)  ==  "user_id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_robot','<?php echo ($sort); ?>','Share','index')" title="按照机器人  <?php echo ($sortType); ?> ">机器人  <?php if(($order)  ==  "is_robot"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('title','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("TITLE");?>  <?php echo ($sortType); ?> "><?php echo L("TITLE");?>  <?php if(($order)  ==  "title"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('content','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("CONTENT");?>   <?php echo ($sortType); ?> "><?php echo L("CONTENT");?>   <?php if(($order)  ==  "content"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('images_count','<?php echo ($sort); ?>','Share','index')" title="按照图片数  <?php echo ($sortType); ?> ">图片数  <?php if(($order)  ==  "images_count"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_check','<?php echo ($sort); ?>','Share','index')" title="按照审核状态  <?php echo ($sortType); ?> ">审核状态  <?php if(($order)  ==  "is_check"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_recommend','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("IS_RECOMMEND");?>  <?php echo ($sortType); ?> "><?php echo L("IS_RECOMMEND");?>  <?php if(($order)  ==  "is_recommend"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_index','<?php echo ($sort); ?>','Share','index')" title="按照首页显示  <?php echo ($sortType); ?> ">首页显示  <?php if(($order)  ==  "is_index"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_top','<?php echo ($sort); ?>','Share','index')" title="按照置顶  <?php echo ($sortType); ?> ">置顶  <?php if(($order)  ==  "is_top"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_effect','<?php echo ($sort); ?>','Share','index')" title="按照有效性  <?php echo ($sortType); ?> ">有效性  <?php if(($order)  ==  "is_effect"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','Share','index')" title="按照<?php echo L("CREATE_TIME");?>   <?php echo ($sortType); ?> "><?php echo L("CREATE_TIME");?>   <?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th >操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topic): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($topic["id"]); ?>"></td><td>&nbsp;<?php echo ($topic["id"]); ?></td><td>&nbsp;<?php echo ($topic["duobao_item_id"]); ?></td><td>&nbsp;<?php echo (get_user_name($topic["user_id"])); ?></td><td>&nbsp;<?php echo (get_is_robot($topic["is_robot"])); ?></td><td>&nbsp;<?php echo (msubstr($topic["title"])); ?></td><td>&nbsp;<?php echo (get_list_content($topic["content"])); ?></td><td>&nbsp;<?php echo ($topic["images_count"]); ?></td><td>&nbsp;<?php echo (get_check_status($topic["is_check"])); ?></td><td>&nbsp;<?php echo (get_toogle_status($topic["is_recommend"],$topic['id'],is_recommend)); ?></td><td>&nbsp;<?php echo (get_toogle_status($topic["is_index"],$topic['id'],is_index)); ?></td><td>&nbsp;<?php echo (get_toogle_status($topic["is_top"],$topic['id'],is_top)); ?></td><td>&nbsp;<?php echo (get_toogle_status($topic["is_effect"],$topic['id'],is_effect)); ?></td><td>&nbsp;<?php echo (to_date($topic["create_time"])); ?></td><td><a href="javascript:edit('<?php echo ($topic["id"]); ?>')"><?php echo L("EDIT");?></a>&nbsp;<a href="javascript:del('<?php echo ($topic["id"]); ?>')"><?php echo L("DELETE");?></a>&nbsp;</td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="15" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->
 

<div class="blank5"></div>
<div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>