<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
</head>
<? include("resize.php"); ?>
<body>
<table width="813" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="231" height="19"><strong>ORGINAL IMAGE:</strong></td>
  </tr>
  <tr>
    <td height="19"><img src="test.jpg"></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="813" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="231" height="19"><strong>RESIZED IMAGE:</strong></td>
  </tr>
  <tr>
    <td height="19"><img src="<? echo(makeimage('test.jpg','thumbnail_','imgs/',250,250)); ?>"></td>
  </tr>
</table>
</body>
</html>
