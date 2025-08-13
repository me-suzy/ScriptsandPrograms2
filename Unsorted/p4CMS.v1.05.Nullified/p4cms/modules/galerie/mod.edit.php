<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	?>
 
                <?
                if ($_REQUEST['action']=="step2") {
                	if (!isset($_REQUEST['nid'])) {
                		$query  = "INSERT INTO " . $sql_prefix ."galerien(titel,prozeile,w,h,bgcolor) VALUES ";
                		$query .= "('$_REQUEST[titel]','$_REQUEST[prozeile]','$_REQUEST[w]','$_REQUEST[h]','$_REQUEST[bgcolor]')";
                		$sql =& new MySQLq();
						$sql->Query($query);
						$nid = $sql->IId();
						$sql->Close();
						$_REQUEST['nid'] = $nid;
                	} else {
                		$query  = "UPDATE " . $sql_prefix . "galerien SET  bgcolor='$_REQUEST[bgcolor]', titel='$_REQUEST[titel]', prozeile='$_REQUEST[prozeile]', w='$_REQUEST[w]', h='$_REQUEST[h]' WHERE id='$_REQUEST[nid]'";	
                		$nid = $_REQUEST['nid'];
                		$sql =& new MySQLq();
                		$sql->Query($query);
                		$sql->Close();
                	}
                	
					$_REQUEST['action'] = "bilder";
                } else {
                	$nid = $_REQUEST['nid'];
                }
                
                if ($_REQUEST['action']=="upload") {
                	$nid = $_REQUEST['nid'];
                	if (isset($_FILES['bild'])) {
                		$handle = fopen($_FILES['bild']['tmp_name'], "r");
                		$bild = fread($handle, filesize($_FILES['bild']['tmp_name']));
                		fclose($handle);
                		
                		$sql =& new MySQLq();
                		$sql->Query("INSERT INTO " . $sql_prefix . "galerien_bilder(gallerie,bild,titel) VALUES ('$nid','" . base64_encode($bild) . "','$_REQUEST[titel]')");
                		$sql->Close();
                	}
                	
                	$_REQUEST['action'] = "bilder";            
                }
                
                if ($_REQUEST['dowhat']=="loeschen")  {
                	$sql =& new MySQLq();
                	$sql->Query("DELETE FROM " . $sql_prefix . "galerien_bilder WHERE id='$_REQUEST[id]'");
                	$sql->Close();
                	$_REQUEST['action'] = "bilder";
                }
				if ($_REQUEST['dowhat']=="bearbeiten")  {
                	$sql =& new MySQLq();
                	$sql->Query("UPDATE " . $sql_prefix . "galerien_bilder  SET titel='$_REQUEST[titel]' WHERE id='$_REQUEST[id]'");
                	$sql->Close();
                	$_REQUEST['action'] = "bilder";
                }		
                
                if ($_REQUEST['action']=="bilder") {
                	?>
                	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


                	<?
                	$sql =& new MySQLq();
                	$sql->Query("SELECT * FROM " . $sql_prefix . "galerien_bilder WHERE gallerie='$_REQUEST[nid]' ORDER BY id ASC");
                	while ($row = $sql->FetchRow()) {
                		?>
                		<form action="" method="post">
                		<input type="hidden" name="action" value="delete">
                		<input type="hidden" name="id" value="<?=$row->id;?>">
                		<input type="hidden" name="nid" value="<?=$nid;?>">
                		<tr>
                		 <td bgcolor="#EAEBEE">
                		  <img src="modules/galerie/bild.php?modus=thumb&id=<?=$row->id;?>" border="0">
                		  &nbsp;
                		 </td>
                		 <td bgcolor="#FAFAFB">
                		   Titel<br>
                		   <input name="titel" type="text" id="titel" value="<?=$row->titel;?>">
                		   <input name="dowhat" type="submit" class="button"  id="dowhat" value="bearbeiten">
               		      <input name="dowhat" type="submit" class="button" id="dowhat" value="loeschen">                		 </td>
                		</tr>
                		</form>
                		<?
                	}
                	$sql->Close();
                	?>
                	</table>
				             	
		<br>
     	    
                	<script language="javascript">
                	<!--
                	function chk(F){
                			var len = F.bild.value.length;
                			var ext = new Array('.','j','p','g');
                			if (F.bild.value == '')	{
                				alert('Bitte wählen Sie eine Datei zum Hochladen aus!');
                				return false;
                			}
                			for (i=0; i < 4; i++)	{
                				if (ext[i] != F.bild.value.charAt(len-4+i))	{
                					alert('Es können nur Dateien im JPG-Format hochgeladen werden!');
                					return false;
                				}
                			}
                			return true;
                	}                									
                    //-->
                	</script>         	
                	<form action="" method="post" enctype="multipart/form-data" style="display:inline;" onSubmit="return chk(this);">
                	<input type="hidden" name="action" value="upload">
                	<input type="hidden" name="nid" value="<?=$nid;?>">
                	<center>Bild hinzuf&uuml;gen (JPG): <input type="file" name="bild" size="20"> Titel: <input type="text" name="titel" value="" size="15"> <input type="submit" class="button" value=" Ok ">
                	</form>
                	<?
                }
                
                if ($_REQUEST['action']=="" || !isset($_REQUEST['action']) || $_REQUEST['action']=="edit") {
                	?>
                	<form style="display:inline;" action="" name="gallerie" method="post">
                	<?
                	if (isset($_REQUEST['id'])) {
                		$sql =& new MySQLq();
                		$sql->Query("SELECT * FROM " . $sql_prefix . "galerien WHERE id='$_REQUEST[id]'");
                		$row = $sql->FetchRow();
                		$sql->Close();
                		$ed_titel = htmlentities(stripslashes($row->titel));
                		$ed_prozeile = htmlentities(stripslashes($row->prozeile));
                		$ed_w = $row->w;
                		$ed_h = $row->h;
						$bgc = $row->bgcolor;
                		?>
                		<input type="hidden" name="nid" value="<?=$row->id;?>">
                		<?
                	} else { ?>
					<?
                		$ed_titel = "";
                		$ed_prozeile = 4;
                		$ed_w = 120;
                		$ed_h = 100;
						$bgc = "#FFFFFF";
                	}
                	?>
                	<input type="hidden" name="action" value="step2">
					<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

					<tr bgcolor="#FAFAFB">
					<td width="150" bgcolor="#EAEBEE">Titel:</td>
					<td>
					  <input type="text" name="titel" size="32" style="width:98%;" value="<?=$ed_titel;?>"></td>
					</tr>
					<tr bgcolor="#FAFAFB">
					<td width="150" bgcolor="#EAEBEE">Bilder pro Zeile:</td>
					<td>
					  <input type="text" name="prozeile" size="4" value="<?=$ed_prozeile;?>"></td>
					</tr>
					<tr bgcolor="#FAFAFB">
					<td width="150" bgcolor="#EAEBEE">Thumbnail-Gr&ouml;&szlig;e:</td>
					<td>
					  <input type="text" size="3" name="w" value="<?=$ed_w;?>"> x <input type="text" size="3" name="h" value="<?=$ed_h;?>"></td>
					</tr>
					<tr bgcolor="#FAFAFB">
					  <td bgcolor="#EAEBEE">Hintergrundfarbe</td>
					  <td>
				      <input name="bgcolor" type="text" id="bgcolor" value="<?=$bgc;?>"></td>
					  </tr>
					<tr bgcolor="#FAFAFB">
					<td bgcolor="#EAEBEE">&nbsp;</td>
					<td>
					  <input type="submit" class="button" value="weiter">
</td>
					</tr>
					</table>
					</form>
					<?
                }
                ?>
				
					<? 	
 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?>