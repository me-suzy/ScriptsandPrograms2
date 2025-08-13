<?
if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
	SessionError();
	exit;
}

if ($HTTP_SESSION_VARS[u_gid] == 1) {
	
	if (!isset($_REQUEST['dosave']) || $_REQUEST['dosave']=="no") {
 	?>
	<link rel="stylesheet" href="/p4cms/style/style.css">
 	<form name="suche" method="post" action="">
 	<?
 	if ($_REQUEST['action']=="edit") {
 		$id = $_REQUEST['id'];
 		$sql =& new MySQLq();
 		$sql->Query("SELECT * FROM " . $sql_prefix . "suchen WHERE id='$id'");
 		$row = $sql->FetchRow();
 		$sql->Close();
 		$_REQUEST['rubrik'] = $row->rubrik;
 		$_REQUEST['felder'] = array();
 		$felder = $row->felder;
 		$felder = explode(",", $felder);
 		$_REQUEST['felder'] = $felder;
 		$ed_titel = stripslashes($row->titel);
 		$ed_vorlage = $row->vorlage;
 		$ed_elem = stripslashes($row->elem);
 		?>
 		<input type="hidden" name="update" value="<?=$row->id;?>">
 		<?
 	} else {
 		$ed_titel = "";
 		$ed_vorlage = "";
 		$ed_elem = "";
 	}
	?>



                 <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
					
					<td width="150" bgcolor="#EAEBEE">Suchen in Rubrik:</td>
					<td bgcolor="#FAFAFB">
					<?
					if (isset($_REQUEST['rubrik'])) {
						$rubid = $_REQUEST['rubrik'];
						if ($rubid == 0) {
							$ttitel = "[ Ganze Seite ]";
							$ruba = "gs";
						} else {
							$sql =& new MySQLq();
							$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken WHERE id='$rubid'");
							$ruba = $sql->FetchRow();
							$sql->Close();
							$ttitel = stripslashes($ruba->titel);
						}
						echo $ttitel;
					} else {
					?>
					<select name="rubrik"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						echo "<option value=\"$row->id\">$row->titel</option>";
					}
					$sql->Close();
						?><option value="0">[ Ganze Seite ]</option></select> <input type="submit" value=" Weiter &gt;&gt; " />
					<?
					}
					?></td>
					<? if (isset($ruba)) {
						
						if ($rubid != 0) {
					?>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">Suchen in Feldern:</td>
					<td bgcolor="#FAFAFB">
					<select name="felder[]" multiple size="5" style="width:60%;"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$ruba->id'");
					while ($row = $sql->FetchRow()) {
						if (!isset($_REQUEST['felder'])) {
							$sel = " selected";
						} else {
							$sel = "";
							reset($_REQUEST['felder']);
							while(list($key,$val)=each($_REQUEST['felder'])) {
								if ($val==$row->id) {
									$sel = " selected";
								}
							}
						}
						echo "<option value=\"$row->id\"$sel>$row->titel</option>";
					}
						$sql->Close(); ?></select><input type="hidden" name="rubrik" value="<?=$_REQUEST['rubrik'];?>"><br />
						<? if (!isset($_REQUEST['felder'])) { ?><input type="submit" value=" Weiter &gt;&gt; " /><? } ?>
					</td>
					</tr>
					<?
						} else {
							$s_felder = "n";?><input type="hidden" name="rubrik" value="<?=$_REQUEST['rubrik'];?>"><?
							$_REQUEST['felder'] = "n";
						}
						
						if (isset($_REQUEST['felder']) || $rubid==0) {
							$s_felder = $_REQUEST['felder'];
							?>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Titel der Suche:</td>
					<td bgcolor="#FAFAFB"><input type="text" size="30" style="width:100%;" name="stitel" value="<?=$ed_titel;?>" /></td>
					</tr>
					<tr>
					<td width="150" bgcolor="#EAEBEE">Vorlage:</td>
					<td bgcolor="#FAFAFB">
					<select name="vorlage"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "vorlagen ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						if ($row->id == $ed_vorlage) {
							$sl = " selected";
						} else {
							$sl = "";
						}
						echo "<option value=\"$row->id\"$sl>$row->titel</option>";
					}
					$sql->Close();
						?></select>
					</td>
					</tr>
							<?
						}
						
					}
					?>
					</tr>
					</table>
	

