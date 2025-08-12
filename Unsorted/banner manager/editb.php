<?php
	session_start();
	if($user_name == '' && $id == '')
		header('Location: relogin.php');

	include('./include/connection.php');
	if(isset($submitForm))
	{
		$SQL = "UPDATE banner SET graphic = '$url', url= '$link', alt = '$alt', size= $size, show_text = $show_link, popup = $pop WHERE campaign_id = $campaign AND id = $bid";
		if(@mysql_query($SQL,$con))
		{
			
			$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
			$html .= '<html><head><title>Add Banner</title><meta name="Author" content="">';
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
			$html .= '<script language="javascript">opener.document.location.href=opener.document.location.href;</script></head><body bgcolor="white"><center><br><br><br>';
			$html .= '<table width="70%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">';
			$html .= '<tr><td><table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">';
			$html .= '<tr><td align="center"><br><br>Banner Edited!!!<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="self.close();"><b>Close Window</b></a><br><br></td></tr></table></td></tr></table>';
			$html .= '<br></body></html>';
			print($html);
			exit;
		}
		else
		{
			$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
			$html .= '<html><head><title>Add Banner</title><meta name="Author" content="">';
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
			$html .= '<tr><td align="center"><br><br>There is some error during banner editing. Please try another.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="history.back();"><b>Back</b></a><br><br></td></tr></table></td></tr></table>';
			$html .= '<br></body></html>';
			print($html);
			exit;
		}
	}
	else
	{
		$SQL = "SELECT banner.id,url,graphic,alt,campaign_id,size,name,show_text,popup FROM banner_campaign,banner WHERE group_id = $group AND campaign_id = banner_campaign.id AND banner.id = $bid";
		$result = @mysql_query($SQL,$con);

		if(@mysql_affected_rows()>0)
		{
			$row = mysql_fetch_array($result);		
		}
		else
		{
			$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
			$html .= '<html><head><title>Add Banner</title><meta name="Author" content="">';
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
			$html .= '<br></body></html>';
			print($html);
			exit;
		}
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Edit Banner</title>
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
</script>
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
		select {font: 8pt arial}

</style>
</head>
<body bgcolor="white">
<center>
<font class="header">Banner Manager Admin</font><br>
<form action="editb.php" method="post">
<table width="90%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr><td>&nbsp;</td></tr>
		<tr><td align="center">
		<table width="88%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr align="center">
			<td colspan="3">
				<b><?php echo $row['name']; ?></b><br><hr width="80%">
			</td>
		</tr>
		<tr>
			<td><b>Banner URL</b></td><td>:</td><td><input type="text" name="url" maxlength="255" size="38" value="<?php echo $row['graphic'];?>"></td>
		</tr>
		<tr>
			<td><b>Banner Link</b></td><td>:</td><td><input type="text" name="link" maxlength="255" size="38" value="<?php echo $row['url'];?>"></td>
		</tr>
		<tr>
			<td><b>ALT Text</b></td><td>:</td><td><input type="text" name="alt" maxlength="200" size="38" value="<?php echo $row['alt'];?>"></td>
		</tr>
		<tr>
			<td><b>Show Text Link</b></td><td>:</td><td><input type="radio" name="show_link" value="1"<?php if($row['show_text']== 1) echo "checked"; ?>>Yes&nbsp;&nbsp;<input type="radio" name="show_link" value="0"<?php if($row['show_text']== 0) echo "checked"; ?>>No</td>
		</tr>
		<tr>
			<td><b>Link As Popup?</b></td><td>:</td><td><input type="radio" name="pop" value="1"<?php if($row['popup']== 1) echo "checked"; ?>>Yes&nbsp;&nbsp;<input type="radio" name="pop" value="0"<?php if($row['popup']== 0) echo "checked"; ?>>No</td>
		</tr>
		<tr>
			<td><b>Banner Size</b></td><td>:</td><td>
			<select name="size">
			
			<?php
				$SQL = "SELECT * FROM banner_size";
				$result = @mysql_query($SQL,$con);
				while($size = mysql_fetch_array($result))
				{	
					if($size[0] == $row['size'])
						echo '<option value="'.$size[0].'" selected>'.$size[1];
					else
						echo '<option value="'.$size[0].'">'.$size[1];
				}
			?>
			</select>
			</td>
		</tr>
		<tr><td colspan="3">** Note that all banners in a campaign are recommended be of the same size for it work properly.</td></tr>
		<tr><td colspan="3" align="right"><input type="hidden" name="bid" value="<?php echo $bid; ?>"><input type="hidden" name="campaign" value="<?php echo $row['campaign_id']; ?>"><input type="submit" name="submitForm" value="Save Banner"></td></tr>
		</table>
		</td></tr>
		</table>
		</td>
	</tr>
</table>
</form>
<?php include("./include/footer.php"); ?>
</center>
</body>
</html>