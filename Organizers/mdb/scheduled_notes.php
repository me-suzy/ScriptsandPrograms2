<?php

if (isset($Delete)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$insert = mysql_query("DELETE FROM $Table_scheduled_notes WHERE SN_ID=\"$Delete\"") or die(mysql_error());
	
	mysql_close($CONNECT);
	header("location: scheduled_notes.php?Sec=schedule");
}

include "header.inc.php";

$Query = mysql_query("SELECT *,DATE_FORMAT(date, '%M %D, %Y')as new_date FROM $Table_scheduled_notes ORDER BY SN_ID DESC") or die(mysql_error());
$Counted = mysql_num_rows($Query);

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Scheduled Notes<br><br>");
?>

<table width="95%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000" align="center">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/scheduled.gif"><br><table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td class="Title" style="padding='3'">&nbsp;&nbsp;Sceduled Notes</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

<?php

	if ($Counted == 0) {
		echo ("<tr><td align=center><strong>No scheduled notes found</strong></td></tr>");
	}

	while($R=mysql_fetch_object($Query)) {


?>


			  <tr>
                <td>
				  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr> 
                            <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr valign="top"> 

                                  <td width="120"><strong><?= $R->new_date; ?></strong></td>

                                  <td>&nbsp;<font size="1"><?= nl2br($R->note); ?></font></td>

                                  <td width="22"> <div align="center"><a href="scheduled_notes.php?Sec=schedule&Delete=<?= $R->SN_ID; ?>"><img src="images/delete.gif" alt="Delete Task" width="16" height="16" border="0"></a></div></td>

                                </tr>
                              </table>
							</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
				</td>
              </tr>

<?php
}

?>


              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr> 
                            <td height="10"><strong>&nbsp;</font></strong></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>


<?php


include "footer.inc.php";
?>