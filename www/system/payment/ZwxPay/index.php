<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>梓微兴Web扫码支付测试页面</title>
    <link href="css/pay.css" rel="stylesheet" type="text/css"/>
    <link href="css/sprite.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="js/pay.js"></script>
</head>
<body>
    <div id="pay_platform">
        <div class="header">
            <div class="wrap">
                <div class="logo"><h1><a href="javascript:void(0);" title="微信支付商户平台">微信支付商户平台</a></h1></div>
                <div class="link">客服热线：(0755) 88368899-8051</div>
            </div>
        </div>
        <div class="content">
            <div class="menu">
                <div class="item">
                    <h5>接口测试</h5>
                    <div class="">
                        <ul>
                            <li class="cur" href="orderInfo">支付测试</li>
                            <li href="queryOrder">订单查询测试</li>
                            <li href="refundTest">退款测试</li>
                            <li href="queryRefund">退款查询测试</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="auto_center" id="auto_center">
                
            </div>
        </div><!-- content end -->	
    </div>
    <div class="foot">
        <ul class="links ft">
            <li class="links_item no_extra">深圳梓微兴科技发展有限公司</li>
            <li class="links_item"><p class="copyright">深圳市福田区福强路4001号深圳文化创意园F馆3层</p> </li>
        </ul>
    </div>
    <script type="text/javascript">
        $(function(){
            var foot = $('.foot'), fb = foot.position().top , winH = window.screen.availHeight;
            if(fb < winH){
                foot.css({'position':'absolute','left':0,'right':0,'top':(winH - foot.height())});
            }
        });
    </script>
</body>
</html>