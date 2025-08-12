<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<html>
<head>
<title>Comus TGP Link Cleanup Page</title>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<?php
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

   $query = "SELECT * FROM tblCategories ORDER BY Category";
   $result = mysql_query ($query)
        or die ("Query failed"); 
?>
<center><a href="index.php"><b><font size=-1 face=arial>Return to main page</font></b></a></center><br>
<table width="600" border="0" align="center">
  <tr>
    <td>Note to admin: When clicking a link below, depending on 
      how many galleries you have, the resulting page may take 
      a *long* time to load. Please be patient when waiting.</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="320" border="0" align="center">
  <tr>
    <td bgcolor="0099CC"><b><font color="white">Click on a category 
      to check for broken links</font></b></td>
  </tr>
  <tr>
    <td><?php if ($result) {
   while ($r = mysql_fetch_array($result)) { 
   $Category = $r["Category"];
echo "<a href=\"new.check.php?choice=$Category\">$Category</a><br>";
 
   }
}?></td>
  </tr>
  <tr>
    <td bgcolor="0099CC">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
