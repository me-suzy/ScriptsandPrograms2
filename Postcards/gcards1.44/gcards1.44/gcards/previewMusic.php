<?
include_once('config.php');
include_once('inc/UIfunctions.php');
$page = new pagebuilder;
include_once('inc/setLang.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title><? echo $siteName;?></title>
</head>
<link href="css/style.css" rel="stylesheet" type="text/css">
<body>

<div align="center">
<EMBED SRC="sound/<? echo $_GET['music'];?>" AUTOSTART="true" LOOP="true" WIDTH="290" HEIGHT="55" ALIGN="CENTER">
</EMBED>
<br><br>
<a href="#" onClick="window.close();">[ <? echo $preview06;?> ]</a>
</div>

</body>
</html>
