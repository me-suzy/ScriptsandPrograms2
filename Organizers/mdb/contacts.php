<?php
include "header.inc.php";

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Contacts<br><br>");

// query contacts
$Query = mysql_query("SELECT * FROM $Table_contacts ORDER BY first_name ASC") or die(mysql_error());
$Count_Query = mysql_num_rows($Query);

// query groups
$Query_group = mysql_query("SELECT G_ID,name FROM $Table_groups ORDER BY name") or die(mysql_error());
$Count = mysql_num_rows($Query_group);
?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr> 
          <td><p><img src="images/mycontacts.gif" width="200" height="50"></p>
            <table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td class="Title" style="padding='3'">Name</td>
                                  <td width="50" class="Title" style="padding='3'"><div align="center">Email</div></td>
                                  <td width="150" align="center" class="Title" style="padding='3'">Home Phone</td>
                                  <td width="150" align="center" class="Title" style="padding='3'">Work Phone</td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              
<?php
	if ($Count_Query == 0) {
		echo "<tr><td align=center><strong>No contacts found in database</strong></td></tr>";
	}
	
	
	if ($Count > 0) {
		while ($R1=mysql_fetch_object($Query_group)) {


?>
			  
			  
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFCC00">
                          <tr> 
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr> 
                                  <td><a href="groups.php?Sec=contacts&ID=<?= $R1->G_ID; ?>&gname=<?= $R1->name; ?>" class="linkB" onMOuseOver="this.style.color='#FF0000'" onMouseOut="this.style.color='#000000'" style="color='#000000'"><strong>- Group: <?= $R1->name; ?></strong></a></td>
                                  <td width="50"><div align="center"><a href="email.php?Sec=contacts&ID=<?= $R1->G_ID; ?>&type=2"><img src="images/mail_group.gif" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                                  <td width="302"> 
                                    <div align="left">&nbsp;&nbsp;&nbsp;</div></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

			  


<?php

			}
	}



	while ($R=mysql_fetch_object($Query)) {

	  ?>

			  <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr> 
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr> 
                                  <td><a href="edit_contact.php?Sec=contacts&ID=<?= $R->C_ID; ?>" class="linkB" onMOuseOver="this.style.color='#FF0000'" onMouseOut="this.style.color=''"><strong><?= $R->first_name; ?>&nbsp;<?= $R->last_name; ?></a></strong></td>
                                  <td width="50"><div align="center"><a href="email.php?Sec=contacts&ID=<?= $R->C_ID; ?>&type=1"><img src="images/email_contact.gif" width="18" height="12" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                                  <td width="150" align="center"><?= $R->home_phone; ?></td>
                                  <td width="150" align="center"><?= $R->work_phone; ?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

		  <?php

		}
	?>


              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            <p>&nbsp;</p></td>
        </tr>
      </table></td>
  </tr>
</table>


<?php

include "footer.inc.php";
?>