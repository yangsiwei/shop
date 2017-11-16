<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/relate_goods.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/layer.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";



$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/jQuery.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/jQuery.js";






?>

<?php echo $this->fetch('inc/header_title_home.html'); ?>

<style>
#jl:hover{background:#d93a55;color:#fff;}
</style>
</head>
<body>
<div style="width: 100%;height: 40px;">
    <ul style="height: 100%;">
        <li id="jl" style="float: left;margin: 0 auto;width: 50%; background: #fff;text-align: center;height: 100%;line-height: 40px;color: #d93a55;">领红包</li>
        <li id="jl1" style="background: #fff;text-align: center;height: 100%;line-height: 40px;color: #d93a55;">签到记录</li>
    </ul>
</div>
<div id="big_box">
    <div class="check-form" style="text-align: center;height: 5.6rem;background-color: #d93a55;border-radius: 0 0 4.4rem 4.4rem;">
            <div class="txt-box" style="font-size: 14px;color: rgba(255, 255, 0, 0.62);line-height: 3.0rem;">恭喜今天你领到现金红包~</div>
            <div class="input-box" style="color: rgba(255, 255, 0, 0.56);font-size:20px;"><?php if ($this->_var['red_packet']): ?><?php echo $this->_var['red_packet']; ?><?php else: ?>0<?php endif; ?>元</div>
            <div class="tip-txt"></div>
    </div>
        <div style="text-align: center;margin-top: 20px;font-size: 16px;font-weight: bolder;
        ">未提现红包金额：<span style="color: #d90e0e;font-size:20px;" ><?php if ($this->_var['red_pactket_total']): ?><?php echo $this->_var['red_pactket_total']; ?><?php else: ?>0<?php endif; ?>元</span>
        </div>
        <div style="margin-left: 85px;margin-top:15px;">
            <li style="float: left;border-right: 1px solid rgba(30, 46, 58, 0.27);padding: 0 10px;">领取总金额：<span id="non_present"><?php if ($this->_var['red_pactket_total']): ?><?php echo $this->_var['red_pactket_total']; ?><?php else: ?>0<?php endif; ?></span>元</li><li style="float: left;padding: 0 10px;">来存签到红包吧:(</li>
        </div>
        <div id="red_packet1" style="line-height: 30px;width: 200px;height: 30px;background: #d93a55;border-radius: 19px;text-align: center;margin: 0 auto;margin-top: 69px;color: rgba(255, 255, 0, 0.56);">存入可用余额</div>

        <div id="msg_dom1" style="display:none;">
            <div id="pop_win" style="text-align: center; position: absolute; z-index: 1999; background: rgb(248, 248, 248); width: 250px; border-radius: 10px; left: 82px; top: 319px;">
                <span style="font-size: 16px;font-weight: 100;"><b>签到红包提示</b></span>
                <span style="padding:10px;display:block; border-bottom:1px solid #ccc;">
                    <span class="info"></span>
                </span><div style="padding:10px; display:-moz-box; display:-webkit-box;display:box; width:100%;">
                <div style="-moz-box-flex:1.0;-webkit-box-flex:1.0;box-flex:1.0;display:block;" id="yes">确定</div>
            </div>
            </div>
            <div id="bg_mask" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 3216px; background: rgb(0, 0, 0); z-index: 1998; opacity: 0.2;"></div>
        </div><br>
    <?php if ($this->_var['qdhb']): ?>

    <div style="width: 100%;height: 40px;">
        <ul style="height: 100%; border-bottom: 1px solid #e6e6e6;" >
            <li style="float: left;margin: 0 auto;width: 33.3%; text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;">时间</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;width: 33.3%;float:left;">领取</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;width: 33.4%;float:left;">剩余</li>
        </ul>
    </div>
    <?php $_from = $this->_var['qdhb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
    <div style="width: 100%;height: 40px;">
        <ul style="height: 100%; border-bottom: 1px solid #e6e6e6;" >
            <li style="float: left;margin: 0 auto;width: 33.3%; text-align: center;height: 100%;line-height: 40px;color: #666;"><?php echo $this->_var['value']['time']; ?></li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #666;width: 33.3%;float:left;"><?php echo $this->_var['value']['red_packet_record']; ?>元</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #666;width: 33.4%;float:left;">0元</li>
        </ul>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php endif; ?>
</div>

<div id="big_box1" style="display: none;">
    <?php if ($this->_var['hbjl']): ?>
    <div style="width: 100%;height: 40px;">
        <ul style="height: 100%; border-bottom: 1px solid #e6e6e6;" >
            <li style="float: left;margin: 0 auto;width: 33.3%; text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;">时间</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;width: 33.3%;float:left;">来源</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #b0b0b0;width: 33.4%;float:left;">红包</li>
        </ul>
    </div>
    <?php $_from = $this->_var['hbjl']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
    <div style="width: 100%;height: 40px;">
        <ul style="height: 100%; border-bottom: 1px solid #e6e6e6;" >
            <li style="float: left;margin: 0 auto;width: 33.3%; text-align: center;height: 100%;line-height: 40px;color: #666;"><?php echo $this->_var['value']['dateline']; ?></li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #666;width: 33.3%;float:left;">连续签到<?php echo $this->_var['value']['frequency']; ?>天</li>
            <li style="text-align: center;height: 100%;line-height: 40px;color: #666;width: 33.4%;float:left;"><?php echo $this->_var['value']['red_packet']; ?>元</li>
        </ul>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php else: ?>
    <div class="wrap">
        <div class="content">
            <div class="null_data">
                <p class="icon"><i class="iconfont"></i></p>
                <p class="message">暂无数据</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<script>
    $(function () {
        var lock=false;
        $("#red_packet1").click(function () {
            var non_present = $("#non_present").html();
//            console.log(non_present);

            if(!lock) {
                lock=true;
                $.ajax({
                    type: "POST",
                    url: "<?php
echo parse_url_tag("u:index\|uc_qdhb#withdrawals\|"."".""); 
?>",
                    data: "do="+non_present,
                    dataType: "json",
                    success: function(datas){
                        lock=false;
                        if(datas.status = 'success'){
                            var info = datas.info;
                            $(".info").html(info);
                            $("#msg_dom1").css("display","block");

                        }
                    }
                });
            }



        });
        $("#yes").click(function () {
            window.location.href= "http://www.gagoods.cn/wap/index.php?ctl=uc_qdhb&show_prog=1";
//             $("#msg_dom1").css("display","none");

        });
        $("#jl").click(function(){
           $("#jl").css({"color":"#fff","background":"#d93a55"});
            $("#jl1").css({"color":"#d93a55","background":"#fff"});
            $("#big_box").css("display","block");
            $("#big_box1").css("display","none");

        });
        $("#jl1").click(function(){
            $("#jl1").css({"color":"#fff","background":"#d93a55"});
            $("#jl").css({"color":"#d93a55","background":"#fff"});
            $("#big_box1").css("display","block");
            $("#big_box").css("display","none");

        });
    });
</script>
<?php echo $this->fetch('inc/no_footer.html'); ?>
