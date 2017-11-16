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

<?php function get_handle($id, $item)
    {
    $str.="<a href='javascript:view(".$id.");'>".查看."&nbsp;</a>";
    $str.="<a href='javascript:foreverdel(".$id.");'>".彻底删除."&nbsp;</a>";
    if($item['type']==-1&&$item['push_type']!=1)
    {
        $str.="<a href='javascript:edit(".$id.");'>".编辑."&nbsp;</a>";
        $str.="<a href='javascript:appbroadcast(".$id.",1,".$item['push_type'].");'>".安卓推送."&nbsp;</a>";
        $str.="<a href='javascript:appbroadcast(".$id.",2,".$item['push_type'].");'>".ios推送."&nbsp;</a>";
        $str.="<a href='javascript:appbroadcast(".$id.",0,".$item['push_type'].");'>".安卓和ios推送."&nbsp;</a>";
    }else if($item['push_type']==1&&$item['is_read']==0){
        $str.="<a href='javascript:edit(".$id.");'>".编辑."&nbsp;</a>";
      if($item['android_device_tokens']){
        $str.="<a href='javascript:appbroadcast(".$id.",1,".$item['push_type'].");'>".安卓推送."&nbsp;</a>";
      }else if($item['ios_device_tokens']){
        $str.="<a href='javascript:appbroadcast(".$id.",2,".$item['push_type'].");'>".ios推送."&nbsp;</a>";
      }else{
        $str.="<a href='javascript:return false;'>无法推送</a>";
      }
    }
    return $str;
    }
    function get_push_type($type){
        switch($type){
            case '1':return '单播';
            case '2':return '自定义播';
            case '3':return '组播';
            case '4':return '广播';
            case '5':return '文件播';
            default:return '广播';
        }
    } ?>
<script type="text/javascript">
    function view(id)
    {
        location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=view&id="+id;
    }
    function edit(id){
        location.href = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=edit&id="+id;
    }
    function appbroadcast(id,type,push_type){
        $.ajax({
            url:ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=appbroadcast",
            method:"post",
            dataType:"json",
            data:{id:id,type:type,push_type:push_type},
            success:function(da){
                    alert(da.info);
                    window.location.href=da.jumpUrl;
            },
            error:function(da){
                alert('发送错误');
            }
        })
    }
</script>
<div class="main">
    <div class="main_title"><?php echo ($main_title); ?></div>
    <div class="blank5"></div>
    <div class="button_row">
        <input type="button" class="button" value="<?php echo L("ADD");?>" onclick="add();" />
        <input type="button" class="button" value="<?php echo L("FOREVERDEL");?>" onclick="foreverdel();" />
    </div>
    <div class="blank5"></div>
    <!-- Think 系统列表组件开始 -->
<table id="dataTable" class="dataTable" cellpadding=0 cellspacing=0 ><tr><td colspan="8" class="topTd" >&nbsp; </td></tr><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('dataTable')"></th><th width="50px"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照<?php echo L("ID");?><?php echo ($sortType); ?> "><?php echo L("ID");?><?php if(($order)  ==  "id"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('content','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照内容<?php echo ($sortType); ?> ">内容<?php if(($order)  ==  "content"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('pusher','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照推送者<?php echo ($sortType); ?> ">推送者<?php if(($order)  ==  "pusher"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照<?php echo L("CREATE_TIME");?><?php echo ($sortType); ?> "><?php echo L("CREATE_TIME");?><?php if(($order)  ==  "create_time"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('is_read','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照是否已推送<?php echo ($sortType); ?> ">是否已推送<?php if(($order)  ==  "is_read"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('push_type','<?php echo ($sort); ?>','MsgBroadcast','index')" title="按照类型<?php echo ($sortType); ?> ">类型<?php if(($order)  ==  "push_type"): ?><img src="__TMPL__Common/images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th >操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): ++$i;$mod = ($i % 2 )?><tr class="row" ><td><input type="checkbox" name="key" class="key" value="<?php echo ($item["id"]); ?>"></td><td>&nbsp;<?php echo ($item["id"]); ?></td><td>&nbsp;<?php echo ($item["content"]); ?></td><td>&nbsp;<?php echo ($item["pusher"]); ?></td><td>&nbsp;<?php echo (to_date($item["create_time"])); ?></td><td>&nbsp;<?php echo (get_toogle_status($item["is_read"],$item['id'],is_read)); ?></td><td>&nbsp;<?php echo (get_push_type($item["push_type"])); ?></td><td> <?php echo (get_handle($item["id"],$item)); ?>&nbsp;</td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td colspan="8" class="bottomTd"> &nbsp;</td></tr></table>
<!-- Think 系统列表组件结束 -->

    <div class="blank5"></div>
    <div class="page"><?php echo ($page); ?></div>
</div>
</body>
</html>