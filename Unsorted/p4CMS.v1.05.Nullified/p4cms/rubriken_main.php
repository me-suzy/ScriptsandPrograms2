<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_vorlagen']=="no") {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
 
 if ($_REQUEST['action']=="del") {
 	$sql =& new MySQLq();
 	$sql->Query("SELECT datei,id FROM " . $sql_prefix . "dokumente WHERE rubrik='$_REQUEST[id]'");
 	while ($row = $sql->FetchRow()) {
 		@unlink(".." . $row->datei);
		@unlink(".." .  str_replace(".htm", "_print.htm", $row->datei));
		$sql4 =& new MySQLq();
		$sql4->Query("DELETE FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$row->id'");
		$sql4->Close();
 	}
 	$sql->Close();
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "dokumente WHERE rubrik='$_REQUEST[id]'");
 	$sql->Close();
  	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "abfragen WHERE rubrik='$_REQUEST[id]'");
 	$sql->Close();
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "rubriken WHERE id='$_REQUEST[id]'");
 	$sql->Close();
  	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$_REQUEST[id]'");
 	$sql->Close();
 	eLog("user", "$_SESSION[u_user] löscht Rubrik $_REQUEST[id]");
 	?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
 	<?
 }
?>
<html>
<head>
<link rel="stylesheet" href="style/style.css">
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<script language="javascript">
<!--
 function DelRub(id, titel, inh) {
 	if (window.confirm('Wollen Sie die Rubrik "' + titel + '" wirklich komplett löschen? Alle Dokumente (' + inh + ') dieser Rubrik sowie alle Abfragen die diese Rubrik nutzen gehen verloren!')) {
 		document.location.href='rubriken_main.php?action=del&id=' + id + '&d4sess=<?=$sessid;?>';
 	}
 }
//-->
</script>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr>
   <td height="17" colspan="3" class="boxheader"><b>Rubriken</b></td>
  </tr>
 <tr>
                <td height="17" class="boxheader">Titel</td>
                <td height="17" class="boxheader">
                  <div align="center">Enth. Dokumente</div>
                </td>
                <td height="17" class="boxheader">
                  <div align="center">Aktionen</div>
                </td>
                </tr>
                <?
                	$sql2 =& new MySQLq();
                	$sql2->Query("SELECT * FROM " . $sql_prefix . "rubriken ORDER BY id DESC");	
                	while ($row2 = $sql2->FetchRow()) {
                		$titel_g = stripslashes($row2->titel);
                		if (strlen($titel_g) > 30) {
                			$titel = substr($titel_g, 0, 27) . "...";
                		} else {
                			$titel = $titel_g;
                		}
                	    $sql3 =& new MySQLq();
                	    $sql3->Query("SELECT id FROM " . $sql_prefix . "dokumente WHERE rubrik='$row2->id'");
                	    $enthdok = $sql3->RowCount();
                	    $sql3->Close();
                		?>
                		<tr bgcolor="#FAFAFB">
                		<td height="17"><a title="<?=$titel_g;?>"><?=$titel;?></a></td>
                		<td height="17" width="125">
                		  <div align="center">
                		    <?=$enthdok;?>
                		  </div>
                		</td>
                		<td height="17" width="95">
                		  <div align="center"><a href="rubrik.php?mode=edit&id=<?=$row2->id;?>&d4sess=<?=$sessid;?>"><img src="gfx/edit.gif" alt="Bearbeiten" border="0" align="absmiddle"></a> <a href="javascript:DelRub('<?=$row2->id;?>','<?=$titel_g;?>','<?=$enthdok;?>');"><img src="gfx/del.gif" alt="L&ouml;schen" border="0" align="absmiddle"></a></div>
                		</td>
                		</tr>
                		<?
                	}
                	$sql2->Close();
                ?>
                </table>
   
