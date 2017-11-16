<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title><?php echo l("ERROR");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
</head>
<body>
<div class="message">
<table cellpadding=0 cellspacing=0 class="form" >
	<tr>
		<td class="topTd"></td>
	</tr>
	<tr class="row" >
		<th class="title_row"><?php echo ($msgTitle); ?></th>
	</tr>
	

	<tr class="row">
		<td class="message_row"><?php echo ($message); ?></td>
	</tr>

	<tr class="row">
		<td  class="jump)row">
			<?php echo sprintf(l("JUMP_TIPS"),$waitSecond,$jumpUrl);?>
		</td>
	</tr>
	<tr>
			<td class="bottomTd"></td>
	</tr>
	</table>
</div>
</body>
</html>