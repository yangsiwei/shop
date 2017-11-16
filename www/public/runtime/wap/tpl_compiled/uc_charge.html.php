<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_incharge.css";

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

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_charge.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_charge.js";

?>
<?php echo $this->fetch('inc/header_getpassword.html'); ?>
<style>
	.skb_sel{
		width:60%;
		height:30px;
		border:1px solid #E8E8E8;
		margin-left:6%;
		margin-top:15px;
	}
</style>
<script src="https://lib.fuqian.la/h5jssdk.4.0.js"></script>
<?php if ($this->_var['payment_class'] == skb || $this->_var['payment_class'] == fql): ?>
<form name="do_charge" action="<?php
echo parse_url_tag("u:index|uc_charge#idnex|"."".""); 
?>" method="post" >
<?php endif; ?>
<?php if ($this->_var['payment_class'] == shan): ?>
<form name="do_charge" action="<?php
echo parse_url_tag("u:index|uc_charge#done|"."".""); 
?>" method="post" >
<?php endif; ?>
<h1>选择充值金额(夺宝币)</h1>

<div class="money" id="money1">
	<?php if ($this->_var['user_info']['total_money']): ?>
	<input type="text" value="10" readonly="readonly" class="select_num">
	<input type="text" value="50" readonly="readonly" class="select_num">
	<input type="text" value="100" readonly="readonly" class="select_num">
	<input type="text" value="200" readonly="readonly" class="select_num">
	<input type="text" value="500" readonly="readonly" class="select_num">
	<input type="text" value="1000" readonly="readonly" class="select_num">
	<?php else: ?>
	<input type="text" value="10" readonly="readonly" class="select_num">
	<input type="text" value="100" readonly="readonly" class="select_num">
	<input type="text" value="1000" readonly="readonly" class="select_num">
	<?php endif; ?>
</div>
<?php if ($this->_var['payment_class'] == skb): ?>
<input type="hidden" name="payment_id" value="100" >
<?php else: ?>
<input type="hidden" name="payment_id" value="200" >
<?php endif; ?>
<input type="hidden" name="aaa" class="appId" value="ShYeR0yaXG7O1oCwtI5OsQ">

<!--<?php if ($this->_var['payment_class'] == skb): ?>-->
<!--<span style="margin-left:10%;">支付方式</span>-->
<!--<select name="bank_code" class="skb_sel">-->
	<!--<option value="">选择支付方式</option>-->
	<!--<option value="ALIPAY">支付宝</option>-->
	<!--<option value="WEBCHAT">微信支付</option>-->
<!--</select>-->

<!--<?php endif; ?>-->
<?php if ($this->_var['payment_class'] == shan): ?>
<?php $_from = $this->_var['data']['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'payment');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['payment']):
?>
<label for="weixin"  class="pay_select">
<input type="hidden" name="payment_id" class="payment_id" value="<?php echo $this->_var['payment']['id']; ?>">
</label>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<!--<h1>选择充值方式</h1>-->
<!--<ul class="pay-way">-->
    <!--<?php $_from = $this->_var['data']['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'payment');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['payment']):
?>-->
    <!--<li>-->
        <!--<label for="weixin"  class="pay_select">-->
            <!--<input class="payment_id" type="radio" name="payment_id" value="<?php echo $this->_var['payment']['id']; ?>"  <?php if ($this->_var['key'] == 0): ?>checked="checked"<?php endif; ?>><?php echo $this->_var['payment']['name']; ?>-->
            <!--<span <?php if ($this->_var['key'] == 0): ?>class="checked"<?php endif; ?>></span>-->

        <!--</label>-->
    <!--</li>-->
    <!--<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
<!--</ul>-->
<?php endif; ?>
<div class="subbox">
<?php if ($this->_var['payment_class'] == skb): ?>
	<input type="submit" value="确认充值" style="width:70%;height:35px;border:1px solid #fff; background:#DD4E66;border-radius:5px;color:#fff;font-size:18px;text-align:center;margin-left:15%;margin-top:20px;" class="done">
<?php endif; ?>
<?php if ($this->_var['payment_class'] == fql): ?>
    <input type="submit" value="支付宝支付" style="width:70%;height:35px;border:1px solid #fff; background:#DD4E66;border-radius:5px;color:#fff;font-size:18px;text-align:center;margin-left:15%;margin-top:20px;" id="alipay">
<?php endif; ?>
<?php if ($this->_var['payment_class'] == shan): ?>
<input type="submit" value="确认充值" class="sub">
<?php endif; ?>
</div>
</form>
<?php if ($this->_var['payment_class'] == skb): ?>
<script>
	$(function(){
        var money;
        $(".select_num").click(function(){
            money = $("input[name='money']").val();
            console.log(money);
        });

		$("form[name='do_charge']").bind("submit",function(){
            var payment_id = $("input[name='payment_id']").val();
            var query = $(this).serialize();
            if(money>0){
                $.ajax({
                    type: "POST",
                    url: "/wap/index.php?ctl=uc_charge&act=done_gepi&show_prog=1",
                    data: query,
                    dataType: "json",
                    success: function(data){
                        window.location.href="http://www.aliduobaodao.com/shukebao/app_post.php?money="+data.money+"&bank_code=ALIPAY&order_no="+data.order_no+"&order_time="+data.order_time+"&order_userid="+data.order_userid;
                    }
                });
			}
		});
	});
</script>
<?php endif; ?>

<?php if ($this->_var['payment_class'] == fql): ?>
<script type="text/javascript">


	 $(function(){
	 		var subject = '神州普惠';
	 		var notifyUrl = 'http://www.aliduobaodao.com/wap/index.php?ctl=notify';
	 		var time = Date.now()+"<?php echo $this->_var['user_info']['id']; ?>";
	 		var qrCode;
	 		var appId = $(".appId").val();
	 		var userId = String(<?php echo $this->_var['user_info']['id']; ?>);

	 		$(".select_num").click(function(){
	 			qrCode = String($(".selected").val()*100);
	 		});

	 	document.querySelector('#alipay').addEventListener('click', e => {
	 		FUQIANLA.init({
	 	    'app_id': appId,
	 	    'order_no': time,
	 	    'channel': 'ali_pay_scan',
	 	    'amount': qrCode,
	 	    'subject': subject,
	 	    'optional': userId,
	 	    'notify_url': notifyUrl
	 		});
	 	});
	 });
</script>
<?php endif; ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>