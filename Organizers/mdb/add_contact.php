<?php
function is_email_valid($email) { 
  if(eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$", $email)) return TRUE; 
  else return FALSE; 
}

include "header.inc.php";

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Add contact<br><br>");

if (isset($Submit)) {
	if ($first_name == "" || $first_name == " ") {
		echo "<div align=center><p>&nbsp;</p><p><font size=3><strong>oops!</strong></font><br>Your submission was empty.<br>Please go back and at least give the person a name.</p><p><a href=\"$HTTP_REFERER\">Go Back</a></p><p>&nbsp;</p>";
		include "footer.inc.php";
		exit;
	}



	$info = array	(
					'first_name'	=> $first_name,
					'last_name'		=> $last_name,
					'birthday'		=> $birthday,
					'title'			=> $title,
					'company'		=> $company,
					'email'			=> $email,
					'home_phone'	=> $home_phone,
					'icq'			=> $icq,
					'work_phone'	=> $work_phone,
					'msn'			=> $msn,
					'other_phone'	=> $other_phone,
					'yahoo'			=> $yahoo,
					'cell_phone'	=> $cell_phone,
					'aim'			=> $aim,
					'pager'			=> $pager,
					'website'		=> $website,
					'street'		=> $street,
					'city'			=> $city,
					'state'			=> $state,
					'country'		=> $country,
					'zip'			=> $zip,
					'notes'			=> $notes,
					'group'			=> $group
					);

        foreach ($info as $key=>$value) {
           $info_value = stripslashes(htmlspecialchars($value));
           $info[$key] = $info_value;
        }

		if($email != "") {
			if (is_email_valid($email)) { }
			else {
				echo "<div align=center><p>&nbsp;</p><p><font size=3><strong>oops!</strong></font><br>Your email address is invalid.<br>Please go back and Check the email address to mkae sure it is correct.</p><p><a href=\"$HTTP_REFERER\">Go Back</a></p><p>&nbsp;</p>";
				include "footer.inc.php";
				exit;
			}
		}

		
		$insert = mysql_query("INSERT INTO $Table_contacts SET first_name=\"$info[first_name]\", last_name=\"$info[last_name]\", birthday=\"$info[birthday]\", title=\"$info[title]\", company=\"$info[company]\", email=\"$info[email]\", home_phone=\"$info[home_phone]\", icq=\"$info[icq]\", work_phone=\"$info[work_phone]\", msn=\"$info[msn]\", other_phone=\"$info[other_phone]\", yahoo=\"$info[yahoo]\", cell_phone=\"$info[cell_phone]\", aim=\"$info[aim]\", pager=\"$info[pager]\", website=\"$info[website]\", street=\"$info[street]\", city=\"$info[city]\", state=\"$info[state]\", country=\"$info[country]\", zip=\"$info[zip]\", notes=\"$info[notes]\", group_num=\"$group\"") or die(mysql_error());

		echo ("<div align=center><p>&nbsp;</p><p><font size=3><strong>$info[first_name] Added!</strong></font><br>You have successfully added a new contact.</p><p><a href=\"contacts.php?Sec=contacts\">Return to contact listing</a>");

		include "footer.inc.php";
		exit;
}

$G = mysql_query("SELECT * FROM $Table_groups") or die(mysql_error());

?>

<table width="655" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#E3E3E3">
        <tr>
          <td><form name="form1" method="post" action="">
              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr bgcolor="#000000"> 
                  <td colspan="4"> <div align="center"> 
                      <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                        <tr> 
                          <td class="Title"><div align="center" style="padding='3px'">Personal Information </div></td>
                        </tr>
                      </table>
                    </div></td>
                </tr>
                <tr> 
                  <td width="150"><strong>First Name:</strong></td>
                  <td><input name="first_name" type="text" id="first_name" maxlength="36"></td>
                  <td width="130">Last Name:</td>
                  <td><input name="last_name" type="text" id="last_name" maxlength="36"></td>
                </tr>
                <tr> 
                  <td>BirthDay:</td>
                  <td><input name="birthday" type="text" id="birthday" maxlength="36"></td>
                  <td>Title:</td>
                  <td><input name="title" type="text" id="title" maxlength="36"></td>
                </tr>
                <tr> 
                  <td>Company Name:</td>
                  <td colspan="2"><input name="company" type="text" id="company" size="36" maxlength="120"></td>
                  <td>
				    <select name="group" id="group">
                      <option value="0" selected>No Group</option>

						<?php
							while ($group = mysql_fetch_object($G)) {
								echo ("<option value=\"$group->G_ID\">$group->name</option>\n");
							}
						?>

                    </select>
				  </td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr bgcolor="#000000"> 
                  <td colspan="4"><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                      <tr> 
                        <td class="Title"><div align="center" style="padding='3px'">Contact Information</div></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td>Email Address:</td>
                  <td><input name="email" type="text" id="email"></td>
                  <td>Home Phone:</td>
                  <td><input name="home_phone" type="text" id="home_phone"></td>
                </tr>
                <tr> 
                  <td>ICQ:</td>
                  <td><input name="icq" type="text" id="icq"></td>
                  <td>Work Phone:</td>
                  <td><input name="work_phone" type="text" id="work_phone"></td>
                </tr>
                <tr> 
                  <td>MSN:</td>
                  <td><input name="msn" type="text" id="msn"></td>
                  <td>Other Phone:</td>
                  <td><input name="other_phone" type="text" id="other_phone"></td>
                </tr>
                <tr> 
                  <td>Yahoo:</td>
                  <td><input name="yahoo" type="text" id="yahoo"></td>
                  <td>Cell Phone:</td>
                  <td><input name="cell_phone" type="text" id="cell_phone"></td>
                </tr>
                <tr> 
                  <td>AIM:</td>
                  <td><input name="aim" type="text" id="aim"></td>
                  <td>Pager:</td>
                  <td><input name="pager" type="text" id="pager"></td>
                </tr>
                <tr> 
                  <td>WebSite:</td>
                  <td colspan="3"><input name="website" type="text" id="website" value="http://" size="72"></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr bgcolor="#000000"> 
                  <td colspan="4"><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                      <tr> 
                        <td class="Title"><div align="center" style="padding='3px'">Physical Address</div></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td>Street:</td>
                  <td colspan="2"><input name="street" type="text" id="street" size="48"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td>City:</td>
                  <td><input name="city" type="text" id="city"></td>
                  <td>State:</td>
                  <td><input name="state" type="text" id="state"></td>
                </tr>
                <tr> 
                  <td>Country:</td>
                  <td><input name="country" type="text" id="country"></td>
                  <td>Zip code:</td>
                  <td><input name="zip" type="text" id="zip"></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr bgcolor="#000000"> 
                  <td colspan="4"><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                      <tr> 
                        <td class="Title"><div align="center" style="padding='3px'">Notes</div></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td colspan="4"><div align="center"> 
                      <textarea name="notes" cols="75" rows="6" id="notes"></textarea>
                    </div></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td colspan="2"> <div align="center"> 
                      <input type="submit" name="Submit" value="  Add Contact  ">
                    </div></td>
                  <td>&nbsp;</td>
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