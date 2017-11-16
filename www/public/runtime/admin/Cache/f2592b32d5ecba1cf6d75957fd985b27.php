<?php if (!defined('THINK_PATH')) exit();?>
<script type="text/javascript">
	function check_incharge_form()
	{
		if($("input[name='money']").val()==''&&$("input[name='score']").val()==''&&$("input[name='point']").val()==''&&$("input[name='coupons']").val()=='')
		{
			alert("资金，积分，优惠币，经验至少要填一项");
			return false;
		}
		if($("input[name='money']").val()!=''&&isNaN($("input[name='money']").val()))
		{
			alert(LANG['MONEY_FORMAT_ERROR']);
			return false;
		}
		if($("input[name='score']").val()!=''&&isNaN($("input[name='score']").val()))
		{
			alert(LANG['SCORE_FORMAT_ERROR']);
			return false;
		}
		if($("input[name='coupons']").val()!=''&&isNaN($("input[name='coupons']").val()))
		{
			alert(LANG['COUPONS_FORMAT_ERROR']);
			return false;
		}
		if($("input[name='point']").val()!=''&&isNaN($("input[name='point']").val()))
		{
			alert("操作的经验不正确");
			return false;
		}
		return true;
	}
</script>
<div class="main">
<div class="main_title"><?php echo ($user_info["user_name"]); ?> <?php echo L("USER_MONEY");?>:<?php echo (format_price($user_info["money"])); ?>  <?php echo L("USER_SCORE");?>:<?php echo (format_score($user_info["score"])); ?> 优惠币:<?php echo ($user_info["coupons"]); ?>  经验:<?php echo ($user_info["point"]); ?></div>
<div class="blank5"></div>
<form name="edit" action="__APP__" method="post" enctype="multipart/form-data" onsubmit="return check_incharge_form();">
<table class="form" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=2 class="topTd"></td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("INCHARGE_USER_MONEY");?>:</td>
		<td class="item_input"><input type="text" class="textbox require" name="money" />
		<span class='tip_span'>[<?php echo L("INCHARGE_USER_MONEY_TIP");?>]</span>
		</td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("INCHARGE_USER_SCORE");?>:</td>
		<td class="item_input"><input type="text" class="textbox require" name="score" />
		<span class='tip_span'>[<?php echo L("INCHARGE_USER_SCORE_TIP");?>]</span>
		</td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("INCHARGE_USER_COUPONS");?>:</td>
		<td class="item_input"><input type="text" class="textbox require" name="coupons" />
		<span class='tip_span'>[<?php echo L("INCHARGE_USER_COUPONS_TIP");?>]</span>
		</td>
	</tr>
	<tr style="display:none;">
		<td class="item_title">经验:</td>
		<td class="item_input"><input type="text" class="textbox require" name="point" />
		<span class='tip_span'>[正数为增加经验，负数为减少经验]</span>
		</td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("INCHARGE_MSG");?>:</td>
		<td class="item_input"><input type="text" class="textbox" name="msg" style="width:400px;" />
		</td>
	</tr>
	<tr>
		<td class="item_title">&nbsp;</td>
		<td class="item_input">
			<!--隐藏元素-->
			<input type="hidden" name="id" value="<?php echo ($user_info["id"]); ?>" />
			<input type="hidden" name="<?php echo conf("VAR_MODULE");?>" value="User" />
			<input type="hidden" name="<?php echo conf("VAR_ACTION");?>" value="modify_account" />
			<!--隐藏元素-->
			<input type="submit" class="button" value="<?php echo L("OK");?>" />
			<input type="reset" class="button" value="<?php echo L("RESET");?>" />
		</td>
	</tr>
	<tr>
		<td colspan=2 class="bottomTd"></td>
	</tr>
</table>	 
</form>
</div>