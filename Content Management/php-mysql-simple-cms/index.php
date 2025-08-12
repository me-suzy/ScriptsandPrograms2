<?php 
include "vars.php";
?>
<html>
<head>
<title><? echo "$title"; ?></title>
<meta name="keywords" content="<? echo $keywords; ?>">
<meta name="description" content="<? echo $description; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<? echo $backcolor; ?>" leftmargin="0" topmargin="0" marginwidth="0">
<br>
<table width="720" border="0" align="center" class="hoofdtabel">
<tr>
<td colspan="2" align="left" height="75" valign="top">
  <h1><? echo "$title"; ?></h1>
  <i><? echo "$description"; ?></i><br><br>
  </td>
</tr>
<tr>
<td width="150" align="center" valign="top">
<? include "nav.php"; ?>
</td>
<td width="550">
<iframe src="page.php?id=<?php echo $startpage; ?>" width="550" height="400" name="centerframe" id="centerframe" frameborder="0"></iframe> 
</td>

</tr>
<tr>
	<td colspan="2" align="right"><small><a name="simple CMS" id="simple CMS" href="http://www.cms-center.com" target="_blank" title="simple CMS">powered by php mysql simple cms</a></small></td>
</tr>
</table>

</body>
</html>
