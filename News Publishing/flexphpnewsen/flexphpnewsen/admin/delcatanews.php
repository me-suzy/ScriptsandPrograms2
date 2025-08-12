<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");
?>
<html>
<head>
<title><?php print "$admin_newsadmin"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form method="POST" action="catalognews.php">
<input type="hidden" name="newsid" value="<?php print "$newsid"; ?>">
<input type="hidden" name="catid" value="<?php print "$catid"; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top"> 
      <?php
      include("top.php3");
      ?>
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td align="center"> 
            <p><H3><?php print "$admin_delconfirm"; ?></H3></p>            
          </td>
        </tr>
        <tr> 
          <td align="center"> 
            <p><input type="submit" name="Delnews" value="<?php print "$admin_yes"; ?>">&nbsp;
            <input type="submit" name="Delnews" value="<?php print "$admin_no"; ?>"></p>            
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
</form>
<?php
include("bottom.php3");
?>
</body>
</html>



