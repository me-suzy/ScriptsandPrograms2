<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	if ($_REQUEST['action']=="del") {
 		$sql =& new MySQLq();
 		$sql->Query("DELETE FROM " . $sql_prefix . "galerien WHERE id='$_REQUEST[id]'");
 		$sql->Close();
 		$sql =& new MySQLq();
 		$sql->Query("DELETE FROM " . $sql_prefix . "galerien_bilder WHERE gallerie='$_REQUEST[id]'");
 		$sql->Close();
 	}
 	?>
 <html>
<head>
<? StyleSheet(); ?>
<link rel="stylesheet" href="/p4cms/style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>

<script language="javascript">
<!--
 function DelGal(id, titel) {
 	if (window.confirm('Wollen Sie die Gallerie "' + titel + '" wirklich komplett löschen?')) {
 		document.location.href='module.php?module=galerie&page=mod.overview.php&action=del&id=' + id + '&d4sess=<?=$sessid;?>';
 	}
 }
//-->
</script>
 
 
                     <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
                       <tr bgcolor="#EAEBEE">
                         <td height="17" colspan="2" class="boxheader"><b>Galerie</b></td>
                       </tr>
                       <tr>
                         <td height="17" class="boxheader">Titel</td>
                         <td width="5%" height="17" nowrap class="boxheader" alt="Abonenten der Liste anzeigen">&nbsp;Aktionen</td>
                       </tr>
                       <?
                $sql =& new MySQLq();
                $sql->Query("SELECT titel,id FROM " . $sql_prefix . "galerien ORDER BY titel ASC");
                while ($row = $sql->FetchRow()) {
                	?>
                       <tr bgcolor="#FAFAFB">
                         <td height="17">
                           <?=stripslashes($row->titel);?>
                         </td>
                         <td height="17" nowrap>&nbsp;<a href="module.php?module=galerie&page=mod.edit.php&d4sess=<?=$d4sess;?>&action=edit&id=<?=$row->id;?>"><img src="/p4cms/gfx/edit.gif" alt="Bearbeiten" width="16" height="16" border="0" align="absmiddle"></a> <a href="#" onClick="window.open('modules/galerie/code.php?id=<?=$row->id;?>', 'galleriecodefor<?=$row->id;?>', 'width=600,height=200,top=0,left=0');"><img src="/p4cms/gfx/listshow.gif" alt="Einbindungs-Code anzeigen" width="16" height="16" border="0" align="absmiddle"></a> <a href="javascript:DelGal('<?=$row->id;?>','<?=$row->titel;?>');"><img src="/p4cms/gfx/del.gif" alt="L&ouml;schen" width="20" height="20" border="0" align="absmiddle"></a></td>
                       </tr>
                       <?
                }
                $sql->Close();
                ?>
                     </table>
                   
               <? 	
 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?>
