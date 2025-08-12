 <p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_122; ?> </strong><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
  </font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></b></font></b></font></b></font></b></font></font><font size="2" face="Arial, Helvetica, sans-serif"> 
  </font></b></font></b></font></b></font></b></font></font> </p>
<p> </p>
  
<?PHP
if ($action != save){
?>
<font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_123; ?></font> 
<p><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_124; ?> EX: http://www.yoursite.com/thanks.htm</strong></font> 
</p>
<form name="adminForm" method="post" action="main.php">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100" height="50" bgcolor="#F3F3F3"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_108; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          </font></div></td>
      <td width="350" height="50" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="add3" type="text" id="name13" value="<?PHP	print $row["add3"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td width="100" height="50"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_110; ?></font></div></td>
      <td width="350" height="50"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <input name="add2" type="text" id="name22" value="<?PHP	print $row["add2"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td height="50" bgcolor="#F3F3F3"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_466; ?></font></td>
      <td height="50" bgcolor="#F3F3F3"><font size="1" face="Arial, Helvetica, sans-serif">
        <input name="add4" type="text" id="add2" value="<?PHP	print $row["add4"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td height="50"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_112; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
      <td height="50"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="add1" type="text" id="name32" value="<?PHP	print $row["add1"];	?>" size="30">
        </font></td>
    </tr>
    <tr bgcolor="#F3F3F3"> 
      <td height="50"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_109; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
      <td height="50"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="unsub3" type="text" id="name42" value="<?PHP	print $row["unsub3"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td height="50"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_111; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
      <td height="50"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="unsub2" type="text" id="name52" value="<?PHP	print $row["unsub2"];	?>" size="30">
        </font></td>
    </tr>
    <tr bgcolor="#F3F3F3">
      <td height="50"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_467; ?></font></td>
      <td height="50"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="unsub4" type="text" id="unsub2" value="<?PHP	print $row["unsub4"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td height="50"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_113; ?></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
      <td height="50"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="unsub1" type="text" id="name62" value="<?PHP	print $row["unsub1"];	?>" size="30">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input type="submit" name="Submit2" value="<?PHP print $lang_6; ?>">
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="page" type="hidden" id="page" value="engine_redirects">
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
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_125; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
mysql_query("UPDATE Backend SET add1='$add1',add2='$add2',add3='$add3',add4='$add4',unsub1='$unsub1',unsub2='$unsub2',unsub3='$unsub3',unsub4='$unsub4' WHERE (valid='1')");
?>
</font> 
<?PHP
}
?>
