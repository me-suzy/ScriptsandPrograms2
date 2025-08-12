<?
include("header.inc.php");

$result9 = mysql_query("SELECT meinebannera, meinebannerurla, meinebannerb, meinebannerurlb, meinebannerc, meinebannerurlc, meinebannerd, meinebannerurld FROM `demo_a_werbebanner`");
$myrow9 = mysql_fetch_row($result9);

$banna = $myrow9[0];
$bannaurl = $myrow9[1];
$bannb = $myrow9[2];
$bannburl = $myrow9[3];
$bannc = $myrow9[4];
$banncurl = $myrow9[5];
$bannd = $myrow9[6];
$banndurl = $myrow9[7];

$result10 = mysql_query("SELECT aa, ab, ba, bb, ca, cb, da, db FROM `demo_a_grosse`");
$myrow10 = mysql_fetch_row($result10);

$einsa = $myrow10[0];
$einsb = $myrow10[1];
$zweia = $myrow10[2];
$zweib = $myrow10[3];
$dreia = $myrow10[4];
$dreib = $myrow10[5];
$viera = $myrow10[6];
$vierb = $myrow10[7];

?>
<?
include("../templates/admin-header.txt");
?>
  <form method="post" action="banner2.php">
  <TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" width="99%" align="center">
<TR>
  <TD width="130" align="center" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Banner 1 IMAGE URL:<br> <input type="text" name="bannaneu" value="<? echo "$banna"; ?>"><br>Banner 1 Target URL:<br><input type="text" name="bannaurlneu" value="<? echo "$bannaurl"; ?>"><br>Gr&ouml;ße<br><input size="4" type="text" name="einsaneu" value="<? echo "$einsa"; ?>"> x <input size="4" type="text" name="einsbneu" value="<? echo "$einsb"; ?>"></TD>
  <TD width="470" align="center" bgcolor="#E6E6E6"><a href="<? echo "$bannaurl"; ?>" target="_blank"><img src="<? echo "$banna"; ?>" border="0"></a></TD>
</TR>
<TR>
  <TD width="130" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;</TD>
  <TD width="470" align="center">&nbsp;</TD>
</TR>
<TR>
  <TD width="130" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Banner 2 IMAGE URL:<br> <input type="text" name="bannbneu" value="<? echo "$bannb"; ?>"><br>Banner 2 Target URL:<br><input type="text" name="bannburlneu" value="<? echo "$bannburl"; ?>"><br>Gr&ouml;ße<br><input size="4" type="text" name="zweianeu" value="<? echo "$zweia"; ?>"> x <input size="4" type="text" name="zweibneu" value="<? echo "$zweib"; ?>"></TD>
  <TD width="470" align="center"><a href="<? echo "$bannburl"; ?>" target="_blank"><img src="<? echo "$bannb"; ?>" border="0"></a></TD>
</TR>
<TR>
  <TD width="130" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;</TD>
  <TD width="470" align="center">&nbsp;</TD>
</TR>
<TR>
  <TD width="130" align="center" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Banner 3 IMAGE URL:<br> <input type="text" name="banncneu" value="<? echo "$bannc"; ?>"><br>Banner 3 Target URL:<br><input type="text" name="banncurlneu" value="<? echo "$banncurl"; ?>"><br>Gr&ouml;ße<br><input size="4" type="text" name="dreianeu" value="<? echo "$dreia"; ?>"> x <input size="4" type="text" name="dreibneu" value="<? echo "$dreib"; ?>"></TD>
  <TD width="470" align="center" bgcolor="#E6E6E6"><a href="<? echo "$banncurl"; ?>" target="_blank"><img src="<? echo "$bannc"; ?>" border="0"></a></TD>
</TR>
<TR>
  <TD width="130" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;</TD>
  <TD width="470" align="center">&nbsp;</TD>
</TR>
<TR>
  <TD width="130" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Banner 4 IMAGE URL:<br> <input type="text" name="banndneu" value="<? echo "$bannd"; ?>"><br>Banner 4 Target URL:<br><input type="text" name="banndurlneu" value="<? echo "$banndurl"; ?>"><br>SIZE<br><input size="4" type="text" name="vieraneu" value="<? echo "$viera"; ?>"> x <input size="4" type="text" name="vierbneu" value="<? echo "$vierb"; ?>"></TD>
  <TD width="470" align="center"><a href="<? echo "$banndurl"; ?>" target="_blank"><img src="<? echo "$bannd"; ?>" border="0"></a></TD>
</TR>
</TABLE><br><center><input type="submit" value="Update"></form></center>
 <?
include("../templates/admin-footer.txt");
?>