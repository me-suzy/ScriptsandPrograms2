<?php require_once("adminOnly.php");?>
<?php require_once('../config.php'); ?>

<?php
$colname_rsDelete = "1";
if (isset($_POST['deletionID'])) {
  $colname_rsDelete = (get_magic_quotes_gpc()) ? $_POST['deletionID'] : addslashes($_POST['deletionID']);
}
mysql_select_db($database, $accelgbook);
$query_rsDelete = sprintf("SELECT * FROM accelgbook WHERE ID = %s", $colname_rsDelete);
$rsDelete = mysql_query($query_rsDelete, $accelgbook) or die(mysql_error());
$row_rsDelete = mysql_fetch_assoc($rsDelete);
$totalRows_rsDelete = mysql_num_rows($rsDelete);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>modify</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="deleted.php?ID=%22.$HTTP_POST_VARS%5B%27deletionID%27%5D.%22" method="POST" name="modify" id="modify">
  <table width="600" border="0" cellpadding="2" cellspacing="1" bgcolor="#FFFFFF">
    <?php do { ?>
    <tr>
      <td width="120" bgcolor="#F3F3F3" class="forTableBgLeft">ID:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><?php echo $row_rsDelete['ID']; ?> </td>
    </tr>
    <tr>
      <td width="120" bgcolor="#F3F3F3" class="forTableBgLeft">Name: </td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><?php echo $row_rsDelete['firstName']; ?> <?php echo $row_rsDelete['lastName']; ?></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Email:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><?php echo $row_rsDelete['email']; ?></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Website:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><?php echo $row_rsDelete['website']; ?></td>
    </tr>
    <tr>
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Country:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><small><?php echo $row_rsDelete['country']; ?></small></td>
    </tr>
    <tr valign="top">
      <td bgcolor="#F3F3F3" class="forTableBgLeft">Message:</td>
      <td bgcolor="#F8F8F8" class="forTableBgRight"><?php echo $row_rsDelete['comment']; ?></td>
    </tr>
    <?php } while ($row_rsDelete = mysql_fetch_assoc($rsDelete)); ?>
    <tr class="forTableBgLeft">
      <td bgcolor="#F3F3F3" class="forTableBgLeft"><input name="ID" type="hidden" id="ID" value="<?php echo $row_rsDelete['ID']; ?>">
      <input name="deletionID" type="hidden" id="deletionID" value="<?php echo $_POST['deletionID']; ?>"></td>
      <td bgcolor="#F8F8F8" class="forTableBgLeft"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><input name="Submit" type="submit" class="forButton" value="Confirm Deletion"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="addMessage">
</form>
</body>
</html>
<?php
mysql_free_result($rsDelete);
?>
