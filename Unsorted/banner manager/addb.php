<?php
	session_start();
	if($user_name == '' && $id == '')
		header('Location: ./relogin.php');

	include_once('./include/connection.php');
	if(isset($submitForm))
	{
		$SQL = "SELECT name FROM banner_campaign WHERE id = $campaign AND group_id = $group";
		$result = @mysql_query($SQL,$con);
		if(mysql_affected_rows()>0)
		{
			mysql_free_result($result);
			$SQL = "INSERT INTO banner(id,campaign_id,size,graphic,url,alt,master,show_text,popup) VALUES (null,$campaign,$size,'$url','$link','$alt','$code',$show_link,$pop)";
			if(@mysql_query($SQL,$con))
			{
				$banner_id = mysql_insert_id();
				$SQL = "INSERT INTO banner_stat(id,campaign_id,banner_id,clicks,views) VALUES(null,$campaign,$banner_id,0,0)";
				mysql_query($SQL,$con);
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
				$html .= '<tr><td align="center"><br><br>Banner Added!!!<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="self.close();"><b>Close Window</b></a><br><br></td></tr></table></td></tr></table>';
				$html .= '<br></body></html>';
				print($html);
				exit;
			}
			else
			{	echo mysql_error();
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
				$html .= '<tr><td align="center"><br><br>There is some error during banner insertion. Please try another.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="history.back();"><b>Back</b></a><br><br></td></tr></table></td></tr></table>';
				$html .= '</body></html>';
				print($html);
				exit;

			}
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
			$html .= '<tr><td align="center"><br><br>This campaign does not belong to you. Please try another.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="self.close();"><b>Close Window</b></a><br><br></td></tr></table></td></tr></table>';
			$html .= '</body></html>';
			print($html);
			exit;
		}
	}
	if(isset($cid))
	{
		$SQL = "SELECT name FROM banner_campaign WHERE id = $cid AND group_id = $group";
		$result = @mysql_query($SQL,$con);
		if(mysql_affected_rows()>0)
		{
			$row = mysql_fetch_array($result);
			$campaign_name = $row[0];
			mysql_free_result($result);
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
			$html .= '<tr><td align="center"><br><br>This campaign does not belong to you. Please try another.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="self.close();"><b>Close Window</b></a><br><br></td></tr></table></td></tr></table>';
			$html .= '</body></html>';
			print($html);
			exit;
		}
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
		$html .= '<tr><td align="center"><br><br>There is error accessing this page. You may have access this page incorrectly. <br>Please try again.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="index.php"><b>Home</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: history.back();"><b>Back</b></a><br><br></td></tr></table></td></tr></table>';
		$html .= '</body></html>';
		print($html);
		exit;
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Add Banner</title>
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
<script language="javascript">
	function checkSize(form)
	{
		if(form.size.options[form.size.selectedIndex].value=="0")
		{
			alert("Please select a banner size.");
			return false;
		}
		if(form.url.value == "")
		{
			alert("Please enter a banner URL.");
			return false;
		}
		if(form.link.value == "")
		{
			alert("Please enter a banner link.");
			return false;
		}
		return true;
	}
</script>
</head>
<body bgcolor="white">
<center>
<font class="header">Banner Manager Admin</font><br>
<form action="addb.php" method="post">
<table width="90%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr><td>&nbsp;</td></tr>
		<tr><td align="center">
		<table width="88%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr align="center">
			<td colspan="3">
				<b><?php echo $campaign_name; ?></b><br><hr width="80%">
			</td>
		</tr>
		<tr>
			<td><b>Banner File Path</b></td><td>:</td><td><input type="text" name="url" maxlength="255" size="38"></td>
		</tr>
		<tr>
			<td><b>Banner Link</b></td><td>:</td><td><input type="text" name="link" maxlength="255" size="38"></td>
		</tr>
		<tr>
			<td><b>ALT Text</b></td><td>:</td><td><input type="text" name="alt" maxlength="200" size="38"></td>
		</tr>
		<tr>
			<td><b>Show Text Link</b></td><td>:</td><td><input type="radio" name="show_link" value="1" checked>Yes&nbsp;&nbsp;<input type="radio" name="show_link" value="0">No</td>
		</tr>
		<tr>
			<td><b>Link As Popup?</b></td><td>:</td><td><input type="radio" name="pop" value="1" checked>Yes&nbsp;&nbsp;<input type="radio" name="pop" value="0">No</td>
		</tr>
		<tr>
			<td><b>Banner Size</b></td><td>:</td><td>
			<select name="size" class="copyright">
			<option value="0">[Select a Size]
			<?php
				$SQL = "SELECT * FROM banner_size";
				$result = @mysql_query($SQL,$con);
				while($row = mysql_fetch_array($result))
					echo '<option value="'.$row[0].'">'.$row[1];
			?>
			</select>
			</td>
		</tr>
		<tr><td colspan="3">** Note that all banners in a campaign are recommended be of the same size for it work properly.</td></tr>
		<tr><td colspan="3" align="right"><input type="hidden" name="campaign" value="<?php echo $cid; ?>"><input type="hidden" name="code" value="<?php echo $code; ?>"><input type="button" value="Cancel"  class="copyright" onClick="javascript: self.close();">&nbsp;&nbsp;<input type="submit" name="submitForm" value="Add Banner"  onClick="return checkSize(form);" class="copyright"></td></tr>
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