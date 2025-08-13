<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }

 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_redakteur']=="no") {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
 
 if ($_REQUEST[action]=="delete") {
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "gruppen WHERE id='" . $_REQUEST[id] . "'");
 	$sql->Close();
 	$_REQUEST[action] = "showgruppen";
    eLog("user", "$_SESSION[u_user] löscht Gruppe $_REQUEST[id]");
 }
 
 if ($_REQUEST['action']=="adduser" || $_REQUEST['action']=="edituser") {
 	if (isset($_REQUEST['m_redakteur'])) {
 		$m_redakteur = "yes";
 	} else {
 		$m_redakteur = "no";
 	}
 	if (isset($_REQUEST['m_vorlagen'])) {
 		$m_vorlagen = "yes";
 	} else {
 		$m_vorlagen = "no";
 	}
 	if (isset($_REQUEST['m_abfragen'])) {
 		$m_abfragen = "yes";
 	} else {
 		$m_abfragen = "no";
 	}
 	if (isset($_REQUEST['m_dokumente'])) {
 		$m_dokumente = "yes";
 	} else {
 		$m_dokumente = "no";
 	}
 	if (isset($_REQUEST['m_mediapool'])) {
 		$m_mediapool = "yes";
 	} else {
 		$m_mediapool = "no";
 	}
 	if (isset($_REQUEST['m_newsletter'])) {
 		$m_newsletter = "yes";
 	} else {
 		$m_newsletter = "no";
 	}
 }
 
 if ($_REQUEST[action]=="adduser") {
 	$query  = "INSERT INTO " . $sql_prefix . "gruppen(titel,m_redakteur,m_vorlagen,m_abfragen,m_dokumente,m_mediapool,m_newsletter) VALUES (";
 	$query .= "'" . $_REQUEST['titel'] . "', '$m_redakteur', '$m_vorlagen', '$m_abfragen', '$m_dokumente', '$m_mediapool', '$m_newsletter'";
	$query .= ")";
 	$sql =& new MySQLq();
 	$sql->Query($query);
 	$sql->Close();
    eLog("user", "$_SESSION[u_user] erstellt Gruppe $_REQUEST[titel]");
 }
 
 if ($_REQUEST[action]=="edituser") {
 	$query  = "UPDATE " . $sql_prefix . "gruppen SET ";
 	$query .= "titel='" . $_REQUEST['titel'] . "', m_redakteur='$m_redakteur', m_vorlagen='$m_vorlagen', m_abfragen='$m_abfragen', m_dokumente='$m_dokumente', m_mediapool='$m_mediapool', m_newsletter='$m_newsletter' WHERE id=" . $_REQUEST['id'];
 	$sql =& new MySQLq();
 	$sql->Query($query);
 	$sql->Close();
    eLog("user", "$_SESSION[u_user] editiert Gruppe $_REQUEST[titel]");
 }
 
 if ($_REQUEST[action]=="showgruppen") {
 	 	?>
<html>
<head>
<link rel="stylesheet" href="style/style.css">
<? StyleSheet(); ?>
</head>
<body  class="boxstandart" topmargin="0" leftmargin="0"> 
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr> 
    <td height="17" class="boxheader">&nbsp;Titel</td> 
    <td height="17" class="boxheader">&nbsp;Rechte</td> 
    <td height="17" class="boxheader">&nbsp;</td> 
  </tr> 
  <?
 	function Y1C($in) {
 		if ($in=="yes") {
 			return 1;
 		} else {
 			return 0;
 		}
 	}
 	
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "gruppen ORDER BY titel ASC");
	while ($row = $sql->FetchRow()) {
		?> 
  <tr bgcolor="#FAFAFB"> 
    <td height="17">&nbsp;<? echo(stripslashes($row->titel)); ?></td> 
    <td height="17">&nbsp;<? echo(y1c($row->m_redakteur) . ", " . y1c($row->m_vorlagen) . ", " . y1c($row->m_abfragen) . ", " . y1c($row->m_dokumente) . ", " . y1c($row->m_mediapool) . ", " . y1c($row->m_newsletter)); ?></td> 
    <td height="17" align="center">&nbsp;<? if($row->titel!="Administratoren") { ?> 
      <a href="javascript:confirmLink('gruppen.php?d4sess=<? echo($sessid); ?>&action=delete&id=<? echo($row->id); ?>', 'Wollen Sie die Gruppe <? echo($row->titel); ?> wirklich löschen?');">Löschen</a> - <a href="gruppen.php?d4sess=<? echo($sessid); ?>&action=edit&id=<? echo($row->id); ?>" target="inhalt">Editieren</a> 
      <? } ?></td> 
  </tr> 
  <?
	}
	$sql->Close();
	?> 
