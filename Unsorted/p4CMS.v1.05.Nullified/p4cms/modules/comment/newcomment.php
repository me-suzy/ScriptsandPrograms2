<?
ob_start();
@require_once("../../include/config.inc.php"); 
@require_once("../../include/mysql-class.inc.php");
@require_once("../../include/functions.inc.php");
@require_once("../../modules/comment/c.config.php");

$cid=$_REQUEST['cid'];
if($_REQUEST['send']=="1")
{
 $sql =& new MySQLq();
 $sql->Query("INSERT INTO " . $sql_prefix . "kommentare (id,commid,datum,email,name,titel,text) VALUES ('','".$cid."','".time()."','".strip_tags($_REQUEST['email'])."','".strip_tags($_REQUEST['name'])."','".addslashes(strip_tags($_REQUEST['titel']))."','".substr(addslashes(htmlspecialchars($_REQUEST['text'])),0,$maxpost)."')");
 header("location:comment.php?modu=alle&cid=$cid");
}

	$tpl =& new Template("${abs_pfad}modules/comment/template_neuerkommentar.htm");		
	$tpl->Insert("{cid}",  $_REQUEST['cid']);
	$tpl->Insert("{anzahl}",  $maxpost);
	
	$tpl->POut();
?>

