<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Software Details</strong></font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong> 
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?php
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
		  $result = mysql_query ("SELECT * FROM cnpBackend
                         WHERE valid LIKE '1'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Version</font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php	print $row["version"];	?>
        </font></td>
    </tr>
  </table>
  </form>
