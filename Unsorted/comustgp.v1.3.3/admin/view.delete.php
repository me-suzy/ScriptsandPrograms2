<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<html>
<head>
<?php echo "<title>$url</title>"; ?>
</head>

<body bgcolor="#FFFFFF">
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

/* Delete from listing */

if (isset($zap)) {

   $Query = "DELETE FROM tblTgp WHERE id='$id'";
      $result = mysql_query($Query, $conn);
}
Echo "$url<br>Has been deleted";
?> 
</body>
</html>
