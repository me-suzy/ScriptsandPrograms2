<?php
	session_start();
	if($user_name == '' && $id == '')
		header('Location: ./relogin.php');

	include('./include/connection.php');
	$SQL = "SELECT banner.id,url,graphic,alt,banner.campaign_id,banner_size.size,name, banner_stat.clicks, banner_stat.views FROM banner_campaign,banner,banner_size,banner_stat WHERE group_id = $group AND banner.campaign_id = banner_campaign.id AND banner.id = $bid AND banner.size = banner_size.size_id AND banner.id = banner_stat.banner_id";
	$result = @mysql_query($SQL,$con);
	if(@mysql_affected_rows()>0)
	{
		$row = mysql_fetch_array($result);		
	}
	else
	{
		$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
		$html .= '<html><head><title>Banner Detail</title><meta name="Author" content="">';
		$html .= '<meta name="Keywords" content="">';
		$html .= '<meta name="Description" content="">';
		$html .= '<style type="text/css">';
		$html .= '.copyright {font: 8pt arial}';
		$html .= '.tips {font: italic 8pt arial}';
		$html .= '.copyrightsite {font: bold 8pt verdana}';
		$html .= '.header {font: bold 10pt verdana}';
		$html .= '.label {font: 9pt arial}';
		$html .= '.error {font: italic 8pt arial; color: red}';
		$html .= 'body {font: 8pt arial}';
		$html .= 'td {font: 8pt arial}';
		$html .= 'input {font: 8pt arial}';
		$html .= '</style>';
		$html .= '</head><body bgcolor="white"><center><br><br><br>';
		$html .= '<table width="70%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">';
		$html .= '<tr><td><table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">';
		$html .= '<tr><td align="center"><br><br>This banner does not belong to you. Please try another.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="self.close();"><b>Close Window</b></a><br><br></td></tr></table></td></tr></table>';
		$html .= '<br><br></body></html>';
		print($html);
		exit;
	}
	
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Banner Detail</title>
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<style type="text/css">
		.copyright {font: 8pt arial}
		.tips {font: italic 8pt arial}
		.copyrightsite {font: bold 8pt verdana}
		.header {font: bold 10pt verdana}
		.label {font: 9pt arial}
		.error {font: italic 8pt arial; color: red}
		body {font: 8pt arial}
		td {font: 8pt arial}
		input {font: 8pt arial}
</style>
</head>
<body bgcolor="white">
<center>
<font class="header">Banner Manager Admin</font><br>
<table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr><td>&nbsp;</td></tr>
		<tr align="center">
			<td colspan="2">
				<b><?php echo $row['name']; ?></b><br><hr width="80%">
			</td>
		</tr>
		<tr><td align="center">
		<table width="88%" height="100%" cellpadding="1" cellspacing="0" border="1" bgcolor="#eeeeee">
		
		<tr valign="top">
			<td><b>URL</b></td><td> <?php echo $row['graphic'];?></td>
		</tr>
		<tr valign="top">
			<td><b>Link</b></td><td> <?php echo $row['url'];?></td>
		</tr>
		<tr valign="top">
			<td><b>Text</b></td><td> <?php echo $row['alt'];?></td>
		</tr>
		<tr>
			<td><b>Size</b></td><td>
			 <?php echo $row['size']; ?>		
			</td>
		</tr>
		<tr valign="top">
			<td><b>Clicks</b></td><td> <?php echo $row['clicks'];?></td>
		</tr>
		<tr valign="top">
			<td><b>Impression</b></td><td> <?php echo $row['views'];?></td>
		</tr>
		<tr valign="top">
			<td><b>% Clicks</b></td><td> <?php if($row['views'] != '0') echo round(($row['clicks']/$row['views'])*100,2) ; else echo '0'?>%</td>
		</tr>
		
		</table>
		</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>
		</td>
	</tr>
</table>
<?php include("./include/footer.php"); ?>
</center>
</body>
</html>