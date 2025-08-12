<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<?php // Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php"); 

?>
<html>
<head>
<title>Comus TGP Admin Page</title>
</head>
<body bgcolor="#FFFFFF">
<table width="650" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr bgcolor="0099CC"> 
    <td colspan="3"> 
      <div align="center"><font face="Arial" size="-1" color-white="color-white" color="white"><b>Comus 
        TGP Administrative Page</b></font></div>
    </td>
  </tr>
  <tr> 
    <td colspan="3" height="48"> 
      <div align="center"></div>
    </td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999" height="30"> <font face="Arial" size="-1"><b><a href="admin.php">New 
      Posts</a></b></font><font face="Arial" size="-1"></font></td>
    <td colspan="2" width="499" height="30"><font face="Arial" size="-1">View, 
      accept and deny new posts</font></td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999"><b><font face="Arial" size="-1"><a href="preferred.php">Preferred</a></font></b></td>
    <td colspan="2" width="499"><font face="Arial" size="-1">View, 
      add and remove from Preferred Submitter List</font></td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999"><b><font face="Arial" size="-1"><a href="blacklist.php">Black 
      List</a></font></b></td>
    <td colspan="2" width="499"><font face="Arial" size="-1">View, 
      add and remove from Black List</font></td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999"><b><a href="categories.php">Categories</a></b></td>
    <td colspan="2" width="499"> 
      <p><font face="Arial" size="-1">View, add and remove categories</font></p>
    </td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999"><a href="config.setup.php"><b>Change 
      Settings</b></a></td>
    <td colspan="2" width="499"> 
      <p><font face="Arial" size="-1">Change site-wide settings 
        here </font></p>
    </td>
  </tr>
  <tr> 
    <td width="137" bordercolor="#999999"><b><a href="check.list.php">Clean 
      up </a></b></td>
    <td colspan="2" width="499"> 
      <p><font face="Arial" size="-1">Gallery Clean up. Check 
        for and remove dead links</font></p>
    </td>
  </tr>
</table>

</body>
</html>
