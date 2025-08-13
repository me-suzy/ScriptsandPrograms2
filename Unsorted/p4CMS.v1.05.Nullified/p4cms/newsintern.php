<?
ob_start();
include("include/include.inc.php");
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;}
 
 $limitpage = 25;

 	?>
 <html>
<head>
<? StyleSheet(); ?>
<link rel="stylesheet" href="/p4cms/style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<? if($_REQUEST['act']==""){ 
$sql =& new MySQLq();
if($_REQUEST['show']=="" || $_REQUEST['show']=="alle"){
$zusatz = "";}

else{$zusatz = " where status='$_REQUEST[show]' ";}
                $sql->Query("SELECT * FROM " . $sql_prefix . "newsintern $zusatz ORDER BY id DESC");
				
              	$number = $sql->Numrows();
				$wieviele = $number;
				
				if($_REQUEST['start']==""){
				$start=0;}
				  
				if($_REQUEST['start']!=""){
				$start=$_REQUEST['start'];}
				$chk_start=$start;
	
			   	$sql =& new MySQLq();
              	$sql->Query("SELECT * FROM " . $sql_prefix . "newsintern $zusatz ORDER BY id DESC LIMIT $start,$limitpage");
				$number = $sql->Numrows();

?>

                <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

 <tr>
   <td height="17" colspan="7" class="boxheader"><b>News</b></td>
 </tr>
 <tr bgcolor="#FAFAFB">
   <td height="17" colspan="7">
     <table width="100%" border="0" cellpadding="0" cellspacing="1">
       <tr>
         <td>&nbsp;</td>
         <td width="1%" nowrap>
           <div align="right"><a href="newsintern.php?act=edit&newsid=<?=$row->id;?>">
             <? if ($HTTP_SESSION_VARS[u_gid] == 1) { ?>
             </a><a href="newsintern.php?act=new"><img src="/p4cms/gfx/code.png" alt="" width="16" height="16" border="0" align="absmiddle">News schreiben</a> &nbsp;&nbsp;
             <? } ?>
           </div>
         </td>
         <td width="1%" nowrap>
           <form name="form2" method="post" action="">
             <select name="show" id="show">
               <option>alle</option>
               <option value="zu erledigen" <? if($_REQUEST['show']=="zu erledigen"){echo"selected";}?>>zu erledigende</option>
               <option value="erledigte" <? if($_REQUEST['show']=="erledigte"){echo"selected";}?>>erledigte</option>
               <option value="in Arbeit" <? if($_REQUEST['show']=="in Arbeit"){echo"selected";}?>>in Arbeit</option>
             </select>
             <input type="submit" class="button" value="anzeigen">
           </form>
         </td>
         <td width="1%" nowrap>
           <div align="right">
             <? if($wieviele > $limitpage){$nav="news";include("nextfor.php");}  ?>
           </div>
         </td>
       </tr>
     </table>
   </td>
   </tr>
 <tr>
                <td height="17" class="boxheader">&nbsp;Titel</td>
                <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">Kommentare</div></td>
                <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">Autor</div></td>
                <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">Status</div></td>
                <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">Wichtigkeit</div></td>
                <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">Datum </div></td>
                <td width="5%" height="17" class="boxheader" alt="Abonenten der Liste anzeigen">&nbsp; </td>
                </tr>
                <?
                while ($row = $sql->FetchRow()) {
				
				$comments = "";
				$sql4 =& new MySQLq();
              	$sql4->Query("SELECT * FROM " . $sql_prefix . "newsintern_kommentare WHERE newsid='$row->id'");
				$comments = $sql4->Numrows();
                	?>
                	<tr bgcolor="#FAFAFB">
                	<td height="17"><a href="newsintern.php?act=show&newsid=<?=$row->id;?>"><?=stripslashes($row->titel);?></a></td>
					<td>
					  <div align="center">
					  <?=$comments?>
					</div></td>
					<td nowrap>
					  <div align="center">
					  <?=stripslashes($row->autor);?>
					</div></td>
					<td nowrap>
					  <div align="center">
					  <?=stripslashes($row->status);?>
					</div></td>
					<td nowrap>					  
					  <div align="center">
					  <?=stripslashes($row->wichtigkeit);?>
</div></td>
					<td nowrap>
					  <div align="center">
					  <?=date("d.m.y H.i", $row->datum);?>
					</div></td>
					<td width="5%" height="17" nowrap>
					  <div align="center"><a href="newsintern.php?act=edit&newsid=<?=$row->id;?>">
					    <? if ($HTTP_SESSION_VARS[u_gid] == 1) { ?>
					    <img src="/p4cms/gfx/edit.gif" border="0" align="absmiddle"></a><a href="newsintern.php?act=del&newsid=<?=$row->id;?>&titel=<?=stripslashes($row->titel);?>"><img src="/p4cms/gfx/del.gif" width="20" height="20" border="0" align="absmiddle"></a>
					    <? } ?>
					  </div>
					</td>
               	 </tr>
                	<?
                }
                //$sql->Close();
                ?>
               </table>
               <br><br>
