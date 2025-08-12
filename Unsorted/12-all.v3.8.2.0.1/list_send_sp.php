<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_450; ?></strong></font></p>
<form name="form1" method="post" action="">
  <?PHP
  if ($opt != view){
  if ($val == "del"){
  print "<p>";
  mysql_query ("DELETE FROM Messages
  WHERE id LIKE '$id'
								");

  print "$lang_451.<p>";
  }
  ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr bgcolor="#D5E2F0"> 
      <td width="78"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?></font></b></div></td>
      <td width="58"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_30; ?></font></b></div></td>
      <td> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></b></div></td>
      <td width="98">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr> 
      <td bgcolor="#CCCCCC"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
          <?PHP 
		  $cnl = ", $nl ,";
$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '%$cnl%'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
          <tr bgcolor="#FFFFFF"> 
            <td width="70" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP print $row["mdate"]; ?> </font></div></td>
            <td bordercolor="#CCCCCC" width="50"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP print $row["mtime"]; ?> </font></div></td>
            <td width="758" bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP 
			if ($row["subject"] == ""){
			print "No Subject";
			}
			else {
			  $subject = ereg_replace ("[\]", "", $row["subject"]);	

			print $subject; 
			}
			?>
                </font></div></td>
            <td width="91" bordercolor="#CCCCCC"><p align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <font size="1"><a href="main.php?page=list_sendr1&nl=<?PHP print $nl; ?>&psubject=<?PHP print $row["subject"]; ?>&pfrom=<?PHP print $row["mfrom"]; ?>&format=<?PHP print $row["type"]; ?>&ptext=<?PHP print $row["id"]; ?>&previewalpha=<?PHP print $row["id"]; ?>&nlsets=<?PHP print $row["nl"]; ?>&savid=<?PHP print $row["id"]; ?>"><?PHP print $lang_452; ?><br>
                </a></font></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_send_sp&val=del&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/del.gif" width="11" height="7" border="0"></a></font></p>
              </td>
          </tr>
          <?PHP
}

} else {print "$lang_32.";} ?>
        </table></td>
    </tr>
  </table>
  <br>
  <table width="360" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
    <tr> 
      <td> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_143; ?></font></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
          <tr> 
            <td> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/del.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_144; ?></font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <?PHP }

  ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  </font></font></b></font></b></font></b></font></b></font></font></b></font></b></font></b></font></b></font>
</form>
