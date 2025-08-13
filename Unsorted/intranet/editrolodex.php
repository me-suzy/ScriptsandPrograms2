<html><head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
$appheaderstring='Rolodex';
include("header.php");
echo "<center><table width='95%' border='0' cellpadding='0' cellspacing='0'><tr><td>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";


if ($medit != 'yes') { $medit = 'no'; }
if ($medit == "no")
{
	if ($edittype == "edit")
			{
			dbconnect($dbusername,$dbuserpasswd);
			$result = mysql_query( "select * from rolodex where id = '$id'");

	    		while ($row = mysql_fetch_array($result)) {
				echo "<form name='fluxy' action='editrolodex.php' method='post'>";
				echo "<tr><td align='right'>First Name: </td><td><input type='text' size=10 name='firstname' value='", $row[firstname], "'></td></tr>";
				echo "<tr><td align='right'>Last Name: </td><td><input type='text' size=20 name='lastname' value='", $row[lastname], "'></td></tr>";
				echo "<tr><td align='right'>Title: </td><td><input type='text' size=35 name='title' value='", $row[title], "'></td></tr>";
				echo "<tr><td align='right'>Company: </td><td><input type='text' size=50 name='company' value='", $row[company], "'></td></tr>";
				echo "<tr><td align='right'>Address Line 1: </td><td><input type='text' size=50 name='address1' value='", $row[address1], "'></td></tr>";
				echo "<tr><td align='right'>Address Line 2: </td><td><input type='text' size=50 name='address2' value='", $row[address2], "'></td></tr>";
				echo "<tr><td align='right'>Address Line 3: </td><td><input type='text' size=50 name='address3' value='", $row[address3], "'></td></tr>";
				echo "<tr><td align='right'>City: </td><td><input type='text' name='city' size=50 value='", $row[city], "'></td></tr>";
				echo "<tr><td align='right'>State: </td><td><input type='text' name='state' size=2 maxlength=2 value='", $row[state], "'></td></tr>";
				echo "<tr><td align='right'>Zip Code: </td><td><input type='text' name='zipcode' size=5 maxlength=5 value='", $row[zipcode], "'>-<input type='text' size=4 maxlength=4 name='zipplus4' value='", $row[10], "'></td></tr>";
				echo "<tr><td align='right'>Phone: </td><td><input type='text' name='phone' size=20 value='", $row[phone], "'></td></tr>";
				echo "<tr><td align='right'>Alternate Phone: </td><td><input type='text' size=20 name='altphone' value='", $row[altphone], "'></td></tr>";
				echo "<tr><td align='right'>Alternate Phone Type: </td><td><input type='text' size=10 name='altphonetype' value='", $row[altphonetype], "'></td></tr>";
				echo "<tr><td align='right'>Fax: </td><td><input type='text' name='fax' size=20 value='", $row[fax], "'></td></tr>";
				echo "<tr><td align='right'>E-mail: </td><td><input type='text' name='email' size=50 value='", $row[email], "'></td></tr>";
				echo "<tr><td align='right'>Web Site: </td><td><input type='text' name='website' size=50 value='", $row[website], "'></td></tr>";
				echo "<tr><td align='right' valign='top'>Comments:</td>";
				echo "<td><textarea cols='50' rows='4' name='comment'>", $row[comment], "</textarea></td></tr>";
				echo "<input type='hidden' name='id' value='", $id, "'><input type='hidden' name='editby' value='", $setting[editby], "'>";
				echo "<tr><td align='right'>Media Contact: </td><td><select name='mediacontact'><option value='n'>No<option";
				if ($row[mediacontact] == 'y') { echo " selected"; }
				echo " value='y'>Yes</select></td></tr>";
				echo "<tr><td align='left'><input type='hidden' name='medit' value='yes'><input type='submit' value='Make Changes'></td></form>";
				echo "<td align='right'><form name='abort' action='rolodex.php' method='post'><td align='right'><input type='submit' value='Cancel'></td></tr></form>";
                                                               }
			  }
			  if ($edittype == "new") {
				echo "<form name='fluxy' action='editrolodex.php' method='post'>";
				echo "<tr><td align='right'>First Name: </td><td><input type='text' size=10 name='firstname' value=''></td></tr>";
				echo "<tr><td align='right'>Last Name: </td><td><input type='text' size=20 name='lastname' value=''></td></tr>";
				echo "<tr><td align='right'>Title: </td><td><input type='text' size=35 name='title' value=''></td></tr>";
				echo "<tr><td align='right'>Company: </td><td><input type='text' size=50 name='company' value=''></td></tr>";
				echo "<tr><td align='right'>Address Line 1: </td><td><input type='text' size=50 name='address1' value=''></td></tr>";
				echo "<tr><td align='right'>Address Line 2: </td><td><input type='text' size=50 name='address2' value=''></td></tr>";
				echo "<tr><td align='right'>Address Line 3: </td><td><input type='text' size=50 name='address3' value=''></td></tr>";
				echo "<tr><td align='right'>City: </td><td><input type='text' name='city' size=50 value=''></td></tr>";
				echo "<tr><td align='right'>State: </td><td><input type='text' name='state' size=2 maxlength=2 value='MO'></td></tr>";
				echo "<tr><td align='right'>Zip Code: </td><td><input type='text' name='zipcode' size=5 maxlength=5 value=''>-<input type='text' size=4 maxlength=4 name='zipplus4' value=''></td></tr>";
				echo "<tr><td align='right'>Phone: </td><td><input type='text' name='phone' size=20 value=''></td></tr>";
				echo "<tr><td align='right'>Alternate Phone: </td><td><input type='text' size=20 name='altphone' value=''></td></tr>";
				echo "<tr><td align='right'>Alternate Phone Type: </td><td><input type='text' size=10 name='altphonetype' value=''></td></tr>";
				echo "<tr><td align='right'>Fax: </td><td><input type='text' name='fax' size=20 value=''></td></tr>";
				echo "<tr><td align='right'>E-mail: </td><td><input type='text' name='email' size=50 value=''></td></tr>";
				echo "<tr><td align='right'>Web Site: </td><td><input type='text' name='website' size=50 value=''></td></tr>";
				echo "<tr><td align='right' valign='top'>Comments:</td>";
				echo "<td><textarea cols='50' rows='4' name='comment'></textarea></td></tr>";
				echo "<input type='hidden' name='id' value='", $id, "'>";
				echo "<tr><td align='right'>Media Contact: </td><td><select name='mediacontact'><option value='n'>No<option value='y'";
				echo ">Yes</select></td></tr>";
				echo "<tr><td align='left'><input type='hidden' name='editby' value='", $setting[8], "'><input type='hidden' name='medit' value='yes'><input type='hidden' name='edittype' value='new'><input type='submit' value='Add Record'></td></form>";
				echo "<td align='right'><form name='abort' action='rolodex.php' method='post'><td align='right'><input type='submit' value='Cancel'></td></tr></form>";
						}
} else {
dbconnect($dbusername,$dbuserpasswd);
	if ($edittype == "new") {
		$comment = nl2br($comment);
		if (substr($website,0, 7) != "http://" and strlen($website) > 1)
			{
                        $website = "http://" . $website;
			}
		mysql_query( "insert into rolodex (firstname, lastname, title, company, address1,
address2, address3, city, state, zipcode, zipplus4, phone, altphone, altphonetype,
email, website, comment, fax, editby, mediacontact, createdate)
values('$firstname', '$lastname', '$title', '$company', '$address1', '$address2', '$address3', '$city',
'$state', '$zipcode', '$zipplus4','$phone', '$altphone', '$altphonetype',
'$email', '$website', '$comment', '$fax', '$setting[login]', '$mediacontact', now() )");
	echo "<tr><td>You successfully added a record for ", $firstname, " ", $lastname, ".";
	echo "<p><form name='searchagain' method='post' action='rolodex.php'><input type='submit' value='Search'></form></td></tr>";

				} else {
		if (substr($website,0, 7) != "http://" and strlen($website) > 1)
			{
                        $website = "http://" . $website;
			}
	mysql_query( "update rolodex set firstname='$firstname', lastname='$lastname', title='$title', company='$company',
			address1='$address1', address2='$address2', address3='$address3',
			city='$city', state='$state', zipcode='$zipcode', zipplus4='$zipplus4',
			phone='$phone', email='$email', website='$website', comment='$comment',
			fax='$fax', altphone='$altphone', altphonetype='$altphonetype', editby='$setting[login]',
			mediacontact='$mediacontact' where id='$id'");
	echo "<tr><td>You successfully updated the record for ", $firstname, " ", $lastname, " (record #", $id,")";
	echo "<p><form name='searchagain' method='post' action='rolodex.php'><input type='submit' value='Search'></form></td></tr>";
					}
	}
?>
</table></td></tr></table></center></body></html>
<?php
	} ?>
