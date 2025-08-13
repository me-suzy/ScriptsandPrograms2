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
?>
<html>
<head>
<link rel="stylesheet" href="style/style.css">
<? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<form name="rubrik" method="post" action="rubrik.php"> 
  <input type="hidden" name="switch" value=""> 
  <?
if ($_REQUEST['switch']=="save") {
	$sql =& new MySQLq();
	$sql->Query("UPDATE " . $sql_prefix . "rubriken SET template='" . $_REQUEST['vorlageneditor'] . "' WHERE id='" . $_REQUEST['id'] . "'");
	$sql->Close;
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$_REQUEST[id]'");
	$row = $sql->FetchRow();
	$sql->Close();
	$vatext = stripslashes($row->template);
	$vaid = $row->stdvorlange;
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "vorlagen WHERE id='$vaid'");
	$row = $sql->FetchRow();
	$sql->Close();
	$sql =& new MySQLq();
	$sql->Query("UPDATE " . $sql_prefix . "dokumente SET published='no' WHERE rubrik='" . $_REQUEST['id'] . "'");
	$sql->Close();
	RenderPages();
	$vorschau = str_replace("{CONTENT}", $vatext, stripslashes($row->vorlage));
	echo $vorschau;
	eLog("user", "$_SESSION[u_user] speichert Rubrik $_REQUEST[id]");
}

if ($_REQUEST['mode']=="new" || $_REQUEST['mode']=="edit") {
	if ($_REQUEST['mode']=="edit") {
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='" . $_REQUEST['id'] . "'");
		$row = $sql->FetchRow();
		$sql->Close();
		$ed_titel = stripslashes($row->titel);
		$ed_vorlage = $row->stdvorlange;
		$ed_shema = $row->stdname;
		$ed_print = $row->printv;
		echo "<input type=\"hidden\" name=\"id\" value=\"$row->id\">";
	} else {
		$ed_titel = "";
		$ed_vorlage = "";
		$ed_shema = "/neuerubrik/{d},{m},{y},,{h},{i},{s},,{r}.htm";
		$ed_print = "yes";
	}
	?> 
  <script language="javascript">
	<!--
	function submCheck() {
		var ok='1';
		
		if (document.all.titel.value=='') {
			alert('Bitte geben Sie einen Titel ein!');
			ok='0';
			
		}
		if (document.all.shema.value=='') {
		} else {
			if (document.all.shema.value.substr(0,1)=='/') {
			} else {
				alert('Das Auto-Dateiname-Schema muss mit einem "/" anfangen.');
				ok='0';
				
			}
		}
		
		if (ok=='1') {
			document.forms['rubrik'].submit();
		}
	}
	//-->
	</script> 
  <!--
	<tr>
    <td valign="top" class="boxstandart"> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td bgcolor="#F6F6F6"> 
		  --> 
 <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


          <tr> 
            <td width="10%" nowrap bgcolor="#EAEBEE">Titel der Rubrik:</td> 
            <td bgcolor="#FAFAFB">
            <input type="text" size="62" name="titel" class="feld" style="width:99%;" value="<? echo($ed_titel); ?>"></td> 
          </tr> 
          <tr> 
            <td width="10%" nowrap bgcolor="#EAEBEE">Auto-Dateiname-Schema:</td> 
            <td bgcolor="#FAFAFB">
              <input type="text" size="62" name="shema" class="feld" style="width:90%;" value="<? echo($ed_shema); ?>"> 
            <input type="button" class="button" onClick="rubDlg();" value=" ... "></td> 
          </tr> 
          <tr> 
            <td width="10%" nowrap bgcolor="#EAEBEE">Vorlagen-Basis:</td> 
            <td bgcolor="#FAFAFB">
              <select class="feld" name="vbasis"> 
                <?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "vorlagen ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						if ($row->id == $ed_vorlage) {
							$sw = " selected";
						} else {
							$sw = "";
						}
						echo "<option value=\"$row->id\"$sw>" . stripslashes($row->titel) . "</option>\n";
					}
					$sql->Close();
					?> 
            </select></td> 
          </tr> 
          <tr> 
            <td width="10%" nowrap bgcolor="#EAEBEE">Druckversion:</td> 
            <td bgcolor="#FAFAFB">
              <input type="checkbox" name="makeprint"<? if($ed_print=="yes") { echo " checked"; } ?> /> 
            Automatisch generieren</td> 
          </tr>
          <tr>
            <td nowrap bgcolor="#EAEBEE">&nbsp;</td>
            <td bgcolor="#FAFAFB">
              <input type="button" class="button" value="weiter>>" onClick="document.forms['rubrik'].elements['switch'].value='bearbeiten';submCheck();">
            </td>
          </tr> 
  </table> 
    <?
}

