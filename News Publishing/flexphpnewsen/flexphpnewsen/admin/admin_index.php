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

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top"> 
      <?php
      include("top.php3");
      ?>
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4" height="300">
        <tr> 
          <td align="center"> 
            <p><?php print "$admin_welcome"; ?>,<?php print "$loginuser"; ?></p>            
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
<?php
include("bottom.php3");
?>
</body>
</html>
