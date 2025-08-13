<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	if ($_REQUEST['action']=="del") {
 		$sql =& new MySQLq();
 		$sql->Query("DELETE FROM " . $sql_prefix . "suchen WHERE id='$_REQUEST[id]'");
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
 function DelSuche(id, titel) {
 	if (window.confirm('Wollen Sie die Suche "' + titel + '" wirklich komplett löschen?')) {
 		document.location.href='module.php?module=suche&page=mod.overview.php&action=del&id=' + id + '&d4sess=<?=$sessid;?>';
 	}
 }
//-->
</script>

        <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

   <tr>
     <td height="17" colspan="2" class="boxheader"><b>Suche</b></td>
     </tr>
   <tr>
                <td height="17" class="boxheader">Titel</td>
                <td width="100" height="17" class="boxheader" alt="Abonenten der Liste anzeigen">
                  <div align="center">Aktion</div>
                </td>
                </tr>
                <?
                $sql =& new MySQLq();
                $sql->Query("SELECT titel,id FROM " . $sql_prefix . "suchen ORDER BY titel ASC");
                while ($row = $sql->FetchRow()) {
                	?>
                	<tr bgcolor="#FAFAFB">
                	<td height="17"><?=stripslashes($row->titel);?></td>
					<td height="17">
					  <div align="center"><a href="module.php?module=suche&page=mod.create.php&d4sess=<?=$d4sess;?>&action=edit&id=<?=$row->id;?>"><img src="gfx/edit.gif" border="0" alt="Bearbeiten"></a> <a href="javascript:DelSuche('<?=$row->id;?>','<?=$row->titel;?>');"><img src="gfx/del.gif" border="0" alt="L&ouml;schen"></a></div>
					</td>
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