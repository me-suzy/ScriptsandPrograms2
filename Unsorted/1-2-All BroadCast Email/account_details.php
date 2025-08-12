<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_1; ?> 
  <?PHP if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Admin
                         WHERE user LIKE '$usernow'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="main.php">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_2; ?></font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP	print $row["user"];	?></font></td>
    </tr>
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_3; ?></font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="pass" type="password" id="pass" value="<?PHP $passnow=base64_decode ($row["pass"]); print $passnow; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="name" type="text" id="name" value="<?PHP	print $row["name"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="email" type="text" id="email" value="<?PHP	print $row["email"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input type="submit" name="Submit" value="<?PHP print $lang_6; ?>">
          <input name="val" type="hidden" id="val" value="final">
          </font><font size="2"><font face="Arial, Helvetica, sans-serif"> </font><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font size="2"><font face="Arial, Helvetica, sans-serif">
          <input name="page" type="hidden" id="val3" value="account_details">
          </font></font><font face="Arial, Helvetica, sans-serif"> </font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
          </font></font></p></td>
    </tr>
  </table>
  <br>
  <br>
  <table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#333333">
    <tr> 
      <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
          <tr> 
            <td><div align="center"> 
                <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><font color="#990000"><?PHP print $lang_7; ?></font><br>
                  </strong></font><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_8; ?></font></p>
                </div>
              </td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<?PHP
}
else {
?>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_9; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?PHP
$pass=base64_encode($pass);
mysql_query("UPDATE Admin SET pass='$pass',name='$name',email='$email' WHERE (user='$usernow')");
?>
  <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP } ?>
