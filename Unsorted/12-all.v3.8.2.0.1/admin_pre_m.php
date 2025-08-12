
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_282; ?> </strong></font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Admin
                         WHERE id LIKE '$aid'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font> </p>
<p> </p>
  <?PHP
if ($action != save){
?>
<form name="adminForm" method="post" action="main.php">
  <?PHP
  if ($row_admin["user"] == "admin"){
  ?>
  <br>
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td colspan="2" bgcolor="#BFD2E8"><font size="2"><font face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_470; ?></strong></font></font><strong><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></strong></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_471; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_ui" value="0" <?PHP	if ($row["a_ui"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_ui" value="1" <?PHP	if ($row["a_ui"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_472; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_ua" value="0" <?PHP	if ($row["a_ua"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_ua" value="1" <?PHP	if ($row["a_ua"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_474; ?></font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="a_as" type="text" id="a_as" value="<?PHP	if ($row["a_as"] == "0"){	print "100000";
			}
			else{
	print $row["a_as"];	} ?>" size="10">
        <font color="#666666">bytes</font> </font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_475; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="a_ff" type="text" id="a_ff" value="<?PHP if ($row["a_ff"] == ""){ print "gif,jpg,pdf,zip"; } else { print $row["a_ff"]; } ?>">
        <br>
        <font size="1"><?PHP print $lang_476; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_477; ?></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="a_nm" type="text" id="a_nm" value="<?PHP	print $row["a_nm"];	?>" size="10">
        </font> <select name="a_pt" id="a_pt">
          <option value="day" <?PHP if ($row["a_pt"] == "" OR $row["a_pt"] == "day"){ print "selected"; } ?>>Per 
          Day</option>
          <option value="week" <?PHP if ($row["a_pt"] == "week"){ print "selected"; } ?>>Per 
          Week</option>
          <option value="month" <?PHP if ($row["a_pt"] == "month"){ print "selected"; } ?>>Per 
          Month</option>
        </select> <br> <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_479; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_gc" value="0" <?PHP	if ($row["a_gc"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_gc" value="1" <?PHP	if ($row["a_gc"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_480; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_tp" value="0" <?PHP	if ($row["a_tp"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_tp" value="1" <?PHP	if ($row["a_tp"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_493; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_lt" value="0" <?PHP	if ($row["a_lt"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_lt" value="1" <?PHP	if ($row["a_lt"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_494; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_pz" value="0" <?PHP	if ($row["a_pz"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_pz" value="1" <?PHP	if ($row["a_pz"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_495; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_bn" value="0" <?PHP	if ($row["a_bn"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_197; ?> &nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_bn" value="1" <?PHP	if ($row["a_bn"] == 1){ print "checked"; } ?>>
        <?PHP print $lang_198; ?></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_496; ?></font></td>
      <td><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_op" value="0" <?PHP	if ($row["a_op"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_op" value="1" <?PHP	if ($row["a_op"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif"> 
        </font></font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_497; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="a_co" value="0" <?PHP	if ($row["a_co"] == 0){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_197; ?></font></font><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp; 
        <input type="radio" name="a_co" value="1" <?PHP	if ($row["a_co"] == 1){ print "checked"; } ?>>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_198; ?></font></font><font face="Arial, Helvetica, sans-serif"> 
        </font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_482; ?></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="a_mx" type="text" id="a_mx" value="<?PHP	print $row["a_mx"];	?>" size="10">
        <br>
        </font><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
    </tr>
    <tr> 
      <td width="200" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_483; ?></font></td>
      <td bgcolor="#F3F3F3"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input name="a_s1" type="checkbox" id="a_s1" value="0" <?PHP if ($row["a_s1"] == 0){ print "checked"; } ?>>
        <?PHP print $lang_484; ?>&nbsp;&nbsp;&nbsp; </font><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input name="a_s2" type="checkbox" id="a_s2" value="0" <?PHP if ($row["a_s2"] == 0){ print "checked"; } ?>>
        </font></font><font face="Arial, Helvetica, sans-serif"> <?PHP print $lang_485; ?></font><font size="2"><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp; 
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input name="a_s3" type="checkbox" id="a_s3" value="0" <?PHP if ($row["a_s3"] == 0){ print "checked"; } ?>>
        </font></font><font face="Arial, Helvetica, sans-serif"> <?PHP print $lang_486; ?></font></font></font></td>
    </tr>
    <tr> 
      <td width="200"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_487; ?></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="a_em" type="text" id="a_em" value="<?PHP	print $row["a_em"];	?>">
        </font></td>
    </tr>
  </table>
  <?PHP } ?>
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="Submit" type="submit" id="Submit" value="<?PHP print $lang_98; ?>">
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="page" type="hidden" id="page" value="admin_pre_m">
          <input name="action" type="hidden" id="action" value="save">
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font size="2"><font size="2" face="Arial, Helvetica, sans-serif">
          <input name="aid" type="hidden" id="aid" value="<?PHP print $aid; ?>">
          </font></font><font size="2" face="Arial, Helvetica, sans-serif"> </font><font face="Arial, Helvetica, sans-serif"> 
          </font></font></p></td>
    </tr>
  </table>
</form>
<p> 
  <?PHP
}
else {
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_99; ?></font> 
  <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?PHP
if ($a_s1 == ""){
$a_s1 = "1";
}
if ($a_s2 == ""){
$a_s2 = "1";
}
if ($a_s3 == ""){
$a_s3 = "1";
}
mysql_query("UPDATE Admin SET a_ui='$a_ui',a_ua='$a_ua',a_is='$a_is',a_as='$a_as',a_ff='$a_ff',a_nm='$a_nm',a_pt='$a_pt',a_tp='$a_tp',a_gc='$a_gc',a_ed='$a_ed',a_mx='$a_mx',a_s1='$a_s1',a_s2='$a_s2',a_s3='$a_s3',a_em='$a_em',a_lt='$a_lt',a_pz='$a_pz',a_bn='$a_bn',a_co='$a_co',a_op='$a_op' WHERE (id='$aid')");
?>
  </font> 
  <?PHP
}
?>
</p>
