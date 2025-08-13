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
 	$sql->Query("DELETE FROM " . $sql_prefix . "redakteure WHERE id='" . $_REQUEST[id] . "'");
 	$sql->Close();
 	$_REQUEST[action] = "showusers";
    eLog("user", "$_SESSION[u_user] löscht einen Benutzer");
 }
 
 if ($_REQUEST[action]=="adduser") {
 	$query  = "INSERT INTO " . $sql_prefix . "redakteure(username,passwort,name,email,gruppe) VALUES (";
 	$query .= "'" . $_REQUEST[benutzername] . "', '" . $_REQUEST[pass] . "', '" . $_REQUEST[realname] . "', '" . $_REQUEST[email] . "', '" . $_REQUEST[gruppe] . "'";
 	$query .= ")";
 	$sql =& new MySQLq();
 	$sql->Query($query);
 	$sql->Close();
    eLog("user", "$_SESSION[u_user] erstellt einen Benutzer");
 }
 
 if ($_REQUEST[action]=="edituser") {
 	$query  = "UPDATE " . $sql_prefix . "redakteure SET ";
 	$query .= "passwort='" . $_REQUEST[pass] . "', name='" . $_REQUEST[realname] . "', email='" . $_REQUEST[email] . "', gruppe='" . $_REQUEST[gruppe] . "' ";
 	$query .= "WHERE id='" . $_REQUEST[id] . "'";
 	$sql =& new MySQLq();
 	$sql->Query($query);
 	$sql->Close();
    eLog("user", "$_SESSION[u_user] editiert einen Benutzer");
 }
 
 if ($_REQUEST[action]=="showusers") {
 	 	?>
 	<html>
	<head>
 		<title>Benutzerverwaltung</title>
		<link rel="stylesheet" href="style/style.css">
 		<? StyleSheet(); ?>  
	</head>
 	<body  class="boxstandart" topmargin="0" leftmargin="0"> 
 	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
           <tr> 
                      <td height="17" class="boxheader">&nbsp;Benutzername</td>
                      <td height="17" class="boxheader">&nbsp;Name</td>
                      <td height="17" class="boxheader">&nbsp;E-Mail</td>
					  <td height="17" class="boxheader">&nbsp;Gruppe</td>
					  <td height="17" class="boxheader">&nbsp;</td>
	  </tr>
 	<?
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "redakteure ORDER BY username ASC");
	while ($row = $sql->FetchRow()) {
		?>
		            <tr bgcolor="#FAFAFB"> 
                      <td height="17">&nbsp;<? echo(stripslashes($row->username)); ?></td>
                      <td height="17">&nbsp;<? echo(stripslashes($row->name)); ?></td>
                      <td height="17">&nbsp;<? echo(stripslashes($row->email)); ?></td>
                      <td height="17">&nbsp;<? $sql2 =& new MySQLq();
                      $sql2->Query("SELECT * FROM " . $sql_prefix . "gruppen WHERE id='$row->gruppe'");
                      $gruppe = $sql2->FetchRow();
                      $gtitel = $gruppe->titel;
                      echo "$gtitel (#$row->gruppe)";
                      $sql2->Close(); ?></td>
                      <td height="17" align="center"><? if($row->username!="admin") { ?><a href="javascript:confirmLink('benutzer.php?d4sess=<? echo($sessid); ?>&action=delete&id=<? echo($row->id); ?>', 'Wollen Sie den Benutzer <? echo($row->username); ?> wirklick löschen?');">Löschen</a> - <? } ?><a href="benutzer.php?d4sess=<? echo($sessid); ?>&action=edit&id=<? echo($row->id); ?>" target="inhalt">Editieren</a></td>
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
<link rel="stylesheet" href="style/style.css">
</head>
<body class="struktur" background="gfx/login/bgbody.gif">
		<?
		if ($_REQUEST[action]=="edit") {
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "redakteure WHERE id='" . $_REQUEST[id] . "'");
		$row = $sql->FetchRow();
		$sql->Close();
		}
		?>
<form name="uform" onSubmit="return PasswortVergleich();" action="benutzer.php?d4sess=<? echo($sessid); ?>&action=<? if($_REQUEST[action]=="edit") { echo "edituser&id=" . $_REQUEST[id]; } else { echo "adduser"; } ?>" method="post">
					
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr bgcolor="#EAEBEE">
   <td colspan="2"><b>Benutzer</b></td>
 </tr>
 <tr bgcolor="#FAFAFB">
   <td colspan="2">Hier k&ouml;nnen sie alle Administratoren und Redakteure anlegen, Rechte zuweisen und l&ouml;schen, die mit diesem System arbeiten d&uuml;rfen.</td>
   </tr>
 <tr>
          <td width="20%" bgcolor="#EAEBEE">Benutzername:</td>
          <td bgcolor="#FAFAFB">
          <input type="text" name="benutzername" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->username\" readonly "); } ?> style="width:60%;"></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">Passwort:</td>
          <td bgcolor="#FAFAFB">
          <input type="password" name="pass" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->passwort\" "); } ?> style="width:30%;"></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">Wiederholen:</td>
          <td bgcolor="#FAFAFB">
          <input type="password" name="retype" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->passwort\" "); } ?> style="width:30%;"></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">Name:</td>
          <td bgcolor="#FAFAFB">
          <input type="text" name="realname" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->name\" "); } ?> style="width:60%;"></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">E-Mail:</td>
          <td bgcolor="#FAFAFB">
          <input type="text" name="email" <? if($_REQUEST[action]=="edit") { echo ("value=\"$row->email\" "); } ?> style="width:60%;"></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">Gruppe:</td>
          <td bgcolor="#FAFAFB">
            <select name="gruppe">
              <?
						$row2 = $row;
						unset($row);
						$sql =& new MySQLq();
						$sql->Query("SELECT * FROM " . $sql_prefix . "gruppen ORDER BY titel ASC");
						while ($row = $sql->FetchRow()) {
							if ($row->id == $row2->id) { $sel = " selected"; } else { $sel = ""; }
							echo "<option value=\"$row->id\"$sel>" . stripslashes($row->titel) . " (#$row->id)</option>";
						}
						$sql->Close();
						?>
          </select></td>
        </tr>
        <tr>
          <td bgcolor="#EAEBEE">&nbsp;</td>
          <td bgcolor="#FAFAFB">
		  <input class="button" name="submit" type="submit" value="Benutzer <? if ($_REQUEST[action]=="edit") { echo "&auml;ndern"; } else { echo "erstellen"; } ?>"></td>
        </tr>
      </table>
</form>
					<br>
                    
      

<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
<tr> 
                <td valign="top" height="300" class="boxstandart">
					<iframe width="100%" height="100%" src="benutzer.php?d4sess=<? echo($sessid); ?>&action=showusers" border="0" frameborder="no"></iframe>
					<br>
                    </td>
              </tr>
            </table>
      
</body>
</html>
