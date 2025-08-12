<p><font size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="4" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font color="#336699" size="4"> 
  <strong> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM ListMembers
                         WHERE id LIKE '$id'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </strong></font></font></b></font></b></font></b></font></font></font></b></font></b></font></b></font></b></font><strong><font color="#336699"><?PHP print $row["email"]; ?></font></strong></font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="50%">
<table width="272" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
        <tr> 
          <td><table width="270" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
              <tr> 
                <td colspan="2" bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_367; ?> </strong></font></div></td>
              </tr>
              <tr> 
                <td width="95"> <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_5; ?> </font></div></td>
                <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["email"]; ?> 
                    </font></div></td>
              </tr>
              <tr> 
                <td width="95"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></div></td>
                <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?PHP 
			  $namclown = $row["name"];
			  if ($namclown == ""){
			  print "-";
			  }
			  else {
			   print $namclown; 
			   }
			  ?>
                    </font></div></td>
			  
			  		        <?PHP
					  $result213 = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$listinfo = mysql_fetch_array($result213);

		$cnumc = 0;
		while($cnumc !=11){
		if ($listinfo["field$cnumc"] != ""){
		?>
		    </tr>
	    <tr> 
      <td width="95"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $listinfo["field$cnumc"]; ?></font></div></td>
                <td > <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?PHP	
					if ($row["field$cnumc"] == ""){
					print "-"; 
					}
					else {
					print $row["field$cnumc"];	
					}
					?>
                    </font></div></td>
    </tr>
        <?PHP
		}
		$cnumc = $cnumc + 1;
		}
		?>

            </table></td>
        </tr>
      </table>
      
    </td>
    <td width="50%"><div align="right"></div>
      <table width="252" border="0" align="right" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
        <tr> 
          <td><table width="250" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
              <tr> 
                <td width="100" height="35"> <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_368; ?></font></div></td>
                <td height="35"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["sdate"]; ?> 
                    </font></div></td>
              </tr>
              <tr> 
                <td width="100" height="35"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_369; ?></font></div></td>
                <td height="35"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $row["comp"]; ?></font></div></td>
              </tr>
              <tr> 
                <td width="100" height="35"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif">IP Address </font></div></td>
                <td height="35"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["sip"]; ?> 
                    </font></div></td>
              </tr>
              <tr> 
                <td width="100" height="35"> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_370; ?> 
                    </font></div>
                  <div align="right"></div></td>
                <td height="35"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?PHP 
				if ($row["active"] == 0){
				print "Active";
				}
				else {
				print "Inactive";
				}
				?>
                    </font></div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