if ($_REQUEST['switch']=="bearbeiten") {
	if (isset($_REQUEST['makeprint'])) {
		$printv = "yes";
	} else {
		$printv = "no";
	}
	if (!isset($_REQUEST['id']) || $_REQUEST['id'] == "") {
		$query = "INSERT INTO " . $sql_prefix . "rubriken(titel,stdvorlange,stdname,printv) VALUES ('" . $_REQUEST['titel'] . "', '" . $_REQUEST['vbasis'] . "', '" . $_REQUEST['shema'] . "','$printv')";
	    eLog("user", "$_SESSION[u_user] erstellt Rubrik $_REQUEST[titel]");
	} else {
		$ed_id = $_REQUEST['id'];
		if (isset($_REQUEST['titel']) && isset($_REQUEST['vbasis'])) {
		 	$query = "UPDATE " . $sql_prefix . "rubriken SET titel='" . $_REQUEST['titel'] . "', stdvorlange='" . $_REQUEST['vbasis'] . "', stdname='" . $_REQUEST['shema'] . "', printv='$printv' WHERE id='$ed_id'";
		} else {
			$query = "";
		}
	}
	if ($query == "") { } else {
		$sql =& new MySQLq();
		$sql->Query($query);
		if (!isset($ed_id)) {
			$ed_id = $sql->IId();
		}
		$sql->Close();
	}
	if ($_REQUEST['action']=="changefeld") {
		if (isset($_REQUEST['delete'])) {
			$query = "DELETE FROM " . $sql_prefix . "rubriken_felder WHERE id='" . $_REQUEST['feld'] . "'";
		} else {
			$query = "UPDATE " . $sql_prefix . "rubriken_felder SET titel='" . $_REQUEST['feldname'] . "', typ='" . strtolower($_REQUEST['typ']) . "', stdwert='" . $_REQUEST['stdwert'] . "' WHERE id='" . $_REQUEST['feld'] . "'";
		}
		$sql =& new MySQLq();
		$sql->Query($query);
		$sql->Close();
		$sql =& new MySQLq();
		$sql->Query("UPDATE " . $sql_prefix . "dokumente SET published='no' WHERE rubrik='$ed_id'");
		$sql->Close();
		RenderPages();
		eLog("user", "$_SESSION[u_user] ändert Feld $_REQUEST[feld]");
	}	
	if ($_REQUEST['action']=="addfeld") {
		$query = "INSERT INTO " . $sql_prefix . "rubriken_felder(titel,typ,rubrik,stdwert) VALUES('" . $_REQUEST['feldname'] . "', '" . strtolower($_REQUEST['typ']) . "', '$ed_id','" . $_REQUEST['stdwert'] . "')";
		$sql =& new MySQLq();
		$sql->Query($query);
		$fid = $sql->IId();
		$sql->Close();
		
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE rubrik='$ed_id'");
		while ($row = $sql->FetchRow()) {
			$sql2 =& new MySQLq();
			$sql2->Query("INSERT INTO " . $sql_prefix . "dokumente_felder(dokument,feld,inhalt) VALUES('$row->id','$fid','')");
			$sql2->Close();
		}
		$sql->Close();
		
		$sql =& new MySQLq();
		$sql->Query("UPDATE " . $sql_prefix . "dokumente SET published='no' WHERE rubrik='$ed_id'");
		$sql->Close();
		RenderPages();
		eLog("user", "$_SESSION[u_user] erstellt Feld in Rubrik $ed_id");
	}		
	?>
<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script> 
</form> 

<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

        <tr> 
          <td bgcolor="#EAEBEE"><b>Feldname</b></td> 
          <td bgcolor="#EAEBEE"><b>Standard-Wert</b></td> 
          <td bgcolor="#EAEBEE"><b>Feldtyp</b></td> 
          <td bgcolor="#EAEBEE"><b>&nbsp;</b></td> 
        </tr> 
        <?
                	$i = 0;
                	$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$ed_id'");
					while ($row = $sql->FetchRow()) {
						$i++;
						$felder2 = PFelder("<option value=\"%y\"%x>%s</option>\n", $row->typ);
						echo "<form name=\"form$i\" action=\"rubrik.php?d4sess=$sessid&action=changefeld&id=$ed_id\" method=\"post\" style=\"display:inline;\"><input type=\"hidden\" name=\"feld\" value=\"$row->id\"><input type=\"hidden\" name=\"switch\" value=\"bearbeiten\"><tr><td bgcolor=\"#FAFAFB\"><input type=\"text\" name=\"feldname\" value=\"" . stripslashes($row->titel) . "\" size=\"32\"></td><td align=\"left\" bgcolor=\"#FAFAFB\"><input type=\"text\" name=\"stdwert\" value=\"" . $row->stdwert . "\" size=\"20\"> <input class=button type=\"button\" value=\" ... \" onClick=\"stded('stdwert','form$i');\"></td><td align=\"left\" bgcolor=\"#FAFAFB\"><select name=\"typ\">$felder2</select></td><td align=\"right\" bgcolor=\"#FAFAFB\"><input class=button  type=\"submit\" value=\"Speichern\">&nbsp;&nbsp;<input class=button  type=\"submit\" name=\"delete\" value=\"Löschen\"></td></tr></form>";
					}
					$i++;
					$felder2 = PFelder("<option value=\"%y\"%x>%s</option>\n", $row->typ);
					echo "<form name=\"form$i\" action=\"rubrik.php?d4sess=$sessid&action=addfeld&switch=bearbeiten&id=$ed_id\" method=\"post\" style=\"display:inline;\"><tr><td bgcolor=\"#FAFAFB\"><input type=\"text\" value=\"\" size=\"32\" name=\"feldname\"></td><td align=\"left\" bgcolor=\"#FAFAFB\"><input type=\"text\" name=\"stdwert\" value=\"\" size=\"20\"> <input class=button  type=\"button\" value=\" ... \" onClick=\"stded('stdwert','form$i');\"></td><td align=\"left\" bgcolor=\"#FAFAFB\"><select name=\"typ\">$felder2</select></td><td align=\"right\" bgcolor=\"#FAFAFB\"><input class=button  type=\"submit\" value=\"Hinzuf&uuml;gen\"></td></tr></form>";
					?> 
</table>
<br> 
<input type="button" class="button" value="<<zurück" onClick="location.href='rubrik.php?mode=edit&id=<?=$ed_id;?>&d4sess=<?=$sessid;?>'">
<input type="button" class="button" value="weiter>>" onClick="document.forms['rubrik'].action='rubrik.php?d4sess=<?=$sessid;?>&id=<?=$ed_id;?>';document.forms['rubrik'].elements['switch'].value='style';document.forms['rubrik'].submit();">
<?
}

