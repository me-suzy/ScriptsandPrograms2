<?php 
	$m = stripslashes($m);
	$s = stripslashes($s);
	$l = stripslashes($l);
?>

<html>
<head>
<title><?php echo ($s) ?></title>
<link rel="stylesheet" HREF="<?php echo "../essential/{$l}_default.css"; ?>" type="text/css">
</head>

<body bgcolor="#FFFFFF" background="../images/design/background.gif" leftmargin="0" topmargin="1" marginwidth="0" marginheight="0">
  <table width="98%" border="0" cellspacing="1" cellpadding="4" align="center">
    <tr> 
      <td class=tn align=center>
	<br><?php echo $m ?>
	</td>
    </tr>
  </table>
</body>
</html>