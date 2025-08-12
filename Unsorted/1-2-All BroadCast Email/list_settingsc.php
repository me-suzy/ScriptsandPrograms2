<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_274; ?> </strong></font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699">
  <?PHP
		  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></p>
<?PHP
if ($action != save){
?>
<form name="adminForm" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td bgcolor="#BFD2E8"><div align="center"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <input type="radio" name="confirm" value="1" <?PHP	if ($row["confirm"] == 1){ print "checked"; } ?>>
          <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
          <input type="radio" name="confirm" value="0" <?PHP	if ($row["confirm"] == 0){ print "checked"; } ?>>
          <?PHP print $lang_198; ?></font></font><font size="2" face="Arial, Helvetica, sans-serif"></font></div></td>
      <td bgcolor="#D5E2F0"><font size="2"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_275; ?></strong></font><font face="Arial, Helvetica, sans-serif"> 
        </font></font></td>
    </tr>
    <tr> 
      <td width="200" valign="top"><div align="left"> 
          <p><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_276; ?><br>
            </font><font face="Arial, Helvetica, sans-serif"><font color="#666666" size="1"><?PHP print $lang_277; ?></font></font></p>
        </div></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="welcomes" value="<?PHP	print $row["welcomes"];	?>">
        <?PHP print $lang_31; ?> <br>
        <textarea name="welcomemesg" cols="50" rows="4" id="welcomemesg"><?PHP	print stripslashes($row["welcomemesg"]);	?></textarea>
        <br>
        </font><font color="#666666" size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_278; ?>: 
        subscribername<br>
        <?PHP print $lang_279; ?>: subscriberemail</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#BFD2E8"><div align="center"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <input type="radio" name="confirm2" value="1" <?PHP	if ($row["confirm2"] == 1){ print "checked"; } ?>>
          <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
          <input type="radio" name="confirm2" value="0" <?PHP	if ($row["confirm2"] == 0){ print "checked"; } ?>>
          <?PHP print $lang_198; ?></font></font><font size="2" face="Arial, Helvetica, sans-serif"></font></div></td>
      <td bgcolor="#D5E2F0"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_464
; ?></strong></font><font size="2">&nbsp;</font></td>
    </tr>
    <tr> 
      <td width="200" valign="top"><div align="left"> 
          <p><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_280; ?><b><br>
            <font color="#666666"> </font></b><font color="#666666" size="1"><?PHP print $lang_281; ?></font></font></p>
        </div></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="goodbyes" value="<?PHP	print $row["goodbyes"];	?>">
        <?PHP print $lang_31; ?> <br>
        <textarea name="goodbyemesg" cols="50" rows="4" id="goodbyemesg"><?PHP	print stripslashes($row["goodbyemesg"]);	?></textarea>
        <br>
        </font><font color="#666666" size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_279; ?>: 
        subscriberemail</font><font color="#666666" size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td><p><font size="2"><font face="Arial, Helvetica, sans-serif"> <br>
          <input type="submit" name="Submit2" value="<?PHP print $lang_98; ?>">
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="page" type="hidden" id="page" value="list_settingsc">
          <input name="action" type="hidden" id="action" value="save">
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font face="Arial, Helvetica, sans-serif"> </font></font></p></td>
    </tr>
  </table>
  </form>
<?PHP
}
else {
?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_174; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
$welcomemesg = addslashes($welcomemesg);
$goodbyemesg = addslashes($goodbyemesg);
mysql_query("UPDATE Lists SET welcomemesg='$welcomemesg',goodbyemesg='$goodbyemesg',welcomes='$welcomes',goodbyes='$goodbyes',confirm='$confirm',confirm2='$confirm2' WHERE (id='$nl')");
?>
</font> 
<?PHP
}
?>
