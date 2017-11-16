<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- Mobile Devices Support @begin -->
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="false" name="twcClient" id="twcClient">
    <meta name="wap-font-scale" content="no">
    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <!--允许全屏模式-->
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <!--指定sari的样式-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta content="telephone=no" name="format-detection" />
    <!-- Mobile Devices Support @end -->
    <title>我的余额</title>
    <script type="text/javascript">
        var APP_ROOT = '<?php echo $this->_var['APP_ROOT']; ?>';
        var LOADER_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loader_img.gif';
        var LOADING_IMG = '<?php echo $this->_var['TMPL']; ?>/images/loading.gif';
        var AJAX_URL = '<?php
echo parse_url_tag("u:index|ajax|"."".""); 
?>';
        var PAGE_TYPE = '<?php echo $this->_var['PAGE_TYPE']; ?>';
        var DOMAIN_URL = '<?php
echo parse_url_tag("u:index|index|"."".""); 
?>';
    </script>
    <link rel="stylesheet" type="text/css" href="<?php 
$k = array (
  'name' => 'parse_css',
  'v' => $this->_var['pagecss'],
);
echo $k['name']($k['v']);
?>" />
    <script type="text/javascript" src="<?php 
$k = array (
  'name' => 'parse_script',
  'v' => $this->_var['pagejs'],
  'c' => $this->_var['cpagejs'],
);
echo $k['name']($k['v'],$k['c']);
?>"></script>
    <script>
        /*app 请求时候用到*/
    $(function(){
        <?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>
        App.page_title('<?php echo $this->_var['data']['page_title']; ?>');

        if($(".hide_list")){
            $(".hide_list").addClass("page_type_app");
        }
        <?php endif; ?>

        //后退
        $('#header_back_btn').click(function(){
            var Expression=/http(s)?:\/\/?/;
            var objExp=new RegExp(Expression);
            var backurl = $(this).attr('backurl');
            $(this).attr('backurl','-1');

            if(objExp.test(backurl)==true){
                location.href = backurl;
            }else{
                window.history.go(-1);
            }
        });
        function share_compleate(share_key){
            $.showSuccess("分享成功");
        }
        var share_data={};
        var share_title=$(".good-countdown > p").html();
        var share_imageUrl=$(".content").find("img").attr("src")
        share_data["share_content"]=window.location.href;
        share_data["share_url"]=window.location.href;
        share_data["key"]='';
        share_data['sina_app_api']=1;
        share_data['qq_app_api']=1;
        share_data["share_imageUrl"]=share_imageUrl?share_imageUrl:"/yydb/wap/Tpl/main/images/default_logo.png";
        share_data['share_title'] = share_title?share_title:"快快加入一夺宝币夺宝";
        share_data=JSON.stringify(share_data);
        $("#fenxiang").click(function(){
            try{
                App.sdk_share(share_data);
            }catch(e){
                $.showErr(e);
            }
        });
    });

    </script>
    <script type="text/javascript">
        //减少移动端触发"Click"事件时300毫秒的时间差
        window.addEventListener('load', function() {
            FastClick.attach(document.body);
        }, false);
    </script>
</head>
<body>
<?php if ($this->_var['PAGE_TYPE'] == 'app'): ?>

<?php else: ?>
<div class="header">
    <div class="header-wrap">
        <div class="c-hd split-line">
            <section class="cut_city">
                <a id="header_back_btn" backurl='-1'><i class="iconfont">&#xe701;</i></a>
            </section>
            <section class="logo_img">我的余额</section>
            <section style="width:2rem !important;" class="cut_city hd-right">
                <a id="hd-drop" href="javascript:void(0)"><i class="iconfont home">&#xe657;</i></a>
            </section>
            <div class="hd-drop-mask"></div>
            <ul class="hd-drop">

                <li>
                    <a class="flex-box split-line" href="<?php
echo parse_url_tag("u:index|index#index|"."".""); 
?>">
                    <i class="iconfont">&#xe6ee;</i>
                    <p>返回首页</p>
                    </a>
                </li>
                <li>
                    <a class="flex-box split-line" href="<?php
echo parse_url_tag("u:index|user_center#index|"."".""); 
?>">
                    <i class="iconfont">&#xe6f1;</i>
                    <p>用户中心</p>
                    </a>
                </li>
                <li>
                    <a class="flex-box split-line" href="<?php
echo parse_url_tag("u:index|uc_duobao_record#index|"."".""); 
?>">
                    <i class="iconfont">&#xe6ff;</i>
                    <p>夺宝记录</p>
                    </a>
                </li>
                <?php if ($this->_var['is_app'] == true): ?>
                <li>
                    <a class="flex-box split-line" id="fenxiang">
                        <i class="iconfont">&#xe6bb;</i>
                        <p>分享</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>
<script>
    $("#hd-drop").bind('click', function() {
        $(".hd-drop").toggleClass('active');
        $(".hd-drop-mask").toggleClass('active');
    });
    $(".hd-drop-mask").bind('click', function() {
        $(".hd-drop").removeClass('active');
        $(".hd-drop-mask").removeClass('active');
    });
</script>
<?php echo $this->fetch('inc/wx_share.html'); ?>