</table> 
</html>
<?
 	exit;
 }
?>
<html>
<head>
<? StyleSheet(); ?>
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif"> 
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
      <form name="uform" onSubmit="return PasswortVergleich();" action="gruppen.php?d4sess=<? echo($sessid); ?>&action=<? if($_REQUEST[action]=="edit") { echo "edituser&id=" . $_REQUEST[id]; } else { echo "adduser"; } ?>" method="post"> 
          <?
					if ($_REQUEST[action]=="edit") {
						$sql =& new MySQLq();
						$sql->Query("SELECT * FROM " . $sql_prefix . "gruppen WHERE id='" . $_REQUEST[id] . "'");
						$row = $sql->FetchRow();
						$sql->Close();
					}
					?> 
          <tr bgcolor="#EAEBEE">
            <td colspan="2"><b>Gruppen</b></td>
        </tr>
          <tr bgcolor="#FAFAFB">
            <td colspan="2">Hier k&ouml;nnen sie Benutzergruppen anlegen, bearbeiten und l&ouml;schen. Hier legen sie die Rechte fest, welche die verschiedenen Redakteure besitzen werden, die mit diesem System arbeiten d&uuml;rfen. </td>
        </tr>
          <tr> 
            <td width="35%" bgcolor="#EAEBEE">Titel:</td> 
            <td bgcolor="#FAFAFB">
            <input type="text" name="titel" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->titel\" "); } ?> style="width:60%;"></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">Redakteure/Gruppen anlegen/editieren:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_redakteur" <? if($_REQUEST[action]=="edit" && $row->m_redakteur == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">Vorlagen anlegen/editieren:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_vorlagen" <? if($_REQUEST[action]=="edit" && $row->m_vorlagen == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">Abfragen anlegen/editieren:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_abfragen" <? if($_REQUEST[action]=="edit" && $row->m_abfragen == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">Dokumente anlegen/editieren:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_dokumente" <? if($_REQUEST[action]=="edit" && $row->m_dokumente == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">In den Mediapool hochladen:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_mediapool" <? if($_REQUEST[action]=="edit" && $row->m_mediapool == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">Newsletter schreiben:</td> 
            <td bgcolor="#FAFAFB">
            <input type="checkbox" name="m_newsletter" <? if($_REQUEST[action]=="edit" && $row->m_newsletter == "yes") { echo ("checked "); } ?>></td> 
          </tr> 
          <tr> 
            <td bgcolor="#EAEBEE">&nbsp;</td> 
            <td bgcolor="#FAFAFB">
            <input class="button" type="submit" value="Gruppe <? if ($_REQUEST[action]=="edit") { echo "&auml;ndern"; } else { echo "erstellen"; } ?>"></td> 
          </tr> 
  </form> 
      </table> 
	
	

	  
      <br> 
     <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr> 
          <td valign="top" height="300" class="boxstandart"> <iframe width="100%" height="100%" src="gruppen.php?d4sess=<? echo($sessid); ?>&action=showgruppen" border="0" frameborder="no"></iframe> 
            <br> </td> 
        </tr> 
      </table> 
</table> 
</body>
</html>
