<?
 include("include/include.inc.php");

 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_dokumente']=="no") {
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
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<script language="javascript">
<!--
function CheckForm() {
	var error='0';
	
	if (document.all.filename.value == '') {
		alert('Bitte geben Sie einen Speicherort an!');
		error='1';
	}
	if (document.all.titel.value == '') {
		alert('Bitte geben Sie einen Titel an!');
		error='1';
	}
	
	if (error=='0') {
		document.forms['dokument'].submit();
	}
}
//-->
</script>
<form name="dokument" method="post" action="dokument.php">

<input type="hidden" name="switch" value="">
<input type="hidden" name="dfn" value="">
<input type="hidden" name="mode" value="save">
<?
if ($_REQUEST['mode']=="new" || $_REQUEST['mode']=="edit") {
	if ($_REQUEST['mode']=="edit") {
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE id='$_REQUEST[id]'");
		$doka = $sql->FetchRow();
		$sql->Close();
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$doka->rubrik'");
		$ruba = $sql->FetchRow();
		$sql->Close();
		$_REQUEST['rub'] = $ruba->id;
		$ed_titel = stripslashes($doka->titel);
		$ed_fn = stripslashes($doka->datei);
		$ed_ab = $doka->ablauf;
		echo "<input type=\"hidden\" name=\"doedit\" value=\"yes\">";
		echo "<input type=\"hidden\" name=\"doeditid\" value=\"$_REQUEST[id]\">";
	} else {
		$ed_titel = "";
		$ed_rubrik = "";
		$ed_fn = "";
		$ed_ab = "";
	}
	?>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

					<?
					if (!isset($_REQUEST['rub'])) {
						?>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Rubrik:</td>
					<td bgcolor="#FAFAFB">
					  <select class="feld" name="rub"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						if ($row->id == $ed_vorlage) {
							$sw = " selected";
						} else {
							$sw = "";
						}
						echo "<option value=\"$row->id\"$sw>" . stripslashes($row->titel) . "</option>\n";
					}
					$sql->Close();
					?></select> <input name="subm" type="submit" class="button"  onClick="document.all.mode.value='new';" value="weiter >>"></td>
					</tr>
						<?
					} else {
						
						?>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Rubrik:</td>
					<td bgcolor="#FAFAFB"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$_REQUEST[rub]'");
					$ruba = $sql->FetchRow();
					echo $ruba->titel;
					?>
					</td>
					</tr>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Titel des Dokumentes:</td>
					<td bgcolor="#FAFAFB">
					  <input type="text" size="62" name="titel" class="feld" style="width:99%;" value=""></td>
					</tr>
					<script language="javascript">
					<!--
					document.all.titel.value='<?=addslashes($ed_titel);?>';
					function checku() {
						if (document.all.autoname.checked == true) {
							document.all.bbut.disabled = true;
							document.all.filename.disabled = true;
							document.all.filename.value = '<?
							$autoname = $ruba->stdname;
							$autoname = str_replace("{y}", date("Y"), $autoname);
							$autoname = str_replace("{m}", date("m"), $autoname);
							$autoname = str_replace("{d}", date("d"), $autoname);
							$autoname = str_replace("{h}", date("H"), $autoname);
							$autoname = str_replace("{i}", date("i"), $autoname);
							$autoname = str_replace("{s}", date("s"), $autoname);
							$autoname = str_replace("{r}", mt_rand(100, 999), $autoname);
							$autoname = str_replace("'", "\\'", $autoname);
							echo $autoname;							
							?>';
						} else {
							document.all.bbut.disabled = false;
							document.all.filename.disabled = false;
						}
					}
					//-->
					</script>
					<?
					if (isset($doka)) {
						?>
						<input type="hidden" name="filename" value="<? echo($ed_fn); ?>">
						<?
					} else {
						?><tr>
					<td width="150" bgcolor="#EAEBEE">Speicherort:</td>
					<td bgcolor="#FAFAFB">
					  <input type="text" size="62" readonly name="filename" class="feld" style="width:40%;" value="<? echo($ed_fn); ?>"> <input name="bbut" type="button" class="button" onClick="SaveDialog('');" value="Durchsuchen"> 
					<input type="checkbox" name="autoname" onClick="checku();"> Automatische Benennung</td>
					</tr><?
					}
					?>
					<tr>
					<td width="150" bgcolor="#EAEBEE"><?
					if (isset($doka)) {
						echo "Update-Datum:";
					} else {
						echo "Publizierungs-Datum:";
					} ?></td>
					<td bgcolor="#FAFAFB">
					  <script type="text/javascript">
	<!--
		// Calendar callback. When a date is clicked on the calendar
		// this function is called so you can do as you want with it
		function calendarCallback(date, month, year)
		{
			date = date + '.' + month + '.' + year;
			document.all.datum.value = date;
		}
	// -->
	</script>
	<input type="text" name="datum" value="" class="feld">
	<script language="JavaScript" type="text/javascript">
    <!--
    	fooCalendar = new dynCalendar('fooCalendar', 'calendarCallback', 'gfx/kalender/');
    //-->
    </script> &nbsp;(Freilassen f&uuml;r sofortige<?
    if (isset($doka)) {
    	echo "s Update";
    } else {
    	echo " Publizierung";
    } ?>)</td>
					</tr>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Ablauf-Datum:</td>
					<td bgcolor="#FAFAFB">
					  <script type="text/javascript">
	<!--
		// Calendar callback. When a date is clicked on the calendar
		// this function is called so you can do as you want with it
		function calendar2Callback(date, month, year)
		{
			date = date + '.' + month + '.' + year;
			document.all.ablauf.value = date;
		}
	// -->
	</script>
	<input type="text" name="ablauf" value="<?=$ed_ab;?>" class="feld">
	<script language="JavaScript" type="text/javascript">
    <!--
    	fooCalendar2 = new dynCalendar('fooCalendar2', 'calendar2Callback', 'gfx/kalender/');
    //-->
    </script> &nbsp;(Freilassen f&uuml;r keinen Ablauf)</td>
					</tr>
					<?
					}
					?>	
  </table>


