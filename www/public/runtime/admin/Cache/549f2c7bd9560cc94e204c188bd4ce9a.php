<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo conf("APP_NAME");?><?php echo l("ADMIN_PLATFORM");?></title>
<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
<script type="text/javascript">
	var version = '<?php echo app_conf("DB_VERSION");?>';
	var app_type = '<?php echo ($apptype); ?>';
	var ofc_swf = '__TMPL__Common/js/open-flash-chart.swf';
	var sale_line_data_url = '<?php echo urlencode(u("Ofc/sale_line"));?>';
</script>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/main.css" />
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/swfobject.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/script.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/main.js"></script>
</head>

<body>
	<div class="main">
	<div class="main_title"><?php echo conf("APP_NAME");?><?php echo l("ADMIN_PLATFORM");?> <?php echo L("HOME");?>	</div>
	<div class="notify_box">
		<table>
			<tr>
			<td class="version_box">
				<table>
					<tr><td>
						当前版本：<?php echo conf("DB_VERSION");?><?php if(app_conf("APP_SUB_VER")){ ?>.<?php echo app_conf("APP_SUB_VER");?><?php } ?><br />
						<div id="version_tip"></div>
						<!--<div>授权号：<?php echo ($FANWE_APP_ID); ?></div>-->
					</td></tr>
				</table>
			</td><!--version_box 版本提示-->
			<td class="order_box">
				<table>
					<tr><td>
						订单累计成交额 <?php echo (format_price($income_order)); ?><br />
						
						<?php if($dealing_order > 0): ?>待处理订单共计 <?php echo ($dealing_order); ?> <a href="<?php echo u("DealOrder/index",array("delivery_status_item"=>0,"type"=>0,"is_robot"=>0));?>">【去处理】</a> <br /><?php endif; ?>
						
					</td></tr>
				</table>
			</td><!--order_box 订单提醒-->
			<td class="user_box">
				<table>
					<tr><td>
						平台会员总数 <?php echo ($user_count); ?><br />
						<?php if($income_incharge > 0): ?>预付款总金额 <?php echo (format_price($income_incharge)); ?><br /><?php endif; ?>
						<?php if($withdraw > 0): ?>共有 <?php echo ($withdraw); ?> 笔提现申请 <a href="<?php echo u("User/withdrawal_index",array("is_paid"=>0));?>">【去处理】</a><?php endif; ?>
					</td></tr>
				</table>
			</td><!--user_box 会员提醒-->		
			<td class="tuan_box">
				<table>
					<tr><td>
						共有 <?php echo ($duobao_count); ?>期夺宝活动<br />			
						进行中共有 <?php echo ($duobaoing_count); ?>期<br />	
						揭晓中共有 <?php echo ($lotterying_count); ?>期<br />	
						已揭晓共有 <?php echo ($lottery_count); ?>期<br />	
					</td></tr>
				</table>
			</td><!--tuan_box 团购提醒-->	
			</tr>
			
	
		</table>
	</div>	
	<div class="blank5"></div>
	<div class="blank5"></div>
	<div class="blank5"></div>
	<div class="blank5"></div>
	<div class="main_title">最近30天运营数据</div>
	<table width=100%>
		
		<tr>
			<td width=10>&nbsp;</td>
			<td width=100%>
				<div id="sale_line_data_chart"></div>
			</td>	
			<td width=10>&nbsp;</td>
		</tr>
	</table>
	</div>
</body>
</html>