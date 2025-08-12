<?php require_once('../config.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($_POST['deletionID'])) && ($_POST['deletionID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM adesbook WHERE ID=%s",
                       GetSQLValueString($_POST['deletionID'], "int"));

  mysql_select_db($database_adesGBook, $adesGBook);
  $Result1 = mysql_query($deleteSQL, $adesGBook) or die(mysql_error());
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>deleted</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	color: #FF3300;
	font-size: 12px;
}
-->
</style>
</head>

<body>
<table width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td height="30" class="forTableBgLeft"><div align="center">Deleted Successfully
      </div></td>
  </tr>
  <tr>
    <td class="forTableBgRight"><div align="center">Record ID [ <?php echo $_POST['deletionID']; ?> ] has been deleted from the database </div></td>
  </tr>
</table>
</body>
</html>
