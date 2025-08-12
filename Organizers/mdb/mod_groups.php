<?php
if (isset($delete)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Job = mysql_query("SELECT * FROM $Table_contacts") or die(mysql_error());

	while ($J = mysql_fetch_object($Job)) {
		$Sub = $J->group_num;
		$removed = ereg_replace(":".$ID,"", $Sub);
		$Rem = mysql_query("UPDATE $Table_contacts SET group_num=\"$removed\" WHERE C_ID=\"$J->C_ID\"") or die(mysql_error());
	}

	$Delete = mysql_query("DELETE FROM $Table_groups WHERE G_ID=\"$ID\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
}

include "header.inc.php";
$Query1 = mysql_query("SELECT * FROM $Table_groups") or die(mysql_error());

if (isset($Submit)) {
	if($group == "" || $group == " ") {
		echo ("<div align=center><font color=red><strong>Invalid group name given.</strong></font></div><br>");
		$set = 2;
	}
	
	while ($Check = mysql_fetch_array($Query1)) {
		if ($Check[name] == $group) {
			echo ("<div align=center><font color=red><strong>Group already exists.</strong></font></div><br>");
			$set = 1;
		}
	}

	if(!isset($set)) {
		$group = stripslashes(htmlspecialchars($group));
		$insert = mysql_query("INSERT INTO $Table_groups SET name=\"$group\"") or die(mysql_error());
	}
}

$Query = mysql_query("SELECT * FROM $Table_groups") or die(mysql_error());

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Manage Groups<br><br>");

?>

<table width="550" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr> 
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr> 
                            <td class="Title"><div align="center">Manage Groups</div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><form name="form1" method="post" action="">
                    <div align="center">Add New Group <input name="group" type="text" id="group" size="20" maxlength="36">
                      <input type="submit" name="Submit" value="  Add Group  "><br>
                    </div>
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="350" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                    <tr>
                      <td><table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
                          <tr> 
                            <td colspan="2" class="Title"><div align="center">Current Groups</div></td>
                          </tr>

							<?php
							
							$Count = mysql_num_rows($Query);

							if ($Count == 0) {
								echo ("<tr bgcolor=\"#FFFFFF\"><td colspan=2><div align=center><strong>No categories found.</strong></div></td></tr>");

							}

								while ($Row = mysql_fetch_object($Query)) {
									?>
									  <tr bgcolor="#FFFFFF"> 
										<td width="50%"> 
										  <div align="right"><?= $Row->name; ?> </div></td>
										<td>&nbsp;&nbsp;<font size="1" color="#999999">( <a href="mod_groups.php?delete=yes&ID=<?= $Row->G_ID; ?>" class="MiniLink">Delete</a> )</font></td>
									  </tr>
									<?php
								}
							?>

                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>


<?php

include "footer.inc.php";
?>