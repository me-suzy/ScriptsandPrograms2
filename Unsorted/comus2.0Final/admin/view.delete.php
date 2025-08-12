<html>
<head>
<? echo "<title>$url</title>"; ?>
</head>

<body bgcolor="#FFFFFF">
<?
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
