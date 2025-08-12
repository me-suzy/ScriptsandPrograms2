<?php 
include "./ressourcen/config.php";
$ressourcen = "./ressourcen/";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>MySQL Commander <?php echo $config->commander_version;?></title>
	<link rel="stylesheet" href='<?php echo $ressourcen;?>standard.css' type="text/css"> 
</head>

<?php 
include $ressourcen."popup.php";
$text =  $pop[$HTTP_GET_VARS['id']][$config->language];

$breite = 302;

?>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 bgcolor="#ebebeb">
<table width="<?php echo $breite;?>" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td height="8"><?php $page->pixel(91);?></td>
	<td><?php $page->pixel($breite-91);?></td>
</tr>
<tr>
	<td align="center" valign="top"><img src="img/commander.jpg" width="75" height="78" alt="" border="0"></td>
	<td valign="top">
	<table width="<?php echo ($breite-91);?>" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="Black">
			<td height="10"><?php $page->pixel();?></td>
			<td><?php $page->pixel($breite-91-80);?></td>
			<td><?php $page->pixel(80);?></td>
			<td><?php $page->pixel();?></td>
		</tr>
		<tr>
			<td bgcolor="Black"><?php $page->pixel();?></td>
			<td height="46" class="txtblaufett">&nbsp;&nbsp;<?php echo $funcs->text("Hilfe", "Help");?><br><br></td>
			<td><img src="img/img_default.jpg" width="80" height="46" alt="" border="0"></td>
			<td bgcolor="Black"><?php $page->pixel();?></td>
		</tr>
		<tr>
			<td bgcolor="Black"><?php $page->pixel();?></td>
			<td bgcolor="#b5b5b5" height="1"><?php $page->pixel();?></td>
			<td bgcolor="#b5b5b5"><?php $page->pixel();?></td>
			<td bgcolor="Black"><?php $page->pixel();?></td>
		</tr>
		<tr>
			<td bgcolor="Black"><?php $page->pixel();?></td>
			<td bgcolor="White" height="14"><?php $page->pixel();?></td>
			<td bgcolor="White"><?php $page->pixel();?></td>
			<td bgcolor="Black"><?php $page->pixel();?></td>
		</tr>
		<tr>
			<td colspan="4" bgcolor="#b5b5b5"><?php $page->pixel();?></td>
		</tr>
	</table>
	</td>
</tr>
</table>

<br>

<table width="<?php echo $breite;?>" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><?php $page->pixel(8);?></td>
	<td><?php $page->pixel($breite-8);?></td>
</tr>
<tr>
	<td><?php $page->pixel(8);?></td>
	<td class="txtkl"><?php echo $text;?></td>
</tr>
<tr>
	<td><?php $page->pixel(8);?></td>
	<td height="8"><?php $page->pixel();?></td>
</tr>
<tr>
	<td><?php $page->pixel(8);?></td>
	<td bgcolor="#b5b5b"><?php $page->pixel();?></td>
</tr>

</table>
</body>
</html>
