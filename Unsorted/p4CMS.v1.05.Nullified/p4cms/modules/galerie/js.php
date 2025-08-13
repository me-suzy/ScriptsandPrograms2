<table cellpadding=4 cellspacing=1><tr>
<?
 @require_once("${abs_pfad}include/config.inc.php"); 
 @require_once("${abs_pfad}include/mysql-class.inc.php");
 @require_once("${abs_pfad}include/functions.inc.php");
 
 $r = mt_rand(10000,999999);
 
 $_REQUEST[id] = $agalerie;
 
?>
<script language="javascript">
<!--
function zeigebild<?=$r;?>(id) {
 window.open('/p4cms/modules/galerie/popup.php?id=' + id, 'bild' + id, 'top=10,left=10,scrollbars=no,resizable=yes,width=100,height=100');
}
//-->
</script>
<?
 
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "galerien WHERE id='$_REQUEST[id]'");
 while ($row = $sql->FetchRow()) {
 	$prozeile = $row->prozeile;
 	$count = 0;
 	
 	$sql2 =& new MySQLq();
 	$sql2->Query("SELECT * FROM " . $sql_prefix . "galerien_bilder WHERE gallerie='$row->id'");
 	while ($row2 = $sql2->FetchRow()) {
 		$count++;
 		if ($count > $prozeile) {
 			$count = 1;
 			echo ("</tr><tr>\n");
 		}
 		$row2->titel = stripslashes($row2->titel);
 	    echo ("<td bgcolor=\"$row->bgcolor\"><a href=\"javascript:void(0);\" onClick=\"zeigebild$r('$row2->id');\" title=\"$row2->titel\"><img src=\"/p4cms/modules/galerie/bild.php?modus=thumb&id=$row2->id\" border=\"0\" name=\"$row2->titel\" id=\"$row2->titel\"></a>\r\n");
 		echo ("$row2->titel &nbsp;</td>\r\n");
	}
 	$sql2->Close();
 }
 $sql->Close();
 
 if ($count != $prozeile) {
 	for ($i=1; $i<=($prozeile - $count); $i++) {
 		echo ("<td>&nbsp;</td>\r\n");
 	}
	unset($row);
 }
?>
</tr></table>