if ($_REQUEST['switch']=="style") {
	$ed_id = $_REQUEST['id'];
	?> 
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 
            <tr> 
              <td height="350" valign="top" bgcolor="#FAFAFB">
                  <?
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$ed_id'");
$row = $sql->FetchRow();
$sql->Close();
$printv = $row->printv;
$vatext = stripslashes($row->template);

CreateEditor("100%","350",$vatext,"vorlageneditor");
?> 
</td> 
</tr> 
</table>
<input type="hidden" name="text" value=""> 
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

      <tr> 
        <td height="17" class="boxheader">&nbsp;Variable</td> 
        <td height="17" class="boxheader">&nbsp;Beschreibung</td> 
        <td height="17" class="boxheader">&nbsp;</td> 
      </tr> 
      <?

					if ($printv=="yes") {
						?> 
    
	 
      <tr bgcolor="#FAFAFB"> 
        <td height="17">&nbsp;{PRINTURL}</td> 
        <td height="17">&nbsp;<font color="#dddddd">(RUBRIK)</font> URL zur Druckversion</td> 
        <td height="17" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{PRINTURL}');">Einf&uuml;gen</a></td> 
      </tr> 
      <?
	} if(file_exists("modules/comment/c.inc.php")){ ?>
	  <tr bgcolor="#FAFAFB">
        <td height="17">&nbsp;{p4:kommentar}</td>
        <td height="17" bgcolor="#FAFAFB">&nbsp;<font color="#dddddd">(RUBRIK)</font> Kommentarm&ouml;glichkeit</td>
        <td height="17" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{p4:kommentar}');">Einf&uuml;gen</a></td>
      </tr>
	<? } 

$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$ed_id' ORDER BY titel ASC");
while ($row = $sql->FetchRow()) {
					?> 
      <tr bgcolor="#FAFAFB"> 
        <td height="17">&nbsp;{RUB:<? echo($row->id); ?>}</td> 
        <td height="17">&nbsp;<font color="#dddddd">(RUBRIK)</font> <? echo(stripslashes($row->titel)); ?></td> 
        <td height="17" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{RUB:<? echo($row->id); ?>}');">Einf&uuml;gen</a></td> 
      </tr> 
      <?
}
$sql->Close();
?> 
</table> 
    <br>
    <input type="button" class="button" value="speichern" onClick="document.forms['rubrik'].action='rubrik.php?id=<?=$ed_id;?>&d4sess=<?=$sessid;?>&switch=save';document.forms['rubrik'].elements['switch'].value='save';document.forms['rubrik'].submit();">
    <br>
	
	<?
}
?>
