<?
ob_start();
@require_once("../../include/config.inc.php"); 
@require_once("../../include/mysql-class.inc.php");
@require_once("../../include/functions.inc.php");
@require_once("../../modules/comment/c.config.php");

if($_REQUEST['modu']=="alle"){

 $sql2 =& new MySQLq();
 $sql2->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE datei='$_REQUEST[cid]'");
 $row2 = $sql2->FetchRow();
 $seite = stripslashes($row2->titel);
 $sql2->Close();

 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE commid='$_REQUEST[cid]' ORDER by id DESC");
 while($row=$sql->FetchRow())
 	{
	$title_length = strlen($row->titel);
	if($title_length > $maxlaenge) { $title = substr("$row->titel", 0, $maxlaenge). "..."; }else{
	$title = $row->titel;}
	
	$tpl =& new Template("${abs_pfad}modules/comment/template_alle_list.htm");		
	$tpl->Insert("{id}",  $row->id);
	$tpl->Insert("{breite}", $breitepop);
	$tpl->Insert("{hoehe}", $hoehepop);
	$tpl->Insert("{datum}", date("d.m.Y, H.i",$row->datum));
	$tpl->Insert("{titel}", stripslashes(strip_tags($title,"<b><i>")));
	$tpl->Insert("{cid}", $commid);
	$alle .= $tpl->VOut();
	}
	$tpl2 =& new Template("${abs_pfad}modules/comment/template_alle.htm");		
	$tpl2->Insert("{kommentare}", $alle);
	$tpl2->Insert("{seite}", $seite);
	$tpl2->Insert("{cid}", $_REQUEST['cid']);
	$tpl2->POut();
	
} else {
//================================================
// einzelner Kommentar
//================================================
if(isset($_REQUEST['id'])){
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE datei='$_REQUEST[cid]'");
 $row2 = $sql->FetchRow();
 $seite = stripslashes($row2->titel);
 $sql->Close();
 
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE id='$_REQUEST[id]'");
 $row = $sql->FetchRow();
 $sql->Close();
 
 $titel = stripslashes(strip_tags($row->titel));
 $text = substr(nl2br(stripslashes(htmlentities($row->text))),0, $maxpost);
 $text = parseURL($text);
$text = BBcode($text);
 
 if($row->email!="")
 	{
	$poster = "<a href=\"mailto:".$row->email."\">".strip_tags(stripslashes($row->name))."</a>";
	} else {
	$poster  = strip_tags(stripslashes($row->name));}
	
	$tpl =& new Template("${abs_pfad}modules/comment/template_kommentar.htm");		
	$tpl->Insert("{titel}",  $titel);
	$tpl->Insert("{text}", $text);
	$tpl->Insert("{poster}", $poster);
	$tpl->Insert("{seite}", $seite);
	$tpl->Insert("{datum}", date("d.m.Y, H.i",$row->datum));
	$tpl->POut();
}

}
?>

