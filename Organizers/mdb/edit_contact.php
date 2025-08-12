<?php
function is_email_valid($email) { 
  if(eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$", $email)) return TRUE; 
  else return FALSE; 
}

include "header.inc.php";

if (isset($Mod)) {
	switch ($Mod) {
		case Y:
			$del = mysql_query("DELETE FROM $Table_contacts WHERE C_ID=\"$ID\"") or die(mysql_error());
			echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Delete Contact<br><br>");
			echo "<div align=center><p>&nbsp;</p><p><strong>Contact removed from listing</strong></p><a href=\"contacts.php?Sec=contacts\">Return to contact listing</a></div>";
			
			break;

		case E:
			$QC = mysql_query("SELECT * FROM $Table_contacts WHERE C_ID=\"$ID\"") or die(mysql_error());			
			$F = mysql_fetch_object($QC);

			$G = mysql_query("SELECT * FROM $Table_groups") or die(mysql_error());

			echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > <a href=\"$HTTP_REFERER\">Show Contact</a> > Edit Contact<br><br>");

			?>

			<table width="655" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
			  <tr>
				<td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#E3E3E3">
					<tr>
					  <td>
					    <form name="form1" method="post" action="">
						<input type="hidden" name="Mod" value="S">
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
							  <td><input name="first_name" type="text" id="first_name" maxlength="36" value="<?= $F->first_name; ?>"></td>
							  <td width="130">Last Name:</td>
							  <td><input name="last_name" type="text" id="last_name" maxlength="36" value="<?= $F->last_name; ?>"></td>
							</tr>
							<tr> 
							  <td>BirthDay:</td>
							  <td><input name="birthday" type="text" id="birthday" maxlength="36" value="<?= $F->birthday; ?>"></td>
							  <td>Title:</td>
							  <td><input name="title" type="text" id="title" maxlength="36" value="<?= $F->title; ?>"></td>
							</tr>
							<tr> 
							  <td>Company Name:</td>
							  <td colspan="2"><input name="company" type="text" id="company" size="36" maxlength="120" value="<?= $F->company; ?>"></td>
							  <td>
								<select name="group" id="group">
								  <option value="0" selected>No Group</option>

									<?php
										while ($group = mysql_fetch_object($G)) {
											if ($group->G_ID == $F->group_num) {
												echo ("<option value=\"$group->G_ID\" selected>$group->name</option>\n");
											} else {
												echo ("<option value=\"$group->G_ID\">$group->name</option>\n");
											}
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
							  <td><input name="email" type="text" id="email" value="<?= $F->email; ?>"></td>
							  <td>Home Phone:</td>
							  <td><input name="home_phone" type="text" id="home_phone" value="<?= $F->home_phone; ?>"></td>
							</tr>
							<tr> 
							  <td>ICQ:</td>
							  <td><input name="icq" type="text" id="icq" value="<?= $F->icq; ?>"></td>
							  <td>Work Phone:</td>
							  <td><input name="work_phone" type="text" id="work_phone" value="<?= $F->work_phone; ?>"></td>
							</tr>
							<tr> 
							  <td>MSN:</td>
							  <td><input name="msn" type="text" id="msn" value="<?= $F->msn; ?>"></td>
							  <td>Other Phone:</td>
							  <td><input name="other_phone" type="text" id="other_phone" value="<?= $F->other_phone; ?>"></td>
							</tr>
							<tr> 
							  <td>Yahoo:</td>
							  <td><input name="yahoo" type="text" id="yahoo" value="<?= $F->yahoo; ?>"></td>
							  <td>Cell Phone:</td>
							  <td><input name="cell_phone" type="text" id="cell_phone" value="<?= $F->cell_phone; ?>"></td>
							</tr>
							<tr> 
							  <td>AIM:</td>
							  <td><input name="aim" type="text" id="aim" value="<?= $F->aim; ?>"></td>
							  <td>Pager:</td>
							  <td><input name="pager" type="text" id="pager" value="<?= $F->pager; ?>"></td>
							</tr>
							<tr> 
							  <td>WebSite:</td>
							  <td colspan="3"><input name="website" type="text" id="website" value="<?= $F->website; ?>" size="72"></td>
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
							  <td colspan="2"><input name="street" type="text" id="street" size="48" value="<?= $F->street; ?>"></td>
							  <td>&nbsp;</td>
							</tr>
							<tr> 
							  <td>City:</td>
							  <td><input name="city" type="text" id="city" value="<?= $F->city; ?>"></td>
							  <td>State:</td>
							  <td><input name="state" type="text" id="state" value="<?= $F->state; ?>"></td>
							</tr>
							<tr> 
							  <td>Country:</td>
							  <td><input name="country" type="text" id="country" value="<?= $F->country; ?>"></td>
							  <td>Zip code:</td>
							  <td><input name="zip" type="text" id="zip" value="<?= $F->zip; ?>"></td>
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
								  <textarea name="notes" cols="75" rows="6" id="notes"><?=$F->notes;?></textarea>
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
								  <input type="submit" name="Submit" value="  Edit Contact  ">
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
			break;


		case S:
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

				if(isset($email)) {
					if (is_email_valid($email)) { }
					else {
						echo "<div align=center><p>&nbsp;</p><p><font size=3><strong>oops!</strong></font><br>Your email address is invalid.<br>Please go back and Check the email address to mkae sure it is correct.</p><p><a href=\"$HTTP_REFERER\">Go Back</a></p><p>&nbsp;</p>";
						include "footer.inc.php";
						exit;
					}
				}

				$insert = mysql_query("UPDATE $Table_contacts SET first_name=\"$info[first_name]\", last_name=\"$info[last_name]\", birthday=\"$info[birthday]\", title=\"$info[title]\", company=\"$info[company]\", email=\"$info[email]\", home_phone=\"$info[home_phone]\", icq=\"$info[icq]\", work_phone=\"$info[work_phone]\", msn=\"$info[msn]\", other_phone=\"$info[other_phone]\", yahoo=\"$info[yahoo]\", cell_phone=\"$info[cell_phone]\", aim=\"$info[aim]\", pager=\"$info[pager]\", website=\"$info[website]\", street=\"$info[street]\", city=\"$info[city]\", state=\"$info[state]\", country=\"$info[country]\", zip=\"$info[zip]\", notes=\"$info[notes]\", group_num=\"$group\" WHERE C_ID=\"$ID\"") or die(mysql_error());

				echo ("<div align=center><p>&nbsp;</p><p><font size=3><strong>Changes made to $info[first_name] have been recorded</strong></font><br>You have successfully altered a contact.</p><p><a href=\"contacts.php?Sec=contacts\">Return to contact listing</a>");
			break;

	}
include "footer.inc.php";
exit;
}

$Get = mysql_query("SELECT * FROM $Table_contacts WHERE C_ID=\"$ID\"") or die(mysql_error());
$array = mysql_fetch_object($Get);

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Show Contact<br><br>");

?>

<table width="655" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
        <tr> 
          <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr> 
                <td><font size="3"><strong>&nbsp;&nbsp;<?= $array->first_name; ?> <?= $array->last_name; ?></strong></font></td>
                <td width="250">
				  <div align="right">
					<a href="print.php?Sec=contacts&ID=<?= $ID; ?>"><img src="images/printer.gif" alt="Print <?= $array->first_name; ?> <?= $array->last_name; ?>"  width="25" height="25" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="email.php?Sec=contacts&ID=<?= $ID; ?>&type=1"><img src="images/email.gif" alt="Email <?= $array->first_name; ?> <?= $array->last_name; ?>"  border="0"></a>&nbsp;&nbsp;
					<a href="edit_contact.php?Sec=contacts&ID=<?= $ID; ?>&Mod=E"><img src="images/edit_contact.gif" alt="Edit <?= $array->first_name; ?> <?= $array->last_name; ?>" border="0"></a>&nbsp;&nbsp;
                    <a href="edit_contact.php?Sec=contacts&ID=<?= $ID; ?>&Mod=Y"><img src="images/trash.gif" alt="Delete <?= $array->first_name; ?> <?= $array->last_name; ?>" width="39" height="33" border="0"></a>&nbsp;&nbsp;</div></td>
              </tr>
            </table>
            <br>
            <table width="95%" border="0" align="center" cellpadding="1" cellspacing="0">
              <tr> 
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Personal Information </div></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr> 
                                  <td width="125">First Name:</td>
                                  <td width="175"><em><?= $array->first_name; ?></em></td>
                                  <td width="125">Last Name:</td>
                                  <td><em><?= $array->last_name; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>Birthday:</td>
                                  <td><em><?= $array->birthday; ?></em></td>
                                  <td>Title:</td>
                                  <td><em><?= $array->title; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>Company Name:</td>
                                  <td colspan="2"><em><?= $array->company; ?></em></td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Contact Information </div></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr> 
                                  <td width="125">Email Address:</td>
                                  <td width="175"><em><?= $array->email; ?></em></td>
                                  <td width="125">Home Phone:</td>
                                  <td><em><?= $array->home_phone; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>ICQ:</td>
                                  <td><em><?= $array->icq; ?></em></td>
                                  <td>Work Phone:</td>
                                  <td><em><?= $array->work_phone; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>MSN:</td>
                                  <td><em><?= $array->msn; ?></em></td>
                                  <td>Other Phone:</td>
                                  <td><em><?= $array->other_phone; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>Yahoo:</td>
                                  <td><em><?= $array->yahoo; ?></em></td>
                                  <td>Cell Phone:</td>
                                  <td><em><?= $array->cell_phone; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>AIM:</td>
                                  <td><em><?= $array->aim; ?></em></td>
                                  <td>Pager:</td>
                                  <td><em><?= $array->pager; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>WebSite:</td>
                                  <td colspan="3"><em><?= $array->website; ?></em></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Physical Address </div></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr> 
                                  <td width="125">Street:</td>
                                  <td colspan="3"><em><?= $array->street; ?></em></td>
                                </tr>
                                <tr> 
                                  <td width="125">City:</td>
                                  <td width="175"><em><?= $array->city; ?></em></td>
                                  <td width="125">State:</td>
                                  <td><em><?= $array->state; ?></em></td>
                                </tr>
                                <tr> 
                                  <td>Country:</td>
                                  <td><em><?= $array->country; ?></em></td>
                                  <td>Zip Code:</td>
                                  <td><em><?= $array->zip; ?></em></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Notes</div></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="8" cellpadding="8">
                                <tr>
                                  <td bgcolor="#F0F0F0"><em><?= nl2br($array->notes); ?></em></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
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