<!--- GANZE NEWS --->
<?  } if($_REQUEST['act']=="show"){

	if($_REQUEST['del']=="1"){
	$sql3 =& new MySQLq();
	$sql3->Query("DELETE FROM " . $sql_prefix . "newsintern_kommentare WHERE id='$_REQUEST[commid]'");
	eLog("user", "$_SESSION[u_user]  löscht Newskommentar");	
	}

	$sql2 =& new MySQLq();
	$sql2->Query("SELECT * FROM " . $sql_prefix . "newsintern WHERE id = '$_REQUEST[newsid]'");
	$row2 = $sql2->FetchRow();
	
	if($_REQUEST['send']=="1"){
	$sql3 =& new MySQLq();
	$sql3->Query("INSERT INTO " . $sql_prefix . "newsintern_kommentare (newsid,id,titel,text,autor)VALUES('$_REQUEST[id]','','".addslashes(htmlspecialchars($_REQUEST['titel']))."','".addslashes(htmlspecialchars($_REQUEST['text']))."','$_REQUEST[autor]')");
	eLog("user", "$_SESSION[u_user]  schreibt einen Newskommentar zu News \"$_REQUEST[newstitel]\"");	
	}
	
	
?>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
    <td align="left" valign="top" class="boxstandart">
      <table width="100%">
        <tr>
          <td><h3>
		  <?=stripslashes(strip_tags($row2->titel));
		  $net = stripslashes(strip_tags($row2->titel));
		  ?></h3></td>
          <td><div align="right"><a href="newsintern.php"><img src="/p4cms/gfx/code.png" alt="" width="16" height="16" border="0" align="absmiddle">News&uuml;bersicht</a>&nbsp;&nbsp;<a href="#comment"><img src="/p4cms/gfx/code.png" alt="" width="16" height="16" border="0" align="absmiddle">Kommentar schreiben </a></div></td>
        </tr>
      </table>
      <table>
        <tr>
          <td>
          <?=stripslashes($row2->text);?>
          </td>
        </tr>
      </table>      
</td>
  </tr>
</table>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

 <tr>
   
<?
	$sql2 =& new MySQLq();
	$sql2->Query("SELECT * FROM " . $sql_prefix . "newsintern_kommentare WHERE newsid = '$_REQUEST[newsid]' order by id DESC");
	$number = $sql2->Numrows();
	$wieviele = $number;
	
	if($_REQUEST['start']==""){
    $start=0;}
      
	if($_REQUEST['start']!=""){
    $start=$_REQUEST['start'];}
	$chk_start=$start;
	
	$sql2 =& new MySQLq();
	$sql2->Query("SELECT * FROM " . $sql_prefix . "newsintern_kommentare WHERE newsid = '$_REQUEST[newsid]' order by id DESC limit $start,$limitpage");
	$number = $sql2->Numrows();
	?>
	 <td align="left" valign="top" class="boxstandart">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><b>Kommentare</b></td>
          <td><? if($wieviele > $limitpage){include("nextfor.php");}  ?></td>
        </tr>
      </table>
      <br>
	  <?
	while ( $row2 = $sql2->FetchRow())
	{
	?>
	<table width="100%"  border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><b><font color="#FF9900"><?=stripslashes(strip_tags($row2->titel));?></font></b> (<?=stripslashes(strip_tags($row2->autor));?>)</td>
              <td><div align="right"><a href="newsintern.php?act=show&newsid=<?=$_REQUEST['newsid']?>&del=1&commid=<?=$row2->id;?>"><? if ($HTTP_SESSION_VARS[u_gid] == 1) { ?><img src="/p4cms/gfx/del.gif" width="20" height="20" border="0"></a><? } ?></div></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><i>
          <?=nl2br(stripslashes($row2->text));?>
        </i></td>
      </tr>
    </table>	
	<hr size="1" noshade>
	
	<? }

	 
	
	?>	</td>
  </tr>
</table>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
    <td align="left" valign="top"><b><a name="comment"></a>Kommentar schreiben</b><br>
      <br>
	  <!-- FORM -->
	  <form name="form1" method="post" action="">
	     Autor
<br>
<input name="autor" type="text" id="autor" value="<?=$HTTP_SESSION_VARS['u_user'];?>" size="45">
<br>
Titel<br>

  <input name="titel" type="text" id="titel" size="45">
  <br>
Kommentar <br>
  <textarea name="text" style="width:100%" rows="10" id="text"></textarea>
  <br>
  <input type="submit" class="button" value="eintragen">
  <input type="reset" class="button" value="zurücksetzen">
  <input name="id" type="hidden" id="id" value="<?=$_REQUEST['newsid'];?>">
  <input name="newstitel" type="hidden" id="newstitel" value="<?=$net;?>">
  <input name="send" type="hidden" id="send" value="1">
</form>
<!-- FORM --></td>
  </tr>
</table>
<? } ?>
<!--- GANZE NEWS --->
<?
if ($HTTP_SESSION_VARS[u_gid] == 1) {
	if($_REQUEST['act']=="edit") {
	$sql2 =& new MySQLq();
	$sql2->Query("SELECT * FROM " . $sql_prefix . "newsintern WHERE id = '$_REQUEST[newsid]'");
	$row2 = $sql2->FetchRow();
	
	if($_REQUEST['send']=="1")
	{
	$sql =& new MySQLq();
	$sql->Query("UPDATE " . $sql_prefix . "newsintern SET autor='$_REQUEST[autor]', status='$_REQUEST[status]',wichtigkeit='$_REQUEST[wichtigkeit]', titel='".addslashes($_REQUEST['titel'])."', text='".nl2br(htmlspecialchars(addslashes($_REQUEST['text'])))."' WHERE id = '$_REQUEST[id]'");
	$rel = $_REQUEST['rel'];
	
	eLog("user", "$_SESSION[u_user]  bearbeitet News ($_REQUEST[titel])");	
	header("location:$rel");
	}
	?>
	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
        <td align="left" valign="top" class="boxstandart"><b><a name="comment"></a>News bearbeiten </b><br>
            <br>
            <!-- FORM -->
            <form name="form1" method="post" action="">
        Status<br>
          <select name="status" id="status">
          <option value="zu erledigen" <? if($row2->status=="zu erledigen"){echo "selected";}?></option>zu erledigen</option>
		  <option value="in Arbeit" <? if($row2->status=="in Arbeit"){echo "selected";}?>>in Arbeit</option>
		  <option value="erledigt" <? if($row2->status=="erledigt"){echo "selected";}?>>erledigt</option>
        </select>
        <br>
        <br>
        Wichtigkeit<br>
        <select name="wichtigkeit" id="wichtigkeit">
		 <option value="niedrig" <? if($row2->wichtigkeit=="niedrig"){echo "selected";}?></option>niedrig</option>
		 <option value="wichtig" <? if($row2->wichtigkeit=="wichtig"){echo "selected";}?></option>wichtig</option>
		 <option value="hoch" <? if($row2->wichtigkeit=="hoch"){echo "selected";}?></option>hoch</option>
        </select>
        <br>
        <br>
        Autor
        <br>
        <input name="autor" type="text" id="autor" value="<?=$HTTP_SESSION_VARS['u_user'];?>" size="45">
        <br>
        Titel<br>
        <input name="titel" type="text" id="titel" value="<?=stripslashes($row2->titel);?>" size="45">
        <br>
        News<br>
        <textarea name="text" style="width:100%" rows="15" id="text"><?=stripslashes(eregi_replace("<br([a-zA-Z0-9\_\\ \/]*)>", "", $row2->text));echo"\n\nBearbeitet von $_SESSION[u_user]  am ".date("d.m.Y H.i",time())."";?></textarea>
        <br>
        <input name="Senden" type="submit" class="button" value="ändern">
        <input type="reset" class="button" value="zur&uuml;cksetzen">
        <input name="id" type="hidden" id="id" value="<?=$_REQUEST['newsid'];?>">
        <input name="rel" type="hidden" id="rel" value="<?=$_SERVER['HTTP_REFERER'];?>">
        <input name="send" type="hidden" id="send" value="1">
            </form>                          <!-- FORM --></td>
      </tr>
    </table>
	<?
	}

if($_REQUEST['act']=="new") {

if($_REQUEST['send']=="1")
	{
	$sql =& new MySQLq();
	$sql->Query("INSERT INTO " . $sql_prefix . "newsintern (id,datum,titel,text,wichtigkeit,status,autor) VALUES ('','".time()."','".addslashes($_REQUEST['titel'])."','".nl2br(htmlspecialchars(addslashes($_REQUEST['text'])))."','$_REQUEST[wichtigkeit]','$_REQUEST[status]','$_REQUEST[autor]') ");
	
	eLog("user", "$_SESSION[u_user]  schreibt eine News ($_REQUEST[titel])");	
	header("location:newsintern.php");
	}
?>
   <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

 <tr>
        <td align="left" valign="top" class="boxstandart"><b><a name="comment"></a>News schreiben </b><br>
            <br>
            <!-- FORM -->
            <form name="form1" method="post" action="">
        Status<br>
        <select name="status" id="status">
          <option value="zu erledigen" <? if($row2->status=="zu erledigen"){echo "selected";}?>>zu erledigen</option>
          <option value="in Arbeit" <? if($row2->status=="in Arbeit"){echo "selected";}?>>in Arbeit</option>
          <option value="erledigt" <? if($row2->status=="erledigt"){echo "selected";}?>>erledigt</option>
        </select>
        <br>
        <br>
        Wichtigkeit<br>
        <select name="wichtigkeit" id="wichtigkeit">
          <option value="niedrig" <? if($row2->wichtigkeit=="niedrig"){echo "selected";}?>>niedrig</option>
          <option value="wichtig" <? if($row2->wichtigkeit=="wichtig"){echo "selected";}?>>wichtig</option>
          <option value="hoch" <? if($row2->wichtigkeit=="hoch"){echo "selected";}?>>hoch</option>
          </select>
        <br>
        <br>
        Autor
        <br>
        <input name="autor" type="text" id="autor" value="<?=$HTTP_SESSION_VARS['u_user'];?>" size="45">
        <br>
        Titel<br>
        <input name="titel" type="text" id="titel" value="<?=stripslashes($row2->titel);?>" size="45">
        <br>
        News<br>
        <textarea name="text" style="width:100%" rows="15" id="text"><?=stripslashes(eregi_replace("<br([a-zA-Z0-9\_\\ \/]*)>", "", $row2->text));?></textarea>
        <br>
        <input name="Senden" type="submit" class="button" value="News eintragen">
        <input type="reset" class="button" value="zur&uuml;cksetzen">
        <input name="send" type="hidden" id="send" value="1">
            </form>
            <!-- FORM --></td>
      </tr>
    </table>
<? }
if($_REQUEST['act']=="del") {
$sql =& new MySQLq();
$sql->Query("DELETE FROM " . $sql_prefix . "newsintern WHERE id='$_REQUEST[newsid]'");
eLog("user", "$_SESSION[u_user]  löscht News ($_REQUEST[titel])");	

header("location:newsintern.php");

}
}
?>
