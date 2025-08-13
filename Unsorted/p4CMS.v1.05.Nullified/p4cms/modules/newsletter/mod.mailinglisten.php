<?PHP
ob_start();
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_newsletter']=="no") {
 	$msg = "<center>Ihre Gruppe hat keine Berechtigung, diese Seite zu betreten.</center>";
	MsgBox($msg);
	exit;
 }
 

 
 
 if ($_REQUEST['action']=="add") {
 	$titel = $_REQUEST['titel'];
 	$sql =& new MySQLq();
 	$sql->Query("INSERT INTO " . $sql_prefix . "mailinglisten(titel) VALUES ('$titel')");
 	$sql->Close();
 }
 
 if ($_REQUEST['action']=="deluser") {
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "listsubscribers WHERE id='$_REQUEST[id]'");
 	$sql->Close();
 	$_REQUEST['action'] = "expand";
 	$_REQUEST['id'] = $_REQUEST['expand'];
 }
 
 if ($_REQUEST['action']=="del") {
 	$sql =& new MySQLQ();
 	$sql->Query("DELETE FROM " . $sql_prefix . "mailinglisten WHERE id='$_REQUEST[id]'");
 	$sql->Close();
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "listsubscribers WHERE liste='$_REQUEST[id]'");
 	$sql->Close();
 }
?>
<html>
<head>
<link rel="stylesheet" href="/p4cms/style/style.css">
<? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<script language="javascript">
<!--
 function DelList(id, titel) {
 	if (window.confirm('Wollen Sie die Mailingliste "' + titel + '" wirklich komplett löschen?')) {
 		document.location.href='module.php?module=newsletter&page=mod.mailinglisten.php&action=del&id=' + id + '&d4sess=<?=$sessid;?>';
 	}
 }
