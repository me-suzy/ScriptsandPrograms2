<?PHP
if ($action == "set"){
	mysql_query("UPDATE Lists SET confirmoptt='$format' WHERE (id='$nl')");
}
$result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<font color="#336699" size="4" face="Arial, Helvetica, sans-serif"></font> 
<form action="main.php" method="post" name="formatswitch" id="formatswitch">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><div align="left"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_195; ?> 
          </strong><font size="2" face="Arial, Helvetica, sans-serif"><b> 
          </b>
          <input name="page" type="hidden" id="page" value="list_opt">
          <input name="action" type="hidden" id="action" value="set">
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          <b> </b></font></font></div></td>
      <td width="225"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_555; ?>:</font></div></td>
      <td width="135"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <select name="format" id="format">
            <option value="text" <?PHP if ($row["confirmoptt"] == "" OR $row["confirmoptt"] == "text"){ print "selected"; } ?>>Text</option>
            <option value="html" <?PHP if ($row["confirmoptt"] == "html"){ print "selected"; } ?>>HTML</option>
          </select>
          <input type="submit" value="<?PHP print $lang_427; ?>">
          </font></div></td>
    </tr>
  </table>
</form>
<p>
<?PHP
if ($action == "set"){
	mysql_query("UPDATE Lists SET confirmoptt='$format' WHERE (id='$nl')");
	print "<font size=\"2\" face=\"Arial, Helvetica, sans-serif\" color=\"#990000\">$lang_174</font>";
}
if ($action == "save") {
	print "<font size=\"2\" face=\"Arial, Helvetica, sans-serif\" color=\"#990000\">$lang_174</font>";
	$urlfinder = mysql_query ("SELECT * FROM Backend
								WHERE valid LIKE '1'
								limit 1
							");
	$findurl = mysql_fetch_array($urlfinder);
	$murl = $findurl["murl"];
	if ($murl != ""){
		$icontent2 = ereg_replace ("$murl/%CONFIRM", "%CONFIRM", $icontent2);
		$ocontent2 = ereg_replace ("$murl/%CONFIRM", "%CONFIRM", $ocontent2);
		$icontent2 = ereg_replace ("$murl/#", "#", $icontent2);
		$ocontent2 = ereg_replace ("$murl/#", "#", $ocontent2);
	}
	$icontent2 = addslashes($icontent2);
	$ocontent2 = addslashes($ocontent2);
	$isubject = addslashes($isubject);
	$osubject = addslashes($osubject);
	mysql_query("UPDATE Lists SET isubject='$isubject',osubject='$osubject',icontent='$icontent2',ocontent='$ocontent2',confirmopt='$confirmopt',confirmopt2='$confirmopt2' WHERE (id='$nl')");
}
		  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
</p>
<form name="adminForm" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="110" height="30" bgcolor="#BFD2E8"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="radio" name="confirmopt" value="1" <?PHP	if ($row["confirmopt"] == 1){ print "checked"; } ?>>
          <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
          <input type="radio" name="confirmopt" value="0" <?PHP	if ($row["confirmopt"] == 0){ print "checked"; } ?>>
          <?PHP print $lang_198; ?></font></div></td>
      <td height="30" bgcolor="#D5E2F0"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <strong><?PHP print $lang_196; ?></strong></font></td>
    </tr>
    <tr> 
      <td width="110" height="30" valign="top" bgcolor="#FFFFFF"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></td>
      <td height="30"><input name="isubject" type="text" id="isubject" value="<?PHP	print stripslashes($row["isubject"]);	?>" size="45"></td>
    </tr>
    <tr> 
      <td width="110" height="30" valign="top" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_554; ?></font></td>
      <td height="30">&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2" valign="top" bgcolor="#F3F3F3"><p> 
          <?PHP 
		  $type = $row["confirmoptt"];
		  $icontent = $row["icontent"];
		  $ocontent = $row["ocontent"];
		  $riname = $row["name"];
		  $riemail = $row["email"];
	if ($icontent == ""){
		$icontent = "$lang_559, $riname.\n\n$lang_560:\n%CONFIRMLINK%\n\n$lang_561 $riemail";
	}
	if ($ocontent == ""){
		$ocontent = "$lang_562, $riname $lang_563.\n\n$lang_564:\n%CONFIRMLINK%\n\n$lang_565 $riemail";
	}
  if ($type == "html"){
  ?>
          <?PHP 
	$visEdit_root = __FILE__ ;
	$visEdit_root = str_replace('\\', '/', $visEdit_root);
	$icontent = nl2br($icontent);
	$visEdit_root = str_replace('list_opt.php', 'e_data/', $visEdit_root);
	//$visEdit_root = 'e_data/';
	include $visEdit_root.'visEdit_control.class.php'; 
	include $visEdit_root.'/lib/lang/en/en_lang_data.inc.php'; 
	// Generate pre existing content
	$sw = new visEdit_Wysiwyg('icontent2' /*name*/,$icontent /*value*/,
                       'en' /*language*/, 'full' /*toolbar mode*/, 'default' /*theme*/);
	$sw->show();
}
?>
          <font size="2" face="Arial, Helvetica, sans-serif"><b> 
          <?PHP 
  if ($type == "text"){
  ?>
          </b></font> 
          <textarea name="icontent2" cols="65" rows="10" id="icontent2"><?PHP	print $icontent;	?></textarea>
          <?PHP } ?>
      </td>
    </tr>
    <tr> 
      <td width="110" height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td width="110" height="30" bgcolor="#BFD2E8"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="radio" name="confirmopt2" value="1" <?PHP	if ($row["confirmopt2"] == 1){ print "checked"; } ?>>
          <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
          <input type="radio" name="confirmopt2" value="0" <?PHP	if ($row["confirmopt2"] == 0){ print "checked"; } ?>>
          <?PHP print $lang_198; ?></font></div></td>
      <td height="30" bgcolor="#D5E2F0"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_465; ?></strong></font></td>
    </tr>
    <tr> 
      <td width="110" height="30" valign="top" bgcolor="#FFFFFF"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></td>
      <td height="30"><input name="osubject" type="text" id="osubject" value="<?PHP	print stripslashes($row["osubject"]);	?>" size="45"></td>
    </tr>
    <tr> 
      <td width="110" height="30" valign="top" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_556; ?></font></td>
      <td height="30">&nbsp;</td>
    </tr>
    <tr> 
      <td height="30" colspan="2" valign="top" bgcolor="#F3F3F3"><font face="Arial, Helvetica, sans-serif" size="2"> 
        <?PHP 
		  $type = $row["confirmoptt"];
  if ($type == "html"){
  ?>
        <?PHP 
	// Generate pre existing content
	$ocontent = nl2br($ocontent);
	$sw = new visEdit_Wysiwyg('ocontent2' /*name*/,$ocontent /*value*/,
                       'en' /*language*/, 'full' /*toolbar mode*/, 'default' /*theme*/);
	$sw->show();
}
?>
        <font size="2" face="Arial, Helvetica, sans-serif"><b> 
        <?PHP 
  if ($type == "text"){
  ?>
        </b></font> 
        <textarea name="ocontent2" cols="65" rows="10" id="ocontent2"><?PHP	print $ocontent;	?></textarea>
        <?PHP } ?>
        </font></td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" name="submit2">
        <input name="page" type="hidden" id="page" value="list_opt">
        <input name="action" type="hidden" id="action" value="save">
        <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
        </font></td>
    </tr>
  </table>
  <p><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><em>*<?PHP print $lang_557; ?> &nbsp;%CONFIRMLINK% 
    &nbsp;<?PHP print $lang_558; ?></em></font></p>
</form>