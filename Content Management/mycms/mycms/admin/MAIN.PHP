<?
require_once("cms.php");



?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="75%" border="1">
  <tr> 
    <td width="28%"><a href="#">Add Content</a></td>
    <td><a href="#">Add Poll</a></td>
  </tr>
</table>
<table width="75%" border="1">
  <tr> 
    <td width="2%" height="240" rowspan="2" valign="top">&nbsp;</td>
    <td width="26%" rowspan="2" valign="top"> 
      <?
	dmenu();
	?>
    </td>
    <td width="15%"><a href="#">Add Category </a></td>
    <td width="16%"><a href="#">Add Page </a></td>
    <td width="14%"><a href="#">Edit Page</a></td>
    <td width="27%"><a href="#">Delete page</a></td>
  </tr>
  <tr> 
    <td height="206" colspan="4">
	<?
	stype($type,$menuid);
	?>
	
	</td>
  </tr>
</table>
</body>
</html>
