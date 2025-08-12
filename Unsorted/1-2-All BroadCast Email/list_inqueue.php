<META HTTP-EQUIV="Refresh" CONTENT="25; URL=main.php?page=list_inqueue&nl=<?PHP print $nl; ?>">
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_504; ?></strong></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><a href="javascript:window.location.reload()"><b> 
  <?PHP print $lang_230; ?></b></a> <?PHP print $lang_231; ?></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_514;
	$t_date = date("Y-m-d");
	$t_time = date("H:i:s");
	print " $t_date , $t_time";
	?></font></p>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" align="center">
        <?PHP 
$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '$nl'
						AND completed LIKE '0'
						AND status LIKE '4'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <table width="100%" border="0" cellpadding="8" cellspacing="0" background="media/queue_back.gif">
              <tr background="media/invis.gif"> 
                <td><font size="2" face="Arial, Helvetica, sans-serif"><b> 
                  <?PHP 
					print $row["subject"]; 
					?>
                  </b></font></td>
                <td width="259"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_505; ?> <?PHP print $row["s_date"]; ?> at <?PHP print $row["s_time"]; ?></font></td>
                <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_send3_pre&id=<?PHP print $row["id"]; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $row["sent"]; ?>&cval=8"><?PHP print $lang_506; ?></a><br>
                  <a href="main.php?page=list_archive&val=del&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><?PHP print $lang_193; ?></a></font></td>
              </tr>
            </table></td>
        </tr>
        <?PHP
}

} else {
?>
        <font color="#336699" size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP
print "$lang_229";
		  ?>
        </font> 
        <?PHP
		  } ?>
      </table></td>
  </tr>
</table>
