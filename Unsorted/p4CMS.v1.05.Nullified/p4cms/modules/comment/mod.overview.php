<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_dokumente']=="no") {
 	$msg = "<center>Ihre Gruppe hat keine Berechtigung, diese Seite zu betreten.</center>";
	MsgBox($msg);
	exit;
 }
 
$limitpage = 25;
$mondnav = "comment";

if(!isset($_REQUEST['commid']))
{
$sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "dokumente order by id DESC");
 $wieviele = $sql->NumRows();
 $sql->Close();

 
 if($_REQUEST['start']==""){$start=0;}
 if($_REQUEST['start']!=""){$start=$_REQUEST['start'];}
 $chk_start=$start;

 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "dokumente order by id DESC limit $start,$limitpage");
 $number = $sql->NumRows();	
?>


<link href="/p4cms/style/style.css" rel="stylesheet" type="text/css">


<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
  <td height="17" colspan="4"><div align="right">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><b>Kommentare</b></td>
        <td>
          <div align="right">
            <? if($wieviele > $limitpage){$nav="news";include("nextfor.php");}  ?>
          </div>
        </td>
      </tr>
    </table>
  </div>
  </td>
  </tr>
<tr>
<td height="17" class="boxheader"><b>Titel</b></td>
<td class="boxheader"><b>Dokument</b></td>
<td width="10%" class="boxheader"><div align="center"><b>Kommentare</b></div></td>
<td width="10%" class="boxheader"><div align="center"><b>Aktion</b></div></td>
</tr>
<?		
 while($row = $sql->FetchRow())
 {
 $sql2 =& new MySQLq();
 $sql2->Query("SELECT * FROM " . $sql_prefix . "kommentare where commid='$row->datei'");
 $anz = $sql2->NumRows();
 if($anz!="0"){$anz="<b>".$anz."</b>";}
 //if($anz!="0" || $anz!=""){
?>

  <tr bgcolor="#FAFAFB">
    <td bgcolor="#FAFAFB"><?=$row->titel;?></td>
    <td><a target="_blank" href="http://<?=$_SERVER['HTTP_HOST'].$row->datei?>"><?=$row->datei;?></a></td>
    <td>
      <div align="center"><?=$anz;?></div></td>
    <td>
      <div align="center"><!--<img src="/p4cms/gfx/del.gif" alt="alle Kommentare l&ouml;schen" width="20" height="20" hspace="3">-->
	<a href="#" onClick="window.open('module.php?module=comment&d4sess=<? echo($sessid); ?>&page=mod.doccomments.php&commid=<?=$row->datei;?>', 'filemanager', 'width=600,height=500,top=0,left=0,scrollbars=yes');">
	<img src="/p4cms/gfx/edit.gif" alt="Kommentare anzeigen" border="0"></a></div>
    </td>
  </tr>
  <? 
  //}
 }
?>
</table>


<?
}
?>

