<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/user_center.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/publish.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/exif.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/lrz.js";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_account_head.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_account_head.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";


?>
<?php echo $this->fetch('inc/no_header.html'); ?>
<?php endif; ?>

<div class="wrap loading_container" id="loading_container">
	<div class="content">
		<?php echo $this->fetch('inc/uc_info_head.html'); ?>
		<script type="text/javascript">
            var suce_url = '<?php
echo parse_url_tag("u:index|uc_account_head#submit_cert|"."".""); 
?>';
		</script>
		<style>
			.wallet{
				width: 100%;
				height: 100px;
				background: #fff;
			}
			.wallet>ul{
				width:100%;
				height:100%;
				background: #fff;
			}
			.wallet_li{
				float: left;
				height: 100%;
				width: 20%;
				text-align: center;
				font-size: 12px;
			}
			.wallet_money{
				font-size: 18px;
				line-height: 24px;
				margin-top: 20px;
				font-weight: bold;
			}
			.wallet_hit{
				margin-top:10px;
				font-weight: bold;
				color:#9f8787;
			}
			.wallet_small{
				font-size:10px;
				color: #c0afaf;
			}
			.wallet_wallet{
				margin-top:10px;
				font-size:26px;
			}
			.use{
				margin-top:10px;
				border-bottom: 1px solid #ebe1e1;
			}
			.use>ul>li{
				width:25%;
			}
			.use_1>ul>li{
				width:25%;
			}
			.uc_money{
				width: 100%;
				height: 70%;
				overflow: hidden;
				margin-top: 15%;
				border-left: 1px solid #cebfbf;
				background: linear-gradient(to right, #b2b2b2 0%, #f1f1f1 100%);
			}
			.wallet_top{
				margin-top:20%;
			}
			.use_li{
				box-shadow: 1px 1px 10px #c7bebe;
			}
		</style>
		<!--副导航-->
		<div class="uc-sub-nav split-line">
			<ul class="uc-nav-list">

				<li class="uc-nav-item">
					<a href="<?php
echo parse_url_tag("u:index|uc_duobao_record|"."log_type=1".""); 
?>">
					<div class="iconfont">
						<svg class="icon" >
							<use xlink:href="#icon-zhengzaijinhang"></use>
						</svg>
					</div>
					<div class="nav-title">正在进行</div>
					</a>
				</li>
				<li class="uc-nav-item">
					<a href="<?php
echo parse_url_tag("u:index|uc_winlog|"."".""); 
?>">
					<div class="iconfont">
						<svg class="icon" >
							<use xlink:href="#icon-daishouhuo"></use>
						</svg>
					</div>
					<div class="nav-title">待收货</div>
					</a>
				</li>
				<li class="uc-nav-item">
					<a href="<?php
echo parse_url_tag("u:index|uc_winlog#index|"."".""); 
?>">
					<div class="iconfont">
						<svg class="icon" >
							<use xlink:href="#icon-icon-anxinqiao-"></use>
						</svg>
					</div>
					<div class="nav-title">待评价</div>
					</a>
				</li>
				<li class="uc-nav-item">
					<a href="<?php
echo parse_url_tag("u:index|uc_share|"."".""); 
?>">
					<div class="iconfont">
						<svg class="icon" >
							<use xlink:href="#icon-wodeshaidan1"></use>
						</svg>
					</div>
					<div class="nav-title">我的晒单</div>
					</a>
				</li>
				<li class="uc-nav-item">
					<a href="<?php
echo parse_url_tag("u:index|uc_duobao_record|"."".""); 
?>">
					<div class="iconfont">
						<svg class="icon" >
							<use xlink:href="#icon-jilu"></use>
						</svg>
					</div>
					<div class="nav-title">夺宝记录</div>
					</a>
				</li>
			</ul>
		</div>
		<!--end 副导航-->


		<div class="wallet">
			<ul>
				<li class="wallet_li">
					<p class="wallet_money"><?php if ($this->_var['user_info']['money']): ?><?php echo $this->_var['user_info']['money']; ?><?php else: ?>0<?php endif; ?></p>
					<p class="wallet_hit">本金</p>
				</li>
				<li class="wallet_li">
					<p class="wallet_money"><?php if ($this->_var['user_info']['give_money']): ?><?php echo $this->_var['user_info']['give_money']; ?><?php else: ?>0<?php endif; ?></p>
					<p class="wallet_hit">赠送金额</p>
					<?php if ($this->_var['user_info']['can_use_give_money']): ?>
					<p class="wallet_small">可提现 <span><?php echo $this->_var['user_info']['can_use_give_money']; ?></span> </p>
					<?php endif; ?>
				</li>
				<li class="wallet_li">
					<p class="wallet_money"><?php if ($this->_var['user_info']['fx_money']): ?><?php echo $this->_var['user_info']['fx_money']; ?><?php else: ?>0<?php endif; ?></p>
					<p class="wallet_hit">推广奖</p>
					<?php if ($this->_var['jjdz']): ?>
					<p class="wallet_small">即将到账 <span><?php echo $this->_var['jjdz']; ?></span> </p>
					<?php endif; ?>
				</li>
				<li class="wallet_li">
					<p class="wallet_money"><?php if ($this->_var['user_info']['admin_money']): ?><?php echo $this->_var['user_info']['admin_money']; ?><?php else: ?>0<?php endif; ?></p>
					<p class="wallet_hit">管理奖</p>
				</li>
				<li class="wallet_li">
					<a href="<?php
echo parse_url_tag("u:index|uc_money#setting|"."".""); 
?>">
					<div class="uc_money">
						<p class="wallet_money wallet_wallet">
							<svg class="icon" >
								<use xlink:href="#icon-qianbao3"></use>
							</svg>
						</p>
						<p class="wallet_hit">钱包</p>
					</div>
					</a>
				</li>
			</ul>
		</div>
		<div class="wallet use">
			<ul>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|uc_qdhb|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-huodong2"></use>
						</svg>
					</p>
					<p class="wallet_hit">签到记录</p>
					</a>
				</li>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|prize|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-dazhuanpan"></use>
						</svg>
					</p>
					<p class="wallet_hit">大转盘</p>
					</a>
				</li>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-shequ1"></use>
						</svg>
					</p>
					<p class="wallet_hit">社区</p>
					</a>
				</li>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|helps|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-bangzhu2"></use>
						</svg>
					</p>
					<p class="wallet_hit">帮助</p>
					</a>
				</li>
			</ul>
		</div>
		<div class="wallet use_1">
			<ul>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|prize|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-tubiao-"></use>
						</svg>
					</p>
					<p class="wallet_hit">活动</p>
					</a>
				</li>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|uc_fxinvite#index1|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-tuandui1"></use>
						</svg>
					</p>
					<p class="wallet_hit">我的团队</p>
					</a>
				</li>
				<li class="wallet_li use_li">
					<a href="<?php
echo parse_url_tag("u:index|uc_fxinvite|"."".""); 
?>">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-lianjie1"></use>
						</svg>
					</p>
					<p class="wallet_hit">推广链接</p>
					</a>
				</li>
				<li class="wallet_li use_li service">
					<p class="wallet_money wallet_wallet wallet_top">
						<svg class="icon" >
							<use xlink:href="#icon-kefu1"></use>
						</svg>
					</p>
					<p class="wallet_hit">联系客服</p>
				</li>
			</ul>
		</div>
		<div class="wallet" style="margin-top:10px;">
			<img src="./wap/Tpl/main/images/youqinghit.jpg" alt="">
		</div>
	</div>
</div>

<div class="services" style="width:80%;height:55%;background: linear-gradient(to right, #ff5c40 0%, #ff755b 30%,#ff127a 100%);position:fixed;left:10%;top:18%;z-index:100;display:none;border:1px solid #f40505;">
	<span class="close_ser" style="width:24px;height:24px;display:block;background:#fff;text-align:center;font-size:24px;float:right;color:#de0c23;line-height:24px;">X</span>
	<p style="text-align:center;font-size:16px;margin-top:24px;color:#b2522d;">联系客服</p>
	<ul>
		<li>
			<img src="Tpl/main/images/wx_qrcode.jpg" alt="" style="width:65%;margin-left:18.5%;">
			<p style="text-align:center;color:#b2522d;">请使用微信扫描上方二维码</p>
			<hr style="border:none;border-bottom:1px solid #2423a1;margin-top:2%;">
		</li>
		<li>
			<span style="margin-left:10%;display:block;margin-top:2%;float:left;color:#0d12ec;">客服QQ：</span>
			<span id="qq" style="margin-left:4%;color:#de0c0c;">1141122187</span>
			<button style="margin-left: 12%;border-radius: 5px;width: 15%;background: #5553ca;text-align: center;color:#f1f1f1;;border: 1px solid #fff;margin-top: 2%;" id="copy">复制</button>
		</li>
		<li>
			<a href="tel://027-59260885">
				<span style="margin-left:10%;display:block;margin-top:2%;float:left;color:#0d12ec;">客服电话：</span>
				<span style="color:#de0c0c;">027-59260885</span>
				<button style="margin-left: 10%;border-radius: 5px;width: 15%;background: #5553ca;text-align: center;color: #f1f1f1;;border: 1px solid #fff;margin-top: 2%;">拨打</button>
			</a>

		</li>
	</ul>
</div>
<div class="tongzhi tishi_aaa" style="position: fixed;
	display:none;
    top:0;
    left: 2%;
    width: 96%;
    height: 10%;
    background: #dadfd0;
    z-index: 1000;
    border-radius: 15px;">
	<p class="msg_stytem" style="    font-size: 14px;
    line-height: 25px;
    margin-top: 3%;
    width: 90%;
    margin-left: 5%;"> </p>
	<p class="hits" style="float: right;
    font-size: 12px;
    margin-right: 10%;
    color: #c6632d;">点击关闭</p>
</div>
<script>
    $(function() {
    	$('.menu_box>li').eq(4).children('a').children('p').children('img').attr('src','http://www.gagoods.cn/wap/Tpl/main/images/menu/mer.png');
        $(".tobeDealers").click(function(){
            $(".tongzhi").show("slow");
            $.ajax({
                type: "POST",
                url: "<?php
echo parse_url_tag("u:index\|user_center#tobe_dealers\|"."".""); 
?>",
                data: "",
                dataType: "json",
                success: function(data){
                    if(data['status'] == 1){
                        $(".msg_stytem").text(data['info']);
                        $(".tobeDealers").hide();
                    }else{
                        $(".msg_stytem").text(data['info']);
                    }
                }
            });
        });
        $(".tongzhi").click(function(){
            $(".tongzhi").hide();
        });

        $(".service").click(function () {
            $(".services").show(1000);
        });
        $(".close_ser").click(function(){
            $(".services").hide(1000);
        });
    });
</script>

<script>
    function copyArticle(event){
        const range = document.createRange();
        range.selectNode(document.getElementById('qq'));

        const selection = window.getSelection();
        if(selection.rangeCount > 0) selection.removeAllRanges();
        selection.addRange(range);

        document.execCommand('copy');
        alert("qq号"+range+"复制成功");
    }


    document.getElementById('copy').addEventListener('click', copyArticle, false);
</script>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>