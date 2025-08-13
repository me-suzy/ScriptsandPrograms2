<?php
  // Process config file to determine default server (if any)
  require('lib.inc.php');
?>

<html>
<head>
<title>phpMyAdmin</title>
</head>

<frameset cols="150,*" rows="*" border="0" frameborder="0"> 
  <frame src="left.php?server=<?php echo $server;?>" name="nav">
  <frame src="main.php?server=<?php echo $server;?>" name="phpmain">
</frameset>
<noframes>
<body bgcolor="#FFFFFF">

</body>
</noframes>
</html>
