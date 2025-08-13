<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	if (isset($_REQUEST['id'])) {
 		if ($_REQUEST['id']=="add") {
 			$_SESSION['kfelder'][] = array(
 					'name' => $_REQUEST['name'],
 					'typ'  => $_REQUEST['typ'],
					'pflicht'  => $_REQUEST['pflicht'],
					'laenge'  => $_REQUEST['laenge']
 			); 			
 		} else {
 			if (isset($_REQUEST['del'])) {
 				unset ($_SESSION['kfelder'][$_REQUEST['id']]);
 			} else {
 				$_SESSION['kfelder'][$_REQUEST['id']] = array(
 						'name' => $_REQUEST['name'],
 						'typ'  => $_REQUEST['typ'],
						'pflicht'  => $_REQUEST['pflicht'],
						'laenge'  => $_REQUEST['laenge']
 				); 				
 			}
 		}
 	}
?>
<script language="JavaScript">
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Inhalt wird markiert (und in die Zwischenablage kopiert) !"
setTimeout("window.status=''",1800)
}
}
</script>
    <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

       <tr>
                <td valign="top" class="boxstandart">
                Sie erstellen ein Kontaktformular, indem Sie
                zuerst die Felder definieren, die vom Kontakt-Suchenden
                ausgefüllt werden sollen. Dann geben Sie bitte die
                Empfänger-Mailadresse an und klicken auf "Code generieren".
                </td>
              </tr>
     </table><br>
   
                 <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


                  <tr bgcolor="#EAEBEE">
                   <td><b>Feldname:</b></td>
                   <td><b>Feldtyp:</b></td>
                   <td><b>Pflichtfeld</b></td>
                   <td><b>L&auml;nge</b></td>
                   <td>&nbsp;</td>
                  </tr>
                  <?
                  while (list($key, $val) = each($_SESSION['kfelder'])) {
                  	?>
                  <form style="display:inline;" action="" method="post">
                  <input type="hidden" name="id" value="<?=$key;?>">
                  <tr bgcolor="#FAFAFB">
                   <td>
                    <input type="text" name="name" style="width:100%;" value="<?=htmlentities(stripslashes($val['name']));?>"></td>
                   <td>
                     <select name="typ">
                   <option value="text"<? if($val['typ']=="text") echo " selected"; ?>>Einzeilig</option>
                   <option value="textarea"<? if($val['typ']=="textarea") echo " selected"; ?>>Mehrzeilig</option>
                   </select></td>
                   <td>
                    <input name="pflicht" type="checkbox" id="pflicht" value="1" <? if($val['pflicht']=="1"){echo"checked";}?>></td>
                   <td>
                     <input name="laenge" type="text" id="laenge" value="<?=htmlentities(stripslashes($val['laenge']));?>" size="10">
                   Pixel</td>
                   <td>
                    <input class="button" type="submit" value="Ändern"> <input class="button" type="submit" value="Löschen" name="del"></td>
                  </tr>
                  </form>
                  	<?
                  }
                  ?>
                  <form style="display:inline;" action="" method="post">
                  <input type="hidden" name="id" value="add">
                  <tr bgcolor="#FAFAFB">
                   <td>
                    <input type="text" name="name" style="width:100%;"></td>
                   <td>
                     <select name="typ">
                   <option value="text">Einzeilig</option>
                   <option value="textarea">Mehrzeilig</option>
                   </select></td>
                   <td>
                    <input name="pflicht" type="checkbox" id="pflicht" value="1" <? if($val['pflicht']=="1"){echo"checked";}?>></td>
                   <td>
                     <input name="laenge" type="text" id="laenge" value="<?=htmlentities(stripslashes($val['laenge']));?>" size="10">
                   Pixel</td>
                   <td>
                    <input class="button" type="submit" value="Hinzufügen"></td>
                  </tr>
                 </table>
                 </form>
            <br>
    <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
                <td valign="top" class="boxstandart">
                <?
 					if ($_REQUEST['action'] != "code") {
 						?>
<b>Empfänger eMail-Adresse(n) angeben</b><br>Sie haben 2 Möglichkeiten<br><br>
1. Einen einzelnen Empfänger anlegen<br>
Geben Sie dazu einfach die eMail-Adress in das Feld ein<br><br>
2. Eine Auswahl erstellen, die es dem Benutzer erlaubt den Empfänger zu wählen<br>
<br>

<form name="s" action="" method="post" style="display:inline;" onSubmit="return checkTHIS();">
<script language="javascript">
<!--
function checkTHIS()
{
if(document.s.mailto.value.length < 1){
alert("Bitte geben Sie die eMail-Adressen an");
document.s.mailto.focus();
return false;}
}
//-->
</script>
<input type="hidden" name="action" value="code">
                 <table width="100%">
                  <tr>
                   <td width="150">Admin E-Mail(s):&nbsp;</td>
                   <td><input type="text" name="mailto" style="width:100%;"></td>
                  </tr>
                  <tr>
                   <td width="150">&nbsp;</td>
                   <td><input type="submit" value="Code generieren" class="button"></td>
                  </tr>
                 </table>
                 </form>
                 		<?
 					} else {
 						?>
<form name="source">
<textarea name="codegen" style="width:100%;height:200;" readonly><?
 						ob_start();
 						?>
<!-- KONTAKTFORM -->
<form name="kontakt" action="/p4cms/modules/kontakt/send.php" method="post" target="_blank"  onSubmit="return checkthis();">
<? echo'<script language="javascript">
<!--
function checkthis()
{ '; 

reset($_SESSION['kfelder']);
while (list($key, $val) = each($_SESSION['kfelder']))
	{
	if($val['pflicht']=="1")
		{
		echo"
		if(document.kontakt.".$val['name'].".value < 1){
		alert(\"Bitte kontrollieren Sie das Feld -> ".$val['name']."  \");
		document.kontakt.".$val['name'].".focus();
		return false;}
		";
		}
	}

 
echo '}
//-->
</script>'; ?>

<table>
<? if (ereg(";", $_REQUEST['mailto'])) {
	$mails = explode(";", $_REQUEST['mailto']);
	echo "<tr>\r\n <td>Empfänger: </td>\r\n <td><select class=feld name=\"mailto\">\r\n";
	while (list($key, $val) = each($mails)) {
		list($titel, $addy) = explode(",", $val);
		echo "<option value=\"$addy\">$titel</option>\r\n";
	}
	echo "</select></td>\r\n</tr>\r\n";
} else {
	?>
<input class="feld" type="hidden" name="mailto" value="<?=$_REQUEST['mailto'];?>">	
	<?
} 

reset($_SESSION['kfelder']);
while (list($key, $val) = each($_SESSION['kfelder'])) {
	?><tr>
 <td><?=$val['name'];?>: </td>
 <td><? if ($val['typ']=="textarea") { ?><textarea class="feld" style="width:<? if($val['laenge']==""){echo"150";}else{echo $val['laenge'];} ?>px" rows="5" name="<?=$val['name'];?>"></textarea><? } else {
?><input class="feld" style="width:<? if($val['laenge']==""){echo"150";}else{echo $val['laenge'];} ?>px" name="<?=$val['name'];?>"><? } ?></td>
</tr>
<?
}
?>
<tr>
 <td>&nbsp;</td>
 <td><input class="button" type="submit" value="Absenden"></td>
</tr>
</table>
</form>
<!-- KONTAKTFORM -->
<?
 						$inh = ob_get_contents();
 						ob_end_clean();
 						
 						echo (htmlentities($inh));
 						?>
						</textarea>
						<input class="button" onClick="HighlightAll('source.codegen')" type="button" value="in die Zwischenablage kopieren">
 						</form>
						<?
 					}
 				?>
                </td>
              </tr>
     </table>
    <?
 }
?>