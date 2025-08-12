<?php

	$message = '';
	if(isset($submitForm))
	{
		if($bug_message != '')
		{
			mail("admin@admin.com","Bug Report From $sender",$bug_message,"From:$email");

			$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
			$html .= '<html><head><title>Report a Bug</title><meta name="Author" content="">';
			$html .= '<meta name="Keywords" content="s">';
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
			$html .= '<tr><td align="center"><br><br>We thank you very much for helping us constantly upgrade our services. It is because people like you that we manage to upgrade our site to best suit your needs. We appreciated that and will try our best to fix the problem sonnest possible. <br>Thank you again.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="#" onClick="javascript:self.close();"><b>Close</b></a><br><br></td></tr></table></td></tr></table>';
			$html .= '<br><br></body></html>';
			print($html);
			exit;
		}
		else
		{
			$message = "Please describe the bug.";
		}
	}
?>


<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Bug Report</title>
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
		select {font: 8pt arial}
</style>
</head>
<body bgcolor="white">
<center>
<form action="bug_report.php" method="post">
<table width="70%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr><td>&nbsp;</td></tr>
		<tr><td align="center">
		<table width="78%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">
		<tr align="center">
			<td colspan="2">
				<b>Report a Bug</b><br><hr width="80%">
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2"><font color="red"><?php print($message); ?></font></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><b>Name</b></td><td>:<input type="text" name="sender"></td></tr>
		<tr><td><b>Email</b></td><td>:<input type="text" name="email"></td></tr>
		<tr>
			<td colspan="2"><b>Description</td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="bug_message" rows="10" cols = "60" class="copyright"></textarea></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td align="right" colspan="2"><input type="submit" name="submitForm" value="Catch The Bug!"></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
		</table>
		</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		</table>
		</td>
	</tr>
</table>
</form>
<?php include("./include/footer.php"); ?>
</center>
</body>
</html>