<? if (isset($s_felder)) {
?>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

     <tr> 
                <td valign="top" bgcolor="#FAFAFB">
                  
					<?
					CreateEditor("100%","300",$ed_elem,"elemtext");
					?>
       </td>
  </tr>
    </table>
<br>


<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


                    <tr> 
                      <td height="17" bgcolor="#EAEBEE">&nbsp;Variable</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;Beschreibung</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="17" bgcolor="#EAEBEE">&nbsp;{LINK}</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;<font color="#dddddd"></font>
                        URL zum gefundenen Dokument</td>
                      <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.elemtext,'{LINK}');">Einfügen</a></td>
                    </tr>
                    <tr>
                      <td height="17" bgcolor="#EAEBEE">&nbsp;{FUND}</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;
                        Fundstelle</td>
                      <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.elemtext,'{FUND}');">Einfügen</a></td>
                    </tr>
                    <?
                    if ($rubid==0) {
	?>
                    <tr>
                      <td height="17" bgcolor="#EAEBEE">&nbsp;{TITEL}</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;
                        Titel des gefunden Dokumentes</td>
                      <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.elemtext,'{TITEL}');">Einfügen</a></td>
                    </tr>
	<?
                    } else {
                    	reset ($s_felder);
                    	while(list($key,$val)=each($s_felder)) {
                    		$sql =& new MySQLq();
                    		$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE id='$val'");
                    		while ($row = $sql->FetchRow()) {
					?>
                    <tr>
                      <td height="17" bgcolor="#EAEBEE">&nbsp;{RUB:<? echo($row->id); ?>}</td>
                      <td height="17" bgcolor="#FAFAFB">&nbsp;<font color="#dddddd">(RUBRIK)</font> 
                        <? echo(stripslashes($row->titel)); ?></td>
                      <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.elemtext,'{RUB:<? echo($row->id); ?>}');">Einfügen</a></td>
                    </tr>
                    <?
                    		}
                    		$sql->Close();
                    	}
                    }
?>
    </table>               
			
			
			<input type="hidden" name="dosave" value="yes" />
			<br>
			<input type="submit" class="button" value="speichern>>">
			<?
} ?>
	<?
	}
	
	if ($_REQUEST['dosave']=="yes") {
		
		if ($_REQUEST['rubrik']!=0) {
			$lfelder = "";
			reset($_REQUEST['felder']);
			while (list($key,$val) = each($_REQUEST['felder'])) {
				$lfelder .= "," . $val;
			}
			$lfelder = substr($lfelder, 1);
		} else {
			$lfelder = "n";
		}
		
		if (isset($_REQUEST['update'])) {
			$query = "UPDATE " . $sql_prefix . "suchen SET rubrik='$_REQUEST[rubrik]', felder='$lfelder', titel='$_REQUEST[stitel]', vorlage='$_REQUEST[vorlage]', elem='$_REQUEST[elemtext]' WHERE id='$_REQUEST[update]'";
			
			$sql =& new MySQLq();
			$sql->Query($query);
			$sid = $_REQUEST['update'];
			$sql->Close();
		} else {
			$query = "INSERT INTO " . $sql_prefix . "suchen(rubrik,felder,titel,vorlage,elem) VALUES('$_REQUEST[rubrik]','$lfelder','$_REQUEST[stitel]','$_REQUEST[vorlage]','$_REQUEST[elemtext]')";
			
			$sql =& new MySQLq();
			$sql->Query($query);
			$sid = $sql->IId();
			$sql->Close();
		}
		?>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
       <tr>
    <td align="left" valign="top" bgcolor="#FAFAFB">
				Die Suche wurde gespeichert und steht
				ab sofort zur Verf&uuml;gung.<br>
				Die Suche kann z.B. &uuml;ber folgendes Formular eingebunden werden:<br>
				<br />
	  <textarea cols="80" rows="8"><?
				$formular  = "<form style=\"display:inline;\" action=\"/suche/query.php\" method=\"post\">\n";
				$formular .= "<input type=\"hidden\" name=\"id\" value=\"$sid\" />\n";
				$formular .= "Suchbegriff: <input type=\"text\" name=\"query\" size=\"18\" /> <input type=\"submit\" value=\"Suchen\" />\n";
				$formular .= "</form>";
				echo (htmlentities($formular));
				?></textarea>
</td>
            </tr>
    </table>
		<?
	}
} else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
}
?>