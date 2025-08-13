<?
 @require_once("${abs_pfad}include/config.inc.php"); 
 @require_once("${abs_pfad}include/mysql-class.inc.php");
 @require_once("${abs_pfad}include/functions.inc.php");
 @require_once("${abs_pfad}modules/comment/c.config.php");

$commid = str_replace("_print", "", $commid);
 
if($commid){
$commenttrue = "1";
}
 
 if($commenttrue=="1"){
 
 
 echo "
  <script language=\"javascript\">
  function kommentar(id,breite,hoehe,commid,mode) {
  var winWidth = breite;
  var winHeight = hoehe;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'http://$_SERVER[HTTP_HOST]/p4cms/modules/comment/comment.php?id='+id+'&cid='+commid+'&modu='+mode;
  var name = 'name';
  var features = 'scrollbars=yes,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);}
  
  function kommentarneu(breite,hoehe,commid) {
  var winWidth = breite;
  var winHeight = hoehe;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'http://$_SERVER[HTTP_HOST]/p4cms/modules/comment/newcomment.php?&cid='+commid;
  var name = 'name';
  var features = 'scrollbars=yes,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);}
//kommentarneu('{breite}','{hoehe}','{cid}')

</script>";
 
 
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE commid='$commid'");
 $num = $sql->NumRows();

$sql->Close();

$tpl =& new Template("${abs_pfad}modules/comment/template_anzeige.htm");		
$tpl->Insert("{anzahl}",  $num);
$tpl->Insert("{max}", $maxshow);
$tpl->Insert("{id}",  $row->id);
$tpl->Insert("{breite}", $breitepop);
$tpl->Insert("{hoehe}", $hoehepop);
$tpl->Insert("{titel}", stripslashes(strip_tags($title,"<b><i>")));
$tpl->Insert("{cid}", $commid);
$tpl->POut();



 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE commid='$commid' order by  id DESC limit 0,".$maxshow."");

 while($row = $sql->FetchRow())
 	{
	
	$title_length = strlen($row->titel);
	if($title_length > $maxlaenge) { $title = substr("$row->titel", 0, $maxlaenge). "..."; }else{
	$title = $row->titel;}
	
	if(strlen(str_replace(" ","",$row->text)) > $maxtextseite-1){$zusatz = "...";}
	
	$text = substr(stripslashes(strip_tags($row->text)),0, $maxtextseite);
	$text = str_replace("<br />"," ",$text);
	$text = parseURL($text);
	$text = BBcode($text);
	
	
	$tpl =& new Template("${abs_pfad}modules/comment/template_anzeige_titel.htm");		
	$tpl->Insert("{id}",  $row->id);
	$tpl->Insert("{breite}", $breitepop);
	$tpl->Insert("{hoehe}", $hoehepop);
	$tpl->Insert("{titel}", strip_tags($title,"<b><i>"));
	$tpl->Insert("{cid}", $commid);
	$tpl->Insert("{text}", $text.$zusatz);
	$tpl->POut();
	unset($text);
	unset($zusatz);
	}
 }
 
 
 
 
?>