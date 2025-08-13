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
  <td><div align="center">
    <p><strong><?= $language['description_accessdenied'] ?></strong></p>
    <p><a href="index.php"><?= $language['link_clicktologin'] ?></a></p>
  </div><br /><br /><br />    
    <p><?php
// Include Credits - REMOVAL WILL VOID LICENSE
require('../includes/credits.php');
?></p></td></tr>
</table>
</body>
</html>