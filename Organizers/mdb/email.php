<?php
include "header.inc.php";

if(isset($type)) {
	switch ($type) {
		case 1:
			$Query = mysql_query("SELECT first_name,last_name,email FROM $Table_contacts WHERE C_ID=\"$ID\"") or die(mysql_error());
			$A = mysql_fetch_object($Query);
			$AB = $A->email;
			$What = 3;
			break;

		case 2:
			$Query = mysql_query("SELECT * FROM $Table_groups WHERE G_ID=\"$ID\"") or die(mysql_error());
			$A = mysql_fetch_object($Query);
			$AB = $A->G_ID;
			$What = 4;
			break;

		case 3:
			mail($to_mail, $subject, $contents, "From: \"$YOU\" <$YOUR_EMAIL>\r\n");
				
			echo ("<div align=center><p>&nbsp;</p><p><font size=3><strong>eMail sent!</strong></font><br>You have successfully sent an email. The reply address on the eMail is $YOUR_EMAIL</p><p><a href=\"contacts.php?Sec=contacts\">Return to contact listing</a>");
			include "footer.inc.php";
			exit;
			break;

		case 4:
			$Get = mysql_query("SELECT * FROM $Table_contacts") or die(mysql_error());

			while ($row = mysql_fetch_row($Get)) {
				$exp = explode(":", $row[23]);

					if(in_array($to_mail, $exp)) {
						mail($row[6], $subject, $contents, "From: \"$YOU\" <$YOUR_EMAIL>\r\n");
					}
			}

			echo ("<div align=center><p>&nbsp;</p><p><font size=3><strong>Group eMail sent!</strong></font><br>You have successfully sent an email. The reply address on the eMail is $YOUR_EMAIL</p><p><a href=\"contacts.php?Sec=contacts\">Return to contact listing</a>");
			include "footer.inc.php";
			exit;
			break;

	} // end switch
} // if isset

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Email<br><br>");

?>

<table width="650" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td>
		   <form name="form1" method="post" action="">
		     <input type="hidden" name="type" value="<?= $What; ?>">
			 <input type="hidden" name="to_mail" value="<?= $AB; ?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="1">
                <tr> 
                  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                      <tr>
                        <td><table width="100%" border="0" cellpadding="4" cellspacing="0">
                            <tr bgcolor="#E3E3E3"> 
                              <td width="75">To:</td>
                              <td>
							  
							  <?php
								if ($type == 1) {
									print "$A->first_name $A->last_name ";
									print "<font color=\"#999999\" size=\"1\">< $A->email ></font>";
								}

								if ($type == 2) {
									echo $A->name . " Group <font color=\"#999999\" size=\"1\">< multiple in group ></font>";
								}
							   
							  ?>
							  
							  
							  </td>
                            </tr>
                            <tr bgcolor="#E3E3E3"> 
                              <td width="75">From:</td>
                              <td><?= $YOU . " <font color=\"#999999\" size=\"1\">< " . $YOUR_EMAIL . " ></font>" ?></td>
                            </tr>
                            <tr bgcolor="#E3E3E3"> 
                              <td>Subject:</td>
                              <td> <input name="subject" type="text" id="subject" size="69"></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td><hr size="1"></td>
                </tr>
                <tr> 
                  <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr valign="top"> 
                        <td width="75">Contents:</td>
                        <td> <textarea name="contents" cols="60" rows="14" id="contents"></textarea></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td><div align="center">
                      <input type="submit" name="Submit" value="   Send   ">
                    </div></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>

<?php

include "footer.inc.php";
?>