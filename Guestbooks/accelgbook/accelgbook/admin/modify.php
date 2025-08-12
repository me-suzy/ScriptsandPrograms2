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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modify")) {
  $updateSQL = sprintf("UPDATE adesbook SET firstName=%s, lastName=%s, country=%s, email=%s, website=%s, comment=%s WHERE ID=%s",
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['website'], "text"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_adesGBook, $adesGBook);
  $Result1 = mysql_query($updateSQL, $adesGBook) or die(mysql_error());

  $updateGoTo = "updated.php?modifyID=".$HTTP_POST_VARS['ID']."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsModify = "1";
if (isset($_POST['modifyID'])) {
  $colname_rsModify = (get_magic_quotes_gpc()) ? $_POST['modifyID'] : addslashes($_POST['modifyID']);
}
mysql_select_db($database, $accelgbook);
$query_rsModify = sprintf("SELECT * FROM accelgbook WHERE ID = %s", $colname_rsModify);
$rsModify = mysql_query($query_rsModify, $accelgbook) or die(mysql_error());
$row_rsModify = mysql_fetch_assoc($rsModify);
$totalRows_rsModify = mysql_num_rows($rsModify);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>modify</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="modify" id="modify">
  <table width="600" border="0" cellpadding="2" cellspacing="1">
    <tr>
      <td width="120" bgcolor="#F3F3F3" class="forTableBgLeft">First Name:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><input name="firstName" type="text" class="forForm" id="firstName" value="<?php echo $row_rsModify['firstName']; ?>" size="25"></td>
    </tr>
    <tr>
      <td width="120" bgcolor="#F3F3F3" class="forTableBgLeft">Last Name: </td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><input name="lastName" type="text" class="forForm" id="lastName" value="<?php echo $row_rsModify['lastName']; ?>" size="25"></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Email:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><input name="email" type="text" class="forForm" id="email" value="<?php echo $row_rsModify['email']; ?>" size="25"></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Website:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><input name="website" type="text" class="forForm" id="website" value="<?php echo $row_rsModify['website']; ?>" size="25"></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Country:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><font face="Arial, Helvetica"><small>
        <input name="country" type="text" class="forForm" id="country" value="<?php echo $row_rsModify['country']; ?>" size="25">
      </small></font></td>
    </tr>
    <tr valign="top">
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Message:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><textarea name="comment" cols="50" rows="6" class="forForm" id="comment"><?php echo $row_rsModify['comment']; ?></textarea></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft"><input name="ID" type="hidden" id="ID" value="<?php echo $row_rsModify['ID']; ?>"></td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><input name="Submit" type="submit" class="forButton" value="Modify"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="addMessage">
  <input type="hidden" name="MM_update" value="modify">
</form>
</body>
</html>
<?php
mysql_free_result($rsModify);
?>
