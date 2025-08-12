<?php
SingletonQueue();
header('HTTP/1.1 404 OK');
?>
<html>
<head>
	<title>FuseLogic 404!</title>
</head>

<body>
<center>
<br><br>
<table width=500 cellpadding=7 cellspacing=0 border=0>
<tr><td align=center><font face=arial size=+1><b>Sorry, the page you requested was not found.</b></font></td></tr>
<tr><td><font face=arial size=-1>Please check the URL for proper spelling and capitalization. If you're having trouble locating a destination on our website, try visiting the <b><a href="http://<?php echo $_SERVER['HTTP_HOST']?>">home page</a></b></font>
</td></tr>
<tr><td>
		<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=#cc0033><tr><td height=10></td></tr></table>
</td></tr>
</table>
<br>
<hr size=1 noshade width=650>
<font face=arial size=-2>Copyright &copy; 2003 <?php echo $_SERVER['HTTP_HOST'];?>! Inc. All rights reserved. <a href="http://fuselogic.haltebis.com">Power By FuseLogic <?php echo FL_VERSION; ?></a></font>

</center>
<?php
echo '<p align="center"><i><font face=arial size=-1>Webmaster Note : '.$FuseLogic->errormessage.'</font></i></p>';
?>
</body>
</html>
<?php 
@include_once('404_to_email.php');
?>