<?
 if (isset($ruba)) {
 	?>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


  <tr>
    <td bgcolor="#FAFAFB"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <table width="100%">
        <?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$ruba->id' ORDER BY id ASC");
					while ($row = $sql->FetchRow()) {
						?>
        <tr>
          <td width="10%" valign="top" nowrap><?=stripslashes(htmlspecialchars($row->titel));?>
            :&nbsp;&nbsp;&nbsp;</td>
          <td valign="top">
            <?
						if ($row->stdwert!="") {
							$std = str_replace("{d}", date("d"), $row->stdwert);
							$std = str_replace("{m}", date("m"), $std);
							$std = str_replace("{y}", date("Y"), $std);
							$std = str_replace("{h}", date("H"), $std);
							$std = str_replace("{i}", date("i"), $std);
							$std = str_replace("{s}", date("s"), $std);
							$std = str_replace("{u_name}", $HTTP_SESSION_VARS['u_name'], $std);
							$std = str_replace("{u_user}", $HTTP_SESSION_VARS['u_user'], $std);
							$std = stripslashes($std);
						} else {
							$std = "";
						}
						
						if (isset($doka)) {
							$sql2 =& new MySQLq();
							$sql2->Query("SELECT inhalt FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$doka->id' AND feld='$row->id'");
							$row2 = $sql2->FetchRow();
							$std = stripslashes($row2->inhalt);
						}
						
						if ($row->typ=="kurztext") {
							?>
            <input type="text" name="feld_<?=$row->id;?>" size="64" style="width:100%;" value="<?=htmlentities($std);?>">
            <?
						}
						if ($row->typ=="haupttext") {
							$ed =& new p4cmsEditor($std);
							$in = str_replace("<TBODY>", "\n", $row->id);
							$in = str_replace("</TBODY>", "\n", $row->id);
							$ed->CreateFCKeditor("feld_" . $row->id, "100%", "300");
							echo '<input name="v" type="button" class="button"  id="v" onClick="Vorschau2(\'feld_' . $row->id . '\');" value="Vorschau">';
						}
						if ($row->typ=="javascript") {
							?>
            <textarea name="feld_<?=$row->id;?>" cols="70" rows="8"><?=$std;?></textarea>
            <?
						}
						if (substr($row->typ,0,4)=="bild" || $row->typ=="video" || $row->typ=="flash") {
							?>
            <img  <?
							if ($row->typ=="video") {
								if ($std=="") {
									echo "dynsrc=\"gfx/nopic.gif\" ";
								} else {
									echo "dynsrc=\"media" . $std . "\" ";
								}
							} else {
								?>
								src="<?
							if ($std=="") {
								echo "gfx/nopic.gif";
							} else {
								echo "media" . $std;
							}?>"
								<?
							} ?>border="0" id="img_feld_<?=$row->id;?>"><br>
            <input type="text" name="feld_<?=$row->id;?>" readonly size="32" value="<?=$std;?>">
            <input name="button" type="button" class="button" onClick="MediaPool('<?=substr($row->typ,0,4);?>','feld_<?=$row->id;?>');" value="Media-Pool">
            <?						
						}
						?>
          </td>
        </tr>
        <?
					}
					$sql->Close();
					?>
      </table>
      </font></td>
  </tr>
</table>
	<br>
	<input type="button" onClick="document.forms['dokument'].elements['dfn'].value=document.forms['dokument'].elements['filename'].value;document.forms['dokument'].elements['switch'].value='<?=$ruba->id;?>';CheckForm();" class="button" value="speichern>>">
	<?
 }

} else {
	$dok_rid = $_REQUEST['switch'];
	$dok_fn = $_REQUEST['dfn'];
	$dok_titel = $_REQUEST['titel'];
	$dok_datum = $_REQUEST['datum'];
	if ($dok_datum==""){
		$dok_datum = time();
		$tm = "now";
	} else {
		$dok_splot = explode(".", $dok_datum);
		$dok_datum = mktime(0, 0, 0, $dok_splot[1], $dok_splot[0], $dok_splot[2]);
		unset ($dok_splot);
		$tm = "later";
	}
	
	if (isset($_REQUEST['doedit']) && $_REQUEST['doedit'] == "yes") {
		$dok_id = $_REQUEST['doeditid'];
		$sql =& new MySQLq();
		$qr  = "UPDATE " . $sql_prefix . "dokumente SET titel='$dok_titel', ablauf='$_REQUEST[ablauf]', pubdatum='$dok_datum', published='no', lastupdate='" . time() . "' WHERE id='$dok_id'";
		$sql->Query($qr);
		$sql->Close();
		
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$dok_rid' ORDER BY id ASC");
		while ($row = $sql->FetchRow()) {
			$df_id = $row->id;
			$df_inh = $_REQUEST['feld_' . $df_id];
			$qr = "UPDATE " . $sql_prefix . "dokumente_felder SET inhalt='$df_inh' WHERE dokument='$dok_id' AND feld='$df_id'";
			$sql2 =& new MySQLq();
			$sql2->Query($qr);
			$sql2->Close();
		}
		$sql->Close();	

		eLog("user", "$_SESSION[u_user]  editiert Dokument $_REQUEST[doeditid]");	
	} else {
	
		$sql =& new MySQLq();
		$qr  = "INSERT INTO " . $sql_prefix . "dokumente(titel,ablauf,datei,rubrik,datum,redakteur,pubdatum,published,lastupdate) VALUES ";
		$qr .= "('$dok_titel','$_REQUEST[ablauf]','$dok_fn','$dok_rid','" . time() . "','$HTTP_SESSION_VARS[u_id]','$dok_datum','no','" . time() . "')";
		$sql->Query($qr);
		$dok_id = $sql->IId();
		$sql->Close();
		
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$dok_rid' ORDER BY id ASC");
		while ($row = $sql->FetchRow()) {
			$df_id = $row->id;
			$df_inh = $_REQUEST['feld_' . $df_id];
			$qr = "INSERT INTO " . $sql_prefix . "dokumente_felder(dokument,feld,inhalt) VALUES('$dok_id','$df_id','$df_inh')";
			$sql2 =& new MySQLq();
			$sql2->Query($qr);
			$sql2->Close();
		}
		$sql->Close();
		
		eLog("user", "$_SESSION[u_user] erstellt ein Dokument ($dok_fn)");
	}
	RenderPages();
	
	?>
<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
<table width=100%  border="1" cellpadding="8" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


      <tr>
        <td class="boxstandart">Die Seite wurde gespeichert und
        <?
				if ($tm=="now") {
					echo "publiziert.<br><br><a href=\"..$dok_fn\" target=\"_blank\">Publizierte Seite ansehen</a>";
				} else {
					echo "wird am angegebenen Datum publiziert.";
				} ?>		</td>
      </tr>
  </table>
	<?
}
?>
</form>