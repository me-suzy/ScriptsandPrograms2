<?PHP
	require("engine.inc.php");
	mysql_query("UPDATE Messages SET init = '1' WHERE id = '$id'");
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head>
<frameset rows="50,0" frameborder="NO" border="0" framespacing="0"> 
  <frame src="send_app3.php?id=<?PHP print $id; ?>&sendval=<?PHP print $sendval; ?>&nl=<?PHP print $nl; ?>">
  <frame src="send_app2.php?id=<?PHP print $id; ?>&sendval=<?PHP print $sendval; ?>&nl=<?PHP print $nl; ?>">
</frameset>
<noframes> 
<body bgcolor="#FFFFFF" text="#000000">
</body>
</noframes> 
</html>
