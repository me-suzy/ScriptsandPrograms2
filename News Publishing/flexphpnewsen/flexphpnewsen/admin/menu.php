<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body text="#000000" bgcolor="#6699CC" topmargin="2">
<a href="http://www.china-on-site.com/flexphpsite/" target="_blank"><img src="images/admin_logo.gif" width="160" height="26" border="0"></a> 
<table width="100%" border="0" cellspacing="1" cellpadding="4" bgcolor="#FFFFFF">
  <tr> 
    <td bgcolor="#F2F2F2"><a href="admin_index.php" target="mainFrame" class="en_b"><?php print "$admin_adminindex"; ?></a></td>
  </tr>
  <tr>
    <td bgcolor="#F2F2F2"><a href="catadmin.php" target="mainFrame" class="en_b"><?php print "$admin_catalogadmin"; ?></a></td>
  </tr>
  <tr> 
    <td bgcolor="#F2F2F2"><a href="newsadmin.php" target="mainFrame" class="en_b"><?php print "$admin_newsadmin"; ?></a></td>
  </tr>  
</table>
</body>
</html>
