<?
include("include/include.inc.php");
 
if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
 SessionError();
 exit;
}

$sql =& new MySQLq();
$sql->Query("DELETE FROM " . $sql_prefix . "variablen WHERE id='$_REQUEST[id]'");
$sql->Close();
?>
<script language="javascript">
<!--
 alert("Die Variable wurde gelöscht. Bitte beachten Sie, dass diese erst \nbei einem neuladen der Seite aus dem Variablenmanager verschwindet.");
 window.close();
//-->
</script>