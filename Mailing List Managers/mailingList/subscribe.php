<?php
session_start();
if(empty($m))
{
	$message = '';
}
if(@$m == 1)
{
	@$message = '**verification does not match';
}
if(@$m == 2)
{
	@$message = '**fill in your email address';
}
if(@$m == 3)
{
	@$message = '**provide us with your name';
}
if(@$m == 4)
{
	@$message = '**email address exist';
}
if(@$m == 5)
{
	@$message = '**To prevent spam, please verify your email address';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Subscribe to our mailing list</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	font: 13px "Arial", "Helvetica", "sans-serif";
	color: #333333;
	margin-top: 60px;
	margin-left: 250px;
}
.subscribe {
	font: 13px "Arial", "Helvetica", "sans-serif";
	color: #333333;
	background: #eeeeee;
	border-top: 1px dashed #666666;
	border-right: none #666666;
	border-bottom: 1px dashed #666666;
	border-left: none #666666;
	padding: 4px;
}
.copyright {
	font: 11px "Verdana", "Arial", "Helvetica", "sans-serif";
	color: #666666;
}
a.copyright:link {
	fonr: 11px "verdana";
	color: #0000ff;
	text-decoration: underline;
}
a.copyright:visited {
	fonr: 11px "verdana";
	color: #0000ff;
	text-decoration: underline;
}
a.copyright:hover {
	fonr: 11px "verdana";
	color: #8fa9e5;
	text-decoration: underline;
}
-->
</style>
</head>

<body>
<?php echo '<font color="#ff0000"><b>'.$message.'</b></font>'; ?>
<form action="subsProcess.php" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0" class="subscribe">
<tr> 
      <td width="190">Your name:</td>
    <td width="302"><input name="name" type="text" maxlength="70"></td>
  </tr>
  <tr> 
    <td width="190">Email address:</td>
    <td width="302"><input name="email" type="text" id="email" maxlength="63"></td>
  </tr>
  <tr> 
      <td>Verify code:<br>
        <img src="inc/image.php"></td>
      <td valign="top">
        <input name="verifyImage" type="text" id="verifyImage">
      </td>
  </tr>
<tr> 
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Subscribe Me"></td>
  </tr>
</table>

<table width="500" border="0" cellspacing="0" cellpadding="0" class="copyright">
  <tr> 
    <td width="303">Powered By <a href="http://www.hotwebtools.com" target="_blank">Hotwebtools.com</a> 
      &copy; 2005</td>
    <td width="197">
      <div align="right">Opensource application <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GPL</a></div></td>
  </tr>
</table>
</form>
</body>
</html>
