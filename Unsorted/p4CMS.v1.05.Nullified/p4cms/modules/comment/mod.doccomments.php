<? ob_start();
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

$ref = "module.php?module=comment&d4sess=$sessid&page=mod.doccomments.php&commid=$_REQUEST[commid]";
$mondnav = "comment";

if(isset($_REQUEST['commid']))
{
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE commid='$_REQUEST[commid]' order by id DESC");

if($_REQUEST['act']=="edit"){
if($_REQUEST['send']=="1"){
$sql2 =& new MySQLq();
$sql2->Query("UPDATE " . $sql_prefix . "kommentare SET titel='".addslashes($_REQUEST['titel'])."',email='".addslashes($_REQUEST['email'])."',name='".addslashes($_REQUEST['name'])."',text='".addslashes($_REQUEST['text'])."' WHERE id='$_REQUEST[id]'");
header("location:$ref");
}
$sql2 =& new MySQLq();
$sql2->Query("SELECT * FROM " . $sql_prefix . "kommentare WHERE id='$_REQUEST[id]'");
$row=$sql2->FetchRow();
?>

    <td class="boxstandart">    <form name="form1" method="post" action="">
      <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

        <tr bgcolor="#EAEBEE">
          <td height="17" colspan="2"><b>Kommentar bearbeiten </b></td>
        </tr>
        <tr bgcolor="#FAFAFB">
          <td width="20%" height="17" nowrap>
          <div align="left">Name</div></td>
          <td>
          <input name="name" type="text" id="name" value="<?=$row->name;?>" size="40"></td>
        </tr>
        <tr bgcolor="#FAFAFB">
          <td width="20%" height="17" nowrap>
          <div align="left">email</div></td>
          <td>
          <input name="email" type="text" id="email" value="<?=$row->email;?>" size="40"></td>
        </tr>
        <tr bgcolor="#FAFAFB">
          <td width="20%" height="17" nowrap>
          <div align="left">Titel</div></td>
          <td>
          <input name="titel" type="text" id="titel" value="<?=htmlspecialchars(stripslashes($row->titel));?>" size="40"></td>
        </tr>
        <tr bgcolor="#FAFAFB">
          <td height="17" nowrap>Text</td>
          <td>
		  <textarea name="text" cols="40" rows="5" id="text"><?=htmlspecialchars(stripslashes($row->text));?></textarea></td>
        </tr>
        <tr bgcolor="#FAFAFB">
          <td height="17" nowrap>&nbsp;</td>
          <td>
            <input name="send" type="hidden" id="send" value="1">
            <input name="id" type="hidden" id="id" value="<?=$_REQUEST['id'];?>">
          <input class="button" type="submit" name="Submit" value="ändern"></td>
        </tr>
      </table>
    </form>
<br>
<? }

if($_REQUEST['act']=="del"){
$sql2 =& new MySQLq();
$sql2->Query("DELETE FROM " . $sql_prefix . "kommentare WHERE id='$_REQUEST[id]'");
$sql2->Close();
}
?>


<link href="/p4cms/style/style.css" rel="stylesheet" type="text/css">


<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr bgcolor="#EAEBEE">
  <td height="17" colspan="2">
    <div align="left"><b>Kommentare</b></div>
  </td>
  </tr>
<tr>
<td height="17" class="boxheader"><b>Titel</b></td>
<td width="10%" nowrap class="boxheader">
  <div align="center"><b>Aktion</b></div>
</td>
</tr>
<?		
 while($row = $sql->FetchRow())
 {
 $sql2 =& new MySQLq();
 $sql2->Query("SELECT * FROM " . $sql_prefix . "kommentare where commid='$row->datei'");
 $anz = $sql2->NumRows();
 if($anz!="0"){$anz="<b>".$anz."</b>";}
?>

  <tr bgcolor="#FAFAFB">
    <td><?=stripslashes($row->titel);?></td>
    <td>
	<div align="center">
	<a href="module.php?module=comment&d4sess=<? echo($sessid); ?>&page=mod.doccomments.php&commid=<?=$_REQUEST['commid'];?>&act=del&id=<?=$row->id;?>"><img src="/p4cms/gfx/del.gif" alt="löschen" width="20" height="20" hspace="3" border="0"></a>
	<a href="module.php?module=comment&d4sess=<? echo($sessid); ?>&page=mod.doccomments.php&commid=<?=$_REQUEST['commid'];?>&act=edit&id=<?=$row->id;?>"><img src="/p4cms/gfx/edit.gif" alt="bearbeiten" border="0"></a></div>	
    </td>
  </tr>
  <? 

 }
?>
</table>


<?
}
?>

