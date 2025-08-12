<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_126; ?> </strong><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></b></font></b></font></b></font></b></font></font></p>
<?PHP
if ($action != save){
?>
<form name="adminForm" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr valign="top"> 
      <td height="30" bgcolor="#F3F3F3"><table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
          <tr> 
            <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
                <tr> 
                  <td> <p><font face="Arial, Helvetica, sans-serif" size="2"><strong></strong><font size="3"><b><?PHP print $lang_127; ?></b></font></font><font color="#666666" size="3" face="Arial, Helvetica, sans-serif"></font></p></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <br> <font size="2" face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="murl" type="text" id="murl" value="<?PHP	print $row["murl"];	?>" size="40">
        <font size="1"><br>
        </font> </font></font><font size="1"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1"><?PHP print $lang_128; ?></font></font><font color="#666666" face="Arial, Helvetica, sans-serif"><br>
        EX: http://domain.com/12all</font></font></td>
      <td width="200" bgcolor="#F3F3F3"><table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
          <tr> 
            <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
                <tr> 
                  <td> <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><font size="3"><?PHP print $lang_449; ?></font></strong></font><font size="3"><strong></strong></font></p></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <font size="2" face="Arial, Helvetica, sans-serif"><strong> <br>
        <select name="lang" id="lang">
          <option value="english" <?PHP if ($row["lang"] == "english"){ print "selected"; } ?>>English</option>
          <option value="chinese" <?PHP if ($row["lang"] == "chinese"){ print "selected"; } ?>>Chinese</option>
          <option value="dutch" <?PHP if ($row["lang"] == "dutch"){ print "selected"; } ?>>Dutch</option>
          <option value="french" <?PHP if ($row["lang"] == "french"){ print "selected"; } ?>>French</option>
          <option value="german" <?PHP if ($row["lang"] == "german"){ print "selected"; } ?>>German</option>
          <option value="spanish" <?PHP if ($row["lang"] == "spanish"){ print "selected"; } ?>>Spanish</option>
          <option value="custom" <?PHP if ($row["lang"] == "custom"){ print "selected"; } ?>>Custom</option>
        </select>
        </strong></font></td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" name="submit">
        <input name="page" type="hidden" id="page" value="engine_settings">
        <input name="action" type="hidden" id="action" value="save">
        <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
        </font></td>
    </tr>
  </table>
</form>
<?PHP
}
else {	


?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_125; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
$smtp_pass = base64_encode($smtp_pass);
mysql_query("UPDATE Backend SET murl='$murl', lang='$lang' WHERE (valid='1')");
?>
</font> 
<?PHP
}
?>
