<?php require_once('config.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsRead = 5;
$pageNum_rsRead = 0;
if (isset($_GET['pageNum_rsRead'])) {
  $pageNum_rsRead = $_GET['pageNum_rsRead'];
}
$startRow_rsRead = $pageNum_rsRead * $maxRows_rsRead;

mysql_select_db($database, $accelgbook);
$query_rsRead = "SELECT * FROM accelgbook ORDER BY accelgbook.marker DESC, accelgbook.`time`DESC";
$query_limit_rsRead = sprintf("%s LIMIT %d, %d", $query_rsRead, $startRow_rsRead, $maxRows_rsRead);
$rsRead = mysql_query($query_limit_rsRead, $accelgbook) or die(mysql_error());
$row_rsRead = mysql_fetch_assoc($rsRead);

if (isset($_GET['totalRows_rsRead'])) {
  $totalRows_rsRead = $_GET['totalRows_rsRead'];
} else {
  $all_rsRead = mysql_query($query_rsRead);
  $totalRows_rsRead = mysql_num_rows($all_rsRead);
}
$totalPages_rsRead = ceil($totalRows_rsRead/$maxRows_rsRead)-1;

$queryString_rsRead = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsRead") == false && 
        stristr($param, "totalRows_rsRead") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsRead = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsRead = sprintf("&totalRows_rsRead=%d%s", $totalRows_rsRead, $queryString_rsRead);

# © Peter Affentranger, ANP Affentranger Net Productions, www.anp.ch
function MakeHyperlink($text) {
	$text = preg_replace("/((http(s?):\/\/)|(www\.))([\S\.]+)\b/i","<a href=\"http$3://$4$5\" target=\"_blank\">$2$4$5</a>", $text);
	$text = preg_replace("/([\w\.]+)(@)([\S\.]+)\b/i","<a href=\"mailto:$0\">$0</a>",$text);
	return nl2br($text);
}
?>
<table width="500"  border="0" align="center" cellpadding="1" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="Header_Footer_bg">
      <tr class="forText">
        <td width="50%">Displaying: <?php echo ($startRow_rsRead + 1) ?> - <?php echo min($startRow_rsRead + $maxRows_rsRead, $totalRows_rsRead) ?> </td>
        <td width="50%" align="right">Total Messages: <?php echo $totalRows_rsRead ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><table width="497"  border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td background="images/dot.gif"><img src="images/dot.gif" width="3" height="5"></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">

  <?php do { ?>
  <tr valign="top">
    <td width="120" class="forTableBgLeft">Date:</td>
    <td width="380" class="forTableBgRight"><?php echo $row_rsRead['date']; ?></td>
  </tr>
  <tr valign="top">
    <td width="120" class="forTableBgLeft">Name:</td>
    <td width="380" class="forTableBgRight"><?php echo $row_rsRead['firstName']; ?> <?php echo $row_rsRead['lastName']; ?></td>
  </tr>
  <tr valign="top">
    <td width="120" class="forTableBgLeft">Email:</td>
    <td width="380" class="forTableBgRight"><?php echo MakeHyperlink($row_rsRead['email']); ?></td>
  </tr>
  <tr valign="top">
    <td width="120" class="forTableBgLeft">Website:</td>
    <td width="380" class="forTableBgRight"><?php echo MakeHyperlink($row_rsRead['website']); ?></td>
  </tr>
<tr valign="top">
    <td width="120" class="forTableBgLeft">Country:</td>
    <td width="380" class="forTableBgRight"><?php echo $row_rsRead['country']; ?></td>
  </tr>
  <tr valign="top">
    <td width="120" class="forTableBgLeft">Message:</td>
    <td width="380" class="forTableBgRight"><?php echo $row_rsRead['comment']; ?></td>
  </tr>
  <tr valign="top">
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="images/dot.gif"><img src="images/dot.gif" width="3" height="5"></td>
      </tr>
    </table></td>
  </tr>
  <?php } while ($row_rsRead = mysql_fetch_assoc($rsRead)); ?>
</table>
<table width="498"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr valign="middle" class="Header_Footer_bg">
    <td width="30%" height="20">&nbsp;<?php if ($pageNum_rsRead > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsRead=%d%s", $currentPage, max(0, $pageNum_rsRead - 1), $queryString_rsRead); ?>">Previous Page</a>
    <?php } // Show if not first page ?></td>
    <td width="30%" align="center"><a href="signgbook.php">Sign Guestbook</a></td>
    <td width="30%" align="right"><?php if ($pageNum_rsRead < $totalPages_rsRead) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsRead=%d%s", $currentPage, min($totalPages_rsRead, $pageNum_rsRead + 1), $queryString_rsRead); ?>">Next Page</a>
    <?php } // Show if not last page ?>
    &nbsp;</td>
  </tr>
</table>
<?php
mysql_free_result($rsRead);
?>
