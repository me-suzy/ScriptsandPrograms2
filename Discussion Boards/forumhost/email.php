<?
if (!$id || !$mid ) exit;
include "./config.php";
$sql = "select email from $tableposts where id='$mid' and owner='$id'";
$result = mysql_query($sql);
$resrow = mysql_fetch_row($result);
$email = $resrow[0];
Header("Location: mailto:$email");
exit;
?>
