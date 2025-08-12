<?php require_once("adminOnly.php");?>
<?php require_once('../config.php'); ?><?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsDelete = 10;
$pageNum_rsDelete = 0;
if (isset($_GET['pageNum_rsDelete'])) {
  $pageNum_rsDelete = $_GET['pageNum_rsDelete'];
}
$startRow_rsDelete = $pageNum_rsDelete * $maxRows_rsDelete;

mysql_select_db($database, $accelgbook);
$query_rsDelete = "SELECT ID, firstName, lastName, email, comment, `date`, marker FROM accelgbook ORDER BY accelgbook.marker DESC, accelgbook.`time`DESC";
$query_limit_rsDelete = sprintf("%s LIMIT %d, %d", $query_rsDelete, $startRow_rsDelete, $maxRows_rsDelete);
$rsDelete = mysql_query($query_limit_rsDelete, $accelgbook) or die(mysql_error());
$row_rsDelete = mysql_fetch_assoc($rsDelete);

if (isset($_GET['totalRows_rsDelete'])) {
  $totalRows_rsDelete = $_GET['totalRows_rsDelete'];
} else {
  $all_rsDelete = mysql_query($query_rsDelete);
  $totalRows_rsDelete = mysql_num_rows($all_rsDelete);
}
$totalPages_rsDelete = ceil($totalRows_rsDelete/$maxRows_rsDelete)-1;

$queryString_rsDelete = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsDelete") == false && 
        stristr($param, "totalRows_rsDelete") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsDelete = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsDelete = sprintf("&totalRows_rsDelete=%d%s", $totalRows_rsDelete, $queryString_rsDelete);
?>

<html><head>
<link href="../forText.css" rel="stylesheet" type="text/css">
</head>
<body>
<form method="POST" name="deleteRcd" id="deleteRcd">
  <table width="600"  border="0" cellpadding="0" cellspacing="1">
    <tr bgcolor="#EBEBEB" class="forText">
      <td>Total Entries: <?php echo $totalRows_rsDelete ?></td>
    </tr>
  </table>
  <table width="600" border="0" cellpadding="2" cellspacing="1">
    <?php do { ?>
    <tr>
      <td width="70" rowspan="4" valign="top" class="forTableBgLeft">ID: <?php echo $row_rsDelete['ID']; ?></td>
      <td width="100" valign="top" class="forTableBgLeft">Date:</td>
      <td width="430" valign="top" class="forTableBgRight"><?php echo $row_rsDelete['date']; ?> </td>
    </tr>
    <tr>
      <td width="100" valign="top" class="forTableBgLeft">Name:</td>
      <td width="430" valign="top" class="forTableBgRight"><?php echo $row_rsDelete['firstName']; ?> <?php echo $row_rsDelete['lastName']; ?></td>
    </tr>
    <tr>
      <td valign="top" class="forTableBgLeft">Email:</td>
      <td valign="top" class="forTableBgRight"><?php echo $row_rsDelete['email']; ?></td>
    </tr>
    <tr>
      <td width="100" valign="top" class="forTableBgLeft">Message: </td>
      <td width="430" valign="top" class="forTableBgRight"><?php echo $row_rsDelete['comment']; ?>	<input name="ID" type="hidden" id="ID3" value="<?php echo $row_rsDelete['ID']; ?>">      </td>
    </tr>
    <tr valign="top">
      <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td background="../images/dot.gif"><img src="../images/dot.gif" width="3" height="5"></td>
        </tr>
      </table></td>
    </tr>
    <?php } while ($row_rsDelete = mysql_fetch_assoc($rsDelete)); ?>
</table>
  <table width="600"  border="0" cellpadding="0" cellspacing="1">
    <tr bgcolor="#EBEBEB" class="forTableBgLeft">
      <td width="50%" height="20"> <?php if ($pageNum_rsDelete > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsDelete=%d%s", $currentPage, max(0, $pageNum_rsDelete - 1), $queryString_rsDelete); ?>">Previous Page</a>
        <?php } // Show if not first page ?> </td>
      <td width="50%" height="20" align="right"> <?php if ($pageNum_rsDelete < $totalPages_rsDelete) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsDelete=%d%s", $currentPage, min($totalPages_rsDelete, $pageNum_rsDelete + 1), $queryString_rsDelete); ?>">Next Page</a>
        <?php } // Show if not last page ?> 
      </td>
    </tr>
  </table>
  
    
    
  
</form>

</body></html>
<?php
mysql_free_result($rsDelete);


?>

