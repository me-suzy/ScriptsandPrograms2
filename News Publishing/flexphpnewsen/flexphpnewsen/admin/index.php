<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");
?>
<html>
<head>
<title><?php print "$admin_adminindex"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
</head>
<frameset cols="180,*" frameborder="NO" rows="*"> 
  <frame name="leftFrame" scrolling="NO" noresize src="menu.php">
  <frame name="mainFrame" src="admin_index.php">
</frameset>
<noframes> 
<body bgcolor="#FFFFFF" text="#000000">
</body>
</noframes> 
</html>