//-->
</script>
<?
if($_REQUEST['modmode']=="edituser"){
  if ($_REQUEST['action']=="bearbeiten") {
  if($_REQUEST['email']!="" && $_REQUEST['name']!=""){
 	$titel = $_REQUEST['titel'];
 	$sql =& new MySQLq();
 	$sql->Query("UPDATE " . $sql_prefix . "listsubscribers SET name='$_REQUEST[name]', email='".ltrim(rtrim($_REQUEST['email']))."' WHERE id='$_REQUEST[uid]'");
 	$sql->Close();
	}
	header("location:$_REQUEST[jumpto]");
 }
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE id='$_REQUEST[id]'");
 $row = $sql->FetchRow();

 ?>

      <form name="form1" method="post" action="">
       <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

          <tr bgcolor="#EAEBEE">
            <td colspan="2"><b>Abo bearbeiten </b></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td width="10%">Name</td>
            <td><input name="email" type="text" id="email" value="<?=$row->email;?>" size="45"></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td>E-Mail</td>
            <td><input name="name" type="text" id="name" value="<?=$row->name;?>" size="45"></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" class="button" value="bearbeiten">              <input name="modmode" type="hidden" id="modmode" value="edituser">              <input name="uid" type="hidden" id="uid" value="<?=$_REQUEST['id'];?>">              
            <input name="jumpto" type="hidden" id="jumpto" value="<?=$_SERVER['HTTP_REFERER'];?>">              <input name="action" type="hidden" id="action" value="bearbeiten">
			<? if($_REQUEST['action']=="bearbeiten"){ ?>
			<input onClick="javascript:location.href = ['module.php?module=newsletter&page=mod.mailinglisten.php&d4sess=<?=$d4sess;?>&action=expand&id=<?=$_REQUEST['eid'];?>'];" name="Submit" type="button" class="button" value="<< zurück zur Übersicht">			<? } ?>
			</td>
          </tr>
        </table>
      </form>
<?
}else{ ?>


                <form style="display:inline;" method="post" action="modules/newsletter/code.php?action=multi" target="codewindow" onSubmit="window.open('', 'codewindow', 'width=600,height=400,top=0,left=0');">
               <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
  <td height="20" colspan="4" class="boxheader"><b>Mailinglisten</b></td>
  </tr>
<tr>
                <td height="20" class="boxheader">&nbsp; </td>
                <td height="20" class="boxheader">&nbsp;Titel</td>
                <td height="20" class="boxheader"><div align="center">Abonenten</div></td>
                <td height="17" class="boxheader" alt="Abonenten der Liste anzeigen"><div align="center">&nbsp;Aktionen</div></td>
                </tr>
                <?
                $sql =& new MySQLq();
                $sql->Query("SELECT * FROM " . $sql_prefix . "mailinglisten ORDER BY titel ASC");
                while ($row = $sql->FetchRow()) {
                	?>
                	<tr bgcolor="#FAFAFB">
          			<td height="17">
          			  <input type="checkbox" name="l<?=$row->id;?>"></td> 
                	<td height="17">&nbsp;<?=stripslashes($row->titel);?></td>
                	<td height="17">
                	  <div align="center">
                	    <?
                	$sql2 =& new MySQLq();
                	$sql2->Query("SELECT id FROM " . $sql_prefix . "listsubscribers WHERE liste='$row->id'");
                	echo $sql2->RowCount();
                	$sql2->Close();
                	?>
                	  </div>
                	</td>
                	<td height="17">
                	  <div align="center"><a href="module.php?module=newsletter&page=mod.mailinglisten.php&d4sess=<?=$d4sess;?>&action=expand&id=<?=$row->id;?>"><img src="gfx/listshow.gif" border="0" alt="Abonenten anzeigen"></a> <a href="#" onClick="DelList('<?=$row->id;?>','<?=$row->titel;?>');"><img src="gfx/del.gif" border="0" alt="L&ouml;schen"></a> <a href="#" onClick="window.open('modules/newsletter/code.php?id=<?=$row->id;?>', 'codefor<?=$row->id;?>', 'width=600,height=400,top=0,left=0');"><img src="gfx/code.png" border="0" alt="Einbindungs-Code anzeigen"></a></div>
                	</td>
               	 </tr>
                	<?
                	if ($_REQUEST['action']=="expand" && $_REQUEST['id']==$row->id) {
                		$perpage = 50;
                		
                		if (!isset($_REQUEST['apage'])) {
                			$apage = 1;
                			$_REQUEST['apage'] = 1;
                		} else {
                			$apage = $_REQUEST['apage'];
                		}
                		
                		$sql2 =& new MySQLq();
                		$sql2->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE liste='$row->id' ORDER BY email ASC");
                		$gesamt = $sql2->RowCount();
                		$sql2->Close();
                		
                		$start = ($apage * $perpage) - $perpage;
                		
                		$sql2 =& new MySQLq();
                		$sql2->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE liste='$row->id' ORDER BY email ASC LIMIT $start,$perpage");
                		while ($row2 = $sql2->FetchRow()) {
                			?>
                			<tr>
                			<td height="17" colspan="4" class="boxstandart">
                			<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
<tr>
       			  <td width="25%" height="17" bgcolor="#FAFAFB"><?=$row2->email;?>                 			  </td>
       			  <td width="25%" height="17" bgcolor="#EAEBEE"><?=$row2->name;?>                 			  </td>
                			<td width="5%" height="17" nowrap bgcolor="#FAFAFB">
                			  <div align="center">
                			    <?=date("d.m.Y", $row2->datum);?>
                			  </div>
                			</td>
<td width="5%" height="17" bgcolor="#EAEBEE">
       			    <div align="center">
                			    <?
                			if ($row2->art=="html") {
                				echo "HTML";
                			} else {
                				echo "Text";
                			} ?>
       			    </div>
		    </td>
                			<td width="5%" height="17" nowrap bgcolor="#FAFAFB">
                			  <div align="center"><a href="module.php?module=newsletter&page=mod.mailinglisten.php&action=deluser&d4sess=<?=$sessid;?>&id=<?=$row2->id;?>&expand=<?=$_REQUEST['id'];?>"><img src="gfx/del.gif" alt="" border="0" align="absmiddle"></a><a href="module.php?module=newsletter&page=mod.mailinglisten.php&modmode=edituser&d4sess=<?=$sessid;?>&id=<?=$row2->id;?>&eid=<?=$row->id;?>"><img src="gfx/code.png" alt="bearbeiten" border="0" align="absmiddle"></a> </div>
       			  </td>
                			</tr>
                			</table>                			</td>
                			</tr>
                			<?
                		}
                		$sql2->Close();
                		?>
                			<tr bgcolor="#EAEBEE">
                			<td height="17" colspan="4">
                			  <table width="100%" border="0" cellpadding="0" cellspacing="2" cellpacing="1">
                			<tr>
                			<td height="17" width="95%">&nbsp;Seite: <?
								for ($i=1; $i<=ceil($gesamt / $perpage); $i++) {
									echo "[";
									
									if ($_REQUEST['apage']!=$i) {
										echo "<a href=\"module.php?module=newsletter&page=mod.mailinglisten.php&d4sess=$sessid&action=expand&id=$_REQUEST[id]&apage=$i\">$i</a>";
									} else {
										echo $i;
									} 
									
									echo "] ";	
								}
                			?></td>
                			</tr>
                			</table>
                			</td>
                			</tr>
                		<?
                	}
                }
                $sql->Close();
                ?>
                </table>
               <br>
                <input class="button" type="submit" value="Formularcode für markierte Listen zeigen">
                </form>
                <br><br><br>
                <center><form style="display:inline;" method="post"><input type="hidden" name="action" value="add">Neue Mailingliste anlegen: <input type="text" name="titel" value="Titel" size="32"> <input type="submit" class="button" value="Ok"></form></center>
               
<?
}
?>
