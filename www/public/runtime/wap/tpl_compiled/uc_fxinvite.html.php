
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_fxwithdraw.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_invite.css";

$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fxin.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";


?>
<?php echo $this->fetch('inc/header.html'); ?>
<script type="text/javascript">
	function erfenxing(){
		$("#imgs").css('display','block');
		$("#no").css('display','block');
		$("#qrcod").css('display','block');
	}
	$(document).ready(function(){
		$("#no").click(function(){
			$("#imgs").css('display','none');
			$("#no").css('display','none');
			$("#qrcod").css('display','none');
			
		});
		$("#qrcod").click(function(){
			$("#imgs").css('display','none');
			$("#no").css('display','none');
			$("#qrcod").css('display','none');
			
		});
	});
</script>
<div class="wrap">
	<div class="content">

		<div>
			<img src="http://<?php echo $this->_var['host']; ?>/wap/Tpl/main/images/inv-ad.jpg" style="width:100%">
		</div>
		
		<div style="padding:15px;">

			<div class="fenx" style="    text-align: center;background: #fffce0;border: 1px solid #fffcaf;line-height: 40px;">
				<span style="font-size: 16px;color: #ff7515;">我的分享链接： <input type="text" value="<?php echo $this->_var['fx_url']; ?>" id="fx_url"> </span>
				<button style="width: 10%;text-align: center;border: 1px solid #ffcbc5;border-radius: 5px;background: #28deff;color: #fff;"  id="fz">复制</button>
			</div>

			<!--<div class="money_box" style="text-align:center;width:100%;">-->
				<!--<a style="font-weight:bold;color:#f60;">我的推荐人：<b style="color:#000;"><?php echo $this->_var['data']['pname']; ?></b> </a>-->
			<!--</div>-->
			<h4 style="margin-bottom:10px;">下方二维码是你的分享连接，通过社交软件分享下方二维码：</h4>
			<div style="text-align:center;margin-top:10px;position:relative" class="fx_lj">
				<img src="<?php echo $this->_var['data']['share_register_qrcode']; ?>" id="img1"/>
				<img src="/image/Icon-60.png" alt="" style="position: absolute;" id="img2">
				<p style="text-align:center;">我的推荐人：<?php echo $this->_var['data']['pname']; ?></p>
			</div>
			<div style="width:100%;margin-top:20px;">
				<div style="width:50%;float:left;border-right: 1px solid #ccc;padding: 10px 40px;" >
					<div style="text-align:center;width:50%;margin:0 auto;">
						<p style="color:#f60;">
						<?php if ($this->_var['data']['fx_money']): ?>
						<?php echo $this->_var['data']['fx_money']; ?>
						<?php else: ?>
						0
						<?php endif; ?>
						</p>
						<div style="height:8px;"></div>
						<p>推广奖</p>
						<div style="height:8px;"></div>
						<p style="color:#f60;">夺宝币</p>
					</div>
				</div>
				
			
				<div style="width:50%;float:right;text-align:center;padding: 10px 40px;">
					<div style="text-align:center;width:50%;margin:0 auto;">
						<p style="color:#f60;">
						<?php if ($this->_var['data']['admin_money']): ?>
						<?php echo $this->_var['data']['admin_money']; ?>
						<?php else: ?>
						0
						<?php endif; ?>
						</p>
						<div style="height:8px;"></div>
						<p>管理奖</p>
						<div style="height:8px;"></div>
						<p style="color:#f60;">夺宝币</p>
					</div>
				</div>
			</div>
			<!--<div>-->
				<!--<div>-->
					 <!--<h4 style="margin-bottom:10px;">下方二维码是你的分享连接，通过社交软件分享下方二维码：</h4>-->
					 <!--<input type="text" class="ui-textbox"  value="<?php echo $this->_var['data']['share_register_url']; ?>" style=" width:95%; padding: 0px 10px;height: 46px;border-radius: 5px;font-size: 16px;border: 1px solid #CCC;">-->
				<!--</div>-->
			<!--</div>-->

		</div>

	 	<!--<h2 class="add" style="text-align:center;line-height:2rem">我的推荐人：<?php echo $this->_var['data']['pname']; ?></h2>-->
		<div class="blank" style="height:50px;margin-top:30px;width: 100%;"></div>

		<div class="info_table"  style="margin-top: 10px;">
			<table class="split-line-top">
				<tbody>
				<tr>
				<th class="split-line">我推荐的人</th>
				<th class="split-line">返利总推广奖</th>
				<th class="split-line">返利总管理奖</th>
				</tr>

				<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
				<tr class="alt">
				<td class="split-line">
				<?php echo $this->_var['row']['user_name']; ?>
				</td>
				<td class="split-line">
				<h1><?php echo $this->_var['row']['score']; ?></h1>
				</td>
				<td class="split-line">
				<h1><?php echo $this->_var['row']['coupons']; ?></h1>
				</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

				<tr>
					<?php if ($this->_var['data']['list']): ?>
					<?php if ($this->_var['pages']): ?>
					<td colspan="4"><div class="pages"><?php echo $this->_var['pages']; ?></div></td>
					<?php endif; ?>
					<?php else: ?>
					<td colspan="4"><span>暂时没有下线会员</span></td>
					<?php endif; ?>
				</tr>
				</tbody>
			</table>
		</div>

		<div class="blank50"></div>

	</div>
</div>

<!--<div id="imgs"  style="display:none;"></div>-->
<!--<div id="no" style="display:none;">-->
	<!--<div id="big">x</div>-->
<!--</div>-->
<!--<div id="qrcod" style="display:none;">-->
		<!--<img src="<?php echo $this->_var['data']['share_register_qrcode']; ?>" />-->
<!--</div>-->

<script>
	$("#fz").click(function(){
        var d = document.getElementById("fx_url");
        d.select();
        document.execCommand("Copy");
        alert("复制成功！");
	});
	var y=$('#img1').height();
	var x=$('#img2').height();
	var h=(y-x)/2;
	var w1 = $("#img1").width();
	var w2 = w1/4;
	var w3=$(".fx_lj").width();
	var w = (w3-w2)/2;
    $('#img2').css({'top':h+'px','left':w+'px','width':w2+'px'});
</script>
<?php echo $this->fetch('inc/footer_index.html'); ?>