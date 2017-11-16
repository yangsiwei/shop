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

<!-- <script type="text/javascript" src="__TMPL__Common/js/fx_salary.js"></script> -->
<div class="main">
    <div class="main_title"><?php echo ($title_name); ?></div>
    <div class="blank5"></div>
    <div class="button_row">

    </div>
    <div class="blank5"></div>
    <form name="edit" action="<?php echo U('edit');?>" method="post" enctype="multipart/form-data">
        <table class="form" cellpadding=0 cellspacing=0>
            <tr>
                <td colspan=2 class="topTd"></td>
            </tr>


            <tr>
                <td class="item_title">爱贝:</td>
                <td>
                    <button class="shan_open shan">开启</button>
                    <button class="shan_close shan">关闭</button>
                </td>
            </tr>

            <tr>
                <td class="item_title">付钱啦支付:</td>
                <td>
                    <button class="fql_open fql">开启</button>
                    <button class="fql_close fql">关闭</button>
                </td>
            </tr>

            <tr>
                <td class="item_title">数科宝支付:</td>
                <td>
                    <button class="skb_open skb">开启</button>
                    <button class="skb_close skb">关闭</button>
                </td>
            </tr>

        </table>
    </form>
</div>

<script>
    $(function(){

        var shan = <?php echo ($shan); ?>;
        var fql = <?php echo ($fql); ?>;
        var skb = <?php echo ($skb); ?>;
        console.log(skb);
        if(shan == 1){
            $(".shan_close").show();
            $(".shan_open").hide();
        }else{
            $(".shan_close").hide();
            $(".shan_open").show();
        }

        if(fql == 1){
            $(".fql_close").show();
            $(".fql_open").hide();
        }else{
            $(".fql_close").hide();
            $(".fql_open").show();
        }

        if(skb == 1){
            $(".skb_close").show();
            $(".skb_open").hide();
        }else{
            $(".skb_close").hide();
            $(".skb_open").show();
        }

        $(".shan_open").click(function(){
            $(".shan_open").hide();
            $(".shan_close").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('shan_open');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data.info);
                }
            });
        });

        $(".shan_close").click(function(){
            $(".shan_close").hide();
            $(".shan_open").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('shan_close');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data['info']);
                }
            });
        });

        $(".fql_open").click(function(){
            $(".fql_open").hide();
            $(".fql_close").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('fql_open');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data['info']);
                }
            });
        });
        $(".fql_close").click(function(){
            $(".fql_close").hide();
            $(".fql_open").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('fql_close');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data['info']);
                }
            });
        });

        $(".skb_open").click(function(){
            $(".skb_open").hide();
            $(".skb_close").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('skb_open');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data['info']);
                }
            });
        });
        $(".skb_close").click(function(){
            $(".skb_close").hide();
            $(".skb_open").show();
            $.ajax({
                type: "POST",
                url: "<?php echo U('skb_close');?>",
                data: "111",
                dataType: "json",
                success: function(data){
                    alert(data['info']);
                }
            });
        });

    });
</script>

</body>
</html>