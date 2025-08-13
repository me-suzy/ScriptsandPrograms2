<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

// Start script
$admin = 1;
require_once('../includes/init.php');  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Olate Download - <?= $language['title_admin'] ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../css/style.css" title="default" />

</head>
<body><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td><form action="login.php" method="post" name="login" id="login">
    <table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="13%">
          <?= $language['title_username'] ?>
        </td>
        <td width="87%">
          <input name="username" type="text" id="username" size="12">
        </td>
      </tr>
      <tr>
        <td>
          <?= $language['title_password'] ?>
        </td>
        <td>
          <input name="password" type="password" id="password" size="12">
        </font></td>
      </tr>
      <tr>
        <td><div align="left">
            <input type="submit" name="Submit" value="Âîéòè">
        </div></td>
      </tr>
    </table>
  </form>  <br /><br /><br /> <p><?php
// Include Credits - REMOVAL WILL VOID LICENSE
require('../includes/credits.php');
?></p></td>
  </tr>
</table>
</body>
</html>